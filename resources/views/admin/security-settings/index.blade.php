<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Güvenlik Ayarları') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    {{ __('Uygulama güvenlik ayarlarını yapılandırın') }}
                </p>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 rounded-full bg-green-400"></div>
                <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Güvenlik Aktif') }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Security Status Card -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ __('Güvenlik Durumu') }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Mevcut güvenlik yapılandırması') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div id="security-status" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Status will be loaded here via AJAX -->
                    </div>
                </div>
            </div>

            <!-- Security Headers Card -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ __('Güvenlik Başlıkları') }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ __('HTTP güvenlik başlıklarını yapılandırın') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.security-settings.update') }}" class="space-y-6">
                        @csrf
                        
                        <!-- Password Policy Settings -->
                        <div class="space-y-4">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Parola Politikası') }}</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label for="password_min_length" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Minimum Uzunluk') }}</label>
                                    <input type="number" id="password_min_length" name="password_min_length" value="{{ $settings['password_min_length'] ?? 8 }}" min="6" max="50" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Minimum parola uzunluğu') }}</p>
                                </div>
                                <div class="space-y-2">
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" name="password_require_uppercase" value="1" {{ ($settings['password_require_uppercase'] ?? false) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Büyük Harf Zorunlu') }}</span>
                                    </label>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('En az bir büyük harf içermeli') }}</p>
                                </div>
                                <div class="space-y-2">
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" name="password_require_numbers" value="1" {{ ($settings['password_require_numbers'] ?? false) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Rakam Zorunlu') }}</span>
                                    </label>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('En az bir rakam içermeli') }}</p>
                                </div>
                                <div class="space-y-2">
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" name="password_require_symbols" value="1" {{ ($settings['password_require_symbols'] ?? false) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Özel Karakter Zorunlu') }}</span>
                                    </label>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('En az bir özel karakter içermeli') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- HSTS Settings -->
                        <div class="space-y-4">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('HSTS (HTTP Strict Transport Security)') }}</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" name="hsts_enabled" value="1" {{ $settings['hsts_enabled'] ?? false ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('HSTS Aktif') }}</span>
                                    </label>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('HTTPS bağlantılarını zorunlu kılar') }}</p>
                                </div>
                                <div class="space-y-2">
                                    <label for="hsts_max_age" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Max Age (saniye)') }}</label>
                                    <input type="number" id="hsts_max_age" name="hsts_max_age" value="{{ $settings['hsts_max_age'] ?? 31536000 }}" min="300" max="31536000" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('1 yıl = 31536000 saniye') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Frame Options -->
                        <div class="space-y-4">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('X-Frame-Options') }}</h4>
                            <div class="space-y-2">
                                <select name="frame_options" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    <option value="DENY" {{ ($settings['frame_options'] ?? 'DENY') === 'DENY' ? 'selected' : '' }}>{{ __('DENY - Tüm frame\'leri engelle') }}</option>
                                    <option value="SAMEORIGIN" {{ ($settings['frame_options'] ?? 'DENY') === 'SAMEORIGIN' ? 'selected' : '' }}>{{ __('SAMEORIGIN - Sadece aynı origin\'den') }}</option>
                                    <option value="ALLOW-FROM" {{ ($settings['frame_options'] ?? 'DENY') === 'ALLOW-FROM' ? 'selected' : '' }}>{{ __('ALLOW-FROM - Belirli origin\'den') }}</option>
                                </select>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Clickjacking saldırılarına karşı koruma') }}</p>
                            </div>
                        </div>

                        <!-- Content Type Options -->
                        <div class="space-y-4">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('X-Content-Type-Options') }}</h4>
                            <div class="space-y-2">
                                <select name="content_type_options" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    <option value="nosniff" {{ ($settings['content_type_options'] ?? 'nosniff') === 'nosniff' ? 'selected' : '' }}>{{ __('nosniff - MIME type sniffing\'i engelle') }}</option>
                                </select>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('MIME type confusion saldırılarına karşı koruma') }}</p>
                            </div>
                        </div>

                        <!-- Referrer Policy -->
                        <div class="space-y-4">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Referrer-Policy') }}</h4>
                            <div class="space-y-2">
                                <select name="referrer_policy" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    <option value="no-referrer" {{ ($settings['referrer_policy'] ?? 'strict-origin-when-cross-origin') === 'no-referrer' ? 'selected' : '' }}>{{ __('no-referrer - Referrer bilgisini gönderme') }}</option>
                                    <option value="no-referrer-when-downgrade" {{ ($settings['referrer_policy'] ?? 'strict-origin-when-cross-origin') === 'no-referrer-when-downgrade' ? 'selected' : '' }}>{{ __('no-referrer-when-downgrade - HTTPS\'ten HTTP\'ye geçerken gönderme') }}</option>
                                    <option value="origin" {{ ($settings['referrer_policy'] ?? 'strict-origin-when-cross-origin') === 'origin' ? 'selected' : '' }}>{{ __('origin - Sadece origin bilgisini gönder') }}</option>
                                    <option value="origin-when-cross-origin" {{ ($settings['referrer_policy'] ?? 'strict-origin-when-cross-origin') === 'origin-when-cross-origin' ? 'selected' : '' }}>{{ __('origin-when-cross-origin - Cross-origin\'de sadece origin') }}</option>
                                    <option value="same-origin" {{ ($settings['referrer_policy'] ?? 'strict-origin-when-cross-origin') === 'same-origin' ? 'selected' : '' }}>{{ __('same-origin - Aynı origin\'de tam bilgi') }}</option>
                                    <option value="strict-origin" {{ ($settings['referrer_policy'] ?? 'strict-origin-when-cross-origin') === 'strict-origin' ? 'selected' : '' }}>{{ __('strict-origin - Sadece HTTPS origin') }}</option>
                                    <option value="strict-origin-when-cross-origin" {{ ($settings['referrer_policy'] ?? 'strict-origin-when-cross-origin') === 'strict-origin-when-cross-origin' ? 'selected' : '' }}>{{ __('strict-origin-when-cross-origin - Cross-origin\'de HTTPS origin') }}</option>
                                    <option value="unsafe-url" {{ ($settings['referrer_policy'] ?? 'strict-origin-when-cross-origin') === 'unsafe-url' ? 'selected' : '' }}>{{ __('unsafe-url - Tüm bilgileri gönder') }}</option>
                                </select>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Referrer bilgilerinin nasıl paylaşılacağını belirler') }}</p>
                            </div>
                        </div>

                        <!-- Permissions Policy -->
                        <div class="space-y-4">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Permissions-Policy') }}</h4>
                            <div class="space-y-2">
                                <textarea name="permissions_policy" rows="4" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white font-mono text-sm">{{ $settings['permissions_policy'] ?? 'geolocation=(), microphone=(), camera=(), payment=(), usb=(), magnetometer=(), gyroscope=(), accelerometer=(), ambient-light-sensor=(), autoplay=(), encrypted-media=(), fullscreen=(), picture-in-picture=(), publickey-credentials-get=(), sync-xhr=(), clipboard-read=(), clipboard-write=(), display-capture=(), document-domain=(), execution-while-not-rendered=(), execution-while-out-of-viewport=(), focus-without-user-activation=(), cross-origin-isolated=(), identity-credentials-get=(), payment=(), publickey-credentials-create=(), screen-wake-lock=(), web-share=(), xr-spatial-tracking=()' }}</textarea>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Tarayıcı özelliklerinin kullanımını kısıtlar') }}</p>
                            </div>
                        </div>

                        <!-- Content Security Policy -->
                        <div class="space-y-4">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Content-Security-Policy') }}</h4>
                            <div class="space-y-2">
                                <textarea name="csp_policy" rows="4" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white font-mono text-sm">{{ $settings['csp_policy'] ?? "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' data:; connect-src 'self'; frame-ancestors 'none'; base-uri 'self'; form-action 'self';" }}</textarea>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Kaynak yükleme politikalarını belirler') }}</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between pt-6">
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ __('Güvenlik Ayarlarını Kaydet') }}
                            </button>

                            @if (session('status'))
                                <div class="flex items-center space-x-2 px-4 py-2 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 rounded-lg border border-green-200 dark:border-green-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium">{{ session('status') }}</span>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Load security status
        document.addEventListener('DOMContentLoaded', function() {
            loadSecurityStatus();
        });

        function loadSecurityStatus() {
            fetch('{{ route("admin.security-settings.test") }}')
                .then(response => response.json())
                .then(data => {
                    const statusContainer = document.getElementById('security-status');
                    statusContainer.innerHTML = '';
                    
                    Object.entries(data).forEach(([key, value]) => {
                        const statusCard = createStatusCard(key, value);
                        statusContainer.appendChild(statusCard);
                    });
                })
                .catch(error => {
                    console.error('Error loading security status:', error);
                });
        }

        function createStatusCard(key, value) {
            const card = document.createElement('div');
            card.className = 'p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600';
            
            const isActive = value.status === 'active' || value.status === 'enabled';
            const iconColor = isActive ? 'text-green-500' : 'text-red-500';
            const bgColor = isActive ? 'bg-green-100 dark:bg-green-900/20' : 'bg-red-100 dark:bg-red-900/20';
            
            card.innerHTML = `
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 ${bgColor} rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${isActive ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' : 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'}"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">${value.label}</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">${value.description}</p>
                    </div>
                </div>
            `;
            
            return card;
        }
    </script>
</x-app-layout>
