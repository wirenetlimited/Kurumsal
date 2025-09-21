<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Hassas alanları şifrele
        $sensitiveFields = [
            'mail_password',
            'mail_username',
            'database_password',
            'api_key',
            'secret_key',
            'webhook_secret',
            'oauth_client_secret',
            'jwt_secret',
            'encryption_key'
        ];

        foreach ($sensitiveFields as $field) {
            $settings = DB::table('settings')
                ->where('key', $field)
                ->whereNotNull('value')
                ->where('value', '!=', '')
                ->get();

            foreach ($settings as $setting) {
                try {
                    // Eğer değer zaten şifrelenmişse atla
                    if ($this->isEncrypted($setting->value)) {
                        continue;
                    }

                    // Değeri şifrele
                    $encryptedValue = Crypt::encryptString($setting->value);
                    
                    DB::table('settings')
                        ->where('id', $setting->id)
                        ->update(['value' => $encryptedValue]);

                } catch (\Exception $e) {
                    // Şifreleme hatası durumunda log'la ama devam et
                    \Log::warning("Failed to encrypt setting: {$field}", [
                        'setting_id' => $setting->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hassas alanları çöz (sadece test ortamında)
        if (app()->environment('testing')) {
            $sensitiveFields = [
                'mail_password',
                'mail_username',
                'database_password',
                'api_key',
                'secret_key',
                'webhook_secret',
                'oauth_client_secret',
                'jwt_secret',
                'encryption_key'
            ];

            foreach ($sensitiveFields as $field) {
                $settings = DB::table('settings')
                    ->where('key', $field)
                    ->whereNotNull('value')
                    ->where('value', '!=', '')
                    ->get();

                foreach ($settings as $setting) {
                    try {
                        if ($this->isEncrypted($setting->value)) {
                            $decryptedValue = Crypt::decryptString($setting->value);
                            
                            DB::table('settings')
                                ->where('id', $setting->id)
                                ->update(['value' => $decryptedValue]);
                        }
                    } catch (\Exception $e) {
                        \Log::warning("Failed to decrypt setting: {$field}", [
                            'setting_id' => $setting->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Check if a value is already encrypted
     */
    private function isEncrypted($value): bool
    {
        if (empty($value)) {
            return false;
        }

        // Laravel encrypted string pattern check
        // Encrypted strings start with base64 encoded data and have specific format
        try {
            // Try to decrypt - if it works, it's encrypted
            Crypt::decryptString($value);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
};
