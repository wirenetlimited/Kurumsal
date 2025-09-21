<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class ComprehensiveSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Site Ayarları - Genişletilmiş
            ['key' => 'site_name', 'value' => 'WH Kurumsal', 'type' => 'text', 'label' => 'Site Adı', 'description' => 'Sitenizin görünen adı'],
            ['key' => 'site_description', 'value' => 'Kurumsal Web Hosting Yönetim Sistemi', 'type' => 'textarea', 'label' => 'Site Açıklaması', 'description' => 'Sitenizin meta açıklaması'],
            ['key' => 'site_keywords', 'value' => 'hosting, web hosting, kurumsal, yönetim sistemi', 'type' => 'textarea', 'label' => 'Site Anahtar Kelimeleri', 'description' => 'SEO için anahtar kelimeler'],
            ['key' => 'site_logo', 'value' => null, 'type' => 'file', 'label' => 'Site Logo', 'description' => 'Ana logo dosyası (PNG, JPG)'],
            ['key' => 'site_favicon', 'value' => null, 'type' => 'file', 'label' => 'Site Favicon', 'description' => 'Favicon dosyası (ICO, PNG)'],
            ['key' => 'site_theme', 'value' => 'default', 'type' => 'select', 'label' => 'Site Teması', 'description' => 'Kullanılacak tema', 'options' => ['default' => 'Varsayılan', 'dark' => 'Koyu Tema', 'light' => 'Açık Tema']],
            
            // İletişim Bilgileri
            ['key' => 'contact_email', 'value' => 'info@whkurumsal.com', 'type' => 'email', 'label' => 'İletişim E-posta', 'description' => 'Ana iletişim e-posta adresi'],
            ['key' => 'contact_phone', 'value' => '+90 212 123 4567', 'type' => 'text', 'label' => 'İletişim Telefon', 'description' => 'Ana iletişim telefon numarası'],
            ['key' => 'contact_address', 'value' => 'İstanbul, Türkiye', 'type' => 'textarea', 'label' => 'İletişim Adresi', 'description' => 'Şirket adresi'],
            ['key' => 'contact_working_hours', 'value' => 'Pazartesi - Cuma: 09:00 - 18:00', 'type' => 'text', 'label' => 'Çalışma Saatleri', 'description' => 'Şirket çalışma saatleri'],
            ['key' => 'contact_support_email', 'value' => 'destek@whkurumsal.com', 'type' => 'email', 'label' => 'Destek E-posta', 'description' => 'Teknik destek e-posta adresi'],
            ['key' => 'contact_sales_email', 'value' => 'satis@whkurumsal.com', 'type' => 'email', 'label' => 'Satış E-posta', 'description' => 'Satış e-posta adresi'],
            
            // Sosyal Medya
            ['key' => 'social_facebook', 'value' => 'https://facebook.com/whkurumsal', 'type' => 'text', 'label' => 'Facebook URL', 'description' => 'Facebook sayfa linki'],
            ['key' => 'social_twitter', 'value' => 'https://twitter.com/whkurumsal', 'type' => 'text', 'label' => 'Twitter URL', 'description' => 'Twitter sayfa linki'],
            ['key' => 'social_instagram', 'value' => 'https://instagram.com/whkurumsal', 'type' => 'text', 'label' => 'Instagram URL', 'description' => 'Instagram sayfa linki'],
            ['key' => 'social_linkedin', 'value' => 'https://linkedin.com/company/whkurumsal', 'type' => 'text', 'label' => 'LinkedIn URL', 'description' => 'LinkedIn şirket sayfası'],
            ['key' => 'social_youtube', 'value' => 'https://youtube.com/whkurumsal', 'type' => 'text', 'label' => 'YouTube URL', 'description' => 'YouTube kanal linki'],
            
            // Finansal Ayarlar - Genişletilmiş
            ['key' => 'currency', 'value' => 'TRY', 'type' => 'select', 'label' => 'Para Birimi', 'description' => 'Ana para birimi', 'options' => ['TRY' => 'Türk Lirası', 'USD' => 'Amerikan Doları', 'EUR' => 'Euro']],
            ['key' => 'currency_symbol', 'value' => '₺', 'type' => 'text', 'label' => 'Para Birimi Sembolü', 'description' => 'Para birimi sembolü'],
            ['key' => 'tax_rate', 'value' => '18', 'type' => 'number', 'label' => 'KDV Oranı (%)', 'description' => 'Varsayılan KDV oranı'],
            ['key' => 'withholding_tax_rate', 'value' => '20', 'type' => 'number', 'label' => 'Stopaj Vergi Oranı (%)', 'description' => 'Stopaj vergi oranı'],
            ['key' => 'tax_number', 'value' => '1234567890', 'type' => 'text', 'label' => 'Vergi Numarası', 'description' => 'Şirket vergi numarası'],
            ['key' => 'bank_name', 'value' => 'Türkiye İş Bankası', 'type' => 'text', 'label' => 'Banka Adı', 'description' => 'Ana banka adı'],
            ['key' => 'bank_iban', 'value' => 'TR33 0006 4000 0001 2345 6789 01', 'type' => 'text', 'label' => 'Banka IBAN', 'description' => 'Banka IBAN numarası'],
            ['key' => 'bank_account_number', 'value' => '1234567890', 'type' => 'text', 'label' => 'Banka Hesap No', 'description' => 'Banka hesap numarası'],
            ['key' => 'payment_methods', 'value' => '["bank_transfer","credit_card","cash"]', 'type' => 'json', 'label' => 'Ödeme Yöntemleri', 'description' => 'Kabul edilen ödeme yöntemleri'],
            ['key' => 'invoice_prefix', 'value' => 'INV', 'type' => 'text', 'label' => 'Fatura Öneki', 'description' => 'Fatura numaraları için önek'],
            ['key' => 'quote_prefix', 'value' => 'QUO', 'type' => 'text', 'label' => 'Teklif Öneki', 'description' => 'Teklif numaraları için önek'],
            ['key' => 'invoice_start_number', 'value' => '1000', 'type' => 'number', 'label' => 'Fatura Başlangıç No', 'description' => 'Fatura numaraları başlangıç değeri'],
            ['key' => 'quote_start_number', 'value' => '1000', 'type' => 'number', 'label' => 'Teklif Başlangıç No', 'description' => 'Teklif numaraları başlangıç değeri'],
            ['key' => 'auto_invoice_numbering', 'value' => '1', 'type' => 'boolean', 'label' => 'Otomatik Fatura Numaralandırma', 'description' => 'Fatura numaralarını otomatik artır'],
            ['key' => 'auto_quote_numbering', 'value' => '1', 'type' => 'boolean', 'label' => 'Otomatik Teklif Numaralandırma', 'description' => 'Teklif numaralarını otomatik artır'],
            
            // Güvenlik Ayarları - Genişletilmiş
            ['key' => 'password_min_length', 'value' => '8', 'type' => 'number', 'label' => 'Minimum Şifre Uzunluğu', 'description' => 'Şifreler için minimum karakter sayısı'],
            ['key' => 'password_require_uppercase', 'value' => '1', 'type' => 'boolean', 'label' => 'Büyük Harf Zorunlu', 'description' => 'Şifrelerde büyük harf zorunlu olsun'],
            ['key' => 'password_require_numbers', 'value' => '1', 'type' => 'boolean', 'label' => 'Rakam Zorunlu', 'description' => 'Şifrelerde rakam zorunlu olsun'],
            ['key' => 'password_require_symbols', 'value' => '0', 'type' => 'boolean', 'label' => 'Özel Karakter Zorunlu', 'description' => 'Şifrelerde özel karakter zorunlu olsun'],
            ['key' => 'password_expiry_days', 'value' => '90', 'type' => 'number', 'label' => 'Şifre Geçerlilik Süresi (Gün)', 'description' => 'Şifrelerin geçerlilik süresi'],
            ['key' => 'session_timeout', 'value' => '120', 'type' => 'number', 'label' => 'Oturum Zaman Aşımı (Dakika)', 'description' => 'Oturum zaman aşımı süresi'],
            ['key' => 'max_login_attempts', 'value' => '5', 'type' => 'number', 'label' => 'Maksimum Giriş Denemesi', 'description' => 'Hesap kilitleme öncesi maksimum deneme'],
            ['key' => 'lockout_duration', 'value' => '30', 'type' => 'number', 'label' => 'Hesap Kilitleme Süresi (Dakika)', 'description' => 'Hesap kilitleme süresi'],
            ['key' => 'two_factor_auth', 'value' => '0', 'type' => 'boolean', 'label' => 'İki Faktörlü Doğrulama', 'description' => 'İki faktörlü doğrulama aktif olsun'],
            ['key' => 'allowed_ips', 'value' => null, 'type' => 'textarea', 'label' => 'İzin Verilen IP Adresleri', 'description' => 'Sadece belirli IP adreslerinden erişim'],
            ['key' => 'secure_cookies', 'value' => '1', 'type' => 'boolean', 'label' => 'Güvenli Çerezler', 'description' => 'HTTPS üzerinden çerez gönderimi'],
            ['key' => 'same_site_policy', 'value' => 'lax', 'type' => 'select', 'label' => 'Same-Site Çerez Politikası', 'description' => 'Çerez same-site politikası', 'options' => ['lax' => 'Lax', 'strict' => 'Strict', 'none' => 'None']],
            ['key' => 'http_only_cookies', 'value' => '1', 'type' => 'boolean', 'label' => 'HTTP-Only Çerezler', 'description' => 'JavaScript erişimini engelle'],
            ['key' => 'https_required', 'value' => '0', 'type' => 'boolean', 'label' => 'HTTPS Zorunlu', 'description' => 'Tüm bağlantılar HTTPS olsun'],
            ['key' => 'csrf_protection', 'value' => '1', 'type' => 'boolean', 'label' => 'CSRF Koruması', 'description' => 'CSRF token koruması aktif'],
            ['key' => 'xss_protection', 'value' => '1', 'type' => 'boolean', 'label' => 'XSS Koruması', 'description' => 'XSS koruması aktif'],
            ['key' => 'content_security_policy', 'value' => '1', 'type' => 'boolean', 'label' => 'CSP Politikası', 'description' => 'Content Security Policy aktif'],
            
            // Sistem Ayarları - Genişletilmiş
            ['key' => 'timezone', 'value' => 'Europe/Istanbul', 'type' => 'select', 'label' => 'Zaman Dilimi', 'description' => 'Sistem zaman dilimi', 'options' => ['Europe/Istanbul' => 'İstanbul (UTC+3)', 'UTC' => 'UTC', 'Europe/London' => 'Londra (UTC+0)']],
            ['key' => 'locale', 'value' => 'tr', 'type' => 'select', 'label' => 'Dil', 'description' => 'Sistem dili', 'options' => ['tr' => 'Türkçe', 'en' => 'English']],
            ['key' => 'date_format', 'value' => 'd.m.Y', 'type' => 'select', 'label' => 'Tarih Formatı', 'description' => 'Tarih gösterim formatı', 'options' => ['d.m.Y' => 'DD.MM.YYYY', 'Y-m-d' => 'YYYY-MM-DD', 'd/m/Y' => 'DD/MM/YYYY']],
            ['key' => 'time_format', 'value' => 'H:i', 'type' => 'select', 'label' => 'Saat Formatı', 'description' => 'Saat gösterim formatı', 'options' => ['H:i' => '24 Saat', 'h:i A' => '12 Saat AM/PM']],
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean', 'label' => 'Bakım Modu', 'description' => 'Site bakım modunda olsun'],
            ['key' => 'maintenance_message', 'value' => 'Site bakımda, lütfen bekleyin...', 'type' => 'textarea', 'label' => 'Bakım Mesajı', 'description' => 'Bakım modunda gösterilecek mesaj'],
            ['key' => 'cache_enabled', 'value' => '1', 'type' => 'boolean', 'label' => 'Cache Aktif', 'description' => 'Sistem cache\'i aktif olsun'],
            ['key' => 'cache_duration', 'value' => '3600', 'type' => 'number', 'label' => 'Cache Süresi (Saniye)', 'description' => 'Cache geçerlilik süresi'],
            ['key' => 'log_level', 'value' => 'info', 'type' => 'select', 'label' => 'Log Seviyesi', 'description' => 'Sistem log seviyesi', 'options' => ['debug' => 'Debug', 'info' => 'Info', 'warning' => 'Warning', 'error' => 'Error']],
            ['key' => 'log_retention_days', 'value' => '30', 'type' => 'number', 'label' => 'Log Saklama Süresi (Gün)', 'description' => 'Log dosyalarının saklanma süresi'],
            ['key' => 'backup_enabled', 'value' => '1', 'type' => 'boolean', 'label' => 'Otomatik Yedekleme', 'description' => 'Otomatik yedekleme aktif olsun'],
            ['key' => 'backup_frequency', 'value' => 'daily', 'type' => 'select', 'label' => 'Yedekleme Sıklığı', 'description' => 'Yedekleme sıklığı', 'options' => ['daily' => 'Günlük', 'weekly' => 'Haftalık', 'monthly' => 'Aylık']],
            ['key' => 'backup_retention', 'value' => '7', 'type' => 'number', 'label' => 'Yedek Saklama Sayısı', 'description' => 'Saklanacak yedek sayısı'],
            
            // E-posta Ayarları - Genişletilmiş
            ['key' => 'mail_mailer', 'value' => 'smtp', 'type' => 'select', 'label' => 'E-posta Gönderici', 'description' => 'E-posta gönderim yöntemi', 'options' => ['smtp' => 'SMTP', 'sendmail' => 'Sendmail', 'mail' => 'PHP Mail', 'log' => 'Log (Test)']],
            ['key' => 'mail_host', 'value' => 'smtp.gmail.com', 'type' => 'text', 'label' => 'SMTP Sunucu', 'description' => 'SMTP sunucu adresi'],
            ['key' => 'mail_port', 'value' => '587', 'type' => 'number', 'label' => 'SMTP Port', 'description' => 'SMTP port numarası'],
            ['key' => 'mail_username', 'value' => '', 'type' => 'text', 'label' => 'SMTP Kullanıcı Adı', 'description' => 'SMTP kullanıcı adı'],
            ['key' => 'mail_password', 'value' => '', 'type' => 'password', 'label' => 'SMTP Şifre', 'description' => 'SMTP şifresi'],
            ['key' => 'mail_encryption', 'value' => 'tls', 'type' => 'select', 'label' => 'SMTP Şifreleme', 'description' => 'SMTP bağlantı şifrelemesi', 'options' => ['tls' => 'TLS', 'ssl' => 'SSL', 'none' => 'Yok']],
            ['key' => 'mail_from_address', 'value' => 'info@whkurumsal.com', 'type' => 'email', 'label' => 'Gönderen E-posta', 'description' => 'Gönderen e-posta adresi'],
            ['key' => 'mail_from_name', 'value' => 'WH Kurumsal', 'type' => 'text', 'label' => 'Gönderen Adı', 'description' => 'Gönderen adı'],
            ['key' => 'mail_reply_to', 'value' => 'destek@whkurumsal.com', 'type' => 'email', 'label' => 'Yanıt E-posta', 'description' => 'Yanıt için e-posta adresi'],
            ['key' => 'mail_signature', 'value' => 'WH Kurumsal Ekibi', 'type' => 'textarea', 'label' => 'E-posta İmzası', 'description' => 'E-postalarda kullanılacak imza'],
            ['key' => 'mail_queue_enabled', 'value' => '0', 'type' => 'boolean', 'label' => 'E-posta Kuyruğu', 'description' => 'E-postaları kuyruk ile gönder'],
            ['key' => 'mail_max_retries', 'value' => '3', 'type' => 'number', 'label' => 'Maksimum Yeniden Deneme', 'description' => 'Başarısız e-postalar için yeniden deneme sayısı'],
            
            // Bildirim Ayarları
            ['key' => 'notifications_enabled', 'value' => '1', 'type' => 'boolean', 'label' => 'Bildirimler Aktif', 'description' => 'Sistem bildirimleri aktif olsun'],
            ['key' => 'email_notifications', 'value' => '1', 'type' => 'boolean', 'label' => 'E-posta Bildirimleri', 'description' => 'E-posta ile bildirim gönder'],
            ['key' => 'sms_notifications', 'value' => '0', 'type' => 'boolean', 'label' => 'SMS Bildirimleri', 'description' => 'SMS ile bildirim gönder'],
            ['key' => 'push_notifications', 'value' => '0', 'type' => 'boolean', 'label' => 'Push Bildirimleri', 'description' => 'Push bildirimleri aktif olsun'],
            ['key' => 'notification_sound', 'value' => '1', 'type' => 'boolean', 'label' => 'Bildirim Sesi', 'description' => 'Bildirimlerde ses çalsın'],
            
            // API Ayarları
            ['key' => 'api_enabled', 'value' => '1', 'type' => 'boolean', 'label' => 'API Aktif', 'description' => 'REST API aktif olsun'],
            ['key' => 'api_rate_limit', 'value' => '100', 'type' => 'number', 'label' => 'API Hız Limiti', 'description' => 'Dakikada maksimum API isteği'],
            ['key' => 'api_key_expiry_days', 'value' => '365', 'type' => 'number', 'label' => 'API Anahtar Geçerlilik (Gün)', 'description' => 'API anahtarlarının geçerlilik süresi'],
            ['key' => 'webhook_enabled', 'value' => '0', 'type' => 'boolean', 'label' => 'Webhook Aktif', 'description' => 'Webhook bildirimleri aktif olsun'],
            ['key' => 'webhook_url', 'value' => '', 'type' => 'text', 'label' => 'Webhook URL', 'description' => 'Webhook bildirimleri için URL'],
            ['key' => 'webhook_secret', 'value' => '', 'type' => 'password', 'label' => 'Webhook Gizli Anahtar', 'description' => 'Webhook doğrulama için gizli anahtar'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'group' => $this->getGroupForKey($setting['key']),
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                    'label' => $setting['label'],
                    'description' => $setting['description'],
                    'options' => $setting['options'] ?? null,
                    'is_public' => false,
                    'sort_order' => 0
                ]
            );
        }

        echo "Kapsamlı ayarlar başarıyla eklendi!\n";
    }

    /**
     * Get group for setting key
     */
    private function getGroupForKey($key)
    {
        if (str_starts_with($key, 'site_') || str_starts_with($key, 'contact_') || str_starts_with($key, 'social_')) {
            return 'site';
        }
        
        if (str_starts_with($key, 'currency') || str_starts_with($key, 'tax_') || str_starts_with($key, 'bank_') || str_starts_with($key, 'payment_') || str_starts_with($key, 'invoice_') || str_starts_with($key, 'quote_')) {
            return 'financial';
        }
        
        if (str_starts_with($key, 'password_') || str_starts_with($key, 'session_') || str_starts_with($key, 'max_login_') || str_starts_with($key, 'lockout_') || str_starts_with($key, 'two_factor_') || str_starts_with($key, 'allowed_ips') || str_starts_with($key, 'secure_') || str_starts_with($key, 'same_site_') || str_starts_with($key, 'http_only_') || str_starts_with($key, 'https_required') || str_starts_with($key, 'csrf_') || str_starts_with($key, 'xss_') || str_starts_with($key, 'content_security_')) {
            return 'security';
        }
        
        if (str_starts_with($key, 'timezone') || str_starts_with($key, 'locale') || str_starts_with($key, 'date_') || str_starts_with($key, 'time_') || str_starts_with($key, 'maintenance_') || str_starts_with($key, 'cache_') || str_starts_with($key, 'log_') || str_starts_with($key, 'backup_')) {
            return 'system';
        }
        
        if (str_starts_with($key, 'mail_') || str_starts_with($key, 'email_')) {
            return 'email';
        }
        
        if (str_starts_with($key, 'notification_') || str_starts_with($key, 'api_') || str_starts_with($key, 'webhook_')) {
            return 'advanced';
        }
        
        return 'custom';
    }
}
