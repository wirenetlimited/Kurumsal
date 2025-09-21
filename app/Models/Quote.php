<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quote extends Model
{
    protected $fillable = [
        'number','customer_id','customer_name','customer_email','customer_phone','title','status',
        'quote_date','valid_until','discount_amount','tax_rate','subtotal','tax_amount','total','notes','terms',
    ];

    protected $casts = [
        'quote_date' => 'date',
        'valid_until' => 'date',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuoteItem::class);
    }

    public function recalcTotals(): void
    {
        // Ara toplam (KDV hariç) - line_total kullan
        $subtotal = $this->items->sum('line_total');
        
        // İndirim tutarı
        $discountAmount = (float) ($this->discount_amount ?? 0);
        
        // İndirim sonrası tutar
        $afterDiscount = $subtotal - $discountAmount;
        
        // KDV tutarı (indirim sonrası üzerinden)
        $taxTotal = $afterDiscount * ((float) ($this->tax_rate ?? 0) / 100);
        
        // Genel toplam
        $this->subtotal = round($subtotal, 2);
        $this->tax_amount = round($taxTotal, 2);
        $this->total = round($afterDiscount + $taxTotal, 2);
    }
}
