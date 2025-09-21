<!DOCTYPE html>
<html lang="tr" class="{{ (auth()->user()->theme ?? 'light') === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tema Test</title>
    
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
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Navigation -->
        <nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('dashboard') }}" class="flex items-center">
                                <span class="text-xl font-bold text-blue-700 dark:text-blue-400">WH Kurumsal</span>
                            </a>
                        </div>
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-3">
                        <!-- Theme Toggle Button -->
                        <button 
                            id="theme-toggle" 
                            class="inline-flex items-center justify-center p-2 rounded-lg text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-all duration-200"
                            title="{{ (auth()->user()->theme ?? 'light') === 'dark' ? 'Açık temaya geç' : 'Koyu temaya geç' }}"
                        >
                            <!-- Sun Icon (Light Mode) -->
                            <svg id="theme-toggle-light-icon" class="w-5 h-5 {{ (auth()->user()->theme ?? 'light') === 'dark' ? 'hidden' : 'block' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0V4a4 4 0 118 0v6zM3 8a1 1 0 00-1 1v1a1 1 0 102 0V9a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <!-- Moon Icon (Dark Mode) -->
                            <svg id="theme-toggle-dark-icon" class="w-5 h-5 {{ (auth()->user()->theme ?? 'light') === 'dark' ? 'block' : 'hidden' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                            </svg>
                        </button>

                        <!-- User Info -->
                        <div class="text-gray-700 dark:text-gray-300">
                            {{ Auth::user()->name ?? 'Test User' }}
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Content -->
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                    Tema Toggle Test Sayfası
                </h1>
                
                <div class="space-y-4">
                    <div class="p-4 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                        <p class="text-blue-800 dark:text-blue-200">
                            Bu sayfa tema toggle'ı test etmek için oluşturuldu.
                        </p>
                    </div>
                    
                    <div class="p-4 bg-green-100 dark:bg-green-900/30 rounded-lg">
                        <p class="text-green-800 dark:text-green-200">
                            Sağ üst köşedeki güneş/ay ikonuna tıklayarak temayı değiştirebilirsiniz.
                        </p>
                    </div>
                    
                    <div class="p-4 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                        <p class="text-purple-800 dark:text-purple-200">
                            Mevcut tema: <strong>{{ auth()->user()->theme ?? 'light' }}</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
