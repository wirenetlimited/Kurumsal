<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class SecuritySettingsController extends Controller
{
    /**
     * Display security settings page
     */
    public function index()
    {
        $settings = $this->getSecuritySettings();
        $securityStatus = $this->getSecurityStatus();
        
        return view('admin.security-settings.index', compact('settings', 'securityStatus'));
    }

    /**
     * Update security settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'password_min_length' => 'required|integer|min:6|max:50',
            'password_require_uppercase' => 'boolean',
            'password_require_numbers' => 'boolean',
            'password_require_symbols' => 'boolean',
            'session_timeout' => 'required|integer|min:5|max:1440',
            'secure_cookies' => 'boolean',
            'same_site_policy' => 'required|in:lax,strict,none',
            'https_required' => 'boolean',
            'hsts_enabled' => 'boolean',
            'hsts_max_age' => 'required_if:hsts_enabled,1|integer|min:300|max:31536000',
            'frame_options' => 'required|in:DENY,SAMEORIGIN,ALLOW-FROM',
            'content_type_options' => 'required|in:nosniff',
            'referrer_policy' => 'required|in:no-referrer,no-referrer-when-downgrade,origin,origin-when-cross-origin,same-origin,strict-origin,strict-origin-when-cross-origin,unsafe-url',
            'permissions_policy' => 'required|string|max:1000',
            'csp_policy' => 'required|string|max:1000',
            'csrf_protection' => 'boolean',
            'xss_protection' => 'boolean',
            'content_security_policy' => 'boolean',
        ]);

        // Persist to DB settings
        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        // Clear settings cache so runtime overrides pick up immediately
        Setting::clearCache();

        // Log the update for security audit
        Log::info('Security settings updated', [
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email,
            'updated_fields' => array_keys($validated),
            'ip_address' => $request->ip()
        ]);

        return back()->with('status', 'Güvenlik ayarları başarıyla güncellendi.');
    }

    /**
     * Get security settings from DB
     */
    public function getSecuritySettings()
    {
        $settings = [];
        
        // Password settings
        $settings['password_min_length'] = Setting::get('password_min_length', 8);
        $settings['password_require_uppercase'] = Setting::get('password_require_uppercase', false);
        $settings['password_require_numbers'] = Setting::get('password_require_numbers', false);
        $settings['password_require_symbols'] = Setting::get('password_require_symbols', false);
        
        // Session settings
        $settings['session_timeout'] = Setting::get('session_timeout', 120);
        $settings['secure_cookies'] = Setting::get('secure_cookies', true);
        $settings['same_site_policy'] = Setting::get('same_site_policy', 'lax');
        $settings['http_only_cookies'] = Setting::get('http_only_cookies', true);
        
        // HTTPS settings
        $settings['https_required'] = Setting::get('https_required', false);
        $settings['hsts_enabled'] = Setting::get('hsts_enabled', true);
        $settings['hsts_max_age'] = Setting::get('hsts_max_age', 31536000);
        
        // Security headers
        $settings['frame_options'] = Setting::get('frame_options', 'DENY');
        $settings['content_type_options'] = Setting::get('content_type_options', 'nosniff');
        $settings['referrer_policy'] = Setting::get('referrer_policy', 'strict-origin-when-cross-origin');
        $settings['permissions_policy'] = Setting::get('permissions_policy', 'geolocation=(), microphone=(), camera=(), payment=(), usb=(), magnetometer=(), gyroscope=(), accelerometer=(), ambient-light-sensor=(), autoplay=(), encrypted-media=(), fullscreen=(), picture-in-picture=(), publickey-credentials-get=(), sync-xhr=(), clipboard-read=(), clipboard-write=(), display-capture=(), document-domain=(), execution-while-not-rendered=(), execution-while-out-of-viewport=(), focus-without-user-activation=(), cross-origin-isolated=(), identity-credentials-get=(), payment=(), publickey-credentials-create=(), screen-wake-lock=(), web-share=(), xr-spatial-tracking=()');
        $settings['csp_policy'] = Setting::get('csp_policy', "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' data:; connect-src 'self'; frame-ancestors 'none'; base-uri 'self'; form-action 'self';");
        $settings['csrf_protection'] = Setting::get('csrf_protection', true);
        $settings['xss_protection'] = Setting::get('xss_protection', true);
        $settings['content_security_policy'] = Setting::get('content_security_policy', true);
        
        return $settings;
    }

    /**
     * Get current security status
     */
    public function getSecurityStatus()
    {
        $currentSettings = $this->getSecuritySettings();
        
        return [
            'session_secure' => config('session.secure'),
            'session_same_site' => config('session.same_site'),
            'session_http_only' => config('session.http_only'),
            'session_lifetime' => config('session.lifetime'),
            'https_enforced' => $currentSettings['https_required'] && !app()->environment('local'),
            'cookies_secure' => $currentSettings['secure_cookies'],
            'csrf_active' => $currentSettings['csrf_protection'],
            'xss_protection_active' => $currentSettings['xss_protection'],
            'csp_active' => $currentSettings['content_security_policy'],
            'hsts_active' => $currentSettings['hsts_enabled'],
        ];
    }

    /**
     * Test security configuration
     */
    public function testSecurity(Request $request)
    {
        $settings = $this->getSecuritySettings();
        
        // Test current security configuration
        $securityStatus = [
            'hsts' => [
                'label' => 'HSTS',
                'description' => 'HTTP Strict Transport Security',
                'status' => $settings['hsts_enabled'] ? 'enabled' : 'disabled',
                'value' => $settings['hsts_enabled'] ? "max-age={$settings['hsts_max_age']}" : 'Not configured'
            ],
            'frame_options' => [
                'label' => 'X-Frame-Options',
                'description' => 'Clickjacking protection',
                'status' => 'active',
                'value' => $settings['frame_options'] ?? 'DENY'
            ],
            'content_type_options' => [
                'label' => 'X-Content-Type-Options',
                'description' => 'MIME type protection',
                'status' => 'active',
                'value' => $settings['content_type_options'] ?? 'nosniff'
            ],
            'referrer_policy' => [
                'label' => 'Referrer-Policy',
                'description' => 'Referrer information control',
                'status' => 'active',
                'value' => $settings['referrer_policy'] ?? 'strict-origin-when-cross-origin'
            ],
            'permissions_policy' => [
                'label' => 'Permissions-Policy',
                'description' => 'Browser feature restrictions',
                'status' => 'active',
                'value' => 'Configured'
            ],
            'csp' => [
                'label' => 'Content-Security-Policy',
                'description' => 'Resource loading policies',
                'status' => $settings['content_security_policy'] ? 'enabled' : 'disabled',
                'value' => $settings['content_security_policy'] ? 'Active' : 'Not configured'
            ],
            'xss_protection' => [
                'label' => 'X-XSS-Protection',
                'description' => 'XSS protection',
                'status' => $settings['xss_protection'] ? 'enabled' : 'disabled',
                'value' => $settings['xss_protection'] ? '1; mode=block' : 'Not configured'
            ],
            'https_required' => [
                'label' => 'HTTPS Enforcement',
                'description' => 'Secure connection requirement',
                'status' => $settings['https_required'] ? 'enabled' : 'disabled',
                'value' => $settings['https_required'] ? 'Required' : 'Optional'
            ],
            'secure_cookies' => [
                'label' => 'Secure Cookies',
                'description' => 'Cookie security',
                'status' => $settings['secure_cookies'] ? 'enabled' : 'disabled',
                'value' => $settings['secure_cookies'] ? 'HTTPS only' : 'HTTP allowed'
            ],
            'same_site_policy' => [
                'label' => 'SameSite Policy',
                'description' => 'Cookie cross-site policy',
                'status' => 'active',
                'value' => $settings['same_site_policy'] ?? 'lax'
            ]
        ];

        return response()->json($securityStatus);
    }

    /**
     * Get security recommendations
     */
    public function getRecommendations()
    {
        $currentSettings = $this->getSecuritySettings();
        $recommendations = [];
        
        // Session timeout recommendations
        if ($currentSettings['session_timeout'] > 60) {
            $recommendations[] = [
                'type' => 'warning',
                'message' => 'Oturum zaman aşımı 60 dakikadan fazla. Güvenlik için 30-60 dakika arası önerilir.',
                'setting' => 'session_timeout'
            ];
        }
        
        // HTTPS recommendations
        if (!$currentSettings['https_required']) {
            $recommendations[] = [
                'type' => 'info',
                'message' => 'HTTPS zorunluluğu aktif değil. Üretim ortamında HTTPS kullanımı önerilir.',
                'setting' => 'https_required'
            ];
        }
        
        // HSTS recommendations
        if (!$currentSettings['hsts_enabled']) {
            $recommendations[] = [
                'type' => 'info',
                'message' => 'HSTS (HTTP Strict Transport Security) aktif değil. HTTPS kullanıyorsanız aktifleştirmeniz önerilir.',
                'setting' => 'hsts_enabled'
            ];
        }
        
        // SameSite recommendations
        if ($currentSettings['same_site_policy'] === 'none') {
            $recommendations[] = [
                'type' => 'warning',
                'message' => 'SameSite politikası "none" olarak ayarlanmış. Güvenlik için "lax" veya "strict" önerilir.',
                'setting' => 'same_site_policy'
            ];
        }
        
        return response()->json($recommendations);
    }
}
