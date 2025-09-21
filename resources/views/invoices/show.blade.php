<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-pink-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <div class="max-w-7xl mx-auto p-6 space-y-8">
            <!-- Header -->
            <div class="relative overflow-hidden bg-gradient-to-r from-purple-600 via-pink-600 to-rose-600 rounded-3xl p-8 shadow-2xl">
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="relative flex items-center justify-between">
                    <div class="space-y-2">
                        <h1 class="text-4xl font-bold text-white drop-shadow-lg">Fatura Detayı</h1>
                        <p class="text-purple-100 text-lg">Fatura bilgilerini görüntüleyin</p>
                    </div>
                    <div class="text-right">
                        <div class="text-4xl font-bold">📄</div>
                        <div class="text-purple-100 text-lg">Fatura #{{ $invoice->id }}</div>
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
                <a href="{{ route('invoices.index') }}" 
                   class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-2xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 font-semibold shadow-sm hover:shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Listeye Dön
                </a>
                
                @can('admin')
                <button onclick="openQuickPaymentModal({{ $invoice->id }})" 
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-2xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                    Tahsilat Ekle
                </button>
                @endcan
                
                <a href="{{ route('invoices.edit', $invoice) }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Düzenle
                </a>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Invoice Details -->
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
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Fatura Bilgileri</h2>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-6">
                                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Müşteri</span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">
                                            @if($invoice->customer)
                                                {{ $invoice->customer->name }}@if($invoice->customer->surname) {{ ' ' . $invoice->customer->surname }}@endif
                                            @else
                                                -
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Fatura Tarihi</span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $invoice->issue_date?->format('d.m.Y') ?? '-' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Vade Tarihi</span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $invoice->due_date?->format('d.m.Y') ?? '-' }}</span>
                                    </div>
                                </div>
                                
                                <div class="space-y-6">
                                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Durum</span>
                                        <span class="status-badge inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                            @if($invoice->status->value === 'paid') bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 dark:from-green-900/30 dark:to-emerald-900/30 dark:text-green-400 border border-green-200 dark:border-green-700
                                            @elseif($invoice->status->value === 'sent') bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 dark:from-blue-900/30 dark:to-indigo-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-700
                                            @elseif($invoice->status->value === 'overdue') bg-gradient-to-r from-red-100 to-pink-100 text-red-800 dark:from-red-900/30 dark:to-pink-900/30 dark:text-red-400 border border-red-200 dark:border-red-700
                                            @elseif($invoice->status->value === 'draft') bg-gradient-to-r from-gray-100 to-slate-100 text-gray-800 dark:from-gray-700 dark:to-slate-700 dark:text-gray-400 border border-gray-200 dark:border-gray-600
                                            @else bg-gradient-to-r from-yellow-100 to-orange-100 text-yellow-800 dark:from-yellow-900/30 dark:to-orange-900/30 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-700 @endif">
                                            @if($invoice->status->value === 'paid') ✅ Ödendi
                                            @elseif($invoice->status->value === 'sent') 📤 Gönderildi
                                            @elseif($invoice->status->value === 'overdue') ⏰ Gecikmiş
                                            @elseif($invoice->status->value === 'draft') 📝 Taslak
                                            @else {{ ucfirst($invoice->status->value ?? $invoice->status) }}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Para Birimi</span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $invoice->currency ?? 'TRY' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Fatura No</span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $invoice->invoice_number ?? '#' . $invoice->id }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Items -->
                    <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
                        <div class="relative">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Fatura Kalemleri</h2>
                            </div>
                            
                            @if($invoice->items && $invoice->items->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="w-full table-fixed divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600">
                                            <tr>
                                                <th class="w-2/5 px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Açıklama</th>
                                                <th class="w-1/12 px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Miktar</th>
                                                <th class="w-1/6 px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Birim Fiyat</th>
                                                <th class="w-1/12 px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">KDV %</th>
                                                <th class="w-1/6 px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tutar</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white/50 dark:bg-gray-800/50 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($invoice->items as $item)
                                            <tr class="hover:bg-gray-100/50 dark:hover:bg-gray-700/50 transition-all duration-200">
                                                <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">
                                                    <div class="break-words">
                                                        {{ $item->description }}
                                                        @if($item->service)
                                                            <span class="text-xs text-gray-500 dark:text-gray-400 block mt-1">{{ ucfirst($item->service->service_type) }}</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $item->qty }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                                    {{ $invoice->currency ?? '₺' }}{{ number_format($item->unit_price, 2, ',', '.') }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">%{{ $item->tax_rate }}</td>
                                                <td class="px-6 py-4 text-sm font-bold text-gray-900 dark:text-white">
                                                    {{ $invoice->currency ?? '₺' }}{{ number_format($item->line_total, 2, ',', '.') }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                                    <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-lg font-semibold">Fatura kalemi bulunamadı</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Financial Summary -->
                    <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
                        <div class="relative">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Finansal Özet</h3>
                            </div>
                            
                            <div class="space-y-6">
                                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                    <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Ara Toplam</span>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $invoice->currency ?? '₺' }}{{ number_format($invoice->subtotal ?? 0, 2, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                    <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">KDV Toplam</span>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $invoice->currency ?? '₺' }}{{ number_format($invoice->tax_total ?? 0, 2, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/30 dark:to-indigo-900/30 rounded-2xl border border-blue-200 dark:border-blue-700">
                                    <span class="text-sm font-semibold text-blue-700 dark:text-blue-300">Genel Toplam</span>
                                    <span class="text-xl font-bold text-blue-900 dark:text-blue-100">{{ $invoice->currency ?? '₺' }}{{ number_format($invoice->total ?? 0, 2, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Status -->
                    <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
                        <div class="relative">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Ödeme Durumu</h3>
                            </div>
                            
                            <div class="space-y-6">
                                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                    <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Ödenen Tutar</span>
                                    <span class="text-sm font-bold text-green-600 dark:text-green-400">{{ $invoice->currency ?? '₺' }}{{ number_format($invoice->paidAmount() ?? 0, 2, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                    <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Kalan Tutar</span>
                                    <span class="text-sm font-bold text-red-600 dark:text-red-400 remaining-amount">
                                        {{ $invoice->currency ?? '₺' }}{{ number_format($invoice->remaining_amount, 2, ',', '.') }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-2xl">
                                    <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Durum</span>
                                    <span class="status-badge text-sm font-bold {{ $invoice->isPaid() ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ $invoice->isPaid() ? '✅ Tamamen Ödendi' : '❌ Kısmen Ödendi' }}
                                    </span>
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
                                <form method="POST" action="{{ route('invoices.send-email', $invoice) }}" class="inline w-full">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center justify-center px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 text-sm font-semibold shadow-lg hover:shadow-xl">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        E-posta Gönder
                                    </button>
                                </form>
                                
                                <a href="{{ route('invoices.show', $invoice) }}?pdf=1" target="_blank"
                                   class="w-full flex items-center justify-center px-6 py-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-2xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 text-sm font-semibold shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    PDF Görüntüle
                                </a>
                                
                                <a href="#" onclick="printInvoice()" 
                                   class="w-full flex items-center justify-center px-6 py-4 bg-gradient-to-r from-gray-600 to-slate-600 text-white rounded-2xl hover:from-gray-700 hover:to-slate-700 transition-all duration-200 text-sm font-semibold shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                    </svg>
                                    Yazdır
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Payment Modal -->
    @include('payments._quick_modal')

    <script>
        function printInvoice() {
            // PDF görünümünü yeni pencerede aç
            const pdfUrl = '{{ route("invoices.show", $invoice) }}?pdf=1';
            const printWindow = window.open(pdfUrl, '_blank', 'width=800,height=600');
            
            // PDF yüklendiğinde yazdır
            printWindow.onload = function() {
                setTimeout(function() {
                    printWindow.print();
                }, 1000); // 1 saniye bekle, sayfa tam yüklensin
            };
            
            // Eğer popup engellendiyse, direkt yazdır
            if (!printWindow || printWindow.closed || typeof printWindow.closed == 'undefined') {
                alert('Popup engellendi. PDF sayfasını manuel olarak açıp yazdırın.');
                window.open(pdfUrl, '_blank');
            }
        }
    </script>
</x-app-layout>


