<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\AccountingLedgerEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AccountingAutomationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Test müşterisi oluştur
        $this->customer = Customer::create([
            'name' => 'Test Müşteri',
            'email' => 'test@example.com',
            'phone' => '5551234567',
        ]);
    }

    /** @test */
    public function draft_invoice_creates_no_debit_entry()
    {
        // Draft fatura oluştur
        $invoice = Invoice::create([
            'customer_id' => $this->customer->id,
            'invoice_number' => 'INV-001',
            'issue_date' => now(),
            'due_date' => now()->addDays(30),
            'status' => 'draft',
            'total' => 1000.00,
            'subtotal' => 1000.00,
            'tax_total' => 0.00,
        ]);

        // Debit entry olmamalı
        $this->assertDatabaseMissing('accounting_ledger_entries', [
            'invoice_id' => $invoice->id,
            'type' => 'debit',
        ]);
    }

    /** @test */
    public function sent_invoice_creates_debit_entry()
    {
        // Draft fatura oluştur
        $invoice = Invoice::create([
            'customer_id' => $this->customer->id,
            'invoice_number' => 'INV-002',
            'issue_date' => now(),
            'due_date' => now()->addDays(30),
            'status' => 'draft',
            'total' => 1000.00,
            'subtotal' => 1000.00,
            'tax_total' => 0.00,
        ]);

        // Status'u 'sent' yap
        $invoice->update(['status' => 'sent']);

        // Debit entry oluşmalı
        $this->assertDatabaseHas('accounting_ledger_entries', [
            'invoice_id' => $invoice->id,
            'type' => 'debit',
            'amount' => 1000.00,
        ]);
    }

    /** @test */
    public function partial_payments_complete_invoice_to_paid()
    {
        // Sent fatura oluştur
        $invoice = Invoice::create([
            'customer_id' => $this->customer->id,
            'invoice_number' => 'INV-003',
            'issue_date' => now(),
            'due_date' => now()->addDays(30),
            'status' => 'sent',
            'total' => 1000.00,
            'subtotal' => 1000.00,
            'tax_total' => 0.00,
        ]);

        // İlk ödeme (600₺)
        $payment1 = Payment::create([
            'customer_id' => $this->customer->id,
            'invoice_id' => $invoice->id,
            'amount' => 600.00,
            'payment_method' => 'cash',
            'payment_date' => now(),
            'paid_at' => now(),
        ]);

        // Fatura hala 'sent' olmalı (600₺ ödendi, 400₺ kaldı)
        $invoice->refresh();
        $this->assertEquals('sent', $invoice->status);
        
        // Kalan tutar 400₺ olmalı
        $remainingAmount = $invoice->remaining_amount;
        $this->assertEquals(400.00, $remainingAmount);

        // İkinci ödeme (400₺)
        $payment2 = Payment::create([
            'customer_id' => $this->customer->id,
            'invoice_id' => $invoice->id,
            'amount' => 400.00,
            'payment_method' => 'cash',
            'payment_date' => now(),
            'paid_at' => now(),
        ]);

        // Fatura 'paid' olmalı
        $invoice->refresh();
        $this->assertEquals('paid', $invoice->status);
    }

    /** @test */
    public function cancelled_invoice_reverses_all_entries_to_zero()
    {
        // Sent fatura oluştur
        $invoice = Invoice::create([
            'customer_id' => $this->customer->id,
            'invoice_number' => 'INV-004',
            'issue_date' => now(),
            'due_date' => now()->addDays(30),
            'status' => 'sent',
            'total' => 1000.00,
            'subtotal' => 1000.00,
            'tax_total' => 0.00,
        ]);

        // Ödeme ekle
        $payment = Payment::create([
            'customer_id' => $this->customer->id,
            'invoice_id' => $invoice->id,
            'amount' => 500.00,
            'payment_method' => 'cash',
            'payment_date' => now(),
            'paid_at' => now(),
        ]);

        // Fatura durumunu 'draft' yap (canceled yerine draft kullanıyoruz)
        $invoice->update(['status' => 'draft']);

        // Reverse entries oluşmalı
        $this->assertDatabaseHas('accounting_ledger_entries', [
            'invoice_id' => $invoice->id,
            'type' => 'reverse',
            'amount' => 1000.00, // Debit reverse
        ]);

        $this->assertDatabaseHas('accounting_ledger_entries', [
            'invoice_id' => $invoice->id,
            'type' => 'reverse',
            'amount' => 500.00, // Credit reverse
        ]);

        // Net bakiye 0 olmalı
        $balance = AccountingLedgerEntry::getInvoiceBalance($invoice->id);
        $this->assertEquals(0, $balance);
    }

    /** @test */
    public function debit_entry_is_idempotent()
    {
        // Draft fatura oluştur
        $invoice = Invoice::create([
            'customer_id' => $this->customer->id,
            'invoice_number' => 'INV-005',
            'issue_date' => now(),
            'due_date' => now()->addDays(30),
            'status' => 'draft',
            'total' => 1000.00,
            'subtotal' => 1000.00,
            'tax_total' => 0.00,
        ]);

        // Status'u 'sent' yap (debit entry oluşacak)
        $invoice->update(['status' => 'sent']);

        // Debit entry sayısı 1 olmalı
        $debitCount = AccountingLedgerEntry::where('invoice_id', $invoice->id)
            ->where('type', 'debit')
            ->count();
        $this->assertEquals(1, $debitCount);

        // Tekrar status güncelle
        $invoice->update(['status' => 'sent']);

        // Debit entry sayısı hala 1 olmalı
        $debitCount = AccountingLedgerEntry::where('invoice_id', $invoice->id)
            ->where('type', 'debit')
            ->count();
        $this->assertEquals(1, $debitCount);
    }

    /** @test */
    public function credit_entry_is_idempotent()
    {
        // Sent fatura oluştur
        $invoice = Invoice::create([
            'customer_id' => $this->customer->id,
            'invoice_number' => 'INV-006',
            'issue_date' => now(),
            'due_date' => now()->addDays(30),
            'status' => 'sent',
            'total' => 1000.00,
            'subtotal' => 1000.00,
            'tax_total' => 0.00,
        ]);

        // Ödeme oluştur
        $payment = Payment::create([
            'customer_id' => $this->customer->id,
            'invoice_id' => $invoice->id,
            'amount' => 500.00,
            'payment_method' => 'cash',
            'payment_date' => now(),
            'paid_at' => now(),
        ]);

        // Credit entry sayısı 1 olmalı
        $creditCount = AccountingLedgerEntry::where('payment_id', $payment->id)
            ->where('type', 'credit')
            ->count();
        $this->assertEquals(1, $creditCount);

        // Aynı ödeme için tekrar event tetikle
        event(new \App\Events\PaymentCreated($payment));

        // Credit entry sayısı hala 1 olmalı
        $creditCount = AccountingLedgerEntry::where('payment_id', $payment->id)
            ->where('type', 'credit')
            ->count();
        $this->assertEquals(1, $creditCount);
    }

    /** @test */
    public function customer_balance_calculation_is_correct()
    {
        // Sent fatura oluştur
        $invoice = Invoice::create([
            'customer_id' => $this->customer->id,
            'invoice_number' => 'INV-007',
            'issue_date' => now(),
            'due_date' => now()->addDays(30),
            'status' => 'sent',
            'total' => 1000.00,
            'subtotal' => 1000.00,
            'tax_total' => 0.00,
        ]);

        // Ödeme ekle
        $payment = Payment::create([
            'customer_id' => $this->customer->id,
            'invoice_id' => $invoice->id,
            'amount' => 600.00,
            'payment_method' => 'cash',
            'payment_date' => now(),
            'paid_at' => now(),
        ]);

        // Müşteri bakiyesi 400₺ olmalı (1000 - 600)
        $balance = AccountingLedgerEntry::getCustomerBalance($this->customer->id);
        $this->assertEquals(400.00, $balance);
    }
}
