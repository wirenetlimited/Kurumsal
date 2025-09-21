<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use Illuminate\Console\Command;

class MarkInvoicesOverdue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:mark-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark sent invoices whose due_date has passed as overdue';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->toDateString();

        $count = Invoice::where('status', 'sent')
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', $today)
            ->update(['status' => 'overdue']);

        $this->info("Overdue marked: {$count}");
        return self::SUCCESS;
    }
}
