<?php

namespace App\Console\Commands;

use App\Services\RevenueCacheService;
use Illuminate\Console\Command;

class ClearRevenueCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'revenue:clear-cache {--all : Tüm cache\'leri temizle} {--mrr : Sadece MRR cache\'ini temizle}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revenue cache\'ini temizle';

    /**
     * Execute the console command.
     */
    public function handle(RevenueCacheService $revenueCache): int
    {
        $all = $this->option('all');
        $mrr = $this->option('mrr');

        if ($all) {
            $this->info('Tüm revenue cache\'leri temizleniyor...');
            $revenueCache->clearCache();
            $this->info('✅ Tüm revenue cache\'leri temizlendi.');
        } elseif ($mrr) {
            $this->info('MRR cache\'leri temizleniyor...');
            \Illuminate\Support\Facades\Cache::forget('mrr_current');
            \Illuminate\Support\Facades\Cache::forget('mrr_by_type');
            $this->info('✅ MRR cache\'leri temizlendi.');
        } else {
            $this->info('Revenue cache\'leri temizleniyor...');
            $revenueCache->clearCache();
            $this->info('✅ Revenue cache\'leri temizlendi.');
        }

        return Command::SUCCESS;
    }
}

