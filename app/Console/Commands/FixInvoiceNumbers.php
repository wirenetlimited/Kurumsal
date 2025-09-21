<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invoice;
use Illuminate\Support\Str;

class FixInvoiceNumbers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:fix-numbers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix duplicate invoice numbers and add unique invoice_number field';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fatura numaraları düzeltiliyor...');

        // Mevcut faturaları kontrol et
        $invoices = Invoice::all();
        $this->info("Toplam {$invoices->count()} fatura bulundu.");

        // Her fatura için unique numara oluştur
        foreach ($invoices as $invoice) {
            $invoiceNumber = 'INV-' . str_pad($invoice->id, 6, '0', STR_PAD_LEFT) . '-' . date('Y');
            
            $invoice->update(['invoice_number' => $invoiceNumber]);
            $this->line("Fatura #{$invoice->id} -> {$invoiceNumber}");
        }

        $this->info('Tüm fatura numaraları düzeltildi!');
        
        return Command::SUCCESS;
    }
}
