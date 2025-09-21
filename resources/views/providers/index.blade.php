<x-app-layout>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-orange-50 to-red-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="max-w-7xl mx-auto p-6 space-y-8">
      <!-- Başlık ve İstatistikler -->
      <div class="relative overflow-hidden bg-gradient-to-r from-orange-600 via-red-600 to-pink-600 rounded-3xl p-8 shadow-2xl">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative flex items-center justify-between">
          <div class="space-y-2">
            <h1 class="text-4xl font-bold text-white drop-shadow-lg">Sağlayıcı Yönetimi</h1>
            <p class="text-orange-100 text-lg">Hizmet sağlayıcılarınızı yönetin ve takip edin</p>
          </div>
          <div class="text-right">
            <div class="w-20 h-20 bg-white/20 rounded-3xl flex items-center justify-center backdrop-blur-sm">
              <div class="text-4xl font-bold">{{ $providers->total() }}</div>
            </div>
            <div class="text-orange-100 text-lg mt-2">Toplam Sağlayıcı</div>
          </div>
        </div>
        
        <!-- Dekoratif Elementler -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
      </div>

      <!-- İstatistik Kartları -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6 hover:shadow-2xl hover:scale-105 transition-all duration-300">
          <div class="absolute inset-0 bg-gradient-to-br from-orange-50/50 to-red-50/50 dark:from-orange-900/20 dark:to-red-800/20 rounded-2xl"></div>
          <div class="relative flex items-center justify-between">
            <div>
              <p class="text-sm font-semibold text-gray-600 dark:text-gray-400">Toplam Sağlayıcı</p>
              <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $providers->total() }}</p>
              <p class="text-sm text-orange-600 dark:text-orange-400 mt-1">Kayıtlı sağlayıcı</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-red-600 rounded-2xl flex items-center justify-center shadow-lg">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
              </svg>
            </div>
          </div>
        </div>

        <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6 hover:shadow-2xl hover:scale-105 transition-all duration-300">
          <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 to-indigo-50/50 dark:from-blue-900/20 dark:to-indigo-800/20 rounded-2xl"></div>
          <div class="relative flex items-center justify-between">
            <div>
              <p class="text-sm font-semibold text-gray-600 dark:text-gray-400">Tür Çeşidi</p>
              <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Provider::count() }}</p>
              <p class="text-sm text-blue-600 dark:text-blue-400 mt-1">Farklı hizmet türü</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
              </svg>
            </div>
          </div>
        </div>

        <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6 hover:shadow-2xl hover:scale-105 transition-all duration-300">
          <div class="absolute inset-0 bg-gradient-to-br from-green-50/50 to-emerald-50/50 dark:from-green-900/20 dark:to-emerald-800/20 rounded-2xl"></div>
          <div class="relative flex items-center justify-between">
            <div>
              <p class="text-sm font-semibold text-gray-600 dark:text-gray-400">Web Sitesi Olan</p>
              <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Provider::whereNotNull('website')->where('website', '<>', '')->count() }}</p>
              <p class="text-sm text-green-600 dark:text-green-400 mt-1">Online varlık</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
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
              <p class="text-sm font-semibold text-gray-600 dark:text-gray-400">Aktif Hizmet</p>
              <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Service::where('status', 'active')->count() }}</p>
              <p class="text-sm text-purple-600 dark:text-purple-400 mt-1">Devam eden hizmet</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-violet-600 rounded-2xl flex items-center justify-center shadow-lg">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
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
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Sağlayıcı Listesi</h3>
            <a href="{{ route('providers.create') }}" class="group relative inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-600 to-red-600 text-white font-semibold rounded-2xl hover:from-orange-700 hover:to-red-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
              <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-white/10 rounded-2xl"></div>
              <svg class="w-5 h-5 mr-2 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
              <span class="relative z-10">Yeni Sağlayıcı</span>
            </a>
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

          <!-- Sağlayıcı Listesi -->
          <div class="w-full">
            <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600">
                <tr>
                  <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Sağlayıcı</th>
                  <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tür</th>
                  <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">İletişim</th>
                  <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Hizmet Sayısı</th>
                  <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">İşlemler</th>
                </tr>
              </thead>
              <tbody class="bg-white/50 dark:bg-gray-800/50 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($providers as $provider)
                  <tr class="hover:bg-gray-100/50 dark:hover:bg-gray-700/50 transition-all duration-200">
                    <td class="px-6 py-4">
                      <div class="flex items-center">
                        <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/30 rounded-full flex items-center justify-center mr-3">
                          <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                          </svg>
                        </div>
                        <div>
                          <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $provider->name }}</div>
                          @if($provider->email)
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $provider->email }}</div>
                          @endif
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4">
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
                        
                        // Dinamik badge array'i oluştur
                        $typeToBadge = [];
                        foreach ($serviceTypes as $serviceType) {
                          $typeToBadge[$serviceType['id']] = [
                            'label' => $serviceType['icon'] . ' ' . $serviceType['name'],
                            'bg' => 'bg-opacity-10',
                            'text' => 'text-opacity-80',
                            'style' => 'background-color: ' . $serviceType['color'] . '20; color: ' . $serviceType['color'] . ';'
                          ];
                        }
                        
                        // Provider types'ı array olarak al
                        $providerTypes = is_array($provider->type) ? $provider->type : [$provider->type];
                        
                        // Legacy mapping
                        $legacyMap = [
                          'domain_registrar' => 'domain',
                          'hosting_company' => 'hosting',
                          'ssl_provider' => 'ssl',
                          'email_service' => 'email',
                          'software_vendor' => 'other',
                          'web_design' => 'other',
                          'marketing_agency' => 'other',
                          'cloud_provider' => 'other',
                          'backup_service' => 'other',
                          'security_service' => 'other',
                          'cms_platform' => 'other',
                          'ecommerce_platform' => 'other',
                          'payment_gateway' => 'other',
                          'analytics_service' => 'other',
                        ];
                        
                        // Legacy types'ları normalize et
                        $normalizedTypes = array_map(function($type) use ($legacyMap) {
                          return $legacyMap[$type] ?? $type;
                        }, $providerTypes);
                        
                        // Unique types
                        $normalizedTypes = array_unique($normalizedTypes);
                      @endphp
                      
                      <div class="flex flex-col gap-1">
                        @foreach($normalizedTypes as $type)
                          @php
                            $cfg = $typeToBadge[$type] ?? $typeToBadge['other'] ?? ['label' => '📦 Diğer', 'style' => 'background-color: #6B728020; color: #6B7280;'];
                          @endphp
                          <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="{{ $cfg['style'] }}">
                            {{ $cfg['label'] }}
                          </span>
                        @endforeach
                      </div>
                    </td>
                    <td class="px-6 py-4">
                      @if($provider->website)
                        <a href="{{ $provider->website }}" target="_blank" rel="noopener noreferrer" 
                           class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-all duration-200">
                          <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                          </svg>
                          {{ parse_url($provider->website, PHP_URL_HOST) ?: $provider->website }}
                        </a>
                        @if($provider->phone)
                          <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $provider->phone }}</div>
                        @endif
                      @else
                        <span class="text-gray-400 dark:text-gray-500 text-sm">Web sitesi yok</span>
                      @endif
                    </td>
                    <td class="px-6 py-4">
                      <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $provider->services_count ?? 0 }}</div>
                      <div class="text-xs text-gray-500 dark:text-gray-400">aktif hizmet</div>
                    </td>
                    <td class="px-6 py-4 text-right">
                      <div class="flex items-center justify-end space-x-2">
                        <a href="{{ route('providers.edit', $provider) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 p-2 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-xl transition-all duration-200">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                          </svg>
                        </a>
                        @php($isDemo = auth()->check() && strcasecmp(auth()->user()->email, config('app.demo_email')) === 0)
                        <form action="{{ route('providers.destroy', $provider) }}" method="POST" class="inline" onsubmit="if({{ $isDemo ? 'true' : 'false' }}){ alert('Demo modunda silme işlemi devre dışıdır.'); return false; } return confirm('Bu sağlayıcıyı silmek istediğinizden emin misiniz?')">
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
                    <td colspan="5" class="px-6 py-12 text-center">
                      <div class="flex flex-col items-center">
                        <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">Henüz sağlayıcı bulunmuyor</p>
                        <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">İlk sağlayıcınızı ekleyerek başlayın</p>
                        <a href="{{ route('providers.create') }}" class="mt-4 inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-600 to-red-600 text-white rounded-2xl hover:from-orange-700 hover:to-red-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                          </svg>
                          İlk Sağlayıcıyı Ekle
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
      @if($providers->hasPages())
        <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6">
          <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-2xl"></div>
          <div class="relative">
            {{ $providers->links() }}
          </div>
        </div>
      @endif
    </div>
  </div>
</x-app-layout>


