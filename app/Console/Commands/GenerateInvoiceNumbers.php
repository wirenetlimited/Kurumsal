<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invoice;
use App\SettingsHelper;
use Illuminate\Support\Facades\DB;

class GenerateInvoiceNumbers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:generate-numbers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mevcut faturalara fatura numaraları ekler';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fatura numaraları oluşturuluyor...');

        // Site ayarlarından fatura numarası bilgilerini al
        $prefix = DB::table('settings')->where('key', 'invoice_prefix')->value('value') ?? 'INV';
        $startNumber = (int)(DB::table('settings')->where('key', 'invoice_start_number')->value('value') ?? 1);
        
        $invoices = Invoice::whereNull('invoice_number')->orderBy('id')->get();
        $updated = 0;

        foreach ($invoices as $invoice) {
            $invoiceNumber = $this->generateInvoiceNumber($prefix, $startNumber);
            $invoice->update(['invoice_number' => $invoiceNumber]);
            $updated++;
            $startNumber++;
            
            $this->line("✓ Fatura #{$invoice->id}: {$invoiceNumber}");
        }

        $this->info("Toplam {$updated} fatura güncellendi.");
        $this->info('Fatura numaraları başarıyla oluşturuldu!');
    }

    /**
     * Generate invoice number
     */
    private function generateInvoiceNumber($prefix, $number)
    {
        $year = date('Y');
        return $prefix . '-' . str_pad($number, 6, '0', STR_PAD_LEFT) . '-' . $year;
    }
}
