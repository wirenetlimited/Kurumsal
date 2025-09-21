<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-green-50 dark:from-gray-900 dark:to-green-900/20">
        <div class="max-w-7xl mx-auto p-6 space-y-8">
            <!-- Header -->
            <div class="relative overflow-hidden bg-gradient-to-r from-green-600 via-teal-600 to-emerald-600 rounded-2xl p-8 text-white shadow-2xl">
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between">
                        <div class="space-y-2">
                            <h1 class="text-3xl font-bold">Sağlayıcı Düzenle</h1>
                            <p class="text-green-100 text-lg">Sağlayıcı bilgilerini güncelleyin</p>
                        </div>
                        <div class="text-right">
                            <div class="text-5xl mb-2">🏢</div>
                            <div class="text-green-100 font-medium">Sağlayıcı Sistemi</div>
                        </div>
                    </div>
                </div>
                
                <!-- Decorative Elements -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Provider Edit Form -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Provider Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <form method="POST" action="{{ route('providers.update', $provider) }}" class="p-8 space-y-8">
                            @csrf
                            @method('PUT')
                            
                            <!-- Basic Information -->
                            <div class="space-y-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Temel Bilgiler</h2>
                                        <p class="text-gray-600 dark:text-gray-400">Şirket adı ve hizmet türleri</p>
                                    </div>
                                </div>
                                
                                <div class="space-y-4">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sağlayıcı Adı *</label>
                                        <input name="name" value="{{ old('name', $provider->name) }}" 
                                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                               placeholder="Sağlayıcı adı" required>
                                        @error('name')
                                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hizmet Türleri *</label>
                                        <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4 space-y-3">
                                            @php
                                                $providerTypes = is_array($provider->type) ? $provider->type : [$provider->type];
                                                $oldTypes = old('types', $providerTypes);
                                                
                                                // Site ayarlarından hizmet türlerini al
                                                $serviceTypesSetting = \App\Models\Setting::where('key', 'service_types')->first();
                                                $serviceTypes = $serviceTypesSetting ? json_decode($serviceTypesSetting->value, true) : [];
                                                
                                                // Varsayılan türler (eğer ayar yoksa)
                                                if (empty($serviceTypes)) {
                                                    $serviceTypes = [
                                                        ['id' => 'domain', 'name' => 'Domain', 'icon' => '🌐'],
                                                        ['id' => 'hosting', 'name' => 'Hosting', 'icon' => '🖥️'],
                                                        ['id' => 'ssl', 'name' => 'SSL Hizmetleri', 'icon' => '🔒'],
                                                        ['id' => 'email', 'name' => 'E-mail Paketleri', 'icon' => '📧'],
                                                        ['id' => 'other', 'name' => 'Diğer', 'icon' => '📦'],
                                                    ];
                                                }
                                            @endphp
                                            @foreach($serviceTypes as $serviceType)
                                                <div class="flex items-center">
                                                    <input type="checkbox" name="types[]" value="{{ $serviceType['id'] }}" 
                                                           id="type_{{ $serviceType['id'] }}" 
                                                           {{ in_array($serviceType['id'], $oldTypes) ? 'checked' : '' }}
                                                           class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                    <label for="type_{{ $serviceType['id'] }}" class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                                        {{ $serviceType['icon'] }} {{ $serviceType['name'] }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Birden fazla hizmet türü seçebilirsiniz</p>
                                        @error('types')
                                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="space-y-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">İletişim Bilgileri</h2>
                                        <p class="text-gray-600 dark:text-gray-400">İletişim ve iletişim bilgileri</p>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Website</label>
                                        <input name="website" value="{{ old('website', $contact['website'] ?? '') }}" 
                                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                               placeholder="https://www.example.com">
                                        @error('website')
                                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">E-posta</label>
                                        <input name="email" type="email" value="{{ old('email', $contact['email'] ?? '') }}" 
                                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                               placeholder="info@example.com">
                                        @error('email')
                                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Destek E-postası</label>
                                        <input name="support_email" value="{{ old('support_email', $contact['support_email'] ?? '') }}" 
                                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                               placeholder="destek@example.com">
                                        @error('support_email')
                                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telefon</label>
                                        <input name="phone" value="{{ old('phone', $contact['phone'] ?? '') }}" 
                                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                               placeholder="+90 212 123 45 67">
                                        @error('phone')
                                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Faks</label>
                                        <input name="fax" value="{{ old('fax', $contact['fax'] ?? '') }}" 
                                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                               placeholder="+90 212 123 45 68">
                                        @error('fax')
                                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Address Information -->
                            <div class="space-y-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Adres Bilgileri</h2>
                                        <p class="text-gray-600 dark:text-gray-400">Fiziksel adres ve konum bilgileri</p>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2 md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Adres</label>
                                        <textarea name="address" rows="3" 
                                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                                  placeholder="Şirket adresi...">{{ old('address', $contact['address'] ?? '') }}</textarea>
                                        @error('address')
                                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Şehir</label>
                                        <input name="city" value="{{ old('city', $contact['city'] ?? '') }}" 
                                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                               placeholder="İstanbul">
                                        @error('city')
                                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ülke</label>
                                        <input name="country" value="{{ old('country', $contact['country'] ?? 'Türkiye') }}" 
                                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                               placeholder="Türkiye">
                                        @error('country')
                                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Posta Kodu</label>
                                        <input name="postal_code" value="{{ old('postal_code', $contact['postal_code'] ?? '') }}" 
                                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                               placeholder="34000">
                                        @error('postal_code')
                                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="space-y-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Ek Bilgiler</h2>
                                        <p class="text-gray-600 dark:text-gray-400">Vergi numarası ve notlar</p>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Vergi Numarası</label>
                                        <input name="tax_number" value="{{ old('tax_number', $contact['tax_number'] ?? '') }}" 
                                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                               placeholder="1234567890">
                                        @error('tax_number')
                                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Vergi Dairesi</label>
                                        <input name="tax_office" value="{{ old('tax_office', $contact['tax_office'] ?? '') }}" 
                                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                               placeholder="Kadıköy">
                                        @error('tax_office')
                                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notlar</label>
                                    <textarea name="notes" rows="4" 
                                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                              placeholder="Sağlayıcı hakkında ek notlar...">{{ old('notes', $contact['notes'] ?? '') }}</textarea>
                                    @error('notes')
                                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-between items-center pt-8 border-t border-gray-200 dark:border-gray-700">
                                <a href="{{ route('providers.index') }}" 
                                   class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 font-medium">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    İptal
                                </a>
                                <button type="submit" 
                                        class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-xl hover:from-green-700 hover:to-teal-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:scale-105">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Sağlayıcıyı Güncelle
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Quick Service Addition -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Bu Sağlayıcıya Hızlı Hizmet Ekle</h2>
                                <p class="text-gray-600 dark:text-gray-400">Mevcut sağlayıcıya yeni hizmet ekleyin</p>
                            </div>
                        </div>
                        
                        <form method="POST" action="{{ route('services.store') }}" class="space-y-6">
                            @csrf
                            <input type="hidden" name="provider_id" value="{{ $provider->id }}" />
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Müşteri *</label>
                                    <select name="customer_id" 
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                            required>
                                        @foreach($customers as $c)
                                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hizmet Türü *</label>
                                    <select name="service_type" 
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                            required>
                                        <option value="domain">🌐 Domain</option>
                                        <option value="hosting">🖥️ Hosting</option>
                                        <option value="ssl">🔒 SSL</option>
                                    </select>
                                </div>
                                
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Durum</label>
                                    <select name="status" 
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                        @foreach(['active','suspended','cancelled','expired'] as $st)
                                            <option value="{{ $st }}" {{ $st==='active' ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Başlangıç Tarihi</label>
                                    <input type="date" name="start_date" 
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                </div>
                                
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bitiş Tarihi</label>
                                    <input type="date" name="end_date" 
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                </div>
                                
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dönem</label>
                                    <select name="cycle" 
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                        @foreach(['monthly','quarterly','semiannually','yearly','biennially','triennially'] as $cy)
                                            <option value="{{ $cy }}" {{ $cy==='yearly' ? 'selected' : '' }}>{{ ucfirst($cy) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Maliyet</label>
                                    <input name="cost_price" 
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                           placeholder="0.00">
                                </div>
                                
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Satış Fiyatı</label>
                                    <input name="sell_price" 
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                           placeholder="0.00">
                                </div>
                                
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Not</label>
                                    <input name="notes" 
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                           placeholder="Hizmet notu">
                                </div>
                            </div>
                            
                            <div class="flex justify-end">
                                <button type="submit" 
                                        class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:scale-105">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Hizmet Ekle
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Provider Services -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Sağlayıcı Hizmetleri</h3>
                                <p class="text-gray-600 dark:text-gray-400">Mevcut hizmetlerin listesi</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            @if($services->count() > 0)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4">
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                        <span class="font-medium">{{ $services->count() }}</span> aktif hizmet bulunuyor
                                    </div>
                                    
                                    <div class="space-y-3">
                                        @foreach($services->take(5) as $s)
                                            <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-600 rounded-xl border border-gray-200 dark:border-gray-500">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                                        <span class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                                            @if($s->service_type === 'domain') 🌐
                                                            @elseif($s->service_type === 'hosting') 🖥️
                                                            @else 🔒
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $s->customer->name ?? '-' }}</div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst($s->service_type) }}</div>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $s->start_date ? \Carbon\Carbon::parse($s->start_date)->format('d.m.Y') : '-' }}</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $s->end_date ? \Carbon\Carbon::parse($s->end_date)->format('d.m.Y') : '-' }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    @if($services->count() > 5)
                                        <div class="text-center mt-4">
                                            <span class="text-sm text-gray-500 dark:text-gray-400">+{{ $services->count() - 5 }} hizmet daha...</span>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">Henüz hizmet eklenmemiş</p>
                                    <p class="text-gray-400 dark:text-gray-500 text-xs mt-1">Yukarıdaki form ile hizmet ekleyebilirsiniz</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Hızlı İstatistikler</h3>
                                <p class="text-gray-600 dark:text-gray-400">Hizmet durumu özeti</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Toplam Hizmet</span>
                                <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">{{ $services->count() }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Aktif Hizmet</span>
                                <span class="text-sm font-semibold text-green-600 dark:text-green-400">{{ $services->where('status', 'active')->count() }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Süresi Dolan</span>
                                <span class="text-sm font-semibold text-red-600 dark:text-red-400">{{ $services->where('status', 'expired')->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Hizmet türü seçimlerini güncelle
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('input[name="types[]"]');
            
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const label = this.nextElementSibling;
                    if (this.checked) {
                        label.classList.add('text-blue-600', 'dark:text-blue-400');
                        label.classList.remove('text-gray-700', 'dark:text-gray-300');
                    } else {
                        label.classList.remove('text-blue-600', 'dark:text-blue-400');
                        label.classList.add('text-gray-700', 'dark:text-gray-300');
                    }
                });
            });
        });
    </script>
</x-app-layout>


