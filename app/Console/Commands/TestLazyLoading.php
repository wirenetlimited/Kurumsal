<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Service;
use App\Services\LazyLoadingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class TestLazyLoading extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:lazy-loading {--type=all : Test type (all, customers, services, invoices, dashboard)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test lazy loading optimizations and performance improvements';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type');
        
        $this->info('🚀 Lazy Loading Performance Test Başlatılıyor...');
        $this->newLine();

        switch ($type) {
            case 'customers':
                $this->testCustomerOptimizations();
                break;
            case 'services':
                $this->testServiceOptimizations();
                break;
            case 'invoices':
                $this->testInvoiceOptimizations();
                break;
            case 'dashboard':
                $this->testDashboardOptimizations();
                break;
            default:
                $this->testAllOptimizations();
                break;
        }

        $this->info('✅ Lazy Loading Test Tamamlandı!');
    }

    private function testCustomerOptimizations()
    {
        $this->info('📊 Customer Optimizations Test...');
        
        // Test 1: Normal vs Optimized Customer List
        $this->info('Test 1: Normal vs Optimized Customer List');
        
        $start = microtime(true);
        $customers = Customer::with(['services', 'invoices', 'ledgerEntries'])->get();
        $normalTime = microtime(true) - $start;
        
        $start = microtime(true);
        $optimizedCustomers = Customer::withBalanceAndStats()->get();
        $optimizedTime = microtime(true) - $start;
        
        $this->table(
            ['Method', 'Time (ms)', 'Improvement'],
            [
                ['Normal', round($normalTime * 1000, 2), '-'],
                ['Optimized', round($optimizedTime * 1000, 2), round((($normalTime - $optimizedTime) / $normalTime) * 100, 1) . '%']
            ]
        );

        // Test 2: Query Count Comparison
        $this->info('Test 2: Query Count Comparison');
        
        DB::enableQueryLog();
        Customer::with(['services', 'invoices', 'ledgerEntries'])->get();
        $normalQueries = count(DB::getQueryLog());
        
        DB::flushQueryLog();
        Customer::withBalanceAndStats()->get();
        $optimizedQueries = count(DB::getQueryLog());
        
        $this->table(
            ['Method', 'Query Count', 'Reduction'],
            [
                ['Normal', $normalQueries, '-'],
                ['Optimized', $optimizedQueries, round((($normalQueries - $optimizedQueries) / $normalQueries) * 100, 1) . '%']
            ]
        );
    }

    private function testServiceOptimizations()
    {
        $this->info('🔧 Service Optimizations Test...');
        
        // Test 1: Normal vs Optimized Service List
        $this->info('Test 1: Normal vs Optimized Service List');
        
        $start = microtime(true);
        $services = Service::with(['customer', 'provider', 'domain', 'hosting'])->get();
        $normalTime = microtime(true) - $start;
        
        $start = microtime(true);
        $optimizedServices = Service::select([
            'id', 'customer_id', 'provider_id', 'service_type', 'status', 
            'start_date', 'end_date', 'cycle', 'payment_type', 'sell_price'
        ])->with([
            'customer:id,name,surname,email',
            'provider:id,name',
            'domain:id,service_id,domain_name',
            'hosting:id,service_id,server_name'
        ])->get();
        $optimizedTime = microtime(true) - $start;
        
        $this->table(
            ['Method', 'Time (ms)', 'Improvement'],
            [
                ['Normal', round($normalTime * 1000, 2), '-'],
                ['Optimized', round($optimizedTime * 1000, 2), round((($normalTime - $optimizedTime) / $normalTime) * 100, 1) . '%']
            ]
        );
    }

    private function testInvoiceOptimizations()
    {
        $this->info('📄 Invoice Optimizations Test...');
        
        // Test 1: Normal vs Optimized Invoice List
        $this->info('Test 1: Normal vs Optimized Invoice List');
        
        $start = microtime(true);
        $invoices = Invoice::with('customer')->get();
        $normalTime = microtime(true) - $start;
        
        $start = microtime(true);
        $optimizedInvoices = Invoice::select(['id', 'customer_id', 'invoice_number', 'issue_date', 'due_date', 'status', 'total'])
            ->with(['customer:id,name,surname,email'])
            ->get();
        $optimizedTime = microtime(true) - $start;
        
        $this->table(
            ['Method', 'Time (ms)', 'Improvement'],
            [
                ['Normal', round($normalTime * 1000, 2), '-'],
                ['Optimized', round($optimizedTime * 1000, 2), round((($normalTime - $optimizedTime) / $normalTime) * 100, 1) . '%']
            ]
        );
    }

    private function testDashboardOptimizations()
    {
        $this->info('📊 Dashboard Optimizations Test...');
        
        $lazyLoadingService = new LazyLoadingService();
        
        // Test 1: Normal vs Optimized Dashboard Data
        $this->info('Test 1: Normal vs Optimized Dashboard Data');
        
        $start = microtime(true);
        // Normal dashboard data loading
        $normalData = [
            'customers' => Customer::count(),
            'activeCustomers' => Customer::where('is_active', true)->count(),
            'services' => Service::count(),
            'activeServices' => Service::where('status', 'active')->count(),
            'invoices' => Invoice::count(),
            'overdueInvoices' => Invoice::where('status', 'overdue')->count(),
        ];
        $normalTime = microtime(true) - $start;
        
        $start = microtime(true);
        // Optimized dashboard data loading
        $optimizedData = $lazyLoadingService->getDashboardData();
        $optimizedTime = microtime(true) - $start;
        
        $this->table(
            ['Method', 'Time (ms)', 'Improvement'],
            [
                ['Normal', round($normalTime * 1000, 2), '-'],
                ['Optimized', round($optimizedTime * 1000, 2), round((($normalTime - $optimizedTime) / $normalTime) * 100, 1) . '%']
            ]
        );
    }

    private function testAllOptimizations()
    {
        $this->testCustomerOptimizations();
        $this->newLine();
        $this->testServiceOptimizations();
        $this->newLine();
        $this->testInvoiceOptimizations();
        $this->newLine();
        $this->testDashboardOptimizations();
    }
}
