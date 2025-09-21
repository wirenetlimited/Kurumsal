<x-app-layout>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="max-w-7xl mx-auto p-6 space-y-8">
      <!-- Başlık ve İstatistikler -->
      <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 rounded-3xl p-8 shadow-2xl">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative flex items-center justify-between">
          <div class="space-y-2">
            <h1 class="text-4xl font-bold text-white drop-shadow-lg">Müşteri Yönetimi</h1>
            <p class="text-blue-100 text-lg">Müşterilerinizi yönetin ve analiz edin</p>
          </div>
          <div class="text-right">
            <div class="text-4xl font-bold text-white drop-shadow-lg">{{ $totalCustomers }}</div>
            <div class="text-blue-100 text-lg">Toplam Müşteri</div>
          </div>
        </div>
        
        <!-- Dekoratif Elementler -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
      </div>

      <!-- İstatistik Kartları -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6 hover:shadow-2xl hover:scale-105 transition-all duration-300">
          <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 to-indigo-50/50 dark:from-blue-900/20 dark:to-indigo-800/20 rounded-2xl"></div>
          <div class="relative flex items-center justify-between">
            <div>
              <p class="text-sm font-semibold text-gray-600 dark:text-gray-400">Toplam Müşteri</p>
              <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalCustomers }}</p>
              <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                <span class="font-semibold">{{ $activeCustomers }}</span> aktif
              </p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
              </svg>
            </div>
          </div>
        </div>

        <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6 hover:shadow-2xl hover:scale-105 transition-all duration-300">
          <div class="absolute inset-0 bg-gradient-to-br from-green-50/50 to-emerald-50/50 dark:from-green-900/20 dark:to-emerald-800/20 rounded-2xl"></div>
          <div class="relative flex items-center justify-between">
            <div>
              <p class="text-sm font-semibold text-gray-600 dark:text-gray-400">Aktif Müşteri</p>
              <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $activeCustomers }}</p>
              <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                <span class="font-semibold">{{ $totalCustomers > 0 ? round(($activeCustomers / $totalCustomers) * 100, 1) : 0 }}%</span> oran
              </p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
          </div>
        </div>

        <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6 hover:shadow-2xl hover:scale-105 transition-all duration-300">
          <div class="absolute inset-0 bg-gradient-to-br from-purple-50/50 to-pink-50/50 dark:from-purple-900/20 dark:to-pink-800/20 rounded-2xl"></div>
          <div class="relative flex items-center justify-between">
            <div>
              <p class="text-sm font-semibold text-gray-600 dark:text-gray-400">Toplam Alacak</p>
              <p class="text-3xl font-bold text-gray-900 dark:text-white">₺{{ number_format($totalReceivable, 0, ',', '.') }}</p>
              <p class="text-sm text-green-600 dark:text-green-400 mt-1">Tahsil edilecek</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
              </svg>
            </div>
          </div>
        </div>

        <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6 hover:shadow-2xl hover:scale-105 transition-all duration-300">
          <div class="absolute inset-0 bg-gradient-to-br from-red-50/50 to-pink-50/50 dark:from-red-900/20 dark:to-pink-800/20 rounded-2xl"></div>
          <div class="relative flex items-center justify-between">
            <div>
              <p class="text-sm font-semibold text-gray-600 dark:text-gray-400">Toplam Borç</p>
              <p class="text-3xl font-bold text-gray-900 dark:text-white">₺{{ number_format($totalPayable, 0, ',', '.') }}</p>
              <p class="text-sm text-red-600 dark:text-red-400 mt-1">Ödenecek</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-red-400 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Filtreler ve Arama -->
      <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8">
        <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
        <div class="relative">
          <div class="flex items-center justify-between mb-8">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Müşteri Filtreleme</h3>
            <div class="flex items-center space-x-4">
              <a href="{{ route('customers.create') }}" class="group relative inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-2xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl"></div>
                <svg class="w-5 h-5 mr-2 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span class="relative z-10">Yeni Müşteri</span>
              </a>
              <a href="{{ route('admin.customer-balances') }}" class="group relative inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-2xl hover:from-purple-700 hover:to-pink-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-pink-600 rounded-2xl"></div>
                <svg class="w-5 h-5 mr-2 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                <span class="relative z-10">Müşteri Bakiyeleri</span>
              </a>
            </div>
          </div>

          @if (session('status'))
            <div class="mb-8 p-6 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/30 dark:to-emerald-900/30 border border-green-200 dark:border-green-800 rounded-2xl shadow-lg">
              <div class="flex">
                <svg class="w-6 h-6 text-green-500 dark:text-green-300 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-green-800 dark:text-green-200 font-semibold">{{ session('status') }}</span>
              </div>
            </div>
          @endif

          <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-6">
            <div class="md:col-span-2">
              <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Arama</label>
              <input type="text" name="q" value="{{ request('q') }}" placeholder="Müşteri ara (ad, e-posta, telefon)" class="w-full border border-gray-300 dark:border-gray-600 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 shadow-sm hover:shadow-md transition-all duration-200" />
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Durum</label>
              <select name="status" class="w-full border border-gray-300 dark:border-gray-600 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white shadow-sm hover:shadow-md transition-all duration-200">
                <option value="">Tüm Durumlar</option>
                <option value="active" {{ request('status')==='active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status')==='inactive' ? 'selected' : '' }}>Pasif</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Min Bakiye</label>
              <input type="number" step="0.01" name="balance_min" value="{{ request('balance_min') }}" placeholder="₺" class="w-full border border-gray-300 dark:border-gray-600 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 shadow-sm hover:shadow-md transition-all duration-200" />
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Maks Bakiye</label>
              <input type="number" step="0.01" name="balance_max" value="{{ request('balance_max') }}" placeholder="₺" class="w-full border border-gray-300 dark:border-gray-600 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 shadow-sm hover:shadow-md transition-all duration-200" />
            </div>
            <div class="flex items-end">
              <button type="submit" class="w-full bg-gradient-to-r from-gray-600 to-gray-700 dark:from-gray-500 dark:to-gray-600 text-white py-4 px-6 rounded-2xl hover:from-gray-700 hover:to-gray-800 dark:hover:from-gray-600 dark:hover:to-gray-700 transition-all duration-200 shadow-lg hover:shadow-xl font-semibold">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Filtrele
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Müşteri Listesi -->
      <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
        <div class="relative w-full">
          <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600">
              <tr>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Müşteri</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tür</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">İletişim</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Durum</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Hizmet</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Aylık Gelir</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider w-40">Bakiye</th>
                <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">İşlemler</th>
              </tr>
            </thead>
            <tbody class="bg-white/50 dark:bg-gray-800/50 divide-y divide-gray-200 dark:divide-gray-700">
              @forelse ($customers as $customer)
                <tr class="hover:bg-gray-100/50 dark:hover:bg-gray-700/50 transition-all duration-200">
                  <td class="px-6 py-4">
                    <div class="flex items-center">
                      <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-full flex items-center justify-center mr-3 shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                      </div>
                      <div>
                        <a class="text-sm font-semibold text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200" href="{{ route('customers.show', $customer) }}">
                          {{ $customer->name }}@if($customer->surname) {{ ' ' . $customer->surname }}@endif
                        </a>
                        @if($customer->tax_number)
                          <p class="text-xs text-gray-500 dark:text-gray-400">VKN: {{ $customer->tax_number }}</p>
                        @endif
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    @php
                      $rawType = (string) ($customer->customer_type ?? 'individual');
                      $norm = strtolower(trim($rawType));
                      if ($norm === 'kurumsal') { $norm = 'corporate'; }
                      if ($norm === 'bireysel') { $norm = 'individual'; }
                      $badge = $norm === 'corporate'
                        ? ['label' => 'Kurumsal', 'bg' => 'bg-gradient-to-r from-indigo-100 to-purple-100 dark:from-indigo-900/30 dark:to-purple-900/30', 'text' => 'text-indigo-800 dark:text-indigo-300', 'border' => 'border border-indigo-200 dark:border-indigo-700']
                        : ['label' => 'Bireysel', 'bg' => 'bg-gradient-to-r from-gray-100 to-slate-100 dark:from-gray-700 dark:to-slate-700', 'text' => 'text-gray-800 dark:text-gray-300', 'border' => 'border border-gray-200 dark:border-gray-600'];
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $badge['bg'] }} {{ $badge['text'] }} {{ $badge['border'] }}">
                      {{ $badge['label'] }}
                    </span>
                  </td>
                  <td class="px-6 py-4">
                    <div class="text-sm text-gray-900 dark:text-white">{{ $customer->email }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $customer->phone }}</div>
                  </td>
                  <td class="px-6 py-4">
                    <div class="flex items-center">
                      <div class="w-3 h-3 rounded-full {{ $customer->is_active ? 'bg-green-400' : 'bg-gray-400' }} mr-3 shadow-sm"></div>
                      <span class="text-sm font-semibold {{ $customer->is_active ? 'text-green-600 dark:text-green-400' : 'text-gray-600 dark:text-gray-400' }}">
                        {{ $customer->is_active ? 'Aktif' : 'Pasif' }}
                      </span>
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $customer->service_count }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">hizmet</div>
                  </td>
                  <td class="px-6 py-4">
                    <div class="text-sm font-semibold text-gray-900 dark:text-white">₺{{ number_format($customer->monthly_revenue, 0, ',', '.') }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">aylık</div>
                  </td>
                  <td class="px-6 py-4 w-40">
                    <!-- Bakiye Tutarı ve Renk - Geniş Alan ve Dikey Hizalama -->
                    <div class="flex items-center space-x-3 min-h-[2rem]">
                      <div class="w-3 h-3 rounded-full {{ $customer->current_balance > 0 ? 'bg-green-500' : ($customer->current_balance < 0 ? 'bg-red-500' : 'bg-gray-400') }} flex-shrink-0"></div>
                      <span class="text-base font-semibold {{ $customer->current_balance > 0 ? 'text-green-700 dark:text-green-400' : ($customer->current_balance < 0 ? 'text-red-700 dark:text-red-400' : 'text-gray-700 dark:text-gray-400') }}">
                        {{ $customer->current_balance > 0 ? '₺'.number_format($customer->current_balance,0,',','.') : ($customer->current_balance < 0 ? '-₺'.number_format(abs($customer->current_balance),0,',','.') : '₺0') }}
                      </span>
                    </div>
                  </td>
                  <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end space-x-3">
                      <a href="{{ route('customers.show', $customer) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 p-2 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-xl transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                      </a>
                      <a href="{{ route('customers.edit', $customer) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 p-2 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-xl transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                      </a>
                      @php
                        $isDemo = auth()->check() && strcasecmp(auth()->user()->email, config('app.demo_email')) === 0;
                      @endphp
                      <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline" onsubmit="if({{ $isDemo ? 'true' : 'false' }}){ alert('Demo modunda silme işlemi devre dışıdır.'); return false; } return confirm('Bu müşteriyi silmek istediğinizden emin misiniz?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" {{ $isDemo ? 'disabled' : '' }} class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 p-2 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-all duration-200 {{ $isDemo ? 'opacity-50 cursor-not-allowed' : '' }}" title="{{ $isDemo ? 'Demo modunda kapalı' : '' }}">
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                          </svg>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center">
                      <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                      </svg>
                      <p class="text-gray-500 dark:text-gray-400 text-xl font-semibold">Henüz müşteri bulunmuyor</p>
                      <p class="text-gray-400 dark:text-gray-500 text-base mt-2">İlk müşterinizi ekleyerek başlayın</p>
                      <a href="{{ route('customers.create') }}" class="mt-6 inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-500 dark:to-indigo-500 text-white rounded-2xl hover:from-blue-700 hover:to-indigo-700 dark:hover:from-blue-600 dark:hover:to-indigo-600 transition-all duration-300 shadow-lg hover:shadow-xl font-semibold">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        İlk Müşteriyi Ekle
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
      @if($customers->hasPages())
        <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6">
          <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
          <div class="relative">
            {{ $customers->links() }}
          </div>
        </div>
      @endif
    </div>
  </div>
</x-app-layout>
