<?php

namespace App\Observers;

use App\Jobs\UpdateCustomerBalanceJob;
use App\Models\LedgerEntry;

class LedgerEntryObserver
{
    /**
     * Handle the LedgerEntry "created" event.
     */
    public function created(LedgerEntry $ledgerEntry): void
    {
        $this->dispatchBalanceUpdateJob($ledgerEntry->customer_id);
    }

    /**
     * Handle the LedgerEntry "updated" event.
     */
    public function updated(LedgerEntry $ledgerEntry): void
    {
        $this->dispatchBalanceUpdateJob($ledgerEntry->customer_id);
    }

    /**
     * Handle the LedgerEntry "deleted" event.
     */
    public function deleted(LedgerEntry $ledgerEntry): void
    {
        $this->dispatchBalanceUpdateJob($ledgerEntry->customer_id);
    }

    /**
     * Handle the LedgerEntry "restored" event.
     */
    public function restored(LedgerEntry $ledgerEntry): void
    {
        $this->dispatchBalanceUpdateJob($ledgerEntry->customer_id);
    }

    /**
     * Handle the LedgerEntry "force deleted" event.
     */
    public function forceDeleted(LedgerEntry $ledgerEntry): void
    {
        $this->dispatchBalanceUpdateJob($ledgerEntry->customer_id);
    }

    /**
     * Dispatch balance update job
     */
    private function dispatchBalanceUpdateJob(int $customerId): void
    {
        UpdateCustomerBalanceJob::dispatch($customerId)->onQueue('balance-updates');
    }
}
