<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 dark:from-gray-900 dark:to-slate-800">
        <div class="max-w-7xl mx-auto p-6 space-y-8">
            <!-- Header -->
            <div class="relative overflow-hidden bg-gradient-to-r from-yellow-600 via-orange-600 to-red-600 rounded-2xl p-8 text-white shadow-2xl">
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between">
                        <div class="space-y-2">
                            <h1 class="text-3xl font-bold">Teklif Detayı</h1>
                            <p class="text-yellow-100 text-lg">Teklif bilgilerini görüntüleyin</p>
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

            <!-- Status Message -->
            @if (session('status'))
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-xl p-4">
                    <div class="flex items-center">
                        <div class="w-5 h-5 bg-green-100 dark:bg-green-800 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-3 h-3 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="text-green-800 dark:text-green-200 font-medium">{{ session('status') }}</span>
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex justify-end gap-4">
                <a href="{{ route('quotes.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Listeye Dön
                </a>
                <a href="{{ route('quotes.edit', $quote) }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Düzenle
                </a>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Quote Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Customer Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Müşteri Bilgileri</h2>
                                <p class="text-gray-600 dark:text-gray-400">Müşteri detayları ve iletişim bilgileri</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Müşteri Adı</span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                        @if($quote->customer)
                                            {{ $quote->customer->name }}@if($quote->customer->surname) {{ ' ' . $quote->customer->surname }}@endif
                                        @else
                                            {{ $quote->customer_name ?? '-' }}
                                        @endif
                                    </span>
                                </div>
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">E-posta</span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                        @if($quote->customer_email)
                                            <a href="mailto:{{ $quote->customer_email }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                {{ $quote->customer_email }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Telefon</span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                        @if($quote->customer_phone)
                                            <a href="tel:{{ $quote->customer_phone }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                {{ $quote->customer_phone }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Durum</span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        @if($quote->status === 'draft') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300
                                        @elseif($quote->status === 'sent') bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300
                                        @elseif($quote->status === 'accepted') bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300
                                        @elseif($quote->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                        @endif">
                                        @if($quote->status === 'draft') 📝 Taslak
                                        @elseif($quote->status === 'sent') 📤 Gönderildi
                                        @elseif($quote->status === 'accepted') ✅ Kabul Edildi
                                        @elseif($quote->status === 'rejected') ❌ Reddedildi
                                        @else ⏰ Süresi Doldu
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quote Details -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Teklif Detayları</h2>
                                <p class="text-gray-600 dark:text-gray-400">Teklif bilgileri ve tarihler</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Başlık</span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $quote->title ?? '-' }}</span>
                                </div>
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Teklif Tarihi</span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $quote->quote_date?->format('d.m.Y') ?? '-' }}</span>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Geçerlilik Tarihi</span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $quote->valid_until?->format('d.m.Y') ?? '-' }}</span>
                                </div>
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">KDV Oranı</span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">%{{ $quote->tax_rate ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quote Items -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Ürün/Hizmetler</h2>
                                <p class="text-gray-600 dark:text-gray-400">Teklif kalemleri ve detayları</p>
                            </div>
                        </div>
                        
                        @if($quote->items && $quote->items->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Açıklama</th>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Miktar</th>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Birim Fiyat</th>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tutar</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                                        @foreach($quote->items as $item)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $item->description }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $item->qty }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                ₺{{ number_format($item->unit_price, 2, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">
                                                ₺{{ number_format($item->line_total, 2, ',', '.') }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                                <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-lg font-medium">Teklif kalemi bulunamadı</p>
                                <p class="text-sm">Henüz ürün veya hizmet eklenmemiş</p>
                            </div>
                        @endif
                    </div>

                    <!-- Notes and Terms -->
                    @if($quote->notes || $quote->terms)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Notlar ve Şartlar</h2>
                                <p class="text-gray-600 dark:text-gray-400">Teklif detayları ve koşullar</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if($quote->notes)
                            <div class="space-y-3">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Teklif Notları</h3>
                                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $quote->notes }}</p>
                                </div>
                            </div>
                            @endif
                            
                            @if($quote->terms)
                            <div class="space-y-3">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Şartlar ve Koşullar</h3>
                                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $quote->terms }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Financial Summary -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Finansal Özet</h3>
                                <p class="text-gray-600 dark:text-gray-400">Toplam tutarlar</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Ara Toplam</span>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">₺{{ number_format($quote->subtotal ?? 0, 2, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">İndirim</span>
                                <span class="text-sm font-semibold text-red-600 dark:text-red-400">₺{{ number_format($quote->discount_amount ?? 0, 2, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">KDV ({{ $quote->tax_rate ?? 0 }}%)</span>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">₺{{ number_format($quote->tax_total ?? 0, 2, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between p-6 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl text-white border border-green-400">
                                <span class="text-sm font-medium text-green-100">Genel Toplam</span>
                                <span class="text-xl font-bold text-white">₺{{ number_format($quote->total ?? 0, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Hızlı İşlemler</h3>
                                <p class="text-gray-600 dark:text-gray-400">Teklif işlemleri</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <a href="{{ route('quotes.pdf', $quote) }}" 
                               class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-red-600 to-pink-600 text-white rounded-xl hover:from-red-700 hover:to-pink-700 transition-all duration-200 text-sm font-medium shadow-lg hover:shadow-xl">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                PDF İndir
                            </a>
                            
                            <form action="{{ route('quotes.send', $quote) }}" method="POST" class="inline w-full">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 text-sm font-medium shadow-lg hover:shadow-xl">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    E-posta Gönder
                                </button>
                            </form>
                            
                            <form action="{{ route('quotes.to_invoice', $quote) }}" method="POST" class="inline w-full">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 text-sm font-medium shadow-lg hover:shadow-xl">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Faturaya Dönüştür
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

