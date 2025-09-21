<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Service;
use App\Models\Domain;
use App\Models\Hosting;

class GenerateServiceIdentifiers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'services:generate-identifiers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mevcut hizmetlere benzersiz tanımlayıcılar ekler';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Hizmet tanımlayıcıları oluşturuluyor...');

        $services = Service::all();
        $updated = 0;

        foreach ($services as $service) {
            $identifier = $this->generateIdentifier($service);
            
            if ($identifier) {
                $service->update(['service_identifier' => $identifier]);
                $updated++;
                $this->line("✓ Hizmet #{$service->id}: {$identifier}");
            }
        }

        $this->info("Toplam {$updated} hizmet güncellendi.");
        $this->info('Hizmet tanımlayıcıları başarıyla oluşturuldu!');
    }

    /**
     * Hizmet türüne göre benzersiz tanımlayıcı oluştur
     */
    private function generateIdentifier(Service $service): ?string
    {
        switch ($service->service_type) {
            case 'domain':
                $domain = $service->domain;
                if ($domain && $domain->domain_name) {
                    return $domain->domain_name;
                }
                break;

            case 'hosting':
                $hosting = $service->hosting;
                if ($hosting) {
                    $parts = [];
                    if ($hosting->plan_name) $parts[] = $hosting->plan_name;
                    if ($hosting->server_name) $parts[] = $hosting->server_name;
                    
                    if (!empty($parts)) {
                        return implode(' - ', $parts);
                    }
                }
                break;

            case 'ssl':
                return 'SSL Sertifikası';
                
            case 'email':
                return 'E-posta Paketi';
                
            default:
                return ucfirst($service->service_type);
        }

        return null;
    }
}
