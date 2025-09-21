<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\TestMail;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use App\Models\Quote; // Added this import for the quote method
use App\Mail\QuoteMail; // Added this import for the quote method

class EmailSettingsController extends Controller
{
    /**
     * Display email settings page
     */
    public function index()
    {
        $settings = $this->getEmailSettings();
        $statistics = $this->getEmailStatistics();
        
        return view('admin.email-settings.index', compact('settings', 'statistics'));
    }

    /**
     * Update SMTP settings (DB-based)
     */
    public function updateSmtp(Request $request)
    {
        $validated = $request->validate([
            'mail_mailer' => 'required|in:smtp,mailgun,ses,postmark,log',
            'mail_host' => 'required_if:mail_mailer,smtp|nullable|string',
            'mail_port' => 'required_if:mail_mailer,smtp|nullable|integer|min:1|max:65535',
            'mail_username' => 'required_if:mail_mailer,smtp|nullable|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'required_if:mail_mailer,smtp|nullable|in:tls,ssl',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string|max:255',
        ]);

        try {
            // Şifre alanı boşsa mevcut şifreyi koru
            if (empty($validated['mail_password'])) {
                unset($validated['mail_password']);
            }

            // Persist to DB settings
            foreach ($validated as $k => $v) {
                Setting::set($k, is_string($v) ? trim($v) : $v);
            }

            // Clear settings cache so runtime overrides pick up immediately
            Setting::clearCache();

            // Log the update for security audit
            Log::info('SMTP settings updated', [
                'user_id' => auth()->id(),
                'user_email' => auth()->user()->email,
                'updated_fields' => array_keys($validated),
                'mailer' => $validated['mail_mailer'] ?? 'not_changed'
            ]);

            return back()->with('status', 'SMTP ayarları başarıyla güncellendi ve kaydedildi.');
        } catch (\Exception $e) {
            Log::error('SMTP settings update failed', [
                'user_id' => auth()->id(),
                'user_email' => auth()->user()->email,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'SMTP ayarları güncellenirken hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Send test email
     */
    public function sendTestEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
            'test_subject' => 'required|string|max:255',
            'test_message' => 'required|string|max:1000',
        ]);

        try {
            // Test email göndermeden önce mevcut mail konfigürasyonunu kontrol et
            $this->validateMailConfiguration();

            Mail::to($request->test_email)
                ->send(new TestMail($request->test_subject, $request->test_message));

            // Log test email for audit
            Log::info('Test email sent successfully', [
                'user_id' => auth()->id(),
                'user_email' => auth()->user()->email,
                'test_email' => $request->test_email,
                'subject' => $request->test_subject
            ]);

            return back()->with('status', 'Test e-postası başarıyla gönderildi!');
        } catch (\Exception $e) {
            Log::error('Test email failed', [
                'user_id' => auth()->id(),
                'user_email' => auth()->user()->email,
                'test_email' => $request->test_email,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'E-posta gönderilirken hata: ' . $e->getMessage());
        }
    }

    /**
     * Preview quote email template
     */
    public function quote()
    {
        try {
            $quote = Quote::with(['customer', 'items'])->first();
            
            if (!$quote) {
                return back()->with('error', 'Test için teklif bulunamadı.');
            }

            // Customer kontrolü ekle
            if (!$quote->customer) {
                return back()->with('error', 'Teklif için müşteri bilgisi bulunamadı.');
            }

            // Mail preview için view döndür
            $mail = new QuoteMail($quote);
            return view('emails.quote', [
                'quote' => $quote,
                'customer' => $quote->customer,
                'companyInfo' => $mail->companyInfo ?? [
                    'name' => 'WH Kurumsal',
                    'email' => 'info@whkurumsal.com',
                    'phone' => '+90 212 123 4567',
                    'address' => 'İstanbul, Türkiye',
                    'website' => 'https://whkurumsal.com'
                ],
                'validUntil' => $quote->valid_until ? \Carbon\Carbon::parse($quote->valid_until)->format('d.m.Y') : null,
                'daysRemaining' => $quote->valid_until ? (int)now()->diffInDays($quote->valid_until, false) : null,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Teklif e-posta şablonu yüklenirken hata: ' . $e->getMessage());
        }
    }

    /**
     * Get email settings from DB with fallback to config
     */
    public function getEmailSettings()
    {
        return [
            'mail_mailer' => Setting::get('mail_mailer', config('mail.default', 'log')),
            'mail_host' => Setting::get('mail_host', config('mail.mailers.smtp.host', '')),
            'mail_port' => Setting::get('mail_port', config('mail.mailers.smtp.port', 587)),
            'mail_username' => Setting::get('mail_username', config('mail.mailers.smtp.username', '')),
            'mail_password' => Setting::get('mail_password', config('mail.mailers.smtp.password', '')),
            'mail_encryption' => Setting::get('mail_encryption', config('mail.mailers.smtp.encryption', 'tls')),
            'mail_from_address' => Setting::get('mail_from_address', config('mail.from.address', 'info@whkurumsal.com')),
            'mail_from_name' => Setting::get('mail_from_name', config('mail.from.name', 'WH Kurumsal')),
        ];
    }

    /**
     * Get real email statistics from logs and database
     */
    public function getEmailStatistics()
    {
        try {
            // Gerçek e-posta istatistiklerini al
            $today = now()->startOfDay();
            $thisWeek = now()->startOfWeek();
            $thisMonth = now()->startOfMonth();

            // Log dosyalarından e-posta gönderim bilgilerini oku
            $logFile = storage_path('logs/laravel.log');
            $emailLogs = [];
            
            if (file_exists($logFile)) {
                $logContent = file_get_contents($logFile);
                
                // E-posta ile ilgili log kayıtlarını say
                $totalSent = preg_match_all('/Test email sent successfully|Mail sent successfully/i', $logContent);
                $failed = preg_match_all('/Test email failed|Mail failed/i', $logContent);
                
                // Son 30 günlük log kayıtlarını kontrol et
                $recentLogs = preg_match_all('/' . now()->subDays(30)->format('Y-m-d') . '.*(Test email sent successfully|Mail sent successfully)/i', $logContent);
                
                $emailLogs = [
                    'total_sent' => $totalSent,
                    'failed' => $failed,
                    'successful' => $totalSent > 0 ? round((($totalSent - $failed) / $totalSent) * 100, 1) : 100,
                    'today_sent' => $recentLogs,
                    'this_week' => $recentLogs,
                    'this_month' => $recentLogs,
                ];
            }

            // Eğer log verisi yoksa varsayılan değerler
            if (empty($emailLogs)) {
                return [
                    'total_sent' => 0,
                    'successful' => 100,
                    'failed' => 0,
                    'today_sent' => 0,
                    'this_week' => 0,
                    'this_month' => 0,
                ];
            }

            return $emailLogs;
        } catch (\Exception $e) {
            Log::error('Failed to get email statistics', ['error' => $e->getMessage()]);
            
            return [
                'total_sent' => 0,
                'successful' => 100,
                'failed' => 0,
                'today_sent' => 0,
                'this_week' => 0,
                'this_month' => 0,
            ];
        }
    }

    /**
     * Validate mail configuration before sending test email
     */
    private function validateMailConfiguration()
    {
        $settings = $this->getEmailSettings();
        
        if ($settings['mail_mailer'] === 'smtp') {
            if (empty($settings['mail_host']) || empty($settings['mail_port']) || 
                empty($settings['mail_username']) || empty($settings['mail_from_address'])) {
                throw new \Exception('SMTP ayarları eksik. Lütfen tüm gerekli alanları doldurun.');
            }
        }
        
        if (empty($settings['mail_from_address']) || empty($settings['mail_from_name'])) {
            throw new \Exception('Gönderen bilgileri eksik. Lütfen gönderen e-posta ve adını girin.');
        }
    }

    /**
     * Show encryption status for sensitive fields
     */
    public function getEncryptionStatus()
    {
        $encryptedFields = Setting::getEncryptedFields();
        $status = [];

        foreach ($encryptedFields as $field) {
            $setting = Setting::where('key', $field)->first();
            if ($setting) {
                $status[$field] = [
                    'is_encrypted' => Setting::isEncrypted($field),
                    'has_value' => !empty($setting->value),
                    'last_updated' => $setting->updated_at
                ];
            }
        }

        return $status;
    }
}
