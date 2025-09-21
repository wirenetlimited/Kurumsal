<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MetricService
{
    public function __construct(private MRRService $mrr) {}

    public function totalCustomers(): int
    {
        return Customer::count(); // Tüm müşteriler
    }

    public function activeServices(): int
    {
        return \App\Models\Service::active()->count();
    }

    public function overdue(): array
    {
        // Due date geçmiş ve ödenmemiş faturalar
        $q = Invoice::query()
            ->where('due_date', '<', now())
            ->whereIn('status', ['sent', 'draft'])
            ->whereRaw('(total - COALESCE(paid_amount, 0)) > 0');
        
        return [
            'count' => $q->count(),
            'amount' => (float) $q->sum(DB::raw('total - COALESCE(paid_amount, 0)')),
        ];
    }

    public function thisMonthRevenue(): float
    {
        $startOfMonth = Carbon::now()->copy()->startOfMonth();
        $startOfNextMonth = (clone $startOfMonth)->addMonth();

        // Bu ay kesilen (taslak hariç) faturaların ID'lerini al
        $thisMonthInvoiceIds = Invoice::whereNotNull('issue_date')
            ->whereIn('status', [
                \App\Enums\InvoiceStatus::SENT,
                \App\Enums\InvoiceStatus::PAID,
                \App\Enums\InvoiceStatus::OVERDUE,
            ])
            ->where('issue_date', '>=', $startOfMonth)
            ->where('issue_date', '<', $startOfNextMonth)
            ->pluck('id');

        // Bu ay kesilen faturaların bu ay tahsil edilen kısmı
        return (float) Payment::whereIn('invoice_id', $thisMonthInvoiceIds)
            ->whereNotNull('paid_at')
            ->where('paid_at', '>=', $startOfMonth)
            ->where('paid_at', '<', $startOfNextMonth)
            ->sum('amount');
    }

    public function unpaidTotal(): float
    {
        return (float) Invoice::whereIn('status',['sent','overdue'])
            ->get()
            ->sum(fn($i)=> max(0, (float)$i->total - (float)$i->paid_amount));
    }

    public function mrr(): float
    {
        return $this->mrr->current();
    }

    public function mrrByType(): array
    {
        return $this->mrr->byType();
    }
}
