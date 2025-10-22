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
                            <?php
                                $phpVersion = phpversion();
                                $phpVersionOk = version_compare($phpVersion, '8.2.0', '>=');
                            ?>
                            <?php if($phpVersionOk): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <?php echo e($phpVersion); ?>

                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                    <?php echo e($phpVersion); ?>

                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- PHP Extensions -->
                <div class="border rounded-lg p-4">
                    <h3 class="font-medium text-gray-900 mb-3">PHP Eklentileri</h3>
                    <div class="space-y-2">
                        <?php
                            $requiredExtensions = ['pdo', 'pdo_mysql', 'mbstring', 'xml', 'curl', 'gd', 'zip'];
                            $allExtensionsOk = true;
                        ?>
                        <?php $__currentLoopData = $requiredExtensions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $extension): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $extensionLoaded = extension_loaded($extension);
                                if (!$extensionLoaded) $allExtensionsOk = false;
                            ?>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600"><?php echo e($extension); ?></span>
                                <?php if($extensionLoaded): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <!-- Debug Information -->
                <div class="border rounded-lg p-4 bg-blue-50 border-blue-200">
                    <h3 class="font-medium text-blue-900 mb-3">🔍 Debug Bilgileri</h3>
                    <div class="text-sm text-blue-800 space-y-1">
                        <div><strong>Mevcut Dizin:</strong> <?php echo e(getcwd()); ?></div>
                        <div><strong>Base Path:</strong> <?php echo e(base_path()); ?></div>
                        <div><strong>Storage Path:</strong> <?php echo e(storage_path()); ?></div>
                        <div><strong>PHP OS:</strong> <?php echo e(PHP_OS_FAMILY); ?></div>
                    </div>
                </div>

                <!-- File Permissions -->
                <div class="border rounded-lg p-4">
                    <h3 class="font-medium text-gray-900 mb-3">Dosya İzinleri</h3>
                    <div class="space-y-3">
                        <?php
                            $requiredPaths = [
                                'storage/' => ['path' => 'storage/', 'required' => '775', 'description' => 'Laravel cache ve log dosyaları için'],
                                'bootstrap/cache/' => ['path' => 'bootstrap/cache/', 'required' => '775', 'description' => 'Laravel bootstrap cache için'],
                                '.env' => ['path' => '.env', 'required' => '644', 'description' => 'Uygulama yapılandırması için']
                            ];
                            
                            $allPermissionsOk = true;
                        ?>
                        <?php $__currentLoopData = $requiredPaths; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $displayName => $config): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $path = $config['path'];
                                $required = $config['required'];
                                $description = $config['description'];
                                
                                // Hem göreli hem mutlak yol ile kontrol et
                                $relativePath = $path;
                                $absolutePath = base_path($path);
                                
                                $writable = is_writable($relativePath) || is_writable($absolutePath);
                                $exists = file_exists($relativePath) || is_dir($relativePath) || file_exists($absolutePath) || is_dir($absolutePath);
                                
                                // Hangi yolun çalıştığını belirle
                                $workingPath = '';
                                if (file_exists($relativePath) || is_dir($relativePath)) {
                                    $workingPath = $relativePath;
                                } elseif (file_exists($absolutePath) || is_dir($absolutePath)) {
                                    $workingPath = $absolutePath;
                                }
                                
                                // Mevcut izinleri al (Windows uyumlu)
                                if ($exists) {
                                    if (PHP_OS_FAMILY === 'Windows') {
                                        // Windows'ta farklı kontrol
                                        $currentPerms = $writable ? 'Yazılabilir' : 'Salt Okunur';
                                    } else {
                                        $currentPerms = substr(sprintf('%o', fileperms($workingPath ?: $path)), -3);
                                    }
                                } else {
                                    $currentPerms = 'Dosya/Klasör Yok';
                                }
                                
                                if (!$writable) $allPermissionsOk = false;
                            ?>
                            <div class="border rounded-lg p-3 <?php echo e($writable ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200'); ?>">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center">
                                        <span class="font-medium text-gray-900"><?php echo e($displayName); ?></span>
                                        <?php if($writable): ?>
                                            <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                OK
                                            </span>
                                        <?php else: ?>
                                            <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                </svg>
                                                HATA
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="text-sm text-gray-600 mb-2"><?php echo e($description); ?></div>
                                
                                <!-- Debug Path Info -->
                                <div class="text-xs text-gray-500 mb-2 bg-gray-50 p-2 rounded">
                                    <div><strong>Göreli Yol:</strong> <?php echo e($relativePath); ?> 
                                        <?php if(file_exists($relativePath) || is_dir($relativePath)): ?>
                                            <span class="text-green-600">✅ VAR</span>
                                        <?php else: ?>
                                            <span class="text-red-600">❌ YOK</span>
                                        <?php endif; ?>
                                    </div>
                                    <div><strong>Mutlak Yol:</strong> <?php echo e($absolutePath); ?>

                                        <?php if(file_exists($absolutePath) || is_dir($absolutePath)): ?>
                                            <span class="text-green-600">✅ VAR</span>
                                        <?php else: ?>
                                            <span class="text-red-600">❌ YOK</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if($workingPath): ?>
                                        <div><strong>Çalışan Yol:</strong> <?php echo e($workingPath); ?> <span class="text-blue-600">✅</span></div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 text-xs">
                                    <div>
                                        <span class="font-medium text-gray-700">Mevcut İzin:</span>
                                        <span class="ml-1 font-mono <?php echo e($writable ? 'text-green-600' : 'text-red-600'); ?>"><?php echo e($currentPerms); ?></span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Gerekli İzin:</span>
                                        <span class="ml-1 font-mono text-blue-600"><?php echo e($required); ?></span>
                                    </div>
                                </div>
                                
                                <?php if(!$writable): ?>
                                    <div class="mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded text-xs">
                                        <div class="font-medium text-yellow-800 mb-1">Çözüm:</div>
                                        <div class="text-yellow-700">
                                            <div class="mb-2">
                                                <strong>Linux/Unix:</strong><br>
                                                <?php if($displayName === '.env'): ?>
                                                    <code class="bg-yellow-100 px-1 rounded">chmod 644 .env</code>
                                                <?php else: ?>
                                                    <code class="bg-yellow-100 px-1 rounded">chmod -R 775 <?php echo e($path); ?></code>
                                                <?php endif; ?>
                                            </div>
                                            <div class="mb-2">
                                                <strong>cPanel:</strong><br>
                                                File Manager → sağ tık → "Permissions" → <?php echo e($required); ?>

                                            </div>
                                            <div>
                                                <strong>Plesk:</strong><br>
                                                File Manager → sağ tık → "Change Permissions" → <?php echo e($required); ?><br>
                                                <em class="text-xs">veya SSH: <code class="bg-yellow-100 px-1 rounded">chmod -R <?php echo e($required); ?> <?php echo e($path); ?></code></em>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="flex justify-between mt-8">
                <div>
                    <button onclick="location.reload()" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        İzinleri Yenile
                    </button>
                </div>
                <div class="flex space-x-3">
                    <?php
                        $canProceed = $phpVersionOk && $allExtensionsOk && $allPermissionsOk;
                    ?>
                    <?php if($canProceed): ?>
                        <a href="<?php echo e(route('install.database')); ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Devam Et
                            <svg class="ml-2 -mr-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    <?php else: ?>
                        <!-- BYPASS BUTTON - Plesk için -->
                        <a href="<?php echo e(route('install.database')); ?>?bypass=1" class="inline-flex items-center px-4 py-2 border border-orange-300 text-sm font-medium rounded-md text-orange-700 bg-orange-50 hover:bg-orange-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            Plesk Bypass
                        </a>
                        <button disabled class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">
                            Devam Et
                            <svg class="ml-2 -mr-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Error Messages -->
            <?php if(!$canProceed): ?>
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
                                <p class="mt-2"><strong>Plesk kullanıyorsanız:</strong> "Plesk Bypass" butonunu kullanarak devam edebilirsiniz.</p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\Kurumsal\resources\views/install/index.blade.php ENDPATH**/ ?>