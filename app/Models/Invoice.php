<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Invoice extends Model
{
    protected $fillable = [
        'customer_id',
        'invoice_number',
        'issue_date',
        'due_date',
        'status',
        'paid_at',
        'currency',
        'subtotal',
        'tax_amount',
        'total',
        'withholding',
        'paid_amount',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'status' => InvoiceStatus::class,
        'paid_at' => 'datetime',
        'paid_amount' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function ledgerEntries(): MorphMany
    {
        return $this->morphMany(\App\Models\LedgerEntry::class, 'related');
    }

    public function calculateTotalsFromItems(): void
    {
        // Önce line_total'ları hesapla (KDV dahil değil)
        foreach ($this->items as $item) {
            $lineSubtotal = (float) $item->qty * (float) $item->unit_price;
            $item->line_total = round($lineSubtotal, 2);
            $item->save();
        }

        // Ara toplam (KDV hariç)
        $subtotal = $this->items->sum('line_total');

        // KDV toplamı
        $taxTotal = $this->items->sum(function (InvoiceItem $item) {
            return round($item->line_total * (float) $item->tax_rate / 100, 2);
        });

        // Genel toplamlar
        $this->subtotal = round($subtotal, 2);
        $this->tax_amount = round($taxTotal, 2);
        $this->total = round($this->subtotal + $this->tax_amount, 2);
    }

    public function paidAmount(): float
    {
        // Cache'den ödenen tutarı al
        $cacheKey = 'invoice_paid_amount_' . $this->id;
        
        return cache()->remember($cacheKey, now()->addMinutes(5), function () {
            return (float) $this->payments()->sum('amount');
        });
    }

    public function isPaid(): bool
    {
        return $this->remaining_amount <= 0;
    }

    public function getRemainingAmountAttribute(): float
    {
        // Cache'den kalan tutarı al
        $cacheKey = 'invoice_remaining_amount_' . $this->id;
        
        return cache()->remember($cacheKey, now()->addMinutes(5), function () {
            // Muhasebe sisteminden kalan tutarı hesapla
            if (class_exists(\App\Models\AccountingLedgerEntry::class)) {
                $ledgerBalance = \App\Models\AccountingLedgerEntry::getInvoiceBalance($this->id);
                // Eğer ledger kaydı varsa onu kullan, yoksa basit hesaplama yap
                if ($ledgerBalance != 0 || \App\Models\AccountingLedgerEntry::where('invoice_id', $this->id)->exists()) {
                    return max(0, $ledgerBalance);
                }
            }
            
            // Fallback: basit hesaplama
            return max(0, (float) $this->total - $this->paidAmount());
        });
    }

    /**
     * Scope for draft invoices
     */
    public function scopeDraft($query)
    {
        return $query->where('status', InvoiceStatus::DRAFT);
    }

    /**
     * Scope for sent invoices
     */
    public function scopeSent($query)
    {
        return $query->where('status', InvoiceStatus::SENT);
    }

    /**
     * Scope for paid invoices
     */
    public function scopePaid($query)
    {
        return $query->where('status', InvoiceStatus::PAID);
    }

    /**
     * Scope for overdue invoices
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', InvoiceStatus::OVERDUE);
    }

    /**
     * Scope for cancelled invoices
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', InvoiceStatus::CANCELLED);
    }

    /**
     * Scope for unpaid invoices (sent or overdue)
     */
    public function scopeUnpaid($query)
    {
        return $query->whereIn('status', [InvoiceStatus::SENT, InvoiceStatus::OVERDUE]);
    }

    /**
     * Check if invoice is draft
     */
    public function isDraft(): bool
    {
        return $this->status === InvoiceStatus::DRAFT;
    }

    /**
     * Check if invoice is sent
     */
    public function isSent(): bool
    {
        return $this->status === InvoiceStatus::SENT;
    }

    /**
     * Check if invoice is paid
     */
    public function isPaidStatus(): bool
    {
        return $this->status === InvoiceStatus::PAID;
    }

    /**
     * Check if invoice is overdue
     */
    public function isOverdue(): bool
    {
        return $this->status === InvoiceStatus::OVERDUE;
    }

    /**
     * Check if invoice is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === InvoiceStatus::CANCELLED;
    }

    /**
     * Check if invoice is unpaid (sent or overdue)
     */
    public function isUnpaid(): bool
    {
        return $this->status->isUnpaid();
    }

    /**
     * Check if invoice can be paid
     */
    public function canBePaid(): bool
    {
        return $this->status->canBePaid();
    }

    /**
     * Get status label for display
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->status->label();
    }

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute(): string
    {
        return $this->status->color();
    }
}
