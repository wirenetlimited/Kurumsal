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

    public function testDatabase(Request $request)
    {
        if ($this->isInstalled()) {
            return redirect('/');
        }

        $request->validate([
            'db_host' => 'required|string',
            'db_port' => 'required|numeric',
            'db_database' => 'required|string',
            'db_username' => 'required|string',
            'db_password' => 'required|string',
        ]);

        try {
            // Test database connection
            $connection = mysqli_connect(
                $request->db_host,
                $request->db_username,
                $request->db_password,
                $request->db_database,
                $request->db_port
            );

            if (!$connection) {
                throw new \Exception('Veritabanı bağlantısı başarısız: ' . mysqli_connect_error());
            }

            mysqli_close($connection);

            // Update .env file
            $this->updateEnvFile($request);

            return redirect()->route('install.database')->with('success', 'Veritabanı bağlantısı başarılı!');

        } catch (\Exception $e) {
            return redirect()->route('install.database')->with('error', $e->getMessage());
        }
    }

    public function migrate()
    {
        if ($this->isInstalled()) {
            return redirect('/');
        }

        try {
            // Run migrations
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

    private function updateEnvFile(Request $request)
    {
        $envPath = base_path('.env');
        
        if (!file_exists($envPath)) {
            copy(base_path('.env.example'), $envPath);
        }

        $envContent = file_get_contents($envPath);

        // Update database settings
        $envContent = preg_replace('/DB_HOST=.*/', 'DB_HOST=' . $request->db_host, $envContent);
        $envContent = preg_replace('/DB_PORT=.*/', 'DB_PORT=' . $request->db_port, $envContent);
        $envContent = preg_replace('/DB_DATABASE=.*/', 'DB_DATABASE=' . $request->db_database, $envContent);
        $envContent = preg_replace('/DB_USERNAME=.*/', 'DB_USERNAME=' . $request->db_username, $envContent);
        $envContent = preg_replace('/DB_PASSWORD=.*/', 'DB_PASSWORD=' . $request->db_password, $envContent);

        // Update app settings
        $envContent = preg_replace('/APP_ENV=.*/', 'APP_ENV=production', $envContent);
        $envContent = preg_replace('/APP_DEBUG=.*/', 'APP_DEBUG=false', $envContent);

        file_put_contents($envPath, $envContent);
    }
}
