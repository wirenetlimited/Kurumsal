<x-app-layout>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="max-w-7xl mx-auto p-6 space-y-8">
      <!-- Başlık -->
      <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 rounded-3xl p-8 shadow-2xl">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative flex items-center justify-between">
          <div class="space-y-2">
            <h1 class="text-4xl font-bold text-white drop-shadow-lg">E-posta Ayarları</h1>
            <p class="text-blue-100 text-lg">SMTP yapılandırması ve e-posta yönetimi</p>
          </div>
          <div class="text-right">
            <div class="text-4xl font-bold">📧</div>
            <div class="text-blue-100 text-lg">{{ $statistics['total_sent'] }} Toplam Gönderim</div>
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

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- SMTP Ayarları -->
        <div class="lg:col-span-2 space-y-8">
          <!-- SMTP Yapılandırması -->
          <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8">
            <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
            <div class="relative">
              <div class="flex items-center justify-between mb-8">
                <div>
                  <h2 class="text-2xl font-bold text-gray-900 dark:text-white">SMTP Yapılandırması</h2>
                  <p class="text-gray-600 dark:text-gray-400 mt-2 text-lg">E-posta sunucu ayarlarını yapılandırın</p>
                </div>
                <div class="flex space-x-3">
                  <button type="button" onclick="loadPreset('gmail')" class="bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 rounded-2xl text-sm font-semibold hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                    Gmail
                  </button>
                  <button type="button" onclick="loadPreset('outlook')" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-2xl text-sm font-semibold hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                    Outlook
                  </button>
                  <button type="button" onclick="loadPreset('custom')" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-2xl text-sm font-semibold hover:from-gray-600 hover:to-gray-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                    Özel
                  </button>
                </div>
              </div>

              <form method="POST" action="{{ route('admin.email-settings.update-smtp') }}" class="space-y-6" id="smtp-form">
                @csrf
                
                <!-- Mail Driver -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">E-posta Sürücüsü</label>
                  <select name="mail_mailer" id="mail_mailer" class="w-full border border-gray-300 dark:border-gray-600 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 transition-all duration-200">
                    <option value="log" {{ $settings['mail_mailer'] == 'log' ? 'selected' : '' }}>Log (Test için)</option>
                    <option value="smtp" {{ $settings['mail_mailer'] == 'smtp' ? 'selected' : '' }}>SMTP</option>
                    <option value="mailgun">Mailgun</option>
                    <option value="ses">Amazon SES</option>
                    <option value="postmark">Postmark</option>
                  </select>
                </div>

                <!-- SMTP Settings (conditional) -->
                <div id="smtp_settings" class="space-y-6 {{ $settings['mail_mailer'] == 'smtp' ? '' : 'hidden' }}">
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">SMTP Sunucu</label>
                      <input type="text" name="mail_host" value="{{ $settings['mail_host'] }}" 
                             class="w-full border border-gray-300 dark:border-gray-600 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 transition-all duration-200"
                             placeholder="smtp.gmail.com" required>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Port</label>
                      <input type="number" name="mail_port" value="{{ $settings['mail_port'] }}" 
                             class="w-full border border-gray-300 dark:border-gray-600 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 transition-all duration-200"
                             placeholder="587" required>
                    </div>
                  </div>

                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Kullanıcı Adı</label>
                      <input type="text" name="mail_username" value="{{ $settings['mail_username'] }}" 
                             class="w-full border border-gray-300 dark:border-gray-600 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 transition-all duration-200"
                             placeholder="your-email@gmail.com" required>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Şifre</label>
                      <input type="password" name="mail_password" 
                             class="w-full border border-gray-300 dark:border-gray-600 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 transition-all duration-200"
                             placeholder="{{ $settings['mail_password'] ? 'Mevcut şifre korunuyor' : 'App Password' }}">
                      @if($settings['mail_password'])
                        <p class="text-xs text-green-600 dark:text-green-400 mt-2 flex items-center">
                          <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                          </svg>
                          Şifre güvenli şekilde şifrelenmiş olarak saklanıyor
                        </p>
                      @endif
                      <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Şifreyi değiştirmek istemiyorsanız boş bırakın</p>
                    </div>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Şifreleme</label>
                    <select name="mail_encryption" class="w-full border border-gray-300 dark:border-gray-600 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                      <option value="tls" {{ $settings['mail_encryption'] == 'tls' ? 'selected' : '' }}>TLS</option>
                      <option value="ssl" {{ $settings['mail_encryption'] == 'ssl' ? 'selected' : '' }}>SSL</option>
                    </select>
                  </div>
                </div>

                <!-- From Settings -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Gönderen E-posta</label>
                    <input type="email" name="mail_from_address" value="{{ $settings['mail_from_address'] }}" 
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 transition-all duration-200"
                           placeholder="info@whkurumsal.com" required>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Gönderen Adı</label>
                    <input type="text" name="mail_from_name" value="{{ $settings['mail_from_name'] }}" 
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 transition-all duration-200"
                           placeholder="WH Kurumsal" required>
                  </div>
                </div>

                <div class="flex justify-end pt-6">
                  <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-3 rounded-2xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl">
                    Ayarları Kaydet
                  </button>
                </div>
              </form>
            </div>
          </div>

          <!-- Test E-posta Gönderme -->
          <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8">
            <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
            <div class="relative">
              <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Test E-posta Gönder</h2>
              <p class="text-gray-600 dark:text-gray-400 mb-8 text-lg">SMTP ayarlarınızı test etmek için bir e-posta gönderin</p>

              <form method="POST" action="{{ route('admin.email-settings.send-test') }}" class="space-y-6" id="test-form">
                @csrf
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Test E-posta Adresi</label>
                  <input type="email" name="test_email" required 
                         class="w-full border border-gray-300 dark:border-gray-600 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 transition-all duration-200"
                         placeholder="test@example.com">
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Konu</label>
                  <input type="text" name="test_subject" required 
                         class="w-full border border-gray-300 dark:border-gray-600 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 transition-all duration-200"
                         placeholder="SMTP Test E-postası">
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Mesaj</label>
                  <textarea name="test_message" rows="4" required 
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 transition-all duration-200"
                            placeholder="Bu bir test e-postasıdır. SMTP ayarlarınız başarıyla çalışıyor!"></textarea>
                </div>

                <div class="flex justify-end pt-6">
                  <button type="submit" class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-8 py-3 rounded-2xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl" id="test-submit-btn">
                    Test E-postası Gönder
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- İstatistikler ve Bilgiler -->
        <div class="space-y-8">
          <!-- E-posta İstatistikleri -->
          <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8">
            <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
            <div class="relative">
              <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">E-posta İstatistikleri</h2>
              
              <div class="space-y-6">
                @if($statistics['total_sent'] > 0)
                  <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl border border-blue-200/50 dark:border-blue-700/50">
                    <div>
                      <div class="text-sm text-blue-600 dark:text-blue-400 font-medium">Başarılı Gönderim</div>
                      <div class="text-3xl font-bold text-blue-900 dark:text-blue-100">{{ $statistics['successful'] }}%</div>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                      <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                      </svg>
                    </div>
                  </div>

                  <div class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-2xl border border-green-200/50 dark:border-green-700/50">
                    <div>
                      <div class="text-sm text-green-600 dark:text-green-400 font-medium">Bugün Gönderilen</div>
                      <div class="text-3xl font-bold text-green-900 dark:text-green-100">{{ $statistics['today_sent'] }}</div>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                      <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                      </svg>
                    </div>
                  </div>

                  <div class="flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-2xl border border-purple-200/50 dark:border-purple-700/50">
                    <div>
                      <div class="text-sm text-purple-600 dark:text-purple-400 font-medium">Bu Hafta</div>
                      <div class="text-3xl font-bold text-purple-900 dark:text-purple-100">{{ $statistics['this_week'] }}</div>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                      <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                      </svg>
                    </div>
                  </div>

                  <div class="flex items-center justify-between p-4 bg-gradient-to-r from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 rounded-2xl border border-orange-200/50 dark:border-orange-700/50">
                    <div>
                      <div class="text-sm text-orange-600 dark:text-orange-400 font-medium">Bu Ay</div>
                      <div class="text-3xl font-bold text-orange-900 dark:text-orange-100">{{ $statistics['this_month'] }}</div>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-red-600 rounded-2xl flex items-center justify-center shadow-lg">
                      <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                      </svg>
                    </div>
                  </div>
                @else
                  <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gradient-to-br from-gray-300 to-gray-400 dark:from-gray-600 dark:to-gray-700 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                      <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                      </svg>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 text-xl font-semibold mb-2">Henüz e-posta gönderilmedi</p>
                    <p class="text-gray-500 dark:text-gray-500 text-lg">Test e-postası göndererek başlayın</p>
                  </div>
                @endif
              </div>
            </div>
          </div>

          <!-- SMTP Preset Bilgileri -->
          <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 p-8">
            <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
            <div class="relative">
              <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">SMTP Preset Bilgileri</h2>
              
              <div class="space-y-6">
                <div class="p-4 bg-gradient-to-r from-red-50 to-rose-50 dark:from-red-900/20 dark:to-rose-900/20 rounded-2xl border border-red-200/50 dark:border-red-700/50">
                  <h3 class="font-semibold text-red-900 dark:text-red-100 text-lg mb-2">Gmail</h3>
                  <p class="text-sm text-red-700 dark:text-red-300 mb-3">App Password gerekli. 2FA aktif olmalı.</p>
                  <div class="text-xs text-red-600 dark:text-red-400 space-y-1">
                    <div><strong>Host:</strong> smtp.gmail.com</div>
                    <div><strong>Port:</strong> 587</div>
                    <div><strong>Encryption:</strong> TLS</div>
                  </div>
                </div>

                <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl border border-blue-200/50 dark:border-blue-700/50">
                  <h3 class="font-semibold text-blue-900 dark:text-blue-100 text-lg mb-2">Outlook/Hotmail</h3>
                  <p class="text-sm text-blue-700 dark:text-blue-300 mb-3">Normal şifre ile çalışır.</p>
                  <div class="text-xs text-blue-600 dark:text-blue-400 space-y-1">
                    <div><strong>Host:</strong> smtp-mail.outlook.com</div>
                    <div><strong>Port:</strong> 587</div>
                    <div><strong>Encryption:</strong> TLS</div>
                  </div>
                </div>

                <div class="p-4 bg-gradient-to-r from-gray-50 to-slate-50 dark:from-gray-700 dark:to-slate-700 rounded-2xl border border-gray-200/50 dark:border-gray-600/50">
                  <h3 class="font-semibold text-gray-900 dark:text-white text-lg mb-2">Özel SMTP</h3>
                  <p class="text-sm text-gray-700 dark:text-gray-300 mb-3">Kendi sunucu bilgilerinizi girin.</p>
                  <div class="text-xs text-gray-600 dark:text-gray-400 space-y-1">
                    <div><strong>Host:</strong> smtp.yourdomain.com</div>
                    <div><strong>Port:</strong> 587 veya 465</div>
                    <div><strong>Encryption:</strong> TLS veya SSL</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // SMTP Preset yükleme
    const presets = {
      gmail: {
        host: 'smtp.gmail.com',
        port: 587,
        encryption: 'tls'
      },
      outlook: {
        host: 'smtp-mail.outlook.com',
        port: 587,
        encryption: 'tls'
      },
      custom: {
        host: '',
        port: 587,
        encryption: 'tls'
      }
    };

    function loadPreset(presetName) {
      const preset = presets[presetName];
      if (preset) {
        document.querySelector('select[name="mail_mailer"]').value = 'smtp';
        document.querySelector('input[name="mail_host"]').value = preset.host;
        document.querySelector('input[name="mail_port"]').value = preset.port;
        document.querySelector('select[name="mail_encryption"]').value = preset.encryption;
        
        // SMTP settings'i göster
        document.getElementById('smtp_settings').classList.remove('hidden');
        
        // Form validation'ı güncelle
        updateFormValidation();
      }
    }

    // Mail driver değiştiğinde SMTP settings'i göster/gizle
    document.getElementById('mail_mailer').addEventListener('change', function() {
      const smtpSettings = document.getElementById('smtp_settings');
      if (this.value === 'smtp') {
        smtpSettings.classList.remove('hidden');
      } else {
        smtpSettings.classList.add('hidden');
      }
      updateFormValidation();
    });

    // Form validation'ı güncelle
    function updateFormValidation() {
      const mailer = document.querySelector('select[name="mail_mailer"]').value;
      const smtpInputs = document.querySelectorAll('#smtp_settings input[required]');
      
      smtpInputs.forEach(input => {
        if (mailer === 'smtp') {
          input.required = true;
        } else {
          input.required = false;
        }
      });
    }

    // Test form submit butonunu devre dışı bırak
    document.getElementById('test-form').addEventListener('submit', function() {
      const submitBtn = document.getElementById('test-submit-btn');
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Gönderiliyor...';
    });

    // Sayfa yüklendiğinde form validation'ı güncelle
    document.addEventListener('DOMContentLoaded', function() {
      updateFormValidation();
    });
  </script>
</x-app-layout>
