<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invoice;
use App\Models\Quote;
use App\Models\InvoiceItem;
use App\Models\QuoteItem;
use Illuminate\Support\Facades\DB;

class FixFinancialCalculations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'finance:fix-calculations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Muhasebe sistemindeki para hesaplamalarını düzeltir';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Muhasebe hesaplamaları düzeltiliyor...');

        // Fatura hesaplamalarını düzelt
        $this->fixInvoiceCalculations();
        
        // Teklif hesaplamalarını düzelt
        $this->fixQuoteCalculations();
        
        // Cache'i temizle
        $this->call('cache:clear');
        $this->call('view:clear');
        
        $this->info('Muhasebe hesaplamaları başarıyla düzeltildi!');
    }

    /**
     * Fatura hesaplamalarını düzelt
     */
    private function fixInvoiceCalculations()
    {
        $this->info('Fatura hesaplamaları düzeltiliyor...');
        
        $invoices = Invoice::with('items')->get();
        $fixedCount = 0;
        
        foreach ($invoices as $invoice) {
            $originalTotal = $invoice->total;
            
            // Her item'ın line_total'ını düzelt (KDV hariç)
            foreach ($invoice->items as $item) {
                $lineSubtotal = (float) $item->qty * (float) $item->unit_price;
                $item->line_total = round($lineSubtotal, 2);
                $item->save();
            }
            
            // Fatura toplamlarını yeniden hesapla
            $invoice->calculateTotalsFromItems();
            $invoice->save();
            
            if ($originalTotal != $invoice->total) {
                $fixedCount++;
                $this->line("Fatura #{$invoice->id}: {$originalTotal} → {$invoice->total}");
            }
        }
        
        $this->info("{$fixedCount} fatura düzeltildi.");
    }

    /**
     * Teklif hesaplamalarını düzelt
     */
    private function fixQuoteCalculations()
    {
        $this->info('Teklif hesaplamaları düzeltiliyor...');
        
        $quotes = Quote::with('items')->get();
        $fixedCount = 0;
        
        foreach ($quotes as $quote) {
            $originalTotal = $quote->total;
            
            // Teklif toplamlarını yeniden hesapla
            $quote->recalcTotals();
            $quote->save();
            
            if ($originalTotal != $quote->total) {
                $fixedCount++;
                $this->line("Teklif #{$quote->id}: {$originalTotal} → {$quote->total}");
            }
        }
        
        $this->info("{$fixedCount} teklif düzeltildi.");
    }
}
