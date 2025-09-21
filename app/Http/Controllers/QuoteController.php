<?php

namespace App\Http\Controllers;

use App\Mail\QuoteMail;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\SettingsHelper;
use Illuminate\Support\Facades\DB;

class QuoteController extends Controller
{
    use SettingsHelper;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Sadece gerekli alanları seç ve customer ile eager loading yap
        $quotes = Quote::select(['id', 'customer_id', 'number', 'title', 'status', 'quote_date', 'total'])
            ->with(['customer:id,name,surname,email'])
            ->latest('quote_date')
            ->paginate(15);

        // İstatistikleri tek sorguda al
        $metricsResult = DB::select("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) as draft,
                SUM(CASE WHEN status = 'sent' THEN 1 ELSE 0 END) as sent,
                SUM(CASE WHEN status = 'accepted' THEN 1 ELSE 0 END) as accepted
            FROM quotes
        ")[0];

        // stdClass'ı array'e çevir
        $metrics = [
            'total' => (int) $metricsResult->total,
            'draft' => (int) $metricsResult->draft,
            'sent' => (int) $metricsResult->sent,
            'accepted' => (int) $metricsResult->accepted
        ];

        return view('quotes.index', compact('quotes','metrics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Sadece gerekli alanları seç
        $customers = Customer::select(['id', 'name', 'surname', 'email', 'customer_type'])
            ->orderBy('name')
            ->get();
        $currencySymbol = '₺';
        return view('quotes.create', compact('customers','currencySymbol'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => ['nullable','exists:customers,id'],
            'customer_name' => ['nullable','string','max:255'],
            'customer_email' => ['nullable','email','max:255'],
            'customer_phone' => ['nullable','string','max:50'],
            'title' => ['nullable','string','max:255'],
            'status' => ['required','in:draft,sent,accepted,rejected,expired'],
            'quote_date' => ['required','date'],
            'valid_until' => ['nullable','date','after_or_equal:quote_date'],
            'tax_rate' => ['nullable','numeric','min:0','max:50'],
            'discount_amount' => ['nullable','numeric','min:0'],
            'notes' => ['nullable','string','max:1000'],
            'terms' => ['nullable','string','max:1000'],
            'items' => ['required','array','min:1'],
            'items.*.description' => ['required','string','max:255'],
            'items.*.qty' => ['required','numeric','min:0.01'],
            'items.*.unit_price' => ['required','numeric','min:0'],
            'send_email' => ['nullable','boolean'],
        ]);

        // Ayarlardan varsayılan değerleri al
        $financialSettings = $this->getFinancialSettings();
        $defaultTaxRate = $financialSettings['tax_rate'];
        
        $quote = Quote::create([
            'number' => 'FT-'.str_pad((string)(Quote::max('id') + 1), 4, '0', STR_PAD_LEFT),
            'customer_id' => $validated['customer_id'] ?? null,
            'customer_name' => $validated['customer_name'] ?? null,
            'customer_email' => $validated['customer_email'] ?? null,
            'customer_phone' => $validated['customer_phone'] ?? null,
            'title' => $validated['title'] ?? null,
            'status' => $validated['status'],
            'quote_date' => $validated['quote_date'],
            'valid_until' => $validated['valid_until'] ?? null,
            'tax_rate' => $validated['tax_rate'] ?? $defaultTaxRate,
            'discount_amount' => $validated['discount_amount'] ?? 0,
            'notes' => $validated['notes'] ?? null,
            'terms' => $validated['terms'] ?? null,
        ]);

        foreach ($validated['items'] as $it) {
            $quote->items()->create([
                'description' => $it['description'],
                'qty' => $it['qty'],
                'unit_price' => $it['unit_price'],
                'line_total' => ((float)$it['qty'] * (float)$it['unit_price']),
            ]);
        }

        $quote->load('items');
        $quote->recalcTotals();
        $quote->save();

        // E-posta gönderme
        if ($request->has('send_email') && $request->send_email) {
            $this->sendQuoteEmail($quote);
        }

        return redirect()->route('quotes.show', $quote)->with('status','Teklif oluşturuldu');
    }

    /**
     * Display the specified resource.
     */
    public function show(Quote $quote)
    {
        // Sadece gerekli ilişkileri yükle
        $quote->load([
            'items:id,quote_id,description,qty,unit_price,line_total',
            'customer:id,name,surname,email,phone'
        ]);
        return view('quotes.show', compact('quote'));
    }

    public function pdf(Quote $quote)
    {
        // Sadece gerekli ilişkileri yükle
        $quote->load([
            'items:id,quote_id,description,qty,unit_price,line_total',
            'customer:id,name,surname,email,phone,address'
        ]);
        
        // Site ayarlarını al
        $site = $this->getSiteSettings();
        
        // Eksik site ayarlarını varsayılan değerlerle doldur
        $site = array_merge([
            'contact_phone' => '',
            'contact_email' => '',
            'company_name' => 'Şirket Adı',
            'company_address' => 'Şirket Adresi',
            'company_tagline' => 'Profesyonel Hizmetler',
            'company_website' => 'www.sirket.com',
        ], $site);
        
        $pdf = PDF::loadView('quotes.pdf', compact('quote','site'));
        
        // Türkçe karakter desteği için PDF ayarları
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'DejaVu Sans',
            'chroot' => public_path(),
            'defaultMediaType' => 'screen',
            'defaultPaperSize' => 'a4',
            'defaultPaperOrientation' => 'portrait',
            'dpi' => 150,
            'fontHeightRatio' => 0.9,
            'enableFontSubsetting' => true,
            'isFontSubsettingEnabled' => true,
            'isPhpEnabled' => false,
            'isJavascriptEnabled' => false,
            'isRemoteEnabled' => false,
        ]);
        
        return $pdf->download('teklif-'.$quote->number.'.pdf');
    }

