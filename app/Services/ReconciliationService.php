<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\LedgerEntry;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class ReconciliationService
{
    /**
     * Fatura ve ledger hareketleri tutarlılığını kontrol et
     */
    public function checkInvoices(): array
    {
        $errors = collect();
        $warnings = collect();
        $ok = collect();

        // Tüm faturaları (müşteri ve ödemeler ile) al
        $invoices = Invoice::with([
            'customer:id,name',
            'payments:id,invoice_id,amount'
        ])->get();

        // Ledger toplamlarını (fatura ile ilişkili hareketler) önceden hesapla
        $ledgerTotals = DB::table('ledger_entries')
            ->select(DB::raw('reference_id as invoice_id'),
                DB::raw('SUM(CASE WHEN type = "debit" THEN amount ELSE 0 END) as total_debit'),
                DB::raw('SUM(CASE WHEN type = "credit" THEN amount ELSE 0 END) as total_credit'))
            ->where('reference_type', 'App\\Models\\Invoice')
            ->groupBy('reference_id')
            ->get()
            ->keyBy('invoice_id');

        // Payment ledger toplamlarını (payment ile ilişkili hareketler) önceden hesapla
        $paymentLedgerTotals = DB::table('ledger_entries')
            ->join('payments', 'ledger_entries.related_id', '=', 'payments.id')
            ->select('payments.invoice_id',
                DB::raw('SUM(CASE WHEN ledger_entries.type = "credit" THEN ledger_entries.amount ELSE 0 END) as total_credit'))
            ->where('ledger_entries.related_type', 'App\\Models\\Payment')
            ->groupBy('payments.invoice_id')
            ->get()
            ->keyBy('invoice_id');

        $paymentTotals = DB::table('payments')
            ->select('invoice_id', 
                DB::raw('SUM(amount) as total_amount'))
            ->groupBy('invoice_id')
            ->get()
            ->keyBy('invoice_id');

        foreach ($invoices as $invoice) {
            $ledgerTotal = $ledgerTotals->get($invoice->id);
            $paymentLedgerTotal = $paymentLedgerTotals->get($invoice->id);
            $paymentTotal = $paymentTotals->get($invoice->id);
            
            $totalDebit = $ledgerTotal ? $ledgerTotal->total_debit : 0;
            $totalCredit = $ledgerTotal ? $ledgerTotal->total_credit : 0;
            $paymentCredit = $paymentLedgerTotal ? $paymentLedgerTotal->total_credit : 0;
            $netLedger = $totalCredit - $totalDebit;
            $totalPayments = $paymentTotal ? $paymentTotal->total_amount : 0;

            switch ($invoice->status->value) {
                case 'draft':
                    // Draft faturalarda ödeme olmamalı
                    if ($totalCredit > 0) {
                        $warnings->push([
                            'type' => 'warning',
                            'invoice_id' => $invoice->id,
                            'customer_id' => $invoice->customer_id,
                            'invoice_number' => $invoice->invoice_number ?? '#' . $invoice->id,
                            'customer_name' => $invoice->customer->name ?? 'Bilinmeyen',
                            'status' => $invoice->status->value,
                            'expected' => 0,
                            'actual' => $totalCredit,
                            'difference' => $totalCredit,
                            'description' => 'Draft faturada ödeme var - durum güncellenmeli',
                            'category' => 'draft_with_payment'
                        ]);
                    } else {
                        $ok->push([
                            'type' => 'ok',
                            'invoice_id' => $invoice->id,
                            'customer_id' => $invoice->customer_id,
                            'invoice_number' => $invoice->invoice_number ?? '#' . $invoice->id,
                            'customer_name' => $invoice->customer->name ?? 'Bilinmeyen',
                            'status' => $invoice->status->value,
                            'expected' => 0,
                            'actual' => $totalCredit,
                            'difference' => 0,
                            'description' => 'Draft fatura - ledger tutarlı',
                            'category' => 'draft_consistent'
                        ]);
                    }
                    break;

                case 'sent':
                case 'overdue':
                    if (abs($totalDebit - $invoice->total) > 0.01) {
                        $errors->push([
                            'type' => 'error',
                            'invoice_id' => $invoice->id,
                            'customer_id' => $invoice->customer_id,
                            'invoice_number' => $invoice->invoice_number ?? '#' . $invoice->id,
                            'customer_name' => $invoice->customer->name ?? 'Bilinmeyen',
                            'status' => $invoice->status->value,
                            'expected' => $invoice->total,
                            'actual' => $totalDebit,
                            'difference' => $invoice->total - $totalDebit,
                            'description' => 'Sent/Overdue faturada ledger debit toplamı fatura tutarına eşit olmalı',
                            'category' => 'missing_debit'
                        ]);
                    } else {
                        $ok->push([
                            'type' => 'ok',
                            'invoice_id' => $invoice->id,
                            'customer_id' => $invoice->customer_id,
                            'invoice_number' => $invoice->invoice_number ?? '#' . $invoice->id,
                            'customer_name' => $invoice->customer->name ?? 'Bilinmeyen',
                            'status' => $invoice->status->value,
                            'expected' => $invoice->total,
                            'actual' => $totalDebit,
                            'difference' => 0,
                            'description' => 'Sent/Overdue fatura - ledger tutarlı',
                            'category' => 'sent_consistent'
                        ]);
                    }
                    break;

                case 'paid':
                    // Paid faturalar için payment credit toplamını kontrol et
                    if (abs($paymentCredit - $invoice->total) > 0.01) {
                        if ($paymentCredit > $invoice->total) {
                            $errors->push([
                                'type' => 'error',
                                'invoice_id' => $invoice->id,
                                'customer_id' => $invoice->customer_id,
                                'invoice_number' => $invoice->invoice_number ?? '#' . $invoice->id,
                                'customer_name' => $invoice->customer->name ?? 'Bilinmeyen',
                                'status' => $invoice->status->value,
                                'expected' => $invoice->total,
                                'actual' => $paymentCredit,
                                'difference' => $paymentCredit - $invoice->total,
                                'description' => 'Paid faturada payment credit toplamı fatura tutarından fazla (overpaid)',
                                'category' => 'overpaid'
                            ]);
                        } else {
                            $errors->push([
                                'type' => 'error',
                                'invoice_id' => $invoice->id,
                                'customer_id' => $invoice->customer_id,
                                'invoice_number' => $invoice->invoice_number ?? '#' . $invoice->id,
                                'customer_name' => $invoice->customer->name ?? 'Bilinmeyen',
                                'status' => $invoice->status->value,
                                'expected' => $invoice->total,
                                'actual' => $paymentCredit,
                                'difference' => $invoice->total - $paymentCredit,
                                'description' => 'Paid faturada payment credit toplamı fatura tutarından eksik',
                                'category' => 'missing_credit'
                            ]);
                        }
                    }
                    
                    // Payments ile ledger tutarlılığını kontrol et
                    if (abs($totalPayments - $paymentCredit) > 0.01) {
                        $errors->push([
                            'type' => 'error',
                            'invoice_id' => $invoice->id,
                            'customer_id' => $invoice->customer_id,
                            'invoice_number' => $invoice->invoice_number ?? '#' . $invoice->id,
                            'customer_name' => $invoice->customer->name ?? 'Bilinmeyen',
                            'status' => $invoice->status->value,
                            'expected' => $totalPayments,
                            'actual' => $paymentCredit,
                            'difference' => $totalPayments - $paymentCredit,
                            'description' => 'Payments ile ledger credit tutarlılığı yok',
                            'category' => 'payments_ledger_mismatch'
                        ]);
                    } else {
                        $ok->push([
                            'type' => 'ok',
                            'invoice_id' => $invoice->id,
                            'customer_id' => $invoice->customer_id,
                            'invoice_number' => $invoice->invoice_number ?? '#' . $invoice->id,
                            'customer_name' => $invoice->customer->name ?? 'Bilinmeyen',
                            'status' => $invoice->status->value,
                            'expected' => $invoice->total,
                            'actual' => $paymentCredit,
                            'difference' => 0,
                            'description' => 'Paid fatura - ledger ve payments tutarlı',
                            'category' => 'paid_consistent'
                        ]);
                    }
                    break;

                case 'cancelled':
                    if (abs($netLedger) > 0.01) {
                        $errors->push([
                            'type' => 'error',
                            'invoice_id' => $invoice->id,
                            'customer_id' => $invoice->customer_id,
                            'invoice_number' => $invoice->invoice_number ?? '#' . $invoice->id,
                            'customer_name' => $invoice->customer->name ?? 'Bilinmeyen',
                            'status' => $invoice->status->value,
                            'expected' => 0,
                            'actual' => $netLedger,
                            'difference' => $netLedger,
                            'description' => 'Cancelled faturada ledger net tutarı 0 olmalı',
                            'category' => 'cancelled_not_zero'
                        ]);
                    } else {
                        $ok->push([
                            'type' => 'ok',
                            'invoice_id' => $invoice->id,
                            'customer_id' => $invoice->customer_id,
                            'invoice_number' => $invoice->invoice_number ?? '#' . $invoice->id,
                            'customer_name' => $invoice->customer->name ?? 'Bilinmeyen',
                            'status' => $invoice->status->value,
                            'expected' => 0,
                            'actual' => $netLedger,
                            'difference' => 0,
                            'description' => 'Cancelled fatura - ledger net 0',
                            'category' => 'cancelled_consistent'
                        ]);
                    }
                    break;

                default:
                    $warnings->push([
                        'type' => 'warning',
                        'invoice_id' => $invoice->id,
                        'customer_id' => $invoice->customer_id,
                        'invoice_number' => $invoice->invoice_number ?? '#' . $invoice->id,
                        'customer_name' => $invoice->customer->name ?? 'Bilinmeyen',
                        'status' => $invoice->status->value,
                        'expected' => 'Bilinmiyor',
                        'actual' => $netLedger,
                        'difference' => 'Bilinmiyor',
                        'description' => 'Bilinmeyen fatura durumu',
                        'category' => 'unknown_status'
                    ]);
                    break;
            }
        }

        return [
            'errors' => $errors,
            'warnings' => $warnings,
            'ok' => $ok
        ];
    }

    /**
     * Müşteri bakiyelerini kontrol et - Optimize edilmiş sorgu
     */
    public function checkCustomerBalances(): Collection
    {
        return DB::table('customers')
            ->select([
                'customers.id',
                'customers.name',
                'customers.email',
                'customers.is_active'
            ])
            ->selectRaw('
                COALESCE(SUM(invoices.total), 0) as total_invoice_amount,
                COALESCE(SUM(CASE WHEN invoices.status IN ("sent", "overdue") THEN invoices.total ELSE 0 END), 0) as outstanding_amount,
                COALESCE(SUM(CASE WHEN invoices.status = "paid" THEN invoices.total ELSE 0 END), 0) as paid_amount
            ')
            ->selectRaw('
                COALESCE(SUM(CASE WHEN ledger_entries.type = "credit" THEN ledger_entries.amount ELSE 0 END), 0) as total_credit,
                COALESCE(SUM(CASE WHEN ledger_entries.type = "debit" THEN ledger_entries.amount ELSE 0 END), 0) as total_debit
            ')
            ->leftJoin('invoices', 'customers.id', '=', 'invoices.customer_id')
            ->leftJoin('ledger_entries', 'customers.id', '=', 'ledger_entries.customer_id')
            ->groupBy('customers.id', 'customers.name', 'customers.email', 'customers.is_active')
            ->get()
            ->map(function ($customer) {
                $netLedger = $customer->total_debit - $customer->total_credit; // Borç - Alacak = Borç Bakiyesi
                $expectedBalance = $customer->outstanding_amount; // Sadece ödenmemiş faturalar
                $difference = $netLedger - $expectedBalance; // Gerçek bakiye - Beklenen bakiye

                return [
                    'customer_id' => $customer->id,
                    'customer_name' => $customer->name,
                    'customer_email' => $customer->email,
                    'is_active' => $customer->is_active,
                    'total_invoice_amount' => $customer->total_invoice_amount,
                    'outstanding_amount' => $customer->outstanding_amount,
                    'paid_amount' => $customer->paid_amount,
                    'total_credit' => $customer->total_credit,
                    'total_debit' => $customer->total_debit,
                    'net_ledger' => $netLedger,
                    'expected_balance' => $expectedBalance,
                    'difference' => $difference,
                    'status' => abs($difference) < 0.01 ? 'balanced' : 'unbalanced'
                ];
            });
    }

    /**
     * Tüm faturaların durumlarını senkronize et
     */
    public function syncInvoiceStatuses(): array
    {
        $updated = 0;
        $errors = [];
        
        try {
            // Tüm faturaları al
            $invoices = Invoice::with(['payments'])->get();
            
            foreach ($invoices as $invoice) {
                $remainingAmount = $invoice->remaining_amount;
                $currentStatus = $invoice->status->value;
                $newStatus = null;
                
                // Durum belirleme mantığı
                if ($remainingAmount <= 0) {
                    // Tamamen ödendi
                    $newStatus = 'paid';
                } elseif ($remainingAmount < $invoice->total) {
                    // Kısmen ödendi
                    $newStatus = 'sent';
                } else {
                    // Hiç ödenmemiş
                    if ($currentStatus === 'draft') {
                        $newStatus = 'draft';
                    } else {
                        $newStatus = 'sent';
                    }
                }
                
                // Durum değişikliği varsa güncelle
                if ($newStatus !== $currentStatus) {
                    $invoice->update([
                        'status' => $newStatus,
                        'paid_at' => $newStatus === 'paid' ? ($invoice->payments()->latest()->first()?->payment_date ?? now()) : null
                    ]);
                    $updated++;
                }
            }
            
            // Cache'i temizle
            cache()->forget('reconciliation_data');
            
            return [
                'success' => true,
                'updated_count' => $updated,
                'message' => "{$updated} fatura durumu güncellendi."
            ];
            
        } catch (\Exception $e) {
            \Log::error('Invoice status sync failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'updated_count' => $updated,
                'message' => 'Durum güncelleme sırasında hata oluştu: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Genel mutabakat özeti - Cache kullanarak optimize edildi
     */
    public function getSummary(): array
    {
        // Özet verilerini cache'den al
        return cache()->remember('reconciliation_summary', 1800, function () { // 30 dakika cache
            $invoiceCheck = $this->checkInvoices();
            $customerBalances = $this->checkCustomerBalances();

            $balancedCustomers = $customerBalances->where('status', 'balanced')->count();
            $unbalancedCustomers = $customerBalances->where('status', 'unbalanced')->count();

            return [
                'invoice_errors' => $invoiceCheck['errors']->count(),
                'invoice_warnings' => $invoiceCheck['warnings']->count(),
                'invoice_ok' => $invoiceCheck['ok']->count(),
                'balanced_customers' => $balancedCustomers,
                'unbalanced_customers' => $unbalancedCustomers,
                'total_customers' => $customerBalances->count(),
                'total_invoices' => Invoice::count(),
                'total_ledger_entries' => LedgerEntry::count(),
            ];
        });
    }
}
