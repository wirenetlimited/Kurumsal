<x-app-layout>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-pink-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="max-w-7xl mx-auto p-6 space-y-8">
      <!-- Başlık -->
      <div class="relative overflow-hidden bg-gradient-to-r from-purple-600 via-pink-600 to-rose-600 rounded-3xl p-8 shadow-2xl">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative flex items-center justify-between">
          <div class="space-y-2">
            <h1 class="text-4xl font-bold text-white drop-shadow-lg">Site Ayarları</h1>
            <p class="text-purple-100 text-lg">Sistem genelinde kullanılan tüm ayarları yönetin</p>
          </div>
          <div class="text-right">
            <div class="text-4xl font-bold">⚙️</div>
            <div class="text-purple-100 text-lg">{{ count($groups) }} Ayar Grubu</div>
          </div>
        </div>
        
        <!-- Dekoratif Elementler -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
      </div>

      @if (session('status'))
        <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6">
          <div class="absolute inset-0 bg-gradient-to-br from-green-50/50 to-emerald-50/50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-2xl"></div>
          <div class="relative flex items-center">
            <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <span class="text-green-800 dark:text-green-200 font-semibold text-lg">{{ session('status') }}</span>
          </div>
        </div>
      @endif

      <!-- Ayar Grupları -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($groups as $groupKey => $group)
          <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8 hover:shadow-2xl hover:scale-105 transition-all duration-300">
            <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
            <div class="relative">
              <div class="flex items-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                  <i class="{{ $group['icon'] }} text-white text-2xl"></i>
                </div>
                <div>
                  <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $group['name'] }}</h3>
                  <p class="text-sm text-gray-600 dark:text-gray-400">{{ $group['description'] }}</p>
                </div>
              </div>

              <div class="space-y-3 mb-6">
                @foreach($settings[$groupKey] as $setting)
                  <div class="flex items-center justify-between text-sm p-3 bg-gray-50/50 dark:bg-gray-700/50 rounded-xl">
                    <span class="text-gray-700 dark:text-gray-300 font-medium">{{ $setting->label }}</span>
                    <span class="text-gray-900 dark:text-white font-semibold">
                      @if($setting->type === 'boolean')
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $setting->value ? 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 dark:from-green-900/30 dark:to-emerald-900/30 dark:text-green-200 border border-green-200 dark:border-green-700' : 'bg-gradient-to-r from-red-100 to-rose-100 text-red-800 dark:from-red-900/30 dark:to-rose-900/30 dark:text-red-200 border border-red-200 dark:border-red-700' }}">
                          {{ $setting->value ? 'Aktif' : 'Pasif' }}
                        </span>
                      @elseif($setting->type === 'file')
                        @if($setting->value)
                          <span class="text-blue-600 dark:text-blue-400 font-semibold">Yüklü</span>
                        @else
                          <span class="text-gray-400 dark:text-gray-500">Yok</span>
                        @endif
                      @else
                        {{ Str::limit($setting->value, 30) ?: 'Ayarlanmamış' }}
                      @endif
                    </span>
                  </div>
                @endforeach
              </div>

              <button onclick="openSettingsModal('{{ $groupKey }}')" 
                      class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white py-3 px-6 rounded-2xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl">
                {{ $group['name'] }}nı Düzenle
              </button>
            </div>
          </div>
        @endforeach
      </div>

      <!-- Hizmet Türleri Yönetimi -->
      <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8">
        <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
        <div class="relative">
          <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Hizmet Türleri Yönetimi</h2>
          
          <div class="space-y-4">
            <div class="flex items-center justify-between p-6 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl border border-blue-200/50 dark:border-blue-700/50">
              <div>
                <h3 class="font-semibold text-gray-900 dark:text-white text-lg mb-2">Mevcut Hizmet Türleri</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Sistemde tanımlı tüm hizmet türlerini görüntüleyin ve yönetin</p>
                
                <!-- Mevcut Hizmet Türleri Önizleme -->
                <div class="mt-3">
                  <div id="serviceTypesPreview" class="flex flex-wrap gap-2">
                    <!-- JavaScript ile doldurulacak -->
                  </div>
                </div>
              </div>
              <button onclick="openServiceTypesModal()" 
                      class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 px-6 rounded-2xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl">
                Türleri Yönet
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Servis Durumları Yönetimi -->
      <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8">
        <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
        <div class="relative">
          <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Servis Durumları Yönetimi</h2>
          
          <div class="space-y-4">
            <div class="flex items-center justify-between p-6 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-2xl border border-green-200/50 dark:border-green-700/50">
              <div>
                <h3 class="font-semibold text-gray-900 dark:text-white text-lg mb-2">Durum Yönetimi</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Hizmet ve fatura durumlarını, etiketlerini ve renklerini yönetin</p>
                
                <!-- Mevcut Durumlar Önizleme -->
                <div class="mt-3 space-y-3">
                  <div class="flex items-center space-x-3">
                    <span class="text-sm text-gray-600 dark:text-gray-400 font-medium">Hizmet Durumları:</span>
                    <div id="serviceStatusesPreview" class="flex flex-wrap gap-2">
                      <!-- JavaScript ile doldurulacak -->
                    </div>
                  </div>
                  <div class="flex items-center space-x-3">
                    <span class="text-sm text-gray-600 dark:text-gray-400 font-medium">Fatura Durumları:</span>
                    <div id="invoiceStatusesPreview" class="flex flex-wrap gap-2">
                      <!-- JavaScript ile doldurulacak -->
                    </div>
                  </div>
                </div>
              </div>
              <button onclick="openServiceStatusesModal()" 
                      class="bg-gradient-to-r from-green-600 to-emerald-600 text-white py-3 px-6 rounded-2xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl">
                Durumları Yönet
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Sistem Araçları -->
      <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8">
        <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
        <div class="relative">
          <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Sistem Araçları</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <form method="POST" action="{{ route('admin.site-settings.clear-cache') }}" class="inline">
              @csrf
              <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-4 px-6 rounded-2xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl group">
                <div class="flex items-center justify-center">
                  <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-200">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                  </div>
                  <span>Cache Temizle</span>
                </div>
              </button>
            </form>

            <a href="{{ route('admin.site-settings.logs') }}" 
               class="w-full bg-gradient-to-r from-orange-600 to-red-600 text-white py-4 px-6 rounded-2xl hover:from-orange-700 hover:to-red-700 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl text-center group">
              <div class="flex items-center justify-center">
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-200">
                  <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                  </svg>
                </div>
                <span>Logları Görüntüle</span>
              </div>
            </a>

            <button onclick="exportSettings()" 
                    class="w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white py-4 px-6 rounded-2xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl group">
              <div class="flex items-center justify-center">
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-200">
                  <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                  </svg>
                </div>
                <span>Ayarları Dışa Aktar</span>
              </div>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Ayar Düzenleme Modal -->
  <div id="settingsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 backdrop-blur-sm">
    <div class="flex items-center justify-center min-h-screen p-4">
      <div class="bg-white/95 dark:bg-gray-800/95 backdrop-blur-md rounded-3xl shadow-2xl max-w-4xl w-full max-h-screen overflow-y-auto border border-white/20 dark:border-gray-700/50">
        <div class="p-8">
          <div class="flex items-center justify-between mb-8">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white" id="modalTitle">Ayar Grubu</h3>
            <button onclick="closeSettingsModal()" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-400 p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-colors">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>

          <form id="settingsForm" method="POST" action="{{ route('admin.site-settings.update') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="group" id="modalGroup">
            
            <div id="modalContent" class="space-y-8">
              <!-- Form içeriği JavaScript ile doldurulacak -->
            </div>

            <div class="flex justify-end space-x-4 mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
              <button type="button" onclick="closeSettingsModal()" 
                      class="bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 py-3 px-6 rounded-2xl hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors font-semibold">
                İptal
              </button>
              <button type="submit" 
                      class="bg-gradient-to-r from-purple-600 to-pink-600 text-white py-3 px-6 rounded-2xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 font-semibold shadow-lg">
                Ayarları Kaydet
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Hizmet Türleri Modal -->
  <div id="serviceTypesModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 backdrop-blur-sm">
    <div class="flex items-center justify-center min-h-screen p-4">
      <div class="bg-white/95 dark:bg-gray-800/95 backdrop-blur-md rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto border border-white/20 dark:border-gray-700/50">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <div class="flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Hizmet Türleri Yönetimi</h3>
            <button onclick="closeServiceTypesModal()" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-400 p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-colors">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
        </div>

        <div class="p-6 space-y-6">
          <!-- Yeni Tür Ekleme -->
          <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl p-6 border border-blue-200/50 dark:border-blue-700/50">
            <h4 class="font-semibold text-gray-900 dark:text-white mb-4 text-lg">Yeni Hizmet Türü Ekle</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tür Adı</label>
                <input type="text" id="newTypeName" placeholder="Örn: VPS" 
                       class="w-full border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400 transition-all duration-200">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">İkon</label>
                <input type="text" id="newTypeIcon" placeholder="Örn: 🖥️" 
                       class="w-full border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400 transition-all duration-200">
              </div>
            </div>
            <button onclick="addServiceType()" 
                    class="mt-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 px-6 rounded-2xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 font-semibold shadow-lg">
              Tür Ekle
            </button>
          </div>

          <!-- Mevcut Türler -->
          <div>
            <h4 class="font-semibold text-gray-900 dark:text-white mb-4 text-lg">Mevcut Hizmet Türleri</h4>
            <div id="serviceTypesList" class="space-y-3">
              <!-- Türler buraya yüklenecek -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Servis Durumları Modal -->
  <div id="serviceStatusesModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 backdrop-blur-sm">
    <div class="flex items-center justify-center min-h-screen p-4">
      <div class="bg-white/95 dark:bg-gray-800/95 backdrop-blur-md rounded-3xl shadow-2xl max-w-5xl w-full max-h-[90vh] overflow-y-auto border border-white/20 dark:border-gray-700/50">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <div class="flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Servis Durumları Yönetimi</h3>
            <button onclick="closeServiceStatusesModal()" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-400 p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-colors">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
        </div>

        <div class="p-6 space-y-6">
          <!-- Hizmet Durumları -->
          <div>
            <h4 class="font-semibold text-gray-900 dark:text-white mb-4 text-lg">Hizmet Durumları</h4>
            <div id="serviceStatusesList" class="space-y-4">
              <!-- Hizmet durumları buraya yüklenecek -->
            </div>
          </div>
          
          <!-- Fatura Durumları -->
          <div>
            <h4 class="font-semibold text-gray-900 dark:text-white mb-4 text-lg">Fatura Durumları</h4>
            <div id="invoiceStatusesList" class="space-y-4">
              <!-- Fatura durumları buraya yüklenecek -->
            </div>
          </div>

          <!-- Kaydet Butonu -->
          <div class="flex justify-end pt-6 border-t border-gray-200 dark:border-gray-700">
            <button onclick="saveServiceStatuses()" 
                    class="bg-gradient-to-r from-green-600 to-emerald-600 text-white py-3 px-8 rounded-2xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 font-semibold shadow-lg">
              Değişiklikleri Kaydet
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    const settingsData = @json($settings);
    const groupsData = @json($groups);

    // Servis durumları için varsayılan veriler
    if (!settingsData.service_statuses) {
      settingsData.service_statuses = {
        value: JSON.stringify([
          { value: 'active', label: 'Aktif', color: 'green', icon: '✅', description: 'Aktif hizmetler' },
          { value: 'expired', label: 'Süresi Dolmuş', color: 'red', icon: '⏰', description: 'Süresi dolmuş hizmetler' },
          { value: 'suspended', label: 'Askıya Alınmış', color: 'yellow', icon: '⏸️', description: 'Askıya alınmış hizmetler' },
          { value: 'cancelled', label: 'İptal Edilmiş', color: 'gray', icon: '❌', description: 'İptal edilmiş hizmetler' }
        ])
      };
    }
    
    if (!settingsData.invoice_statuses) {
      settingsData.invoice_statuses = {
        value: JSON.stringify([
          { value: 'draft', label: 'Taslak', color: 'gray', icon: '📝', description: 'Taslak faturalar' },
          { value: 'sent', label: 'Gönderildi', color: 'blue', icon: '📤', description: 'Gönderilmiş faturalar' },
          { value: 'paid', label: 'Ödendi', color: 'green', icon: '✅', description: 'Ödenmiş faturalar' },
          { value: 'overdue', label: 'Gecikmiş', color: 'red', icon: '⚠️', description: 'Gecikmiş faturalar' },
          { value: 'cancelled', label: 'İptal', color: 'gray', icon: '❌', description: 'İptal edilmiş faturalar' }
        ])
      };
    }

    function openSettingsModal(groupKey) {
      const modal = document.getElementById('settingsModal');
      const modalTitle = document.getElementById('modalTitle');
      const modalGroup = document.getElementById('modalGroup');
      const modalContent = document.getElementById('modalContent');

      modalTitle.textContent = groupsData[groupKey].name;
      modalGroup.value = groupKey;

      let formContent = '';
      const groupSettings = settingsData[groupKey];

      Object.values(groupSettings).forEach(setting => {
        formContent += `
          <div class="space-y-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 bg-gray-50/50 dark:bg-gray-700/50 rounded-2xl border border-gray-200 dark:border-gray-700">
              <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">${setting.label}</label>
                ${setting.description ? `<p class="text-xs text-gray-500 dark:text-gray-400 mb-1">${setting.description}</p>` : ''}
              </div>
              ${generateFormField(setting)}
            </div>
          </div>
        `;
      });

      modalContent.innerHTML = formContent;
      modal.classList.remove('hidden');
    }

    function generateFormField(setting) {
      const value = setting.value || '';
      
      switch(setting.type) {
        case 'textarea':
          return `
            <textarea name="${setting.key}" rows="3" class="w-full border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400 transition-all duration-200"></textarea>
          `;
        
        case 'boolean':
          return `
            <div class="flex items-center">
              <input type="checkbox" name="${setting.key}" value="1" ${value == '1' ? 'checked' : ''} 
                     class="rounded border-gray-300 text-purple-600 focus:ring-purple-500 dark:bg-gray-600 dark:text-white dark:checked:bg-purple-600">
              <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Aktif</span>
            </div>
          `;
        
        case 'select':
          let options = '';
          if (setting.options) {
            Object.entries(setting.options).forEach(([key, label]) => {
              options += `<option value="${key}" ${value == key ? 'selected' : ''}>${label}</option>`;
            });
          }
          return `
            <select name="${setting.key}" class="w-full border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400 transition-all duration-200">${options}</select>
          `;
        
        case 'number':
          return `
            <input type="number" name="${setting.key}" value="${value}" class="w-full border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400 transition-all duration-200">
          `;
        
        case 'file':
          let fileInput = `
            <div class="space-y-2">
              ${value ? `<div class="text-sm text-green-600 dark:text-green-400">Mevcut dosya: ${value}</div>` : ''}
              <input type="file" name="${setting.key}" class="w-full border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400 transition-all duration-200">
            </div>
          `;
          
          // Logo için özel ölçü bilgisi ekle
          if (setting.key === 'site_logo') {
            fileInput = `
              <div class="space-y-3">
                <div class="p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-xl">
                  <div class="flex items-center text-blue-800 dark:text-blue-200 text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">Tavsiye edilen logo ölçüsü: <strong>160x56 piksel</strong></span>
                  </div>
                  <p class="text-blue-600 dark:text-blue-300 text-xs mt-1">Bu ölçü navbar'da en iyi görünümü sağlar</p>
                </div>
                ${value ? `<div class="text-sm text-green-600 dark:text-green-400">Mevcut dosya: ${value}</div>` : ''}
                <input type="file" name="${setting.key}" class="w-full border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400 transition-all duration-200">
              </div>
            `;
          }
          
          // Favicon için özel ölçü bilgisi ekle
          if (setting.key === 'site_favicon') {
            fileInput = `
              <div class="space-y-3">
                <div class="p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-xl">
                  <div class="flex items-center text-green-800 dark:text-green-200 text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">Tavsiye edilen favicon ölçüsü: <strong>32x32 piksel veya daha büyük</strong></span>
                  </div>
                  <p class="text-green-600 dark:text-green-300 text-xs mt-1">Sistem otomatik olarak uygun boyutlarda favicon oluşturacak</p>
                </div>
                ${value ? `<div class="text-sm text-green-600 dark:text-green-400">Mevcut dosya: ${value}</div>` : ''}
                <input type="file" name="${setting.key}" accept="image/*" class="w-full border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400 transition-all duration-200">
              </div>
            `;
          }
          
          return fileInput;
        
        case 'textarea':
          return `
            <textarea name="${setting.key}" rows="3" class="w-full border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400 transition-all duration-200" placeholder="Metin girin">${value}</textarea>
          `;
        
        case 'email':
          return `
            <input type="email" name="${setting.key}" value="${value}" class="w-full border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400 transition-all duration-200" placeholder="E-posta adresi girin">
          `;
        
        case 'password':
          return `
            <input type="password" name="${setting.key}" value="${value}" class="w-full border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400 transition-all duration-200" placeholder="Şifre girin">
          `;
        
        case 'json':
          return `
            <textarea name="${setting.key}" rows="3" class="w-full border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400 transition-all duration-200"></textarea>
          `;
        
        default:
          return `
            <input type="text" name="${setting.key}" value="${value}" class="w-full border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400 transition-all duration-200">
          `;
      }
    }

    function closeSettingsModal() {
      document.getElementById('settingsModal').classList.add('hidden');
    }

    function exportSettings() {
      const data = JSON.stringify(settingsData, null, 2);
      const blob = new Blob([data], { type: 'application/json' });
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = 'site-settings.json';
      a.click();
      URL.revokeObjectURL(url);
    }

    // Modal dışına tıklandığında kapat
    document.getElementById('settingsModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeSettingsModal();
      }
    });

    // Hizmet Türleri Modal
    function openServiceTypesModal() {
      document.getElementById('serviceTypesModal').classList.remove('hidden');
      loadServiceTypes();
    }

    function closeServiceTypesModal() {
      document.getElementById('serviceTypesModal').classList.add('hidden');
    }

    function loadServiceTypes() {
      fetch('/admin/service-types')
        .then(response => response.json())
        .then(data => {
          const container = document.getElementById('serviceTypesList');
          container.innerHTML = '';
          
          data.forEach(type => {
            container.innerHTML += `
              <div class="flex items-center justify-between p-4 bg-gray-50/50 dark:bg-gray-700/50 rounded-2xl border border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-3">
                  <span class="w-4 h-4 rounded-full" style="background-color: ${type.color}"></span>
                  <span class="font-medium">${type.name}</span>
                  <span class="text-sm text-gray-500 dark:text-gray-400">${type.icon}</span>
                </div>
                <div class="flex space-x-2">
                  <button onclick="editServiceType('${type.id}')" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                  </button>
                  <button onclick="deleteServiceType('${type.id}')" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                  </button>
                </div>
              </div>
            `;
          });
        });
    }

    function addServiceType() {
      const name = document.getElementById('newTypeName').value;
      const icon = document.getElementById('newTypeIcon').value;
      
      if (!name || !icon) {
        alert('Lütfen tür adı ve ikon giriniz');
        return;
      }

      fetch('/admin/service-types', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ name, icon })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          document.getElementById('newTypeName').value = '';
          document.getElementById('newTypeIcon').value = '';
          loadServiceTypes();
        } else {
          alert('Hata: ' + data.message);
        }
      });
    }

    function deleteServiceType(id) {
      if (confirm('Bu hizmet türünü silmek istediğinizden emin misiniz?')) {
        fetch(`/admin/service-types/${id}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            loadServiceTypes();
          } else {
            alert('Hata: ' + data.message);
          }
        });
      }
    }

    // Hizmet türleri modal dışına tıklandığında kapat
    document.getElementById('serviceTypesModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeServiceTypesModal();
      }
    });

    // Servis Durumları Modal
    function openServiceStatusesModal() {
      document.getElementById('serviceStatusesModal').classList.remove('hidden');
      loadServiceStatuses();
    }

    function closeServiceStatusesModal() {
      document.getElementById('serviceStatusesModal').classList.add('hidden');
    }

    function loadServiceStatuses() {
      // Hizmet durumlarını yükle
      const serviceStatuses = JSON.parse(settingsData.service_statuses?.value || '[]');
      const container = document.getElementById('serviceStatusesList');
      container.innerHTML = '';
      
      serviceStatuses.forEach((status, index) => {
        container.innerHTML += `
          <div class="p-4 bg-gray-50/50 dark:bg-gray-700/50 rounded-2xl border border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
              <!-- Durum Değeri (Değiştirilemez) -->
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Durum Değeri</label>
                <input type="text" value="${status.value}" disabled 
                       class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-lg text-gray-500 dark:text-gray-400">
              </div>
              
              <!-- Etiket -->
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Etiket</label>
                <input type="text" value="${status.label || ''}" 
                       onchange="updateServiceStatus('service', ${index}, 'label', this.value)"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-600 dark:text-white">
              </div>
              
              <!-- Renk -->
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Renk</label>
                <select onchange="updateServiceStatus('service', ${index}, 'color', this.value)"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-600 dark:text-white">
                  <option value="green" ${status.color === 'green' ? 'selected' : ''}>Yeşil</option>
                  <option value="red" ${status.color === 'red' ? 'selected' : ''}>Kırmızı</option>
                  <option value="yellow" ${status.color === 'yellow' ? 'selected' : ''}>Sarı</option>
                  <option value="blue" ${status.color === 'blue' ? 'selected' : ''}>Mavi</option>
                  <option value="gray" ${status.color === 'gray' ? 'selected' : ''}>Gri</option>
                  <option value="purple" ${status.color === 'purple' ? 'selected' : ''}>Mor</option>
                  <option value="pink" ${status.color === 'pink' ? 'selected' : ''}>Pembe</option>
                  <option value="indigo" ${status.color === 'indigo' ? 'selected' : ''}>İndigo</option>
                </select>
              </div>
              
              <!-- İkon -->
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">İkon</label>
                <input type="text" value="${status.icon || ''}" 
                       onchange="updateServiceStatus('service', ${index}, 'icon', this.value)"
                       placeholder="📋"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-600 dark:text-white">
              </div>
            </div>
            
            <!-- Açıklama -->
            <div class="mt-4">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Açıklama</label>
              <input type="text" value="${status.description || ''}" 
                     onchange="updateServiceStatus('service', ${index}, 'description', this.value)"
                     class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-600 dark:text-white">
            </div>
            
            <!-- Önizleme -->
            <div class="mt-3 flex items-center space-x-3">
              <span class="text-2xl">${status.icon || '📋'}</span>
              <span class="px-3 py-1 rounded-full text-xs font-medium bg-${status.color}-100 text-${status.color}-800 dark:bg-${status.color}-900/30 dark:text-${status.color}-200">
                ${status.label || status.value}
              </span>
              <span class="w-4 h-4 rounded-full" style="background-color: ${getColorValue(status.color)}"></span>
            </div>
          </div>
        `;
      });

      // Fatura durumlarını yükle
      const invoiceStatuses = JSON.parse(settingsData.invoice_statuses?.value || '[]');
      const invoiceContainer = document.getElementById('invoiceStatusesList');
      invoiceContainer.innerHTML = '';
      
      invoiceStatuses.forEach((status, index) => {
        invoiceContainer.innerHTML += `
          <div class="p-4 bg-gray-50/50 dark:bg-gray-700/50 rounded-2xl border border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
              <!-- Durum Değeri (Değiştirilemez) -->
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Durum Değeri</label>
                <input type="text" value="${status.value}" disabled 
                       class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-lg text-gray-500 dark:text-gray-400">
              </div>
              
              <!-- Etiket -->
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Etiket</label>
                <input type="text" value="${status.label || ''}" 
                       onchange="updateServiceStatus('invoice', ${index}, 'label', this.value)"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-600 dark:text-white">
              </div>
              
              <!-- Renk -->
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Renk</label>
                <select onchange="updateServiceStatus('invoice', ${index}, 'color', this.value)"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-600 dark:text-white">
                  <option value="green" ${status.color === 'green' ? 'selected' : ''}>Yeşil</option>
                  <option value="red" ${status.color === 'red' ? 'selected' : ''}>Kırmızı</option>
                  <option value="yellow" ${status.color === 'yellow' ? 'selected' : ''}>Sarı</option>
                  <option value="blue" ${status.color === 'blue' ? 'selected' : ''}>Mavi</option>
                  <option value="gray" ${status.color === 'gray' ? 'selected' : ''}>Gri</option>
                  <option value="purple" ${status.color === 'purple' ? 'selected' : ''}>Mor</option>
                  <option value="pink" ${status.color === 'pink' ? 'selected' : ''}>Pembe</option>
                  <option value="indigo" ${status.color === 'indigo' ? 'selected' : ''}>İndigo</option>
                </select>
              </div>
              
              <!-- İkon -->
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">İkon</label>
                <input type="text" value="${status.icon || ''}" 
                       onchange="updateServiceStatus('invoice', ${index}, 'color', this.value)"
                       placeholder="📋"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-600 dark:text-white">
              </div>
            </div>
            
            <!-- Açıklama -->
            <div class="mt-4">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Açıklama</label>
              <input type="text" value="${status.description || ''}" 
                     onchange="updateServiceStatus('invoice', ${index}, 'description', this.value)"
                     class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-600 dark:text-white">
            </div>
            
            <!-- Önizleme -->
            <div class="mt-3 flex items-center space-x-3">
              <span class="text-2xl">${status.icon || '📋'}</span>
              <span class="px-3 py-1 rounded-full text-xs font-medium bg-${status.color}-100 text-${status.color}-800 dark:bg-${status.color}-900/30 dark:text-${status.color}-200">
                ${status.label || status.value}
              </span>
              <span class="w-4 h-4 rounded-full" style="background-color: ${getColorValue(status.color)}"></span>
            </div>
          </div>
        `;
      });
    }

    function getColorValue(colorName) {
      const colors = {
        'green': '#10B981',
        'red': '#EF4444',
        'yellow': '#F59E0B',
        'blue': '#3B82F6',
        'gray': '#6B7280',
        'purple': '#8B5CF6',
        'pink': '#EC4899',
        'indigo': '#6366F1'
      };
      return colors[colorName] || '#6B7280';
    }

    // Servis durumları modal dışına tıklandığında kapat
    document.getElementById('serviceStatusesModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeServiceStatusesModal();
      }
    });

    // Durum güncelleme fonksiyonu
    function updateServiceStatus(type, index, field, value) {
      if (type === 'service') {
        if (!settingsData.service_statuses) settingsData.service_statuses = { value: '[]' };
        const serviceStatuses = JSON.parse(settingsData.service_statuses.value || '[]');
        serviceStatuses[index][field] = value;
        settingsData.service_statuses.value = JSON.stringify(serviceStatuses);
      } else if (type === 'invoice') {
        if (!settingsData.invoice_statuses) settingsData.invoice_statuses = { value: '[]' };
        const invoiceStatuses = JSON.parse(settingsData.invoice_statuses.value || '[]');
        invoiceStatuses[index][field] = value;
        settingsData.invoice_statuses.value = JSON.stringify(invoiceStatuses);
      }
      
      // Önizlemeyi güncelle
      loadServiceStatuses();
    }

    // Değişiklikleri kaydet
    function saveServiceStatuses() {
      const formData = new FormData();
      formData.append('service_statuses', settingsData.service_statuses?.value || '[]');
      formData.append('invoice_statuses', settingsData.invoice_statuses?.value || '[]');
      formData.append('group', 'service_statuses');

      fetch('/admin/site-settings/update', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Servis durumları başarıyla güncellendi!');
          closeServiceStatusesModal();
          // Sayfayı yenile
          location.reload();
        } else {
          alert('Hata: ' + (data.message || 'Bilinmeyen hata'));
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Bir hata oluştu. Lütfen tekrar deneyin.');
      });
    }

        // Sayfa yüklendiğinde önizlemeleri göster
    document.addEventListener('DOMContentLoaded', function() {
      loadStatusesPreview();
      loadServiceTypesPreview();
    });

    // Durum önizlemelerini yükle
    function loadStatusesPreview() {
      // Hizmet durumları önizleme
      const serviceStatuses = JSON.parse(settingsData.service_statuses?.value || '[]');
      const servicePreview = document.getElementById('serviceStatusesPreview');
      if (servicePreview) {
        servicePreview.innerHTML = '';
        serviceStatuses.forEach(status => {
          servicePreview.innerHTML += `
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-${status.color}-100 text-${status.color}-800 dark:bg-${status.color}-900/30 dark:text-${status.color}-200">
              ${status.icon || '📋'} ${status.label || status.value}
            </span>
          `;
        });
      }

      // Fatura durumları önizleme
      const invoiceStatuses = JSON.parse(settingsData.invoice_statuses?.value || '[]');
      const invoicePreview = document.getElementById('invoiceStatusesPreview');
      if (invoicePreview) {
        invoicePreview.innerHTML = '';
        invoiceStatuses.forEach(status => {
          invoicePreview.innerHTML += `
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-${status.color}-100 text-${status.color}-800 dark:bg-${status.color}-900/30 dark:text-${status.color}-200">
              ${status.icon || '📋'} ${status.label || status.value}
            </span>
          `;
        });
      }
    }

    // Hizmet türleri önizlemesini yükle
     function loadServiceTypesPreview() {
       fetch('/admin/service-types')
         .then(response => response.json())
         .then(data => {
           const preview = document.getElementById('serviceTypesPreview');
           if (preview) {
             preview.innerHTML = '';
             if (data.length === 0) {
               preview.innerHTML = '<span class="text-sm text-gray-500 dark:text-gray-400">Henüz hizmet türü tanımlanmamış</span>';
             } else {
               data.forEach(type => {
                 preview.innerHTML += `
                   <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">
                     ${type.icon || '🔧'} ${type.name}
                   </span>
                 `;
               });
             }
           }
         })
         .catch(error => {
           console.error('Hizmet türleri yüklenirken hata:', error);
           const preview = document.getElementById('serviceTypesPreview');
           if (preview) {
             preview.innerHTML = '<span class="text-sm text-red-500">Hizmet türleri yüklenemedi</span>';
           }
         });
     }
   </script>
</x-app-layout>
