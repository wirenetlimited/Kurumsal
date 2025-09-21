<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class RevenueCacheStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'revenue:cache-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revenue cache durumunu göster';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('📊 Revenue Cache Durumu');
        $this->newLine();

        $cacheKeys = [
            'revenue_data_12' => '12 Aylık Revenue Verileri',
            'revenue_data_6' => '6 Aylık Revenue Verileri',
            'revenue_data_3' => '3 Aylık Revenue Verileri',
            'mrr_current' => 'Mevcut MRR',
            'mrr_by_type' => 'MRR Hizmet Türüne Göre',
            'monthly_revenue_' . now()->format('Y-m') => 'Bu Ay Gelir Verileri',
            'total_revenue_stats' => 'Toplam Gelir İstatistikleri'
        ];

        $table = [];
        foreach ($cacheKeys as $key => $description) {
            $exists = Cache::has($key);
            $table[] = [
                $key,
                $description,
                $exists ? '✅ Aktif' : '❌ Yok',
                $exists ? $this->formatCacheSize($key) : '-'
            ];
        }

        $this->table(['Cache Key', 'Açıklama', 'Durum', 'Boyut'], $table);

        $this->newLine();
        $this->info('💡 Cache Temizleme Komutları:');
        $this->line('  php artisan revenue:clear-cache          # Tüm revenue cache\'lerini temizle');
        $this->line('  php artisan revenue:clear-cache --mrr     # Sadece MRR cache\'lerini temizle');
        $this->line('  php artisan revenue:clear-cache --all     # Tüm cache\'leri temizle');

        return Command::SUCCESS;
    }

    /**
     * Cache boyutunu formatla
     */
    private function formatCacheSize(string $key): string
    {
        $value = Cache::get($key);
        if (!$value) {
            return '-';
        }

        $size = strlen(serialize($value));
        
        if ($size < 1024) {
            return $size . ' B';
        } elseif ($size < 1024 * 1024) {
            return round($size / 1024, 2) . ' KB';
        } else {
            return round($size / (1024 * 1024), 2) . ' MB';
        }
    }
}

