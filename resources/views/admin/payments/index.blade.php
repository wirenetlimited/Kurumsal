<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <div class="max-w-7xl mx-auto p-6 space-y-8">
            <!-- Başlık ve Butonlar -->
            <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 rounded-3xl p-8 shadow-2xl">
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="relative flex items-center justify-between">
                    <div class="space-y-2">
                        <h1 class="text-4xl font-bold text-white drop-shadow-lg">Admin - Ödemeler</h1>
                        <p class="text-blue-100 text-lg">Tüm ödeme kayıtları (Admin)</p>
                    </div>
                    
                    <a href="{{ route('admin.payments.create') }}" class="group relative inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm text-white rounded-2xl hover:bg-white/30 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-white/10 rounded-2xl"></div>
                        <svg class="w-5 h-5 mr-3 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span class="relative z-10 font-semibold">Yeni Ödeme</span>
                    </a>
                </div>
                
                <!-- Dekoratif Elementler -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
            </div>

            <!-- İstatistik Kartları -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6 hover:shadow-2xl hover:scale-105 transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-400/10 to-emerald-500/10 rounded-3xl"></div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Toplam Ödeme</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">₺{{ number_format($payments->sum('amount'), 2, ',', '.') }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6 hover:shadow-2xl hover:scale-105 transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-400/10 to-indigo-500/10 rounded-3xl"></div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Ödeme Sayısı</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $payments->total() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2zm0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6 hover:shadow-2xl hover:scale-105 transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-400/10 to-pink-500/10 rounded-3xl"></div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Bu Ay</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">₺{{ number_format($payments->where('paid_at', '>=', now()->startOfMonth())->sum('amount'), 2, ',', '.') }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6 hover:shadow-2xl hover:scale-105 transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-br from-orange-400/10 to-red-500/10 rounded-3xl"></div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Bu Hafta</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">₺{{ number_format($payments->where('paid_at', '>=', now()->startOfWeek())->sum('amount'), 2, ',', '.') }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-red-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtreler -->
            <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/50 to-purple-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
                <div class="relative">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                        </svg>
                        Filtreler
                    </h3>
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Müşteri</label>
                            <select name="customer_id" class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-500">
                                <option value="">Tümü</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->customer_type === 'corporate' ? $customer->name : $customer->name . ' ' . ($customer->surname ?? '') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Ödeme Yöntemi</label>
                            <select name="payment_method" class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-500">
                                <option value="">Tümü</option>
                                <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Nakit</option>
                                <option value="bank" {{ request('payment_method') === 'bank' ? 'selected' : '' }}>Banka</option>
                                <option value="card" {{ request('payment_method') === 'card' ? 'selected' : '' }}>Kart</option>
                                <option value="transfer" {{ request('payment_method') === 'transfer' ? 'selected' : '' }}>Havale</option>
                                <option value="other" {{ request('payment_method') === 'other' ? 'selected' : '' }}>Diğer</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Başlangıç Tarihi</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}" 
                                   class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Bitiş Tarihi</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" 
                                   class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-500">
                        </div>
                        
                        <div class="flex items-end">
                            <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-2xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Filtrele
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Ödemeler Tablosu -->
            <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30"></div>
                <div class="relative overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600">
                            <tr>
                                <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Müşteri</th>
                                <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Fatura</th>
                                <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tutar</th>
                                <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Yöntem</th>
                                <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tarih</th>
                                <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Notlar</th>
                                <th class="px-8 py-4 text-right text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white/50 dark:bg-gray-800/50 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($payments as $payment)
                                <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700/80 transition-all duration-200 group">
                                    <td class="px-8 py-6">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $payment->customer->name ?? 'N/A' }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $payment->customer->email ?? '' }}</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        @if($payment->invoice)
                                            <a href="{{ route('invoices.show', $payment->invoice) }}" class="inline-flex items-center px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 rounded-full text-sm font-medium hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                {{ $payment->invoice->invoice_number ?? '#' . $payment->invoice->id }}
                                            </a>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500 text-sm">-</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-lg font-bold text-gray-900 dark:text-white">₺{{ number_format($payment->amount, 2, ',', '.') }}</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-blue-100 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 text-blue-800 dark:text-blue-200 border border-blue-200 dark:border-blue-700">
                                            {{ $payment->methodLabel }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $payment->paid_at?->format('d.m.Y') }}</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        @if($payment->notes)
                                            <div class="text-sm text-gray-900 dark:text-white max-w-xs truncate group-hover:max-w-none transition-all duration-300" title="{{ $payment->notes }}">
                                                {{ $payment->notes }}
                                            </div>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500 text-sm">-</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <form action="{{ route('admin.payments.destroy', $payment) }}" method="POST" class="inline" onsubmit="return confirm('Bu ödemeyi silmek istediğinizden emin misiniz?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-all duration-200 group-hover:scale-110">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-8 py-16 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-20 h-20 bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-600 dark:to-gray-700 rounded-full flex items-center justify-center mb-6">
                                                <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                            </div>
                                            <p class="text-gray-500 dark:text-gray-400 text-xl font-semibold mb-2">Henüz ödeme bulunmuyor</p>
                                            <p class="text-gray-400 dark:text-gray-500 text-sm mb-6">İlk ödemenizi oluşturarak başlayın</p>
                                            <a href="{{ route('admin.payments.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-2xl hover:from-blue-700 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                                İlk Ödemeyi Oluştur
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Sayfalama -->
            @if($payments->hasPages())
                <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6">
                    <div class="absolute inset-0 bg-gradient-to-br from-gray-50/50 to-gray-100/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
                    <div class="relative">
                        {{ $payments->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
