<?php

namespace App\Observers;

use App\Models\Service;
use App\Services\RevenueCacheService;

class ServiceObserver
{
    public function __construct(private RevenueCacheService $revenueCache) {}

    /**
     * Handle the Service "created" event.
     */
    public function created(Service $service): void
    {
        $this->clearMRRCache();
    }

    /**
     * Handle the Service "updated" event.
     */
    public function updated(Service $service): void
    {
        $this->clearMRRCache();
    }

    /**
     * Handle the Service "deleted" event.
     */
    public function deleted(Service $service): void
    {
        $this->clearMRRCache();
    }

    /**
     * Handle the Service "restored" event.
     */
    public function restored(Service $service): void
    {
        $this->clearMRRCache();
    }

    /**
     * Handle the Service "force deleted" event.
     */
    public function forceDeleted(Service $service): void
    {
        $this->clearMRRCache();
    }

    /**
     * MRR cache'ini temizle
     */
    private function clearMRRCache(): void
    {
        // Sadece MRR ile ilgili cache'leri temizle
        \Illuminate\Support\Facades\Cache::forget('mrr_current');
        \Illuminate\Support\Facades\Cache::forget('mrr_by_type');
    }
}
