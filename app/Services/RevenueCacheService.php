<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RevenueCacheService
{
    private const CACHE_TTL = 1800; // 30 dakika
    private const REVENUE_DATA_PREFIX = 'revenue_data_';
    private const MRR_PREFIX = 'mrr_';
    private const MONTHLY_REVENUE_PREFIX = 'monthly_revenue_';

    /**
     * Revenue raporu için aylık verileri cache'den al veya hesapla
     */
    public function getMonthlyRevenueData(int $period = 12): array
    {
        $cacheKey = self::REVENUE_DATA_PREFIX . $period;
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($period) {
            return $this->calculateMonthlyRevenueData($period);
        });
    }

    /**
     * MRR (Monthly Recurring Revenue) verilerini cache'den al
     */
    public function getMRRData(): array
    {
        return Cache::remember(self::MRR_PREFIX . 'current', self::CACHE_TTL, function () {
            return $this->calculateMRRData();
        });
    }

    /**
     * MRR verilerini hizmet türüne göre cache'den al
     */
    public function getMRRByType(): array
    {
        return Cache::remember(self::MRR_PREFIX . 'by_type', self::CACHE_TTL, function () {
            return $this->calculateMRRByType();
        });
    }

    /**
     * Bu ay gelir verilerini cache'den al
     */
    public function getThisMonthRevenue(): array
    {
        $thisMonth = Carbon::now()->format('Y-m');
        $cacheKey = self::MONTHLY_REVENUE_PREFIX . $thisMonth;
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () {
            return $this->calculateThisMonthRevenue();
        });
    }

    /**
     * Toplam gelir istatistiklerini cache'den al
     */
    public function getTotalRevenueStats(): array
    {
        return Cache::remember('total_revenue_stats', self::CACHE_TTL, function () {
            return $this->calculateTotalRevenueStats();
        });
    }

    /**
     * Cache'i temizle (veri güncellendiğinde çağrılır)
     */
    public function clearCache(): void
    {
        $keys = [
            self::REVENUE_DATA_PREFIX . '*',
            self::MRR_PREFIX . '*',
            self::MONTHLY_REVENUE_PREFIX . '*',
            'total_revenue_stats'
        ];

        foreach ($keys as $pattern) {
            if (str_contains($pattern, '*')) {
                // Pattern cache temizleme (Laravel'de manuel olarak yapılır)
                $this->clearPatternCache($pattern);
            } else {
                Cache::forget($pattern);
            }
        }
    }

    /**
     * Belirli bir ay için cache'i temizle
     */
    public function clearMonthCache(?string $month = null): void
    {
        $month = $month ?? Carbon::now()->format('Y-m');
        Cache::forget(self::MONTHLY_REVENUE_PREFIX . $month);
    }

    /**
     * Aylık revenue verilerini hesapla
     */
    private function calculateMonthlyRevenueData(int $period): array
    {
        $startDate = Carbon::now()->subMonths($period - 1)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        // Tek sorguda tüm faturaları al
        $invoices = Invoice::whereNotNull('issue_date')
            ->whereBetween('issue_date', [$startDate, $endDate])
            ->get(['issue_date', 'total']);

        // Tek sorguda tüm ödemeleri al
        $payments = Payment::whereNotNull('paid_at')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->get(['paid_at', 'amount']);

        // Aylık gruplamaları hazırla
        $monthlyInvoices = [];
        $monthlyPayments = [];

        // Faturaları aylara göre grupla
        foreach ($invoices as $invoice) {
            $month = $invoice->issue_date->format('Y-m');
            if (!isset($monthlyInvoices[$month])) {
                $monthlyInvoices[$month] = 0;
            }
            $monthlyInvoices[$month] += (float) $invoice->total;
        }

        // Ödemeleri aylara göre grupla
        foreach ($payments as $payment) {
            $month = $payment->paid_at->format('Y-m');
            if (!isset($monthlyPayments[$month])) {
                $monthlyPayments[$month] = 0;
            }
            $monthlyPayments[$month] += (float) $payment->amount;
        }

        $data = [];
        $labels = [];
        $issuedValues = [];
        $paidValues = [];

        for ($i = $period - 1; $i >= 0; $i--) {
            $dt = Carbon::now()->subMonths($i);
            $ym = $dt->format('Y-m');
            
            $issued = $monthlyInvoices[$ym] ?? 0;
            $paid = $monthlyPayments[$ym] ?? 0;
            
            $labels[] = $dt->isoFormat('MMM YY');
            $issuedValues[] = round($issued, 2);
            $paidValues[] = round($paid, 2);
            
            $data[] = [
                'month' => $dt->format('Y-m'),
                'month_name' => $dt->translatedFormat('F Y'),
                'issued' => round($issued, 2),
                'paid' => round($paid, 2),
                'remaining' => round($issued - $paid, 2)
            ];
        }

        return [
            'data' => $data,
            'labels' => $labels,
            'issued_values' => $issuedValues,
            'paid_values' => $paidValues,
            'monthly_invoices' => $monthlyInvoices,
            'monthly_payments' => $monthlyPayments
        ];
    }

    /**
     * MRR verilerini hesapla
     */
    private function calculateMRRData(): array
    {
        $cycleToMonths = [
            'monthly' => 1,
            'quarterly' => 3,
            'semiannually' => 6,
            'yearly' => 12,
            'biennially' => 24,
            'triennially' => 36,
        ];

        $mrr = DB::select("
            SELECT COALESCE(SUM(monthly_amount), 0) as total_mrr
            FROM (
                SELECT 
                    CASE 
                        WHEN payment_type = 'installment' AND cycle IS NOT NULL AND status = 'active'
                        THEN CASE 
                            WHEN cycle = 'monthly' THEN COALESCE(sell_price, price)
                            WHEN cycle = 'quarterly' THEN COALESCE(sell_price, price) / 3
                            WHEN cycle = 'semiannual' THEN COALESCE(sell_price, price) / 6
                            WHEN cycle = 'yearly' THEN COALESCE(sell_price, price) / 12
                            WHEN cycle = 'biennial' THEN COALESCE(sell_price, price) / 24
                            WHEN cycle = 'triennial' THEN COALESCE(sell_price, price) / 36
                            ELSE COALESCE(sell_price, price) / 12
                        END
                        ELSE 0
                    END as monthly_amount
                FROM services 
                WHERE status = 'active'
            ) as mrr_calc
        ")[0]->total_mrr;

        return [
            'total_mrr' => (float) $mrr,
            'calculated_at' => now()->toISOString()
        ];
    }

    /**
     * MRR verilerini hizmet türüne göre hesapla
     */
    private function calculateMRRByType(): array
    {
        $cycleToMonths = [
            'monthly' => 1,
            'quarterly' => 3,
            'semiannually' => 6,
            'yearly' => 12,
            'biennially' => 24,
            'triennially' => 36,
        ];

        $services = Service::active()->get(['service_type', 'price', 'sell_price', 'cycle', 'payment_type', 'start_date']);
        
        $mrrByType = [];
        
        foreach ($services as $service) {
            $type = $service->service_type ?? 'other';
            $months = $cycleToMonths[$service->cycle] ?? 12;
            
            $monthlyAmount = 0;
            // Sadece taksitli (installment) hizmetler MRR'ye dahil edilir
            if ($service->payment_type === 'installment' && $service->cycle) {
                $price = $service->sell_price ?? $service->price;
                $monthlyAmount = $months > 0 ? (float) $price / $months : 0;
            }
            
            if (!isset($mrrByType[$type])) {
                $mrrByType[$type] = 0;
            }
            $mrrByType[$type] += $monthlyAmount;
        }

        // Değerleri yuvarla
        foreach ($mrrByType as $type => $amount) {
            $mrrByType[$type] = round($amount, 2);
        }

        return $mrrByType;
    }

    /**
     * Bu ay gelir verilerini hesapla
     */
    private function calculateThisMonthRevenue(): array
    {
        $thisMonth = Carbon::now()->format('Y-m');
        
        // Bu ay kesilen faturalar
        $thisMonthInvoices = Invoice::whereNotNull('issue_date')
            ->whereRaw("DATE_FORMAT(issue_date, '%Y-%m') = ?", [$thisMonth])
            ->get(['id', 'total']);

        $issued = $thisMonthInvoices->sum('total');
        
        // Bu ay kesilen faturaların tahsilatı
        $thisMonthInvoiceIds = $thisMonthInvoices->pluck('id');
        $collected = Payment::whereIn('invoice_id', $thisMonthInvoiceIds)
            ->whereNotNull('paid_at')
            ->sum('amount');

        return [
            'issued' => (float) $issued,
            'collected' => (float) $collected,
            'remaining' => max(0, (float) $issued - (float) $collected),
            'month' => $thisMonth
        ];
    }

    /**
     * Toplam gelir istatistiklerini hesapla
     */
    private function calculateTotalRevenueStats(): array
    {
        $totalRevenue = Invoice::where('status', \App\Enums\InvoiceStatus::PAID)->sum('total');
        
        // Son 12 ayın ödeme verilerini al
        $last12Months = Payment::whereNotNull('paid_at')
            ->where('paid_at', '>=', Carbon::now()->subMonths(12))
            ->get(['paid_at', 'amount']);

        $monthlyPayments = [];
        foreach ($last12Months as $payment) {
            $month = $payment->paid_at->format('Y-m');
            if (!isset($monthlyPayments[$month])) {
                $monthlyPayments[$month] = 0;
            }
            $monthlyPayments[$month] += (float) $payment->amount;
        }

        $avgMonthlyRevenue = count($monthlyPayments) > 0 ? array_sum($monthlyPayments) / count($monthlyPayments) : 0;
        $maxMonthlyRevenue = count($monthlyPayments) > 0 ? max($monthlyPayments) : 0;

        return [
            'total_revenue' => (float) $totalRevenue,
            'avg_monthly_revenue' => (float) $avgMonthlyRevenue,
            'max_monthly_revenue' => (float) $maxMonthlyRevenue,
            'monthly_payments' => $monthlyPayments
        ];
    }

    /**
     * Pattern cache temizleme (basit implementasyon)
     */
    private function clearPatternCache(string $pattern): void
    {
        // Bu basit bir implementasyon - production'da Redis veya memcached kullanılabilir
        $prefix = str_replace('*', '', $pattern);
        
        // Cache key'lerini manuel olarak temizle
        $keysToClear = [
            self::REVENUE_DATA_PREFIX . '12',
            self::REVENUE_DATA_PREFIX . '6',
            self::REVENUE_DATA_PREFIX . '3',
            self::MRR_PREFIX . 'current',
            self::MRR_PREFIX . 'by_type',
            'total_revenue_stats'
        ];

        foreach ($keysToClear as $key) {
            if (str_starts_with($key, $prefix)) {
                Cache::forget($key);
            }
        }
    }
}

