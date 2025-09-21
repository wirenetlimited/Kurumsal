<x-app-layout>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-indigo-50 to-purple-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="max-w-7xl mx-auto p-6 space-y-8">
      <!-- Başlık -->
      <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 rounded-3xl p-8 shadow-2xl">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative flex items-center justify-between">
          <div class="space-y-2">
            <h1 class="text-4xl font-bold text-white drop-shadow-lg">E-posta Şablonları</h1>
            <p class="text-indigo-100 text-lg">Müşterilerinize gönderilen e-posta şablonlarını yönetin</p>
          </div>
          <div class="text-right">
            <div class="text-4xl font-bold">📧</div>
            <div class="text-indigo-100 text-lg">4 Şablon Türü</div>
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

      @if (session('error'))
        <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6">
          <div class="absolute inset-0 bg-gradient-to-br from-red-50/50 to-rose-50/50 dark:from-red-900/20 dark:to-rose-900/20 rounded-2xl"></div>
          <div class="relative flex items-center">
            <div class="w-12 h-12 bg-gradient-to-br from-red-400 to-rose-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <span class="text-red-800 dark:text-red-200 font-semibold text-lg">{{ session('error') }}</span>
          </div>
        </div>
      @endif

      <!-- E-posta Şablonları -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Hoş Geldin E-postası -->
        <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8 hover:shadow-2xl hover:scale-105 transition-all duration-300">
          <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
          <div class="relative">
            <div class="flex items-center justify-between mb-6">
              <div class="flex items-center">
                <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-emerald-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                  <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                  </svg>
                </div>
                <div>
                  <h3 class="text-xl font-bold text-gray-900 dark:text-white">Hoş Geldin E-postası</h3>
                  <p class="text-sm text-gray-600 dark:text-gray-400">Yeni müşteri kaydı</p>
                </div>
              </div>
            </div>
            
            <div class="space-y-4 mb-6">
              <div class="flex items-center text-sm text-gray-700 dark:text-gray-300 p-3 bg-gray-50/50 dark:bg-gray-700/50 rounded-xl">
                <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Müşteri bilgileri ve hizmetler
              </div>
              <div class="flex items-center text-sm text-gray-700 dark:text-gray-300 p-3 bg-gray-50/50 dark:bg-gray-700/50 rounded-xl">
                <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Hesap erişim bilgileri
              </div>
              <div class="flex items-center text-sm text-gray-700 dark:text-gray-300 p-3 bg-gray-50/50 dark:bg-gray-700/50 rounded-xl">
                <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                İletişim bilgileri
              </div>
            </div>

            <div class="flex space-x-3">
              <a href="{{ route('email-templates.welcome') }}" target="_blank" class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 px-6 rounded-2xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 text-center text-sm font-semibold shadow-lg hover:shadow-xl">
                Önizle
              </a>
              <button onclick="sendTestEmail('welcome')" class="flex-1 bg-gradient-to-r from-green-600 to-emerald-600 text-white py-3 px-6 rounded-2xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 text-sm font-semibold shadow-lg hover:shadow-xl">
                Test Gönder
              </button>
            </div>
          </div>
        </div>

        <!-- Fatura E-postası -->
        <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8 hover:shadow-2xl hover:scale-105 transition-all duration-300">
          <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
          <div class="relative">
            <div class="flex items-center justify-between mb-6">
              <div class="flex items-center">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-pink-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                  <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                  </svg>
                </div>
                <div>
                  <h3 class="text-xl font-bold text-gray-900 dark:text-white">Fatura E-postası</h3>
                  <p class="text-sm text-gray-600 dark:text-gray-400">Fatura bildirimi</p>
                </div>
              </div>
            </div>
            
            <div class="space-y-4 mb-6">
              <div class="flex items-center text-sm text-gray-700 dark:text-gray-300 p-3 bg-gray-50/50 dark:bg-gray-700/50 rounded-xl">
                <svg class="w-5 h-5 mr-3 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Fatura detayları ve tutarı
              </div>
              <div class="flex items-center text-sm text-gray-700 dark:text-gray-300 p-3 bg-gray-50/50 dark:bg-gray-700/50 rounded-xl">
                <svg class="w-5 h-5 mr-3 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Vade tarihi ve uyarılar
              </div>
              <div class="flex items-center text-sm text-gray-700 dark:text-gray-300 p-3 bg-gray-50/50 dark:bg-gray-700/50 rounded-xl">
                <svg class="w-5 h-5 mr-3 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Ödeme seçenekleri
              </div>
            </div>

            <div class="flex space-x-3">
              <a href="{{ route('email-templates.invoice') }}" target="_blank" class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 px-6 rounded-2xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 text-center text-sm font-semibold shadow-lg hover:shadow-xl">
                Önizle
              </a>
              <button onclick="sendTestEmail('invoice')" class="flex-1 bg-gradient-to-r from-green-600 to-emerald-600 text-white py-3 px-6 rounded-2xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 text-sm font-semibold shadow-lg hover:shadow-xl">
                Test Gönder
              </button>
            </div>
          </div>
        </div>

        <!-- Teklif E-postası -->
        <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8 hover:shadow-2xl hover:scale-105 transition-all duration-300">
          <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
          <div class="relative">
            <div class="flex items-center justify-between mb-6">
              <div class="flex items-center">
                <div class="w-16 h-16 bg-gradient-to-br from-teal-400 to-cyan-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                  <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                  </svg>
                </div>
                <div>
                  <h3 class="text-xl font-bold text-gray-900 dark:text-white">Teklif E-postası</h3>
                  <p class="text-sm text-gray-600 dark:text-gray-400">Fiyat teklifi</p>
                </div>
              </div>
            </div>
            
            <div class="space-y-4 mb-6">
              <div class="flex items-center text-sm text-gray-700 dark:text-gray-300 p-3 bg-gray-50/50 dark:bg-gray-700/50 rounded-xl">
                <svg class="w-5 h-5 mr-3 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Teklif detayları ve fiyatı
              </div>
              <div class="flex items-center text-sm text-gray-700 dark:text-gray-300 p-3 bg-gray-50/50 dark:bg-gray-700/50 rounded-xl">
                <svg class="w-5 h-5 mr-3 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Geçerlilik süresi
              </div>
              <div class="flex items-center text-sm text-gray-700 dark:text-gray-300 p-3 bg-gray-50/50 dark:bg-gray-700/50 rounded-xl">
                <svg class="w-5 h-5 mr-3 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Kabul etme butonu
              </div>
            </div>

            <div class="flex space-x-3">
              <a href="{{ route('email-templates.quote') }}" target="_blank" class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 px-6 rounded-2xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 text-center text-sm font-semibold shadow-lg hover:shadow-xl">
                Önizle
              </a>
              <button onclick="sendTestEmail('quote')" class="flex-1 bg-gradient-to-r from-green-600 to-emerald-600 text-white py-3 px-6 rounded-2xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 text-sm font-semibold shadow-lg hover:shadow-xl">
                Test Gönder
              </button>
            </div>
          </div>
        </div>

        <!-- Hizmet Süresi Dolma Uyarısı -->
        <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8 hover:shadow-2xl hover:scale-105 transition-all duration-300">
          <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
          <div class="relative">
            <div class="flex items-center justify-between mb-6">
              <div class="flex items-center">
                <div class="w-16 h-16 bg-gradient-to-br from-orange-400 to-red-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                  <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                  </svg>
                </div>
                <div>
                  <h3 class="text-xl font-bold text-gray-900 dark:text-white">Süre Dolma Uyarısı</h3>
                  <p class="text-sm text-gray-600 dark:text-gray-400">Hizmet yenileme</p>
                </div>
              </div>
            </div>
            
            <div class="space-y-4 mb-6">
              <div class="flex items-center text-sm text-gray-700 dark:text-gray-300 p-3 bg-gray-50/50 dark:bg-gray-700/50 rounded-xl">
                <svg class="w-5 h-5 mr-3 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Hizmet türü ve bitiş tarihi
              </div>
              <div class="flex items-center text-sm text-gray-700 dark:text-gray-300 p-3 bg-gray-50/50 dark:bg-gray-700/50 rounded-xl">
                <svg class="w-5 h-5 mr-3 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Kalan gün sayısı
              </div>
              <div class="flex items-center text-sm text-gray-700 dark:text-gray-300 p-3 bg-gray-50/50 dark:bg-gray-700/50 rounded-xl">
                <svg class="w-5 h-5 mr-3 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Yenileme seçenekleri
              </div>
            </div>

            <div class="flex space-x-3">
              <a href="{{ route('email-templates.service-expiry') }}" target="_blank" class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 px-6 rounded-2xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 text-center text-sm font-semibold shadow-lg hover:shadow-xl">
                Önizle
              </a>
              <button onclick="sendTestEmail('service-expiry')" class="flex-1 bg-gradient-to-r from-green-600 to-emerald-600 text-white py-3 px-6 rounded-2xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 text-sm font-semibold shadow-lg hover:shadow-xl">
                Test Gönder
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Test E-posta Gönderme Modal -->
      <div id="testEmailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 backdrop-blur-sm">
        <div class="flex items-center justify-center min-h-screen p-4">
          <div class="bg-white/95 dark:bg-gray-800/95 backdrop-blur-md rounded-3xl shadow-2xl max-w-md w-full border border-white/20 dark:border-gray-700/50">
            <div class="p-8">
              <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Test E-postası Gönder</h3>
              
              <form id="testEmailForm" method="POST" action="{{ route('email-templates.send-test') }}">
                @csrf
                <input type="hidden" id="templateType" name="template" value="">
                
                <div class="mb-6">
                  <label for="testEmail" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">E-posta Adresi</label>
                  <input type="email" id="testEmail" name="email" required 
                         class="w-full border border-gray-300 dark:border-gray-600 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 transition-all duration-200"
                         placeholder="test@example.com">
                </div>
                
                <div class="flex space-x-4">
                  <button type="button" onclick="closeTestModal()" 
                          class="flex-1 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 py-3 px-6 rounded-2xl hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors font-semibold">
                    İptal
                  </button>
                  <button type="submit" 
                          class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 px-6 rounded-2xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 font-semibold shadow-lg">
                    Gönder
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    function sendTestEmail(template) {
      document.getElementById('templateType').value = template;
      document.getElementById('testEmailModal').classList.remove('hidden');
    }

    function closeTestModal() {
      document.getElementById('testEmailModal').classList.add('hidden');
    }

    // Modal dışına tıklandığında kapat
    document.getElementById('testEmailModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeTestModal();
      }
    });
  </script>
</x-app-layout>
