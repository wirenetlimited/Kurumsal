<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;
use Symfony\Component\HttpFoundation\Response;

class EnforceHttps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Local environment'da HTTPS zorunlu değil
        if (app()->environment('local')) {
            return $next($request);
        }

        // Skip during installation
        if ($this->isInstalling($request)) {
            return $next($request);
        }

        // Check if settings table exists
        if (!\Schema::hasTable('settings')) {
            return $next($request);
        }
        
        // HTTPS zorunluluğu ayarlardan kontrol et
        $httpsRequired = Setting::get('https_required', false);
        
        // HTTPS zorunluysa ve mevcut bağlantı HTTP ise yönlendir
        if ($httpsRequired && !$request->secure()) {
            $url = $request->getRequestUri();
            $secureUrl = 'https://' . $request->getHost() . $url;
            
            return redirect($secureUrl, 301);
        }
        
        // HSTS header ekle (eğer aktifse)
        if ($httpsRequired && $request->secure()) {
            $hstsEnabled = Setting::get('hsts_enabled', false);
            $hstsMaxAge = Setting::get('hsts_max_age', 31536000);
            
            if ($hstsEnabled) {
                $response = $next($request);
                $response->headers->set('Strict-Transport-Security', "max-age={$hstsMaxAge}; includeSubDomains");
                return $response;
            }
        }
        
        return $next($request);
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
