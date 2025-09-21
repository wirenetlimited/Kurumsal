<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class VersionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:version {action?} {--show : Show current version information} {--update : Update version number} {--changelog : Show changelog}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage application version information';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action') ?? 'show';
        
        switch ($action) {
            case 'show':
                $this->showVersion();
                break;
            case 'update':
                $this->updateVersion();
                break;
            case 'changelog':
                $this->showChangelog();
                break;
            default:
                $this->showVersion();
                break;
        }
        
        return 0;
    }
    
    /**
     * Show current version information
     */
    protected function showVersion()
    {
        $version = config('version.version');
        $releaseDate = config('version.release_date');
        $codename = config('version.codename');
        $description = config('version.description');
        
        $this->info('🚀 WH Kurumsal Uygulama Sürüm Bilgileri');
        $this->line('');
        $this->table(
            ['Özellik', 'Değer'],
            [
                ['Sürüm', $version],
                ['Yayın Tarihi', $releaseDate],
                ['Kod Adı', $codename],
                ['Açıklama', $description],
            ]
        );
        
        $this->line('');
        $this->info('🔧 Sürüm Yönetimi Komutları:');
        $this->line('  php artisan app:version show      - Mevcut sürüm bilgilerini göster');
        $this->line('  php artisan app:version update    - Sürüm numarasını güncelle');
        $this->line('  php artisan app:version changelog - Sürüm notlarını göster');
    }
    
    /**
     * Update version number
     */
    protected function updateVersion()
    {
        $currentVersion = config('version.version');
        
        $this->info("Mevcut sürüm: {$currentVersion}");
        
        $newVersion = $this->ask('Yeni sürüm numarası (örn: 2.1.0):');
        
        if (!$this->isValidVersion($newVersion)) {
            $this->error('Geçersiz sürüm formatı! Lütfen MAJOR.MINOR.PATCH formatında girin.');
            return 1;
        }
        
        $releaseDate = $this->ask('Yayın tarihi (YYYY-MM-DD):', date('Y-m-d'));
        $codename = $this->ask('Kod adı (opsiyonel):', '');
        $description = $this->ask('Sürüm açıklaması:', '');
        
        // Update config file
        $this->updateConfigFile($newVersion, $releaseDate, $codename, $description);
        
        // Update CHANGELOG.md
        $this->updateChangelog($newVersion, $releaseDate, $description);
        
        $this->info("✅ Sürüm başarıyla güncellendi: {$newVersion}");
        
        return 0;
    }
    
    /**
     * Show changelog
     */
    protected function showChangelog()
    {
        $changelogPath = base_path('CHANGELOG.md');
        
        if (!File::exists($changelogPath)) {
            $this->error('CHANGELOG.md dosyası bulunamadı!');
            return 1;
        }
        
        $content = File::get($changelogPath);
        
        $this->info('📋 CHANGELOG.md İçeriği:');
        $this->line('');
        $this->line($content);
        
        return 0;
    }
    
    /**
     * Validate version format
     */
    protected function isValidVersion($version)
    {
        return preg_match('/^\d+\.\d+\.\d+$/', $version);
    }
    
    /**
     * Update config file
     */
    protected function updateConfigFile($version, $releaseDate, $codename, $description)
    {
        $configPath = config_path('version.php');
        $content = File::get($configPath);
        
        // Update version
        $content = preg_replace(
            "/'version' => env\('APP_VERSION', '[^']*'\)/",
            "'version' => env('APP_VERSION', '{$version}')",
            $content
        );
        
        // Update release date
        $content = preg_replace(
            "/'release_date' => env\('APP_RELEASE_DATE', '[^']*'\)/",
            "'release_date' => env('APP_RELEASE_DATE', '{$releaseDate}')",
            $content
        );
        
        // Update codename
        if ($codename) {
            $content = preg_replace(
                "/'codename' => env\('APP_VERSION_CODENAME', '[^']*'\)/",
                "'codename' => env('APP_VERSION_CODENAME', '{$codename}')",
                $content
            );
        }
        
        // Update description
        if ($description) {
            $content = preg_replace(
                "/'description' => env\('APP_VERSION_DESCRIPTION', '[^']*'\)/",
                "'description' => env('APP_VERSION_DESCRIPTION', '{$description}')",
                $content
            );
        }
        
        File::put($configPath, $content);
    }
    
    /**
     * Update CHANGELOG.md
     */
    protected function updateChangelog($version, $releaseDate, $description)
    {
        $changelogPath = base_path('CHANGELOG.md');
        
        if (!File::exists($changelogPath)) {
            return;
        }
        
        $content = File::get($changelogPath);
        
        // Add new version entry at the top
        $newEntry = "## [{$version}] - {$releaseDate}\n\n### 🚀 **{$description}**\n- **Sürüm Güncellemesi**: {$version} sürümü yayınlandı\n- **Tarih**: {$releaseDate}\n\n---\n\n";
        
        // Insert after the first heading
        $content = preg_replace(
            '/(# CHANGELOG\n\n)/',
            '$1' . $newEntry,
            $content
        );
        
        File::put($changelogPath, $content);
    }
}
