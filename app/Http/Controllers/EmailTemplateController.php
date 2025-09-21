<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Mail\QuoteMail;
use App\Mail\ServiceExpiryMail;
use App\Mail\WelcomeMail;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Quote;
use App\Models\Service;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    /**
     * Display email templates preview
     */
    public function index()
    {
        return view('email-templates.index');
    }

    /**
     * Preview welcome email template
     */
    public function welcome()
    {
        try {
            $customer = Customer::first();
            
            if (!$customer) {
                return back()->with('error', 'Test için müşteri bulunamadı.');
            }

            // Mail preview için view döndür
            $mail = new WelcomeMail($customer);
            
            // Customer type bilgisini al
            $customerType = $this->getCustomerTypeName($customer->customer_type);
            
            return view('emails.welcome', [
                'customer' => $customer,
                'customerType' => $customerType,
                'companyInfo' => $mail->companyInfo ?? [
                    'name' => \App\Models\Setting::get('site_name', 'WH Kurumsal'),
                    'email' => \App\Models\Setting::get('contact_email', 'info@whkurumsal.com'),
                    'phone' => \App\Models\Setting::get('contact_phone', '+90 212 123 4567'),
                    'address' => \App\Models\Setting::get('contact_address', 'İstanbul, Türkiye'),
                    'website' => \App\Models\Setting::get('website', 'https://whkurumsal.com'),
                    'support_email' => \App\Models\Setting::get('support_email', 'destek@whkurumsal.com')
                ]
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Welcome e-posta şablonu yüklenirken hata: ' . $e->getMessage());
        }
    }

    /**
     * Get customer type name in Turkish
     */
    private function getCustomerTypeName(?string $customerType): string
    {
        $types = [
            'individual' => 'Bireysel',
            'corporate' => 'Kurumsal',
            'bireysel' => 'Bireysel',
            'kurumsal' => 'Kurumsal',
        ];

        return $types[$customerType] ?? 'Müşteri';
    }

    /**
     * Preview invoice email template
     */
    public function invoice()
    {
        try {
            $invoice = Invoice::with('customer')->first();
            
            if (!$invoice) {
                return back()->with('error', 'Test için fatura bulunamadı.');
            }

            // Mail preview için view döndür
            $mail = new InvoiceMail($invoice);
            
            // Due date ve days remaining bilgilerini al
            $dueDate = $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('d.m.Y') : 'Belirtilmemiş';
            $daysRemaining = $invoice->due_date ? (int)now()->diffInDays($invoice->due_date, false) : null;
            
            return view('emails.invoice', [
                'invoice' => $invoice,
                'customer' => $invoice->customer,
                'dueDate' => $dueDate,
                'daysRemaining' => $daysRemaining,
                'companyInfo' => $mail->companyInfo ?? [
                    'name' => \App\Models\Setting::get('site_name', 'WH Kurumsal'),
                    'email' => \App\Models\Setting::get('contact_email', 'info@whkurumsal.com'),
                    'phone' => \App\Models\Setting::get('contact_phone', '+90 212 123 4567'),
                    'address' => \App\Models\Setting::get('contact_address', 'İstanbul, Türkiye'),
                    'website' => \App\Models\Setting::get('website', 'https://whkurumsal.com')
                ]
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Fatura e-posta şablonu yüklenirken hata: ' . $e->getMessage());
        }
    }

    /**
     * Preview quote email template
     */
    public function quote()
    {
        try {
            $quote = Quote::with('items')->first();
            
            if (!$quote) {
                return back()->with('error', 'Test için teklif bulunamadı.');
            }

            // Customer bilgilerini Quote'dan al (foreign key yerine embedded data)
            $customer = (object) [
                'id' => $quote->id, // Quote ID'yi kullan
                'name' => $quote->customer_name ?: 'Müşteri',
                'email' => $quote->customer_email ?: 'musteri@example.com',
                'phone' => $quote->customer_phone ?: 'Belirtilmemiş'
            ];

            // Mail preview için view döndür
            $mail = new QuoteMail($quote);
            
            // Valid until ve days remaining bilgilerini al
            $validUntil = $quote->valid_until ? \Carbon\Carbon::parse($quote->valid_until)->format('d.m.Y') : 'Belirtilmemiş';
            $daysRemaining = $quote->valid_until ? (int)now()->diffInDays($quote->valid_until, false) : null;
            
            return view('emails.quote', [
                'quote' => $quote,
                'customer' => $customer,
                'companyInfo' => $mail->companyInfo ?? [
                    'name' => \App\Models\Setting::get('site_name', 'WH Kurumsal'),
                    'email' => \App\Models\Setting::get('contact_email', 'info@whkurumsal.com'),
                    'phone' => \App\Models\Setting::get('contact_phone', '+90 212 123 4567'),
                    'address' => \App\Models\Setting::get('contact_address', 'İstanbul, Türkiye'),
                    'website' => \App\Models\Setting::get('website', 'https://whkurumsal.com'),
                    'bank_name' => \App\Models\Setting::get('bank_name'),
                    'bank_iban' => \App\Models\Setting::get('bank_iban'),
                    'tax_number' => \App\Models\Setting::get('tax_number')
                ],
                'validUntil' => $validUntil,
                'daysRemaining' => $daysRemaining,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Teklif e-posta şablonu yüklenirken hata: ' . $e->getMessage());
        }
    }

    /**
     * Preview service expiry email template
     */
    public function serviceExpiry()
    {
        try {
            $service = Service::with('customer')->first();
            
            if (!$service) {
                return back()->with('error', 'Test için hizmet bulunamadı.');
            }

            // Mail preview için view döndür
            $mail = new ServiceExpiryMail($service, 5);
            
            // Service type ve expiry date bilgilerini al
            $serviceType = ucfirst($service->service_type);
            $expiryDate = $service->end_date ? \Carbon\Carbon::parse($service->end_date)->format('d.m.Y') : 'Belirtilmemiş';
            
            // Days remaining'i integer olarak al
            $daysRemaining = 5; // Test için sabit değer, gerçek uygulamada: (int)now()->diffInDays($service->end_date, false)
            
            return view('emails.service-expiry', [
                'service' => $service,
                'customer' => $service->customer,
                'serviceType' => $serviceType,
                'expiryDate' => $expiryDate,
                'daysRemaining' => 5,
                'companyInfo' => $mail->companyInfo ?? [
                    'name' => \App\Models\Setting::get('site_name', 'WH Kurumsal'),
                    'email' => \App\Models\Setting::get('contact_email', 'info@whkurumsal.com'),
                    'phone' => \App\Models\Setting::get('contact_phone', '+90 212 123 4567'),
                    'address' => \App\Models\Setting::get('contact_address', 'İstanbul, Türkiye'),
                    'website' => \App\Models\Setting::get('website', 'https://whkurumsal.com'),
                    'bank_name' => \App\Models\Setting::get('bank_name'),
                    'bank_iban' => \App\Models\Setting::get('bank_iban'),
                    'tax_number' => \App\Models\Setting::get('tax_number')
                ]
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Hizmet süresi e-posta şablonu yüklenirken hata: ' . $e->getMessage());
        }
    }

    /**
     * Send test email
     */
    public function sendTest(Request $request)
    {
        $request->validate([
            'template' => 'required|in:welcome,invoice,quote,service-expiry',
            'email' => 'required|email',
        ]);

        try {
            switch ($request->template) {
                case 'welcome':
                    $customer = Customer::first();
                    if ($customer) {
                        \Mail::to($request->email)->queue(new WelcomeMail($customer));
                    }
                    break;
                    
                case 'invoice':
                    $invoice = Invoice::with('customer')->first();
                    if ($invoice) {
                        \Mail::to($request->email)->queue(new InvoiceMail($invoice));
                    }
                    break;
                    
                case 'quote':
                    $quote = Quote::with('items')->first();
                    if ($quote) {
                        // Customer bilgilerini Quote'dan al
                        $customer = (object) [
                            'id' => $quote->id,
                            'name' => $quote->customer_name ?: 'Müşteri',
                            'email' => $quote->customer_email ?: 'musteri@example.com',
                            'phone' => $quote->customer_phone ?: 'Belirtilmemiş'
                        ];
                        
                        // QuoteMail'e customer bilgilerini geç
                        $mail = new QuoteMail($quote);
                        $mail->customer = $customer;
                        
                        \Mail::to($request->email)->queue($mail);
                    }
                    break;
                    
                case 'service-expiry':
                    $service = Service::with('customer')->first();
                    if ($service) {
                        \Mail::to($request->email)->queue(new ServiceExpiryMail($service, 5));
                    }
                    break;
            }

            return back()->with('status', 'Test e-postası başarıyla gönderildi.');
        } catch (\Exception $e) {
            return back()->with('error', 'E-posta gönderilirken hata oluştu: ' . $e->getMessage());
        }
    }
}
