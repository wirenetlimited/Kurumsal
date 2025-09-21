<?php

namespace App\Models;

use App\Enums\ServiceStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Service extends Model
{
    protected $fillable = [
        'customer_id',
        'provider_id',
        'service_type',
        'service_identifier',
        'service_code',
        'status',
        'start_date',
        'end_date',
        'cycle',
        'payment_type',
        'cost_price',
        'sell_price',
        'notes',
        // Eski kolonları da ekle (geriye uyumluluk için)
        'name',
        'description',
        'price',
        'payment_cycle',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'payment_type' => 'string',
        'status' => ServiceStatus::class,
        'cost_price' => 'decimal:2',
        'sell_price' => 'decimal:2',
        'price' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    public function domain(): HasOne
    {
        return $this->hasOne(Domain::class);
    }

    public function hosting(): HasOne
    {
        return $this->hasOne(Hosting::class);
    }

    public function sslCertificate(): HasOne
    {
        return $this->hasOne(SslCertificate::class);
    }

    public function emailService(): HasOne
    {
        return $this->hasOne(EmailService::class);
    }



    /**
     * Scope for active services
     */
    public function scopeActive($query)
    {
        return $query->where('status', ServiceStatus::ACTIVE);
    }

    /**
     * Scope for expired services
     */
    public function scopeExpired($query)
    {
        return $query->where('status', ServiceStatus::EXPIRED);
    }

    /**
     * Scope for suspended services
     */
    public function scopeSuspended($query)
    {
        return $query->where('status', ServiceStatus::SUSPENDED);
    }

    /**
     * Scope for cancelled services
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', ServiceStatus::CANCELLED);
    }

    /**
     * Check if service is active
     */
    public function isActive(): bool
    {
        return $this->status === ServiceStatus::ACTIVE;
    }

    /**
     * Check if service is expired
     */
    public function isExpired(): bool
    {
        return $this->status === ServiceStatus::EXPIRED;
    }

    /**
     * Get service display name with identifier
     */
    public function getDisplayNameAttribute(): string
    {
        $identifier = $this->service_identifier ? " ({$this->service_identifier})" : '';
        $customerName = $this->customer->customer_type === 'corporate' 
            ? $this->customer->name 
            : $this->customer->name . ' ' . ($this->customer->surname ?? '');
        
        return "#{$this->id} - {$this->service_type}{$identifier} - {$customerName}";
    }

    /**
     * Generate unique service code
     */
    public static function generateUniqueServiceCode(): string
    {
        do {
            $code = strtoupper(substr(md5(uniqid()), 0, 8));
        } while (static::where('service_code', $code)->exists());
        
        return $code;
    }

    /**
     * Check if service is suspended
     */
    public function isSuspended(): bool
    {
        return $this->status === ServiceStatus::SUSPENDED;
    }

    /**
     * Check if service is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === ServiceStatus::CANCELLED;
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

    /**
     * Get Monthly Recurring Revenue (MRR) for this service
     */
    public function getMrrAttribute(): float
    {
        if (!$this->price || !$this->cycle) {
            return 0.0;
        }

        $price = (float) $this->price;
        $cycle = $this->cycle;

        return match ($cycle) {
            'monthly' => $price,
            'quarterly' => $price / 3,
            'yearly' => $price / 12,
            'biennial' => $price / 24,
            default => 0.0,
        };
    }

    /**
     * Get Annual Recurring Revenue (ARR) for this service
     */
    public function getArrAttribute(): float
    {
        if (!$this->price || !$this->cycle) {
            return 0.0;
        }

        $price = (float) $this->price;
        $cycle = $this->cycle;

        return match ($cycle) {
            'monthly' => $price * 12,
            'quarterly' => $price * 4,
            'yearly' => $price,
            'biennial' => $price / 2,
            default => 0.0,
        };
    }
}
