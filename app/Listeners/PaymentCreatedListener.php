<?php

namespace App\Listeners;

use App\Events\PaymentCreated;
use App\Models\AccountingLedgerEntry;
use App\Models\Invoice;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class PaymentCreatedListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(PaymentCreated $event): void
    {
        $payment = $event->payment;

        // 1. Muhasebe ledger entry oluştur (idempotent)
        $this->createAccountingEntry($payment);

        // 2. Fatura varsa güncelle (ledger entry oluşturulduktan sonra)
        if ($payment->invoice_id) {
            $this->updateInvoiceStatus($payment);
        }
        
        // 3. Müşteri bakiyesini güncelle
        $this->updateCustomerBalance($payment);
    }

    /**
     * Muhasebe ledger entry oluştur (idempotent)
     */
    private function createAccountingEntry($payment): void
    {
        try {
            AccountingLedgerEntry::create([
                'customer_id' => $payment->customer_id,
                'invoice_id' => $payment->invoice_id,
                'payment_id' => $payment->id,
                'type' => 'credit',
                'amount' => $payment->amount,
                'date' => $payment->paid_at,
                'description' => 'Tahsilat kaydı - ' . $payment->payment_method,
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Unique constraint violation - entry zaten var (idempotent)
            if ($e->getCode() === '23000') {
                // Entry zaten var, işlem başarılı
                return;
            }
            throw $e;
        }
    }

    /**
     * Fatura durumunu güncelle
     */
    private function updateInvoiceStatus($payment): void
    {
        $invoice = Invoice::find($payment->invoice_id);
        if (!$invoice) return;

        // Muhasebe sisteminden kalan tutarı hesapla
        $remainingAmount = AccountingLedgerEntry::getInvoiceBalance($invoice->id);
        
        if ($remainingAmount <= 0) {
            // Tamamen ödendi
            $invoice->update([
                'status' => 'paid',
                'paid_at' => $payment->paid_at,
            ]);
        } else {
            // Kısmen ödendi - durumu 'sent' yap
            if ($invoice->status === 'draft') {
                $invoice->update(['status' => 'sent']);
            }
        }

        // Fatura cache'ini temizle (remaining_amount için)
        cache()->forget('invoice_' . $invoice->id);
        cache()->forget('invoice_remaining_amount_' . $invoice->id);
        cache()->forget('invoice_paid_amount_' . $invoice->id);
    }

    /**
     * Müşteri bakiyesini güncelle
     */
    private function updateCustomerBalance($payment): void
    {
        // Müşteri bakiyesi cache'ini temizle
        cache()->forget('customer_balance_' . $payment->customer_id);
        
        // Müşteri bakiyesi monitoring cache'ini temizle
        cache()->forget('customer_balance_monitoring_' . $payment->customer_id);
    }
}
