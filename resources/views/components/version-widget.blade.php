@props(['showDetails' => false])

<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                        Sürüm Bilgileri
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Uygulama sürümü ve güncelleme bilgileri
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                @if(config('version.codename'))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                        {{ config('version.codename') }}
                    </span>
                @endif
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                    v{{ config('version.version') }}
                </span>
            </div>
        </div>
        
        @if($showDetails)
            <div class="mt-4 space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Yayın Tarihi:</span>
                    <span class="text-gray-900 dark:text-white">{{ config('version.release_date') }}</span>
                </div>
                
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Açıklama:</span>
                    <span class="text-gray-900 dark:text-white">{{ config('version.description') }}</span>
                </div>
                
                @if(config('version.features'))
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Yeni Özellikler:</h4>
                        <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                            @foreach(array_slice(config('version.features'), 0, 3) as $feature)
                                <li class="flex items-center">
                                    <svg class="h-4 w-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <div class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex space-x-2">
                        <a href="{{ route('changelog') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 dark:text-blue-200 dark:bg-blue-900 dark:hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Sürüm Notları
                        </a>
                        
                        <button onclick="showChangelog()" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Hızlı Bakış
                        </button>
                    </div>
                </div>
            </div>
        @else
            <div class="mt-4">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ config('version.description') }}
                </p>
                <div class="mt-3">
                    <a href="{{ route('changelog') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                        Detayları görüntüle →
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
