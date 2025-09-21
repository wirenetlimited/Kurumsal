<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\AccountingLedgerEntry;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class BalanceController extends Controller
{
    public function index(Request $request): View
    {
        try {
            $statusFilter = $request->get('status', 'all');
        
        // Müşteri bazlı bakiye hesaplama - ledger_entries tablosundan
        $customerBalances = Customer::with(['ledgerEntries' => function($query) {
                $query->select('id', 'customer_id', 'debit', 'credit');
            }])
            ->select([
                'customers.id',
                'customers.name',
                'customers.email',
                'customers.is_active as customer_status'
            ])
            ->where('customers.is_active', true)
            ->orderBy('customers.name')
            ->get()
            ->map(function ($customer) {
                $totalDebit = $customer->ledgerEntries->sum('debit');
                $totalCredit = $customer->ledgerEntries->sum('credit');
                $netBalance = $totalDebit - $totalCredit;
                
                // Durum belirleme
                if ($netBalance > 0) {
                    $status = 'borclu';
                    $statusText = 'Borçlu';
                    $statusColor = 'red';
                } elseif ($netBalance < 0) {
                    $status = 'alacakli';
                    $statusText = 'Alacaklı';
                    $statusColor = 'blue';
                } else {
                    $status = 'dengede';
                    $statusText = 'Dengede';
                    $statusColor = 'green';
                }
                
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'customer_status' => $customer->customer_status,
                    'total_debt' => (float) $totalDebit,
                    'total_payments' => (float) $totalCredit,
                    'net_balance' => $netBalance,
                    'status' => $status,
                    'status_text' => $statusText,
                    'status_color' => $statusColor,
                ];
            });
        
        // Status filtresi uygula
        if ($statusFilter !== 'all') {
            $customerBalances = $customerBalances->filter(function ($customer) use ($statusFilter) {
                return $customer['status'] === $statusFilter;
            });
        }
        
        // Genel özet hesapla
        $summary = [
            'total_debt' => $customerBalances->sum('total_debt'),
            'total_payments' => $customerBalances->sum('total_payments'),
            'net_balance' => $customerBalances->sum('net_balance'),
            'total_customers' => $customerBalances->count(),
        ];
        
        // Status sayıları
        $statusCounts = [
            'borclu' => $customerBalances->where('status', 'borclu')->count(),
            'dengede' => $customerBalances->where('status', 'dengede')->count(),
            'alacakli' => $customerBalances->where('status', 'alacakli')->count(),
        ];
        
        return view('admin.customers.balances', compact(
            'customerBalances',
            'summary',
            'statusCounts',
            'statusFilter'
        ));
        } catch (\Exception $e) {
            \Log::error('BalanceController error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Hata durumunda boş veri ile view döndür
            return view('admin.customers.balances', [
                'customerBalances' => collect(),
                'summary' => [
                    'total_debt' => 0,
                    'total_payments' => 0,
                    'net_balance' => 0,
                    'total_customers' => 0,
                ],
                'statusCounts' => [
                    'borclu' => 0,
                    'dengede' => 0,
                    'alacakli' => 0,
                ],
                'statusFilter' => 'all'
            ]);
        }
    }
}
