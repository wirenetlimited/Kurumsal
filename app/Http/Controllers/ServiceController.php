<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Domain;
use App\Models\Hosting;
use App\Models\Provider;
use App\Models\Service;
use App\Models\Setting;
use App\Services\RevenueCacheService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Sadece gerekli alanları seç ve ilişkileri yükle
        $query = Service::select([
            'id', 'customer_id', 'provider_id', 'service_type', 'service_code', 'status', 
            'start_date', 'end_date', 'cycle', 'payment_type', 'sell_price'
        ])->with([
            'customer:id,name,surname,email',
            'provider:id,name',
            'domain:id,service_id,domain_name',
            'hosting:id,service_id,plan_name,server_name'
        ]);

        // Filters: due_in (days) and expired toggle
        $dueIn = $request->integer('due_in');
        if ($dueIn) {
            $query->whereDate('end_date', '>=', now()->toDateString())
                  ->whereDate('end_date', '<=', now()->addDays($dueIn)->toDateString());
        }

        // Sorting by end date
        $sort = $request->string('sort')->toString();
        if ($sort === 'end_asc') {
            $query->orderBy('end_date', 'asc');
        } elseif ($sort === 'end_desc') {
            $query->orderBy('end_date', 'desc');
        } else {
            $query->latest('id');
        }

        $services = $query->paginate(15)->withQueryString();

        // Attach days remaining for view
        $services->getCollection()->transform(function (Service $svc) {
            if ($svc->end_date) {
                $days = (int)now()->diffInDays(Carbon::parse($svc->end_date), false);
                $svc->days_remaining = abs((int)$days);
            } else {
                $svc->days_remaining = null;
            }
            return $svc;
        });

        // İstatistikleri tek sorguda al
        $metricsResult = DB::select("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN service_type = 'domain' THEN 1 ELSE 0 END) as domains,
                SUM(CASE WHEN service_type = 'hosting' THEN 1 ELSE 0 END) as hostings
            FROM services
        ")[0];

        // stdClass'ı array'e çevir
        $metrics = [
            'total' => (int) $metricsResult->total,
            'domains' => (int) $metricsResult->domains,
            'hostings' => (int) $metricsResult->hostings
        ];

        // Cache'den MRR verilerini al
        $revenueCache = app(RevenueCacheService::class);
        $mrrData = $revenueCache->getMRRData();
        $monthlyRevenue = $mrrData['total_mrr'];

        return view('services.index', compact('services', 'metrics', 'monthlyRevenue', 'dueIn', 'sort'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::select(['id', 'name', 'surname', 'email', 'customer_type'])
            ->orderBy('name')
            ->get();
        $providers = Provider::orderBy('name')->get();
        return view('services.create', compact('customers', 'providers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Debug: Gelen request verilerini logla
        \Log::info('Service creation request:', $request->all());
        
        // Dynamically build allowed service types from settings
        $serviceTypesJson = Setting::get('service_types');
        $serviceTypesArr = $serviceTypesJson ? json_decode($serviceTypesJson, true) : [];
        if (!is_array($serviceTypesArr)) { $serviceTypesArr = []; }
        $allowedServiceTypeIds = array_values(array_filter(array_map(function ($item) {
            if (is_array($item)) {
                if (isset($item['id'])) { return $item['id']; }
                if (isset($item['value'])) { return $item['value']; }
            }
            return is_string($item) ? $item : null;
        }, $serviceTypesArr)));
        if (empty($allowedServiceTypeIds)) {
            $allowedServiceTypeIds = ['domain','hosting','ssl','email','other'];
        }
        $serviceTypeRule = 'in:' . implode(',', $allowedServiceTypeIds);

        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'provider_id' => ['nullable', 'exists:providers,id'],
            'service_type' => ['required', $serviceTypeRule],
            'status' => ['required', 'in:' . implode(',', \App\Enums\ServiceStatus::values())],
            'start_date' => ['nullable', 'date', 'before_or_equal:today'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'cycle' => ['required', 'in:monthly,quarterly,semiannually,yearly,biennially,triennially'],
            'payment_type' => ['required', 'in:upfront,installment'],
            'cost_price' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'sell_price' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'notes' => ['nullable', 'string', 'max:1000'],

            // domain validation
            'domain_name' => [
                'nullable', 
                'string', 
                'max:255',
                'regex:/^[a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?(\.[a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?)*$/'
            ],
            'registrar_ref' => ['nullable', 'string', 'max:255'],
            'auth_code' => ['nullable', 'string', 'max:255'],

            // hosting validation
            'plan_name' => ['nullable', 'string', 'max:255'],
            'server_name' => [
                'nullable', 
                'string', 
                'max:255',
                'regex:/^[a-zA-Z0-9]([a-zA-Z0-9\-\.]{0,61}[a-zA-Z0-9])?$/'
            ],
            'ip_address' => [
                'nullable', 
                'string', 
                'max:45',
                'regex:/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/'
            ],
            'cpanel_username' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z0-9_-]+$/'],
            'panel_ref' => ['nullable', 'string', 'max:255'],
        ], [
            'customer_id.required' => 'Müşteri seçimi zorunludur.',
            'customer_id.exists' => 'Seçilen müşteri bulunamadı.',
            'service_type.required' => 'Hizmet türü seçimi zorunludur.',
            'service_type.in' => 'Geçersiz hizmet türü.',
            'status.required' => 'Durum seçimi zorunludur.',
            'status.in' => 'Geçersiz durum.',
            'start_date.date' => 'Geçersiz başlangıç tarihi.',
            'start_date.before_or_equal' => 'Başlangıç tarihi bugünden sonra olamaz.',
            'end_date.date' => 'Geçersiz bitiş tarihi.',
            'end_date.after_or_equal' => 'Bitiş tarihi başlangıç tarihinden önce olamaz.',
            'cycle.required' => 'Dönem seçimi zorunludur.',
            'cycle.in' => 'Geçersiz dönem.',
            'payment_type.required' => 'Ödeme türü seçimi zorunludur.',
            'payment_type.in' => 'Geçersiz ödeme türü.',
            'cost_price.numeric' => 'Maliyet fiyatı sayısal olmalıdır.',
            'cost_price.min' => 'Maliyet fiyatı 0\'dan küçük olamaz.',
            'cost_price.max' => 'Maliyet fiyatı çok yüksek.',
            'sell_price.numeric' => 'Satış fiyatı sayısal olmalıdır.',
            'sell_price.min' => 'Satış fiyatı 0\'dan küçük olamaz.',
            'sell_price.max' => 'Satış fiyatı çok yüksek.',
            'notes.max' => 'Notlar çok uzun.',
            'domain_name.regex' => 'Geçersiz domain adı formatı.',
            'server_name.regex' => 'Geçersiz sunucu adı formatı.',
            'ip_address.regex' => 'Geçersiz IP adresi formatı.',
            'cpanel_username.regex' => 'cPanel kullanıcı adı sadece harf, rakam, tire ve alt çizgi içerebilir.',
        ]);

        // Conditional validation for domain fields
        if ($request->service_type === 'domain') {
            $request->validate([
                'domain_name' => ['required', 'string', 'max:255'],
            ], [
                'domain_name.required' => 'Domain adı zorunludur.',
            ]);
        }

        // Conditional validation for hosting fields
        if ($request->service_type === 'hosting') {
            $request->validate([
                'plan_name' => ['required', 'string', 'max:255'],
                'server_name' => ['required', 'string', 'max:255'],
            ], [
                'plan_name.required' => 'Hosting paket adı zorunludur.',
                'server_name.required' => 'Sunucu adı zorunludur.',
            ]);
        }



        // SQLite NOT NULL alanlarına null göndermemek için varsayılanları uygula
        $validated['cost_price'] = $validated['cost_price'] ?? 0;
        $validated['sell_price'] = $validated['sell_price'] ?? 0;

        // Eski kolonları da doldur (geriye uyumluluk için)
        $validated['price'] = $validated['sell_price'] ?? 0;
        $validated['payment_cycle'] = $validated['cycle'] ?? 'monthly';
        $validated['name'] = $validated['service_type'] ?? 'Hizmet';
        $validated['description'] = $validated['notes'] ?? null;

        try {
            $service = Service::create($validated);
            
            // Hizmet kodunu otomatik oluştur
            $service->update(['service_code' => Service::generateUniqueServiceCode()]);
            
            // Hizmet tanımlayıcısını otomatik oluştur
            $identifier = $this->generateServiceIdentifier($service);
            if ($identifier) {
                $service->update(['service_identifier' => $identifier]);
            }
        } catch (\Exception $e) {
            \Log::error('Service creation failed:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withErrors(['error' => 'Hizmet oluşturulamadı: ' . $e->getMessage()])->withInput();
        }

        if ($service->service_type === 'domain' && $request->filled('domain_name')) {
            Domain::create([
                'service_id' => $service->id,
                'domain_name' => $request->string('domain_name'),
                'registrar_ref' => $request->string('registrar_ref'),
                'auth_code' => $request->string('auth_code'),
            ]);
        }

        if ($service->service_type === 'hosting' && ($request->filled('plan_name') || $request->filled('server_name'))) {
            Hosting::create([
                'service_id' => $service->id,
                'plan_name' => $request->string('plan_name'),
                'server_name' => $request->string('server_name'),
                'ip_address' => $request->string('ip_address'),
                'cpanel_username' => $request->string('cpanel_username'),
                'panel_ref' => $request->string('panel_ref'),
            ]);
        }

        // Otomatik fatura oluştur (eğer seçenek işaretliyse ve satış fiyatı varsa)
        $autoCreateInvoice = $request->has('auto_create_invoice') && $request->auto_create_invoice;
        $sendInvoiceEmail = $request->has('send_invoice_email') && $request->send_invoice_email;
        
        if ($autoCreateInvoice && $service->sell_price > 0) {
            $invoice = $this->createInvoiceFromService($service, $sendInvoiceEmail);
            
            if ($invoice && $sendInvoiceEmail) {
                $statusMessage = 'Hizmet oluşturuldu, fatura oluşturuldu ve müşteriye e-posta gönderildi';
            } elseif ($invoice) {
                $statusMessage = 'Hizmet oluşturuldu ve fatura oluşturuldu';
            } else {
                $statusMessage = 'Hizmet oluşturuldu (fatura oluşturulamadı)';
            }
        } else {
            $statusMessage = 'Hizmet oluşturuldu';
        }

        return redirect()->route('services.show', $service)->with('status', $statusMessage);
    }

    /**
     * Hizmet türüne göre benzersiz tanımlayıcı oluştur
     */
    private function generateServiceIdentifier(Service $service): ?string
    {
        switch ($service->service_type) {
            case 'domain':
                $domain = $service->domain;
                if ($domain && $domain->domain_name) {
                    return $domain->domain_name;
                }
                break;

            case 'hosting':
                $hosting = $service->hosting;
                if ($hosting) {
                    $parts = [];
                    if ($hosting->plan_name) $parts[] = $hosting->plan_name;
                    if ($hosting->server_name) $parts[] = $hosting->server_name;
                    
                    if (!empty($parts)) {
                        return implode(' - ', $parts);
                    }
                }
                break;

            case 'ssl':
                return 'SSL Sertifikası';
                
            case 'email':
                return 'E-posta Paketi';
                
            default:
                return ucfirst($service->service_type);
        }

        return null;
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        // Gerekli ilişkileri yükle ve cache'le
        $service->load([
            'customer:id,name,surname,email',
            'provider:id,name',
            'domain:id,service_id,domain_name,registrar_ref,auth_code',
            'hosting:id,service_id,plan_name,server_name,ip_address,cpanel_username,panel_ref',
            'sslCertificate:id,service_id,certificate_type,issuer,expiry_date,common_name',
            'emailService:id,service_id,email_provider,email_plan,mailbox_count,storage_limit'
        ]);
        
        // Site ayarlarını cache'den al
        $serviceTypes = cache()->remember('service_types', 3600, function () {
            $setting = \App\Models\Setting::where('key', 'service_types')->first();
            if ($setting) {
                return json_decode($setting->value, true);
            }
            return [
                ['id' => 'domain', 'name' => 'Domain', 'icon' => '🌐', 'color' => '#3B82F6'],
                ['id' => 'hosting', 'name' => 'Hosting', 'icon' => '🖥️', 'color' => '#10B981'],
                ['id' => 'ssl', 'name' => 'SSL', 'icon' => '🔒', 'color' => '#8B5CF6'],
                ['id' => 'email', 'name' => 'E-mail', 'icon' => '📧', 'color' => '#06B6D4'],
                ['id' => 'other', 'name' => 'Diğer', 'icon' => '📦', 'color' => '#6B7280'],
            ];
        });
        
        // Kalan günleri hesapla
        if ($service->end_date) {
            $days = (int)now()->diffInDays($service->end_date, false);
            $service->days_remaining = abs((int)$days);
        }
        
        return view('services.show', compact('service', 'serviceTypes'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        $service->load(['domain', 'hosting', 'sslCertificate', 'emailService']);
        $customers = Customer::select(['id', 'name', 'surname', 'email', 'customer_type'])
            ->orderBy('name')
            ->get();
        $providers = Provider::orderBy('name')->get();
        return view('services.edit', compact('service', 'customers', 'providers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        // Dynamically build allowed service types from settings
        $serviceTypesJson = Setting::get('service_types');
        $serviceTypesArr = $serviceTypesJson ? json_decode($serviceTypesJson, true) : [];
        if (!is_array($serviceTypesArr)) { $serviceTypesArr = []; }
        $allowedServiceTypeIds = array_values(array_filter(array_map(function ($item) {
            if (is_array($item)) {
                if (isset($item['id'])) { return $item['id']; }
                if (isset($item['value'])) { return $item['value']; }
            }
            return is_string($item) ? $item : null;
        }, $serviceTypesArr)));
        if (empty($allowedServiceTypeIds)) {
            $allowedServiceTypeIds = ['domain','hosting','ssl','email','other'];
        }
        $serviceTypeRule = 'in:' . implode(',', $allowedServiceTypeIds);

        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'provider_id' => ['nullable', 'exists:providers,id'],
            'service_type' => ['required', $serviceTypeRule],
            'status' => ['required', 'in:active,suspended,cancelled,expired'],
            'start_date' => ['nullable', 'date', 'before_or_equal:today'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'cycle' => ['required', 'in:monthly,quarterly,semiannually,yearly,biennially,triennially'],
            'payment_type' => ['required', 'in:upfront,installment'],
            'cost_price' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'sell_price' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'notes' => ['nullable', 'string', 'max:1000'],

            // domain validation
            'domain_name' => [
                'nullable', 
                'string', 
                'max:255',
                'regex:/^[a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?(\.[a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?)*$/'
            ],
            'registrar_ref' => ['nullable', 'string', 'max:255'],
            'auth_code' => ['nullable', 'string', 'max:255'],

            // hosting validation
            'plan_name' => ['nullable', 'string', 'max:255'],
            'server_name' => [
                'nullable', 
                'string', 
                'max:255',
                'regex:/^[a-zA-Z0-9]([a-zA-Z0-9\-\.]{0,61}[a-zA-Z0-9])?$/'
            ],
            'ip_address' => [
                'nullable', 
                'string', 
                'max:45',
                'regex:/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/'
            ],
            'cpanel_username' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z0-9_-]+$/'],
            'panel_ref' => ['nullable', 'string', 'max:255'],
        ], [
            'customer_id.required' => 'Müşteri seçimi zorunludur.',
            'customer_id.exists' => 'Seçilen müşteri bulunamadı.',
            'service_type.required' => 'Hizmet türü seçimi zorunludur.',
            'service_type.in' => 'Geçersiz hizmet türü.',
            'status.required' => 'Durum seçimi zorunludur.',
            'status.in' => 'Geçersiz durum.',
            'start_date.date' => 'Geçersiz başlangıç tarihi.',
            'start_date.before_or_equal' => 'Başlangıç tarihi bugünden sonra olamaz.',
            'end_date.date' => 'Geçersiz bitiş tarihi.',
            'end_date.after_or_equal' => 'Bitiş tarihi başlangıç tarihinden önce olamaz.',
            'cycle.required' => 'Dönem seçimi zorunludur.',
            'cycle.in' => 'Geçersiz dönem.',
            'payment_type.required' => 'Ödeme türü seçimi zorunludur.',
            'payment_type.in' => 'Geçersiz ödeme türü.',
            'cost_price.numeric' => 'Maliyet fiyatı sayısal olmalıdır.',
            'cost_price.min' => 'Maliyet fiyatı 0\'dan küçük olamaz.',
            'cost_price.max' => 'Maliyet fiyatı çok yüksek.',
            'sell_price.numeric' => 'Satış fiyatı sayısal olmalıdır.',
            'sell_price.min' => 'Satış fiyatı 0\'dan küçük olamaz.',
            'sell_price.max' => 'Satış fiyatı çok yüksek.',
            'notes.max' => 'Notlar çok uzun.',
            'domain_name.regex' => 'Geçersiz domain adı formatı.',
            'server_name.regex' => 'Geçersiz sunucu adı formatı.',
            'ip_address.regex' => 'Geçersiz IP adresi formatı.',
            'cpanel_username.regex' => 'cPanel kullanıcı adı sadece harf, rakam, tire ve alt çizgi içerebilir.',
        ]);

        // Conditional validation for domain fields
        if ($request->service_type === 'domain') {
            $request->validate([
                'domain_name' => ['required', 'string', 'max:255'],
            ], [
                'domain_name.required' => 'Domain adı zorunludur.',
            ]);
        }

        // Conditional validation for hosting fields
        if ($request->service_type === 'hosting') {
            $request->validate([
                'plan_name' => ['required', 'string', 'max:255'],
                'server_name' => ['required', 'string', 'max:255'],
            ], [
                'plan_name.required' => 'Hosting paket adı zorunludur.',
                'server_name.required' => 'Sunucu adı zorunludur.',
            ]);
        }

        $validated['cost_price'] = $validated['cost_price'] ?? 0;
        $validated['sell_price'] = $validated['sell_price'] ?? 0;

        // Eski kolonları da güncelle (geriye uyumluluk için)
        $validated['price'] = $validated['sell_price'] ?? 0;
        $validated['payment_cycle'] = $validated['cycle'] ?? 'monthly';
        $validated['name'] = $validated['service_type'] ?? 'Hizmet';
        $validated['description'] = $validated['notes'] ?? null;

        $service->update($validated);
        
        // Hizmet tanımlayıcısını güncelle
        $identifier = $this->generateServiceIdentifier($service);
        if ($identifier) {
            $service->update(['service_identifier' => $identifier]);
        }

        if ($service->service_type === 'domain') {
            $service->hosting()?->delete();
            $data = [
                'domain_name' => $request->string('domain_name'),
                'registrar_ref' => $request->string('registrar_ref'),
                'auth_code' => $request->string('auth_code'),
            ];
            if ($service->domain) {
                $service->domain->update($data);
            } else {
                $service->domain()->create($data);
            }
        } elseif ($service->service_type === 'hosting') {
            $service->domain()?->delete();
            $data = [
                'plan_name' => $request->string('plan_name'),
                'server_name' => $request->string('server_name'),
                'ip_address' => $request->string('ip_address'),
                'cpanel_username' => $request->string('cpanel_username'),
                'panel_ref' => $request->string('panel_ref'),
            ];
            if ($service->hosting) {
                $service->hosting->update($data);
            } else {
                $service->hosting()->create($data);
            }
        }

        return redirect()->route('services.show', $service)->with('status', 'Hizmet güncellendi');
    }

    /**
     * Generate invoice number with concurrency protection
     */
    private function generateInvoiceNumber()
    {
        $prefix = Setting::get('invoice_prefix', 'INV');
        $startNumber = (int) Setting::get('invoice_start_number', 1);
        
        // Database transaction ile yarış koşullarını önle
        return \DB::transaction(function () use ($prefix, $startNumber) {
            // En son geçerli fatura numarasını bul (NULL olmayan)
            $lastInvoice = \App\Models\Invoice::whereNotNull('invoice_number')
                ->orderBy('id', 'desc')
                ->first();
            
            if ($lastInvoice && $lastInvoice->invoice_number) {
                // Mevcut numaradan sonraki numarayı al
                // Format: INV-000001-2025 -> 000001 kısmını al
                if (preg_match('/^' . preg_quote($prefix, '/') . '-(\d+)-(\d{4})$/', $lastInvoice->invoice_number, $matches)) {
                    $lastNumber = (int) $matches[1];
                    $nextNumber = $lastNumber + 1;
                } else {
                    // Eğer format uygun değilse başlangıç numarasını kullan
                    $nextNumber = $startNumber;
                }
            } else {
                // Hiç fatura yoksa veya tüm faturalar NULL ise başlangıç numarasını kullan
                $nextNumber = $startNumber;
            }
            
            // Format: INV-000001-2025
            $year = date('Y');
            $formattedNumber = $prefix . '-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT) . '-' . $year;
            
            // Bu numaranın benzersiz olduğunu kontrol et
            while (\App\Models\Invoice::where('invoice_number', $formattedNumber)->exists()) {
                $nextNumber++;
                $formattedNumber = $prefix . '-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT) . '-' . $year;
            }
            
            return $formattedNumber;
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $service->domain()?->delete();
        $service->hosting()?->delete();
        $service->delete();
        return redirect()->route('services.index')->with('status', 'Hizmet silindi');
    }

    /**
     * Hizmetten otomatik fatura oluştur
     */
    private function createInvoiceFromService(Service $service, bool $sendEmail = true)
    {
        try {
            // Ayarlardan finansal bilgileri al
            $currency = Setting::get('currency', 'TRY');
            $taxRate = (float) Setting::get('tax_rate', '18');

            // Fatura oluştur
            $invoice = \App\Models\Invoice::create([
                'customer_id' => $service->customer_id,
                'issue_date' => now(),
                'due_date' => now()->addDays(30),
                'status' => $sendEmail ? 'sent' : 'draft', // E-posta gönderiliyorsa 'sent', değilse 'draft'
                'currency' => $currency,
                'invoice_number' => $this->generateInvoiceNumber(),
            ]);

            // Fatura item'ı ekle
            $invoice->items()->create([
                'service_id' => $service->id,
                'description' => ucfirst($service->service_type) . ' Hizmeti - ' . ($service->domain?->domain_name ?? $service->hosting?->plan_name ?? 'Hizmet'),
                'qty' => 1,
                'unit_price' => $service->sell_price,
                'tax_rate' => $taxRate,
            ]);

            // Toplamları hesapla
            $invoice->calculateTotalsFromItems();
            $invoice->save();

            // Ledger entry oluştur
            \App\Models\LedgerEntry::create([
                'customer_id' => $service->customer_id,
                'related_type' => \App\Models\Invoice::class,
                'related_id' => $invoice->id,
                'entry_date' => $invoice->issue_date,
                'debit' => $invoice->total,
                'credit' => 0,
                'type' => 'debit',
                'amount' => $invoice->total,
                'balance' => $invoice->total,
                'balance_after' => (function() use ($invoice) {
                    $bal = (float) \DB::table('ledger_entries')
                        ->where('customer_id', $invoice->customer_id)
                        ->selectRaw('COALESCE(SUM(debit),0) - COALESCE(SUM(credit),0) as b')
                        ->value('b');
                    return $bal + (float)$invoice->total;
                })(),
                'notes' => 'Hizmet faturası oluşturuldu',
            ]);

            // Fatura e-postasını müşteriye gönder (eğer istenirse)
            if ($sendEmail) {
                $this->sendInvoiceEmailToCustomer($invoice);
            }

            return $invoice;
        } catch (\Exception $e) {
            \Log::error('Otomatik fatura oluşturma hatası:', [
                'service_id' => $service->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Fatura e-postasını müşteriye gönder
     */
    private function sendInvoiceEmailToCustomer(\App\Models\Invoice $invoice)
    {
        try {
            $invoice->load('customer');
            
            if (!$invoice->customer || !$invoice->customer->email) {
                \Log::warning('Fatura e-postası gönderilemedi: Müşteri e-posta adresi bulunamadı', [
                    'invoice_id' => $invoice->id,
                    'customer_id' => $invoice->customer_id
                ]);
                return false;
            }

            // Şirket bilgilerini al
            $companyInfo = $this->getCompanyInfo();

            // Fatura e-postasını gönder
            \Illuminate\Support\Facades\Mail::to($invoice->customer->email)
                ->queue(new \App\Mail\InvoiceMail($invoice, $companyInfo));

            \Log::info('Hizmet faturası e-postası gönderildi', [
                'invoice_id' => $invoice->id,
                'customer_email' => $invoice->customer->email
            ]);

            return true;
        } catch (\Exception $e) {
            \Log::error('Fatura e-postası gönderme hatası:', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Şirket bilgilerini al
     */
    private function getCompanyInfo(): array
    {
        return [
            'name' => \App\Models\Setting::get('company_name', 'Şirket Adı'),
            'address' => \App\Models\Setting::get('company_address', ''),
            'phone' => \App\Models\Setting::get('company_phone', ''),
            'email' => \App\Models\Setting::get('company_email', ''),
            'tax_number' => \App\Models\Setting::get('company_tax_number', ''),
            'logo' => \App\Models\Setting::get('company_logo', ''),
        ];
    }

    /**
     * Create invoice from service
     */
    public function createInvoice(Service $service)
    {
        // Ayarlardan finansal bilgileri al
        $currency = Setting::get('currency', 'TRY');
        $taxRate = (float) Setting::get('tax_rate', '18');

        // Fatura oluştur
        $invoice = \App\Models\Invoice::create([
            'customer_id' => $service->customer_id,
            'issue_date' => now(),
            'due_date' => now()->addDays(30),
            'status' => 'draft',
            'currency' => $currency,
            'invoice_number' => $this->generateInvoiceNumber(),
        ]);

        // Fatura item'ı ekle
        $invoice->items()->create([
            'service_id' => $service->id, // Hizmet ID'sini ekle
            'description' => ucfirst($service->service_type) . ' Hizmeti - ' . ($service->domain?->domain_name ?? $service->hosting?->plan_name ?? 'Hizmet'),
            'qty' => 1,
            'unit_price' => $service->sell_price,
            'tax_rate' => $taxRate,
        ]);

        // Toplamları hesapla
        $invoice->calculateTotalsFromItems();
        $invoice->save();

        return redirect()->route('invoices.edit', $invoice)->with('status', 'Hizmet için fatura oluşturuldu');
    }

    /**
     * Send service expiry reminder email
     */
    public function sendReminderEmail(Service $service)
    {
        try {
            if (!$service->customer || !$service->customer->email) {
                throw new \Exception('Müşteri e-posta adresi bulunamadı.');
            }

            // Hatırlatma e-postası gönder
            $reminderData = [
                'service' => $service,
                'customer' => $service->customer,
                'daysRemaining' => $service->days_remaining ?? 0,
                'expiryDate' => $service->end_date?->format('d.m.Y'),
            ];

            // E-posta gönderimi (şimdilik basit mesaj)
            \Illuminate\Support\Facades\Mail::to($service->customer->email)
                ->send(new \App\Mail\ServiceExpiryReminder($reminderData));

            return back()->with('status', 'Hatırlatma e-postası başarıyla gönderildi.');
        } catch (\Exception $e) {
            return back()->with('error', 'E-posta gönderilirken hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Add reminder for service
     */
    public function addReminder(Service $service, Request $request)
    {
        $validated = $request->validate([
            'reminder_date' => ['required', 'date', 'after:today'],
            'reminder_type' => ['required', 'in:email,sms,notification'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        // Hatırlatma oluştur
        \App\Models\Reminder::create([
            'remindable_type' => Service::class,
            'remindable_id' => $service->id,
            'reminder_type' => $validated['reminder_type'],
            'sent_at' => null,
            'channel' => $validated['reminder_type'],
        ]);

        return back()->with('status', 'Hatırlatma başarıyla eklendi.');
    }
}
