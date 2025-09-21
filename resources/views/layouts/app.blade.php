<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ (auth()->user()->theme ?? 'light') === 'dark' ? 'dark' : '' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ auth()->user()->dashboard_title ?? 'WH | Kurumsal Hizmetler' }}</title>

        <!-- Favicon -->
        @php
            $faviconPath = \App\Models\Setting::get('site_favicon');
            $faviconDir = $faviconPath ? dirname($faviconPath) : null;
        @endphp
        @if($faviconPath && $faviconDir)
            <!-- Multiple favicon sizes for better compatibility -->
            <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('storage/' . $faviconDir . '/favicon-16x16.png') }}">
            <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('storage/' . $faviconDir . '/favicon-32x32.png') }}">
            <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('storage/' . $faviconDir . '/favicon-64x64.png') }}">
            <link rel="apple-touch-icon" sizes="128x128" href="{{ asset('storage/' . $faviconDir . '/favicon-128x128.png') }}">
            <link rel="shortcut icon" href="{{ asset('storage/' . $faviconDir . '/favicon.ico') }}">
            <!-- Fallback -->
            <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $faviconPath) }}">
        @else
            <!-- Default favicon -->
            <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @if(app()->environment('local'))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            @php
                $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
            @endphp
            <link rel="stylesheet" href="{{ asset('build/' . $manifest['resources/css/app.css']['file']) }}">
            <script type="module" src="{{ asset('build/' . $manifest['resources/js/app.js']['file']) }}"></script>
        @endif
    </head>
    <body class="font-sans antialiased" data-accent="{{ auth()->user()->theme_color ?? 'blue' }}">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900" data-version="{{ config('version.version') }}">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot ?? '' }}
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white dark:bg-gray-800 shadow-inner mt-auto">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    <div class="text-center text-sm text-gray-600 dark:text-gray-400">
                        © 2009-2025 <a href="https://www.whkurumsal.com" target="_blank" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors duration-200">WH Kurumsal</a> bir <a href="https://www.wh.web.tr" target="_blank" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors duration-200">WH</a> kuruluşudur.
                        <div class="mt-2 text-xs text-gray-500 dark:text-gray-500">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                v{{ config('version.version') }}
                            </span>
                            <span class="ml-2 text-gray-400 dark:text-gray-500">|</span>
                            <a href="#" onclick="showChangelog()" class="ml-2 text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200">
                                Sürüm Notları
                            </a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
