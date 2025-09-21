<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 dark:from-gray-900 dark:to-slate-800">
        <div class="max-w-7xl mx-auto p-6 space-y-8">
            <!-- Header -->
            <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 rounded-2xl p-8 text-white shadow-2xl">
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between">
                        <div class="space-y-2">
                            <h1 class="text-3xl font-bold">Yeni Teklif Oluştur</h1>
                            <p class="text-blue-100 text-lg">Müşteri için profesyonel teklif hazırlayın</p>
                        </div>
                        <div class="text-right">
                            <div class="text-5xl mb-2">📋</div>
                            <div class="text-blue-100 font-medium">Teklif Sistemi</div>
                        </div>
                    </div>
                </div>
                
                <!-- Decorative Elements -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                <form method="POST" action="{{ route('quotes.store') }}" id="quoteForm" class="p-8 space-y-8">
                    @csrf

                    <!-- Company Header -->
                    <div class="bg-gradient-to-r from-gray-50 to-blue-50 dark:from-gray-700 dark:to-slate-700 rounded-xl p-6 border border-gray-200 dark:border-gray-600">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Şirket Bilgileri</h2>
                                <p class="text-gray-600 dark:text-gray-400">Teklif üst bilgileri</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Teklif Başlığı</label>
                                <input name="title" 
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" 
                                       placeholder="Örn: Web Hosting Paketi Teklifi">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Durum</label>
                                <select name="status" 
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    @foreach(['draft'=>'📝 Taslak','sent'=>'📤 Gönderildi','accepted'=>'✅ Kabul Edildi','rejected'=>'❌ Reddedildi','expired'=>'⏰ Süresi Doldu'] as $k=>$v)
                                        <option value="{{ $k }}" {{ $k==='draft' ? 'selected' : '' }}>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Müşteri Bilgileri</h2>
                                <p class="text-gray-600 dark:text-gray-400">Müşteri seçimi veya manuel giriş</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Müşteri Seçimi</label>
                                <div class="flex gap-2">
                                    <select name="customer_id" id="customer_id"
                                            class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                        <option value="">Müşteri seçin veya manuel girin</option>
                                        @foreach($customers as $c)
                                            <option value="{{ $c->id }}" 
                                                    data-name="{{ $c->customer_type === 'corporate' ? $c->name : $c->name . ' ' . ($c->surname ?? '') }}"
                                                    data-email="{{ $c->email }}"
                                                    data-phone="{{ $c->phone ?? '' }}">
                                                {{ $c->customer_type === 'corporate' ? $c->name : $c->name . ' ' . ($c->surname ?? '') }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="button" 
                                            onclick="enableManualEntry()"
                                            class="px-4 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-all duration-200 font-medium">
                                        Manuel Giriş
                                    </button>
                                </div>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Müşteri Adı</label>
                                <input name="customer_name" id="customer_name"
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" 
                                       placeholder="Müşteri adı">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">E-posta</label>
                                <input name="customer_email" id="customer_email" type="email" 
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" 
                                       placeholder="ornek@email.com">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telefon</label>
                                <input name="customer_phone" id="customer_phone"
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" 
                                       placeholder="0532 123 45 67">
                            </div>
                            
                            <div class="space-y-2 md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Adres</label>
                                <textarea name="address" rows="3" 
                                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" 
                                          placeholder="Müşteri adresi..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Quote Details -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Teklif Detayları</h2>
                                <p class="text-gray-600 dark:text-gray-400">Tarih ve geçerlilik bilgileri</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Teklif Tarihi *</label>
                                <input type="date" name="quote_date" value="{{ now()->format('Y-m-d') }}" 
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                       required>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Geçerlilik Tarihi</label>
                                <input type="date" name="valid_until" 
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            </div>
                        </div>
                    </div>

                    <!-- Products/Services -->
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Ürün/Hizmetler</h2>
                                    <p class="text-gray-600 dark:text-gray-400">Teklif kalemlerini ekleyin</p>
                                </div>
                            </div>
                            
                            <button type="button" 
                                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl"
                                    onclick="addRow()">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Ürün/Hizmet Ekle
                            </button>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-6 border border-gray-200 dark:border-gray-600">
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead>
                                        <tr class="border-b border-gray-200 dark:border-gray-600">
                                            <th class="text-left py-4 px-4 font-semibold text-gray-700 dark:text-gray-300">Açıklama</th>
                                            <th class="text-left py-4 px-4 font-semibold text-gray-700 dark:text-gray-300">Miktar</th>
                                            <th class="text-left py-4 px-4 font-semibold text-gray-700 dark:text-gray-300">Birim Fiyat</th>
                                            <th class="text-left py-4 px-4 font-semibold text-gray-700 dark:text-gray-300">Toplam</th>
                                            <th class="text-left py-4 px-4 font-semibold text-gray-700 dark:text-gray-300">İşlem</th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemsBody" class="divide-y divide-gray-200 dark:divide-gray-600">
                                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                            <td class="py-4 px-4">
                                                <input name="items[0][description]" 
                                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" 
                                                       placeholder="Ürün/hizmet açıklaması" required>
                                            </td>
                                            <td class="py-4 px-4">
                                                <input name="items[0][qty]" type="number" min="1" value="1" 
                                                       class="w-20 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                                       required onchange="calculateRowTotal(this)">
                                            </td>
                                            <td class="py-4 px-4">
                                                <input name="items[0][unit_price]" type="number" step="0.01" min="0" value="0" 
                                                       class="w-24 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                                       required onchange="calculateRowTotal(this)">
                                            </td>
                                            <td class="py-4 px-4">
                                                <span class="row-total font-semibold text-gray-900 dark:text-white">₺0.00</span>
                                            </td>
                                            <td class="py-4 px-4">
                                                <button type="button" 
                                                        class="text-red-600 hover:text-red-800 transition-colors p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20" 
                                                        onclick="removeRow(this)">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Financial Settings -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Finansal Ayarlar</h2>
                                <p class="text-gray-600 dark:text-gray-400">KDV ve indirim bilgileri</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">KDV (%)</label>
                                <input name="tax_rate" type="number" min="0" max="100" value="20" 
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                       onchange="calculateTotals()">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">İndirim (₺)</label>
                                <input name="discount_amount" type="number" step="0.01" min="0" value="0" 
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                       onchange="calculateTotals()">
                            </div>
                        </div>
                    </div>

                    <!-- Notes and Terms -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Notlar ve Şartlar</h2>
                                <p class="text-gray-600 dark:text-gray-400">Teklif detayları ve koşullar</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Teklif Notları</label>
                                <textarea name="notes" rows="4" 
                                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" 
                                          placeholder="Teklif ile ilgili notlar..."></textarea>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Şartlar ve Koşullar</label>
                                <textarea name="terms" rows="4" 
                                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" 
                                          placeholder="Şartlar ve koşullar..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Quote Summary -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/30 dark:to-indigo-900/30 rounded-2xl p-8 border border-blue-200 dark:border-blue-700">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Teklif Özeti</h2>
                                <p class="text-gray-600 dark:text-gray-400">Finansal detaylar</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div class="text-center p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-600">
                                <div class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Ara Toplam</div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-white" id="subtotal">₺0.00</div>
                            </div>
                            
                            <div class="text-center p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-600">
                                <div class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">KDV</div>
                                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400" id="taxAmount">₺0.00</div>
                            </div>
                            
                            <div class="text-center p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-600">
                                <div class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">İndirim</div>
                                <div class="text-2xl font-bold text-red-600 dark:text-red-400" id="discountAmount">₺0.00</div>
                            </div>
                            
                            <div class="text-center p-4 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl text-white border border-green-400">
                                <div class="text-sm font-medium text-green-100 mb-2">Genel Toplam</div>
                                <div class="text-3xl font-bold text-white" id="grandTotal">₺0.00</div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-between items-center pt-8 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" 
                                onclick="clearFormData()"
                                class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 font-medium">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Formu Temizle
                        </button>
                        
                        <div class="flex gap-4">
                            <a href="{{ route('quotes.index') }}" 
                               class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 font-medium">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                İptal
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Teklifi Oluştur
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let rowIndex = 1;
        
        // Müşteri bilgilerini otomatik doldur
        function fillCustomerInfo(customerId) {
            if (!customerId) {
                clearCustomerFields();
                return;
            }
            
            fetch(`/api/customers/${customerId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const customer = data.customer;
                        
                        document.querySelector('input[name="customer_name"]').value = 
                            customer.customer_type === 'corporate' ? customer.name : `${customer.name} ${customer.surname || ''}`.trim();
                        
                        document.querySelector('input[name="customer_email"]').value = customer.email || '';
                        
                        document.querySelector('input[name="customer_phone"]').value = customer.phone_mobile || customer.phone || '';
                        
                        const addressParts = [];
                        if (customer.address) addressParts.push(customer.address);
                        if (customer.district) addressParts.push(customer.district);
                        if (customer.city) addressParts.push(customer.city);
                        if (customer.zip) addressParts.push(customer.zip);
                        if (customer.country) addressParts.push(customer.country);
                        
                        document.querySelector('textarea[name="address"]').value = addressParts.join(', ');
                        
                        setCustomerFieldsReadonly(true);
                    }
                })
                .catch(error => {
                    console.error('Müşteri bilgileri alınamadı:', error);
                });
        }
        
        function clearCustomerFields() {
            document.querySelector('input[name="customer_name"]').value = '';
            document.querySelector('input[name="customer_email"]').value = '';
            document.querySelector('input[name="customer_phone"]').value = '';
            document.querySelector('textarea[name="address"]').value = '';
            setCustomerFieldsReadonly(false);
        }
        
        function setCustomerFieldsReadonly(readonly) {
            const fields = [
                'input[name="customer_name"]',
                'input[name="customer_email"]',
                'input[name="customer_phone"]',
                'textarea[name="address"]'
            ];
            
            fields.forEach(selector => {
                const field = document.querySelector(selector);
                if (field) {
                    field.readOnly = readonly;
                    field.classList.toggle('bg-gray-100', readonly);
                    field.classList.toggle('bg-white', !readonly);
                    field.classList.toggle('dark:bg-gray-600', readonly);
                    field.classList.toggle('dark:bg-gray-700', !readonly);
                }
            });
        }
        
        function enableManualEntry() {
            document.querySelector('select[name="customer_id"]').value = '';
            setCustomerFieldsReadonly(false);
            clearCustomerFields();
        }
        
        function addRow() {
            const body = document.getElementById('itemsBody');
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors';
            tr.innerHTML = `
                <td class="py-4 px-4">
                    <input name="items[${rowIndex}][description]" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" 
                           placeholder="Ürün/hizmet açıklaması" required>
                </td>
                <td class="py-4 px-4">
                    <input name="items[${rowIndex}][qty]" type="number" min="1" value="1" 
                           class="w-20 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                           required onchange="calculateRowTotal(this)">
                </td>
                <td class="py-4 px-4">
                    <input name="items[${rowIndex}][unit_price]" type="number" step="0.01" min="0" value="0" 
                           class="w-24 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                           required onchange="calculateRowTotal(this)">
                </td>
                <td class="py-4 px-4">
                    <span class="row-total font-semibold text-gray-900 dark:text-white">₺0.00</span>
                </td>
                <td class="py-4 px-4">
                    <button type="button" 
                            class="text-red-600 hover:text-red-800 transition-colors p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20" 
                            onclick="removeRow(this)">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </td>
            `;
            body.appendChild(tr);
            rowIndex++;
        }
        
        function removeRow(btn) {
            btn.closest('tr').remove();
            calculateTotals();
        }
        
        function calculateRowTotal(input) {
            const row = input.closest('tr');
            const qty = parseFloat(row.querySelector('input[name*="[qty]"]').value) || 0;
            const unitPrice = parseFloat(row.querySelector('input[name*="[unit_price]"]').value) || 0;
            
            const total = qty * unitPrice;
            row.querySelector('.row-total').textContent = `₺${total.toFixed(2)}`;
            calculateTotals();
        }
        
        function calculateTotals() {
            let subtotal = 0;
            
            document.querySelectorAll('#itemsBody tr').forEach(row => {
                const qty = parseFloat(row.querySelector('input[name*="[qty]"]').value) || 0;
                const unitPrice = parseFloat(row.querySelector('input[name*="[unit_price]"]').value) || 0;
                subtotal += qty * unitPrice;
            });
            
            const taxRate = parseFloat(document.querySelector('input[name="tax_rate"]').value) || 0;
            const discountAmount = parseFloat(document.querySelector('input[name="discount_amount"]').value) || 0;
            
            const taxAmount = subtotal * (taxRate / 100);
            const grandTotal = subtotal + taxAmount - discountAmount;
            
            document.getElementById('subtotal').textContent = `₺${subtotal.toFixed(2)}`;
            document.getElementById('taxAmount').textContent = `₺${taxAmount.toFixed(2)}`;
            document.getElementById('discountAmount').textContent = `₺${discountAmount.toFixed(2)}`;
            document.getElementById('grandTotal').textContent = `₺${grandTotal.toFixed(2)}`;
        }
        
        // Form verilerini localStorage'a kaydet
        function saveFormData() {
            const formData = {
                customer_id: document.querySelector('select[name="customer_id"]').value,
                customer_name: document.querySelector('input[name="customer_name"]').value,
                customer_email: document.querySelector('input[name="customer_email"]').value,
                customer_phone: document.querySelector('input[name="customer_phone"]').value,
                address: document.querySelector('textarea[name="address"]').value,
                title: document.querySelector('input[name="title"]').value,
                status: document.querySelector('select[name="status"]').value,
                quote_date: document.querySelector('input[name="quote_date"]').value,
                valid_until: document.querySelector('input[name="valid_until"]').value,
                tax_rate: document.querySelector('input[name="tax_rate"]').value,
                discount_amount: document.querySelector('input[name="discount_amount"]').value,
                notes: document.querySelector('textarea[name="notes"]').value,
                terms: document.querySelector('textarea[name="terms"]').value
            };
            
            localStorage.setItem('quoteFormData', JSON.stringify(formData));
        }
        
        function loadFormData() {
            const savedData = localStorage.getItem('quoteFormData');
            if (savedData) {
                try {
                    const data = JSON.parse(savedData);
                    
                    if (data.customer_id) {
                        document.querySelector('select[name="customer_id"]').value = data.customer_id;
                        fillCustomerInfo(data.customer_id);
                    }
                    
                    if (data.customer_name) document.querySelector('input[name="customer_name"]').value = data.customer_name;
                    if (data.customer_email) document.querySelector('input[name="customer_email"]').value = data.customer_email;
                    if (data.customer_phone) document.querySelector('input[name="customer_phone"]').value = data.customer_phone;
                    if (data.address) document.querySelector('textarea[name="address"]').value = data.address;
                    if (data.title) document.querySelector('input[name="title"]').value = data.title;
                    if (data.status) document.querySelector('select[name="status"]').value = data.status;
                    if (data.quote_date) document.querySelector('input[name="quote_date"]').value = data.quote_date;
                    if (data.valid_until) document.querySelector('input[name="valid_until"]').value = data.valid_until;
                    if (data.tax_rate) document.querySelector('input[name="tax_rate"]').value = data.tax_rate;
                    if (data.discount_amount) document.querySelector('input[name="discount_amount"]').value = data.discount_amount;
                    if (data.notes) document.querySelector('textarea[name="notes"]').value = data.notes;
                    if (data.terms) document.querySelector('textarea[name="terms"]').value = data.terms;
                    
                    calculateTotals();
                } catch (e) {
                    console.error('Form verileri yüklenirken hata:', e);
                }
            }
        }
        
        function clearFormData() {
            localStorage.removeItem('quoteFormData');
            
            document.querySelector('select[name="customer_id"]').value = '';
            document.querySelector('input[name="customer_name"]').value = '';
            document.querySelector('input[name="customer_email"]').value = '';
            document.querySelector('input[name="customer_phone"]').value = '';
            document.querySelector('textarea[name="address"]').value = '';
            document.querySelector('input[name="title"]').value = '';
            document.querySelector('select[name="status"]').value = 'draft';
            document.querySelector('input[name="quote_date"]').value = new Date().toISOString().split('T')[0];
            document.querySelector('input[name="valid_until"]').value = '';
            document.querySelector('input[name="tax_rate"]').value = '20';
            document.querySelector('input[name="discount_amount"]').value = '0';
            document.querySelector('textarea[name="notes"]').value = '';
            document.querySelector('textarea[name="terms"]').value = '';
            
            setCustomerFieldsReadonly(false);
            calculateTotals();
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            loadFormData();
            
            document.querySelector('select[name="customer_id"]').addEventListener('change', function() {
                alert('Müşteri seçimi değişti: ' + this.value);
                console.log('Müşteri seçimi değişti:', this.value);
                const selectedOption = this.options[this.selectedIndex];
                const customerNameField = document.getElementById('customer_name');
                const customerEmailField = document.getElementById('customer_email');
                const customerPhoneField = document.getElementById('customer_phone');
                
                console.log('Seçilen option:', selectedOption);
                console.log('Data attributes:', {
                    name: selectedOption.dataset.name,
                    email: selectedOption.dataset.email,
                    phone: selectedOption.dataset.phone
                });
                
                if (this.value) {
                    // Müşteri seçildi, bilgileri doldur
                    customerNameField.value = selectedOption.dataset.name || '';
                    customerEmailField.value = selectedOption.dataset.email || '';
                    customerPhoneField.value = selectedOption.dataset.phone || '';
                    console.log('Müşteri bilgileri dolduruldu');
                    alert('Müşteri bilgileri dolduruldu: ' + customerNameField.value);
                } else {
                    // Müşteri seçimi kaldırıldı, alanları temizle
                    customerNameField.value = '';
                    customerEmailField.value = '';
                    customerPhoneField.value = '';
                    console.log('Müşteri alanları temizlendi');
                }
                
                saveFormData();
            });
            
            const formInputs = document.querySelectorAll('input, select, textarea');
            formInputs.forEach(input => {
                input.addEventListener('change', saveFormData);
                input.addEventListener('input', saveFormData);
            });
            
            document.getElementById('quoteForm').addEventListener('submit', function() {
                // Form submit edildikten sonra temizle (success sayfasında)
                // clearFormData(); // Bu satırı kaldırdık
            });
        });
    </script>
</x-app-layout>

