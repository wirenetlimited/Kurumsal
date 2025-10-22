<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Setting;

class InstallController extends Controller
{
    public function index()
    {
        if ($this->isInstalled()) {
            return redirect('/');
        }

        return view('install.index');
    }

    public function database()
    {
        if ($this->isInstalled()) {
            return redirect('/');
        }

        return view('install.database');
    }

    public function backToIndex()
    {
        if ($this->isInstalled()) {
            return redirect('/');
        }

        return redirect()->route('install.index');
    }

    public function backToDatabase()
    {
        if ($this->isInstalled()) {
            return redirect('/');
        }

        return redirect()->route('install.database');
    }

    public function testDatabase(Request $request)
    {
        if ($this->isInstalled()) {
            return redirect('/');
        }

        try {
            $request->validate([
                'db_host' => 'required|string',
                'db_port' => 'required|numeric',
                'db_database' => 'required|string',
                'db_username' => 'required|string',
                'db_password' => 'nullable|string', // Şifre opsiyonel
            ]);
        } catch (\Exception $e) {
            \Log::error('Validation failed', ['error' => $e->getMessage()]);
            return redirect()->route('install.database')->with('error', 'Validation hatası: ' . $e->getMessage());
        }

        try {
            // Debug: Log the attempt
            \Log::info('Database connection attempt', [
                'host' => $request->db_host,
                'port' => $request->db_port,
                'database' => $request->db_database,
                'username' => $request->db_username,
                'password_length' => strlen($request->db_password)
            ]);

            // Test database connection (boş şifre desteği)
            $password = $request->db_password ?? '';
            $connection = mysqli_connect(
                $request->db_host,
                $request->db_username,
                $password,
                $request->db_database,
                $request->db_port
            );

            if (!$connection) {
                $error = mysqli_connect_error();
                \Log::error('Database connection failed', ['error' => $error]);
                throw new \Exception('Veritabanı bağlantısı başarısız: ' . $error);
            }

            // Test if we can query the database
            $result = mysqli_query($connection, "SELECT 1");
            if (!$result) {
                $error = mysqli_error($connection);
                \Log::error('Database query test failed', ['error' => $error]);
                throw new \Exception('Veritabanı sorgusu başarısız: ' . $error);
            }

            mysqli_close($connection);

            // Update .env file
            $this->updateEnvFile($request);

            \Log::info('Database connection successful');
            \Log::info('.env file updated successfully');
            
            // Automatically trigger migration
            return redirect()->route('install.migrate');

        } catch (\Exception $e) {
            \Log::error('Database connection error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('install.database')->with('error', 'Hata: ' . $e->getMessage());
        }
    }

    public function migrate()
    {
        if ($this->isInstalled()) {
            return redirect('/');
        }

        try {
            // Import SQL file if exists
            $sqlFile = database_path('wh_kurumsal.sql');
            if (file_exists($sqlFile)) {
                \Log::info('SQL import başlıyor', ['file' => $sqlFile, 'size' => filesize($sqlFile)]);
                $this->importSqlFile($sqlFile);
                \Log::info('SQL import tamamlandı');
                return redirect()->route('install.admin')->with('success', 'SQL dosyası başarıyla import edildi!');
            }
            
            // Fallback: Run migrations if no SQL file
            Artisan::call('migrate:fresh');
            
            // Run seeders
            Artisan::call('db:seed');
            
            // Clear caches
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');

            return redirect()->route('install.admin')->with('success', 'Veritabanı tabloları başarıyla oluşturuldu!');

        } catch (\Exception $e) {
            return redirect()->route('install.database')->with('error', 'Migration hatası: ' . $e->getMessage());
        }
    }

    private function importSqlFile($sqlFile)
    {
        try {
            \Log::info('SQL dosyası okunuyor', ['file' => $sqlFile]);
            $sql = file_get_contents($sqlFile);
            
            if (!$sql) {
                throw new \Exception('SQL dosyası okunamadı veya boş');
            }
            
            // Get database connection details from .env
            $host = env('DB_HOST', '127.0.0.1');
            $port = env('DB_PORT', '3306');
            $database = env('DB_DATABASE');
            $username = env('DB_USERNAME');
            $password = env('DB_PASSWORD');
            
            \Log::info('Veritabanı bağlantısı kuruluyor', [
                'host' => $host,
                'port' => $port,
                'database' => $database,
                'username' => $username
            ]);
            
            // Create connection
            $connection = mysqli_connect($host, $username, $password, $database, $port);
            
            if (!$connection) {
                throw new \Exception('Veritabanı bağlantısı kurulamadı: ' . mysqli_connect_error());
            }
            
            // Set charset
            mysqli_set_charset($connection, 'utf8mb4');
            
            // Execute SQL file directly using multi_query for better handling
            \Log::info('SQL import başlatılıyor (multi_query kullanarak)');
            
            if (mysqli_multi_query($connection, $sql)) {
                $successCount = 0;
                do {
                    // Store first result set (if any)
                    if ($result = mysqli_store_result($connection)) {
                        mysqli_free_result($result);
                    }
                    
                    // Check for errors
                    if (mysqli_errno($connection)) {
                        $error = mysqli_error($connection);
                        \Log::warning('SQL statement uyarısı', ['error' => $error]);
                        
                        // Kritik olmayan hataları atla
                        if (strpos($error, 'already exists') === false && 
                            strpos($error, 'Duplicate entry') === false) {
                            \Log::error('SQL statement hatası', ['error' => $error]);
                        }
                    } else {
                        $successCount++;
                    }
                    
                    // More results?
                } while (mysqli_more_results($connection) && mysqli_next_result($connection));
                
                \Log::info('SQL import başarılı', ['affected_statements' => $successCount]);
            } else {
                throw new \Exception('SQL import başlatılamadı: ' . mysqli_error($connection));
            }
            
            \Log::info('SQL import tamamlandı', [
                'success' => $successCount
            ]);
            
            mysqli_close($connection);
            
            // Clear caches after import
            \Log::info('Cache temizleniyor');
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            
        } catch (\Exception $e) {
            \Log::error('SQL import hatası', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            throw $e;
        }
    }

    public function admin()
    {
        if ($this->isInstalled()) {
            return redirect('/');
        }

        return view('install.admin');
    }

    public function createAdmin(Request $request)
    {
        if ($this->isInstalled()) {
            return redirect('/');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            // Create admin user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
                'is_admin' => true,
            ]);

            // Mark as installed
            $this->markAsInstalled();

            return redirect()->route('install.admin')->with('success', 'Kurulum başarıyla tamamlandı! Admin hesabınız oluşturuldu.');

        } catch (\Exception $e) {
            return redirect()->route('install.admin')->with('error', 'Admin kullanıcı oluşturma hatası: ' . $e->getMessage());
        }
    }

