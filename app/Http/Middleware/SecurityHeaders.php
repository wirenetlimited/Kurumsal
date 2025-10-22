<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Setting;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): \Symfony\Component\HttpFoundation\Response
    {
        $response = $next($request);

        // Development ortamında güvenlik başlıklarını devre dışı bırak
        if (app()->environment('local')) {
            return $response;
        }

        // Skip during installation
        if ($this->isInstalling($request)) {
            return $response;
        }

        // Check if settings table exists
        if (!\Schema::hasTable('settings')) {
            return $response;
        }

        // HSTS (HTTP Strict Transport Security)
        $hstsEnabled = Setting::get('hsts_enabled', true);
        $hstsMaxAge = Setting::get('hsts_max_age', 31536000); // 1 year default
        if ($hstsEnabled && $request->secure()) {
            $response->headers->set('Strict-Transport-Security', "max-age={$hstsMaxAge}; includeSubDomains; preload");
        }

        // X-Frame-Options
        $frameOptions = Setting::get('frame_options', 'DENY');
        $response->headers->set('X-Frame-Options', $frameOptions);

        // X-Content-Type-Options
        $contentTypeOptions = Setting::get('content_type_options', 'nosniff');
        $response->headers->set('X-Content-Type-Options', $contentTypeOptions);

        // Referrer-Policy
        $referrerPolicy = Setting::get('referrer_policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Referrer-Policy', $referrerPolicy);

        // Permissions-Policy (formerly Feature-Policy)
        $permissionsPolicy = Setting::get('permissions_policy', 'geolocation=(), microphone=(), camera=(), payment=(), usb=(), magnetometer=(), gyroscope=(), accelerometer=(), ambient-light-sensor=(), autoplay=(), encrypted-media=(), fullscreen=(), picture-in-picture=(), publickey-credentials-get=(), sync-xhr=(), clipboard-read=(), clipboard-write=(), display-capture=(), document-domain=(), execution-while-not-rendered=(), execution-while-out-of-viewport=(), focus-without-user-activation=(), cross-origin-isolated=(), identity-credentials-get=(), payment=(), publickey-credentials-create=(), screen-wake-lock=(), web-share=(), xr-spatial-tracking=()');
        $response->headers->set('Permissions-Policy', $permissionsPolicy);

        // X-XSS-Protection (conditional based on settings)
        $xssProtection = Setting::get('xss_protection', true);
        if ($xssProtection) {
            $response->headers->set('X-XSS-Protection', '1; mode=block');
        }

        // Content-Security-Policy (conditional based on settings)
        $cspEnabled = Setting::get('content_security_policy', true);
        if ($cspEnabled) {
            $csp = Setting::get('csp_policy', "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; img-src 'self' data: https:; font-src 'self' data: https://fonts.gstatic.com; connect-src 'self'; frame-ancestors 'none'; base-uri 'self'; form-action 'self';");
            $response->headers->set('Content-Security-Policy', $csp);
        }

        // Additional security headers
        $response->headers->set('X-Download-Options', 'noopen');
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');
        
        // Remove server information headers
        $response->headers->remove('Server');
        $response->headers->remove('X-Powered-By');

        return $response;
    }

    /**
     * Check if we are in installation mode
     */
    private function isInstalling(Request $request): bool
    {
        $path = $request->path();
        return str_starts_with($path, 'install') || 
               str_starts_with($path, 'public/install') ||
               str_contains($path, 'install');
    }
}
