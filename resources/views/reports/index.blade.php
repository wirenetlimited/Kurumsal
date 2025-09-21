<x-app-layout>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="max-w-7xl mx-auto p-6 space-y-8">
      <!-- Başlık -->
      <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 rounded-3xl p-8 shadow-2xl">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative flex items-center justify-between">
          <div class="space-y-2">
            <h1 class="text-4xl font-bold text-white drop-shadow-lg">Raporlar & Analizler</h1>
            <p class="text-blue-100 text-lg">İşletmenizin performansını analiz edin ve detaylı raporlar alın</p>
          </div>
          <div class="text-right">
            <div class="text-4xl font-bold">📊</div>
            <div class="text-blue-100 text-lg">Analiz Merkezi</div>
          </div>
        </div>
        
        <!-- Dekoratif Elementler -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
      </div>

      <!-- Rapor Kartları -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Gelir Raporu -->
        <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8 hover:shadow-2xl hover:scale-105 transition-all duration-300">
          <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
          <div class="relative">
            <div class="flex items-center justify-between mb-6">
              <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
              </div>
              <span class="text-sm font-semibold text-blue-600 dark:text-blue-400 bg-gradient-to-r from-blue-100 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 px-3 py-1 rounded-full border border-blue-200 dark:border-blue-700">Gelir</span>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Gelir Analizi</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-6">Aylık, yıllık gelir trendlerini ve büyüme oranlarını görün</p>
            <div class="flex space-x-2">
              <a href="{{ route('reports.revenue') }}" class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-center py-3 px-6 rounded-2xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 text-sm font-semibold shadow-lg hover:shadow-xl">
                Görüntüle
              </a>
            </div>
          </div>
        </div>

        <!-- Hizmet Raporu -->
        <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8 hover:shadow-2xl hover:scale-105 transition-all duration-300">
          <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
          <div class="relative">
            <div class="flex items-center justify-between mb-6">
              <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
                </svg>
              </div>
              <span class="text-sm font-semibold text-green-600 dark:text-green-400 bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 px-3 py-1 rounded-full border border-green-200 dark:border-green-700">Hizmet</span>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Hizmet Analizi</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-6">Domain, hosting ve SSL hizmetlerinin dağılımını analiz edin</p>
            <div class="flex space-x-2">
              <a href="{{ route('reports.services') }}" class="flex-1 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-center py-3 px-6 rounded-2xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 text-sm font-semibold shadow-lg hover:shadow-xl">
                Görüntüle
              </a>
            </div>
          </div>
        </div>

        <!-- Müşteri Raporu -->
        <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8 hover:shadow-2xl hover:scale-105 transition-all duration-300">
          <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
          <div class="relative">
            <div class="flex items-center justify-between mb-6">
              <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
              </div>
              <span class="text-sm font-semibold text-purple-600 dark:text-purple-400 bg-gradient-to-r from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 px-3 py-1 rounded-full border border-purple-200 dark:border-purple-700">Müşteri</span>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Müşteri Analizi</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-6">Müşteri segmentasyonu ve hizmet kullanım oranlarını görün</p>
            <div class="flex space-x-2">
              <a href="{{ route('reports.customers') }}" class="flex-1 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-center py-3 px-6 rounded-2xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 text-sm font-semibold shadow-lg hover:shadow-xl">
                Görüntüle
              </a>
            </div>
          </div>
        </div>

        <!-- Sağlayıcı Raporu -->
        <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8 hover:shadow-2xl hover:scale-105 transition-all duration-300">
          <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
          <div class="relative">
            <div class="flex items-center justify-between mb-6">
              <div class="w-16 h-16 bg-gradient-to-br from-orange-400 to-red-600 rounded-2xl flex items-center justify-center shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
              </div>
              <span class="text-sm font-semibold text-orange-600 dark:text-orange-400 bg-gradient-to-r from-orange-100 to-red-100 dark:from-orange-900/30 dark:to-red-900/30 px-3 py-1 rounded-full border border-orange-200 dark:border-orange-700">Sağlayıcı</span>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Sağlayıcı Analizi</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-6">Sağlayıcı performanslarını ve hizmet dağılımlarını analiz edin</p>
            <div class="flex space-x-2">
              <a href="{{ route('reports.providers') }}" class="flex-1 bg-gradient-to-r from-orange-600 to-red-600 text-white text-center py-3 px-6 rounded-2xl hover:from-orange-700 hover:to-red-700 transition-all duration-200 text-sm font-semibold shadow-lg hover:shadow-xl">
                Görüntüle
              </a>
            </div>
          </div>
        </div>

        <!-- Özet Rapor -->
        <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8 hover:shadow-2xl hover:scale-105 transition-all duration-300">
          <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
          <div class="relative">
            <div class="flex items-center justify-between mb-6">
              <div class="w-16 h-16 bg-gradient-to-br from-indigo-400 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
              </div>
              <span class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 bg-gradient-to-r from-indigo-100 to-blue-100 dark:from-indigo-900/30 dark:to-blue-900/30 px-3 py-1 rounded-full border border-indigo-200 dark:border-indigo-700">Özet</span>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Genel Özet</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-6">Tüm metrikleri tek sayfada görün ve karşılaştırın</p>
            <div class="flex space-x-2">
              <a href="{{ route('dashboard') }}" class="flex-1 bg-gradient-to-r from-indigo-600 to-blue-600 text-white text-center py-3 px-6 rounded-2xl hover:from-indigo-700 hover:to-blue-700 transition-all duration-200 text-sm font-semibold shadow-lg hover:shadow-xl">
                Dashboard'a Git
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- İstatistikler -->
      <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8">
        <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
        <div class="relative">
          <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">Hızlı İstatistikler</h3>
          <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="text-center group">
              <div class="text-4xl font-bold text-blue-600 dark:text-blue-400 mb-2 group-hover:scale-110 transition-transform duration-200">{{ \App\Models\Customer::count() }}</div>
              <div class="text-sm font-semibold text-gray-600 dark:text-gray-400">Toplam Müşteri</div>
            </div>
            <div class="text-center group">
              <div class="text-4xl font-bold text-green-600 dark:text-green-400 mb-2 group-hover:scale-110 transition-transform duration-200">{{ \App\Models\Service::where('status', 'aktif')->count() }}</div>
              <div class="text-sm font-semibold text-gray-600 dark:text-gray-400">Aktif Hizmet</div>
            </div>
            <div class="text-center group">
              <div class="text-4xl font-bold text-purple-600 dark:text-purple-400 mb-2 group-hover:scale-110 transition-transform duration-200">₺{{ number_format(\App\Models\Invoice::where('status', 'odendi')->sum('total'), 0, ',', '.') }}</div>
              <div class="text-sm font-semibold text-gray-600 dark:text-gray-400">Toplam Gelir</div>
            </div>
            <div class="text-center group">
              <div class="text-4xl font-bold text-orange-600 dark:text-orange-400 mb-2 group-hover:scale-110 transition-transform duration-200">{{ \App\Models\Provider::count() }}</div>
              <div class="text-sm font-semibold text-gray-600 dark:text-gray-400">Sağlayıcı</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
