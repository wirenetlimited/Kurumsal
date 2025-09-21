<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WH Kurumsal - Kurulum Sihirbazı</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-2xl w-full bg-white rounded-lg shadow-lg p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">WH Kurumsal</h1>
                <p class="text-gray-600">Kurulum Sihirbazı - Adım 1/4</p>
            </div>

            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-blue-600">Gereksinimler Kontrolü</span>
                    <span class="text-sm text-gray-500">25%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: 25%"></div>
                </div>
            </div>

            <!-- Requirements Check -->
            <div class="space-y-6">
                <h2 class="text-xl font-semibold text-gray-900">Sistem Gereksinimleri</h2>
                
                <!-- PHP Version -->
                <div class="border rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-medium text-gray-900">PHP Sürümü</h3>
                            <p class="text-sm text-gray-600">Minimum PHP 8.2 gerekli</p>
                        </div>
                        <div class="flex items-center">
                            @php
                                $phpVersion = phpversion();
                                $phpVersionOk = version_compare($phpVersion, '8.2.0', '>=');
                            @endphp
                            @if($phpVersionOk)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $phpVersion }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $phpVersion }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- PHP Extensions -->
                <div class="border rounded-lg p-4">
                    <h3 class="font-medium text-gray-900 mb-3">PHP Eklentileri</h3>
                    <div class="space-y-2">
                        @php
                            $requiredExtensions = ['pdo', 'pdo_mysql', 'mbstring', 'xml', 'curl', 'gd', 'zip'];
                            $allExtensionsOk = true;
                        @endphp
                        @foreach($requiredExtensions as $extension)
                            @php
                                $extensionLoaded = extension_loaded($extension);
                                if (!$extensionLoaded) $allExtensionsOk = false;
                            @endphp
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">{{ $extension }}</span>
                                @if($extensionLoaded)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- File Permissions -->
                <div class="border rounded-lg p-4">
                    <h3 class="font-medium text-gray-900 mb-3">Dosya İzinleri</h3>
                    <div class="space-y-2">
                        @php
                            $requiredPaths = [
                                'storage/' => 'storage/',
                                'bootstrap/cache/' => 'bootstrap/cache/',
                                '.env' => '.env'
                            ];
                            $allPermissionsOk = true;
                        @endphp
                        @foreach($requiredPaths as $path => $displayName)
                            @php
                                $writable = is_writable($path);
                                if (!$writable) $allPermissionsOk = false;
                            @endphp
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">{{ $displayName }}</span>
                                @if($writable)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="flex justify-between mt-8">
                <div></div>
                <div>
                    @php
                        $canProceed = $phpVersionOk && $allExtensionsOk && $allPermissionsOk;
                    @endphp
                    @if($canProceed)
                        <a href="{{ route('install.database') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Devam Et
                            <svg class="ml-2 -mr-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    @else
                        <button disabled class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">
                            Devam Et
                            <svg class="ml-2 -mr-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Error Messages -->
            @if(!$canProceed)
                <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Kurulum için gereksinimler karşılanmıyor</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p>Lütfen yukarıdaki gereksinimleri karşıladıktan sonra tekrar deneyin.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</body>
</html>

