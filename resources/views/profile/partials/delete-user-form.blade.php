<section>
    <form method="post" action="{{ route('profile.destroy') }}" class="space-y-6">
        @csrf
        @method('delete')

        <!-- Warning Message -->
        <div class="p-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
            <div class="flex items-start space-x-3">
                <svg class="w-6 h-6 text-red-600 dark:text-red-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-red-900 dark:text-red-100">
                        {{ __('Hesabınızı Kalıcı Olarak Silin') }}
                    </h3>
                    <p class="text-sm text-red-700 dark:text-red-300 mt-2">
                        {{ __('Hesabınız silindikten sonra, tüm verileriniz ve kaynaklarınız kalıcı olarak silinecektir. Bu işlem geri alınamaz.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Confirmation Checkbox -->
        <div class="space-y-3">
            <label class="flex items-start space-x-3 cursor-pointer">
                <input 
                    type="checkbox" 
                    name="user_delete_confirmation" 
                    value="1" 
                    class="mt-1 w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                    required
                />
                <div class="flex-1">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Hesabımı silmek istediğimi onaylıyorum') }}
                    </span>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        {{ __('Bu işlem geri alınamaz ve tüm verileriniz kalıcı olarak silinecektir.') }}
                    </p>
                </div>
            </label>
        </div>

        <!-- Password Confirmation -->
        <div class="space-y-2">
            <label for="password" class="flex items-center space-x-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                <span>{{ __('Şifrenizi Girin') }}</span>
            </label>
            <div class="relative">
                <input 
                    id="password" 
                    name="password" 
                    type="password" 
                    class="w-full px-4 py-3 border border-red-300 dark:border-red-700 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white dark:focus:ring-red-400 dark:focus:border-red-400 transition-colors duration-200" 
                    required 
                    autocomplete="current-password"
                    placeholder="{{ __('Hesabınızı silmek için şifrenizi girin') }}"
                />
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
            </div>
            @error('password')
                <p class="text-sm text-red-600 dark:text-red-400 flex items-center space-x-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ $message }}</span>
                </p>
            @enderror
        </div>

        <!-- Final Warning -->
        <div class="p-4 bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700 rounded-lg">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <p class="text-sm text-red-800 dark:text-red-200 font-medium">
                    {{ __('Bu işlem geri alınamaz! Tüm verileriniz kalıcı olarak silinecektir.') }}
                </p>
            </div>
        </div>

        <!-- Action Button -->
        <div class="pt-4">
            <button 
                type="submit" 
                class="w-full inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                onclick="return confirm('{{ __('Hesabınızı silmek istediğinizden emin misiniz? Bu işlem geri alınamaz!') }}')"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                {{ __('Hesabımı Kalıcı Olarak Sil') }}
            </button>
        </div>
    </form>
</section>
