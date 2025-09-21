<?php

namespace App;

use App\Models\Setting;

trait SettingsHelper
{
    /**
     * Get setting value
     */
    protected function getSetting($key, $default = null)
    {
        return Setting::get($key, $default);
    }

    /**
     * Set setting value
     */
    protected function setSetting($key, $value)
    {
        return Setting::set($key, $value);
    }

    /**
     * Get site settings
     */
    protected function getSiteSettings()
    {
        return [
            'name' => $this->getSetting('site_name', 'WH Kurumsal'),
            'description' => $this->getSetting('site_description', 'Kurumsal Web Hosting Yönetim Sistemi'),
            'logo' => $this->getSetting('site_logo'),
            'favicon' => $this->getSetting('site_favicon'),
            'contact_email' => $this->getSetting('contact_email', 'info@whkurumsal.com'),
            'contact_phone' => $this->getSetting('contact_phone', '+90 212 123 4567'),
            'contact_address' => $this->getSetting('contact_address', 'İstanbul, Türkiye'),
            'tax_number' => $this->getSetting('tax_number'),
            'bank_iban' => $this->getSetting('bank_iban'),
            'social_facebook' => $this->getSetting('social_facebook'),
            'social_twitter' => $this->getSetting('social_twitter'),
            'social_instagram' => $this->getSetting('social_instagram'),
            'social_linkedin' => $this->getSetting('social_linkedin'),
        ];
    }

    /**
     * Get financial settings
     */
    protected function getFinancialSettings()
    {
        return [
            'currency' => $this->getSetting('currency', 'TRY'),
            'currency_symbol' => $this->getSetting('currency_symbol', '₺'),
            'tax_rate' => (float) $this->getSetting('tax_rate', 18),
            'withholding_tax_rate' => (float) $this->getSetting('withholding_tax_rate', 20),
            'tax_number' => $this->getSetting('tax_number'),
            'bank_name' => $this->getSetting('bank_name'),
            'bank_iban' => $this->getSetting('bank_iban'),
            'payment_methods' => json_decode($this->getSetting('payment_methods', '["bank_transfer","credit_card","cash"]'), true),
            'invoice_prefix' => $this->getSetting('invoice_prefix', 'INV'),
            'invoice_start_number' => (int) $this->getSetting('invoice_start_number', 1000),
        ];
    }

    /**
     * Get security settings
     */
    protected function getSecuritySettings()
    {
        return [
            'password_min_length' => (int) $this->getSetting('password_min_length', 8),
            'password_require_uppercase' => (bool) $this->getSetting('password_require_uppercase', true),
            'password_require_numbers' => (bool) $this->getSetting('password_require_numbers', true),
            'password_require_symbols' => (bool) $this->getSetting('password_require_symbols', false),
            'session_timeout' => (int) $this->getSetting('session_timeout', 120),
            'max_login_attempts' => (int) $this->getSetting('max_login_attempts', 5),
            'lockout_duration' => (int) $this->getSetting('lockout_duration', 30),
            'two_factor_auth' => (bool) $this->getSetting('two_factor_auth', false),
            'allowed_ips' => $this->getSetting('allowed_ips'),
        ];
    }

    /**
     * Get system settings
     */
    protected function getSystemSettings()
    {
        return [
            'timezone' => $this->getSetting('timezone', 'Europe/Istanbul'),
            'date_format' => $this->getSetting('date_format', 'd.m.Y'),
            'time_format' => $this->getSetting('time_format', 'H:i'),
            'locale' => $this->getSetting('locale', 'tr'),
            'maintenance_mode' => (bool) $this->getSetting('maintenance_mode', false),
            'cache_enabled' => (bool) $this->getSetting('cache_enabled', true),
            'log_level' => $this->getSetting('log_level', 'info'),
        ];
    }

    /**
     * Get email settings
     */
    protected function getEmailSettings()
    {
        return [
            'mail_from_name' => $this->getSetting('mail_from_name', 'WH Kurumsal'),
            'mail_from_address' => $this->getSetting('mail_from_address', 'info@whkurumsal.com'),
            'email_signature' => $this->getSetting('email_signature', 'WH Kurumsal\nİstanbul, Türkiye\ninfo@whkurumsal.com'),
        ];
    }

    /**
     * Format currency
     */
    protected function formatCurrency($amount)
    {
        $settings = $this->getFinancialSettings();
        return $settings['currency_symbol'] . number_format($amount, 2, ',', '.');
    }

    /**
     * Calculate tax
     */
    protected function calculateTax($amount, $taxRate = null)
    {
        if ($taxRate === null) {
            $settings = $this->getFinancialSettings();
            $taxRate = $settings['tax_rate'];
        }
        return $amount * ($taxRate / 100);
    }

    /**
     * Generate invoice number with concurrency protection
     */
    protected function generateInvoiceNumber()
    {
        $settings = $this->getFinancialSettings();
        $prefix = $settings['invoice_prefix'] ?? 'INV';
        $startNumber = $settings['invoice_start_number'] ?? 1;
        
        // Database transaction ile yarış koşullarını önle
        return \DB::transaction(function () use ($prefix, $startNumber) {
            // En son geçerli fatura numarasını bul (NULL olmayan)
            $lastInvoice = \App\Models\Invoice::whereNotNull('invoice_number')
                ->orderBy('id', 'desc')
                ->first();
            
            if ($lastInvoice && $lastInvoice->invoice_number) {
                // Mevcut numaradan sonraki numarayı al
                // Format: INV-000001-2025 -> 000001 kısmını al
                if (preg_match('/^' . preg_quote($prefix, '/') . '-(\d+)-(\d{4})$/', $lastInvoice->invoice_number, $matches)) {
                    $lastNumber = (int) $matches[1];
                    $nextNumber = $lastNumber + 1;
                } else {
                    // Eğer format uygun değilse başlangıç numarasını kullan
                    $nextNumber = $startNumber;
                }
            } else {
                // Hiç fatura yoksa veya tüm faturalar NULL ise başlangıç numarasını kullan
                $nextNumber = $startNumber;
            }
            
            // Format: INV-000001-2025
            $year = date('Y');
            $formattedNumber = $prefix . '-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT) . '-' . $year;
            
            // Bu numaranın benzersiz olduğunu kontrol et
            while (\App\Models\Invoice::where('invoice_number', $formattedNumber)->exists()) {
                $nextNumber++;
                $formattedNumber = $prefix . '-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT) . '-' . $year;
            }
            
            return $formattedNumber;
        });
    }

    /**
     * Get company info for emails
     */
    protected function getCompanyInfo()
    {
        $siteSettings = $this->getSiteSettings();
        $financialSettings = $this->getFinancialSettings();
        
        return [
            'name' => $siteSettings['name'],
            'description' => $siteSettings['description'],
            'email' => $siteSettings['contact_email'],
            'phone' => $siteSettings['contact_phone'],
            'address' => $siteSettings['contact_address'],
            'tax_number' => $financialSettings['tax_number'],
            'bank_name' => $financialSettings['bank_name'],
            'bank_iban' => $financialSettings['bank_iban'],
            'social' => [
                'facebook' => $siteSettings['social_facebook'],
                'twitter' => $siteSettings['social_twitter'],
                'instagram' => $siteSettings['social_instagram'],
                'linkedin' => $siteSettings['social_linkedin'],
            ]
        ];
    }
}
