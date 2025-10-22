<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WH Kurumsal - Kurulum Sıfırlama</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-2xl w-full bg-white rounded-lg shadow-lg p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">WH Kurumsal</h1>
                <p class="text-gray-600">Kurulum Sıfırlama</p>
            </div>

            <!-- Warning Message -->
            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">⚠️ Dikkat!</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Kurulum sıfırlama işlemi:</p>
                            <ul class="list-disc list-inside mt-2">
                                <li><code class="bg-yellow-100 px-1 rounded">storage/installed</code> dosyasını siler</li>
                                <li>Tüm cache'leri temizler</li>
                                <li>Kurulum sihirbazını yeniden başlatır</li>
                            </ul>
                            <p class="mt-2"><strong>NOT:</strong> Veritabanı verileriniz silinmez!</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reset Information -->
            <div class="space-y-6">
                <h2 class="text-xl font-semibold text-gray-900">Kurulum Neden Sıfırlanmalı?</h2>
                
                <div class="space-y-4">
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <h3 class="font-medium text-blue-900 mb-2">🔧 Kurulum Hatası</h3>
                        <p class="text-sm text-blue-800">
                            Kurulum sırasında 500 hatası aldıysanız ve tekrar kurulum yapmak istiyorsanız, 
                            önce kurulumu sıfırlamanız gerekir.
                        </p>
                    </div>

                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                        <h3 class="font-medium text-green-900 mb-2">🔄 Yeniden Kurulum</h3>
                        <p class="text-sm text-green-800">
                            Kurulum ayarlarını değiştirmek veya farklı bir veritabanı kullanmak istiyorsanız, 
                            kurulumu sıfırlayıp baştan başlayabilirsiniz.
                        </p>
                    </div>
                </div>

                <!-- Reset Button -->
                <div class="mt-8 flex justify-center">
                    <a href="{{ route('install.reset') }}" 
                       onclick="return confirm('Kurulumu sıfırlamak istediğinizden emin misiniz?')"
                       class="inline-flex items-center px-6 py-3 bg-red-600 text-white text-base font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Kurulumu Sıfırla
                    </a>
                </div>

                <!-- Back Button -->
                <div class="mt-4 flex justify-center">
                    <a href="{{ route('install.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-md hover:bg-gray-600 transition-colors">
                        ← Geri (Kurulum Sayfası)
                    </a>
                </div>
            </div>

            <!-- Manual Reset Instructions -->
            <div class="mt-8 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                <h3 class="font-medium text-gray-900 mb-2">💻 Manuel Sıfırlama (Alternatif)</h3>
                <div class="text-sm text-gray-700 space-y-2">
                    <p>Komut satırından da sıfırlayabilirsiniz:</p>
                    <div class="bg-gray-800 text-green-400 p-3 rounded font-mono text-xs overflow-x-auto">
                        <div># Linux/Unix/Mac:</div>
                        <div>rm storage/installed</div>
                        <div>php artisan config:clear</div>
                        <div>php artisan cache:clear</div>
                        <div class="mt-2"># Windows:</div>
                        <div>del storage\installed</div>
                        <div>php artisan config:clear</div>
                        <div>php artisan cache:clear</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

