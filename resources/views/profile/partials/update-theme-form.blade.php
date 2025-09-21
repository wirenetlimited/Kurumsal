<section>
    <form method="post" action="{{ route('profile.theme') }}" class="space-y-6">
        @csrf
        
        <!-- Theme Selection -->
        <div class="space-y-4">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ __('Tema Seçimi') }}
            </label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Light Theme Option -->
                <label class="relative cursor-pointer">
                    <input 
                        type="radio" 
                        name="theme" 
                        value="light" 
                        class="sr-only" 
                        {{ (old('theme', auth()->user()->theme ?? 'light') === 'light') ? 'checked' : '' }}
                    />
                    <div class="p-4 border-2 rounded-lg transition-all duration-200 hover:shadow-md {{ (old('theme', auth()->user()->theme ?? 'light') === 'light') ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500' }}">
                        <div class="flex items-center space-x-3">
                            <div class="w-4 h-4 rounded-full border-2 {{ (old('theme', auth()->user()->theme ?? 'light') === 'light') ? 'border-blue-500 bg-blue-500' : 'border-gray-300 dark:border-gray-500' }}"></div>
                            <div class="flex-1">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0V4a4 4 0 118 0v6zM3 8a1 1 0 00-1 1v1a1 1 0 102 0V9a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ __('Açık Tema') }}</span>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Parlak ve temiz görünüm') }}</p>
                            </div>
                        </div>
                    </div>
                </label>

                <!-- Dark Theme Option -->
                <label class="relative cursor-pointer">
                    <input 
                        type="radio" 
                        name="theme" 
                        value="dark" 
                        class="sr-only" 
                        {{ (old('theme', auth()->user()->theme ?? 'light') === 'dark') ? 'checked' : '' }}
                    />
                    <div class="p-4 border-2 rounded-lg transition-all duration-200 hover:shadow-md {{ (old('theme', auth()->user()->theme ?? 'light') === 'dark') ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500' }}">
                        <div class="flex items-center space-x-3">
                            <div class="w-4 h-4 rounded-full border-2 {{ (old('theme', auth()->user()->theme ?? 'light') === 'dark') ? 'border-blue-500 bg-blue-500' : 'border-gray-300 dark:border-gray-500' }}"></div>
                            <div class="flex-1">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                                    </svg>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ __('Koyu Tema') }}</span>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Göz yormayan koyu görünüm') }}</p>
                            </div>
                        </div>
                    </div>
                </label>
            </div>
            @error('theme')
                <p class="text-sm text-red-600 dark:text-red-400 flex items-center space-x-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ $message }}</span>
                </p>
            @enderror
        </div>

        <!-- Accent Color Selection -->
        <div class="space-y-4">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ __('Vurgu Rengi') }}
            </label>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @php($currentColor = old('theme_color', auth()->user()->theme_color ?? 'blue'))
                
                <!-- Blue -->
                <label class="relative cursor-pointer">
                    <input type="radio" name="theme_color" value="blue" class="sr-only" {{ $currentColor === 'blue' ? 'checked' : '' }} />
                    <div class="p-3 border-2 rounded-lg transition-all duration-200 hover:shadow-md {{ $currentColor === 'blue' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500' }}">
                        <div class="flex flex-col items-center space-y-2">
                            <div class="w-8 h-8 bg-blue-500 rounded-full border-2 {{ $currentColor === 'blue' ? 'border-blue-600' : 'border-gray-300 dark:border-gray-500' }}"></div>
                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ __('Mavi') }}</span>
                        </div>
                    </div>
                </label>

                <!-- Green -->
                <label class="relative cursor-pointer">
                    <input type="radio" name="theme_color" value="green" class="sr-only" {{ $currentColor === 'green' ? 'checked' : '' }} />
                    <div class="p-3 border-2 rounded-lg transition-all duration-200 hover:shadow-md {{ $currentColor === 'green' ? 'border-green-500 bg-green-50 dark:bg-green-900/20' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500' }}">
                        <div class="flex flex-col items-center space-y-2">
                            <div class="w-8 h-8 bg-green-500 rounded-full border-2 {{ $currentColor === 'green' ? 'border-green-600' : 'border-gray-300 dark:border-gray-500' }}"></div>
                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ __('Yeşil') }}</span>
                        </div>
                    </div>
                </label>

                <!-- Purple -->
                <label class="relative cursor-pointer">
                    <input type="radio" name="theme_color" value="purple" class="sr-only" {{ $currentColor === 'purple' ? 'checked' : '' }} />
                    <div class="p-3 border-2 rounded-lg transition-all duration-200 hover:shadow-md {{ $currentColor === 'purple' ? 'border-purple-500 bg-purple-50 dark:bg-purple-900/20' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500' }}">
                        <div class="flex flex-col items-center space-y-2">
                            <div class="w-8 h-8 bg-purple-500 rounded-full border-2 {{ $currentColor === 'purple' ? 'border-purple-600' : 'border-gray-300 dark:border-gray-500' }}"></div>
                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ __('Mor') }}</span>
                        </div>
                    </div>
                </label>

                <!-- Orange -->
                <label class="relative cursor-pointer">
                    <input type="radio" name="theme_color" value="orange" class="sr-only" {{ $currentColor === 'orange' ? 'checked' : '' }} />
                    <div class="p-3 border-2 rounded-lg transition-all duration-200 hover:shadow-md {{ $currentColor === 'orange' ? 'border-orange-500 bg-orange-50 dark:bg-orange-900/20' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500' }}">
                        <div class="flex flex-col items-center space-y-2">
                            <div class="w-8 h-8 bg-orange-500 rounded-full border-2 {{ $currentColor === 'orange' ? 'border-orange-600' : 'border-gray-300 dark:border-gray-500' }}"></div>
                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ __('Turuncu') }}</span>
                        </div>
                    </div>
                </label>
            </div>
            @error('theme_color')
                <p class="text-sm text-red-600 dark:text-red-400 flex items-center space-x-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ $message }}</span>
                </p>
            @enderror
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between pt-4">
            <button 
                type="submit" 
                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                </svg>
                {{ __('Temayı Güncelle') }}
            </button>

            <!-- Quick Theme Toggle Info -->
            <div class="text-sm text-gray-600 dark:text-gray-400 text-right">
                <p class="font-medium mb-1">{{ __('Hızlı Tema Değiştirme') }}</p>
                <p>{{ __('Navigation bar\'daki tema butonunu kullanarak anında tema değiştirebilirsiniz.') }}</p>
            </div>

            @if (session('status') === 'Tema güncellendi')
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
                    <span class="text-sm font-medium">{{ __('Tema başarıyla güncellendi!') }}</span>
                </div>
            @endif
        </div>
    </form>
</section>


