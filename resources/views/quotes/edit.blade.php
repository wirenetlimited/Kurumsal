<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 dark:from-gray-900 dark:to-slate-800">
        <div class="max-w-7xl mx-auto p-6 space-y-8">
            <!-- Header -->
            <div class="relative overflow-hidden bg-gradient-to-r from-yellow-600 via-orange-600 to-red-600 rounded-2xl p-8 text-white shadow-2xl">
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between">
                        <div class="space-y-2">
                            <h1 class="text-3xl font-bold">Teklif Düzenle</h1>
                            <p class="text-yellow-100 text-lg">Teklif bilgilerini güncelleyin</p>
                        </div>
                        <div class="text-right">
                            <div class="text-5xl mb-2">💼</div>
                            <div class="text-yellow-100 font-medium">Teklif #{{ $quote->number ?? $quote->id }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Decorative Elements -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                <form method="POST" action="{{ route('quotes.update', $quote) }}" class="p-8 space-y-8">
                    @csrf
                    @method('PUT')
                    
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
                                <input name="title" value="{{ old('title', $quote->title) }}" 
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" 
                                       placeholder="Örn: Web Hosting Paketi Teklifi">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Durum *</label>
                                <select name="status" 
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                        required>
                                    @foreach(['draft'=>'📝 Taslak','sent'=>'📤 Gönderildi','accepted'=>'✅ Kabul Edildi','rejected'=>'❌ Reddedildi','expired'=>'⏰ Süresi Doldu'] as $st)
                                        <option value="{{ $st }}" {{ $st == $quote->status ? 'selected' : '' }}>
                                            {{ $st }}
                                        </option>
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
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Müşteri</label>
                                <select name="customer_id" id="customer_id"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    <option value="">Müşteri Seçin</option>
                                    @foreach($customers as $c)
                                        <option value="{{ $c->id }}" 
                                                data-name="{{ $c->customer_type === 'corporate' ? $c->name : $c->name . ' ' . ($c->surname ?? '') }}"
                                                data-email="{{ $c->email }}"
                                                data-phone="{{ $c->phone ?? '' }}"
                                                {{ $c->id == $quote->customer_id ? 'selected' : '' }}>
                                            {{ $c->customer_type === 'corporate' ? $c->name : $c->name . ' ' . ($c->surname ?? '') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Müşteri Adı (Müşteri seçilmediyse)</label>
                                <input name="customer_name" id="customer_name" value="{{ old('customer_name', $quote->customer_name) }}" 
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" 
                                       placeholder="Müşteri adı">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Müşteri E-posta</label>
                                <input name="customer_email" id="customer_email" type="email" value="{{ old('customer_email', $quote->customer_email) }}" 
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" 
                                       placeholder="musteri@email.com">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Müşteri Telefon</label>
                                <input name="customer_phone" id="customer_phone" value="{{ old('customer_phone', $quote->customer_phone) }}" 
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" 
                                       placeholder="+90 212 123 45 67">
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
                                <input type="date" name="quote_date" value="{{ old('quote_date', $quote->quote_date) }}"
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                       required>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Geçerlilik Tarihi</label>
                                <input type="date" name="valid_until" value="{{ old('valid_until', $quote->valid_until) }}"
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">KDV Oranı (%)</label>
                                <input name="tax_rate" type="number" step="0.01" min="0" max="50" value="{{ old('tax_rate', $quote->tax_rate) }}"
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" 
                                       placeholder="18">
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">İndirim Tutarı</label>
                                <input name="discount_amount" type="number" step="0.01" min="0" value="{{ old('discount_amount', $quote->discount_amount) }}"
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" 
                                       placeholder="0.00">
                            </div>
                        </div>
                    </div>

                    <!-- Quote Items -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Teklif Kalemleri</h2>
                                <p class="text-gray-600 dark:text-gray-400">Ürün ve hizmet detayları</p>
                            </div>
                        </div>

                        <div id="quote-items" class="space-y-4">
                            @if($quote->items && $quote->items->count() > 0)
                                @foreach($quote->items as $index => $item)
                                <div class="quote-item border border-gray-200 dark:border-gray-600 rounded-xl p-6 bg-gray-50 dark:bg-gray-700">
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                        <div class="space-y-2">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Açıklama</label>
                                            <input name="items[{{ $index }}][description]" value="{{ $item->description }}" required
                                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Miktar</label>
                                            <input name="items[{{ $index }}][qty]" type="number" min="1" value="{{ $item->qty }}" required
                                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Birim Fiyat</label>
                                            <input name="items[{{ $index }}][unit_price]" type="number" step="0.01" min="0" value="{{ $item->unit_price }}" required
                                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tutar</label>
                                            <input type="text" value="{{ $currencySymbol }}{{ number_format($item->qty * $item->unit_price, 2, ',', '.') }}" readonly
                                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300">
                                        </div>
                                    </div>
                                    <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                                </div>
                                @endforeach
                            @else
                                <div class="quote-item border border-gray-200 dark:border-gray-600 rounded-xl p-6 bg-gray-50 dark:bg-gray-700">
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                        <div class="space-y-2">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Açıklama</label>
                                            <input name="items[0][description]" required
                                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" 
                                                   placeholder="Ürün/Hizmet açıklaması">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Miktar</label>
                                            <input name="items[0][qty]" type="number" min="1" value="1" required
                                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Birim Fiyat</label>
                                            <input name="items[0][unit_price]" type="number" step="0.01" min="0" required
                                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" 
                                                   placeholder="0.00">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tutar</label>
                                            <input type="text" value="{{ $currencySymbol }}0.00" readonly
                                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="flex justify-center">
                            <button type="button" onclick="addQuoteItem()" 
                                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 text-sm font-medium shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Kalem Ekle
                            </button>
                        </div>
                    </div>

                    <!-- Financial Summary -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/30 dark:to-indigo-900/30 rounded-2xl p-8 border border-blue-200 dark:border-blue-700">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3m6 0a3 3 0 11-6 0 3 3 0 016 0zm-9 4h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v3a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Finansal Özet</h2>
                                <p class="text-gray-600 dark:text-gray-400">Güncel hesaplamalar</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div class="text-center p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-600">
                                <div class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Ara Toplam</div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-white" id="summary-subtotal">{{ $currencySymbol }}{{ number_format($quote->subtotal ?? ($quote->items?->sum(fn($i) => $i->qty * $i->unit_price) ?? 0), 2, ',', '.') }}</div>
                            </div>
                            
                            <div class="text-center p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-600">
                                <div class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">İndirim</div>
                                <div class="text-2xl font-bold text-red-600 dark:text-red-400" id="summary-discount">- {{ $currencySymbol }}{{ number_format($quote->discount_amount ?? 0, 2, ',', '.') }}</div>
                            </div>
                            
                            <div class="text-center p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-600">
                                <div class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">KDV</div>
                                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400" id="summary-tax">{{ $currencySymbol }}{{ number_format($quote->tax_total ?? 0, 2, ',', '.') }}</div>
                            </div>
                            
                            <div class="text-center p-4 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl text-white border border-green-400">
                                <div class="text-sm font-medium text-green-100 mb-2">Genel Toplam</div>
                                <div class="text-3xl font-bold text-white" id="summary-total">{{ $currencySymbol }}{{ number_format($quote->total ?? 0, 2, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes and Terms -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
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
                                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" 
                                          placeholder="Teklif hakkında notlar...">{{ old('notes', $quote->notes) }}</textarea>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Şartlar ve Koşullar</label>
                                <textarea name="terms" rows="4"
                                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" 
                                          placeholder="Şartlar ve koşullar...">{{ old('terms', $quote->terms) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-between items-center pt-8 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('quotes.show', $quote) }}"
                           class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 font-medium">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            İptal
                        </a>
                        
                        <button type="submit"
                                class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-yellow-600 to-orange-600 text-white rounded-xl hover:from-yellow-700 hover:to-orange-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Teklifi Güncelle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let itemIndex = {{ $quote->items ? $quote->items->count() : 1 }};
        const currencySymbol = "{{ $currencySymbol }}";

        function addQuoteItem() {
            const container = document.getElementById('quote-items');
            const newItem = document.createElement('div');
            newItem.className = 'quote-item border border-gray-200 dark:border-gray-600 rounded-xl p-6 bg-gray-50 dark:bg-gray-700';
            newItem.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Açıklama</label>
                        <input name="items[${itemIndex}][description]" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" 
                               placeholder="Ürün/Hizmet açıklaması">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Miktar</label>
                        <input name="items[${itemIndex}][qty]" type="number" min="1" value="1" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Birim Fiyat</label>
                        <input name="items[${itemIndex}][unit_price]" type="number" step="0.01" min="0" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" 
                               placeholder="0.00">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tutar</label>
                        <input type="text" value="${currencySymbol}0.00" readonly
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300">
                    </div>
                </div>
                <div class="mt-3 flex justify-end">
                    <button type="button" onclick="removeQuoteItem(this)" 
                            class="inline-flex items-center px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all duration-200 text-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Kaldır
                    </button>
                </div>
            `;
            container.appendChild(newItem);
            itemIndex++;
        }

        function removeQuoteItem(button) {
            button.closest('.quote-item').remove();
        }

        // Calculate totals when qty or unit_price changes
        document.addEventListener('input', function(e) {
            if (e.target.name && (e.target.name.includes('[qty]') || e.target.name.includes('[unit_price]'))) {
                const itemDiv = e.target.closest('.quote-item');
                const qtyInput = itemDiv.querySelector('input[name*="[qty]"]');
                const priceInput = itemDiv.querySelector('input[name*="[unit_price]"]');
                const totalInput = itemDiv.querySelector('input[readonly]');
                
                if (qtyInput && priceInput && totalInput) {
                    const qty = parseFloat(qtyInput.value) || 0;
                    const price = parseFloat(priceInput.value) || 0;
                    const total = qty * price;
                    totalInput.value = currencySymbol + total.toFixed(2).replace('.', ',');
                }
            }
        });

        // Finansal özetin anlık güncellenmesi
        function recalcSummary() {
            const itemDivs = document.querySelectorAll('#quote-items .quote-item');
            let subtotal = 0;
            itemDivs.forEach(div => {
                const qtyInput = div.querySelector('input[name*="[qty]"]');
                const priceInput = div.querySelector('input[name*="[unit_price]"]');
                const qty = parseFloat(qtyInput?.value) || 0;
                const price = parseFloat(priceInput?.value) || 0;
                subtotal += qty * price;
            });
            const taxRate = parseFloat(document.querySelector('input[name="tax_rate"]').value) || 0;
            const discount = parseFloat(document.querySelector('input[name="discount_amount"]').value) || 0;
            const base = Math.max(subtotal - discount, 0);
            const tax = base * (taxRate / 100);
            const total = base + tax;
            const fmt = (n) => (currencySymbol + n.toFixed(2).replace('.', ','));
            const set = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val; };
            set('summary-subtotal', fmt(subtotal));
            set('summary-discount', '- ' + fmt(discount));
            set('summary-tax', fmt(tax));
            set('summary-total', fmt(total));
        }

        document.addEventListener('input', function(e) {
            if (
                (e.target.name && (e.target.name.includes('[qty]') || e.target.name.includes('[unit_price]'))) ||
                e.target.name === 'tax_rate' || e.target.name === 'discount_amount'
            ) {
                recalcSummary();
            }
        });

        document.addEventListener('DOMContentLoaded', recalcSummary);

        // Müşteri seçimi değiştiğinde bilgileri otomatik doldur
        document.getElementById('customer_id').addEventListener('change', function() {
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
        });
    </script>
</x-app-layout>
