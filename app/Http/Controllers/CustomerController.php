<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use App\Models\Customer;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use App\SettingsHelper;
use App\Services\RevenueCacheService;

class CustomerController extends Controller
{
    use SettingsHelper;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ana sorgu - balance hesaplamaları ile birlikte
        $query = Customer::withBalanceAndStats();

        // Arama filtresi
        if ($request->filled('q')) {
            $q = $request->string('q');
            $query->where(function ($qq) use ($q) {
                $qq->where('customers.name', 'like', "%{$q}%")
                   ->orWhere('customers.email', 'like', "%{$q}%")
                   ->orWhere('customers.phone', 'like', "%{$q}%");
            });
        }

        // Durum filtresi
        if ($request->filled('status') && in_array($request->status, ['active','inactive'])) {
            $query->where('customers.is_active', $request->status === 'active');
        }

        // Bakiye filtreleri - artık subquery ile optimize edildi
        if ($request->filled('balance_min') || $request->filled('balance_max')) {
            $minBalance = $request->filled('balance_min') ? (float) $request->input('balance_min') : null;
            $maxBalance = $request->filled('balance_max') ? (float) $request->input('balance_max') : null;
            
            if (!is_null($minBalance)) {
                $query->where('current_balance', '>=', $minBalance);
            }
            if (!is_null($maxBalance)) {
                $query->where('current_balance', '<=', $maxBalance);
            }
        }

        // Sıralama ve sayfalama
        $customers = $query->latest('customers.created_at')->paginate(15)->withQueryString();

