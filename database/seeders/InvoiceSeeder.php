<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Service;
use App\Models\Customer;
use Carbon\Carbon;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hizmetleri al
        $services = Service::with('customer')->get();
        
        if ($services->isEmpty()) {
            $this->command->error('Hizmet bulunamadı! Önce ServiceSeeder çalıştırın.');
            return;
        }

        $invoicesCreated = 0;
        $totalAmount = 0;
        $totalCollected = 0;

        foreach ($services as $service) {
            // Her hizmet için fatura oluştur
            $invoice = $this->createInvoiceFromService($service);
            
            if ($invoice) {
                $invoicesCreated++;
                $totalAmount += $invoice->total;
                
                // Fatura durumuna göre tahsilat yap
                $this->processInvoicePayment($invoice);
                
                if ($invoice->status === 'paid') {
                    $totalCollected += $invoice->total;
                }
            }
        }

        $this->command->info("Toplam {$invoicesCreated} fatura başarıyla oluşturuldu!");
        $this->command->info("Toplam Fatura Tutarı: ₺" . number_format($totalAmount, 2, ',', '.'));
        $this->command->info("Toplam Tahsilat: ₺" . number_format($totalAmount, 2, ',', '.'));
        $this->command->info("Tahsilat Oranı: %" . round(($totalCollected / $totalAmount) * 100, 2));
    }

    /**
     * Hizmetten fatura oluştur
     */
    private function createInvoiceFromService($service)
    {
        // Fatura durumları ve senaryoları
        $scenarios = [
            'paid' => 0.4,        // %40 - Tamamen ödenmiş
            'overdue' => 0.25,    // %25 - Gecikmiş
            'sent' => 0.2,        // %20 - Gönderilmiş
            'draft' => 0.15       // %15 - Taslak
        ];

        // Rastgele durum seç
        $status = $this->getRandomStatus($scenarios);
        
        // Fatura tarihleri
        $issueDate = $this->getRandomDate($service->start_date, $service->end_date);
        $dueDate = $issueDate->copy()->addDays(rand(15, 45)); // 15-45 gün vade
        
        // Fiyat hesaplamaları
        $subtotal = $service->sell_price;
        $taxRate = 20; // %20 KDV
        $taxAmount = ($subtotal * $taxRate) / 100;
        $total = $subtotal + $taxAmount;
        
        // İndirim (bazı faturalarda)
        $discountAmount = 0;
        if (rand(1, 10) <= 3) { // %30 ihtimalle indirim
            $discountAmount = round($subtotal * (rand(5, 15) / 100), 2); // %5-15 indirim
            $total = $total - $discountAmount;
        }

        // Fatura oluştur
        $invoice = Invoice::create([
            'customer_id' => $service->customer_id,
            'issue_date' => $issueDate,
            'due_date' => $dueDate,
            'status' => $status,
            'currency' => 'TRY',
            'subtotal' => $subtotal,
            'tax_total' => $taxAmount,
            'total' => $total,
            'withholding' => 0,
            'invoice_number' => 'INV-' . str_pad((string)(Invoice::max('id') + 1), 6, '0', STR_PAD_LEFT)
        ]);

        // Fatura kalemleri ekle
        $this->createInvoiceItems($invoice, $service, $discountAmount);

        return $invoice;
    }

    /**
     * Fatura kalemleri oluştur
     */
    private function createInvoiceItems($invoice, $service, $discountAmount)
    {
        // Ana hizmet kalemi
        $lineTotal = $service->sell_price;
        
        // İndirim varsa orantılı dağıt
        if ($discountAmount > 0) {
            $lineTotal = $lineTotal - $discountAmount;
        }

        InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'service_id' => $service->id,
            'description' => $service->service_type . ' - ' . $this->getServiceDescription($service->service_type),
            'qty' => 1,
            'unit_price' => $service->sell_price,
            'tax_rate' => 20,
            'line_total' => $lineTotal
        ]);
    }

    /**
     * Fatura ödeme işlemi
     */
    private function processInvoicePayment($invoice)
    {
        switch ($invoice->status) {
            case 'paid':
                // Tamamen ödenmiş - birden fazla ödeme olabilir
                $this->createMultiplePayments($invoice);
                break;
                
            case 'overdue':
                // Gecikmiş - hiç ödeme yok veya kısmi ödeme
                if (rand(1, 10) <= 3) { // %30 ihtimalle kısmi ödeme
                    $this->createPartialPayment($invoice);
                }
                break;
                
            case 'draft':
            case 'sent':
                // Henüz ödeme yok
                break;
        }
    }

    /**
     * Birden fazla ödeme oluştur
     */
    private function createMultiplePayments($invoice)
    {
        $remainingAmount = $invoice->total;
        $paymentCount = rand(2, 4); // 2-4 ödeme
        
        for ($i = 0; $i < $paymentCount && $remainingAmount > 0; $i++) {
            $paymentAmount = $i === $paymentCount - 1 ? $remainingAmount : rand(10, (int)($remainingAmount * 0.8));
            $paymentAmount = min($paymentAmount, $remainingAmount);
            
            $this->createPayment($invoice, $paymentAmount, $i + 1);
            $remainingAmount -= $paymentAmount;
        }
    }

    /**
     * Kısmi ödeme oluştur
     */
    private function createPartialPayment($invoice)
    {
        $paymentAmount = rand((int)($invoice->total * 0.3), (int)($invoice->total * 0.7));
        $this->createPayment($invoice, $paymentAmount, 1);
    }

    /**
     * Ödeme kaydı oluştur
     */
    private function createPayment($invoice, $amount, $paymentNumber)
    {
        $paymentMethods = ['bank_transfer', 'credit_card', 'cash', 'check'];
        $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
        
        $paymentDate = $invoice->issue_date->copy()->addDays(rand(1, 30));
        
        // Payment modeli yoksa ledger entry olarak kaydet
        $this->createLedgerEntry($invoice, $amount, $paymentMethod, $paymentDate, $paymentNumber);
    }

    /**
     * Ledger entry oluştur (ödeme kaydı)
     */
    private function createLedgerEntry($invoice, $amount, $method, $date, $paymentNumber)
    {
        // Ledger entries tablosuna ödeme kaydı ekle
        \DB::table('ledger_entries')->insert([
            'customer_id' => $invoice->customer_id,
            'related_type' => 'App\Models\Invoice',
            'related_id' => $invoice->id,
            'entry_date' => $date,
            'debit' => 0,
            'credit' => $amount,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Rastgele durum seç
     */
    private function getRandomStatus($scenarios)
    {
        $rand = rand(1, 100);
        $cumulative = 0;
        
        foreach ($scenarios as $status => $probability) {
            $cumulative += $probability * 100;
            if ($rand <= $cumulative) {
                return $status;
            }
        }
        
        return 'paid'; // Varsayılan
    }

    /**
     * Rastgele tarih oluştur
     */
    private function getRandomDate($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        if ($start->gt($end)) {
            $start = $end->copy()->subDays(30);
        }
        
        return $start->copy()->addDays(rand(0, $start->diffInDays($end)));
    }

    /**
     * Hizmet açıklaması
     */
    private function getServiceDescription($serviceType)
    {
        return match($serviceType) {
            'domain' => 'Domain Kayıt ve Yönetim Hizmeti',
            'hosting' => 'Web Hosting Hizmeti',
            'ssl' => 'SSL Sertifika Hizmeti',
            'email' => 'E-posta Hosting Hizmeti',
            'maintenance' => 'Web Sitesi Bakım Hizmeti',
            'development' => 'Web Geliştirme Hizmeti',
            default => 'Genel Hizmet'
        };
    }

    /**
     * Fatura notları oluştur
     */
    private function generateInvoiceNotes($service, $status, $discountAmount)
    {
        $notes = "Hizmet: {$service->service_type} - {$service->notes}";
        
        if ($discountAmount > 0) {
            $notes .= " | İndirim: ₺" . number_format($discountAmount, 2, ',', '.');
        }
        
        $notes .= " | KDV: %20";
        
        switch ($status) {
            case 'paid':
                $notes .= " | Durum: Tamamen ödendi";
                break;
            case 'overdue':
                $notes .= " | Durum: Gecikmiş ödeme";
                break;
            case 'draft':
                $notes .= " | Durum: Taslak";
                break;
            case 'sent':
                $notes .= " | Durum: Müşteriye gönderildi";
                break;
        }
        
        return $notes;
    }
}
