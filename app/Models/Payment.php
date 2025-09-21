<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'invoice_id',
        'amount',
        'payment_method',
        'payment_date',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'paid_at' => 'datetime',
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
     * Ödeme yöntemi Türkçe karşılığı
     */
    public function methodLabel(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match($this->payment_method) {
                    'cash' => 'Nakit',
                    'bank' => 'Banka',
                    'card' => 'Kart',
                    'transfer' => 'Havale',
                    'other' => 'Diğer',
                    default => ucfirst($this->payment_method)
                };
            }
        );
    }

    /**
     * Ödeme yöntemi ikonu
     */
    public function methodIcon(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match($this->payment_method) {
                    'cash' => '💰',
                    'bank' => '🏦',
                    'card' => '💳',
                    'transfer' => '📤',
                    'other' => '📋',
                    default => '💵'
                };
            }
        );
    }

    /**
     * Scope: Belirli müşteriye ait ödemeler
     */
    public function scopeForCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    /**
     * Scope: Belirli faturaya ait ödemeler
     */
    public function scopeForInvoice($query, $invoiceId)
    {
        return $query->where('invoice_id', $invoiceId);
    }

    /**
     * Scope: Belirli tarih aralığındaki ödemeler
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('paid_at', [$startDate, $endDate]);
    }
}
