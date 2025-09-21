<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DemoReadOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $demoEmail = config('app.demo_email', 'demo@example.com');

        // Sadece demo kullanıcı için yazma işlemlerini engelle
        if ($user && strcasecmp((string) $user->email, (string) $demoEmail) === 0) {
            $method = strtoupper($request->getMethod());
            
            // Logout işlemini her zaman izin ver
            if ($request->routeIs('logout')) {
                return $next($request);
            }
            
            if (!in_array($method, ['GET', 'HEAD', 'OPTIONS'], true)) {
                // JSON bekleyen istekler için JSON döndür
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Demo modunda yazma işlemleri devre dışıdır.'
                    ], 403);
                }

                // Aksi halde önceki sayfaya hata ile dön
                return redirect()->back()->withErrors([
                    'demo' => 'Demo modunda yazma işlemleri devre dışıdır.'
                ]);
            }
        }

        return $next($request);
    }
}


