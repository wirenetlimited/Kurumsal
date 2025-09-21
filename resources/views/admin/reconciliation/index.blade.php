<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <div class="max-w-7xl mx-auto p-6 space-y-8">
            <!-- Başlık ve Özet -->
            <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8">
                <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-8">
                        <div class="space-y-2">
                            <h1 class="text-4xl font-bold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-8 h-8 mr-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Muhasebe Mutabakatı
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400 text-lg">Fatura ve ledger hareketleri tutarlılık kontrolü</p>
                        </div>
                        
                        <div class="flex items-center space-x-3">
                            <form action="{{ route('admin.reconciliation.sync-statuses') }}" method="POST" class="inline" onsubmit="return confirm('Tüm faturaların durumları güncellenecek ve veriler yenilenecek. Devam etmek istiyor musunuz?')">
                                @csrf
                                <button type="submit" class="group relative inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-2xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                                    <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-white/10 rounded-2xl"></div>
                                    <svg class="w-5 h-5 mr-2 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    <span class="relative z-10">Durumları Güncelle</span>
                                </button>
                            </form>
                            
                            <div class="relative">
                                <button onclick="toggleExportMenu()" class="group relative inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-2xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                                    <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-white/10 rounded-2xl"></div>
                                    <svg class="w-5 h-5 mr-2 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span class="relative z-10">Export</span>
                                </button>
                                
                                <div id="exportMenu" class="hidden absolute right-0 mt-2 w-48 bg-white/90 dark:bg-gray-700/90 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 dark:border-gray-600/50 z-10">
                                    <a href="{{ route('admin.reconciliation.export', ['format' => 'csv']) }}" class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-t-2xl transition-colors">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        CSV İndir
                                    </a>
                                    <a href="{{ route('admin.reconciliation.export', ['format' => 'pdf']) }}" class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                        PDF İndir
                                    </a>
                                    <a href="{{ route('admin.reconciliation.export', ['format' => 'json']) }}" class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-b-2xl transition-colors">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                                        </svg>
                                        JSON İndir
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Özet Kartları -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="group relative bg-red-50/80 dark:bg-red-900/30 border border-red-200 dark:border-red-700 rounded-2xl p-6 hover:shadow-lg hover:scale-105 transition-all duration-300">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-red-400 to-red-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-semibold text-red-800 dark:text-red-200">Hatalar</p>
                                    <p class="text-3xl font-bold text-red-900 dark:text-red-100">{{ $data['summary']['invoice_errors'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="group relative bg-yellow-50/80 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-700 rounded-2xl p-6 hover:shadow-lg hover:scale-105 transition-all duration-300">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-semibold text-yellow-800 dark:text-yellow-200">Uyarılar</p>
                                    <p class="text-3xl font-bold text-yellow-900 dark:text-yellow-100">{{ $data['summary']['invoice_warnings'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="group relative bg-green-50/80 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-2xl p-6 hover:shadow-lg hover:scale-105 transition-all duration-300">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-semibold text-green-800 dark:text-green-200">Tutarlı</p>
                                    <p class="text-3xl font-bold text-green-900 dark:text-green-100">{{ $data['summary']['invoice_ok'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="group relative bg-blue-50/80 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-2xl p-6 hover:shadow-lg hover:scale-105 transition-all duration-300">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-semibold text-blue-800 dark:text-blue-200">Müşteri</p>
                                    <p class="text-3xl font-bold text-blue-900 dark:text-blue-100">{{ $data['summary']['balanced_customers'] }}/{{ $data['summary']['total_customers'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtreler -->
            <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8">
                <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
                <div class="relative">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"></path>
                        </svg>
                        Filtreler
                    </h3>
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Fatura Durumu</label>
                            <select name="status" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-2xl dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-400">
                                <option value="all" {{ $filters['status'] === 'all' ? 'selected' : '' }}>Tümü</option>
                                <option value="draft" {{ $filters['status'] === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="sent" {{ $filters['status'] === 'sent' ? 'selected' : '' }}>Sent</option>
                                <option value="overdue" {{ $filters['status'] === 'overdue' ? 'selected' : '' }}>Overdue</option>
                                <option value="paid" {{ $filters['status'] === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="cancelled" {{ $filters['status'] === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Kategori</label>
                            <select name="category" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-2xl dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-400">
                                <option value="all" {{ $filters['category'] === 'all' ? 'selected' : '' }}>Tümü</option>
                                <option value="draft_with_debit" {{ $filters['category'] === 'draft_with_debit' ? 'selected' : '' }}>Draft with Debit</option>
                                <option value="missing_debit" {{ $filters['category'] === 'missing_debit' ? 'selected' : '' }}>Missing Debit</option>
                                <option value="missing_credit" {{ $filters['category'] === 'missing_credit' ? 'selected' : '' }}>Missing Credit</option>
                                <option value="overpaid" {{ $filters['category'] === 'overpaid' ? 'selected' : '' }}>Overpaid</option>
                                <option value="cancelled_not_zero" {{ $filters['category'] === 'cancelled_not_zero' ? 'selected' : '' }}>Cancelled Not Zero</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Müşteri</label>
                            <select name="customer" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-2xl dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-400">
                                <option value="all" {{ $filters['customer'] === 'all' ? 'selected' : '' }}>Tümü</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ $filters['customer'] == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="flex items-end">
                            <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-2xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Filtrele
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Hatalar Tablosu -->
            @if($data['invoice_check']['errors']->count() > 0)
            <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8">
                <div class="absolute inset-0 bg-gradient-to-br from-red-50/30 to-red-100/30 dark:from-red-900/20 dark:to-red-800/20 rounded-3xl"></div>
                <div class="relative">
                    <h3 class="text-xl font-bold text-red-600 dark:text-red-400 mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        Hatalar ({{ $data['invoice_check']['errors']->count() }})
                    </h3>
                    <div class="w-full">
                        <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/30 dark:to-red-800/30">
                                <tr>
                                    <th class="px-4 py-4 text-left text-xs font-bold text-red-800 dark:text-red-200 uppercase tracking-wider">Fatura</th>
                                    <th class="px-4 py-4 text-left text-xs font-bold text-red-800 dark:text-red-200 uppercase tracking-wider">Müşteri</th>
                                    <th class="px-4 py-4 text-left text-xs font-bold text-red-800 dark:text-red-200 uppercase tracking-wider">Durum</th>
                                    <th class="px-4 py-4 text-left text-xs font-bold text-red-800 dark:text-red-200 uppercase tracking-wider">Beklenen</th>
                                    <th class="px-4 py-4 text-left text-xs font-bold text-red-800 dark:text-red-200 uppercase tracking-wider">Gerçek</th>
                                    <th class="px-4 py-4 text-left text-xs font-bold text-red-800 dark:text-red-200 uppercase tracking-wider">Fark</th>
                                    <th class="px-4 py-4 text-left text-xs font-bold text-red-800 dark:text-red-200 uppercase tracking-wider">Açıklama</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white/50 dark:bg-gray-800/50 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($data['invoice_check']['errors'] as $error)
                                <tr class="hover:bg-red-50/50 dark:hover:bg-red-900/20 transition-all duration-200 group">
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <a href="{{ route('invoices.show', $error['invoice_id']) }}" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-semibold transition-colors">
                                            {{ $error['invoice_number'] }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <a href="{{ route('customers.show', $error['customer_id']) }}" class="text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                            {{ $error['customer_name'] }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 border border-red-200 dark:border-red-700">
                                            {{ ucfirst($error['status']) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white font-medium">
                                        ₺{{ number_format($error['expected'], 2, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white font-medium">
                                        ₺{{ number_format($error['actual'], 2, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-semibold text-red-600 dark:text-red-400">
                                        ₺{{ number_format($error['difference'], 2, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900 dark:text-white">
                                        {{ $error['description'] }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Uyarılar Tablosu -->
            @if($data['invoice_check']['warnings']->count() > 0)
            <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8">
                <div class="absolute inset-0 bg-gradient-to-br from-yellow-50/30 to-orange-100/30 dark:from-yellow-900/20 dark:to-orange-800/20 rounded-3xl"></div>
                <div class="relative">
                    <h3 class="text-xl font-bold text-yellow-600 dark:text-yellow-400 mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        Uyarılar ({{ $data['invoice_check']['warnings']->count() }})
                    </h3>
                    <div class="w-full">
                        <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gradient-to-r from-yellow-50 to-orange-100 dark:from-yellow-900/30 dark:to-orange-800/30">
                                <tr>
                                    <th class="px-4 py-4 text-left text-xs font-bold text-yellow-800 dark:text-yellow-200 uppercase tracking-wider">Fatura</th>
                                    <th class="px-4 py-4 text-left text-xs font-bold text-yellow-800 dark:text-yellow-200 uppercase tracking-wider">Müşteri</th>
                                    <th class="px-4 py-4 text-left text-xs font-bold text-yellow-800 dark:text-yellow-200 uppercase tracking-wider">Durum</th>
                                    <th class="px-4 py-4 text-left text-xs font-bold text-yellow-800 dark:text-yellow-200 uppercase tracking-wider">Beklenen</th>
                                    <th class="px-4 py-4 text-left text-xs font-bold text-yellow-800 dark:text-yellow-200 uppercase tracking-wider">Gerçek</th>
                                    <th class="px-4 py-4 text-left text-xs font-bold text-yellow-800 dark:text-yellow-200 uppercase tracking-wider">Fark</th>
                                    <th class="px-4 py-4 text-left text-xs font-bold text-yellow-800 dark:text-yellow-200 uppercase tracking-wider">Açıklama</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white/50 dark:bg-gray-800/50 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($data['invoice_check']['warnings'] as $warning)
                                <tr class="hover:bg-yellow-50/50 dark:hover:bg-yellow-900/20 transition-all duration-200 group">
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <a href="{{ route('invoices.show', $warning['invoice_id']) }}" class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-800 dark:hover:text-yellow-300 font-semibold transition-colors">
                                            {{ $warning['invoice_number'] }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <a href="{{ route('customers.show', $warning['customer_id']) }}" class="text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                            {{ $warning['customer_name'] }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 border border-yellow-200 dark:border-yellow-700">
                                            {{ ucfirst($warning['status']->value ?? $warning['status']) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white font-medium">
                                        {{ $warning['expected'] }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white font-medium">
                                        ₺{{ number_format($warning['actual'], 2, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-semibold text-yellow-600 dark:text-yellow-400">
                                        {{ $warning['difference'] }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900 dark:text-white">
                                        {{ $warning['description'] }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Tutarlı Faturalar Tablosu -->
            @if($data['invoice_check']['ok']->count() > 0)
            <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8">
                <div class="absolute inset-0 bg-gradient-to-br from-green-50/30 to-emerald-100/30 dark:from-green-900/20 dark:to-emerald-800/20 rounded-3xl"></div>
                <div class="relative">
                    <h3 class="text-xl font-bold text-green-600 dark:text-green-400 mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Tutarlı Faturalar ({{ $data['invoice_check']['ok']->count() }})
                    </h3>
                    <div class="w-full">
                        <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gradient-to-r from-green-50 to-emerald-100 dark:from-green-900/30 dark:to-emerald-800/30">
                                <tr>
                                    <th class="px-4 py-4 text-left text-xs font-bold text-green-800 dark:text-green-200 uppercase tracking-wider">Fatura</th>
                                    <th class="px-4 py-4 text-left text-xs font-bold text-green-800 dark:text-green-200 uppercase tracking-wider">Müşteri</th>
                                    <th class="px-4 py-4 text-left text-xs font-bold text-green-800 dark:text-green-200 uppercase tracking-wider">Durum</th>
                                    <th class="px-4 py-4 text-left text-xs font-bold text-green-800 dark:text-green-200 uppercase tracking-wider">Beklenen</th>
                                    <th class="px-4 py-4 text-left text-xs font-bold text-green-800 dark:text-green-200 uppercase tracking-wider">Gerçek</th>
                                    <th class="px-4 py-4 text-left text-xs font-bold text-green-800 dark:text-green-200 uppercase tracking-wider">Fark</th>
                                    <th class="px-4 py-4 text-left text-xs font-bold text-green-800 dark:text-green-200 uppercase tracking-wider">Açıklama</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white/50 dark:bg-gray-800/50 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($data['invoice_check']['ok'] as $ok)
                                <tr class="hover:bg-green-50/50 dark:hover:bg-green-900/20 transition-all duration-200 group">
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <a href="{{ route('invoices.show', $ok['invoice_id']) }}" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 font-semibold transition-colors">
                                            {{ $ok['invoice_number'] }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <a href="{{ route('customers.show', $ok['customer_id']) }}" class="text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                            {{ $ok['customer_name'] }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 border border-green-200 dark:border-green-700">
                                            {{ ucfirst($ok['status']->value ?? $ok['status']) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white font-medium">
                                        ₺{{ number_format($ok['expected'], 2, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white font-medium">
                                        ₺{{ number_format($ok['actual'], 2, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-semibold text-green-600 dark:text-green-400">
                                        ₺{{ number_format($ok['difference'], 2, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900 dark:text-white">
                                        {{ $ok['description'] }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Müşteri Bakiye Özeti -->
            <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-50/30 to-indigo-100/30 dark:from-blue-900/20 dark:to-indigo-800/20 rounded-3xl"></div>
                <div class="relative">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Müşteri Bakiye Özeti
                    </h3>
                    <div class="w-full">
                        <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600">
                                <tr>
                                    <th class="px-4 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Müşteri</th>
                                    <th class="px-4 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Toplam Fatura</th>
                                    <th class="px-4 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Ödenmemiş</th>
                                    <th class="px-4 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Ödenen</th>
                                    <th class="px-4 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Net Ledger</th>
                                    <th class="px-4 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Beklenen Bakiye</th>
                                    <th class="px-4 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Fark</th>
                                    <th class="px-4 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Durum</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white/50 dark:bg-gray-800/50 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($data['customer_balances'] as $balance)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-all duration-200 group">
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <a href="{{ route('customers.show', $balance['customer_id']) }}" class="text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 font-semibold transition-colors">
                                            {{ $balance['customer_name'] }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white font-medium">
                                        ₺{{ number_format($balance['total_invoice_amount'], 2, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white font-medium">
                                        ₺{{ number_format($balance['outstanding_amount'], 2, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white font-medium">
                                        ₺{{ number_format($balance['paid_amount'], 2, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white font-medium">
                                        ₺{{ number_format($balance['net_ledger'], 2, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white font-medium">
                                        ₺{{ number_format($balance['expected_balance'], 2, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-semibold {{ abs($balance['difference']) < 0.01 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        ₺{{ number_format($balance['difference'], 2, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        @if($balance['status'] === 'balanced')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 border border-green-200 dark:border-green-700">
                                                Dengeli
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 border border-red-200 dark:border-red-700">
                                                Dengesiz
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleExportMenu() {
            const menu = document.getElementById('exportMenu');
            menu.classList.toggle('hidden');
        }

        // Export menüsünü dışarı tıklandığında kapat
        document.addEventListener('click', function(event) {
            const exportMenu = document.getElementById('exportMenu');
            const exportButton = event.target.closest('button');
            
            if (!exportButton || !exportButton.onclick) {
                exportMenu.classList.add('hidden');
            }
        });
    </script>
</x-app-layout>


