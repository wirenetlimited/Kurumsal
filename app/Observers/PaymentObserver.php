<?php

namespace App\Observers;

use App\Models\Payment;
use App\Models\LedgerEntry;
use App\Services\RevenueCacheService;

class PaymentObserver
{
    public function __construct(private RevenueCacheService $revenueCache) {}

    /**
     * Handle the Payment "created" event.
     */
    public function created(Payment $payment): void
    {
        // Ödeme için ledger entry oluştur
        $this->createLedgerEntry($payment);
        
        // Fatura durumunu güncelle
        $this->updateInvoiceStatus($payment);
        
        $this->clearRevenueCache();
    }

    /**
     * Handle the Payment "updated" event.
     */
    public function updated(Payment $payment): void
    {
        // Ödeme güncellendiğinde ledger entry'yi güncelle
        $this->updateLedgerEntry($payment);
        
        // Fatura durumunu güncelle
        $this->updateInvoiceStatus($payment);
        
        $this->clearRevenueCache();
    }

    /**
     * Handle the Payment "deleted" event.
     */
    public function deleted(Payment $payment): void
    {
        // Ödeme silindiğinde ledger entry'yi sil
        $this->deleteLedgerEntry($payment);
        
        // Fatura durumunu güncelle
        $this->updateInvoiceStatusAfterDeletion($payment);
        
        $this->clearRevenueCache();
    }

    /**
     * Handle the Payment "restored" event.
     */
    public function restored(Payment $payment): void
    {
        // Ödeme geri yüklendiğinde ledger entry oluştur
        $this->createLedgerEntry($payment);
        
        // Fatura durumunu güncelle
        $this->updateInvoiceStatus($payment);
        
        $this->clearRevenueCache();
    }

    /**
     * Handle the Payment "force deleted" event.
     */
    public function forceDeleted(Payment $payment): void
    {
        // Ödeme kalıcı silindiğinde ledger entry'yi sil
        $this->deleteLedgerEntry($payment);
        
        // Fatura durumunu güncelle
        $this->updateInvoiceStatusAfterDeletion($payment);
        
        $this->clearRevenueCache();
    }

    /**
     * Ödeme için ledger entry oluştur
     */
    private function createLedgerEntry(Payment $payment): void
    {
        try {
            // Müşterinin mevcut bakiyesini hesapla
            $currentBalance = $this->calculateCurrentBalance($payment->customer_id);
            $newBalance = $currentBalance + $payment->amount; // Credit ekleniyor
            
            LedgerEntry::create([
                'customer_id' => $payment->customer_id,
                'related_type' => Payment::class,
                'related_id' => $payment->id,
                'entry_date' => $payment->paid_at ?? $payment->created_at,
                'debit' => 0,
                'credit' => $payment->amount,
                'type' => 'credit',
                'amount' => $payment->amount,
                'balance' => $payment->amount,
                'balance_after' => $newBalance,
                'notes' => "Ödeme #{$payment->id}" . ($payment->invoice_id ? " (Fatura #{$payment->invoice_id})" : ""),
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to create ledger entry for payment', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Ödeme için ledger entry güncelle
     */
    private function updateLedgerEntry(Payment $payment): void
    {
        try {
            $ledgerEntry = LedgerEntry::where('related_type', Payment::class)
                ->where('related_id', $payment->id)
                ->first();

            if ($ledgerEntry) {
                $ledgerEntry->update([
                    'credit' => $payment->amount,
                    'entry_date' => $payment->paid_at ?? $payment->created_at,
                    'notes' => "Ödeme #{$payment->id}" . ($payment->invoice_id ? " (Fatura #{$payment->invoice_id})" : ""),
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to update ledger entry for payment', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Ödeme için ledger entry sil
     */
    private function deleteLedgerEntry(Payment $payment): void
    {
        try {
            LedgerEntry::where('related_type', Payment::class)
                ->where('related_id', $payment->id)
                ->delete();
        } catch (\Exception $e) {
            \Log::error('Failed to delete ledger entry for payment', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Fatura durumunu güncelle
     */
    private function updateInvoiceStatus(Payment $payment): void
    {
        if (!$payment->invoice_id) {
            return;
        }

        try {
            $invoice = \App\Models\Invoice::find($payment->invoice_id);
            if (!$invoice) {
                return;
            }

            // Kalan tutarı hesapla
            $remainingAmount = $invoice->remaining_amount;
            
            if ($remainingAmount <= 0) {
                // Tamamen ödendi
                $invoice->update([
                    'status' => 'paid',
                    'paid_at' => $payment->paid_at ?? now(),
                ]);
            } else {
                // Kısmen ödendi - durumu 'sent' yap
                if ($invoice->status === 'draft') {
                    $invoice->update(['status' => 'sent']);
                }
            }

            // Fatura cache'ini temizle
            cache()->forget('invoice_' . $invoice->id);
            cache()->forget('invoice_remaining_amount_' . $invoice->id);
            cache()->forget('invoice_paid_amount_' . $invoice->id);
        } catch (\Exception $e) {
            \Log::error('Failed to update invoice status for payment', [
                'payment_id' => $payment->id,
                'invoice_id' => $payment->invoice_id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Ödeme silindikten sonra fatura durumunu güncelle
     */
    private function updateInvoiceStatusAfterDeletion(Payment $payment): void
    {
        if (!$payment->invoice_id) {
            return;
        }

        try {
            $invoice = \App\Models\Invoice::find($payment->invoice_id);
            if (!$invoice) {
                return;
            }

            // Kalan tutarı hesapla
            $remainingAmount = $invoice->remaining_amount;
            
            if ($remainingAmount > 0) {
                // Hala ödenmemiş tutar var - durumu 'sent' yap
                $invoice->update([
                    'status' => 'sent',
                    'paid_at' => null,
                ]);
            }

            // Fatura cache'ini temizle
            cache()->forget('invoice_' . $invoice->id);
            cache()->forget('invoice_remaining_amount_' . $invoice->id);
            cache()->forget('invoice_paid_amount_' . $invoice->id);
        } catch (\Exception $e) {
            \Log::error('Failed to update invoice status after payment deletion', [
                'payment_id' => $payment->id,
                'invoice_id' => $payment->invoice_id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Revenue cache'ini temizle
     */
    private function clearRevenueCache(): void
    {
        $this->revenueCache->clearCache();
    }

    /**
     * Müşterinin mevcut bakiyesini hesapla
     */
    private function calculateCurrentBalance(int $customerId): float
    {
        $ledgerEntries = LedgerEntry::where('customer_id', $customerId)
            ->orderBy('created_at', 'asc')
            ->get();

        $balance = 0.0;
        foreach ($ledgerEntries as $entry) {
            $balance += $entry->debit - $entry->credit;
        }

        return $balance;
    }
}
