<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-pink-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <div class="max-w-7xl mx-auto p-6 space-y-8">
            <!-- Header -->
            <div class="relative overflow-hidden bg-gradient-to-r from-purple-600 via-pink-600 to-rose-600 rounded-3xl p-8 shadow-2xl">
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="relative flex items-center justify-between">
                    <div class="space-y-2">
                        <h1 class="text-4xl font-bold text-white drop-shadow-lg">Hizmet Detayı</h1>
                        <p class="text-purple-100 text-lg">Hizmet bilgilerini görüntüleyin</p>
                    </div>
                    <div class="text-right">
                        <div class="text-4xl font-bold">👁️</div>
                        <div class="text-purple-100 text-lg">Hizmet Görüntüleme</div>
                    </div>
                </div>
                
                <!-- Dekoratif Elementler -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
            </div>

            <!-- Status Message -->
            @if (session('status'))
                <div class="relative bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/30 dark:to-emerald-900/30 border border-green-200 dark:border-green-800 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center">
                        <div class="w-6 h-6 bg-green-100 dark:bg-green-800 rounded-full flex items-center justify-center mr-4">
                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="text-green-800 dark:text-green-200 font-semibold text-lg">{{ session('status') }}</span>
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex justify-end gap-6">
                <a href="{{ route('services.index') }}" 
                   class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-2xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 font-semibold shadow-sm hover:shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Listeye Dön
                </a>
                <a href="{{ route('services.edit', $service) }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Düzenle
                </a>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Service Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
                        <div class="relative">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Temel Bilgiler</h2>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-6">
                                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                        <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">Müşteri</span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">
                                            @if($service->customer)
                                                {{ $service->customer->name }}@if($service->customer->surname) {{ ' ' . $service->customer->surname }}@endif
                                            @else
                                                -
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                        <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">Sağlayıcı</span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $service->provider->name ?? '-' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                        <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">Hizmet Türü</span>
                                        @php
                                            // Controller'dan gelen serviceTypes kullan
                                            $currentType = collect($serviceTypes)->firstWhere('id', $service->service_type);
                                            if (!$currentType) {
                                                $currentType = ['id' => 'other', 'name' => 'Diğer', 'icon' => '📦', 'color' => '#6B7280'];
                                            }
                                        @endphp
                                        
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border border-gray-200 dark:border-gray-600" 
                                              style="background-color: {{ $currentType['color'] }}20; color: {{ $currentType['color'] }};">
                                            {{ $currentType['icon'] }} {{ $currentType['name'] }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                        <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">Durum</span>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border
                                            @if($service->status->value === 'active') bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 text-green-800 dark:text-green-300 border-green-200 dark:border-green-700
                                            @elseif($service->status->value === 'suspended') bg-gradient-to-r from-yellow-100 to-orange-100 dark:from-yellow-900/30 dark:to-orange-900/30 text-yellow-800 dark:text-yellow-300 border-yellow-200 dark:border-yellow-700
                                            @elseif($service->status->value === 'cancelled') bg-gradient-to-r from-red-100 to-pink-100 dark:from-red-900/30 dark:to-pink-900/30 text-red-800 dark:text-red-300 border-red-200 dark:border-red-700
                                            @else bg-gradient-to-r from-gray-100 to-slate-100 dark:from-gray-700 dark:to-slate-700 text-gray-800 dark:text-gray-300 border-gray-200 dark:border-gray-600 @endif">
                                            {{ ucfirst($service->status->value ?? $service->status) }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="space-y-6">
                                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                        <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">Başlangıç Tarihi</span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $service->start_date?->format('d.m.Y') ?? '-' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                        <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">Bitiş Tarihi</span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $service->end_date?->format('d.m.Y') ?? '-' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                        <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">Dönem</span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ ucfirst($service->cycle->value ?? $service->cycle) }}</span>
                                    </div>
                                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                        <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">Ödeme Şekli</span>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border 
                                            {{ $service->payment_type === 'upfront' ? 'bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 text-green-800 dark:text-green-300 border-green-200 dark:border-green-700' : 'bg-gradient-to-r from-blue-100 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 text-blue-800 dark:text-blue-300 border-blue-200 dark:border-blue-700' }}">
                                            {{ $service->payment_type === 'upfront' ? '💰 Peşin Ödeme' : '📅 Taksit Ödeme' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                        <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">Satış Fiyatı</span>
                                        <span class="text-sm font-bold text-green-600 dark:text-green-400">₺{{ number_format($service->sell_price, 2, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Domain Information -->
                    @if($service->service_type === 'domain' && $service->domain)
                        <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8">
                            <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
                            <div class="relative">
                                <div class="flex items-center gap-4 mb-8">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                                        <span class="text-2xl">🌐</span>
                                    </div>
                                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Domain Bilgileri</h3>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="space-y-6">
                                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                            <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">Domain Adı</span>
                                            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $service->domain->domain_name }}</span>
                                        </div>
                                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                            <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">Registrar Ref</span>
                                            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $service->domain->registrar_ref ?? '-' }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-6">
                                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                            <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">Auth Code</span>
                                            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $service->domain->auth_code ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Hosting Information -->
                    @if($service->service_type === 'hosting' && $service->hosting)
                        <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8">
                            <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
                            <div class="relative">
                                <div class="flex items-center gap-4 mb-8">
                                    <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                                        <span class="text-2xl">🖥️</span>
                                    </div>
                                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Hosting Bilgileri</h3>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="space-y-6">
                                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                            <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">Paket Adı</span>
                                            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $service->hosting->plan_name ?? '-' }}</span>
                                        </div>
                                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                            <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">Sunucu Adı</span>
                                            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $service->hosting->server_name ?? '-' }}</span>
                                        </div>
                                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                            <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">IP Adresi</span>
                                            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $service->hosting->ip_address ?? '-' }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-6">
                                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                            <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">cPanel Kullanıcı</span>
                                            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $service->hosting->cpanel_username ?? '-' }}</span>
                                        </div>
                                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                            <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">Panel Ref</span>
                                            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $service->hosting->panel_ref ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Notes -->
                    @if($service->notes)
                        <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8">
                            <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
                            <div class="relative">
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-400 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Notlar</h3>
                                </div>
                                <p class="text-gray-700 dark:text-gray-300 text-lg">{{ $service->notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Service Summary -->
                    <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
                        <div class="relative">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Hizmet Özeti</h3>
                            </div>
                            
                            <div class="space-y-6">
                                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                    <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Maliyet</span>
                                    <span class="text-sm font-bold text-red-600 dark:text-red-400">₺{{ number_format($service->cost_price, 2, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                    <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Kar Marjı</span>
                                    <span class="text-sm font-bold text-green-600 dark:text-green-400">
                                        @if($service->cost_price > 0)
                                            %{{ number_format((($service->sell_price - $service->cost_price) / $service->cost_price) * 100, 1) }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                    <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Toplam Kar</span>
                                    <span class="text-sm font-bold text-green-600 dark:text-green-400">
                                        ₺{{ number_format($service->sell_price - $service->cost_price, 2, ',', '.') }}
                                    </span>
                                </div>
                                @if($service->end_date)
                                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Kalan Gün</span>
                                        <span class="text-sm font-bold 
                                            @if(abs((int)$service->days_remaining) <= 30) text-red-600 dark:text-red-400
                                            @elseif(abs((int)$service->days_remaining) <= 90) text-yellow-600 dark:text-yellow-400
                                            @else text-green-600 dark:text-green-400 @endif">
                                            {{ abs((int)($service->days_remaining ?? 0)) }} gün
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
                        <div class="relative">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Hızlı İşlemler</h3>
                            </div>
                            
                            <div class="space-y-6">
                                <!-- Fatura Oluştur -->
                                @php($isDemo = auth()->check() && strcasecmp(auth()->user()->email, config('app.demo_email')) === 0)
                                <form method="POST" action="{{ route('services.create-invoice', $service) }}" class="inline w-full" onsubmit="if({{ $isDemo ? 'true' : 'false' }}){ alert('Demo modunda fatura oluşturma devre dışıdır.'); return false; }">
                                    @csrf
                                    <button type="submit" @if($isDemo) disabled @endif class="w-full flex items-center justify-center px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 text-sm font-semibold shadow-lg hover:shadow-xl {{ $isDemo ? 'opacity-50 cursor-not-allowed' : '' }}" title="{{ $isDemo ? 'Demo modunda kapalı' : '' }}">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Fatura Oluştur
                                    </button>
                                </form>

                                <!-- E-posta Gönder -->
                                <form method="POST" action="{{ route('services.send-reminder', $service) }}" class="inline w-full" onsubmit="if({{ $isDemo ? 'true' : 'false' }}){ alert('Demo modunda e-posta gönderme devre dışıdır.'); return false; }">
                                    @csrf
                                    <button type="submit" @if($isDemo) disabled @endif class="w-full flex items-center justify-center px-6 py-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-2xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 text-sm font-semibold shadow-lg hover:shadow-xl {{ $isDemo ? 'opacity-50 cursor-not-allowed' : '' }}" title="{{ $isDemo ? 'Demo modunda kapalı' : '' }}">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        E-posta Gönder
                                    </button>
                                </form>

                                <!-- Hatırlatma Ekle -->
                                <button onclick="{{ $isDemo ? 'alert(\'Demo modunda hatırlatma ekleme devre dışıdır.\')' : 'openReminderModal()' }}" @if($isDemo) disabled @endif class="w-full flex items-center justify-center px-6 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-2xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 text-sm font-semibold shadow-lg hover:shadow-xl {{ $isDemo ? 'opacity-50 cursor-not-allowed' : '' }}" title="{{ $isDemo ? 'Demo modunda kapalı' : '' }}">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Hatırlatma Ekle
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Hatırlatma Modal -->
                    <div id="reminderModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
                        <div class="flex items-center justify-center min-h-screen p-4">
                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-8">
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Hatırlatma Ekle</h3>
                                    <button onclick="closeReminderModal()" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                
                                <form method="POST" action="{{ route('services.add-reminder', $service) }}" class="space-y-6" onsubmit="if({{ $isDemo ? 'true' : 'false' }}){ alert('Demo modunda hatırlatma ekleme devre dışıdır.'); return false; }">
                                    @csrf
                                    
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Hatırlatma Tarihi</label>
                                        <input type="date" name="reminder_date" required 
                                               min="{{ now()->addDay()->format('Y-m-d') }}"
                                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm hover:shadow-md transition-all duration-200">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Hatırlatma Türü</label>
                                        <select name="reminder_type" required 
                                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm hover:shadow-md transition-all duration-200">
                                            <option value="email">📧 E-posta</option>
                                            <option value="sms">📱 SMS</option>
                                            <option value="notification">🔔 Bildirim</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Notlar</label>
                                        <textarea name="notes" rows="3" 
                                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm hover:shadow-md transition-all duration-200"
                                                  placeholder="Hatırlatma notları..."></textarea>
                                    </div>
                                    
                                    <div class="flex justify-end gap-4 pt-6">
                                        <button type="button" onclick="closeReminderModal()" 
                                                class="px-6 py-3 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-2xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200 font-semibold">
                                            İptal
                                        </button>
                                        <button type="submit" @if($isDemo) disabled @endif
                                                class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-2xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl {{ $isDemo ? 'opacity-50 cursor-not-allowed' : '' }}" title="{{ $isDemo ? 'Demo modunda kapalı' : '' }}">
                                            Hatırlatma Ekle
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <script>
                        function openReminderModal() {
                            document.getElementById('reminderModal').classList.remove('hidden');
                        }
                        
                        function closeReminderModal() {
                            document.getElementById('reminderModal').classList.add('hidden');
                        }
                        
                        // Modal dışına tıklandığında kapat
                        document.getElementById('reminderModal').addEventListener('click', function(e) {
                            if (e.target === this) {
                                closeReminderModal();
                            }
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


