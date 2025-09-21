<?php

require_once 'vendor/autoload.php';

use App\Services\RevenueCacheService;
use Illuminate\Support\Facades\Cache;

// Laravel app'i başlat
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🚀 Revenue Cache Performans Testi\n";
echo "================================\n\n";

$revenueCache = app(RevenueCacheService::class);

// Cache'i temizle
Cache::flush();
echo "✅ Cache temizlendi\n\n";

// Test 1: İlk çağrı (cache oluşturma)
echo "📊 Test 1: İlk çağrı (Cache oluşturma)\n";
$startTime = microtime(true);
$data1 = $revenueCache->getMonthlyRevenueData(12);
$firstCallTime = (microtime(true) - $startTime) * 1000; // ms cinsinden

echo "   ⏱️  Süre: " . number_format($firstCallTime, 2) . " ms\n";
echo "   📦 Veri boyutu: " . number_format(strlen(serialize($data1)), 0) . " bytes\n\n";

// Test 2: İkinci çağrı (cache'den okuma)
echo "📊 Test 2: İkinci çağrı (Cache'den okuma)\n";
$startTime = microtime(true);
$data2 = $revenueCache->getMonthlyRevenueData(12);
$secondCallTime = (microtime(true) - $startTime) * 1000; // ms cinsinden

echo "   ⏱️  Süre: " . number_format($secondCallTime, 2) . " ms\n";
echo "   📦 Veri boyutu: " . number_format(strlen(serialize($data2)), 0) . " bytes\n\n";

// Test 3: MRR hesaplama
echo "📊 Test 3: MRR Hesaplama\n";
$startTime = microtime(true);
$mrrData = $revenueCache->getMRRData();
$mrrTime = (microtime(true) - $startTime) * 1000;

echo "   ⏱️  Süre: " . number_format($mrrTime, 2) . " ms\n";
echo "   💰 MRR: ₺" . number_format($mrrData['total_mrr'], 2) . "\n\n";

// Test 4: MRR by type
echo "📊 Test 4: MRR Hizmet Türüne Göre\n";
$startTime = microtime(true);
$mrrByType = $revenueCache->getMRRByType();
$mrrByTypeTime = (microtime(true) - $startTime) * 1000;

echo "   ⏱️  Süre: " . number_format($mrrByTypeTime, 2) . " ms\n";
echo "   📊 Türler: " . implode(', ', array_keys($mrrByType)) . "\n\n";

// Test 5: Bu ay gelir
echo "📊 Test 5: Bu Ay Gelir\n";
$startTime = microtime(true);
$thisMonthRevenue = $revenueCache->getThisMonthRevenue();
$thisMonthTime = (microtime(true) - $startTime) * 1000;

echo "   ⏱️  Süre: " . number_format($thisMonthTime, 2) . " ms\n";
echo "   💰 Kesilen: ₺" . number_format($thisMonthRevenue['issued'], 2) . "\n";
echo "   💰 Tahsil: ₺" . number_format($thisMonthRevenue['collected'], 2) . "\n\n";

// Test 6: Toplam istatistikler
echo "📊 Test 6: Toplam İstatistikler\n";
$startTime = microtime(true);
$totalStats = $revenueCache->getTotalRevenueStats();
$totalStatsTime = (microtime(true) - $startTime) * 1000;

echo "   ⏱️  Süre: " . number_format($totalStatsTime, 2) . " ms\n";
echo "   💰 Toplam Gelir: ₺" . number_format($totalStats['total_revenue'], 2) . "\n";
echo "   📈 Ortalama Aylık: ₺" . number_format($totalStats['avg_monthly_revenue'], 2) . "\n\n";

// Performans özeti
echo "📈 PERFORMANS ÖZETİ\n";
echo "==================\n";
$speedup = $firstCallTime > 0 ? $firstCallTime / $secondCallTime : 0;
echo "   🚀 Hızlanma: " . number_format($speedup, 1) . "x\n";
echo "   ⏱️  İlk çağrı: " . number_format($firstCallTime, 2) . " ms\n";
echo "   ⚡ Cache'den: " . number_format($secondCallTime, 2) . " ms\n";
echo "   💾 Tasarruf: " . number_format($firstCallTime - $secondCallTime, 2) . " ms\n\n";

// Cache durumu
echo "💾 CACHE DURUMU\n";
echo "===============\n";
$cacheKeys = [
    'revenue_data_12' => '12 Aylık Revenue',
    'mrr_current' => 'Mevcut MRR',
    'mrr_by_type' => 'MRR by Type',
    'monthly_revenue_' . now()->format('Y-m') => 'Bu Ay Gelir',
    'total_revenue_stats' => 'Toplam İstatistikler'
];

foreach ($cacheKeys as $key => $description) {
    $exists = Cache::has($key);
    $status = $exists ? '✅ Aktif' : '❌ Yok';
    echo "   {$description}: {$status}\n";
}

echo "\n✅ Test tamamlandı!\n";

