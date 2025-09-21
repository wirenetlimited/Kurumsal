<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Daily reminders: service expiries and overdue invoices
Schedule::command('reminders:send')->dailyAt('09:00');
// Daily overdue marker for invoices
Schedule::command('invoices:mark-overdue')->dailyAt('02:00');
