<x-app-layout>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="max-w-7xl mx-auto p-6 space-y-8">
      <!-- Hoşgeldin Mesajı -->
      <div class="relative bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 rounded-3xl p-8 text-white shadow-2xl overflow-hidden">
        <div class="absolute inset-0 bg-black/10 rounded-3xl"></div>
        <div class="relative">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-3xl font-bold mb-2">Hoş geldin, {{ $user->name }}! 👋</h1>
              <p class="text-blue-100 text-lg">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
            </div>
            <div class="text-right">
              <div class="text-4xl font-bold mb-2">{{ $invoiceStats['currencySymbol'] }}{{ number_format($cards['mrr'], 2, ',', '.') }}</div>
              <div class="text-lg text-blue-100 font-medium">Aylık Tekrarlanan Gelir (MRR)</div>
              <div class="text-sm text-blue-200 mt-2 opacity-90">MRR yalnızca aktif tekrarlayan hizmetlerin aylığa bölünmüş değeridir.</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Ana İstatistikler -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Müşteriler -->
        <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
          <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
          <div class="relative">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Toplam Müşteri</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $cards['totalCustomers'] }}</p>
                <p class="text-sm text-green-600 dark:text-green-400 font-medium">
                  <span class="font-bold">{{ $totals['activeCustomers'] }}</span> aktif
                </p>
              </div>
              <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
              </div>
            </div>
          </div>
        </div>

        <!-- Hizmetler -->
        <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
          <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
          <div class="relative">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Aktif Hizmet</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $cards['activeServices'] }}</p>
                <p class="text-sm text-red-600 dark:text-red-400 font-medium">
                  <span class="font-bold">{{ $expiringIn7->count() }}</span> 7 günde bitiyor
                </p>
              </div>
              <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
                </svg>
              </div>
            </div>
          </div>
        </div>

        <!-- Gelir -->
        <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
          <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
          <div class="relative">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Bu Ay Gelir</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $invoiceStats['currencySymbol'] }}{{ number_format($cards['thisMonthRevenue'], 2, ',', '.') }}</p>
                <p class="text-sm {{ $monthlyGrowth['trend'] === 'up' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} font-medium">
                  <span class="font-bold">{{ $monthlyGrowth['percentage'] }}%</span> 
                  {{ $monthlyGrowth['trend'] === 'up' ? 'artış' : 'azalış' }}
                </p>
              </div>
              <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
              </div>
            </div>
          </div>
        </div>

        <!-- Geciken Faturalar -->
        <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
          <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
          <div class="relative">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Geciken Fatura</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $cards['overdueCount'] }}</p>
                <p class="text-sm text-orange-600 dark:text-orange-400 font-medium">
                  <span class="font-bold">{{ $invoiceStats['currencySymbol'] }}{{ number_format($cards['overdueAmount'], 2, ',', '.') }}</span> toplam
                </p>
              </div>
              <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-amber-600 rounded-2xl flex items-center justify-center shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Grafikler ve Detaylar -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Gelir Grafiği -->
        <div class="lg:col-span-2 relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8 overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
          <div class="relative">
            <div class="flex items-center justify-between mb-8">
              <h3 class="text-xl font-bold text-gray-900 dark:text-white">Gelir Trendi</h3>
              <div class="flex space-x-2">
                <a href="{{ route('dashboard', ['range' => '7d']) }}" class="px-4 py-2 text-sm rounded-2xl font-medium transition-all duration-200 {{ ($range ?? '12m') === '7d' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg' : 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 hover:bg-blue-200 dark:hover:bg-blue-900/50' }}">Son 7 gün</a>
                <a href="{{ route('dashboard', ['range' => '1m']) }}" class="px-4 py-2 text-sm rounded-2xl font-medium transition-all duration-200 {{ ($range ?? '12m') === '1m' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg' : 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 hover:bg-blue-200 dark:hover:bg-blue-900/50' }}">1 Ay</a>
                <a href="{{ route('dashboard', ['range' => '3m']) }}" class="px-4 py-2 text-sm rounded-2xl font-medium transition-all duration-200 {{ ($range ?? '12m') === '3m' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg' : 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 hover:bg-blue-200 dark:hover:bg-blue-900/50' }}">3 Ay</a>
                <a href="{{ route('dashboard', ['range' => '6m']) }}" class="px-4 py-2 text-sm rounded-2xl font-medium transition-all duration-200 {{ ($range ?? '12m') === '6m' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg' : 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 hover:bg-blue-200 dark:hover:bg-blue-900/50' }}">6 Ay</a>
                <a href="{{ route('dashboard', ['range' => '12m']) }}" class="px-4 py-2 text-sm rounded-2xl font-medium transition-all duration-200 {{ ($range ?? '12m') === '12m' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg' : 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 hover:bg-blue-200 dark:hover:bg-blue-900/50' }}">12 Ay</a>
              </div>
            </div>
            
            <!-- Debug bilgisi kaldırıldı -->
            
            <div class="h-64">
              <canvas id="revenueChart"></canvas>
            </div>
            
            <!-- Hata Mesajı -->
            <div id="chartError" class="hidden mt-4 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 rounded-2xl">
              <p class="text-sm text-red-800 dark:text-red-200" id="chartErrorMessage"></p>
            </div>
          </div>
        </div>

        <!-- Hizmet Dağılımı -->
        <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8 overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
          <div class="relative">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-8">MRR Dağılımı (Hizmet Türü)</h3>
            <div class="space-y-4">
              @forelse($mrrByType as $type => $amount)
                @if($type && $amount > 0)
                <div class="flex items-center justify-between p-3 bg-white/50 dark:bg-gray-700/50 rounded-2xl hover:bg-white/70 dark:hover:bg-gray-700/70 transition-colors">
                  <div class="flex items-center space-x-3">
                    <div class="w-4 h-4 rounded-full shadow-lg
                      {{ $type === 'domain' ? 'bg-gradient-to-r from-blue-500 to-blue-600' : '' }}
                      {{ $type === 'hosting' ? 'bg-gradient-to-r from-green-500 to-green-600' : '' }}
                      {{ $type === 'ssl' ? 'bg-gradient-to-r from-purple-500 to-purple-600' : '' }}
                      {{ $type === 'email' ? 'bg-gradient-to-r from-orange-500 to-orange-600' : '' }}
                      {{ $type === 'development' ? 'bg-gradient-to-r from-indigo-500 to-indigo-600' : '' }}
                      {{ $type === 'maintenance' ? 'bg-gradient-to-r from-red-500 to-red-600' : '' }}
                      {{ !in_array($type, ['domain', 'hosting', 'ssl', 'email', 'development', 'maintenance']) ? 'bg-gradient-to-r from-gray-500 to-gray-600' : '' }}">
                    </div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                      @switch($type)
                        @case('domain')
                          Domain
                          @break
                        @case('hosting')
                          Hosting
                          @break
                        @case('ssl')
                          SSL
                          @break
                        @case('email')
                          E-posta
                          @break
                        @case('development')
                          Geliştirme
                          @break
                        @case('maintenance')
                          Bakım
                          @break
                        @default
                          {{ ucfirst($type) }}
                      @endswitch
                    </span>
                  </div>
                  <span class="text-sm font-bold text-gray-900 dark:text-white">₺{{ number_format($amount, 2, ',', '.') }}</span>
                </div>
                @endif
              @empty
                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                  <p class="text-sm">MRR verisi bulunamadı</p>
                </div>
              @endforelse
            </div>
          </div>
        </div>
      </div>

      <!-- Finansal Metrikler -->
      <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
        <div class="relative">
          <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-8">Finansal Özet</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <x-financial-metric-card 
              title="Kesilen"
              value="₺{{ number_format($invoiceStats['issuedThisMonth'], 2, ',', '.') }}"
              icon="dollar"
              color="green"
              badge="Bu Ay"
            />
            
            <x-financial-metric-card 
              title="Tahsil Edilen"
              value="₺{{ number_format($invoiceStats['paidThisMonth'], 2, ',', '.') }}"
              icon="check"
              color="blue"
              badge="Bu Ay"
            />
            
            <x-financial-metric-card 
              title="Bekleyen"
              value="₺{{ number_format($invoiceStats['pendingThisMonth'], 2, ',', '.') }}"
              icon="clock"
              color="orange"
              badge="Kalan"
            />
            
            <x-financial-metric-card 
              title="Aylık Gelir"
              value="₺{{ number_format($cards['mrr'], 2, ',', '.') }}"
              icon="trending"
              color="purple"
              badge="Ortalama"
            />
          </div>
        </div>
      </div>

      <!-- Alt Bölümler -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Yakında Biten Hizmetler -->
        <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8 overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
          <div class="relative">
            <div class="flex items-center justify-between mb-8">
              <h3 class="text-xl font-bold text-gray-900 dark:text-white">Yakında Biten Hizmetler</h3>
              <a href="{{ route('services.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium transition-colors">Tümünü Gör</a>
            </div>
            <div class="space-y-3">
              @forelse($expiringServices as $service)
                <a href="{{ route('services.show', $service) }}" class="block">
                  <div class="flex items-center justify-between p-4 bg-white/50 dark:bg-gray-700/50 rounded-2xl hover:bg-white/70 dark:hover:bg-gray-700/70 transition-all duration-200 cursor-pointer hover:shadow-lg">
                    <div class="flex items-center space-x-3">
                      <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                        </svg>
                      </div>
                      <div>
                        <p class="font-medium text-sm text-gray-900 dark:text-white">
                          {{ $service->customer ? ($service->customer->customer_type === 'corporate' ? $service->customer->name : $service->customer->name . ' ' . ($service->customer->surname ?? '')) : 'Bilinmeyen Müşteri' }}
                        </p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ ucfirst($service->service_type ?? 'Bilinmeyen') }}</p>
                      </div>
                    </div>
                    <div class="text-right">
                      <p class="text-xs font-medium text-gray-900 dark:text-white">{{ $service->end_date?->format('d.m.Y') ?? 'Belirtilmemiş' }}</p>
                      @if($service->days_remaining !== null)
                        <p class="text-xs {{ abs((int)$service->days_remaining) <= 7 ? 'text-red-600 dark:text-red-400' : 'text-orange-600 dark:text-orange-400' }} font-medium">
                          {{ abs((int)$service->days_remaining) }} gün kaldı
                        </p>
                      @else
                        <p class="text-xs text-gray-500 dark:text-gray-400">Süre belirtilmemiş</p>
                      @endif
                    </div>
                  </div>
                </a>
              @empty
              <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-lg">Yakında biten hizmet yok</p>
              </div>
              @endforelse
            </div>
          </div>
        </div>

        <!-- Son Aktiviteler -->
        <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8 overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
          <div class="relative">
            <div class="flex items-center justify-between mb-8">
              <h3 class="text-xl font-bold text-gray-900 dark:text-white">Son Aktiviteler</h3>
            </div>
            <div class="space-y-4">
              @forelse($recentActivities as $activity)
              <div class="flex items-start space-x-3 p-3 bg-white/50 dark:bg-gray-700/50 rounded-2xl hover:bg-white/70 dark:hover:bg-gray-700/70 transition-colors">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    @if($activity['icon'] === 'receipt')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    @else
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
                    @endif
                  </svg>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $activity['title'] }}</p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">{{ $activity['description'] }}</p>
                  <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ $activity['date']->diffForHumans() }}</p>
                </div>
              </div>
              @empty
              <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                <p class="text-lg">Henüz aktivite yok</p>
              </div>
              @endforelse
            </div>
          </div>
        </div>
      </div>

      <!-- Hızlı Erişim -->
      <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
        <div class="relative">
          <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-8">Hızlı Erişim</h3>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <a href="{{ route('customers.create') }}" class="group flex flex-col items-center p-6 bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 rounded-3xl hover:from-blue-100 hover:to-indigo-200 dark:hover:from-blue-900/50 dark:hover:to-indigo-900/50 transition-all duration-300 cursor-pointer hover:shadow-xl hover:-translate-y-1">
              <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg mb-3 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
              </div>
              <span class="text-sm font-bold text-blue-700 dark:text-blue-300">Yeni Müşteri</span>
            </a>
            
            <a href="{{ route('services.create') }}" class="group flex flex-col items-center p-6 bg-gradient-to-br from-green-50 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 rounded-3xl hover:from-green-100 hover:to-emerald-200 dark:hover:from-green-900/50 dark:hover:to-emerald-900/50 transition-all duration-300 cursor-pointer hover:shadow-xl hover:-translate-y-1">
              <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg mb-3 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
              </div>
              <span class="text-sm font-bold text-green-700 dark:text-green-300">Yeni Hizmet</span>
            </a>
            
            <a href="{{ route('invoices.create') }}" class="group flex flex-col items-center p-6 bg-gradient-to-br from-purple-50 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 rounded-3xl hover:from-purple-100 hover:to-pink-200 dark:hover:from-purple-900/50 dark:hover:to-pink-900/50 transition-all duration-300 cursor-pointer hover:shadow-xl hover:-translate-y-1">
              <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg mb-3 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
              </div>
              <span class="text-sm font-bold text-purple-700 dark:text-purple-300">Yeni Fatura</span>
            </a>
            
            <a href="{{ route('providers.create') }}" class="group flex flex-col items-center p-6 bg-gradient-to-br from-orange-50 to-amber-100 dark:from-orange-900/30 dark:to-amber-900/30 rounded-3xl hover:from-orange-100 hover:to-amber-200 dark:hover:from-orange-900/50 dark:hover:to-amber-900/50 transition-all duration-300 cursor-pointer hover:shadow-xl hover:-translate-y-1">
              <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-amber-600 rounded-2xl flex items-center justify-center shadow-lg mb-3 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
              </div>
              <span class="text-sm font-bold text-orange-700 dark:text-orange-300">Yeni Sağlayıcı</span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Chart.js Local -->
  @vite(['resources/js/app.js'])
  
  <script>
    // Chart.js'i global olarak tanımla
    let Chart = null;
    
    // Sayfa yüklendiğinde Chart.js'i kontrol et
    document.addEventListener('DOMContentLoaded', function() {
      console.log('DOM loaded, checking Chart.js...');
      
      // Chart.js'i window'dan al
      if (window.Chart) {
        Chart = window.Chart;
        console.log('Chart.js loaded from window:', typeof Chart !== 'undefined');
        console.log('Chart version:', Chart.version);
        initializeChart();
      } else {
        console.error('Chart.js not found in window');
        showChartError('Chart.js yüklenmedi');
      }
    });
    
    function showChartError(message) {
      const chartError = document.getElementById('chartError');
      const chartErrorMessage = document.getElementById('chartErrorMessage');
      if (chartError && chartErrorMessage) {
        chartErrorMessage.textContent = message;
        chartError.classList.remove('hidden');
      }
    }
    
    function initializeChart() {
      // Gelir Grafiği
      const ctx = document.getElementById('revenueChart');
      const chartError = document.getElementById('chartError');
      const chartErrorMessage = document.getElementById('chartErrorMessage');
      
      console.log('Canvas element:', ctx);
      console.log('Canvas context:', ctx?.getContext('2d'));
      
      if (ctx && Chart) {
        try {
          const labels = {!! json_encode(array_keys($revenueSeries->toArray())) !!};
          const data = {!! json_encode(array_values($revenueSeries->toArray())) !!};
          
          console.log('Chart Data:', { labels, data });
          console.log('Labels type:', typeof labels, 'Length:', labels?.length);
          console.log('Data type:', typeof data, 'Length:', data?.length);
          
          // Veri kontrolü
          if (!labels || !data || labels.length === 0 || data.length === 0) {
            throw new Error('Gelir verisi bulunamadı veya boş');
          }
          
          // Dark mode kontrolü
          const isDark = document.documentElement.classList.contains('dark');
          const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)';
          const textColor = isDark ? '#9ca3af' : '#374151';
          const borderColor = isDark ? '#3b82f6' : '#3b82f6';
          const backgroundColor = isDark ? 'rgba(59, 130, 246, 0.2)' : 'rgba(59, 130, 246, 0.1)';
          
          console.log('Creating chart with options:', {
            type: 'line',
            labels: labels,
            data: data,
            isDark: isDark
          });
          
          const chart = new Chart(ctx, {
            type: 'line',
            data: {
              labels: labels,
              datasets: [{
                label: 'Gelir (₺)',
                data: data,
                borderColor: borderColor,
                backgroundColor: backgroundColor,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: borderColor,
                pointBorderColor: isDark ? '#1f2937' : '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 4
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: {
                  display: false
                }
              },
              scales: {
                y: {
                  beginAtZero: true,
                  grid: {
                    color: gridColor
                  },
                  ticks: {
                    color: textColor,
                    callback: function(value) {
                      return '₺' + value.toLocaleString('tr-TR');
                    }
                  }
                },
                x: {
                  grid: {
                    display: false
                  },
                  ticks: {
                    color: textColor
                  }
                }
              },
              interaction: {
                intersect: false,
                mode: 'index'
              }
            }
          });
          
          console.log('Chart created successfully:', chart);
          console.log('Chart instance:', Chart.getChart(ctx));
          
          // Dark mode değişikliklerini dinle
          const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
              if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                try {
                  const currentChart = Chart.getChart(ctx);
                  if (currentChart) {
                    const isDark = document.documentElement.classList.contains('dark');
                    const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)';
                    const textColor = isDark ? '#9ca3af' : '#374151';
                    
                    currentChart.options.scales.y.grid.color = gridColor;
                    currentChart.options.scales.y.ticks.color = textColor;
                    currentChart.options.scales.x.ticks.color = textColor;
                    currentChart.update();
                  }
                } catch (e) {
                  console.error('Chart update error:', e);
                }
              }
            });
          });

          observer.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['class']
          });
          
        } catch (error) {
          console.error('Chart creation error:', error);
          console.error('Error stack:', error.stack);
          
          // Hata mesajını göster
          if (chartError && chartErrorMessage) {
            chartErrorMessage.textContent = 'Grafik yüklenirken hata oluştu: ' + error.message;
            chartError.classList.remove('hidden');
          }
          
          // Canvas'a hata mesajı yaz
          const ctx2d = ctx.getContext('2d');
          if (ctx2d) {
            const isDark = document.documentElement.classList.contains('dark');
            ctx2d.fillStyle = isDark ? '#6b7280' : '#9ca3af';
            ctx2d.font = '14px Arial';
            ctx2d.textAlign = 'center';
            ctx2d.fillText('Grafik yüklenemedi', ctx.width / 2, ctx.height / 2);
          }
        }
      } else {
        console.error('Canvas element bulunamadı veya Chart.js yüklenmedi');
        if (!Chart) {
          showChartError('Chart.js yüklenmedi');
        }
      }
    }
  </script>
</x-app-layout>
