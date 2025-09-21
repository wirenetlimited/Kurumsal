<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'surname',
        'tax_number',
        'email',
        'website',
        'phone',
        'phone_mobile',
        'address',
        'city',
        'district',
        'zip',
        'country',
        'invoice_address',
        'invoice_city',
        'invoice_district',
        'invoice_zip',
        'invoice_country',
        'customer_type',
        'is_active',
        'notes',
    ];

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function ledgerEntries(): HasMany
    {
        return $this->hasMany(LedgerEntry::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Scope to include current balance and service statistics
     */
    public function scopeWithBalanceAndStats(Builder $query): Builder
    {
        return $query        ->select([
            'customers.*',
            DB::raw('COALESCE(ledger_balance.balance, 0) as current_balance'),
            DB::raw('COALESCE(ledger_balance.total_debit, 0) as total_debit'),
            DB::raw('COALESCE(ledger_balance.total_credit, 0) as total_credit'),
            DB::raw('COALESCE(service_stats.service_count, 0) as service_count'),
            DB::raw('COALESCE(service_stats.monthly_revenue, 0) as monthly_revenue')
        ])
        ->leftJoin(DB::raw('(
            SELECT 
                customer_id,
                SUM(debit) - SUM(credit) as balance,
                SUM(debit) as total_debit,
                SUM(credit) as total_credit
            FROM ledger_entries 
            GROUP BY customer_id
        ) as ledger_balance'), 'customers.id', '=', 'ledger_balance.customer_id')
        ->leftJoin(DB::raw('(
            SELECT 
                customer_id,
                COUNT(*) as service_count,
                SUM(
                    CASE 
                        WHEN cycle IS NOT NULL AND status = "active"
                        THEN CASE 
                            WHEN cycle = "monthly" THEN COALESCE(sell_price, price)
                            WHEN cycle = "quarterly" THEN COALESCE(sell_price, price) / 3
                            WHEN cycle = "semiannual" THEN COALESCE(sell_price, price) / 6
                            WHEN cycle = "yearly" THEN COALESCE(sell_price, price) / 12
                            WHEN cycle = "biennial" THEN COALESCE(sell_price, price) / 24
                            WHEN cycle = "triennial" THEN COALESCE(sell_price, price) / 36
                            ELSE COALESCE(sell_price, price) / 12
                        END
                        ELSE 0
                    END
                ) as monthly_revenue
            FROM services 
            WHERE status = "active"
            GROUP BY customer_id
        ) as service_stats'), 'customers.id', '=', 'service_stats.customer_id');
    }

    /**
     * Get current balance attribute - Lazy loading optimized
     */
    public function getCurrentBalanceAttribute(): float
    {
        if (isset($this->attributes['current_balance'])) {
            return (float) $this->attributes['current_balance'];
        }

        // Eğer ledgerEntries yüklenmişse, collection'dan hesapla
        if ($this->relationLoaded('ledgerEntries')) {
            return (float) $this->ledgerEntries->sum('debit') - $this->ledgerEntries->sum('credit');
        }

        // Değilse tek sorguda hesapla
        return (float) $this->ledgerEntries()
            ->selectRaw('COALESCE(SUM(debit), 0) - COALESCE(SUM(credit), 0) as balance')
            ->value('balance') ?? 0.0;
    }

    /**
     * Get verified balance attribute - Enhanced with data consistency check
     */
    public function getVerifiedBalanceAttribute(): float
    {
        // Ledger entries'den bakiye
        $ledgerBalance = (float) $this->ledgerEntries()
            ->selectRaw('COALESCE(SUM(debit), 0) - COALESCE(SUM(credit), 0) as balance')
            ->value('balance') ?? 0.0;

        // Gerçek fatura ve ödeme verilerinden bakiye
        $invoiceTotal = (float) $this->invoices()
            ->where('status', '!=', 'cancelled')
            ->sum('total');
        
        $paymentTotal = (float) $this->payments()
            ->whereNotNull('paid_at')
            ->sum('amount');

        $calculatedBalance = $invoiceTotal - $paymentTotal;

        // Tutarlılık kontrolü
        if (abs($ledgerBalance - $calculatedBalance) > 0.01) {
            \Log::warning('Balance inconsistency detected', [
                'customer_id' => $this->id,
                'customer_name' => $this->name,
                'ledger_balance' => $ledgerBalance,
                'calculated_balance' => $calculatedBalance,
                'difference' => $ledgerBalance - $calculatedBalance
            ]);
        }

        // Gerçek verilerden hesaplanan bakiyeyi döndür
        return $calculatedBalance;
    }

    /**
     * Get balance status - Enhanced with verification
     */
    public function getBalanceStatusAttribute(): array
    {
        $ledgerBalance = $this->current_balance;
        $verifiedBalance = $this->verified_balance;
        $isConsistent = abs($ledgerBalance - $verifiedBalance) < 0.01;

        $status = match(true) {
            $verifiedBalance > 0 => 'borclu',
            $verifiedBalance < 0 => 'alacakli',
            default => 'dengede'
        };

        $statusText = match($status) {
            'borclu' => 'Borçlu',
            'alacakli' => 'Alacaklı',
            default => 'Dengede'
        };

        $statusColor = match($status) {
            'borclu' => 'red',
            'alacakli' => 'blue',
            default => 'green'
        };

        return [
            'status' => $status,
            'status_text' => $statusText,
            'status_color' => $statusColor,
            'ledger_balance' => $ledgerBalance,
            'verified_balance' => $verifiedBalance,
            'is_consistent' => $isConsistent,
            'inconsistency_amount' => $ledgerBalance - $verifiedBalance
        ];
    }

    /**
     * Get service count attribute - Lazy loading optimized
     */
    public function getServiceCountAttribute(): int
    {
        if (isset($this->attributes['service_count'])) {
            return (int) $this->attributes['service_count'];
        }

        // Eğer services yüklenmişse, collection'dan say
        if ($this->relationLoaded('services')) {
            return $this->services->count();
        }

        // Değilse tek sorguda say
        return $this->services()->count();
    }

    /**
     * Get monthly revenue attribute - Lazy loading optimized
     */
    public function getMonthlyRevenueAttribute(): float
    {
        if (isset($this->attributes['monthly_revenue'])) {
            return (float) $this->attributes['monthly_revenue'];
        }

        // Eğer services yüklenmişse, collection'dan hesapla
        if ($this->relationLoaded('services')) {
            return (float) $this->services
                ->where('status', 'active')
                ->sum(function ($service) {
                    $price = (float) $service->sell_price;
                    
                    // Sadece taksitli ödeme hizmetleri MRR'ye dahil edilir
                    if ($service->payment_type === 'installment') {
                        $months = match($service->cycle) {
                            'monthly' => 1,
                            'quarterly' => 3,
                            'semiannually' => 6,
                            'yearly' => 12,
                            'biennially' => 24,
                            'triennially' => 36,
                            default => 12
                        };
                        return $months > 0 ? $price / $months : 0;
                    }
                    
                    return 0;
                });
        }

        // Değilse tek sorguda hesapla
        return (float) $this->services()
            ->where('status', 'active')
            ->get()
            ->sum(function ($service) {
                $price = (float) $service->sell_price;
                
                // Sadece taksitli ödeme hizmetleri MRR'ye dahil edilir
                if ($service->payment_type === 'installment') {
                    $months = match($service->cycle) {
                        'monthly' => 1,
                        'quarterly' => 3,
                        'semiannually' => 6,
                        'yearly' => 12,
                        'biennially' => 24,
                        'triennially' => 36,
                        default => 12
                    };
                    return $months > 0 ? $price / $months : 0;
                }
                
                // Upfront payment için MRR hesaplanmaz
                return 0;
            });
    }

    /**
     * Scope to load only essential customer data for lists
     */
    public function scopeForList(Builder $query): Builder
    {
        return $query->select([
            'id', 'name', 'surname', 'email', 'phone', 'customer_type', 'is_active'
        ]);
    }

    /**
     * Scope to load customer with minimal relationships for show pages
     */
    public function scopeWithMinimalRelations(Builder $query): Builder
    {
        return $query->with([
            'services:id,customer_id,service_type,status,sell_price,start_date,end_date',
            'invoices:id,customer_id,invoice_number,issue_date,due_date,status,total'
        ]);
    }

    /**
     * Get display name for dropdowns and lists
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->customer_type === 'corporate') {
            return $this->name; // Kurumsal müşteriler için sadece ünvan
        }
        
        // Bireysel müşteriler için ad + soyad
        return trim($this->name . ' ' . ($this->surname ?? ''));
    }


}
