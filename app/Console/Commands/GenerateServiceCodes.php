<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Service;

class GenerateServiceCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'services:generate-codes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mevcut hizmetlere benzersiz random kodlar ekler';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Hizmet kodları oluşturuluyor...');

        $services = Service::whereNull('service_code')->get();
        $updated = 0;

        foreach ($services as $service) {
            $code = Service::generateUniqueServiceCode();
            $service->update(['service_code' => $code]);
            $updated++;
            $this->line("✓ Hizmet #{$service->id}: {$code}");
        }

        $this->info("Toplam {$updated} hizmet güncellendi.");
        $this->info('Hizmet kodları başarıyla oluşturuldu!');
    }
}
