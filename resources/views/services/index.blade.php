<x-app-layout>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-green-50 to-emerald-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="max-w-7xl mx-auto p-6 space-y-8">
      <!-- Başlık ve İstatistikler -->
      <div class="relative overflow-hidden bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 rounded-3xl p-8 shadow-2xl">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative flex items-center justify-between">
          <div class="space-y-2">
            <h1 class="text-4xl font-bold text-white drop-shadow-lg">Hizmet Yönetimi</h1>
            <p class="text-green-100 text-lg">Domain, hosting ve SSL hizmetlerinizi yönetin</p>
          </div>
          <div class="text-right">
            <div class="w-20 h-20 bg-white/20 rounded-3xl flex items-center justify-center backdrop-blur-sm">
              <div class="text-4xl font-bold">{{ $metrics['total'] }}</div>
            </div>
            <div class="text-green-100 text-lg mt-2">Toplam Hizmet</div>
          </div>
        </div>
        
        <!-- Dekoratif Elementler -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
      </div>

      <!-- İstatistik Kartları -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6 hover:shadow-2xl hover:scale-105 transition-all duration-300">
          <div class="absolute inset-0 bg-gradient-to-br from-green-50/50 to-emerald-50/50 dark:from-green-900/20 dark:to-emerald-800/20 rounded-2xl"></div>
          <div class="relative flex items-center justify-between">
            <div>
              <p class="text-sm font-semibold text-gray-600 dark:text-gray-400">Toplam Hizmet</p>
              <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $metrics['total'] }}</p>
              <p class="text-sm text-green-600 dark:text-green-400 mt-1">Kayıtlı hizmet</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
              </svg>
            </div>
          </div>
        </div>

        <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6 hover:shadow-2xl hover:scale-105 transition-all duration-300">
          <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 to-indigo-50/50 dark:from-blue-900/20 dark:to-indigo-800/20 rounded-2xl"></div>
          <div class="relative flex items-center justify-between">
            <div>
              <p class="text-sm font-semibold text-gray-600 dark:text-gray-400">Domain Sayısı</p>
              <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $metrics['domains'] }}</p>
              <p class="text-sm text-blue-600 dark:text-blue-400 mt-1">Kayıtlı domain</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
              </svg>
            </div>
          </div>
        </div>

        <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6 hover:shadow-2xl hover:scale-105 transition-all duration-300">
          <div class="absolute inset-0 bg-gradient-to-br from-purple-50/50 to-violet-50/50 dark:from-purple-900/20 dark:to-violet-800/20 rounded-2xl"></div>
          <div class="relative flex items-center justify-between">
            <div>
              <p class="text-sm font-semibold text-gray-600 dark:text-gray-400">Hosting Sayısı</p>
              <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $metrics['hostings'] }}</p>
              <p class="text-sm text-purple-600 dark:text-purple-400 mt-1">Aktif hosting</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-violet-600 rounded-2xl flex items-center justify-center shadow-lg">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
              </svg>
            </div>
          </div>
        </div>

        <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6 hover:shadow-2xl hover:scale-105 transition-all duration-300">
          <div class="absolute inset-0 bg-gradient-to-br from-orange-50/50 to-amber-50/50 dark:from-orange-900/20 dark:to-amber-800/20 rounded-2xl"></div>
          <div class="relative flex items-center justify-between">
            <div>
              <p class="text-sm font-semibold text-gray-600 dark:text-gray-400">Aylık Gelir</p>
              <p class="text-3xl font-bold text-gray-900 dark:text-white">₺{{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
              <p class="text-sm text-orange-600 dark:text-orange-400 mt-1">Tekrarlayan gelir</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-amber-600 rounded-2xl flex items-center justify-center shadow-lg">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
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
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Hizmet Filtreleme</h3>
            <a href="{{ route('services.create') }}" class="group relative inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-2xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
              <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-white/10 rounded-2xl"></div>
              <svg class="w-5 h-5 mr-2 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
              <span class="relative z-10">Yeni Hizmet</span>
            </a>
          </div>

          <!-- Aylık Gelir Açıklaması -->
          <div class="mb-8 p-6 bg-gradient-to-r from-blue-50 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-800/30 border border-blue-200/50 dark:border-blue-800/50 rounded-2xl">
            <div class="flex items-center gap-3 mb-3">
              <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
              <span class="text-sm font-semibold text-blue-800 dark:text-blue-200">Aylık Gelir (MRR) Açıklaması</span>
            </div>
            <p class="text-sm text-blue-700 dark:text-blue-300">
              <strong>MRR (Monthly Recurring Revenue):</strong> Aylık Tekrarlanan Gelir. 
              Hizmetlerin dönemlerine göre aylık ortalama gelirini gösterir. 
              Örneğin: 100 TL yıllık hizmet = 100÷12 = 8.33 TL aylık gelir.
            </p>
          </div>

          @if (session('status'))
            <div class="mb-8 p-6 bg-gradient-to-r from-green-50 to-emerald-100 dark:from-green-900/30 dark:to-emerald-800/30 border border-green-200/50 dark:border-green-800/50 rounded-2xl">
              <div class="flex items-center">
                <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-emerald-600 rounded-xl flex items-center justify-center mr-3">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                </div>
                <span class="text-green-800 dark:text-green-200 font-semibold">{{ session('status') }}</span>
              </div>
            </div>
          @endif

          <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
              <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Vade (Gün)</label>
              <select name="due_in" class="w-full border border-gray-300 dark:border-gray-600 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white hover:border-gray-400 transition-all duration-200">
                <option value="">Tümü</option>
                <option value="7" {{ request('due_in') == '7' ? 'selected' : '' }}>7 gün içinde</option>
                <option value="15" {{ request('due_in') == '15' ? 'selected' : '' }}>15 gün içinde</option>
                <option value="30" {{ request('due_in') == '30' ? 'selected' : '' }}>30 gün içinde</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Sıralama</label>
              <select name="sort" class="w-full border border-gray-300 dark:border-gray-600 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white hover:border-gray-400 transition-all duration-200">
                <option value="">En Yeni</option>
                <option value="end_asc" {{ request('sort') == 'end_asc' ? 'selected' : '' }}>Vade (Yakın)</option>
                <option value="end_desc" {{ request('sort') == 'end_desc' ? 'selected' : '' }}>Vade (Uzak)</option>
              </select>
            </div>
            <div class="flex items-end">
              <button type="submit" class="w-full bg-gradient-to-r from-gray-600 to-gray-700 dark:from-gray-500 dark:to-gray-600 text-white py-3 px-4 rounded-2xl hover:from-gray-700 hover:to-gray-800 dark:hover:from-gray-600 dark:hover:to-gray-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Filtrele
              </button>
            </div>
            <div class="flex items-end">
              <a href="{{ route('services.index') }}" class="w-full bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-600 dark:to-gray-700 text-gray-700 dark:text-gray-300 py-3 px-4 rounded-2xl hover:from-gray-200 hover:to-gray-300 dark:hover:from-gray-500 dark:hover:to-gray-600 transition-all duration-200 text-center shadow-lg hover:shadow-xl">
                Temizle
              </a>
            </div>
          </form>
        </div>
      </div>

      <!-- Hizmet Listesi -->
      <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
        <div class="relative w-full">
          <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600">
              <tr>
                <th class="px-4 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Hizmet</th>
                <th class="px-3 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Kod</th>
                <th class="px-3 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tür</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Müşteri</th>
                <th class="px-3 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Sağlayıcı</th>
                <th class="px-3 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Dönem</th>
                <th class="px-3 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Ödeme</th>
                <th class="px-3 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Durum</th>
                <th class="px-3 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Vade</th>
                <th class="px-3 py-4 text-right text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">İşlemler</th>
              </tr>
            </thead>
            <tbody class="bg-white/50 dark:bg-gray-800/50 divide-y divide-gray-200 dark:divide-gray-700">
              @forelse ($services as $svc)
                <tr class="hover:bg-gray-100/50 dark:hover:bg-gray-700/50 transition-all duration-200">
                  <td class="px-4 py-4">
                    <div class="flex items-center">
                      <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mr-3">
                        @if($svc->service_type === 'domain')
                          <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                          </svg>
                        @elseif($svc->service_type === 'hosting')
                          <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
                          </svg>
                        @else
                          <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                          </svg>
                        @endif
                      </div>
                      <div>
                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                          @if($svc->service_type === 'domain')
                            {{ $svc->domain->domain_name ?? 'Domain' }}
                          @elseif($svc->service_type === 'hosting')
                            {{ $svc->hosting->plan_name ?? 'Hosting' }}
                          @else
                            {{ ucfirst($svc->service_type) }}
                          @endif
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">₺{{ number_format($svc->sell_price, 0, ',', '.') }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-3 py-4">
                    <a href="{{ route('services.show', $svc) }}" class="inline-flex items-center px-2.5 py-1.5 rounded-xl text-xs font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:hover:bg-blue-900/50 transition-all duration-200 cursor-pointer">
                      <span class="font-mono">{{ $svc->service_code ?? 'N/A' }}</span>
                    </a>
                  </td>
                  <td class="px-3 py-4">
                    @php
                      // Site ayarlarından hizmet türlerini al
                      $serviceTypesSetting = \App\Models\Setting::where('key', 'service_types')->first();
                      $serviceTypes = $serviceTypesSetting ? json_decode($serviceTypesSetting->value, true) : [];
                      
                      // Varsayılan türler (eğer ayar yoksa)
                      if (empty($serviceTypes)) {
                          $serviceTypes = [
                              ['id' => 'domain', 'name' => 'Domain', 'icon' => '🌐', 'color' => '#3B82F6'],
                              ['id' => 'hosting', 'name' => 'Hosting', 'icon' => '🖥️', 'color' => '#10B981'],
                              ['id' => 'ssl', 'name' => 'SSL', 'icon' => '🔒', 'color' => '#8B5CF6'],
                              ['id' => 'email', 'name' => 'E-mail', 'icon' => '📧', 'color' => '#06B6D4'],
                              ['id' => 'other', 'name' => 'Diğer', 'icon' => '📦', 'color' => '#6B7280'],
                          ];
                      }
                      
                      // Mevcut türü bul
                      $currentType = collect($serviceTypes)->firstWhere('id', $svc->service_type);
                      if (!$currentType) {
                          $currentType = ['id' => 'other', 'name' => 'Diğer', 'icon' => '📦', 'color' => '#6B7280'];
                      }
                    @endphp
                    
                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium whitespace-nowrap" 
                          style="background-color: {{ $currentType['color'] }}20; color: {{ $currentType['color'] }};">
                      <span class="mr-1">{{ $currentType['icon'] }}</span>
                      <span>{{ $currentType['name'] }}</span>
                    </span>
                  </td>
                  <td class="px-6 py-4">
                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                      @if($svc->customer)
                        {{ $svc->customer->name }}@if($svc->customer->surname) {{ ' ' . $svc->customer->surname }}@endif
                      @else
                        -
                      @endif
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $svc->customer->email ?? '' }}</div>
                    @if($svc->end_date)
                      @php
                        $daysRemaining = abs((int)$svc->days_remaining);
                        $colorClass = $daysRemaining <= 7 ? 'text-red-600 dark:text-red-400' : ($daysRemaining <= 30 ? 'text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400');
                      @endphp
                      <div class="text-xs {{ $colorClass }} mt-1 font-medium">
                        {{ $daysRemaining }} gün kaldı
                      </div>
                    @endif
                  </td>
                  <td class="px-3 py-4">
                    <div class="text-sm text-gray-900 dark:text-white">{{ $svc->provider->name ?? '-' }}</div>
                  </td>
                  <td class="px-3 py-4">
                    <div class="text-sm font-medium text-gray-900 dark:text-white flex items-center">
                      @switch($svc->cycle)
                        @case('monthly')
                          <span class="mr-1">📅</span><span>Aylık</span>
                          @break
                        @case('quarterly')
                          <span class="mr-1">📅</span><span>3 Aylık</span>
                          @break
                        @case('semiannually')
                          <span class="mr-1">📅</span><span>6 Aylık</span>
                          @break
                        @case('yearly')
                          <span class="mr-1">📅</span><span>Yıllık</span>
                          @break
                        @case('biennially')
                          <span class="mr-1">📅</span><span>2 Yıllık</span>
                          @break
                        @case('triennially')
                          <span class="mr-1">📅</span><span>3 Yıllık</span>
                          @break
                        @default
                          <span class="mr-1">📅</span><span>{{ ucfirst($svc->cycle) }}</span>
                      @endswitch
                    </div>
                  </td>
                  <td class="px-3 py-4">
                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium whitespace-nowrap 
                      {{ $svc->payment_type === 'upfront' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' }}">
                      <span class="mr-1">{{ $svc->payment_type === 'upfront' ? '💰' : '📅' }}</span>
                      <span>{{ $svc->payment_type === 'upfront' ? 'Peşin' : 'Taksit' }}</span>
                    </span>
                  </td>
                  <td class="px-3 py-4">
                    <div class="flex items-center justify-center">
                      @php
                        $statusColors = [
                          'active' => 'background: linear-gradient(135deg, #10b981, #059669); box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);',
                          'suspended' => 'background: linear-gradient(135deg, #f59e0b, #d97706); box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);',
                          'cancelled' => 'background: linear-gradient(135deg, #f43f5e, #e11d48); box-shadow: 0 4px 12px rgba(244, 63, 94, 0.3);',
                          'expired' => 'background: linear-gradient(135deg, #64748b, #475569); box-shadow: 0 4px 12px rgba(100, 116, 139, 0.3);',
                        ];
                        $colorStyle = $statusColors[$svc->status->value] ?? $statusColors['active'];
                      @endphp
                      <div class="w-5 h-5 rounded-full transition-all duration-300 hover:scale-110" style="{{ $colorStyle }}"></div>
                    </div>
                  </td>
                  <td class="px-3 py-4">
                    @if($svc->end_date)
                      <div class="text-sm font-medium text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($svc->end_date)->translatedFormat('d M Y') }}</div>
                    @else
                      <span class="text-gray-400 dark:text-gray-500 text-sm">-</span>
                    @endif
                  </td>
                  <td class="px-3 py-4 text-right">
                    <div class="flex items-center justify-end space-x-2">
                      <a href="{{ route('services.show', $svc) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 p-2 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-xl transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                      </a>
                      <a href="{{ route('services.edit', $svc) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 p-2 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-xl transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                      </a>
                      @php($isDemo = auth()->check() && strcasecmp(auth()->user()->email, config('app.demo_email')) === 0)
                      <form action="{{ route('services.destroy', $svc) }}" method="POST" class="inline" onsubmit="if({{ $isDemo ? 'true' : 'false' }}){ alert('Demo modunda silme işlemi devre dışıdır.'); return false; } return confirm('Bu hizmeti silmek istediğinizden emin misiniz?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" @if($isDemo) disabled @endif class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 p-2 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-all duration-200 {{ $isDemo ? 'opacity-50 cursor-not-allowed' : '' }}" title="{{ $isDemo ? 'Demo modunda kapalı' : '' }}">
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
                  <td colspan="9" class="px-4 py-12 text-center">
                    <div class="flex flex-col items-center">
                      <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
                      </svg>
                      <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">Henüz hizmet bulunmuyor</p>
                      <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">İlk hizmetinizi ekleyerek başlayın</p>
                      <a href="{{ route('services.create') }}" class="mt-4 inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-2xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        İlk Hizmeti Ekle
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
      @if($services->hasPages())
        <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6">
          <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-2xl"></div>
          <div class="relative">
            {{ $services->links() }}
          </div>
        </div>
      @endif
    </div>
  </div>
</x-app-layout>


