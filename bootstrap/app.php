<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// PHP header'ını gizle (güvenlik için)
if (function_exists('header')) {
    header_remove('X-Powered-By');
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Global security middleware
        $middleware->web([
            \App\Http\Middleware\SecurityHeaders::class,
            // Demo kullanıcısı için yazma işlemlerini globalde engelle
            \App\Http\Middleware\DemoReadOnly::class,
        ]);
        
        // Alias middleware
        $middleware->alias([
            'is_admin' => \App\Http\Middleware\IsAdmin::class,
            'demo_readonly' => \App\Http\Middleware\DemoReadOnly::class,
        ]);
        
        // HTTPS enforcement middleware (conditional)
        // Note: We'll handle this in AppServiceProvider instead
        // as app() helper is not available here during bootstrap
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
