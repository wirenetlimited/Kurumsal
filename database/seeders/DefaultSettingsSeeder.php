<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class DefaultSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Site Ayarları
            ['key' => 'site_name', 'value' => 'WH Kurumsal', 'type' => 'text'],
            ['key' => 'site_description', 'value' => 'Kurumsal Web Hosting Yönetim Sistemi', 'type' => 'textarea'],
            ['key' => 'contact_email', 'value' => 'info@whkurumsal.com', 'type' => 'email'],
            ['key' => 'contact_phone', 'value' => '+90 212 123 4567', 'type' => 'text'],
            ['key' => 'contact_address', 'value' => 'İstanbul, Türkiye', 'type' => 'textarea'],
            
            // E-posta Ayarları
            ['key' => 'mail_mailer', 'value' => 'log', 'type' => 'select'],
            ['key' => 'mail_host', 'value' => '', 'type' => 'text'],
            ['key' => 'mail_port', 'value' => '587', 'type' => 'number'],
            ['key' => 'mail_username', 'value' => '', 'type' => 'text'],
            ['key' => 'mail_password', 'value' => '', 'type' => 'text'],
            ['key' => 'mail_encryption', 'value' => 'tls', 'type' => 'select'],
            ['key' => 'mail_from_address', 'value' => 'info@whkurumsal.com', 'type' => 'email'],
            ['key' => 'mail_from_name', 'value' => 'WH Kurumsal', 'type' => 'text'],
            
            // Finansal Ayarlar
            ['key' => 'currency', 'value' => 'TRY', 'type' => 'select'],
            ['key' => 'currency_symbol', 'value' => '₺', 'type' => 'text'],
            ['key' => 'tax_rate', 'value' => '18', 'type' => 'number'],
            ['key' => 'withholding_tax_rate', 'value' => '20', 'type' => 'number'],
            ['key' => 'tax_number', 'value' => '1234567890', 'type' => 'text'],
            ['key' => 'bank_name', 'value' => 'Türkiye İş Bankası', 'type' => 'text'],
            ['key' => 'bank_iban', 'value' => 'TR33 0006 4000 0001 2345 6789 01', 'type' => 'text'],
            ['key' => 'payment_methods', 'value' => '["bank_transfer","credit_card","cash"]', 'type' => 'json'],
            ['key' => 'invoice_prefix', 'value' => 'INV', 'type' => 'text'],
            ['key' => 'invoice_start_number', 'value' => '1000', 'type' => 'number'],
            
            // Sistem Ayarları
            ['key' => 'timezone', 'value' => 'Europe/Istanbul', 'type' => 'select'],
            ['key' => 'date_format', 'value' => 'd.m.Y', 'type' => 'text'],
            ['key' => 'time_format', 'value' => 'H:i', 'type' => 'text'],
            ['key' => 'locale', 'value' => 'tr', 'type' => 'select'],
            
            // Servis Durumları
            ['key' => 'service_statuses', 'value' => '[
                {"value": "active", "label": "Aktif", "color": "green", "icon": "✅", "description": "Aktif hizmetler"},
                {"value": "expired", "label": "Süresi Dolmuş", "color": "red", "icon": "⏰", "description": "Süresi dolmuş hizmetler"},
                {"value": "suspended", "label": "Askıya Alınmış", "color": "yellow", "icon": "⏸️", "description": "Askıya alınmış hizmetler"},
                {"value": "cancelled", "label": "İptal Edilmiş", "color": "gray", "icon": "❌", "description": "İptal edilmiş hizmetler"}
            ]', 'type' => 'json'],
            ['key' => 'invoice_statuses', 'value' => '[
                {"value": "draft", "label": "Taslak", "color": "gray", "icon": "📝", "description": "Taslak faturalar"},
                {"value": "sent", "label": "Gönderildi", "color": "blue", "icon": "📤", "description": "Gönderilmiş faturalar"},
                {"value": "paid", "label": "Ödendi", "color": "green", "icon": "✅", "description": "Ödenmiş faturalar"},
                {"value": "overdue", "label": "Gecikmiş", "color": "red", "icon": "⚠️", "description": "Gecikmiş faturalar"},
                {"value": "cancelled", "label": "İptal Edildi", "color": "gray", "icon": "❌", "description": "İptal edilmiş faturalar"}
            ]', 'type' => 'json'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                    'group' => $this->getGroupFromKey($setting['key']),
                    'label' => $this->getLabelFromKey($setting['key']),
                    'description' => $this->getDescriptionFromKey($setting['key'])
                ]
            );
        }
    }

    /**
     * Get group from key
     */
    private function getGroupFromKey(string $key): string
    {
        if (in_array($key, ['site_name', 'site_description', 'contact_email', 'contact_phone', 'contact_address'])) {
            return 'site';
        }
        
        if (in_array($key, ['mail_mailer', 'mail_host', 'mail_port', 'mail_username', 'mail_password', 'mail_encryption', 'mail_from_address', 'mail_from_name'])) {
            return 'email';
        }
        
        if (in_array($key, ['currency', 'currency_symbol', 'tax_rate', 'withholding_tax_rate', 'tax_number', 'bank_name', 'bank_iban', 'payment_methods', 'invoice_prefix', 'invoice_start_number'])) {
            return 'financial';
        }
        
        if (in_array($key, ['timezone', 'date_format', 'time_format', 'locale'])) {
            return 'system';
        }
        
        if (in_array($key, ['service_statuses', 'invoice_statuses'])) {
            return 'service_statuses';
        }
        
        return 'general';
    }

    /**
     * Get label from key
     */
    private function getLabelFromKey(string $key): string
    {
        $labels = [
            'mail_mailer' => 'E-posta Sürücüsü',
            'mail_host' => 'SMTP Sunucu',
            'mail_port' => 'SMTP Port',
            'mail_username' => 'SMTP Kullanıcı Adı',
            'mail_password' => 'SMTP Şifre',
            'mail_encryption' => 'SMTP Şifreleme',
            'mail_from_address' => 'Gönderen E-posta',
            'mail_from_name' => 'Gönderen Adı',
            'service_statuses' => 'Hizmet Durumları',
            'invoice_statuses' => 'Fatura Durumları',
        ];

        return $labels[$key] ?? ucfirst(str_replace('_', ' ', $key));
    }

    /**
     * Get description from key
     */
    private function getDescriptionFromKey(string $key): string
    {
        $descriptions = [
            'mail_mailer' => 'E-posta gönderim yöntemi (SMTP, Log, Mailgun, vb.)',
            'mail_host' => 'SMTP sunucu adresi',
            'mail_port' => 'SMTP sunucu portu',
            'mail_username' => 'SMTP kullanıcı adı veya e-posta adresi',
            'mail_password' => 'SMTP şifresi veya app password',
            'mail_encryption' => 'SMTP bağlantı şifreleme türü',
            'mail_from_address' => 'Gönderen e-posta adresi',
            'mail_from_name' => 'Gönderen görünen adı',
            'service_statuses' => 'Hizmet durumları, etiketleri ve renkleri yönetin',
            'invoice_statuses' => 'Fatura durumları, etiketleri ve renkleri yönetin',
        ];

        return $descriptions[$key] ?? '';
    }
}
