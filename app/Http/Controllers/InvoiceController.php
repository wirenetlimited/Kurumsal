<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\LedgerEntry;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\SettingsHelper;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    use SettingsHelper;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Sadece gerekli alanları seç ve customer ile eager loading yap
        $invoices = Invoice::select(['id', 'customer_id', 'invoice_number', 'issue_date', 'due_date', 'status', 'total'])
            ->with(['customer:id,name,surname,email'])
            ->latest('issue_date')
            ->paginate(15);
        
        // İstatistikleri tek sorguda al
        $statsResult = DB::select("
            SELECT 
                COUNT(*) as total_count,
                COALESCE(SUM(total), 0) as total_amount,
                COALESCE((
                    SELECT SUM(amount) FROM payments WHERE paid_at IS NOT NULL
                ), 0) as paid_amount
            FROM invoices
        ")[0];
        
        $count = (int) $statsResult->total_count;
        $sumTotal = (float) $statsResult->total_amount;
        $sumPaid = (float) $statsResult->paid_amount;
        
        return view('invoices.index', compact('invoices','count','sumTotal','sumPaid'));
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
        
        $services = Service::with('customer:id,name,surname,customer_type')
            ->select(['id', 'customer_id', 'service_type', 'name', 'price', 'service_code', 'service_identifier'])
            ->orderBy('id', 'desc')
            ->get();
            
        return view('invoices.create', compact('customers','services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => ['required','exists:customers,id'],
            'issue_date' => ['required','date'],
            'due_date' => ['nullable','date','after_or_equal:issue_date'],
            'currency' => ['required','string','size:3'],
            'items' => ['required','array','min:1'],
            'items.*.description' => ['required','string','max:255'],
            'items.*.service_id' => ['nullable','exists:services,id'],
            'items.*.qty' => ['required','numeric','min:0.01'],
            'items.*.unit_price' => ['required','numeric'],
            'items.*.tax_rate' => ['nullable','numeric','min:0','max:50'],
            'send_email' => ['nullable','boolean'],
        ]);

        // Ayarlardan varsayılan değerleri al
        $financialSettings = $this->getFinancialSettings();
        $defaultTaxRate = $financialSettings['tax_rate'];
        $defaultCurrency = $financialSettings['currency'];

        // Otomatik fatura numarası oluştur
        $invoiceNumber = $this->generateInvoiceNumber();
        
        $invoice = Invoice::create([
            'customer_id' => $validated['customer_id'],
            'issue_date' => $validated['issue_date'],
            'due_date' => $validated['due_date'] ?? null,
            'status' => \App\Enums\InvoiceStatus::SENT,
            'currency' => $validated['currency'],
            'invoice_number' => $invoiceNumber,
        ]);

        foreach ($validated['items'] as $it) {
            $item = new InvoiceItem([
                'service_id' => $it['service_id'] ?? null,
                'description' => $it['description'],
                'qty' => $it['qty'],
                'unit_price' => $it['unit_price'],
                'tax_rate' => $it['tax_rate'] ?? $defaultTaxRate,
            ]);
            $invoice->items()->save($item);
        }

        $invoice->load('items');
        $invoice->calculateTotalsFromItems();
        $invoice->save();

        LedgerEntry::create([
            'customer_id' => $invoice->customer_id,
            'related_type' => Invoice::class,
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
            'notes' => 'Fatura oluşturuldu',
        ]);

        // E-posta gönderme
        if ($request->has('send_email') && $request->send_email) {
            $this->sendInvoiceEmail($invoice);
            // E-posta gönderildiğinde fatura durumu zaten 'sent' olarak ayarlandı
        }

        return redirect()->route('invoices.show',$invoice)->with('status','Fatura oluşturuldu');
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        // Sadece gerekli ilişkileri yükle
        $invoice->load([
            'customer:id,name,surname,email,phone',
            'items:id,invoice_id,description,qty,unit_price,tax_rate,line_total'
        ]);
        
        // PDF görüntüleme isteği varsa
        if (request()->has('pdf')) {
            return view('invoices.pdf', compact('invoice'));
        }
        
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        // Sadece gerekli ilişkileri yükle
        $invoice->load(['items:id,invoice_id,service_id,description,qty,unit_price,tax_rate,line_total']);
        
        $customers = Customer::select(['id', 'name', 'surname', 'email', 'customer_type'])
            ->orderBy('name')
            ->get();
            
        $services = Service::with('customer:id,name,surname,customer_type')
            ->select(['id', 'customer_id', 'service_type', 'name', 'price', 'service_code', 'service_identifier'])
            ->orderBy('id', 'desc')
            ->get();
            
        return view('invoices.edit', compact('invoice','customers','services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $allowedStatuses = implode(',', \App\Enums\InvoiceStatus::values());
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'status' => ["required","in:$allowedStatuses"],
            'issue_date' => ['required', 'date', 'before_or_equal:today'],
            'due_date' => ['nullable', 'date', 'after_or_equal:issue_date'],
            'currency' => ['required', 'string', 'size:3', 'in:TRY,USD,EUR,GBP'],

            // Kalemler (opsiyonel; gönderildiyse doğrulanır)
            'items' => ['nullable', 'array'],
            'items.*.id' => ['nullable', 'integer', 'exists:invoice_items,id'],
            'items.*.service_id' => ['nullable', 'integer', 'exists:services,id'],
            'items.*.description' => ['required_with:items.*.qty,items.*.unit_price', 'string', 'max:255'],
            'items.*.qty' => ['nullable', 'numeric', 'min:0.01'],
            'items.*.unit_price' => ['nullable', 'numeric'],
            'items.*.tax_rate' => ['nullable', 'numeric', 'min:0', 'max:50'],
        ], [
            'customer_id.required' => 'Müşteri seçimi zorunludur.',
            'customer_id.exists' => 'Seçilen müşteri bulunamadı.',
            'issue_date.required' => 'Fatura tarihi zorunludur.',
            'issue_date.date' => 'Geçerli bir tarih giriniz.',
            'issue_date.before_or_equal' => 'Fatura tarihi bugünden sonra olamaz.',
            'due_date.date' => 'Geçerli bir vade tarihi giriniz.',
            'due_date.after_or_equal' => 'Vade tarihi fatura tarihinden önce olamaz.',
            'currency.required' => 'Para birimi zorunludur.',
            'currency.size' => 'Para birimi 3 karakter olmalıdır.',
            'currency.in' => 'Geçersiz para birimi.',
        ]);

        // Fatura üst bilgilerini güncelle
        $invoice->update([
            'customer_id' => $validated['customer_id'],
            'status' => $validated['status'],
            'issue_date' => $validated['issue_date'],
            'due_date' => $validated['due_date'] ?? null,
            'currency' => $validated['currency'],
        ]);

        // Kalemler gönderildiyse güncelle/ekle/sil
        if (isset($validated['items']) && is_array($validated['items'])) {
            $keepItemIds = [];

            foreach ($validated['items'] as $it) {
                $data = [
                    'service_id' => $it['service_id'] ?? null,
                    'description' => $it['description'] ?? null,
                    'qty' => $it['qty'] ?? null,
                    'unit_price' => $it['unit_price'] ?? null,
                    'tax_rate' => $it['tax_rate'] ?? null,
                ];

                if (!empty($it['id'])) {
                    // Sadece bu faturaya ait olan kalem güncellensin
                    $itemModel = $invoice->items()->whereKey($it['id'])->first();
                    if ($itemModel) {
                        $itemModel->update($data);
                        $keepItemIds[] = (int) $itemModel->id;
                    }
                } else {
                    $newItem = new \App\Models\InvoiceItem($data);
                    $invoice->items()->save($newItem);
                    $keepItemIds[] = (int) $newItem->id;
                }
            }

            // Formdan gelmeyen eski kalemleri sil
            if (count($keepItemIds) > 0) {
                $invoice->items()->whereNotIn('id', $keepItemIds)->delete();
            } else {
                // Hiç kalem yoksa tümünü sil
                $invoice->items()->delete();
            }
        }

        // Toplamları yeniden hesapla
        $invoice->load('items');
        $oldTotal = (float) $invoice->getOriginal('total');
        $invoice->calculateTotalsFromItems();
        $invoice->save();

        // Ledger düzeltmesi: toplam değiştiyse farkı işle
        $newTotal = (float) $invoice->total;
        $delta = $newTotal - $oldTotal;
        if (abs($delta) > 0.0001) {
            LedgerEntry::create([
                'customer_id' => $invoice->customer_id,
                'related_type' => Invoice::class,
                'related_id' => $invoice->id,
                'entry_date' => now()->toDateString(),
                'debit' => $delta > 0 ? $delta : 0,
                'credit' => $delta < 0 ? abs($delta) : 0,
                'balance_after' => (function() use ($invoice, $delta) {
                    $bal = (float) \DB::table('ledger_entries')
                        ->where('customer_id', $invoice->customer_id)
                        ->selectRaw('COALESCE(SUM(debit),0) - COALESCE(SUM(credit),0) as b')
                        ->value('b');
                    return $bal + (float)$delta;
                })(),
                'notes' => 'Fatura güncelleme farkı',
            ]);
        }

        return redirect()->route('invoices.show',$invoice)->with('status','Fatura güncellendi');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->items()->delete();
        $invoice->delete();
        return redirect()->route('invoices.index')->with('status','Fatura silindi');
    }

    /**
     * Send invoice email to customer
     */
    public function sendEmail(Invoice $invoice)
    {
        try {
            $this->sendInvoiceEmail($invoice);
            return back()->with('status', 'Fatura e-postası başarıyla gönderildi.');
        } catch (\Exception $e) {
            return back()->with('error', 'E-posta gönderilirken hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Send invoice email
     */
    private function sendInvoiceEmail(Invoice $invoice)
    {
        $invoice->load('customer');
        
        if (!$invoice->customer || !$invoice->customer->email) {
            throw new \Exception('Müşteri e-posta adresi bulunamadı.');
        }

        // E-posta gönderildiğinde fatura durumunu 'sent' yap
        if ($invoice->status === 'draft') {
            $invoice->update(['status' => 'sent']);
        }

        $companyInfo = $this->getCompanyInfo();
        Mail::to($invoice->customer->email)
            ->queue(new InvoiceMail($invoice, $companyInfo));
    }
}
