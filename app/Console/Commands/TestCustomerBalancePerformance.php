<?php

namespace App\Console\Commands;

use App\Models\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TestCustomerBalancePerformance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:customer-balance-performance {--count=100 : Number of customers to test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the performance of customer balance queries';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = (int) $this->option('count');
        $this->info("Testing customer balance performance with {$count} customers...");

        // Test 1: Original N+1 approach (simulated)
        $this->info("\n1. Testing N+1 approach (simulated)...");
        $startTime = microtime(true);
        $startMemory = memory_get_usage();
        
        $customers = Customer::limit($count)->get();
        $totalBalance = 0;
        $totalServices = 0;
        
        foreach ($customers as $customer) {
            $balance = $customer->ledgerEntries()->sum(DB::raw('debit - credit'));
            $serviceCount = $customer->services()->count();
            $totalBalance += $balance;
            $totalServices += $serviceCount;
        }
        
        $endTime = microtime(true);
        $endMemory = memory_get_usage();
        $n1Time = ($endTime - $startTime) * 1000;
        $n1Memory = $endMemory - $startMemory;
        
        $this->info("   Time: " . number_format($n1Time, 2) . "ms");
        $this->info("   Memory: " . number_format($n1Memory / 1024, 2) . "KB");
        $this->info("   Total Balance: " . $totalBalance);
        $this->info("   Total Services: " . $totalServices);

        // Test 2: Optimized approach
        $this->info("\n2. Testing optimized approach...");
        $startTime = microtime(true);
        $startMemory = memory_get_usage();
        
        $optimizedCustomers = Customer::withBalanceAndStats()->limit($count)->get();
        $totalBalanceOpt = $optimizedCustomers->sum('current_balance');
        $totalServicesOpt = $optimizedCustomers->sum('service_count');
        
        $endTime = microtime(true);
        $endMemory = memory_get_usage();
        $optTime = ($endTime - $startTime) * 1000;
        $optMemory = $endMemory - $startMemory;
        
        $this->info("   Time: " . number_format($optTime, 2) . "ms");
        $this->info("   Memory: " . number_format($optMemory / 1024, 2) . "KB");
        $this->info("   Total Balance: " . $totalBalanceOpt);
        $this->info("   Total Services: " . $totalServicesOpt);

        // Test 3: Database query count
        $this->info("\n3. Testing database query count...");
        DB::enableQueryLog();
        
        Customer::withBalanceAndStats()->limit($count)->get();
        
        $queryCount = count(DB::getQueryLog());
        $this->info("   Query count: " . $queryCount);
        
        DB::disableQueryLog();

        // Performance comparison
        $this->info("\n4. Performance Comparison:");
        $this->info("   Time improvement: " . round((($n1Time - $optTime) / $n1Time) * 100, 2) . "%");
        $this->info("   Memory improvement: " . round((($n1Memory - $optMemory) / $n1Memory) * 100, 2) . "%");
        
        if ($optTime < $n1Time) {
            $this->info("   ✅ Optimized approach is faster!");
        } else {
            $this->warn("   ⚠️  Optimized approach is slower!");
        }
        
        if ($optMemory < $n1Memory) {
            $this->info("   ✅ Optimized approach uses less memory!");
        } else {
            $this->warn("   ⚠️  Optimized approach uses more memory!");
        }

        // Log results
        Log::info('Customer balance performance test completed', [
            'customer_count' => $count,
            'n1_time_ms' => $n1Time,
            'n1_memory_kb' => $n1Memory / 1024,
            'optimized_time_ms' => $optTime,
            'optimized_memory_kb' => $optMemory / 1024,
            'query_count' => $queryCount,
            'time_improvement_percent' => round((($n1Time - $optTime) / $n1Time) * 100, 2),
            'memory_improvement_percent' => round((($n1Memory - $optMemory) / $n1Memory) * 100, 2)
        ]);

        $this->info("\n✅ Performance test completed!");
        return 0;
    }
}
