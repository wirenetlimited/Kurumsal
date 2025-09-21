<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class CustomerBalanceCacheService
{
    private const CACHE_TTL = 3600; // 1 saat
    private const CACHE_PREFIX = 'customer_balance_';
    private const CACHE_STATS_PREFIX = 'customer_stats_';

    /**
     * Get cached customer balance data
     */
    public function getCachedBalance(int $customerId): ?float
    {
        return Cache::get(self::CACHE_PREFIX . $customerId);
    }

    /**
     * Set customer balance cache
     */
    public function setCachedBalance(int $customerId, float $balance): void
    {
        Cache::put(self::CACHE_PREFIX . $customerId, $balance, self::CACHE_TTL);
    }

    /**
     * Get cached customer statistics
     */
    public function getCachedStats(int $customerId): ?array
    {
        return Cache::get(self::CACHE_STATS_PREFIX . $customerId);
    }

    /**
     * Set customer statistics cache
     */
    public function setCachedStats(int $customerId, array $stats): void
    {
        Cache::put(self::CACHE_STATS_PREFIX . $customerId, $stats, self::CACHE_TTL);
    }

    /**
     * Get cached customers with balance and stats
     */
    public function getCachedCustomersWithBalance(int $limit = 100): ?Collection
    {
        $cacheKey = 'customers_with_balance_' . $limit;
        return Cache::get($cacheKey);
    }

    /**
     * Set cached customers with balance and stats
     */
    public function setCachedCustomersWithBalance(int $limit, Collection $customers): void
    {
        $cacheKey = 'customers_with_balance_' . $limit;
        Cache::put($cacheKey, $customers, self::CACHE_TTL);
    }

    /**
     * Get cached dashboard statistics
     */
    public function getCachedDashboardStats(): ?array
    {
        return Cache::get('dashboard_stats');
    }

    /**
     * Set cached dashboard statistics
     */
    public function setCachedDashboardStats(array $stats): void
    {
        Cache::put('dashboard_stats', $stats, self::CACHE_TTL);
    }

    /**
     * Clear all customer balance caches
     */
    public function clearAllCaches(): void
    {
        Cache::flush();
    }

    /**
     * Clear specific customer cache
     */
    public function clearCustomerCache(int $customerId): void
    {
        Cache::forget(self::CACHE_PREFIX . $customerId);
        Cache::forget(self::CACHE_STATS_PREFIX . $customerId);
    }

    /**
     * Warm up cache for frequently accessed customers
     */
    public function warmUpCache(int $limit = 100): void
    {
        $customers = Customer::withBalanceAndStats()
            ->limit($limit)
            ->get();

        foreach ($customers as $customer) {
            $this->setCachedBalance($customer->id, $customer->current_balance);
            $this->setCachedStats($customer->id, [
                'service_count' => $customer->service_count,
                'monthly_revenue' => $customer->monthly_revenue
            ]);
        }

        // Cache dashboard stats
        $stats = $this->calculateDashboardStats();
        $this->setCachedDashboardStats($stats);
    }

    /**
     * Calculate dashboard statistics
     */
    private function calculateDashboardStats(): array
    {
        $stats = DB::select("
            SELECT 
                COUNT(*) as total_customers,
                SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_customers,
                SUM(CASE WHEN ledger_balance.balance > 0 THEN ledger_balance.balance ELSE 0 END) as total_receivable,
                SUM(CASE WHEN ledger_balance.balance < 0 THEN ABS(ledger_balance.balance) ELSE 0 END) as total_payable
            FROM customers
            LEFT JOIN (
                SELECT 
                    customer_id,
                    SUM(debit) - SUM(credit) as balance
                FROM ledger_entries 
                GROUP BY customer_id
            ) as ledger_balance ON customers.id = ledger_balance.customer_id
        ")[0];

        $currentMonth = now()->startOfMonth()->format('Y-m-d');
        $monthlyRevenue = DB::select("
            SELECT COALESCE(SUM(monthly_amount), 0) as total_mrr
            FROM (
                SELECT 
                    CASE 
                        WHEN payment_type = 'installment' AND status = 'active'
                        THEN CASE 
                            WHEN cycle = 'monthly' THEN sell_price
                            WHEN cycle = 'quarterly' THEN sell_price / 3
                            WHEN cycle = 'semiannually' THEN sell_price / 6
                            WHEN cycle = 'yearly' THEN sell_price / 12
                            WHEN cycle = 'biennially' THEN sell_price / 24
                            WHEN cycle = 'triennially' THEN sell_price / 36
                            ELSE sell_price / 12
                        END
                        ELSE 0
                    END as monthly_amount
                FROM services 
                WHERE status = 'active'
            ) as mrr_calc
        ")[0]->total_mrr;

        return [
            'total_customers' => (int) $stats->total_customers,
            'active_customers' => (int) $stats->active_customers,
            'total_receivable' => (float) $stats->total_receivable,
            'total_payable' => (float) $stats->total_payable,
            'monthly_revenue' => (float) $monthlyRevenue
        ];
    }

    /**
     * Get cache hit rate statistics
     */
    public function getCacheStats(): array
    {
        $keys = Cache::get('cache_stats', []);
        $totalRequests = $keys['total_requests'] ?? 0;
        $cacheHits = $keys['cache_hits'] ?? 0;
        $hitRate = $totalRequests > 0 ? ($cacheHits / $totalRequests) * 100 : 0;

        return [
            'total_requests' => $totalRequests,
            'cache_hits' => $cacheHits,
            'cache_misses' => $totalRequests - $cacheHits,
            'hit_rate' => round($hitRate, 2)
        ];
    }

    /**
     * Record cache hit
     */
    public function recordCacheHit(): void
    {
        $keys = Cache::get('cache_stats', []);
        $keys['total_requests'] = ($keys['total_requests'] ?? 0) + 1;
        $keys['cache_hits'] = ($keys['cache_hits'] ?? 0) + 1;
        Cache::put('cache_stats', $keys, self::CACHE_TTL);
    }

    /**
     * Record cache miss
     */
    public function recordCacheMiss(): void
    {
        $keys = Cache::get('cache_stats', []);
        $keys['total_requests'] = ($keys['total_requests'] ?? 0) + 1;
        Cache::put('cache_stats', $keys, self::CACHE_TTL);
    }
}
