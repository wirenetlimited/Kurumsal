<?php

namespace App\Console\Commands;

use App\Services\CustomerBalanceCacheService;
use Illuminate\Console\Command;

class WarmUpCustomerBalanceCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:warm-up-customer-balance {--limit=100 : Number of customers to cache}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Warm up customer balance cache for better performance';

    /**
     * Execute the console command.
     */
    public function handle(CustomerBalanceCacheService $cacheService)
    {
        $limit = (int) $this->option('limit');
        
        $this->info("Warming up customer balance cache for {$limit} customers...");
        
        $startTime = microtime(true);
        $startMemory = memory_get_usage();
        
        try {
            $cacheService->warmUpCache($limit);
            
            $endTime = microtime(true);
            $endMemory = memory_get_usage();
            $executionTime = ($endTime - $startTime) * 1000;
            $memoryUsed = ($endMemory - $startMemory) / 1024;
            
            $this->info("✅ Cache warm-up completed successfully!");
            $this->info("   Execution time: " . number_format($executionTime, 2) . "ms");
            $this->info("   Memory used: " . number_format($memoryUsed, 2) . "KB");
            $this->info("   Customers cached: {$limit}");
            
            // Cache stats'ı göster
            $cacheStats = $cacheService->getCacheStats();
            $this->info("\n📊 Cache Statistics:");
            $this->info("   Total requests: " . $cacheStats['total_requests']);
            $this->info("   Cache hits: " . $cacheStats['cache_hits']);
            $this->info("   Cache misses: " . $cacheStats['cache_misses']);
            $this->info("   Hit rate: " . $cacheStats['hit_rate'] . "%");
            
        } catch (\Exception $e) {
            $this->error("❌ Cache warm-up failed: " . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
