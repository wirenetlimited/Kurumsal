<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Profil Ayarları') }}
                </h2>
                <p class="text-lg text-gray-600 dark:text-gray-400 mt-2">
                    {{ __('Hesap bilgilerinizi ve tercihlerinizi yönetin') }}
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <div class="w-4 h-4 rounded-full bg-green-400 shadow-lg"></div>
                <span class="text-sm text-gray-600 dark:text-gray-400 font-medium">{{ __('Çevrimiçi') }}</span>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Kişisel Bilgiler -->
            <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
                <div class="relative">
                    <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 px-8 py-6">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <div class="w-8 h-8 bg-white/20 rounded-2xl flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            {{ __('Kişisel Bilgiler') }}
                        </h3>
                    </div>
                    <div class="p-8">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            <!-- Güvenlik -->
            <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
                <div class="relative">
                    <div class="bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 px-8 py-6">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <div class="w-8 h-8 bg-white/20 rounded-2xl flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            {{ __('Güvenlik') }}
                        </h3>
                    </div>
                    <div class="p-8">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            <!-- Dashboard Başlığı -->
            <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
                <div class="relative">
                    <div class="bg-gradient-to-r from-purple-600 via-pink-600 to-rose-600 px-8 py-6">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <div class="w-8 h-8 bg-white/20 rounded-2xl flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                                </svg>
                            </div>
                            {{ __('Dashboard Başlığı') }}
                        </h3>
                    </div>
                    <div class="p-8">
                        @include('profile.partials.update-dashboard-title-form')
                    </div>
                </div>
            </div>

            <!-- Tehlikeli Bölge -->
            <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/50 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-gray-50/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-3xl"></div>
                <div class="relative">
                    <div class="bg-gradient-to-r from-red-600 via-orange-600 to-amber-600 px-8 py-6">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <div class="w-8 h-8 bg-white/20 rounded-2xl flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            {{ __('Tehlikeli Bölge') }}
                        </h3>
                    </div>
                    <div class="p-8">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
