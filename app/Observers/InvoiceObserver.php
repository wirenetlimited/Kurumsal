<?php

namespace App\Observers;

use App\Models\Invoice;
use App\Models\LedgerEntry;
use App\Services\RevenueCacheService;

class InvoiceObserver
{
    public function __construct(private RevenueCacheService $revenueCache) {}

    /**
     * Handle the Invoice "created" event.
     */
    public function created(Invoice $invoice): void
    {
        // Fatura için ledger entry oluştur
        $this->createLedgerEntry($invoice);
        
        $this->clearRevenueCache();
    }

    /**
     * Handle the Invoice "updated" event.
     */
    public function updated(Invoice $invoice): void
    {
        // Fatura güncellendiğinde ledger entry'yi güncelle
        $this->updateLedgerEntry($invoice);
        
        $this->clearRevenueCache();
    }

    /**
     * Handle the Invoice "deleted" event.
     */
    public function deleted(Invoice $invoice): void
    {
        // Fatura silindiğinde ledger entry'yi sil
        $this->deleteLedgerEntry($invoice);
        
        $this->clearRevenueCache();
    }

    /**
     * Handle the Invoice "restored" event.
     */
    public function restored(Invoice $invoice): void
    {
        // Fatura geri yüklendiğinde ledger entry oluştur
        $this->createLedgerEntry($invoice);
        
        $this->clearRevenueCache();
    }

    /**
     * Handle the Invoice "force deleted" event.
     */
    public function forceDeleted(Invoice $invoice): void
    {
        // Fatura kalıcı silindiğinde ledger entry'yi sil
        $this->deleteLedgerEntry($invoice);
        
        $this->clearRevenueCache();
    }

    /**
     * Fatura için ledger entry oluştur
     */
    private function createLedgerEntry(Invoice $invoice): void
    {
        try {
            LedgerEntry::create([
                'customer_id' => $invoice->customer_id,
                'related_type' => Invoice::class,
                'related_id' => $invoice->id,
                'entry_date' => $invoice->due_date ?? $invoice->created_at,
                'debit' => $invoice->total,
                'credit' => 0,
                'notes' => "Fatura #{$invoice->invoice_number}",
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to create ledger entry for invoice', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Fatura için ledger entry güncelle
     */
    private function updateLedgerEntry(Invoice $invoice): void
    {
        try {
            $ledgerEntry = LedgerEntry::where('related_type', Invoice::class)
                ->where('related_id', $invoice->id)
                ->first();

            if ($ledgerEntry) {
                $ledgerEntry->update([
                    'debit' => $invoice->total,
                    'notes' => "Fatura #{$invoice->invoice_number}",
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to update ledger entry for invoice', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Fatura için ledger entry sil
     */
    private function deleteLedgerEntry(Invoice $invoice): void
    {
        try {
            LedgerEntry::where('related_type', Invoice::class)
                ->where('related_id', $invoice->id)
                ->delete();
        } catch (\Exception $e) {
            \Log::error('Failed to delete ledger entry for invoice', [
                'invoice_id' => $invoice->id,
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
}
