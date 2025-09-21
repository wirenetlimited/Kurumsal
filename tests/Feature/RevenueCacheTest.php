<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Service;
use App\Services\RevenueCacheService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class RevenueCacheTest extends TestCase
{
    use RefreshDatabase;

    private RevenueCacheService $revenueCache;

    protected function setUp(): void
    {
        parent::setUp();
        $this->revenueCache = app(RevenueCacheService::class);
        Cache::flush(); // Test başında cache'i temizle
    }

    /** @test */
    public function it_caches_monthly_revenue_data()
    {
        // İlk çağrı - cache oluşturulur
        $data1 = $this->revenueCache->getMonthlyRevenueData(12);

        // İkinci çağrı - cache'den alınır
        $data2 = $this->revenueCache->getMonthlyRevenueData(12);

        // Veriler aynı olmalı
        $this->assertEquals($data1, $data2);
        
        // Cache'de veri olmalı
        $this->assertTrue(Cache::has('revenue_data_12'));
    }

    /** @test */
    public function it_caches_mrr_data()
    {
        // İlk çağrı
        $mrr1 = $this->revenueCache->getMRRData();
        
        // İkinci çağrı
        $mrr2 = $this->revenueCache->getMRRData();

        // Veriler aynı olmalı
        $this->assertEquals($mrr1, $mrr2);
        
        // Cache'de veri olmalı
        $this->assertTrue(Cache::has('mrr_current'));
    }

    /** @test */
    public function it_caches_mrr_by_type()
    {
        // İlk çağrı
        $mrrByType1 = $this->revenueCache->getMRRByType();
        
        // İkinci çağrı
        $mrrByType2 = $this->revenueCache->getMRRByType();

        // Veriler aynı olmalı
        $this->assertEquals($mrrByType1, $mrrByType2);
        
        // Cache'de veri olmalı
        $this->assertTrue(Cache::has('mrr_by_type'));
    }

    /** @test */
    public function it_clears_cache_when_data_changes()
    {
        // İlk veriyi al
        $data1 = $this->revenueCache->getMonthlyRevenueData(12);
        
        // Cache'de veri olmalı
        $this->assertTrue(Cache::has('revenue_data_12'));

        // Cache'i temizle
        $this->revenueCache->clearCache();

        // Cache temizlenmiş olmalı
        $this->assertFalse(Cache::has('revenue_data_12'));
    }

    /** @test */
    public function it_caches_this_month_revenue()
    {
        // Bu ay gelir verilerini al
        $thisMonthRevenue = $this->revenueCache->getThisMonthRevenue();

        // Veri yapısı doğru olmalı
        $this->assertArrayHasKey('issued', $thisMonthRevenue);
        $this->assertArrayHasKey('collected', $thisMonthRevenue);
        $this->assertArrayHasKey('remaining', $thisMonthRevenue);
        $this->assertArrayHasKey('month', $thisMonthRevenue);

        // Cache'de veri olmalı
        $this->assertTrue(Cache::has('monthly_revenue_' . now()->format('Y-m')));
    }

    /** @test */
    public function it_caches_total_revenue_stats()
    {
        // Toplam gelir istatistiklerini al
        $stats = $this->revenueCache->getTotalRevenueStats();

        // Veri yapısı doğru olmalı
        $this->assertArrayHasKey('total_revenue', $stats);
        $this->assertArrayHasKey('avg_monthly_revenue', $stats);
        $this->assertArrayHasKey('max_monthly_revenue', $stats);
        $this->assertArrayHasKey('monthly_payments', $stats);

        // Cache'de veri olmalı
        $this->assertTrue(Cache::has('total_revenue_stats'));
    }


}
