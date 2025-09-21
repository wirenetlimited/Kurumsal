<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <div class="max-w-7xl mx-auto p-6 space-y-8">
            <!-- Başlık -->
            <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 rounded-3xl p-8 shadow-2xl">
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="relative flex items-center justify-between">
                    <div class="space-y-2">
                        <h1 class="text-4xl font-bold text-white drop-shadow-lg">Müşteri Cari Bakiyeleri Raporu</h1>
                        <p class="text-blue-100 text-lg">Müşteri bazlı borç, alacak ve cari bakiye durumu</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('customers.index') }}" class="group relative inline-flex items-center px-6 py-3 bg-white/20 text-white font-semibold rounded-2xl hover:bg-white/30 transition-all duration-300 backdrop-blur-sm">
                            <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-white/10 rounded-2xl"></div>
                            <svg class="w-5 h-5 mr-2 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span class="relative z-10">Müşteriler</span>
                        </a>
                    </div>
                </div>
                
                <!-- Dekoratif Elementler -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
            </div>

            <!-- Özet Kartları -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Toplam Müşteri -->
                <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6 hover:shadow-2xl hover:scale-105 transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 to-indigo-50/50 dark:from-blue-900/20 dark:to-indigo-800/20 rounded-2xl"></div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-400">Toplam Müşteri</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $summary['total_customers'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Toplam Borç -->
                <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6 hover:shadow-2xl hover:scale-105 transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-br from-red-50/50 to-pink-50/50 dark:from-red-900/20 dark:to-pink-800/20 rounded-2xl"></div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-400">Toplam Borç</p>
                            <p class="text-3xl font-bold text-red-600 dark:text-red-400">₺{{ number_format($summary['total_debt'], 2, ',', '.') }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-red-400 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Toplam Alacak -->
                <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6 hover:shadow-2xl hover:scale-105 transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-50/50 to-emerald-50/50 dark:from-green-900/20 dark:to-emerald-800/20 rounded-2xl"></div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-400">Toplam Alacak</p>
                            <p class="text-3xl font-bold text-green-600 dark:text-green-400">₺{{ number_format($summary['total_payments'], 2, ',', '.') }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Net Bakiye -->
                <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6 hover:shadow-2xl hover:scale-105 transition-all duration-300">
                    <div class="absolute inset-0 {{ $summary['net_balance'] > 0 ? 'bg-gradient-to-br from-red-50/50 to-pink-50/50 dark:from-red-900/20 dark:to-pink-800/20' : ($summary['net_balance'] < 0 ? 'bg-gradient-to-br from-blue-50/50 to-indigo-50/50 dark:from-blue-900/20 dark:to-indigo-800/20' : 'bg-gradient-to-br from-gray-50/50 to-slate-50/50 dark:from-gray-900/20 dark:to-slate-800/20') }} rounded-2xl"></div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-400">Cari Bakiye</p>
                            <p class="text-3xl font-bold {{ $summary['net_balance'] > 0 ? 'text-red-600 dark:text-red-400' : ($summary['net_balance'] < 0 ? 'text-blue-600 dark:text-blue-400' : 'text-gray-900 dark:text-white') }}">
                                ₺{{ number_format($summary['net_balance'], 2, ',', '.') }}
                            </p>
                        </div>
                        <div class="w-12 h-12 {{ $summary['net_balance'] > 0 ? 'bg-gradient-to-br from-red-400 to-pink-600' : ($summary['net_balance'] < 0 ? 'bg-gradient-to-br from-blue-400 to-indigo-600' : 'bg-gradient-to-br from-gray-400 to-slate-600') }} rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Durum Filtreleri -->
            <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8">
                <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Durum Filtreleri</h3>
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.customer-balances', ['status' => 'all']) }}" 
                               class="px-6 py-3 rounded-2xl text-sm font-semibold transition-all duration-200 {{ $statusFilter === 'all' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg' : 'bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 text-gray-700 dark:text-gray-300 hover:from-gray-200 hover:to-gray-300 dark:hover:from-gray-600 dark:hover:to-gray-500' }}">
                                Tümü ({{ $summary['total_customers'] }})
                            </a>
                            <a href="{{ route('admin.customer-balances', ['status' => 'borclu']) }}" 
                               class="px-6 py-3 rounded-2xl text-sm font-semibold transition-all duration-200 {{ $statusFilter === 'borclu' ? 'bg-gradient-to-r from-red-600 to-pink-600 text-white shadow-lg' : 'bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 text-gray-700 dark:text-gray-300 hover:from-gray-200 hover:to-gray-300 dark:hover:from-gray-600 dark:hover:to-gray-500' }}">
                                Borçlu ({{ $statusCounts['borclu'] }})
                            </a>
                            <a href="{{ route('admin.customer-balances', ['status' => 'dengede']) }}" 
                               class="px-6 py-3 rounded-2xl text-sm font-semibold transition-all duration-200 {{ $statusFilter === 'dengede' ? 'bg-gradient-to-r from-green-600 to-emerald-600 text-white shadow-lg' : 'bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 text-gray-700 dark:text-gray-300 hover:from-gray-200 hover:to-gray-300 dark:hover:from-gray-600 dark:hover:to-gray-500' }}">
                                Dengede ({{ $statusCounts['dengede'] }})
                            </a>
                            <a href="{{ route('admin.customer-balances', ['status' => 'alacakli']) }}" 
                               class="px-6 py-3 rounded-2xl text-sm font-semibold transition-all duration-200 {{ $statusFilter === 'alacakli' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg' : 'bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 text-gray-700 dark:text-gray-300 hover:from-gray-200 hover:to-gray-300 dark:hover:from-gray-600 dark:hover:to-gray-500' }}">
                                Alacaklı ({{ $statusCounts['alacakli'] }})
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Müşteri Bakiyeleri Tablosu -->
            <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
                <div class="relative w-full">
                    <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Müşteri
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Toplam Borç
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Toplam Alacak
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Cari Bakiye
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Durum
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    İşlemler
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white/50 dark:bg-gray-800/50 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($customerBalances as $customer)
                            <tr class="hover:bg-gray-100/50 dark:hover:bg-gray-700/50 transition-all duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center shadow-lg">
                                                <span class="text-sm font-medium text-white">
                                                    {{ strtoupper(substr($customer['name'], 0, 2)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $customer['name'] }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $customer['email'] }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        ₺{{ number_format($customer['total_debt'], 2, ',', '.') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Fatura tutarları</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        ₺{{ number_format($customer['total_payments'], 2, ',', '.') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Ödeme tutarları</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium {{ $customer['net_balance'] > 0 ? 'text-red-600 dark:text-red-400' : ($customer['net_balance'] < 0 ? 'text-blue-600 dark:text-blue-400' : 'text-gray-900 dark:text-white') }}">
                                        ₺{{ number_format($customer['net_balance'], 2, ',', '.') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        @if($customer['net_balance'] > 0)
                                            <span class="text-red-600 dark:text-red-400">Borçlu</span>
                                        @elseif($customer['net_balance'] < 0)
                                            <span class="text-blue-600 dark:text-blue-400">Alacaklı</span>
                                        @else
                                            <span class="text-gray-600 dark:text-gray-400">Dengede</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $customer['status'] === 'borclu' ? 'bg-gradient-to-r from-red-100 to-pink-100 text-red-800 dark:from-red-900/30 dark:to-pink-900/30 dark:text-red-400 border border-red-200 dark:border-red-700' : '' }}
                                        {{ $customer['status'] === 'dengede' ? 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 dark:from-green-900/30 dark:to-emerald-900/30 dark:text-green-400 border border-green-200 dark:border-green-700' : '' }}
                                        {{ $customer['status'] === 'alacakli' ? 'bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 dark:from-blue-900/30 dark:to-indigo-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-700' : '' }}">
                                        {{ $customer['status_text'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-3">
                                        <a href="{{ route('customers.show', $customer['id']) }}" 
                                           class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 p-2 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-xl transition-all duration-200">
                                            Görüntüle
                                        </a>
                                        <a href="{{ route('customers.statement.pdf', $customer['id']) }}" 
                                           class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300 p-2 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-xl transition-all duration-200">
                                            PDF
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-lg font-medium">Müşteri bakiye verisi bulunamadı</p>
                                        <p class="text-sm">Seçilen filtreye uygun müşteri bulunmuyor</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        
                        <!-- Toplam Satırı -->
                        @if($customerBalances->count() > 0)
                        <tfoot class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600">
                            <tr>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">
                                    TOPLAM ({{ $customerBalances->count() }} müşteri)
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-red-600 dark:text-red-400">
                                    ₺{{ number_format($summary['total_debt'], 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-green-600 dark:text-green-400">
                                    ₺{{ number_format($summary['total_payments'], 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold {{ $summary['net_balance'] > 0 ? 'text-red-600 dark:text-red-400' : ($summary['net_balance'] < 0 ? 'text-blue-600 dark:text-blue-400' : 'text-gray-900 dark:text-white') }}">
                                    ₺{{ number_format($summary['net_balance'], 2, ',', '.') }}
                                </td>
                                <td colspan="2" class="px-6 py-4"></td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
