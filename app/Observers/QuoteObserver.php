<?php

namespace App\Observers;

use App\Models\Quote;

class QuoteObserver
{
    /**
     * Handle the Quote "created" event.
     */
    public function created(Quote $quote): void
    {
        // Teklif oluşturulduğunda toplamları hesapla
        if ($quote->items()->count() > 0) {
            $quote->recalcTotals();
            $quote->saveQuietly(); // Observer loop'unu önle
        }
    }

    /**
     * Handle the Quote "updated" event.
     */
    public function updated(Quote $quote): void
    {
        // Teklif güncellendiğinde toplamları yeniden hesapla
        if ($quote->items()->count() > 0) {
            $quote->recalcTotals();
            $quote->saveQuietly(); // Observer loop'unu önle
        }
    }
}
