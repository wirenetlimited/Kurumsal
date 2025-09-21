<section>
    <form method="post" action="{{ route('profile.dashboard-title') }}" class="space-y-6">
        @csrf
        
        <!-- Dashboard Title Field -->
        <div class="space-y-2">
            <label for="dashboard_title" class="flex items-center space-x-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                <span>{{ __('Dashboard Başlığı') }}</span>
            </label>
            <div class="relative">
                <input 
                    id="dashboard_title" 
                    name="dashboard_title" 
                    type="text" 
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white dark:focus:ring-purple-400 dark:focus:border-purple-400 transition-colors duration-200" 
                    value="{{ old('dashboard_title', auth()->user()->dashboard_title) }}" 
                    required 
                    autofocus 
                    autocomplete="dashboard_title"
                    placeholder="{{ __('Dashboard başlığınızı girin (örn: WH Kurumsal)') }}"
                />
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
            </div>
            @error('dashboard_title')
                <p class="text-sm text-red-600 dark:text-red-400 flex items-center space-x-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ $message }}</span>
                </p>
            @enderror
        </div>

        <!-- Preview Section -->
        <div class="p-4 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                <div>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Önizleme') }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ __('Tarayıcı sekmesinde görünecek başlık:') }}
                        <span class="font-medium text-gray-900 dark:text-gray-100" id="title-preview">
                            {{ old('dashboard_title', auth()->user()->dashboard_title) ?: 'WH | Kurumsal Hizmetler' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between pt-4">
            <button 
                type="submit" 
                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ __('Başlığı Güncelle') }}
            </button>

            @if (session('status') === 'dashboard-title-updated')
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95"
                    x-init="setTimeout(() => show = false, 3000)"
                    class="flex items-center space-x-2 px-4 py-2 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 rounded-lg border border-green-200 dark:border-green-800"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-sm font-medium">{{ __('Başlık başarıyla güncellendi!') }}</span>
                </div>
            @endif
        </div>
    </form>

    <script>
        // Live preview functionality
        document.getElementById('dashboard_title').addEventListener('input', function() {
            const preview = document.getElementById('title-preview');
            const value = this.value.trim();
            preview.textContent = value || 'WH | Kurumsal Hizmetler';
        });
    </script>
</section>