    private function isInstalled()
    {
        return file_exists(storage_path('installed'));
    }

    private function markAsInstalled()
    {
        file_put_contents(storage_path('installed'), date('Y-m-d H:i:s'));
    }

    public function reset()
    {
        try {
            // Remove installed marker
            if (file_exists(storage_path('installed'))) {
                unlink(storage_path('installed'));
            }

            // Clear all caches
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');

            \Log::info('Installation reset completed');

            return redirect()->route('install.index')->with('success', 'Kurulum sıfırlandı! Tekrar kurulum yapabilirsiniz.');
        } catch (\Exception $e) {
            \Log::error('Installation reset error', ['error' => $e->getMessage()]);
            return redirect()->route('install.index')->with('error', 'Sıfırlama hatası: ' . $e->getMessage());
        }
    }

    private function updateEnvFile(Request $request)
    {
        $envPath = base_path('.env');
        $envExamplePath = base_path('.env.example');
        
        // .env yoksa oluştur
        if (!file_exists($envPath)) {
            // .env.example varsa kopyala, yoksa sıfırdan oluştur
            if (file_exists($envExamplePath)) {
                copy($envExamplePath, $envPath);
            } else {
                // Temel .env içeriği oluştur
                $defaultEnv = $this->getDefaultEnvContent();
                file_put_contents($envPath, $defaultEnv);
            }
        }

        $envContent = file_get_contents($envPath);

        // Update database settings
        $envContent = preg_replace('/DB_HOST=.*/', 'DB_HOST=' . $request->db_host, $envContent);
        $envContent = preg_replace('/DB_PORT=.*/', 'DB_PORT=' . $request->db_port, $envContent);
        $envContent = preg_replace('/DB_DATABASE=.*/', 'DB_DATABASE=' . $request->db_database, $envContent);
        $envContent = preg_replace('/DB_USERNAME=.*/', 'DB_USERNAME=' . $request->db_username, $envContent);
        
        // Şifreyi güvenli şekilde yaz (boş şifre desteği)
        $password = $request->db_password ?? '';
        
        if (!empty($password)) {
            $escapedPassword = addslashes($password);
            
            // Özel karakterler varsa tırnak içinde yaz
            if (preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/', $password)) {
                $envContent = preg_replace('/DB_PASSWORD=.*/', 'DB_PASSWORD="' . $escapedPassword . '"', $envContent);
            } else {
                $envContent = preg_replace('/DB_PASSWORD=.*/', 'DB_PASSWORD=' . $escapedPassword, $envContent);
            }
        } else {
            // Boş şifre
            $envContent = preg_replace('/DB_PASSWORD=.*/', 'DB_PASSWORD=', $envContent);
        }

        // Update app settings
        $envContent = preg_replace('/APP_ENV=.*/', 'APP_ENV=production', $envContent);
        $envContent = preg_replace('/APP_DEBUG=.*/', 'APP_DEBUG=false', $envContent);

        // .env dosyasını yazma işlemi
        if (!file_put_contents($envPath, $envContent)) {
            throw new \Exception('.env dosyası yazılamadı. Dosya izinlerini kontrol edin.');
        }
        
        // Generate application key if not exists
        if (!config('app.key')) {
            try {
                Artisan::call('key:generate');
            } catch (\Exception $e) {
                \Log::error('Key generation failed', ['error' => $e->getMessage()]);
                // Key generation hatası kritik değil, devam et
            }
        }
    }
    
    private function getDefaultEnvContent()
    {
        return 'APP_NAME="WH Kurumsal"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://localhost
APP_TIMEZONE=Europe/Istanbul
APP_LOCALE=tr

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wh_kurumsal
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_STORE=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="info@example.com"
MAIL_FROM_NAME="${APP_NAME}"
';
    }
}
