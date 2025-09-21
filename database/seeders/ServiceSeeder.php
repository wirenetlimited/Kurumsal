<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Provider;
use Carbon\Carbon;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hizmet tipleri
        $serviceTypes = [
            'domain' => [
                'names' => ['Domain Kayıt', 'Domain Transfer', 'WHOIS Gizleme', 'DNS Yönetimi'],
                'cost_range' => [50, 200],
                'sell_range' => [80, 350]
            ],
            'hosting' => [
                'names' => ['Web Hosting', 'VPS Hosting', 'Dedicated Server', 'Cloud Hosting'],
                'cost_range' => [100, 800],
                'sell_range' => [150, 1200]
            ],
            'ssl' => [
                'names' => ['SSL Sertifikası', 'Wildcard SSL', 'EV SSL', 'Code Signing'],
                'cost_range' => [80, 500],
                'sell_range' => [120, 750]
            ],
            'email' => [
                'names' => ['E-posta Hosting', 'Exchange Hosting', 'Spam Filtreleme', 'E-posta Arşivleme'],
                'cost_range' => [30, 150],
                'sell_range' => [50, 250]
            ],
            'maintenance' => [
                'names' => ['Web Sitesi Bakım', 'Güvenlik Güncellemesi', 'Yedekleme Hizmeti', 'Teknik Destek'],
                'cost_range' => [200, 1000],
                'sell_range' => [300, 1500]
            ],
            'development' => [
                'names' => ['Web Geliştirme', 'Mobil Uygulama', 'E-ticaret Sitesi', 'API Geliştirme'],
                'cost_range' => [500, 5000],
                'sell_range' => [800, 8000]
            ]
        ];

        // Ödeme dönemleri
        $cycles = ['monthly', 'quarterly', 'semiannually', 'yearly', 'biennially', 'triennially'];

        // Ödeme tipleri
        $paymentTypes = ['upfront', 'installment'];

        // Durumlar
        $statuses = ['active', 'suspended', 'cancelled', 'expired'];

        // Müşterileri al
        $customers = Customer::all();
        $providers = Provider::all();

        if ($customers->isEmpty()) {
            $this->command->error('Müşteri bulunamadı! Önce CustomerSeeder çalıştırın.');
            return;
        }

        if ($providers->isEmpty()) {
            $this->command->error('Sağlayıcı bulunamadı!');
            return;
        }

        $servicesCreated = 0;

        foreach ($customers as $customer) {
            // Her müşteriye 1-3 hizmet ekle
            $serviceCount = rand(1, 3);
            
            for ($i = 0; $i < $serviceCount; $i++) {
                // Rastgele hizmet tipi seç
                $serviceType = array_rand($serviceTypes);
                $typeConfig = $serviceTypes[$serviceType];
                
                // Rastgele hizmet adı seç
                $serviceName = $typeConfig['names'][array_rand($typeConfig['names'])];
                
                // Rastgele maliyet ve satış fiyatı
                $costPrice = rand($typeConfig['cost_range'][0], $typeConfig['cost_range'][1]);
                $sellPrice = rand($typeConfig['cost_range'][1], $typeConfig['sell_range'][1]);
                
                // Kar marjı hesapla
                $profitMargin = round((($sellPrice - $costPrice) / $costPrice) * 100, 2);
                
                // Rastgele dönem seç
                $cycle = $cycles[array_rand($cycles)];
                
                // Rastgele ödeme tipi seç
                $paymentType = $paymentTypes[array_rand($paymentTypes)];
                
                // Rastgele durum seç (çoğunlukla aktif)
                $status = $statuses[array_rand($statuses)];
                if ($status !== 'active') {
                    $status = rand(1, 10) <= 7 ? 'active' : $status; // %70 aktif
                }
                
                // Rastgele başlangıç tarihi (son 1 yıl içinde)
                $startDate = Carbon::now()->subDays(rand(0, 365));
                
                // Bitiş tarihi (döneme göre)
                $endDate = $this->calculateEndDate($startDate, $cycle);
                
                // Rastgele sağlayıcı seç
                $provider = $providers->random();
                
                // Hizmet oluştur
                $service = Service::create([
                    'customer_id' => $customer->id,
                    'provider_id' => $provider->id,
                    'service_type' => $serviceType,
                    'status' => $status,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'cycle' => $cycle,
                    'cost_price' => $costPrice,
                    'sell_price' => $sellPrice,
                    'payment_type' => $paymentType,
                    'notes' => $this->generateServiceNotes($serviceType, $serviceName, $cycle, $paymentType, $profitMargin)
                ]);
                
                $servicesCreated++;
            }
        }

        $this->command->info("Toplam {$servicesCreated} hizmet başarıyla oluşturuldu!");
        $this->command->info("Her müşteriye 1-3 hizmet eklendi.");
        $this->command->info("Hizmet tipleri: " . implode(', ', array_keys($serviceTypes)));
        $this->command->info("Ödeme dönemleri: " . implode(', ', $cycles));
        $this->command->info("Ödeme tipleri: " . implode(', ', $paymentTypes));
    }

    /**
     * Döneme göre bitiş tarihi hesapla
     */
    private function calculateEndDate($startDate, $cycle)
    {
        return match($cycle) {
            'monthly' => $startDate->copy()->addMonth(),
            'quarterly' => $startDate->copy()->addMonths(3),
            'semiannually' => $startDate->copy()->addMonths(6),
            'yearly' => $startDate->copy()->addYear(),
            'biennially' => $startDate->copy()->addYears(2),
            'triennially' => $startDate->copy()->addYears(3),
            default => $startDate->copy()->addYear()
        };
    }

    /**
     * Hizmet notları oluştur
     */
    private function generateServiceNotes($serviceType, $serviceName, $cycle, $paymentType, $profitMargin)
    {
        $cycleText = match($cycle) {
            'monthly' => 'Aylık',
            'quarterly' => '3 Aylık',
            'semiannually' => '6 Aylık',
            'yearly' => 'Yıllık',
            'biennially' => '2 Yıllık',
            'triennially' => '3 Yıllık',
            default => 'Yıllık'
        };

        $paymentText = match($paymentType) {
            'upfront' => 'Ön Ödemeli',
            'installment' => 'Sonra Ödemeli',
            default => 'Standart'
        };

        return "{$serviceName} - {$cycleText} {$paymentText} Hizmet. Kar Marjı: %{$profitMargin}. Otomatik yenileme aktif.";
    }
}
