<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class LedgerEntry extends Model
{
    protected $fillable = [
        'customer_id',
        'related_type',
        'related_id',
        'entry_date',
        'debit',
        'credit',
        'balance_after',
        'notes',
        'type',
        'amount',
        'balance',
        'description',
        'reference_id',
        'reference_type',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'debit' => 'decimal:2',
        'credit' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'balance' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function related(): MorphTo
    {
        return $this->morphTo();
    }
}
