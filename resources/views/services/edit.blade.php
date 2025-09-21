<x-app-layout>
    <div class="max-w-7xl mx-auto p-6 space-y-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Hizmet Düzenle</h1>
                    <p class="text-purple-100 mt-1">Hizmet bilgilerini güncelleyin</p>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold">✏️</div>
                    <div class="text-purple-100">Hizmet Düzenleme</div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
            <form method="POST" action="{{ route('services.update', $service) }}" class="p-8 space-y-8">
                @csrf
                @method('PUT')
                
                <!-- Müşteri ve Sağlayıcı -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Müşteri ve Sağlayıcı</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Müşteri *</label>
                            <select name="customer_id" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                    required>
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}" {{ old('customer_id', $service->customer_id)==$c->id ? 'selected' : '' }}>
                                        {{ $c->customer_type === 'corporate' ? $c->name : $c->name . ' ' . ($c->surname ?? '') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Sağlayıcı</label>
                            <select name="provider_id" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Sağlayıcı seçin</option>
                                @foreach($providers as $p)
                                    <option value="{{ $p->id }}" {{ old('provider_id', $service->provider_id)==$p->id ? 'selected' : '' }}>
                                        {{ $p->name }} ({{ ucfirst($p->type_string) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Hizmet Detayları -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Hizmet Detayları</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Hizmet Türü *</label>
                            <select name="service_type" id="service_type" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                    required>
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
                                    <option value="{{ $serviceType['id'] }}" {{ old('service_type', $service->service_type)===$serviceType['id'] ? 'selected' : '' }}>
                                        {{ $serviceType['icon'] }} {{ $serviceType['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Durum</label>
                            <select name="status" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                @foreach(\App\Enums\ServiceStatus::cases() as $status)
                                    <option value="{{ $status->value }}" {{ old('status', $service->status->value) === $status->value ? 'selected' : '' }}>
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
                            <label class="block text-sm font-medium text-gray-700">Dönem</label>
                            <select name="cycle" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="monthly" {{ old('cycle', $service->cycle)==='monthly' ? 'selected' : '' }}>
                                    📅 Aylık
                                </option>
                                <option value="quarterly" {{ old('cycle', $service->cycle)==='quarterly' ? 'selected' : '' }}>
                                    📅 3 Aylık
                                </option>
                                <option value="semiannually" {{ old('cycle', $service->cycle)==='semiannually' ? 'selected' : '' }}>
                                    📅 6 Aylık
                                </option>
                                <option value="yearly" {{ old('cycle', $service->cycle)==='yearly' ? 'selected' : '' }}>
                                    📅 Yıllık
                                </option>
                                <option value="biennially" {{ old('cycle', $service->cycle)==='biennially' ? 'selected' : '' }}>
                                    📅 2 Yıllık
                                </option>
                                <option value="triennially" {{ old('cycle', $service->cycle)==='triennially' ? 'selected' : '' }}>
                                    📅 3 Yıllık
                                </option>
                            </select>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Ödeme Şekli</label>
                            <select name="payment_type" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="upfront" {{ old('payment_type', $service->payment_type ?? 'upfront')==='upfront' ? 'selected' : '' }}>
                                    💰 Peşin Ödeme
                                </option>
                                <option value="installment" {{ old('payment_type', $service->payment_type ?? 'upfront')==='installment' ? 'selected' : '' }}>
                                    📅 Taksit Ödeme
                                </option>
                            </select>
                            <p class="text-xs text-gray-500">
                                <strong>Peşin:</strong> Gelir başlangıç ayında, <strong>Taksit:</strong> Gelir her ay eşit bölünür
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tarih ve Fiyat Bilgileri -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900">Tarih ve Fiyat Bilgileri</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Başlangıç Tarihi</label>
                            <input type="date" name="start_date" value="{{ old('start_date', $service->start_date?->format('Y-m-d')) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Bitiş Tarihi</label>
                            <input type="date" name="end_date" value="{{ old('end_date', $service->end_date?->format('Y-m-d')) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Maliyet</label>
                                <input name="cost_price" value="{{ old('cost_price', $service->cost_price) }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                       placeholder="0.00">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Satış Fiyatı</label>
                                <input name="sell_price" value="{{ old('sell_price', $service->sell_price) }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                       placeholder="0.00">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Domain Bilgileri -->
                <div id="domain_fields" class="space-y-4 {{ old('service_type', $service->service_type)==='domain' ? '' : 'hidden' }}">
                    <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <span class="text-2xl">🌐</span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Domain Bilgileri</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Domain Adı</label>
                                <input name="domain_name" value="{{ old('domain_name', $service->domain->domain_name ?? '') }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                       placeholder="alanadi.com">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Registrar Ref</label>
                                <input name="registrar_ref" value="{{ old('registrar_ref', $service->domain->registrar_ref ?? '') }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                       placeholder="Registrar referansı">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Auth Code</label>
                                <input name="auth_code" value="{{ old('auth_code', $service->domain->auth_code ?? '') }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                       placeholder="Transfer kodu">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hosting Bilgileri -->
                <div id="hosting_fields" class="space-y-4 {{ old('service_type', $service->service_type)==='hosting' ? '' : 'hidden' }}">
                    <div class="bg-green-50 rounded-lg p-6 border border-green-200">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <span class="text-2xl">🖥️</span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Hosting Bilgileri</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Paket Adı</label>
                                <input name="plan_name" value="{{ old('plan_name', $service->hosting->plan_name ?? '') }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                       placeholder="Premium Hosting">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Sunucu Adı</label>
                                <input name="server_name" value="{{ old('server_name', $service->hosting->server_name ?? '') }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                       placeholder="server1.example.com">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">IP Adresi</label>
                                <input name="ip_address" value="{{ old('ip_address', $service->hosting->ip_address ?? '') }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                       placeholder="192.168.1.1">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">cPanel Kullanıcı</label>
                                <input name="cpanel_username" value="{{ old('cpanel_username', $service->hosting->cpanel_username ?? '') }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                       placeholder="kullanici">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Panel Ref</label>
                                <input name="panel_ref" value="{{ old('panel_ref', $service->hosting->panel_ref ?? '') }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                       placeholder="Panel referansı">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notlar -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900">Notlar</h2>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Hizmet Notları</label>
                        <textarea name="notes" rows="4" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                  placeholder="Hizmet hakkında notlar...">{{ old('notes', $service->notes) }}</textarea>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('services.index') }}" 
                       class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        İptal
                    </a>
                    @php($isDemo = auth()->check() && strcasecmp(auth()->user()->email, config('app.demo_email')) === 0)
                    <button type="submit" @if($isDemo) disabled @endif
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl {{ $isDemo ? 'opacity-50 cursor-not-allowed' : '' }}" title="{{ $isDemo ? 'Demo modunda kapalı' : '' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Hizmeti Güncelle
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const typeSelect = document.getElementById('service_type');
        const domainBox = document.getElementById('domain_fields');
        const hostingBox = document.getElementById('hosting_fields');
        
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