    /**
     * Send quote email to customer
     */
    public function sendEmail(Quote $quote)
    {
        try {
            $this->sendQuoteEmail($quote);
            return back()->with('status', 'Teklif e-postası başarıyla gönderildi.');
        } catch (\Exception $e) {
            return back()->with('error', 'E-posta gönderilirken hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Send quote email
     */
    private function sendQuoteEmail(Quote $quote)
    {
        $quote->load('customer');
        
        $email = $quote->customer->email ?? $quote->customer_email;
        
        if (!$email) {
            throw new \Exception('Müşteri e-posta adresi bulunamadı.');
        }

        $companyInfo = $this->getCompanyInfo();
        Mail::to($email)
            ->queue(new QuoteMail($quote, $companyInfo));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quote $quote)
    {
        $quote->load('items');
        $customers = Customer::select(['id', 'name', 'surname', 'email', 'customer_type'])
            ->orderBy('name')
            ->get();
        $currencySymbol = '₺';
        return view('quotes.edit', compact('quote','customers','currencySymbol'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quote $quote)
    {
        $validated = $request->validate([
            'customer_id' => ['nullable', 'exists:customers,id'],
            'customer_name' => ['nullable', 'string', 'max:255', 'min:2'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:50', 'regex:/^[\+]?[0-9\s\-\(\)]{10,}$/'],
            'title' => ['nullable', 'string', 'max:255', 'min:3'],
            'status' => ['required', 'in:draft,sent,accepted,rejected,expired'],
            'quote_date' => ['required', 'date', 'before_or_equal:today'],
            'valid_until' => ['nullable', 'date', 'after_or_equal:quote_date'],
            'tax_rate' => ['nullable', 'numeric', 'min:0', 'max:50'],
            'discount_amount' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'terms' => ['nullable', 'string', 'max:1000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.description' => ['required', 'string', 'max:255', 'min:3'],
            'items.*.qty' => ['required', 'numeric', 'min:0.01', 'max:999999'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
        ], [
            'customer_id.exists' => 'Seçilen müşteri bulunamadı.',
            'customer_name.min' => 'Müşteri adı en az 2 karakter olmalıdır.',
            'customer_email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'customer_phone.regex' => 'Geçerli bir telefon numarası giriniz.',
            'title.min' => 'Başlık en az 3 karakter olmalıdır.',
            'status.required' => 'Durum seçimi zorunludur.',
            'status.in' => 'Geçersiz durum.',
            'quote_date.required' => 'Teklif tarihi zorunludur.',
            'quote_date.date' => 'Geçerli bir tarih giriniz.',
            'quote_date.before_or_equal' => 'Teklif tarihi bugünden sonra olamaz.',
            'valid_until.date' => 'Geçerli bir geçerlilik tarihi giriniz.',
            'valid_until.after_or_equal' => 'Geçerlilik tarihi teklif tarihinden önce olamaz.',
            'tax_rate.numeric' => 'KDV oranı sayısal olmalıdır.',
            'tax_rate.min' => 'KDV oranı 0\'dan küçük olamaz.',
            'tax_rate.max' => 'KDV oranı 50\'den büyük olamaz.',
            'discount_amount.numeric' => 'İndirim tutarı sayısal olmalıdır.',
            'discount_amount.min' => 'İndirim tutarı 0\'dan küçük olamaz.',
            'discount_amount.max' => 'İndirim tutarı çok yüksek.',
            'items.required' => 'En az bir ürün/hizmet eklenmelidir.',
            'items.min' => 'En az bir ürün/hizmet eklenmelidir.',
            'items.*.description.required' => 'Ürün/hizmet açıklaması zorunludur.',
            'items.*.description.min' => 'Ürün/hizmet açıklaması en az 3 karakter olmalıdır.',
            'items.*.qty.required' => 'Miktar zorunludur.',
            'items.*.qty.numeric' => 'Miktar sayısal olmalıdır.',
            'items.*.qty.min' => 'Miktar 0.01\'den küçük olamaz.',
            'items.*.qty.max' => 'Miktar çok yüksek.',
            'items.*.unit_price.required' => 'Birim fiyat zorunludur.',
            'items.*.unit_price.numeric' => 'Birim fiyat sayısal olmalıdır.',
            'items.*.unit_price.min' => 'Birim fiyat 0\'dan küçük olamaz.',
            'items.*.unit_price.max' => 'Birim fiyat çok yüksek.',
        ]);

        // Customer validation logic
        if (!$validated['customer_id'] && !$validated['customer_name']) {
            $request->validate([
                'customer_name' => ['required', 'string', 'max:255', 'min:2'],
            ], [
                'customer_name.required' => 'Müşteri seçilmediyse müşteri adı zorunludur.',
            ]);
        }

        $quote->update($validated);

        // Update items
        $quote->items()->delete();
        foreach ($validated['items'] as $item) {
            $quote->items()->create([
                'description' => $item['description'],
                'qty' => $item['qty'],
                'unit_price' => $item['unit_price'],
                'line_total' => ((float)$item['qty'] * (float)$item['unit_price']),
            ]);
        }

        $quote->load('items');
        $quote->recalcTotals();
        $quote->save();

        return redirect()->route('quotes.show', $quote)->with('status', 'Teklif güncellendi');
    }

    public function toInvoice(Quote $quote)
    {
        // Convert quote to invoice
        $quote->load('items','customer');
        
        // Ayarlardan finansal bilgileri al
        $financialSettings = $this->getFinancialSettings();
        $currency = $financialSettings['currency'] ?? 'TRY';
        
        $invoice = \App\Models\Invoice::create([
            'customer_id' => $quote->customer_id,
            'issue_date' => now()->toDateString(),
            'due_date' => now()->addDays(7)->toDateString(),
            'status' => 'sent',
            'currency' => $currency,
        ]);
        
        foreach ($quote->items as $it) {
            $invoice->items()->create([
                'description' => $it->description,
                'qty' => $it->qty,
                'unit_price' => $it->unit_price,
                'tax_rate' => $quote->tax_rate,
                // line_total'ı KDV hariç olarak ayarla (qty × unit_price)
                // KDV'yi fatura tarafı hesaplayacak
            ]);
        }
        
        $invoice->load('items');
        // Fatura toplamlarını yeniden hesapla (KDV dahil)
        $invoice->calculateTotalsFromItems();
        $invoice->save();

        return redirect()->route('invoices.show', $invoice)->with('status','Teklif faturaya dönüştürüldü');
    }

    /**
     * Accept quote
     */
    public function accept(Quote $quote)
    {
        // Teklifi kabul edildi olarak işaretle
        $quote->update(['status' => 'accepted']);
        
        // Müşteriye kabul e-postası gönder
        try {
            Mail::to($quote->customer_email)->queue(new QuoteMail($quote));
        } catch (\Exception $e) {
            // E-posta gönderilemezse log'a kaydet
            \Log::error('Quote acceptance email could not be sent: ' . $e->getMessage());
        }
        
        return redirect()->route('quotes.show', $quote)->with('status', 'Teklif kabul edildi ve müşteriye bilgilendirme e-postası gönderildi');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quote $quote)
    {
        try {
            // Önce quote items'ları sil
            $quote->items()->delete();
            
            // Sonra quote'u sil
            $quote->delete();
            
            return redirect()->route('quotes.index')
                ->with('status', 'Teklif başarıyla silindi.');
        } catch (\Exception $e) {
            \Log::error('Quote deletion failed', [
                'quote_id' => $quote->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('quotes.index')
                ->with('error', 'Teklif silinirken hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Site ayarlarını al
     */
    private function getSiteSettings(): array
    {
        $settings = \App\Models\Setting::all()->pluck('value', 'key')->toArray();
        
        return [
            'company_name' => $settings['company_name'] ?? $settings['site_name'] ?? 'Şirket Adı',
            'company_address' => $settings['company_address'] ?? $settings['contact_address'] ?? 'Şirket Adresi',
            'contact_phone' => $settings['contact_phone'] ?? $settings['company_phone'] ?? 'Telefon',
            'contact_email' => $settings['contact_email'] ?? $settings['company_email'] ?? 'E-posta',
            'company_website' => $settings['company_website'] ?? 'www.sirket.com',
            'company_tagline' => $settings['site_description'] ?? 'Profesyonel Hizmetler',
        ];
    }

}
