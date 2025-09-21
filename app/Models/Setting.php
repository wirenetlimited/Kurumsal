<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
        'label',
        'description',
        'options',
        'is_public',
        'sort_order'
    ];

    protected $casts = [
        'options' => 'array',
        'is_public' => 'boolean',
        'sort_order' => 'integer'
    ];

    // Şifrelenmesi gereken hassas alanlar
    protected $encryptedFields = [
        'mail_password',
        'mail_username',
        'database_password',
        'api_key',
        'secret_key',
        'webhook_secret',
        'oauth_client_secret',
        'jwt_secret',
        'encryption_key',
        'session_secret',
        'csrf_token_secret'
    ];

    /**
     * Boot method - model events
     */
    protected static function boot()
    {
        parent::boot();

        // Model kaydedilmeden önce hassas alanları şifrele
        static::saving(function ($setting) {
            if (in_array($setting->key, $setting->encryptedFields) && !empty($setting->value)) {
                $setting->value = Crypt::encryptString($setting->value);
            }
        });

        // Model yüklenirken hassas alanları çöz
        static::retrieved(function ($setting) {
            if (in_array($setting->key, $setting->encryptedFields) && !empty($setting->value)) {
                try {
                    $setting->value = Crypt::decryptString($setting->value);
                } catch (\Exception $e) {
                    // Şifre çözülemezse orijinal değeri koru (eski veriler için)
                    $setting->value = $setting->getRawOriginal('value');
                }
            }
        });
    }

    /**
     * Get setting value by key
     */
    public static function get($key, $default = null)
    {
        return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set setting value
     */
    public static function set($key, $value)
    {
        $setting = static::where('key', $key)->first();
        
        if ($setting) {
            $setting->update(['value' => $value]);
        } else {
            $setting = static::create([
                'key' => $key,
                'value' => $value,
                'group' => 'custom',
                'type' => 'text',
                'label' => ucfirst(str_replace('_', ' ', $key))
            ]);
        }

        // İlgili cache'leri temizle
        Cache::forget("setting.{$key}");
        Cache::forget('settings.all');
        
        // Group cache'ini de temizle
        if ($setting->group) {
            Cache::forget("settings.group.{$setting->group}");
        }
        
        // Database cache'den ilgili setting cache'ini temizle
        if (config('cache.default') === 'database') {
            \Illuminate\Support\Facades\DB::table('cache')
                ->where('key', 'like', "laravel-cache-setting.{$key}")
                ->delete();
        }
        
        return $setting;
    }

    /**
     * Get encrypted setting value (raw encrypted value from DB)
     */
    public static function getEncrypted($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->getRawOriginal('value') : $default;
    }

    /**
     * Check if a setting key should be encrypted
     */
    public static function isEncrypted($key): bool
    {
        return in_array($key, (new static())->encryptedFields);
    }

    /**
     * Get all encrypted field names
     */
    public static function getEncryptedFields(): array
    {
        return (new static())->encryptedFields;
    }

    /**
     * Get settings by group
     */
    public static function getGroup($group)
    {
        return Cache::remember("settings.group.{$group}", 3600, function () use ($group) {
            // Artık group kolonu mevcut, direkt filtreleme yap
            return static::where('group', $group)
                ->orderBy('sort_order')
                ->get()
                ->keyBy('key');
        });
    }

    /**
     * Get all settings as array
     */
    public static function getAll()
    {
        return Cache::remember('settings.all', 3600, function () {
            return static::all()->keyBy('key')->map(function ($setting) {
                return $setting->value;
            })->toArray();
        });
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache()
    {
        // Ana cache key'leri temizle
        Cache::forget('settings.all');
        
        // Tüm setting key'lerini bul ve cache'lerini temizle
        $settings = static::all();
        foreach ($settings as $setting) {
            Cache::forget("setting.{$setting->key}");
        }
        
        // Group cache'lerini temizle - group kolonu mevcut değil, sadece genel cache'leri temizle
        Cache::forget("settings.group.site");
        Cache::forget("settings.group.financial");
        Cache::forget("settings.group.security");
        Cache::forget("settings.group.system");
        Cache::forget("settings.group.email");
        Cache::forget("settings.group.service_statuses");
        
        // Database cache'den tüm setting cache'lerini temizle
        if (config('cache.default') === 'database') {
            \Illuminate\Support\Facades\DB::table('cache')
                ->where('key', 'like', 'laravel-cache-setting%')
                ->delete();
        }
    }

    /**
     * Initialize default settings
     */
    public static function initializeDefaults()
    {
        $defaults = [
            // Site Settings
            ['key' => 'site_name', 'value' => 'WH Kurumsal', 'type' => 'text'],
            ['key' => 'site_description', 'value' => 'Kurumsal Web Hosting Yönetim Sistemi', 'type' => 'textarea'],
            ['key' => 'site_logo', 'value' => null, 'type' => 'file'],
            ['key' => 'site_favicon', 'value' => null, 'type' => 'file'],
            ['key' => 'contact_email', 'value' => 'info@whkurumsal.com', 'type' => 'email'],
            ['key' => 'contact_phone', 'value' => '+90 212 123 4567', 'type' => 'text'],
            ['key' => 'contact_address', 'value' => 'İstanbul, Türkiye', 'type' => 'textarea'],

            // Financial Settings
            ['key' => 'currency', 'value' => 'TRY', 'type' => 'select'],
            ['key' => 'currency_symbol', 'value' => '₺', 'type' => 'text'],
            ['key' => 'tax_rate', 'value' => '18', 'type' => 'number'],
            ['key' => 'withholding_tax_rate', 'value' => '20', 'type' => 'number'],
            ['key' => 'tax_number', 'value' => null, 'type' => 'text'],
            ['key' => 'bank_name', 'value' => null, 'type' => 'text'],
            ['key' => 'bank_iban', 'value' => null, 'type' => 'text'],
            ['key' => 'payment_methods', 'value' => json_encode(['bank_transfer', 'credit_card', 'cash']), 'type' => 'text'],
            ['key' => 'invoice_prefix', 'value' => 'INV', 'type' => 'text'],
            ['key' => 'invoice_start_number', 'value' => '1000', 'type' => 'number'],

            // Security Settings
            ['key' => 'password_min_length', 'value' => '8', 'type' => 'number'],
            ['key' => 'password_require_uppercase', 'value' => '1', 'type' => 'boolean'],
            ['key' => 'password_require_numbers', 'value' => '1', 'type' => 'boolean'],
            ['key' => 'password_require_symbols', 'value' => '0', 'type' => 'boolean'],
            ['key' => 'session_timeout', 'value' => '120', 'type' => 'number'],
            ['key' => 'max_login_attempts', 'value' => '5', 'type' => 'number'],
            ['key' => 'lockout_duration', 'value' => '30', 'type' => 'number'],
            ['key' => 'two_factor_auth', 'value' => '0', 'type' => 'boolean'],
            ['key' => 'allowed_ips', 'value' => null, 'type' => 'textarea'],
            ['key' => 'secure_cookies', 'value' => '1', 'type' => 'boolean'],
            ['key' => 'same_site_policy', 'value' => 'lax', 'type' => 'select'],
            ['key' => 'http_only_cookies', 'value' => '1', 'type' => 'boolean'],
            ['key' => 'https_required', 'value' => '0', 'type' => 'boolean'],
            ['key' => 'csrf_protection', 'value' => '1', 'type' => 'boolean'],
            ['key' => 'xss_protection', 'value' => '1', 'type' => 'boolean'],
            ['key' => 'content_security_policy', 'value' => '1', 'type' => 'boolean'],
            ['key' => 'hsts_enabled', 'value' => '0', 'type' => 'boolean'],
            ['key' => 'hsts_max_age', 'value' => '31536000', 'type' => 'number'],
            ['key' => 'frame_options', 'value' => 'DENY', 'type' => 'select'],
            ['key' => 'content_type_options', 'value' => 'nosniff', 'type' => 'select'],
            ['key' => 'referrer_policy', 'value' => 'strict-origin-when-cross-origin', 'type' => 'select'],
            ['key' => 'permissions_policy', 'value' => 'geolocation=(), microphone=(), camera=(), payment=(), usb=(), magnetometer=(), gyroscope=(), accelerometer=(), ambient-light-sensor=(), autoplay=(), encrypted-media=(), fullscreen=(), picture-in-picture=(), publickey-credentials-get=(), sync-xhr=(), clipboard-read=(), clipboard-write=(), display-capture=(), document-domain=(), execution-while-not-rendered=(), execution-while-out-of-viewport=(), focus-without-user-activation=(), cross-origin-isolated=(), identity-credentials-get=(), payment=(), publickey-credentials-create=(), screen-wake-lock=(), web-share=(), xr-spatial-tracking=()', 'type' => 'textarea'],
            ['key' => 'csp_policy', 'value' => "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' data:; connect-src 'self'; frame-ancestors 'none'; base-uri 'self'; form-action 'self';", 'type' => 'textarea'],

            // System Settings
            ['key' => 'timezone', 'value' => 'Europe/Istanbul', 'type' => 'select'],
            ['key' => 'date_format', 'value' => 'd.m.Y', 'type' => 'select'],
            ['key' => 'time_format', 'value' => 'H:i', 'type' => 'select'],
            ['key' => 'locale', 'value' => 'tr', 'type' => 'select'],
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean'],
            ['key' => 'cache_enabled', 'value' => '1', 'type' => 'boolean'],
            ['key' => 'log_level', 'value' => 'info', 'type' => 'select'],

            // Email Settings
            ['key' => 'mail_from_name', 'value' => 'WH Kurumsal', 'type' => 'text'],
            ['key' => 'mail_from_address', 'value' => 'info@whkurumsal.com', 'type' => 'email'],
            ['key' => 'email_signature', 'value' => 'WH Kurumsal\nİstanbul, Türkiye\ninfo@whkurumsal.com', 'type' => 'textarea'],

            // Service Status Settings
            ['key' => 'service_statuses', 'value' => json_encode([]), 'type' => 'json'],
            ['key' => 'invoice_statuses', 'value' => json_encode([]), 'type' => 'json'],
        ];

        foreach ($defaults as $default) {
            static::firstOrCreate(
                ['key' => $default['key']],
                $default
            );
        }
    }
}
