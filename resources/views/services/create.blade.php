<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-purple-50 dark:from-gray-900 dark:to-purple-900/20">
        <div class="max-w-7xl mx-auto p-6 space-y-8">
            <!-- Header -->
            <div class="relative overflow-hidden bg-gradient-to-r from-purple-600 via-pink-600 to-indigo-600 rounded-2xl p-8 text-white shadow-2xl">
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between">
                        <div class="space-y-2">
                            <h1 class="text-3xl font-bold">Yeni Hizmet Ekle</h1>
                            <p class="text-purple-100 text-lg">Müşteri için yeni hizmet kaydı oluşturun</p>
                        </div>
                        <div class="text-right">
                            <div class="text-5xl mb-2">🛠️</div>
                            <div class="text-purple-100 font-medium">Hizmet Sistemi</div>
                        </div>
                    </div>
                </div>
                
                <!-- Decorative Elements -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                <form method="POST" action="{{ route('services.store') }}" class="p-8 space-y-8">
                    @csrf
                    
                    <!-- Müşteri ve Sağlayıcı -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Müşteri ve Sağlayıcı</h2>
                                <p class="text-gray-600 dark:text-gray-400">Hizmet alıcısı ve sağlayıcı bilgileri</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Müşteri *</label>
                                <select name="customer_id" 
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                        required>
                                    <option value="">Müşteri seçin</option>
                                    @foreach($customers as $c)
                                        <option value="{{ $c->id }}" {{ old('customer_id')==$c->id ? 'selected' : '' }}>
                                            {{ $c->customer_type === 'corporate' ? $c->name : $c->name . ' ' . ($c->surname ?? '') }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sağlayıcı</label>
                                <select name="provider_id" 
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    <option value="">Sağlayıcı seçin</option>
                                    @foreach($providers as $p)
                                        @php
                                            $providerTypes = is_array($p->type) ? $p->type : [$p->type];
                                            $typeLabels = [];
                                            foreach($providerTypes as $type) {
                                                switch($type) {
                                                    case 'domain': $typeLabels[] = '🌐 Domain'; break;
                                                    case 'hosting': $typeLabels[] = '🖥️ Hosting'; break;
                                                    case 'ssl': $typeLabels[] = '🔒 SSL'; break;
                                                    case 'email': $typeLabels[] = '📧 E-mail'; break;
                                                    case 'other': $typeLabels[] = '📦 Diğer'; break;
                                                    default: $typeLabels[] = ucfirst($type);
                                                }
                                            }
                                            $typeDisplay = implode(', ', $typeLabels);
                                        @endphp
                                        <option value="{{ $p->id }}" {{ old('provider_id')==$p->id ? 'selected' : '' }}>
                                            {{ $p->name }} ({{ $typeDisplay }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('provider_id')
                                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Hizmet Detayları -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Hizmet Detayları</h2>
                                <p class="text-gray-600 dark:text-gray-400">Hizmet türü ve özellikleri</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hizmet Türü *</label>
                                <select name="service_type" id="service_type" 
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                        required>
                                    <option value="">Tür seçin</option>
                                    @php
                                        // Site ayarlarından hizmet türlerini al
                                        $serviceTypesSetting = \App\Models\Setting::where('key', 'service_types')->first();
                                        $serviceTypes = $serviceTypesSetting ? json_decode($serviceTypesSetting->value, true) : [];
                                        
                                        // Varsayılan türler (eğer ayar yoksa)
                                        if (empty($serviceTypes)) {
                                            $serviceTypes = [
                                                ['id' => 'domain', 'name' => 'Domain', 'icon' => '🌐'],
                                                ['id' => 'hosting', 'name' => 'Hosting', 'icon' => '🖥️'],
                                                ['id' => 'ssl', 'name' => 'SSL', 'icon' => '🔒'],
                                                ['id' => 'email', 'name' => 'E-mail', 'icon' => '📧'],
                                                ['id' => 'other', 'name' => 'Diğer', 'icon' => '📦'],
                                            ];
                                        }
                                    @endphp
                                    
                                    @foreach($serviceTypes as $serviceType)
                                        <option value="{{ $serviceType['id'] }}" {{ old('service_type')===$serviceType['id'] ? 'selected' : '' }}>
                                            {{ $serviceType['icon'] }} {{ $serviceType['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Durum</label>
                                <select name="status" 
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    @foreach(\App\Enums\ServiceStatus::cases() as $status)
                                        <option value="{{ $status->value }}" {{ old('status', 'active') === $status->value ? 'selected' : '' }}>
                                            @switch($status)
                                                @case(\App\Enums\ServiceStatus::ACTIVE)
                                                    ✅ Aktif
                                                    @break
                                                @case(\App\Enums\ServiceStatus::SUSPENDED)
                                                    ⏸️ Askıya Alınmış
                                                    @break
                                                @case(\App\Enums\ServiceStatus::CANCELLED)
                                                    ❌ İptal Edilmiş
                                                    @break
                                                @case(\App\Enums\ServiceStatus::EXPIRED)
                                                    ⏰ Süresi Dolmuş
                                                    @break
                                            @endswitch
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dönem</label>
                                <select name="cycle" 
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    <option value="monthly" {{ old('cycle','yearly')==='monthly' ? 'selected' : '' }}>
                                        📅 Aylık
                                    </option>
                                    <option value="quarterly" {{ old('cycle','yearly')==='quarterly' ? 'selected' : '' }}>
                                        📅 3 Aylık
                                    </option>
                                    <option value="semiannually" {{ old('cycle','yearly')==='semiannually' ? 'selected' : '' }}>
                                        📅 6 Aylık
                                    </option>
                                    <option value="yearly" {{ old('cycle','yearly')==='yearly' ? 'selected' : '' }}>
                                        📅 Yıllık
                                    </option>
                                    <option value="biennially" {{ old('cycle','yearly')==='biennially' ? 'selected' : '' }}>
                                        📅 2 Yıllık
                                    </option>
                                    <option value="triennially" {{ old('cycle','yearly')==='triennially' ? 'selected' : '' }}>
                                        📅 3 Yıllık
                                    </option>
                                </select>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ödeme Şekli</label>
                                <select name="payment_type" 
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    <option value="upfront" {{ old('payment_type','upfront')==='upfront' ? 'selected' : '' }}>
                                        💰 Peşin Ödeme
                                    </option>
                                    <option value="installment" {{ old('payment_type','upfront')==='installment' ? 'selected' : '' }}>
                                        📅 Taksit Ödeme
                                    </option>
                                </select>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                    <strong>Peşin:</strong> Gelir başlangıç ayında, <strong>Taksit:</strong> Gelir her ay eşit bölünür
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Tarih ve Fiyat Bilgileri -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Tarih ve Fiyat Bilgileri</h2>
                                <p class="text-gray-600 dark:text-gray-400">Başlangıç, bitiş tarihleri ve maliyet</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Başlangıç Tarihi</label>
                                <input type="date" name="start_date" value="{{ old('start_date') }}" 
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bitiş Tarihi</label>
                                <input type="date" name="end_date" value="{{ old('end_date') }}" 
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Maliyet</label>
                                    <input name="cost_price" value="{{ old('cost_price') }}" 
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" 
                                           placeholder="0.00">
                                </div>
                                
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Satış Fiyatı</label>
                                    <input name="sell_price" value="{{ old('sell_price') }}" 
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" 
                                           placeholder="0.00">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Domain Bilgileri -->
                    <div id="domain_fields" class="space-y-4 {{ old('service_type','domain')==='domain' ? '' : 'hidden' }}">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/30 dark:to-indigo-900/30 rounded-2xl p-6 border border-blue-200 dark:border-blue-700 shadow-lg">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-xl flex items-center justify-center">
                                    <span class="text-3xl">🌐</span>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Domain Bilgileri</h3>
                                    <p class="text-gray-600 dark:text-gray-400">Alan adı ve transfer bilgileri</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Domain Adı</label>
                                    <input name="domain_name" value="{{ old('domain_name') }}" 
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" 
                                           placeholder="alanadi.com">
                                </div>
                                
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Registrar Ref</label>
                                    <input name="registrar_ref" value="{{ old('registrar_ref') }}" 
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" 
                                           placeholder="Registrar referansı">
                                </div>
                                
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Auth Code</label>
                                    <input name="auth_code" value="{{ old('auth_code') }}" 
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" 
                                           placeholder="Transfer kodu">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hosting Bilgileri -->
                    <div id="hosting_fields" class="space-y-4 {{ old('service_type')==='hosting' ? '' : 'hidden' }}">
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/30 dark:to-emerald-900/30 rounded-2xl p-6 border border-green-200 dark:border-green-700 shadow-lg">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-xl flex items-center justify-center">
                                    <span class="text-3xl">🖥️</span>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Hosting Bilgileri</h3>
                                    <p class="text-gray-600 dark:text-gray-400">Sunucu ve panel bilgileri</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Paket Adı</label>
                                    <input name="plan_name" value="{{ old('plan_name') }}" 
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" 
                                           placeholder="Premium Hosting">
                                </div>
                                
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sunucu Adı</label>
                                    <input name="server_name" value="{{ old('server_name') }}" 
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" 
                                           placeholder="server1.example.com">
                                </div>
                                
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">IP Adresi</label>
                                    <input name="ip_address" value="{{ old('ip_address') }}" 
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" 
                                           placeholder="192.168.1.1">
                                </div>
                                
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">cPanel Kullanıcı</label>
                                    <input name="cpanel_username" value="{{ old('cpanel_username') }}" 
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" 
                                           placeholder="kullanici">
                                </div>
                                
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Panel Ref</label>
                                    <input name="panel_ref" value="{{ old('panel_ref') }}" 
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" 
                                           placeholder="Panel referansı">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notlar -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Notlar</h2>
                                <p class="text-gray-600 dark:text-gray-400">Hizmet hakkında ek bilgiler</p>
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hizmet Notları</label>
                            <textarea name="notes" rows="4" 
                                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" 
                                      placeholder="Hizmet hakkında notlar...">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <!-- Otomatik Fatura Oluşturma -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Otomatik Fatura Oluşturma</h2>
                                <p class="text-gray-600 dark:text-gray-400">Hizmet oluşturulduğunda otomatik fatura oluştur ve müşteriye gönder</p>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/30 dark:to-pink-900/30 rounded-2xl p-6 border border-purple-200 dark:border-purple-700 shadow-lg">
                            <div class="space-y-4">
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" name="auto_create_invoice" id="auto_create_invoice" value="1" 
                                           {{ old('auto_create_invoice') ? 'checked' : '' }}
                                           class="w-5 h-5 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="auto_create_invoice" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Otomatik fatura oluştur (satış fiyatı varsa)
                                    </label>
                                </div>
                                
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" name="send_invoice_email" id="send_invoice_email" value="1" 
                                           {{ old('send_invoice_email') ? 'checked' : '' }}
                                           class="w-5 h-5 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="send_invoice_email" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Faturayı müşteriye e-posta ile gönder
                                    </label>
                                </div>
                                
                                <div class="text-sm text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-800 rounded-lg p-3 border border-gray-200 dark:border-gray-600">
                                    <p><strong>Not:</strong> Otomatik fatura oluşturma sadece satış fiyatı 0'dan büyük olduğunda çalışır.</p>
                                    <p class="mt-1">E-posta gönderimi için müşterinin geçerli bir e-posta adresine sahip olması gerekir.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-between items-center pt-8 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('services.index') }}" 
                           class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 font-medium">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            İptal
                        </a>
                        @php($isDemo = auth()->check() && strcasecmp(auth()->user()->email, config('app.demo_email')) === 0)
                        <button type="submit" @if($isDemo) disabled @endif
                                class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:scale-105 {{ $isDemo ? 'opacity-50 cursor-not-allowed' : '' }}" title="{{ $isDemo ? 'Demo modunda kapalı' : '' }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Hizmeti Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const typeSelect = document.getElementById('service_type');
        const domainBox = document.getElementById('domain_fields');
        const hostingBox = document.getElementById('hosting_fields');
        const autoCreateInvoiceCheckbox = document.getElementById('auto_create_invoice');
        const sendInvoiceEmailCheckbox = document.getElementById('send_invoice_email');
        
        if (typeSelect) {
            typeSelect.addEventListener('change', () => {
                const v = typeSelect.value;
                
                // Tüm alanları gizle
                domainBox.classList.add('hidden');
                hostingBox.classList.add('hidden');
                
                // Seçilen türe göre göster
                if (v === 'domain') {
                    domainBox.classList.remove('hidden');
                } else if (v === 'hosting') {
                    hostingBox.classList.remove('hidden');
                }
                
                // Animasyon efekti
                if (v) {
                    typeSelect.classList.add('border-green-500', 'bg-green-50');
                    setTimeout(() => {
                        typeSelect.classList.remove('border-green-500', 'bg-green-50');
                    }, 1000);
                }
            });
        }
        
        // Otomatik fatura oluşturma checkbox'ı değiştiğinde
        if (autoCreateInvoiceCheckbox) {
            autoCreateInvoiceCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    // Otomatik fatura oluşturma seçildiğinde e-posta gönderimi de seçili olsun
                    if (sendInvoiceEmailCheckbox) {
                        sendInvoiceEmailCheckbox.checked = true;
                    }
                }
            });
        }
        
        // E-posta gönderimi checkbox'ı değiştiğinde
        if (sendInvoiceEmailCheckbox) {
            sendInvoiceEmailCheckbox.addEventListener('change', function() {
                if (!this.checked && autoCreateInvoiceCheckbox) {
                    // E-posta gönderimi kapatıldığında otomatik fatura oluşturma da kapatılsın
                    autoCreateInvoiceCheckbox.checked = false;
                }
            });
        }
        
        // Sayfa yüklendiğinde mevcut seçimi kontrol et
        document.addEventListener('DOMContentLoaded', function() {
            const currentType = typeSelect.value;
            if (currentType === 'domain') {
                domainBox.classList.remove('hidden');
            } else if (currentType === 'hosting') {
                hostingBox.classList.remove('hidden');
            }
        });
    </script>
</x-app-layout>


