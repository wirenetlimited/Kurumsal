<?php

namespace App\Console\Commands;

use App\Models\Service;
use App\Models\Customer;
use Illuminate\Console\Command;
use Carbon\Carbon;

class TestDashboard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:dashboard';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test dashboard expiring services functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Dashboard Test Başlatılıyor...');
        $this->newLine();

        // Test 1: Yakında biten hizmetler sorgusu
        $this->info('Test 1: Yakında Biten Hizmetler Sorgusu');
        
        $expiringServices = Service::select([
            'id', 'customer_id', 'service_type', 'end_date', 'status'
        ])
            ->with(['customer:id,name,surname,email'])
            ->where('status', 'active')
            ->whereNotNull('end_date')
            ->whereDate('end_date', '<=', now()->addDays(30))
            ->whereDate('end_date', '>=', now())
            ->orderBy('end_date')
            ->limit(8)
            ->get()
            ->map(function ($service) {
                if ($service->end_date) {
                    $days = (int)now()->diffInDays($service->end_date, false);
                    $service->days_remaining = abs((int)$days);
                } else {
                    $service->days_remaining = null;
                }
                return $service;
            })
            ->filter(function ($service) {
                return $service->customer;
            });

        $this->info("Bulunan hizmet sayısı: {$expiringServices->count()}");
        
        if ($expiringServices->count() > 0) {
            $this->table(
                ['ID', 'Müşteri', 'Hizmet Türü', 'Bitiş Tarihi', 'Kalan Gün'],
                $expiringServices->map(function ($service) {
                    return [
                        $service->id,
                        $service->customer->name ?? 'Bilinmeyen',
                        $service->service_type ?? 'Bilinmeyen',
                        $service->end_date?->format('d.m.Y') ?? 'Belirtilmemiş',
                        $service->days_remaining ?? 'Belirtilmemiş'
                    ];
                })->toArray()
            );
        } else {
            $this->warn('Yakında biten hizmet bulunamadı!');
        }

        // Test 2: Test hizmeti oluştur
        $this->newLine();
        $this->info('Test 2: Test Hizmeti Oluşturma');
        
        $customer = Customer::first();
        if ($customer) {
            $testService = Service::create([
                'customer_id' => $customer->id,
                'service_type' => 'ssl',
                'status' => 'active',
                'start_date' => now()->subMonths(2),
                'end_date' => now()->addDays(3),
                'cycle' => 'yearly',
                'payment_type' => 'installment',
                'sell_price' => 75.00
            ]);
            
            $this->info("Test hizmeti oluşturuldu: ID {$testService->id}");
            $this->info("Bitiş tarihi: {$testService->end_date->format('d.m.Y')}");
            $this->info("Kalan gün: " . now()->diffInDays($testService->end_date, false));
        } else {
            $this->error('Müşteri bulunamadı!');
        }

        $this->newLine();
        $this->info('✅ Dashboard Test Tamamlandı!');
    }
}
