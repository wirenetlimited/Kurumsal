<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class SiteSettingsController extends Controller
{
    /**
     * Display site settings page
     */
    public function index()
    {
        $groups = [
            'site' => [
                'name' => 'Site Ayarları',
                'icon' => 'fas fa-globe',
                'description' => 'Site adı, logo, iletişim bilgileri, sosyal medya'
            ],
            'financial' => [
                'name' => 'Finansal Ayarlar',
                'icon' => 'fas fa-money-bill',
                'description' => 'Para birimi, vergi oranları, banka bilgileri, fatura ayarları'
            ],
            'security' => [
                'name' => 'Güvenlik Ayarları',
                'icon' => 'fas fa-shield-alt',
                'description' => 'Şifre politikaları, oturum yönetimi, güvenlik önlemleri'
            ],
            'system' => [
                'name' => 'Sistem Ayarları',
                'icon' => 'fas fa-cogs',
                'description' => 'Zaman dilimi, dil, cache yönetimi, yedekleme'
            ],
            'email' => [
                'name' => 'E-posta Ayarları',
                'icon' => 'fas fa-envelope',
                'description' => 'SMTP ayarları, e-posta gönderim konfigürasyonu'
            ],
            'advanced' => [
                'name' => 'Gelişmiş Ayarlar',
                'icon' => 'fas fa-rocket',
                'description' => 'API, webhook, bildirim ayarları'
            ]
        ];

        $settings = [];
        foreach ($groups as $groupKey => $group) {
            $settings[$groupKey] = Setting::getGroup($groupKey);
        }

        return view('admin.site-settings.index', compact('groups', 'settings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $group = $request->input('group');
        $settings = $request->except(['_token', 'group']);

        foreach ($settings as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            
            if ($setting) {
                // File upload handling
                if ($setting->type === 'file' && $request->hasFile($key)) {
                    $file = $request->file($key);
                    $path = $file->store('settings', 'public');
                    
                    // Delete old file if exists
                    if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                        Storage::disk('public')->delete($setting->value);
                    }
                    
                    // Special handling for favicon - generate different sizes
                    if ($key === 'site_favicon') {
                        $this->generateFaviconSizes($path);
                    }
                    
                    $value = $path;
                }

                // Handle JSON fields
                if ($setting->type === 'json' && is_array($value)) {
                    $value = json_encode($value);
                }

                // Handle boolean fields
                if ($setting->type === 'boolean') {
                    $value = $value ? '1' : '0';
                }

                Setting::set($key, $value);
            }
        }

        // Clear config cache if system settings changed
        if ($group === 'system') {
            Artisan::call('config:clear');
            Cache::flush();
        }

        // JSON response for AJAX requests
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => ucfirst($group) . ' ayarları başarıyla güncellendi.'
            ]);
        }
        
        return back()->with('status', ucfirst($group) . ' ayarları başarıyla güncellendi.');
    }

    /**
     * Generate different favicon sizes
     */
    private function generateFaviconSizes($originalPath)
    {
        try {
            $fullPath = Storage::disk('public')->path($originalPath);
            
            // Check if file exists and is an image
            if (!file_exists($fullPath)) {
                return;
            }
            
            // Get image info
            $imageInfo = getimagesize($fullPath);
            if (!$imageInfo) {
                return;
            }
            
            // Create image resource based on type
            switch ($imageInfo[2]) {
                case IMAGETYPE_JPEG:
                    $source = imagecreatefromjpeg($fullPath);
                    break;
                case IMAGETYPE_PNG:
                    $source = imagecreatefrompng($fullPath);
                    break;
                case IMAGETYPE_GIF:
                    $source = imagecreatefromgif($fullPath);
                    break;
                default:
                    return; // Unsupported format
            }
            
            if (!$source) {
                return;
            }
            
            // Generate different sizes
            $sizes = [16, 32, 64, 128];
            $faviconDir = dirname($fullPath);
            
            foreach ($sizes as $size) {
                $favicon = imagecreatetruecolor($size, $size);
                
                // Preserve transparency for PNG
                if ($imageInfo[2] === IMAGETYPE_PNG) {
                    imagealphablending($favicon, false);
                    imagesavealpha($favicon, true);
                    $transparent = imagecolorallocatealpha($favicon, 255, 255, 255, 127);
                    imagefill($favicon, 0, 0, $transparent);
                }
                
                // Resize image
                imagecopyresampled(
                    $favicon, $source, 
                    0, 0, 0, 0, 
                    $size, $size, 
                    imagesx($source), imagesy($source)
                );
                
                // Save favicon
                $faviconPath = $faviconDir . "/favicon-{$size}x{$size}.png";
                imagepng($favicon, $faviconPath);
                imagedestroy($favicon);
            }
            
            // Generate .ico file (16x16 for compatibility)
            $favicon16 = imagecreatetruecolor(16, 16);
            if ($imageInfo[2] === IMAGETYPE_PNG) {
                imagealphablending($favicon16, false);
                imagesavealpha($favicon16, true);
                $transparent = imagecolorallocatealpha($favicon16, 255, 255, 255, 127);
                imagefill($favicon16, 0, 0, $transparent);
            }
            
            imagecopyresampled(
                $favicon16, $source, 
                0, 0, 0, 0, 
                16, 16, 
                imagesx($source), imagesy($source)
            );
            
            // Save as ICO (using PNG for simplicity)
            $icoPath = $faviconDir . "/favicon.ico";
            imagepng($favicon16, $icoPath);
            
            // Copy to public root for default favicon.ico access
            $publicIcoPath = public_path('favicon.ico');
            copy($icoPath, $publicIcoPath);
            
            imagedestroy($favicon16);
            imagedestroy($source);
            
        } catch (\Exception $e) {
            // Log error but don't fail the upload
            \Log::error('Favicon generation failed: ' . $e->getMessage());
        }
    }

    /**
     * Clear cache
     */
    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Setting::clearCache();

        return back()->with('status', 'Tüm cache\'ler temizlendi.');
    }

    /**
     * View logs
     */
    public function viewLogs()
    {
        $logFile = storage_path('logs/laravel.log');
        $logs = [];

        if (file_exists($logFile)) {
            $logs = file($logFile);
            $logs = array_slice($logs, -100); // Son 100 satır
            $logs = array_reverse($logs); // En yeni en üstte
        }

        return view('admin.site-settings.logs', compact('logs'));
    }

    /**
     * Clear logs
     */
    public function clearLogs()
    {
        $logFile = storage_path('logs/laravel.log');
        
        if (file_exists($logFile)) {
            file_put_contents($logFile, '');
        }

        return response()->json(['success' => true]);
    }

    /**
     * Get log level from log text
     */
    public static function getLogLevel($logText)
    {
        if (strpos($logText, 'ERROR') !== false) return 'ERROR';
        if (strpos($logText, 'WARNING') !== false) return 'WARNING';
        if (strpos($logText, 'INFO') !== false) return 'INFO';
        if (strpos($logText, 'DEBUG') !== false) return 'DEBUG';
        return 'INFO';
    }

    /**
     * Get log date from log text
     */
    public static function getLogDate($logText)
    {
        if (preg_match('/\[(\d{4}-\d{2}-\d{2})/', $logText, $matches)) {
            return $matches[1];
        }
        return '';
    }

    /**
     * Get setting value helper
     */
    public static function get($key, $default = null)
    {
        return Setting::get($key, $default);
    }

    /**
     * Set setting value helper
     */
    public static function set($key, $value)
    {
        return Setting::set($key, $value);
    }

    /**
     * Get all settings as array
     */
    public static function getAll()
    {
        return Setting::getAll();
    }
}
