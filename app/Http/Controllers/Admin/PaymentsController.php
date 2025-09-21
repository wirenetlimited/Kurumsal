<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\Invoice;
// use App\Events\PaymentCreated;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class PaymentsController extends Controller
{
    /**
     * Get payment context for invoice
     */
    public function context(Invoice $invoice)
    {
        $invoice->load('customer');
        return response()->json([
            'invoice_id'       => $invoice->id,
            'invoice_number'   => $invoice->invoice_number,
            'customer_id'      => $invoice->customer_id,
            'customer_name'    => $invoice->customer->name,
            'remaining_amount' => (float) $invoice->remaining_amount,
            'currency'         => $invoice->currency ?? 'TRY',
            'methods'          => ['cash','bank','card','transfer','other'],
        ]);
    }

    /**
     * Get customer invoices for payment form
     */
    public function customerInvoices(Customer $customer)
    {
        $invoices = Invoice::where('customer_id', $customer->id)
            ->where('status', '!=', 'cancelled')
            ->orderBy('issue_date', 'desc')
            ->get(['id', 'invoice_number', 'total', 'status']);

        return response()->json(['invoices' => $invoices]);
    }

    /**
     * Display a listing of payments.
     */
    public function index(Request $request): View
    {
        $query = Payment::with(['customer', 'invoice'])
            ->orderBy('paid_at', 'desc');

        // Filtreleme
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('method')) {
            $query->where('method', $request->method);
        }

        if ($request->filled('date_from')) {
            $query->where('paid_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('paid_at', '<=', $request->date_to . ' 23:59:59');
        }

        $payments = $query->paginate(20);
        $customers = Customer::select(['id', 'name', 'surname', 'email', 'customer_type'])
            ->orderBy('name')
            ->get();

        return view('admin.payments.index', compact('payments', 'customers'));
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create(Request $request): View
    {
        $customers = Customer::select(['id', 'name', 'surname', 'email', 'customer_type'])
            ->orderBy('name')
            ->get();
        $invoices = collect();
        
        // Eğer müşteri seçilmişse, o müşterinin faturalarını getir
        if ($request->filled('customer_id')) {
            $invoices = Invoice::where('customer_id', $request->customer_id)
                ->where('status', '!=', 'cancelled')
                ->orderBy('issue_date', 'desc')
                ->get();
        }

        return view('admin.payments.create', compact('customers', 'invoices'));
    }

    /**
     * Store a newly created payment.
     */
    public function store(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_id' => 'nullable|exists:invoices,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,bank,card,transfer,other',
            'payment_date' => 'required|date',
            'paid_at' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Invoice verildiyse customer tutarlılığını kontrol et
        if ($validated['invoice_id']) {
            $invoice = Invoice::find($validated['invoice_id']);
            if ($invoice->customer_id != $validated['customer_id']) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'ok' => false,
                        'message' => 'Seçilen fatura bu müşteriye ait değil.'
                    ], 422);
                }
                return back()->withErrors(['invoice_id' => 'Seçilen fatura bu müşteriye ait değil.']);
            }
            
            // Güvenlik kontrolü: amount <= remaining_amount
            if ((float) $validated['amount'] > (float) $invoice->remaining_amount) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'ok' => false,
                        'message' => 'Ödeme tutarı kalan tutardan fazla olamaz'
                    ], 422);
                }
                return back()->withErrors(['amount' => 'Ödeme tutarı kalan tutardan fazla olamaz']);
            }
        }

        $payment = Payment::create($validated);

        // PaymentObserver otomatik olarak fatura durumunu güncelleyecek
        // Fatura cache'ini temizle
        if ($validated['invoice_id']) {
            cache()->forget('invoice_' . $validated['invoice_id']);
            cache()->forget('invoice_remaining_amount_' . $validated['invoice_id']);
            cache()->forget('invoice_paid_amount_' . $validated['invoice_id']);
        }

        // JSON response için
        if ($request->expectsJson()) {
            $invoice = Invoice::find($validated['invoice_id']);
            $remainingAmount = $invoice ? (float) $invoice->remaining_amount : 0;
            $invoiceStatus = $invoice ? $invoice->status : null;
            
            return response()->json([
                'ok' => true,
                'invoice_status' => $invoiceStatus,
                'paid_amount' => (float) $payment->amount,
                'remaining_amount' => $remainingAmount,
                'flash' => 'Tahsilat kaydedildi'
            ]);
        }

        return redirect()->route('admin.payments.index')
            ->with('status', 'Ödeme başarıyla oluşturuldu.');
    }

    /**
     * Display the specified payment.
     */
    public function show(Payment $payment): View
    {
        $payment->load(['customer', 'invoice']);
        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified payment.
     */
    public function edit(Payment $payment): View
    {
        $customers = Customer::orderBy('name')->get();
        $invoices = Invoice::where('customer_id', $payment->customer_id)
            ->where('status', '!=', 'cancelled')
            ->orderBy('issue_date', 'desc')
            ->get();

        return view('payments.edit', compact('payment', 'customers', 'invoices'));
    }

    /**
     * Update the specified payment.
     */
    public function update(Request $request, Payment $payment): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_id' => 'nullable|exists:invoices,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,bank,card,transfer,other',
            'payment_date' => 'required|date',
            'paid_at' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Invoice verildiyse customer tutarlılığını kontrol et
        if ($validated['invoice_id']) {
            $invoice = Invoice::find($validated['invoice_id']);
            if ($invoice->customer_id != $validated['customer_id']) {
                return back()->withErrors(['invoice_id' => 'Seçilen fatura bu müşteriye ait değil.']);
            }
        }

        $payment->update($validated);

        return redirect()->route('admin.payments.index')
            ->with('status', 'Ödeme başarıyla güncellendi.');
    }

    /**
     * Remove the specified payment.
     */
    public function destroy(Payment $payment): RedirectResponse
    {
        $payment->delete();

        return redirect()->route('admin.payments.index')
            ->with('status', 'Ödeme başarıyla silindi.');
    }

    /**
     * AJAX: Müşteri faturalarını getir
     */
    public function getCustomerInvoices(Request $request)
    {
        $customerId = $request->customer_id;
        
        $invoices = Invoice::where('customer_id', $customerId)
            ->where('status', '!=', 'cancelled')
            ->orderBy('issue_date', 'desc')
            ->get(['id', 'invoice_number', 'total', 'status', 'issue_date']);

        return response()->json($invoices);
    }
}
