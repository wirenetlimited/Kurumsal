<?php

namespace App\Jobs;

use App\Models\Customer;
use App\Services\CustomerBalanceCacheService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateCustomerBalanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300; // 5 dakika
    public $tries = 3;
    
    protected int $customerId;
    protected bool $updateCache;

    /**
     * Create a new job instance.
     */
    public function __construct(int $customerId, bool $updateCache = true)
    {
        $this->customerId = $customerId;
        $this->updateCache = $updateCache;
    }

    /**
     * Execute the job.
     */
    public function handle(CustomerBalanceCacheService $cacheService): void
    {
        try {
            Log::info('Starting customer balance update job', [
                'customer_id' => $this->customerId,
                'update_cache' => $this->updateCache
            ]);

            $customer = Customer::find($this->customerId);
            
            if (!$customer) {
                Log::warning('Customer not found for balance update', [
                    'customer_id' => $this->customerId
                ]);
                return;
            }

            // Balance hesapla
            $balance = $this->calculateCustomerBalance($customer);
            
            // Service stats hesapla
            $serviceStats = $this->calculateServiceStats($customer);
            
            // Cache güncelle
            if ($this->updateCache) {
                $cacheService->setCachedBalance($this->customerId, $balance);
                $cacheService->setCachedStats($this->customerId, $serviceStats);
                
                Log::info('Customer balance cache updated', [
                    'customer_id' => $this->customerId,
                    'balance' => $balance,
                    'service_stats' => $serviceStats
                ]);
            }

            // Dashboard stats cache'ini temizle (güncellenmiş olabilir)
            $cacheService->setCachedDashboardStats(null);

        } catch (\Exception $e) {
            Log::error('Customer balance update job failed', [
                'customer_id' => $this->customerId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
    }

    /**
     * Calculate customer balance from ledger entries
     */
    private function calculateCustomerBalance(Customer $customer): float
    {
        return (float) $customer->ledgerEntries()
            ->selectRaw('COALESCE(SUM(debit), 0) - COALESCE(SUM(credit), 0) as balance')
            ->value('balance') ?? 0.0;
    }

    /**
     * Calculate customer service statistics
     */
    private function calculateServiceStats(Customer $customer): array
    {
        $currentMonth = now()->startOfMonth()->format('Y-m-d');
        
        $services = $customer->services()
            ->active()
            ->get();

        $serviceCount = $services->count();
        $monthlyRevenue = 0.0;

        foreach ($services as $service) {
            $price = (float) $service->sell_price;
            
            // Sadece taksitli ödeme hizmetleri MRR'ye dahil edilir
            if ($service->payment_type === 'installment') {
                $months = match($service->cycle) {
                    'monthly' => 1,
                    'quarterly' => 3,
                    'semiannually' => 6,
                    'yearly' => 12,
                    'biennially' => 24,
                    'triennially' => 36,
                    default => 12
                };
                $monthlyRevenue += $months > 0 ? $price / $months : 0;
            }
        }

        return [
            'service_count' => $serviceCount,
            'monthly_revenue' => $monthlyRevenue
        ];
    }

    /**
     * Handle job failure
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Customer balance update job failed permanently', [
            'customer_id' => $this->customerId,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}
