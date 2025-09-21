<x-app-layout>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-pink-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="max-w-7xl mx-auto p-6 space-y-8">
      <!-- Başlık ve İstatistikler -->
      <div class="relative overflow-hidden bg-gradient-to-r from-purple-600 via-pink-600 to-rose-600 rounded-3xl p-8 shadow-2xl">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative flex items-center justify-between">
          <div class="space-y-2">
            <h1 class="text-4xl font-bold text-white drop-shadow-lg">Muhasebe Yönetimi</h1>
            <p class="text-purple-100 text-lg">Faturalarınızı yönetin ve gelir takibi yapın</p>
          </div>
          <div class="text-right">
            <div class="text-5xl font-bold text-white drop-shadow-lg">{{ $count }}</div>
            <div class="text-purple-100 text-lg">Toplam Fatura</div>
          </div>
        </div>
        
        <!-- Dekoratif Elementler -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
      </div>

      <!-- İstatistik Kartları -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6 hover:shadow-2xl hover:scale-105 transition-all duration-300">
          <div class="absolute inset-0 bg-gradient-to-br from-purple-400/10 to-pink-500/10 rounded-3xl"></div>
          <div class="relative flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Toplam Fatura</p>
              <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $count }}</p>
              <p class="text-sm text-purple-600 dark:text-purple-400 mt-1">Kayıtlı fatura</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
            </div>
          </div>
        </div>

        <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6 hover:shadow-2xl hover:scale-105 transition-all duration-300">
          <div class="absolute inset-0 bg-gradient-to-br from-orange-400/10 to-red-500/10 rounded-3xl"></div>
          <div class="relative flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Fatura Tutarı</p>
              <p class="text-3xl font-bold text-gray-900 dark:text-white">₺{{ number_format($sumTotal, 0, ',', '.') }}</p>
              <p class="text-sm text-orange-600 dark:text-orange-400 mt-1">Toplam değer</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-red-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
              </svg>
            </div>
          </div>
        </div>

        <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6 hover:shadow-2xl hover:scale-105 transition-all duration-300">
          <div class="absolute inset-0 bg-gradient-to-br from-green-400/10 to-emerald-500/10 rounded-3xl"></div>
          <div class="relative flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Toplam Tahsilat</p>
              <p class="text-3xl font-bold text-gray-900 dark:text-white">₺{{ number_format($sumPaid, 0, ',', '.') }}</p>
              <p class="text-sm text-green-600 dark:text-green-400 mt-1">Ödenen tutar</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
          </div>
        </div>

        <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6 hover:shadow-2xl hover:scale-105 transition-all duration-300">
          <div class="absolute inset-0 bg-gradient-to-br from-red-400/10 to-pink-500/10 rounded-3xl"></div>
          <div class="relative flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Bekleyen</p>
              <p class="text-3xl font-bold text-gray-900 dark:text-white">₺{{ number_format($sumTotal - $sumPaid, 0, ',', '.') }}</p>
              <p class="text-sm text-red-600 dark:text-red-400 mt-1">Tahsil edilecek</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-red-400 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Başlık ve Yeni Ekleme -->
      <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8">
        <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
        <div class="relative">
          <div class="flex items-center justify-between mb-8">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
              <svg class="w-6 h-6 mr-3 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
              Fatura Listesi
            </h3>
            <div class="flex items-center space-x-3">
              <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-2xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
                Tahsilatlar
              </a>
              
              @can('admin')
              <a href="{{ route('admin.reconciliation.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-2xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Muhasebe Mutabakatı
              </a>
              @endcan
              
              <a href="{{ route('invoices.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-2xl hover:from-purple-700 hover:to-pink-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Yeni Fatura
              </a>
            </div>
          </div>

          @if (session('status'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-2xl">
              <div class="flex items-center">
                <svg class="w-5 h-5 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-green-800 dark:text-green-200 font-medium">{{ session('status') }}</span>
              </div>
            </div>
          @endif

          <!-- Fatura Listesi -->
          <div class="w-full">
            <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600">
                <tr>
                  <th class="px-4 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Fatura</th>
                  <th class="px-4 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Müşteri</th>
                  <th class="px-4 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tarihler</th>
                  <th class="px-4 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Durum</th>
                  <th class="px-4 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tutar</th>
                  <th class="px-4 py-4 text-right text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">İşlemler</th>
                </tr>
              </thead>
              <tbody class="bg-white/50 dark:bg-gray-800/50 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($invoices as $inv)
                  <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700/80 transition-all duration-200 group" data-invoice-id="{{ $inv->id }}">
                    <td class="px-4 py-4">
                      <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 rounded-lg flex items-center justify-center mr-2 shadow-sm flex-shrink-0">
                          <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                          </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                          <div class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $inv->invoice_number ?? '#' . $inv->id }}</div>
                          <div class="text-xs text-gray-500 dark:text-gray-400">{{ $inv->issue_date?->format('d.m.Y') }}</div>
                        </div>
                      </div>
                    </td>
                    <td class="px-4 py-4">
                      <div class="min-w-0">
                        <div class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                          @if($inv->customer)
                            {{ $inv->customer->name }}@if($inv->customer->surname) {{ ' ' . $inv->customer->surname }}@endif
                          @else
                            -
                          @endif
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $inv->customer->email ?? '' }}</div>
                      </div>
                    </td>
                    <td class="px-4 py-4">
                      <div class="min-w-0">
                        <div class="text-sm text-gray-900 dark:text-white truncate">Vade: {{ $inv->due_date?->format('d.m.Y') }}</div>
                        @if($inv->due_date)
                          @php
                            $daysRemaining = (int)now()->diffInDays($inv->due_date, false);
                            $colorClass = $daysRemaining < 0 ? 'text-red-600 dark:text-red-400' : ($daysRemaining <= 7 ? 'text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400');
                          @endphp
                          <div class="text-xs {{ $colorClass }} truncate">
                            @if($daysRemaining < 0)
                              {{ abs($daysRemaining) }} gün gecikmiş
                            @else
                              {{ $daysRemaining }} gün kaldı
                            @endif
                          </div>
                        @endif
                      </div>
                    </td>
                    <td class="px-4 py-4">
                      @php
                        $statusConfig = [
                          'draft' => ['bg' => 'bg-gray-100 dark:bg-gray-700', 'text' => 'text-gray-800 dark:text-gray-200', 'icon' => 'text-gray-400', 'tr' => 'Taslak'],
                          'sent' => ['bg' => 'bg-blue-100 dark:bg-blue-900/30', 'text' => 'text-blue-800 dark:text-blue-200', 'icon' => 'text-blue-400', 'tr' => 'Gönderildi'],
                          'paid' => ['bg' => 'bg-green-100 dark:bg-green-900/30', 'text' => 'text-green-800 dark:text-green-200', 'icon' => 'text-green-400', 'tr' => 'Tahsil Edildi'],
                          'overdue' => ['bg' => 'bg-red-100 dark:bg-red-900/30', 'text' => 'text-red-800 dark:text-red-200', 'icon' => 'text-red-400', 'tr' => 'Gecikmiş'],
                          'cancelled' => ['bg' => 'bg-gray-100 dark:bg-gray-700', 'text' => 'text-gray-800 dark:text-gray-200', 'icon' => 'text-gray-400', 'tr' => 'İptal Edildi'],
                        ];
                        $status = $statusConfig[$inv->status->value] ?? $statusConfig['draft'];
                      @endphp
                      <span class="status-badge inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $status['bg'] }} {{ $status['text'] }} border border-gray-200 dark:border-gray-600">
                        <svg class="w-1.5 h-1.5 mr-1.5 {{ $status['icon'] }}" fill="currentColor" viewBox="0 0 8 8">
                          <circle cx="4" cy="4" r="3" />
                        </svg>
                        {{ $status['tr'] }}
                      </span>
                    </td>
                    <td class="px-4 py-4">
                      <div class="min-w-0">
                        <div class="text-sm font-semibold text-gray-900 dark:text-white">₺{{ number_format($inv->total, 2, ',', '.') }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $inv->currency }}</div>
                      </div>
                    </td>
                    <td class="px-4 py-4 text-right">
                      <div class="flex items-center justify-end space-x-1">
                        <a href="{{ route('invoices.show', $inv) }}" class="p-1 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded transition-all duration-200 group-hover:scale-105" title="Görüntüle">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                          </svg>
                        </a>
                        @if($inv->status !== 'paid')
                          <button onclick="openQuickPaymentModal({{ $inv->id }})" 
                                 class="p-1 text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 hover:bg-green-50 dark:hover:bg-green-900/20 rounded transition-all duration-200 group-hover:scale-105 payment-btn" 
                                 title="Tahsil Et">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                          </button>
                        @endif
                        <a href="{{ route('invoices.edit', $inv) }}" class="p-1 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded transition-all duration-200 group-hover:scale-105" title="Düzenle">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                          </svg>
                        </a>
                        @php($isDemo = auth()->check() && strcasecmp(auth()->user()->email, config('app.demo_email')) === 0)
                        <form action="{{ route('invoices.destroy', $inv) }}" method="POST" class="inline" onsubmit="if({{ $isDemo ? 'true' : 'false' }}){ alert('Demo modunda silme işlemi devre dışıdır.'); return false; } return confirm('Bu faturayı silmek istediğinizden emin misiniz?')">
                          @csrf
                          @method('DELETE')
                          <button type="submit" @if($isDemo) disabled @endif class="p-1 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition-all duration-200 group-hover:scale-105 {{ $isDemo ? 'opacity-50 cursor-not-allowed' : '' }}" title="{{ $isDemo ? 'Demo modunda kapalı' : 'Sil' }}">
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
                    <td colspan="6" class="px-4 py-16 text-center">
                      <div class="flex flex-col items-center">
                        <div class="w-20 h-20 bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-600 dark:to-gray-700 rounded-full flex items-center justify-center mb-6">
                          <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                          </svg>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 text-xl font-semibold mb-2">Henüz fatura bulunmuyor</p>
                        <p class="text-gray-400 dark:text-gray-500 text-sm mb-6">İlk faturanızı oluşturarak başlayın</p>
                        <a href="{{ route('invoices.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-2xl hover:from-purple-700 hover:to-pink-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                          </svg>
                          İlk Faturayı Oluştur
                        </a>
                      </div>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Sayfalama -->
      @if($invoices->hasPages())
        <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6">
          <div class="absolute inset-0 bg-gradient-to-br from-gray-50/50 to-gray-100/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
          <div class="relative">
            {{ $invoices->links() }}
          </div>
        </div>
      @endif

      <!-- Quick Payment Modal -->
      @include('payments._quick_modal')
    </div>
  </div>
</x-app-layout>


