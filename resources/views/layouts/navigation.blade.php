<nav x-data="{ open: false }" class="relative bg-white/90 dark:bg-gray-800/90 backdrop-blur-md border-b border-gray-200/50 dark:border-gray-700/50 shadow-lg z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Left Section: Logo + Navigation Links -->
            <div class="flex items-center space-x-4">
                <!-- Logo -->
                <div class="shrink-0 flex items-center -ml-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center group">
                        <div class="relative w-40 h-14 overflow-hidden rounded-lg bg-gray-50 dark:bg-gray-700">
                            @php
                                $logoPath = \App\Models\Setting::get('site_logo');
                                $siteName = \App\Models\Setting::get('site_name', 'WH Kurumsal');
                            @endphp
                            
                            @if($logoPath)
                                <!-- Debug: Logo path: {{ $logoPath }} -->
                                <img src="{{ asset('storage/' . $logoPath) }}" 
                                     alt="{{ $siteName }}" 
                                     class="w-full h-full object-contain group-hover:scale-105 transition-all duration-300"
                                     onerror="console.log('Logo yüklenemedi:', this.src); this.style.display='none'; this.nextElementSibling.style.display='block';"
                                     onload="console.log('Logo başarıyla yüklendi:', this.src);">
                                <!-- Fallback logo -->
                                <x-application-logo class="w-full h-full fill-current text-blue-700 dark:text-blue-400 group-hover:text-blue-600 dark:group-hover:text-blue-300 transition-all duration-300" style="display: none;" />
                            @else
                                <x-application-logo class="w-full h-full fill-current text-blue-700 dark:text-blue-400 group-hover:text-blue-600 dark:group-hover:text-blue-300 transition-all duration-300" />
                            @endif
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden lg:flex items-center space-x-0.5">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="group">
                        <div class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-300 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 dark:hover:from-blue-900/30 dark:hover:to-indigo-900/30">
                            <svg class="w-4 h-4 mr-2 text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                            </svg>
                            <span class="font-medium text-base text-gray-700 dark:text-gray-300 group-hover:text-blue-700 dark:group-hover:text-blue-300 transition-colors duration-300">{{ __('Dashboard') }}</span>
                        </div>
                    </x-nav-link>
                    
                    <x-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')" class="group">
                        <div class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-300 hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 dark:hover:from-green-900/30 dark:hover:to-emerald-900/30">
                            <svg class="w-4 h-4 mr-2 text-green-600 dark:text-green-400 group-hover:scale-110 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                            </svg>
                            <span class="font-medium text-base text-gray-700 dark:text-gray-300 group-hover:text-green-700 dark:group-hover:text-green-300 transition-colors duration-300">{{ __('Müşteriler') }}</span>
                        </div>
                    </x-nav-link>
                    
                    <x-nav-link :href="route('providers.index')" :active="request()->routeIs('providers.*')" class="group">
                        <div class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-300 hover:bg-gradient-to-r hover:from-orange-50 hover:to-amber-50 dark:hover:from-orange-900/30 dark:hover:to-amber-900/30">
                            <svg class="w-4 h-4 mr-2 text-orange-600 dark:text-orange-400 group-hover:scale-110 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                            </svg>
                            <span class="font-medium text-base text-gray-700 dark:text-gray-300 group-hover:text-orange-700 dark:hover:text-orange-300 transition-colors duration-300">{{ __('Sağlayıcılar') }}</span>
                        </div>
                    </x-nav-link>
                    
                    <x-nav-link :href="route('services.index')" :active="request()->routeIs('services.*')" class="group">
                        <div class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-300 hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 dark:hover:from-purple-900/30 dark:hover:to-pink-900/30">
                            <svg class="w-4 h-4 mr-2 text-purple-600 dark:text-purple-400 group-hover:scale-110 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <span class="font-medium text-base text-gray-700 dark:text-gray-300 group-hover:text-purple-700 dark:group-hover:text-purple-300 transition-colors duration-300">{{ __('Hizmetler') }}</span>
                        </div>
                    </x-nav-link>
                    
                    <x-nav-link :href="route('invoices.index')" :active="request()->routeIs('invoices.*')" class="group">
                        <div class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-300 hover:bg-gradient-to-r hover:from-indigo-50 hover:to-blue-50 dark:hover:from-indigo-900/30 dark:hover:to-blue-900/30">
                            <svg class="w-4 h-4 mr-2 text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                            </svg>
                            <span class="font-medium text-base text-gray-700 dark:text-gray-300 group-hover:text-indigo-700 dark:group-hover:text-indigo-300 transition-colors duration-300">{{ __('Muhasebe') }}</span>
                        </div>
                    </x-nav-link>
                    
                    <x-nav-link :href="route('quotes.index')" :active="request()->routeIs('quotes.*')" class="group">
                        <div class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-300 hover:bg-gradient-to-r hover:from-teal-50 hover:to-cyan-50 dark:hover:from-teal-900/30 dark:hover:to-cyan-900/30">
                            <svg class="w-4 h-4 mr-2 text-teal-600 dark:text-teal-400 group-hover:scale-110 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="font-medium text-base text-gray-700 dark:text-gray-300 group-hover:text-teal-700 dark:group-hover:text-teal-300 transition-colors duration-300">{{ __('Teklifler') }}</span>
                        </div>
                    </x-nav-link>
                    
                    <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')" class="group">
                        <div class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-300 hover:bg-gradient-to-r hover:from-rose-50 hover:to-red-50 dark:hover:from-rose-900/30 dark:hover:to-red-900/30">
                            <svg class="w-4 h-4 mr-2 text-rose-600 dark:text-rose-400 group-hover:scale-110 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="font-medium text-base text-gray-700 dark:text-gray-300 group-hover:text-rose-700 dark:group-hover:text-rose-300 transition-colors duration-300">{{ __('Raporlar') }}</span>
                        </div>
                    </x-nav-link>
                </div>
            </div>

            <!-- Right Section: Settings & User Menu -->
            <div class="flex items-center space-x-3">
                <!-- Theme Toggle Button -->
                <button 
                    id="theme-toggle" 
                    class="inline-flex items-center justify-center p-2.5 rounded-xl text-gray-500 dark:text-gray-400 bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 hover:from-gray-200 hover:to-gray-300 dark:hover:from-gray-600 dark:hover:to-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5"
                    title="{{ (auth()->user()->theme ?? 'light') === 'dark' ? 'Açık temaya geç' : 'Koyu temaya geç' }}"
                >
                    <!-- Sun Icon (Light Mode) -->
                    <svg id="theme-toggle-light-icon" class="w-5 h-5 {{ (auth()->user()->theme ?? 'light') === 'dark' ? 'hidden' : 'block' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0V4a4 4 0 118 0v6zM3 8a1 1 0 00-1 1v1a1 1 0 102 0V9a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <!-- Moon Icon (Dark Mode) -->
                    <svg id="theme-toggle-dark-icon" class="w-5 h-5 {{ (auth()->user()->theme ?? 'dark') === 'dark' ? 'block' : 'hidden' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                </button>

                <x-dropdown align="left" width="72" class="z-50">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center justify-center p-2.5 border border-transparent text-sm leading-4 font-medium rounded-xl text-gray-700 dark:text-gray-300 bg-gradient-to-r from-blue-50 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 hover:from-blue-100 hover:to-indigo-200 dark:hover:from-blue-900/50 dark:hover:to-indigo-900/50 focus:outline-none transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
                            <div class="flex items-center">
                                <div class="w-7 h-7 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            </div>

                            <div class="ms-2">
                                <svg class="fill-current h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="group">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-3 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="text-base whitespace-nowrap">{{ __('Profile') }}</span>
                            </div>
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('email-templates.index')" class="group">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-3 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-base whitespace-nowrap">{{ __('E-posta Şablonları') }}</span>
                            </div>
                        </x-dropdown-link>
                        
                        @can('admin')
                        <x-dropdown-link :href="route('admin.email-settings.index')" class="group">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-3 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-base whitespace-nowrap">{{ __('E-posta Ayarları') }}</span>
                            </div>
                        </x-dropdown-link>
                        
                        <x-dropdown-link :href="route('admin.site-settings.index')" class="group">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-3 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                </svg>
                                <span class="text-base whitespace-nowrap">{{ __('Site Ayarları') }}</span>
                            </div>
                        </x-dropdown-link>
                        @endcan
                        
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')" class="group"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-3 text-gray-400 group-hover:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    <span class="text-base whitespace-nowrap">{{ __('Log Out') }}</span>
                                </div>
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="flex items-center lg:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2.5 rounded-xl text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gradient-to-r hover:from-gray-100 hover:to-gray-200 dark:hover:from-gray-700 dark:hover:to-gray-600 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700 focus:text-gray-500 dark:focus:text-gray-400 transition-all duration-300 ease-in-out">
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="group">
                <div class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 dark:hover:from-blue-900/30 dark:hover:to-indigo-900/30">
                    <svg class="w-4 h-4 mr-3 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                    </svg>
                    <span class="font-medium text-base">{{ __('Dashboard') }}</span>
                </div>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')" class="group">
                <div class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 dark:hover:from-green-900/30 dark:hover:to-emerald-900/30">
                    <svg class="w-4 h-4 mr-3 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                    </svg>
                    <span class="font-medium text-base">{{ __('Müşteriler') }}</span>
                </div>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('providers.index')" :active="request()->routeIs('providers.*')" class="group">
                <div class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 hover:bg-gradient-to-r hover:from-orange-50 hover:to-amber-50 dark:hover:from-orange-900/30 dark:hover:to-amber-900/30">
                    <svg class="w-4 h-4 mr-3 text-orange-600 dark:text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                    </svg>
                    <span class="font-medium text-base">{{ __('Sağlayıcılar') }}</span>
                </div>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('services.index')" :active="request()->routeIs('services.*')" class="group">
                <div class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 dark:hover:from-purple-900/30 dark:hover:to-pink-900/30">
                    <svg class="w-4 h-4 mr-3 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span class="font-medium text-base">{{ __('Hizmetler') }}</span>
                </div>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('invoices.index')" :active="request()->routeIs('invoices.*')" class="group">
                <div class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 hover:bg-gradient-to-r hover:from-indigo-50 hover:to-blue-50 dark:hover:from-indigo-900/30 dark:hover:to-blue-900/30">
                    <svg class="w-4 h-4 mr-3 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                    </svg>
                    <span class="font-medium text-base">{{ __('Muhasebe') }}</span>
                </div>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('quotes.index')" :active="request()->routeIs('quotes.*')" class="group">
                <div class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 hover:bg-gradient-to-r hover:from-teal-50 hover:to-cyan-50 dark:hover:from-teal-900/30 dark:hover:to-cyan-900/30">
                    <svg class="w-4 h-4 mr-3 text-teal-600 dark:text-teal-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium text-base">{{ __('Teklifler') }}</span>
                </div>
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')" class="group">
                <div class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 hover:bg-gradient-to-r hover:from-rose-50 hover:to-red-50 dark:hover:from-rose-900/30 dark:hover:to-red-900/30">
                    <svg class="w-4 h-4 mr-3 text-rose-600 dark:text-rose-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="font-medium text-base">{{ __('Raporlar') }}</span>
                </div>
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="flex items-center justify-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                </div>
            </div>

            <div class="mt-4 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="group">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-3 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="text-base">{{ __('Profile') }}</span>
                    </div>
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('email-templates.index')" class="group">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-3 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-base">{{ __('E-posta Şablonları') }}</span>
                    </div>
                </x-responsive-nav-link>
                
                @can('admin')
                <x-responsive-nav-link :href="route('admin.email-settings.index')" class="group">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-3 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="text-base">{{ __('E-posta Ayarları') }}</span>
                    </div>
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('admin.site-settings.index')" class="group">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-3 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                        <span class="text-base">{{ __('Site Ayarları') }}</span>
                    </div>
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('admin.reconciliation.index')" class="group">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-3 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-base">{{ __('Muhasebe Mutabakatı') }}</span>
                    </div>
                </x-responsive-nav-link>
                @endcan

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')" class="group"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-3 text-gray-400 group-hover:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span class="text-base">{{ __('Log Out') }}</span>
                        </div>
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
