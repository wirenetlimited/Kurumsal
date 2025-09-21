<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>WH Kurumsal | Müşteri Takip Sistemi</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

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
    <body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-blue-900/20 dark:to-indigo-900/30">
        <div class="h-screen flex flex-col lg:flex-row relative overflow-hidden">
            <!-- Animasyonlu Arka Plan Elementleri -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <!-- Floating Circles - Responsive -->
                <div class="absolute top-10 left-10 w-32 h-32 sm:w-48 sm:h-48 lg:w-72 lg:h-72 bg-blue-400/10 rounded-full blur-2xl lg:blur-3xl animate-pulse"></div>
                <div class="absolute top-20 right-20 w-40 h-40 sm:w-56 sm:h-56 lg:w-96 lg:h-96 bg-indigo-400/10 rounded-full blur-2xl lg:blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
                <div class="absolute bottom-10 left-1/4 w-24 h-24 sm:w-32 sm:h-32 lg:w-64 lg:h-64 bg-purple-400/10 rounded-full blur-2xl lg:blur-3xl animate-pulse" style="animation-delay: 4s;"></div>
                
                <!-- Grid Pattern -->
                <div class="absolute inset-0 bg-[linear-gradient(rgba(59,130,246,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(59,130,246,0.05)_1px,transparent_1px)] bg-[size:25px_25px] sm:bg-[size:35px_35px] lg:bg-[size:50px_50px]"></div>
            </div>

            <!-- Sol Bölüm: Form Alanı -->
            <div class="flex-1 flex flex-col justify-center px-4 sm:px-6 md:px-8 lg:px-12 xl:px-16 2xl:px-20 relative z-10 py-4 lg:py-0 overflow-y-auto">
                <!-- Logo -->
                <div class="mb-4 sm:mb-6 lg:mb-8 transform hover:scale-105 transition-transform duration-300">
                    <div class="inline-flex items-center space-x-2 sm:space-x-3 lg:space-x-4 group">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 rounded-xl lg:rounded-2xl flex items-center justify-center shadow-xl lg:shadow-2xl group-hover:shadow-blue-500/25 transition-all duration-500 transform group-hover:rotate-3">
                            <span class="text-white text-lg sm:text-xl lg:text-2xl font-black">WH</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xl sm:text-2xl lg:text-3xl font-black text-gray-900 dark:text-white bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Kurumsal</span>
                            <span class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 font-medium">Business Solutions</span>
                        </div>
                    </div>
                </div>

                <!-- Form Container -->
                <div class="max-w-sm sm:max-w-md lg:max-w-lg">
                    {{ $slot }}
                </div>

                <!-- Alt Linkler -->
                <div class="mt-4 sm:mt-6 lg:mt-8 text-center">
                    <a href="/" class="inline-flex items-center space-x-2 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-all duration-300 group">
                        <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span class="font-medium text-sm sm:text-base">Ana Sayfaya Dön</span>
                    </a>
                </div>

                <!-- Footer -->
                <div class="mt-4 sm:mt-6 lg:mt-8 text-center">
                    <div class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 space-y-2">
                        <div class="flex flex-col sm:flex-row items-center justify-center space-y-1 sm:space-y-0 sm:space-x-2">
                            <span>© 2009-2025</span>
                            <a href="https://www.whkurumsal.com" target="_blank" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors duration-200 font-medium">WH Kurumsal</a>
                            <span>bir</span>
                            <a href="https://www.wh.web.tr" target="_blank" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors duration-200 font-medium">WH</a>
                            <span>kuruluşudur.</span>
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-500">
                            Tüm hakları saklıdır.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sağ Bölüm: Görsel Alan -->
            <div class="hidden lg:block flex-1 relative bg-white overflow-hidden">
                <!-- Ana Logo Görseli - Responsive -->
                <div class="absolute inset-0 flex items-center justify-center p-8 xl:p-12 2xl:p-16">
                    <img src="http://localhost:8000/storage/settings/logo.jpeg" 
                         alt="WH Kurumsal Logo" 
                         class="w-full h-full object-contain max-w-lg xl:max-w-2xl 2xl:max-w-3xl">
                </div>
                

            </div>
        </div>
    </body>
</html>
