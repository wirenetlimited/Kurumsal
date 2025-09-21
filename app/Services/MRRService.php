<?php

namespace App\Services;

use App\Models\Service;
use Carbon\Carbon;

class MRRService
{
    public function periodToMonths(string $cycle): int
    {
        return match($cycle) {
            'monthly' => 1,
            'quarterly' => 3,
            'semiannual' => 6,
            'yearly' => 12,
            'biennial' => 24,
            'triennial' => 36,
            default => 0,
        };
    }

    public function share(Service $s): float
    {
        // Sadece taksitli (installment) hizmetler MRR'ye dahil edilir
        if ($s->payment_type !== 'installment' || !$s->cycle) {
            return 0.0;
        }
        
        $m = $this->periodToMonths($s->cycle);
        if (!$s->isActive() || $m === 0) return 0.0;
        
        // sell_price varsa onu kullan, yoksa price kullan
        $price = $s->sell_price ?? $s->price;
        return round((float)$price / $m, 2);
    }

    public function current(): float
    {
        return Service::active()
            ->get()
            ->sum(fn($s) => $this->share($s));
    }

    public function byType(): array
    {
        $grouped = Service::active()->get()->groupBy('service_type');
        return $grouped->map(fn($list) => round($list->sum(fn($s)=>$this->share($s)),2))->toArray();
    }
}
