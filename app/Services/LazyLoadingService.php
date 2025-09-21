<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\Quote;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class LazyLoadingService
{
    /**
     * Customer listesi için optimize edilmiş veri yükleme
     */
    public function getOptimizedCustomerList(array $filters = []): array
    {
        $cacheKey = 'customers.list.' . md5(serialize($filters));
        
        return Cache::remember($cacheKey, 300, function () use ($filters) {
            $query = Customer::withBalanceAndStats();
            
            // Filtreleri uygula
            if (!empty($filters['search'])) {
                $query->where(function ($q) use ($filters) {
                    $q->where('name', 'like', "%{$filters['search']}%")
                      ->orWhere('email', 'like', "%{$filters['search']}%");
                });
            }
            
            if (isset($filters['status'])) {
                $query->where('is_active', $filters['status'] === 'active');
            }
            
            return [
                'customers' => $query->latest('created_at')->paginate(15),
                'stats' => $this->getCustomerStats()
            ];
        });
    }

    /**
     * Tek sorguda customer istatistikleri
     */
    private function getCustomerStats(): array
    {
        $statsResult = DB::select("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN ledger_balance.balance > 0 THEN ledger_balance.balance ELSE 0 END) as receivable,
                SUM(CASE WHEN ledger_balance.balance < 0 THEN ABS(ledger_balance.balance) ELSE 0 END) as payable
            FROM customers
            LEFT JOIN (
                SELECT customer_id, SUM(debit) - SUM(credit) as balance
                FROM ledger_entries GROUP BY customer_id
            ) as ledger_balance ON customers.id = ledger_balance.customer_id
        ")[0];

        return [
            'total' => (int) $statsResult->total,
            'active' => (int) $statsResult->active,
            'receivable' => (float) $statsResult->receivable,
            'payable' => (float) $statsResult->payable
        ];
    }

    /**
     * Service listesi için optimize edilmiş veri yükleme
     */
    public function getOptimizedServiceList(array $filters = []): array
    {
        $cacheKey = 'services.list.' . md5(serialize($filters));
        
        return Cache::remember($cacheKey, 300, function () use ($filters) {
            $query = Service::select([
                'id', 'customer_id', 'provider_id', 'service_type', 'status', 
                'start_date', 'end_date', 'cycle', 'payment_type', 'sell_price'
            ])->with([
                'customer:id,name,surname,email',
                'provider:id,name'
            ]);
            
            // Filtreleri uygula
            if (!empty($filters['type'])) {
                $query->where('service_type', $filters['type']);
            }
            
            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }
            
            return [
                'services' => $query->latest('id')->paginate(15),
                'stats' => $this->getServiceStats()
            ];
        });
    }

    /**
     * Tek sorguda service istatistikleri
     */
    private function getServiceStats(): array
    {
        $statsResult = DB::select("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN service_type = 'domain' THEN 1 ELSE 0 END) as domains,
                SUM(CASE WHEN service_type = 'hosting' THEN 1 ELSE 0 END) as hostings,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active
            FROM services
        ")[0];

        return [
            'total' => (int) $statsResult->total,
            'domains' => (int) $statsResult->domains,
            'hostings' => (int) $statsResult->hostings,
            'active' => (int) $statsResult->active
        ];
    }

    /**
     * Invoice listesi için optimize edilmiş veri yükleme
     */
    public function getOptimizedInvoiceList(array $filters = []): array
    {
        $cacheKey = 'invoices.list.' . md5(serialize($filters));
        
        return Cache::remember($cacheKey, 300, function () use ($filters) {
            $query = Invoice::select([
                'id', 'customer_id', 'invoice_number', 'issue_date', 'due_date', 'status', 'total'
            ])->with(['customer:id,name,surname,email']);
            
            // Filtreleri uygula
            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }
            
            return [
                'invoices' => $query->latest('issue_date')->paginate(15),
                'stats' => $this->getInvoiceStats()
            ];
        });
    }

    /**
     * Tek sorguda invoice istatistikleri
     */
    private function getInvoiceStats(): array
    {
        $statsResult = DB::select("
            SELECT 
                COUNT(*) as total,
                COALESCE(SUM(total), 0) as total_amount,
                COALESCE((
                    SELECT SUM(amount) FROM payments WHERE paid_at IS NOT NULL
                ), 0) as paid_amount,
                SUM(CASE WHEN status = 'overdue' THEN 1 ELSE 0 END) as overdue_count
            FROM invoices
        ")[0];

        return [
            'total' => (int) $statsResult->total,
            'total_amount' => (float) $statsResult->total_amount,
            'paid_amount' => (float) $statsResult->paid_amount,
            'overdue_count' => (int) $statsResult->overdue_count
        ];
    }

    /**
     * Dashboard için optimize edilmiş veri yükleme
     */
    public function getDashboardData(): array
    {
        return Cache::remember('dashboard.data', 300, function () {
            return [
                'expiring_services' => $this->getExpiringServices(),
                'overdue_invoices' => $this->getOverdueInvoices(),
                'recent_customers' => $this->getRecentCustomers(),
                'quick_stats' => $this->getQuickStats()
            ];
        });
    }

    /**
     * Yakında biten hizmetler
     */
    private function getExpiringServices()
    {
        return Service::select(['id', 'customer_id', 'service_type', 'end_date'])
            ->with(['customer:id,name,surname,email'])
            ->active()
            ->whereNotNull('end_date')
            ->whereDate('end_date', '<=', now()->addDays(30))
            ->whereDate('end_date', '>=', now())
            ->orderBy('end_date')
            ->limit(10)
            ->get();
    }

    /**
     * Vadesi geçmiş faturalar
     */
    private function getOverdueInvoices()
    {
        return Invoice::select(['id', 'customer_id', 'invoice_number', 'due_date', 'total'])
            ->with(['customer:id,name,surname,email'])
            ->overdue()
            ->orderBy('due_date')
            ->limit(10)
            ->get();
    }

    /**
     * Son müşteriler
     */
    private function getRecentCustomers()
    {
        return Customer::select(['id', 'name', 'surname', 'email', 'created_at'])
            ->latest('created_at')
            ->limit(5)
            ->get();
    }

    /**
     * Hızlı istatistikler
     */
    private function getQuickStats(): array
    {
        $statsResult = DB::select("
            SELECT 
                (SELECT COUNT(*) FROM customers WHERE is_active = 1) as active_customers,
                (SELECT COUNT(*) FROM services WHERE status = 'active') as active_services,
                (SELECT COUNT(*) FROM invoices WHERE status = 'overdue') as overdue_invoices,
                (SELECT COALESCE(SUM(total), 0) FROM invoices WHERE status = 'unpaid') as unpaid_total
        ")[0];

        return [
            'active_customers' => (int) $statsResult->active_customers,
            'active_services' => (int) $statsResult->active_services,
            'overdue_invoices' => (int) $statsResult->overdue_invoices,
            'unpaid_total' => (float) $statsResult->unpaid_total
        ];
    }
}
