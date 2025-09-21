<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\LedgerEntry;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateLedgerEntries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ledger:generate {--force : Force regeneration of existing entries}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate ledger entries for existing invoices and payments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating ledger entries for existing invoices and payments...');

        // Mevcut ledger entries'leri temizle (eğer force flag varsa)
        if ($this->option('force')) {
            $this->warn('Force flag detected. Clearing existing ledger entries...');
            LedgerEntry::truncate();
            $this->info('Existing ledger entries cleared.');
        }

        // Faturalar için ledger entries oluştur
        $this->generateInvoiceEntries();

        // Ödemeler için ledger entries oluştur
        $this->generatePaymentEntries();

        $this->info('Ledger entries generation completed successfully!');
        
        // Özet bilgi
        $totalEntries = LedgerEntry::count();
        $this->info("Total ledger entries: {$totalEntries}");
    }

    /**
     * Faturalar için ledger entries oluştur
     */
    private function generateInvoiceEntries(): void
    {
        $this->info('Generating ledger entries for invoices...');

        $invoices = Invoice::where('status', '!=', 'cancelled')->get();
        $count = 0;

        foreach ($invoices as $invoice) {
            try {
                // Eğer zaten ledger entry varsa atla
                if (LedgerEntry::where('related_type', Invoice::class)
                    ->where('related_id', $invoice->id)
                    ->exists()) {
                    continue;
                }

                LedgerEntry::create([
                    'customer_id' => $invoice->customer_id,
                    'related_type' => Invoice::class,
                    'related_id' => $invoice->id,
                    'entry_date' => $invoice->due_date ?? $invoice->created_at,
                    'debit' => $invoice->total,
                    'credit' => 0,
                    'notes' => "Fatura #{$invoice->invoice_number}",
                ]);

                $count++;
            } catch (\Exception $e) {
                $this->error("Failed to create ledger entry for invoice #{$invoice->id}: " . $e->getMessage());
            }
        }

        $this->info("Created {$count} ledger entries for invoices.");
    }

    /**
     * Ödemeler için ledger entries oluştur
     */
    private function generatePaymentEntries(): void
    {
        $this->info('Generating ledger entries for payments...');

        $payments = Payment::whereNotNull('paid_at')->get();
        $count = 0;

        foreach ($payments as $payment) {
            try {
                // Eğer zaten ledger entry varsa atla
                if (LedgerEntry::where('related_type', Payment::class)
                    ->where('related_id', $payment->id)
                    ->exists()) {
                    continue;
                }

                LedgerEntry::create([
                    'customer_id' => $payment->customer_id,
                    'related_type' => Payment::class,
                    'related_id' => $payment->id,
                    'entry_date' => $payment->paid_at ?? $payment->created_at,
                    'debit' => 0,
                    'credit' => $payment->amount,
                    'notes' => "Ödeme #{$payment->id}" . ($payment->invoice_id ? " (Fatura #{$payment->invoice_id})" : ""),
                ]);

                $count++;
            } catch (\Exception $e) {
                $this->error("Failed to create ledger entry for payment #{$payment->id}: " . $e->getMessage());
            }
        }

        $this->info("Created {$count} ledger entries for payments.");
    }
}
