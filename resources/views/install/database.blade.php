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
                <p class="text-gray-600">Kurulum Sihirbazı - Adım 2/4</p>
            </div>

            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-blue-600">Veritabanı Ayarları</span>
                    <span class="text-sm text-gray-500">50%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: 50%"></div>
                </div>
            </div>

            <!-- Database Configuration -->
            <div class="space-y-6">
                <h2 class="text-xl font-semibold text-gray-900">Veritabanı Bağlantısı</h2>
                
                @if(session('error'))
                    <div class="p-4 bg-red-50 border border-red-200 rounded-md">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Bağlantı Hatası</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <p>{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('success'))
                    <div class="p-4 bg-green-50 border border-green-200 rounded-md">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 5.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">Başarılı!</h3>
                                <div class="mt-2 text-sm text-green-700">
                                    <p>{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('install.database') }}" class="space-y-4">
                    @csrf
                    
                    <!-- Database Host -->
                    <div>
                        <label for="db_host" class="block text-sm font-medium text-gray-700">Veritabanı Sunucusu</label>
                        <input type="text" name="db_host" id="db_host" value="{{ old('db_host', 'localhost') }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="localhost" required>
                        <p class="mt-1 text-sm text-gray-500">Genellikle "localhost" olarak bırakın</p>
                    </div>

                    <!-- Database Port -->
                    <div>
                        <label for="db_port" class="block text-sm font-medium text-gray-700">Port</label>
                        <input type="number" name="db_port" id="db_port" value="{{ old('db_port', '3306') }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="3306" required>
                        <p class="mt-1 text-sm text-gray-500">MySQL için varsayılan port: 3306</p>
                    </div>

                    <!-- Database Name -->
                    <div>
                        <label for="db_database" class="block text-sm font-medium text-gray-700">Veritabanı Adı</label>
                        <input type="text" name="db_database" id="db_database" value="{{ old('db_database') }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="wh_kurumsal" required>
                        <p class="mt-1 text-sm text-gray-500">cPanel'de oluşturduğunuz veritabanı adı</p>
                    </div>

                    <!-- Database Username -->
                    <div>
                        <label for="db_username" class="block text-sm font-medium text-gray-700">Kullanıcı Adı</label>
                        <input type="text" name="db_username" id="db_username" value="{{ old('db_username') }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="veritabani_kullanici" required>
                        <p class="mt-1 text-sm text-gray-500">cPanel'de oluşturduğunuz veritabanı kullanıcısı</p>
                    </div>

                    <!-- Database Password -->
                    <div>
                        <label for="db_password" class="block text-sm font-medium text-gray-700">Şifre</label>
                        <input type="password" name="db_password" id="db_password" value="{{ old('db_password') }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="veritabani_sifresi" required>
                        <p class="mt-1 text-sm text-gray-500">Veritabanı kullanıcısının şifresi</p>
                    </div>

                    <!-- Instructions -->
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">cPanel'de Veritabanı Oluşturma</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ol class="list-decimal list-inside space-y-1">
                                        <li>cPanel > MySQL Databases'e gidin</li>
                                        <li>Yeni veritabanı oluşturun (örn: wh_kurumsal)</li>
                                        <li>Yeni kullanıcı oluşturun</li>
                                        <li>Kullanıcıyı veritabanına ekleyin (tüm izinler)</li>
                                        <li>Yukarıdaki bilgileri girin</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="flex justify-between pt-6">
                        <a href="{{ route('install.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="mr-2 -ml-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                            </svg>
                            Geri
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Bağlantıyı Test Et
                            <svg class="ml-2 -mr-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </form>

                @if(session('success'))
                    <div class="pt-6">
                        <a href="{{ route('install.migrate') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Migration'ları Çalıştır
                            <svg class="ml-2 -mr-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>

