<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Skip all settings during installation
        if ($this->isInstalling()) {
            return;
        }

        // Session hardening - dynamic timeout from settings
        try {
            // Check if settings table exists first
            if (\Schema::hasTable('settings')) {
                $sessionTimeout = Setting::get('session_timeout', 120);
                config(['session.lifetime' => (int) $sessionTimeout]);
                
                // Security settings override
                $secureCookies = Setting::get('secure_cookies', true);
                $sameSitePolicy = Setting::get('same_site_policy', 'lax');
                $httpOnlyCookies = Setting::get('http_only_cookies', true);
                $httpsRequired = Setting::get('https_required', false);
                
                config([
                    'session.secure' => $secureCookies,
                    'session.same_site' => $sameSitePolicy,
                    'session.http_only' => $httpOnlyCookies,
                    'app.force_https' => $httpsRequired,
                ]);
            }
        } catch (\Throwable $e) {
            \Log::warning('Session hardening config failed', [
                'error' => $e->getMessage()
            ]);
        }

        // Dynamic HTTPS enforcement middleware registration
        try {
            // Check if settings table exists first
            if (\Schema::hasTable('settings')) {
                $httpsRequired = Setting::get('https_required', false);
                
                if ($httpsRequired && !app()->environment('local')) {
                    // Register HTTPS enforcement middleware dynamically
                    $router = app('router');
                    $router->pushMiddlewareToGroup('web', \App\Http\Middleware\EnforceHttps::class);
                }
            }
        } catch (\Throwable $e) {
            // DB hazır değilse sessiz geç
            if (app()->environment('local', 'staging')) {
                \Log::warning('HTTPS middleware registration failed', [
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Override mail configuration from database settings
        $this->overrideMailConfiguration();

        // Override system configuration from database settings
        $this->overrideSystemConfiguration();
    }

    /**
     * Override mail configuration from database settings
     */
    private function overrideMailConfiguration(): void
    {
        try {
            // Check if settings table exists and has email settings
            if (!\Schema::hasTable('settings')) {
                return;
            }

            // Get email settings from database
            $mailMailer = Setting::get('mail_mailer');
            $mailHost = Setting::get('mail_host');
            $mailPort = Setting::get('mail_port');
            $mailUsername = Setting::get('mail_username');
            $mailPassword = Setting::get('mail_password');
            $mailEncryption = Setting::get('mail_encryption');
            $mailFromAddress = Setting::get('mail_from_address');
            $mailFromName = Setting::get('mail_from_name');

            // Override mail configuration if settings exist
            if ($mailMailer) {
                Config::set('mail.default', $mailMailer);
            }
            if ($mailHost) {
                Config::set('mail.mailers.smtp.host', $mailHost);
            }
            if ($mailPort) {
                Config::set('mail.mailers.smtp.port', $mailPort);
            }
            if ($mailUsername) {
                Config::set('mail.mailers.smtp.username', $mailUsername);
            }
            if ($mailPassword) {
                Config::set('mail.mailers.smtp.password', $mailPassword);
            }
            if ($mailEncryption) {
                Config::set('mail.mailers.smtp.encryption', $mailEncryption);
            }
            if ($mailFromAddress) {
                Config::set('mail.from.address', $mailFromAddress);
            }
            if ($mailFromName) {
                Config::set('mail.from.name', $mailFromName);
            }
        } catch (\Throwable $e) {
            // DB hazır değilse sessiz geç
            if (app()->environment('local', 'staging')) {
                \Log::warning('Mail configuration override failed', [
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Override system configuration from database settings
     */
    private function overrideSystemConfiguration(): void
    {
        try {
            // Check if settings table exists
            if (!\Schema::hasTable('settings')) {
                return;
            }

            // Get system settings from database
            $timezone = Setting::get('timezone');
            $locale = Setting::get('locale');
            $dateFormat = Setting::get('date_format');
            $timeFormat = Setting::get('time_format');
            $maintenanceMode = Setting::get('maintenance_mode');
            $cacheEnabled = Setting::get('cache_enabled');
            $logLevel = Setting::get('log_level');

            // Override system configuration if settings exist
            if ($timezone) {
                Config::set('app.timezone', $timezone);
            }
            if ($locale) {
                Config::set('app.locale', $locale);
            }
            if ($dateFormat) {
                Config::set('app.date_format', $dateFormat);
            }
            if ($timeFormat) {
                Config::set('app.time_format', $timeFormat);
            }
            if ($maintenanceMode !== null) {
                Config::set('app.maintenance_mode', $maintenanceMode);
            }
            if ($cacheEnabled !== null) {
                Config::set('cache.default', $cacheEnabled ? 'database' : 'file');
            }
            if ($logLevel) {
                Config::set('logging.level', $logLevel);
            }
        } catch (\Throwable $e) {
            // DB hazır değilse sessiz geç
            if (app()->environment('local', 'staging')) {
                \Log::warning('System configuration override failed', [
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Check if we are in installation mode
     */
    private function isInstalling(): bool
    {
        $path = request()->path();
        return str_starts_with($path, 'install') || 
               str_starts_with($path, 'public/install') ||
               str_contains($path, 'install');
    }
}