        // Toplam istatistikler - tek sorguda
        $statsResult = DB::select("
            SELECT 
                COUNT(*) as total_customers,
                SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_customers,
                SUM(CASE WHEN ledger_balance.balance > 0 THEN ledger_balance.balance ELSE 0 END) as total_receivable,
                SUM(CASE WHEN ledger_balance.balance < 0 THEN ABS(ledger_balance.balance) ELSE 0 END) as total_payable
            FROM customers
            LEFT JOIN (
                SELECT 
                    customer_id,
                    SUM(CASE WHEN type = 'debit' THEN amount ELSE 0 END) - SUM(CASE WHEN type = 'credit' THEN amount ELSE 0 END) as balance
                FROM ledger_entries 
                GROUP BY customer_id
            ) as ledger_balance ON customers.id = ledger_balance.customer_id
        ")[0];

        // Cache'den MRR verilerini al
        $revenueCache = app(RevenueCacheService::class);
        $mrrData = $revenueCache->getMRRData();
        $monthlyRevenue = $mrrData['total_mrr'];

        // View için veri hazırlama
        $totalCustomers = (int) $statsResult->total_customers;
        $activeCustomers = (int) $statsResult->active_customers;
        $totalReceivable = (float) $statsResult->total_receivable;
        $totalPayable = (float) $statsResult->total_payable;

        return view('customers.index', compact(
            'customers', 
            'totalCustomers', 
            'activeCustomers', 
            'monthlyRevenue',
            'totalReceivable', 
            'totalPayable'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {













        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:2'],
            'surname' => ['nullable', 'string', 'max:255', 'min:2'],
            'email' => ['nullable', 'email', 'max:255', 'unique:customers,email'],
            'phone' => ['nullable', 'string', 'max:50', 'regex:/^[\+]?[0-9\s\-\(\)]{10,}$/'],
            'phone_mobile' => ['nullable', 'string', 'max:50', 'regex:/^[\+]?[0-9\s\-\(\)]{10,}$/'],
            'customer_type' => ['nullable', 'in:individual,corporate,bireysel,kurumsal'],
            'website' => ['nullable', 'url', 'max:255'],
            'tax_number' => ['nullable', 'string', 'max:50', 'regex:/^[0-9]{10,11}$/'],
            'address' => ['nullable', 'string', 'max:1000'],
            'city' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'zip' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:100'],
            'invoice_address' => ['nullable', 'string', 'max:1000'],
            'invoice_city' => ['nullable', 'string', 'max:100'],
            'invoice_district' => ['nullable', 'string', 'max:100'],
            'invoice_zip' => ['nullable', 'string', 'max:20'],
            'invoice_country' => ['nullable', 'string', 'max:100'],
            'is_active' => ['boolean'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'send_welcome_email' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Müşteri adı zorunludur.',
            'name.min' => 'Müşteri adı en az 2 karakter olmalıdır.',
            'surname.min' => 'Müşteri soyadı en az 2 karakter olmalıdır.',
            'email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'email.unique' => 'Bu e-posta adresi zaten kullanılıyor.',
            'phone.regex' => 'Geçerli bir telefon numarası giriniz.',
            'phone_mobile.regex' => 'Geçerli bir cep telefonu numarası giriniz.',
            'customer_type.in' => 'Geçersiz müşteri türü.',
            'website.url' => 'Geçerli bir website adresi giriniz.',
            'tax_number.regex' => 'Vergi numarası 10-11 haneli olmalıdır.',
            'address.max' => 'Adres çok uzun.',
            'city.max' => 'Şehir adı çok uzun.',
            'district.max' => 'İlçe adı çok uzun.',
            'zip.max' => 'Posta kodu çok uzun.',
            'country.max' => 'Ülke adı çok uzun.',
            'invoice_address.max' => 'Fatura adresi çok uzun.',
            'invoice_city.max' => 'Fatura şehri çok uzun.',
            'invoice_district.max' => 'Fatura ilçesi çok uzun.',
            'invoice_zip.max' => 'Fatura posta kodu çok uzun.',
            'invoice_country.max' => 'Fatura ülkesi çok uzun.',
            'notes.max' => 'Notlar çok uzun.',
        ]);

        $customer = Customer::create($validated);

        // Hoş geldin e-postası gönderme
        if ($request->has('send_welcome_email') && $request->send_welcome_email && $customer->email) {
            $this->sendWelcomeEmail($customer);
        }

        return redirect()->route('customers.show', $customer)->with('status', 'Müşteri oluşturuldu');
    }

    /**
     * Send welcome email to customer
     */
    public function sendWelcomeEmail(Customer $customer)
    {
        try {
            if (!$customer->email) {
                throw new \Exception('Müşteri e-posta adresi bulunamadı.');
            }

            $companyInfo = $this->getCompanyInfo();
            Mail::to($customer->email)
                ->queue(new WelcomeMail($customer, $companyInfo));

            return back()->with('status', 'Hoş geldin e-postası başarıyla gönderildi.');
        } catch (\Exception $e) {
            return back()->with('error', 'E-posta gönderilirken hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        // Müşteri bilgilerini eager loading ile yükle
        $customer->load([
            'ledgerEntries' => function ($query) {
                // Önce mevcut kolonları kontrol et
                $availableColumns = ['id', 'customer_id'];
                
                // Yeni kolonları ekle (varsa)
                if (Schema::hasColumn('ledger_entries', 'entry_date')) {
                    $availableColumns[] = 'entry_date';
                }
                if (Schema::hasColumn('ledger_entries', 'debit')) {
                    $availableColumns[] = 'debit';
                }
                if (Schema::hasColumn('ledger_entries', 'credit')) {
                    $availableColumns[] = 'credit';
                }
                if (Schema::hasColumn('ledger_entries', 'notes')) {
                    $availableColumns[] = 'notes';
                }
                if (Schema::hasColumn('ledger_entries', 'related_type')) {
                    $availableColumns[] = 'related_type';
                }
                if (Schema::hasColumn('ledger_entries', 'related_id')) {
                    $availableColumns[] = 'related_id';
                }
                if (Schema::hasColumn('ledger_entries', 'type')) {
                    $availableColumns[] = 'type';
                }
                if (Schema::hasColumn('ledger_entries', 'amount')) {
                    $availableColumns[] = 'amount';
                }
                if (Schema::hasColumn('ledger_entries', 'balance')) {
                    $availableColumns[] = 'balance';
                }
                if (Schema::hasColumn('ledger_entries', 'description')) {
                    $availableColumns[] = 'description';
                }
                if (Schema::hasColumn('ledger_entries', 'reference_id')) {
                    $availableColumns[] = 'reference_id';
                }
                if (Schema::hasColumn('ledger_entries', 'reference_type')) {
                    $availableColumns[] = 'reference_type';
                }
                
                $query->select($availableColumns);
                
                // Sadece mevcut kolonlara göre sıralama yap
                if (Schema::hasColumn('ledger_entries', 'entry_date')) {
                    $query->orderBy('entry_date');
                }
                $query->orderBy('id');
            },
            'services' => function ($query) {
                // Sadece gerekli kolonları seç
                $query->select([
                    'id', 'customer_id', 'sell_price', 'cycle', 'status', 
                    'payment_type', 'start_date', 'cost_price'
                ])->where('status', 'active');
            }
        ]);

        // Cari hareketler ve bakiye - optimize edildi
        $entries = $customer->ledgerEntries;
        $running = 0.0;
        $statement = $entries->map(function ($e) use (&$running) {
            // Hem eski hem de yeni kolonları destekle
            $debit = (float)($e->debit ?? $e->amount ?? 0);
            $credit = (float)($e->credit ?? 0);
            
            // Eğer type kolonu varsa ve 'debit' ise amount'u debit olarak kullan
            if ($e->type === 'debit') {
                $debit = (float)($e->amount ?? 0);
                $credit = 0;
            } elseif ($e->type === 'credit') {
                $debit = 0;
                $credit = (float)($e->amount ?? 0);
            }
            
            $running = $running + $debit - $credit;
            $e->running_balance = $running;
            return $e;
        });

        $currentBalance = (float) ($statement->last()->running_balance ?? 0.0);

        // Müşteriye ait hizmetlerin aylık gelirini hesapla - optimize edildi
        $customerMonthlyRevenue = (float) $customer->services->sum(function ($svc) {
            // Hem eski hem de yeni kolonları destekle
            $price = (float) ($svc->sell_price ?? $svc->price ?? 0);
            $cycle = $svc->cycle ?? $svc->payment_cycle ?? 'monthly';
            $paymentType = $svc->payment_type ?? 'recurring';
            
            // Sadece taksitli ödeme hizmetleri MRR'ye dahil edilir
            if ($paymentType === 'installment' || $paymentType === 'recurring') {
                $months = match($cycle) {
                    'monthly' => 1,
                    'quarterly' => 3,
                    'semiannually' => 6,
                    'yearly' => 12,
                    'biennially' => 24,
                    'triennially' => 36,
                    default => 12
                };
                return $months > 0 ? $price / $months : 0;
            }
            
            // Upfront payment için MRR hesaplanmaz
            return 0;
        });

        return view('customers.show', compact('customer', 'customerMonthlyRevenue', 'statement', 'currentBalance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'customer_type' => ['required', 'in:individual,corporate'],
            'name' => ['required', 'string', 'max:255', 'min:2'],
            'surname' => ['nullable', 'string', 'max:255', 'min:2'],
            'email' => ['nullable', 'email', 'max:255', 'unique:customers,email,' . $customer->id],
            'phone' => ['nullable', 'string', 'max:50', 'regex:/^[\+]?[0-9\s\-\(\)]{10,}$/'],
            'phone_mobile' => ['nullable', 'string', 'max:50', 'regex:/^[\+]?[0-9\s\-\(\)]{10,}$/'],
            'website' => ['nullable', 'url', 'max:255'],
            'tax_number' => ['nullable', 'string', 'max:50', 'regex:/^[0-9]{10,11}$/'],
            'address' => ['nullable', 'string', 'max:1000'],
            'city' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'zip' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:100'],
            'invoice_address' => ['nullable', 'string', 'max:1000'],
            'invoice_city' => ['nullable', 'string', 'max:100'],
            'invoice_district' => ['nullable', 'string', 'max:100'],
            'invoice_zip' => ['nullable', 'string', 'max:20'],
            'invoice_country' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'customer_type.required' => 'Müşteri türü zorunludur.',
            'customer_type.in' => 'Geçersiz müşteri türü.',
            'name.required' => 'Müşteri adı zorunludur.',
            'name.min' => 'Müşteri adı en az 2 karakter olmalıdır.',
            'surname.min' => 'Müşteri soyadı en az 2 karakter olmalıdır.',
            'email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'email.unique' => 'Bu e-posta adresi zaten kullanılıyor.',
            'phone.regex' => 'Geçerli bir telefon numarası giriniz.',
            'phone_mobile.regex' => 'Geçerli bir cep telefonu numarası giriniz.',
            'website.url' => 'Geçerli bir website adresi giriniz.',
            'tax_number.regex' => 'Vergi numarası 10-11 haneli olmalıdır.',
            'address.max' => 'Adres çok uzun.',
            'city.max' => 'Şehir adı çok uzun.',
            'district.max' => 'İlçe adı çok uzun.',
            'zip.max' => 'Posta kodu çok uzun.',
            'country.max' => 'Ülke adı çok uzun.',
            'invoice_address.max' => 'Fatura adresi çok uzun.',
            'invoice_city.max' => 'Fatura şehri çok uzun.',
            'invoice_district.max' => 'Fatura ilçesi çok uzun.',
            'invoice_zip.max' => 'Fatura posta kodu çok uzun.',
            'invoice_country.max' => 'Fatura ülkesi çok uzun.',
            'notes.max' => 'Notlar çok uzun.',
        ]);

        $validated['is_active'] = (bool) ($validated['is_active'] ?? $customer->is_active);

        if ($request->boolean('copy_address')) {
            $validated['invoice_address'] = $validated['invoice_address'] ?? $validated['address'] ?? null;
            $validated['invoice_city'] = $validated['invoice_city'] ?? $validated['city'] ?? null;
            $validated['invoice_district'] = $validated['invoice_district'] ?? $validated['district'] ?? null;
            $validated['invoice_zip'] = $validated['invoice_zip'] ?? $validated['zip'] ?? null;
            $validated['invoice_country'] = $validated['invoice_country'] ?? $validated['country'] ?? null;
        }

        $customer->update($validated);

        return redirect()->route('customers.show', $customer)->with('status', 'Müşteri güncellendi');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('status', 'Müşteri silindi');
    }
}
