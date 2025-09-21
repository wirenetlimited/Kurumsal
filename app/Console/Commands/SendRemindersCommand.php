<?php

namespace App\Console\Commands;

use App\Mail\InvoiceOverdueReminder;
use App\Mail\ServiceExpiryReminder;
use App\Models\Invoice;
use App\Models\Reminder;
use App\Models\Service;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendRemindersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send service expiry and overdue invoice reminders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();

        $offsets = [60, 30, 7, 1];
        foreach ($offsets as $days) {
            $target = $now->copy()->addDays($days)->toDateString();
            $services = Service::with('customer')
                ->whereDate('end_date', $target)
                ->active()
                ->get();
            foreach ($services as $service) {
                $key = 'service_'.$service->id.'_d'.$days;
                $already = Reminder::where('remindable_type', Service::class)
                    ->where('remindable_id', $service->id)
                    ->where('reminder_type', $key)
                    ->exists();
                if ($already) continue;

                if ($service->customer?->email) {
                    Mail::to($service->customer->email)
                        ->queue(new ServiceExpiryReminder($service, $days));
                }
                Reminder::create([
                    'remindable_type' => Service::class,
                    'remindable_id' => $service->id,
                    'reminder_type' => $key,
                    'sent_at' => now(),
                    'channel' => 'mail',
                ]);
            }
        }

        $invoices = Invoice::with('customer')
            ->sent()
            ->whereDate('due_date', '<', $now->toDateString())
            ->get();
        foreach ($invoices as $inv) {
            $key = 'invoice_overdue_'.$inv->id.'_'.Str::slug($now->toDateString());
            $already = Reminder::where('remindable_type', Invoice::class)
                ->where('remindable_id', $inv->id)
                ->where('reminder_type', $key)
                ->exists();
            if ($already) continue;
            if ($inv->customer?->email) {
                Mail::to($inv->customer->email)->queue(new InvoiceOverdueReminder($inv));
            }
            Reminder::create([
                'remindable_type' => Invoice::class,
                'remindable_id' => $inv->id,
                'reminder_type' => $key,
                'sent_at' => now(),
                'channel' => 'mail',
            ]);
        }

        $this->info('Reminders processed.');
        return self::SUCCESS;
    }
}