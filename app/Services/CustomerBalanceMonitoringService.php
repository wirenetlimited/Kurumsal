<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class CustomerBalanceMonitoringService
{
    private const PERFORMANCE_THRESHOLD_MS = 100; // 100ms
    private const CACHE_HIT_RATE_THRESHOLD = 80; // %80
    private const MEMORY_THRESHOLD_MB = 50; // 50MB

    /**
     * Monitor customer balance query performance
     */
    public function monitorPerformance(int $customerCount = 100): array
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();
        
        // Test query performance
        $customers = Customer::withBalanceAndStats()->limit($customerCount)->get();
        
        $endTime = microtime(true);
        $endMemory = memory_get_usage();
        
        $executionTime = ($endTime - $startTime) * 1000;
        $memoryUsed = ($endMemory - $startMemory) / 1024 / 1024; // MB
        
        // Performance metrics
        $metrics = [
            'execution_time_ms' => round($executionTime, 2),
            'memory_used_mb' => round($memoryUsed, 2),
            'customer_count' => $customerCount,
            'query_count' => $this->getQueryCount(),
            'cache_hit_rate' => $this->getCacheHitRate(),
            'performance_status' => $this->getPerformanceStatus($executionTime, $memoryUsed),
            'recommendations' => $this->getRecommendations($executionTime, $memoryUsed)
        ];
        
        // Log performance data
        $this->logPerformanceMetrics($metrics);
        
        // Check for alerts
        $this->checkAlerts($metrics);
        
        return $metrics;
    }

    /**
     * Get database query count
     */
    private function getQueryCount(): int
    {
        DB::enableQueryLog();
        Customer::withBalanceAndStats()->limit(1)->get();
        $count = count(DB::getQueryLog());
        DB::disableQueryLog();
        
        return $count;
    }

    /**
     * Get cache hit rate
     */
    private function getCacheHitRate(): float
    {
        $cacheService = app(CustomerBalanceCacheService::class);
        $stats = $cacheService->getCacheStats();
        
        return $stats['hit_rate'] ?? 0.0;
    }

    /**
     * Get performance status
     */
    private function getPerformanceStatus(float $executionTime, float $memoryUsed): string
    {
        if ($executionTime <= self::PERFORMANCE_THRESHOLD_MS && $memoryUsed <= self::MEMORY_THRESHOLD_MB) {
            return 'excellent';
        } elseif ($executionTime <= self::PERFORMANCE_THRESHOLD_MS * 2 && $memoryUsed <= self::MEMORY_THRESHOLD_MB * 2) {
            return 'good';
        } elseif ($executionTime <= self::PERFORMANCE_THRESHOLD_MS * 3 && $memoryUsed <= self::MEMORY_THRESHOLD_MB * 3) {
            return 'fair';
        } else {
            return 'poor';
        }
    }

    /**
     * Get performance recommendations
     */
    private function getRecommendations(float $executionTime, float $memoryUsed): array
    {
        $recommendations = [];
        
        if ($executionTime > self::PERFORMANCE_THRESHOLD_MS) {
            $recommendations[] = 'Query execution time is high. Consider adding database indexes.';
            $recommendations[] = 'Review and optimize complex SQL queries.';
        }
        
        if ($memoryUsed > self::MEMORY_THRESHOLD_MB) {
            $recommendations[] = 'Memory usage is high. Consider implementing pagination.';
            $recommendations[] = 'Review memory-intensive operations.';
        }
        
        if ($this->getCacheHitRate() < self::CACHE_HIT_RATE_THRESHOLD) {
            $recommendations[] = 'Cache hit rate is low. Consider warming up cache.';
            $recommendations[] = 'Review cache invalidation strategy.';
        }
        
        if (empty($recommendations)) {
            $recommendations[] = 'Performance is optimal. No immediate actions required.';
        }
        
        return $recommendations;
    }

    /**
     * Log performance metrics
     */
    private function logPerformanceMetrics(array $metrics): void
    {
        Log::info('Customer balance performance monitoring', $metrics);
        
        // Store metrics in cache for historical analysis
        $historicalMetrics = Cache::get('performance_metrics', []);
        $historicalMetrics[] = [
            'timestamp' => now()->toISOString(),
            'metrics' => $metrics
        ];
        
        // Keep only last 100 entries
        if (count($historicalMetrics) > 100) {
            $historicalMetrics = array_slice($historicalMetrics, -100);
        }
        
        Cache::put('performance_metrics', $historicalMetrics, 86400); // 24 hours
    }

    /**
     * Check for performance alerts
     */
    private function checkAlerts(array $metrics): void
    {
        $alerts = [];
        
        if ($metrics['execution_time_ms'] > self::PERFORMANCE_THRESHOLD_MS * 2) {
            $alerts[] = 'High query execution time detected: ' . $metrics['execution_time_ms'] . 'ms';
        }
        
        if ($metrics['memory_used_mb'] > self::MEMORY_THRESHOLD_MB * 2) {
            $alerts[] = 'High memory usage detected: ' . $metrics['memory_used_mb'] . 'MB';
        }
        
        if ($metrics['cache_hit_rate'] < self::CACHE_HIT_RATE_THRESHOLD) {
            $alerts[] = 'Low cache hit rate detected: ' . $metrics['cache_hit_rate'] . '%';
        }
        
        if (!empty($alerts)) {
            Log::warning('Performance alerts detected', ['alerts' => $alerts]);
            
            // Store alerts for dashboard display
            Cache::put('performance_alerts', $alerts, 3600); // 1 hour
        }
    }

    /**
     * Get historical performance data
     */
    public function getHistoricalData(int $hours = 24): array
    {
        $metrics = Cache::get('performance_metrics', []);
        
        if (empty($metrics)) {
            return [];
        }
        
        $cutoffTime = now()->subHours($hours);
        $filteredMetrics = array_filter($metrics, function ($entry) use ($cutoffTime) {
            return strtotime($entry['timestamp']) >= $cutoffTime->timestamp;
        });
        
        return array_values($filteredMetrics);
    }

    /**
     * Get current performance alerts
     */
    public function getCurrentAlerts(): array
    {
        return Cache::get('performance_alerts', []);
    }

    /**
     * Clear performance alerts
     */
    public function clearAlerts(): void
    {
        Cache::forget('performance_alerts');
    }

    /**
     * Generate performance report
     */
    public function generateReport(): array
    {
        $currentMetrics = $this->monitorPerformance();
        $historicalData = $this->getHistoricalData();
        $alerts = $this->getCurrentAlerts();
        
        // Calculate averages
        $avgExecutionTime = 0;
        $avgMemoryUsage = 0;
        $avgCacheHitRate = 0;
        
        if (!empty($historicalData)) {
            $avgExecutionTime = array_sum(array_column($historicalData, 'execution_time_ms')) / count($historicalData);
            $avgMemoryUsage = array_sum(array_column($historicalData, 'memory_used_mb')) / count($historicalData);
            $avgCacheHitRate = array_sum(array_column($historicalData, 'cache_hit_rate')) / count($historicalData);
        }
        
        return [
            'current_metrics' => $currentMetrics,
            'historical_averages' => [
                'execution_time_ms' => round($avgExecutionTime, 2),
                'memory_used_mb' => round($avgMemoryUsage, 2),
                'cache_hit_rate' => round($avgCacheHitRate, 2)
            ],
            'alerts' => $alerts,
            'report_generated_at' => now()->toISOString()
        ];
    }
}
