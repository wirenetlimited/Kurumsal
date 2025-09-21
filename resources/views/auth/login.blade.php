<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center space-x-3 mb-4">
            <h1 class="text-4xl font-black text-gray-900 dark:text-white">Hoş Geldiniz</h1>
            <div class="w-3 h-3 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full animate-pulse"></div>
        </div>
        <p class="text-xl text-gray-600 dark:text-gray-400 font-medium">Hesabınıza giriş yapın</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="group">
            <x-input-label for="email" :value="__('E-posta')" class="text-gray-700 dark:text-gray-300 text-base font-semibold mb-3 block group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300" />
            <div class="relative">
                <x-text-input id="email" 
                              class="block w-full px-6 py-4 border-2 border-gray-200 dark:border-gray-600 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 dark:bg-gray-800/50 dark:text-white transition-all duration-300 group-hover:border-blue-300 dark:group-hover:border-blue-500 bg-white/80 backdrop-blur-sm" 
                              type="email" 
                              name="email" 
                              :value="old('email')" 
                              required 
                              autofocus 
                              autocomplete="username" 
                              placeholder="ornek@email.com" />
                
                <!-- Icon -->
                <div class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                    </div>
                </div>
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-3" />
        </div>

        <!-- Password -->
        <div class="group">
            <x-input-label for="password" :value="__('Şifre')" class="text-gray-700 dark:text-gray-300 text-base font-semibold mb-3 block group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300" />
            <div class="relative">
                <x-text-input id="password" 
                              class="block w-full px-6 py-4 border-2 border-gray-200 dark:border-gray-600 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 dark:bg-gray-800/50 dark:text-white transition-all duration-300 group-hover:border-blue-300 dark:group-hover:border-blue-500 bg-white/80 backdrop-blur-sm" 
                              type="password"
                          name="password"
                          required 
                          autocomplete="current-password" 
                          placeholder="••••••••" />
                
                <!-- Icon -->
                <div class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                </div>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-3" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                <input id="remember_me" 
                       type="checkbox" 
                       class="w-5 h-5 rounded-lg border-2 border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:ring-4 focus:ring-blue-500/20 dark:focus:ring-blue-600/20 dark:focus:ring-offset-gray-800 transition-all duration-300 group-hover:border-blue-400" 
                       name="remember">
                <span class="ms-3 text-base text-gray-700 dark:text-gray-300 font-medium group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300">{{ __('Beni Hatırla') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-base text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-all duration-300 font-semibold hover:underline decoration-2 underline-offset-4" href="{{ route('password.request') }}">
                    {{ __('Şifremi Unuttum?') }}
                </a>
            @endif
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full justify-center py-4 px-8 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 hover:from-blue-700 hover:via-indigo-700 hover:to-purple-700 text-white font-bold text-lg rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-1 hover:scale-[1.02]">
                {{ __('Giriş Yap') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Demo Account Info -->
    <div class="mt-6 p-4 bg-gradient-to-r from-blue-50/80 via-indigo-50/80 to-purple-50/80 dark:from-blue-900/30 dark:via-indigo-900/30 dark:to-purple-900/30 rounded-2xl border border-blue-200/50 dark:border-blue-800/50 backdrop-blur-sm hover:shadow-lg transition-all duration-300 transform hover:scale-[1.02]">
        <h3 class="text-sm font-bold text-blue-800 dark:text-blue-300 mb-3 flex items-center">
            <div class="w-6 h-6 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg flex items-center justify-center mr-2 shadow-lg">
                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            {{ __('Demo Hesap Bilgileri') }}
        </h3>
        <div class="text-xs text-blue-700 dark:text-blue-400 space-y-2">
            <div class="flex items-center space-x-2 p-2 bg-white/50 dark:bg-blue-900/20 rounded-lg border border-blue-200/30 dark:border-blue-700/30 hover:bg-white/70 dark:hover:bg-blue-900/30 transition-all duration-300">
                <div class="w-2 h-2 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full animate-pulse"></div>
                <span><strong>Demo:</strong> demo@example.com / demo123</span>
            </div>
            <div class="flex items-center space-x-2 p-2 bg-white/50 dark:bg-blue-900/20 rounded-lg border border-blue-200/30 dark:border-blue-700/30 hover:bg-white/70 dark:hover:bg-blue-900/30 transition-all duration-300">
                <div class="w-2 h-2 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full animate-pulse" style="animation-delay: 1s;"></div>
                <span><strong>Admin:</strong> admin@whkurumsal.com / admin123</span>
            </div>
        </div>
    </div>
</x-guest-layout>
