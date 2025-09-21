<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <div class="max-w-7xl mx-auto p-6 space-y-8">
            <!-- Header -->
            <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 rounded-3xl p-8 shadow-2xl">
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="relative flex items-center justify-between">
                    <div class="space-y-2">
                        <h1 class="text-4xl font-bold text-white drop-shadow-lg">{{ $customer->name }}@if($customer->surname) {{ ' ' . $customer->surname }}@endif</h1>
                        <p class="text-blue-100 text-lg">Müşteri detaylarını görüntüleyin</p>
                    </div>
                    <div class="text-right">
                        <div class="text-4xl font-bold">👤</div>
                        <div class="text-blue-100 text-lg">Müşteri Profili</div>
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
                <a href="{{ route('customers.index') }}" 
                   class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-2xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 font-semibold shadow-sm hover:shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Listeye Dön
                </a>
                <a href="{{ route('customers.edit', $customer) }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Düzenle
                </a>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Customer Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
                        <div class="relative">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Temel Bilgiler</h2>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-6">
                                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Müşteri Türü</span>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border
                                            @if($customer->customer_type === 'corporate') bg-gradient-to-r from-blue-100 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 text-blue-800 dark:text-blue-400 border-blue-200 dark:border-blue-700
                                            @else bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 text-green-800 dark:text-green-400 border-green-200 dark:border-green-700 @endif">
                                            @if($customer->customer_type === 'corporate') 🏢 Kurumsal
                                            @else 👤 Bireysel
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">E-posta</span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">
                                            @if($customer->email)
                                                <a href="mailto:{{ $customer->email }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors">
                                                    {{ $customer->email }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Telefon</span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">
                                            @if($customer->phone)
                                                <a href="tel:{{ $customer->phone }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors">
                                                    {{ $customer->phone }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="space-y-6">
                                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Vergi No/TCKN</span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $customer->tax_number ?? '-' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Durum</span>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border
                                            @if($customer->is_active) bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 text-green-800 dark:text-green-400 border-green-200 dark:border-green-700
                                            @else bg-gradient-to-r from-red-100 to-pink-100 dark:from-red-900/30 dark:to-pink-900/30 text-red-800 dark:text-red-400 border-red-200 dark:border-red-700 @endif">
                                            @if($customer->is_active) ✅ Aktif
                                            @else ❌ Pasif
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Kayıt Tarihi</span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $customer->created_at?->format('d.m.Y') ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Information -->
                    @if($customer->address || $customer->city || $customer->district)
                    <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
                        <div class="relative">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Adres Bilgileri</h2>
                            </div>
                            
                            <div class="space-y-6">
                                @if($customer->address)
                                <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                    <span class="text-sm font-semibold text-gray-600 dark:text-gray-400 block mb-2">Adres</span>
                                    <span class="text-sm text-gray-900 dark:text-white">{{ $customer->address }}</span>
                                </div>
                                @endif
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    @if($customer->city)
                                    <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400 block mb-2">Şehir</span>
                                        <span class="text-sm text-gray-900 dark:text-white">{{ $customer->city }}</span>
                                    </div>
                                    @endif
                                    
                                    @if($customer->district)
                                    <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400 block mb-2">İlçe</span>
                                        <span class="text-sm text-gray-900 dark:text-white">{{ $customer->district }}</span>
                                    </div>
                                    @endif
                                    
                                    @if($customer->zip)
                                    <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400 block mb-2">Posta Kodu</span>
                                        <span class="text-sm text-gray-900 dark:text-white">{{ $customer->zip }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Notes -->
                    @if($customer->notes)
                    <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
                        <div class="relative">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-indigo-400 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Notlar</h2>
                            </div>
                            <p class="text-gray-700 dark:text-gray-300 text-lg">{{ $customer->notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Customer Summary -->
                    <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
                        <div class="relative">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Müşteri Özeti</h3>
                            </div>
                            
                            <div class="space-y-6">
                                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                    <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Toplam Hizmet</span>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $customer->services()->count() }}</span>
                                </div>
                                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                    <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Toplam Fatura</span>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $customer->invoices()->count() }}</span>
                                </div>
                                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                    <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Toplam Tahsilat</span>
                                    <span class="text-sm font-bold text-green-600 dark:text-green-400">₺{{ number_format($customer->payments()->sum('amount'), 2, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/30 dark:to-indigo-900/30 rounded-2xl border border-blue-200 dark:border-blue-700">
                                    <span class="text-sm font-semibold text-blue-700 dark:text-blue-300">Aylık Gelir (MRR)</span>
                                    <span class="text-sm font-bold text-blue-900 dark:text-blue-100">₺{{ number_format($customerMonthlyRevenue, 2, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/30 dark:to-orange-900/30 rounded-2xl border border-yellow-200 dark:border-yellow-700">
                                    <span class="text-sm font-semibold text-yellow-700 dark:text-yellow-300">Cari Bakiye</span>
                                    <span class="text-sm font-bold {{ $currentBalance >= 0 ? 'text-green-700 dark:text-green-400' : 'text-red-700 dark:text-red-400' }}">{{ $currentBalance >= 0 ? '₺'.number_format($currentBalance,2,',','.') : '-₺'.number_format(abs($currentBalance),2,',','.') }}</span>
                                </div>
                                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/30 dark:to-pink-900/30 rounded-2xl border border-purple-200 dark:border-purple-700">
                                    <span class="text-sm font-semibold text-purple-700 dark:text-purple-300">Doğrulanmış Bakiye</span>
                                    <span class="text-sm font-bold {{ $customer->verified_balance >= 0 ? 'text-green-700 dark:text-green-400' : 'text-red-700 dark:text-red-400' }}">{{ $customer->verified_balance >= 0 ? '₺'.number_format($customer->verified_balance,2,',','.') : '-₺'.number_format(abs($customer->verified_balance),2,',','.') }}</span>
                                </div>
                                @if(!$customer->balance_status['is_consistent'])
                                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-red-50 to-pink-50 dark:from-red-900/30 dark:to-pink-900/30 rounded-2xl border border-red-200 dark:border-red-700">
                                    <span class="text-sm font-semibold text-red-700 dark:text-red-300">⚠️ Tutarsızlık</span>
                                    <span class="text-sm font-bold text-red-700 dark:text-red-400">₺{{ number_format(abs($customer->balance_status['inconsistency_amount']),2,',','.') }}</span>
                                </div>
                                @endif
                                <div class="text-xs text-gray-500 dark:text-gray-400 text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-2xl">
                                    * MRR: Aylık Tekrarlanan Gelir (Monthly Recurring Revenue)<br>
                                    Sadece taksitli ödeme hizmetleri MRR'ye dahil edilir<br>
                                    Peşin ödeme hizmetleri MRR hesaplamasına dahil edilmez
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
                        <div class="relative">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Hızlı İşlemler</h3>
                            </div>
                            
                            <div class="space-y-6">
                                <a href="{{ route('services.create') }}?customer_id={{ $customer->id }}" 
                                   class="w-full flex items-center justify-center px-6 py-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-2xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 text-sm font-semibold shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Hizmet Ekle
                                </a>
                                
                                <a href="{{ route('invoices.create') }}?customer_id={{ $customer->id }}" 
                                   class="w-full flex items-center justify-center px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 text-sm font-semibold shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Fatura Oluştur
                                </a>
                                
                                <a href="{{ route('quotes.create') }}?customer_id={{ $customer->id }}" 
                                   class="w-full flex items-center justify-center px-6 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-2xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 text-sm font-semibold shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Teklif Oluştur
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Cari Hareket Dökümü -->
<div class="max-w-7xl mx-auto p-6">
  <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8">
    <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
    <div class="relative">
      <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18M5 6h14M5 18h14" />
            </svg>
          </div>
          <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Cari Hareket Dökümü</h2>
        </div>
        <div class="flex items-center gap-3">
          <a href="{{ route('customers.statement.csv', $customer) }}" class="px-4 py-2 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-2xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200 font-semibold shadow-sm hover:shadow-md">CSV</a>
          <a href="{{ route('customers.statement.pdf', $customer) }}" class="px-4 py-2 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-2xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200 font-semibold shadow-sm hover:shadow-md">PDF</a>
        </div>
      </div>
      <div class="overflow-x-auto">
        @can('admin')
        <form action="{{ route('admin.customers.ledger.store', $customer) }}" method="POST" class="mb-6 grid grid-cols-1 md:grid-cols-5 gap-4 items-end bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
          @csrf
          <div>
            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-2 font-semibold">Tarih</label>
            <input type="date" name="entry_date" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-2xl dark:bg-gray-700 dark:text-white shadow-sm hover:shadow-md transition-all duration-200" value="{{ now()->toDateString() }}">
          </div>
          <div class="md:col-span-2">
            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-2 font-semibold">Açıklama</label>
            <input type="text" name="notes" placeholder="Açıklama" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-2xl dark:bg-gray-700 dark:text-white shadow-sm hover:shadow-md transition-all duration-200">
          </div>
          <div>
            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-2 font-semibold">Borç (₺)</label>
            <input type="number" step="0.01" min="0" name="debit" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-2xl dark:bg-gray-700 dark:text-white shadow-sm hover:shadow-md transition-all duration-200">
          </div>
          <div>
            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-2 font-semibold">Alacak (₺)</label>
            <input type="number" step="0.01" min="0" name="credit" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-2xl dark:bg-gray-700 dark:text-white shadow-sm hover:shadow-md transition-all duration-200">
          </div>
          <div class="md:col-span-5">
            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl hover:from-blue-700 hover:to-indigo-700 text-sm font-semibold shadow-lg hover:shadow-xl transition-all duration-200">
              Kaydet
            </button>
            @error('debit')
              <span class="text-xs text-red-600 ml-3">{{ $message }}</span>
            @enderror
          </div>
        </form>
        @endcan
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600">
            <tr>
              <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tarih</th>
              <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Açıklama</th>
              <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Borç</th>
              <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Alacak</th>
              <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Bakiye</th>
              @can('admin')
              <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">İşlem</th>
              @endcan
            </tr>
          </thead>
          <tbody class="bg-white/50 dark:bg-gray-800/50 divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($statement as $e)
            <tr class="hover:bg-gray-100/50 dark:hover:bg-gray-700/50 transition-all duration-200">
              <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($e->entry_date)->format('d.m.Y') }}</td>
              <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $e->notes ?? class_basename($e->related_type) .' #'. $e->related_id }}</td>
              <td class="px-6 py-4 text-sm text-right text-red-600 dark:text-red-400">{{ $e->debit > 0 ? '₺'.number_format($e->debit,2,',','.') : '' }}</td>
              <td class="px-6 py-4 text-sm text-right text-green-600 dark:text-green-400">{{ $e->credit > 0 ? '₺'.number_format($e->credit,2,',','.') : '' }}</td>
              <td class="px-6 py-4 text-sm text-right font-bold {{ $e->running_balance >= 0 ? 'text-green-700 dark:text-green-400' : 'text-red-700 dark:text-red-400' }}">{{ $e->running_balance >= 0 ? '₺'.number_format($e->running_balance,2,',','.') : '-₺'.number_format(abs($e->running_balance),2,',','.') }}</td>
              @can('admin')
              <td class="px-6 py-4 text-sm text-right">
                @if(!$e->related_type && !$e->related_id)
                <form action="{{ route('admin.ledger.destroy', $e->id) }}" method="POST" onsubmit="return confirm('Bu cari hareketi silmek istiyor musunuz?');" class="inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="text-red-600 hover:text-red-800 font-semibold transition-colors">Sil</button>
                </form>
                @endif
              </td>
              @endcan
            </tr>
            @empty
            <tr>
              <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400 font-semibold">Hareket bulunamadı</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>


