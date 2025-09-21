<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountingLedgerEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'invoice_id',
        'payment_id',
        'type',
        'amount',
        'date',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Müşteri ilişkisi
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Fatura ilişkisi
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Ödeme ilişkisi
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Scope: Belirli müşteriye ait hareketler
     */
    public function scopeForCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    /**
     * Scope: Belirli faturaya ait hareketler
     */
    public function scopeForInvoice($query, $invoiceId)
    {
        return $query->where('invoice_id', $invoiceId);
    }

    /**
     * Scope: Belirli ödemeye ait hareketler
     */
    public function scopeForPayment($query, $paymentId)
    {
        return $query->where('payment_id', $paymentId);
    }

    /**
     * Scope: Belirli tipteki hareketler
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Müşteri bakiyesini hesapla
     */
    public static function getCustomerBalance($customerId): float
    {
        $debits = self::forCustomer($customerId)->ofType('debit')->sum('amount');
        $credits = self::forCustomer($customerId)->ofType('credit')->sum('amount');
        $reverses = self::forCustomer($customerId)->ofType('reverse')->sum('amount');
        
        return (float) ($debits - $credits + $reverses);
    }

    /**
     * Fatura bakiyesini hesapla
     */
    public static function getInvoiceBalance($invoiceId): float
    {
        $debits = self::forInvoice($invoiceId)->ofType('debit')->sum('amount');
        $credits = self::forInvoice($invoiceId)->ofType('credit')->sum('amount');
        $reverses = self::forInvoice($invoiceId)->ofType('reverse')->sum('amount');
        
        return (float) ($debits - $credits + $reverses);
    }
}
