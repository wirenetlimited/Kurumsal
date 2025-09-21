<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\Provider;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\SettingsHelper;
use App\Services\MetricService;
use App\Services\RevenueCacheService;

class DashboardController extends Controller
{
    use SettingsHelper;
    
    public function __invoke(Request $request, MetricService $metrics, RevenueCacheService $revenueCache): View
    {
        try {
            $now = now();
            $range = $request->get('range', '12m'); // '7d', '1m', '3m', '6m', '12m'
            
            // Cache'den MRR verilerini al
            $mrrData = $revenueCache->getMRRData();
            $mrrByType = $revenueCache->getMRRByType();
            
            // MetricService ile ana kartları al
            $cards = [
                'totalCustomers'    => $metrics->totalCustomers(),
                'activeServices'    => $metrics->activeServices(),
                'thisMonthRevenue'  => $metrics->thisMonthRevenue(),
                'overdueCount'      => $metrics->overdue()['count'],
                'overdueAmount'     => $metrics->overdue()['amount'],
                'mrr'               => $mrrData['total_mrr'],
            ];

            // Ana istatistikler
            $totals = Cache::remember('dashboard.totals', 300, function () {
                return [
                    'customers' => Customer::count(),
                    'activeCustomers' => Customer::where('is_active', true)->count(),
                    'providers' => Provider::count(),
                    'services' => Service::count(),
                    'activeServices' => Service::active()->count(),
                    'expiredServices' => Service::expired()->count(),
                ];
            });

            // Fatura istatistikleri (bu ay)
            $invoiceStats = Cache::remember('dashboard.invoiceStats', 300, function () {
                $currencySymbol = $this->getSetting('currency_symbol', '₺');
                $startOfMonth = now()->copy()->startOfMonth();
                $startOfNextMonth = (clone $startOfMonth)->addMonth();

                // Bu ay kesilen faturalar (taslak hariç)
                $thisMonthInvoices = Invoice::whereNotNull('issue_date')
                    ->whereIn('status', [
                        \App\Enums\InvoiceStatus::SENT,
                        \App\Enums\InvoiceStatus::PAID,
                        \App\Enums\InvoiceStatus::OVERDUE,
                    ])
                    ->where('issue_date', '>=', $startOfMonth)
                    ->where('issue_date', '<', $startOfNextMonth);

                $issuedThisMonth = (float) (clone $thisMonthInvoices)->sum('total');
                $thisMonthInvoiceIds = (clone $thisMonthInvoices)->pluck('id');

                // Bu ay tahsil edilen (bu ay kesilen faturaların bu ay tahsil edilen kısmı)
                $paidThisMonth = (float) \App\Models\Payment::whereIn('invoice_id', $thisMonthInvoiceIds)
                    ->whereNotNull('paid_at')
                    ->where('paid_at', '>=', $startOfMonth)
                    ->where('paid_at', '<', $startOfNextMonth)
                    ->sum('amount');

                $pendingThisMonth = max(0, $issuedThisMonth - $paidThisMonth);

                return [
                    'issuedThisMonth' => $issuedThisMonth,
                    'paidThisMonth' => $paidThisMonth,
                    'pendingThisMonth' => $pendingThisMonth,
                    'overdueCount' => (int) Invoice::overdue()->count() ?? 0,
                    'sentCount' => (int) Invoice::sent()->count() ?? 0,
                    'totalRevenue' => (float) Invoice::where('status', \App\Enums\InvoiceStatus::PAID)->sum('total') ?? 0,
                    'currencySymbol' => $currencySymbol,
                ];
            });

            // Yakında biten hizmetler - Sadece gerekli alanları seç
            $expiringIn30 = Service::select([
                'id', 'customer_id', 'service_type', 'end_date', 'status'
            ])
                ->with(['customer:id,name,surname,email,customer_type'])
                ->active()
                ->where(function($query) use ($now) {
                    $query->where(function($q) use ($now) {
                        // End date olan ve 30 gün içinde biten hizmetler
                        $q->whereNotNull('end_date')
                          ->whereDate('end_date','<=', $now->copy()->addDays(30))
                          ->whereDate('end_date','>=', $now->copy());
                    })->orWhere(function($q) {
                        // End date olmayan hizmetler (süresiz)
                        $q->whereNull('end_date');
                    });
                })
                ->orderByRaw('CASE WHEN end_date IS NULL THEN 1 ELSE 0 END, end_date ASC')
                ->limit(10)
                ->get();

            // 7 gün içinde biten hizmetler
            $expiringIn7 = Service::select([
                'id', 'customer_id', 'service_type', 'end_date', 'status'
            ])
                ->with(['customer:id,name,surname,email,customer_type'])
                ->active()
                ->where(function($query) use ($now) {
                    $query->where(function($q) use ($now) {
                        // End date olan ve 7 gün içinde biten hizmetler
                        $q->whereNotNull('end_date')
                          ->whereDate('end_date','<=', $now->copy()->addDays(7))
                          ->whereDate('end_date','>=', $now->copy());
                    })->orWhere(function($q) {
                        // End date olmayan hizmetler (süresiz)
                        $q->whereNull('end_date');
                    });
                })
                ->orderByRaw('CASE WHEN end_date IS NULL THEN 1 ELSE 0 END, end_date ASC')
                ->limit(10)
                ->get();

            // Vadesi geçmiş faturalar - Sadece gerekli alanları seç
            $overdueInvoices = Invoice::select([
                'id', 'customer_id', 'invoice_number', 'due_date', 'total'
            ])
                ->with(['customer:id,name,surname,email,customer_type'])
                ->overdue()
                ->orderBy('due_date')
                ->limit(10)
                ->get();

            // Son müşteriler - Sadece gerekli alanları seç
            $recentCustomers = Customer::select([
                'id', 'name', 'surname', 'email', 'customer_type', 'created_at'
            ])
                ->latest('created_at')
                ->limit(5)
                ->get();

            // Vadesi geçmiş faturalar - Sadece gerekli alanları seç
            $overdueInvoices = Invoice::select([
                'id', 'customer_id', 'invoice_number', 'due_date', 'total'
            ])
                ->with(['customer:id,name,surname,email,customer_type'])
                ->overdue()
                ->orderBy('due_date')
                ->limit(10)
                ->get();

            // Gelir serisi (seçili aralığa göre) - Database agnostic
            $revenueSeries = Cache::remember('dashboard.revenueSeries.' . $range, 300, function () use ($now, $range) {
                // Günlük ve aylık kırılımlar için anahtarları hazırla
                if ($range === '7d' || $range === '1m') {
                    // Günlük
                    $daysBack = $range === '7d' ? 6 : 29; // bugün dahil 7 veya 30 gün
                    $days = collect();
                    for ($i = $daysBack; $i >= 0; $i--) {
                        $day = $now->copy()->subDays($i)->format('Y-m-d');
                        $days->put($day, 0);
                    }

                    $fromDate = $range === '7d' ? $now->copy()->subDays(6)->startOfDay() : $now->copy()->subDays(29)->startOfDay();

                    $revenueData = Invoice::paid()
                        ->whereNotNull('issue_date')
                        ->where('issue_date', '>=', $fromDate)
                        ->get()
                        ->groupBy(function ($invoice) {
                            return $invoice->issue_date->format('Y-m-d');
                        })
                        ->map(function ($group) {
                            return $group->sum('total');
                        });

                    foreach ($revenueData as $day => $amount) {
                        if ($days->has($day)) {
                            $days->put($day, (float) $amount);
                        }
                    }

                    return $days;
                }

                // Aylık (3m, 6m, 12m)
                $monthsCount = match ($range) {
                    '3m' => 3,
                    '6m' => 6,
                    default => 12,
                };

                $months = collect();
                for ($i = $monthsCount - 1; $i >= 0; $i--) {
                    $month = $now->copy()->subMonths($i)->format('Y-m');
                    $months->put($month, 0);
                }

                $fromMonth = $now->copy()->subMonths($monthsCount)->startOfMonth();

                $revenueData = Invoice::paid()
                    ->whereNotNull('issue_date')
                    ->where('issue_date', '>=', $fromMonth)
                    ->get()
                    ->groupBy(function ($invoice) {
                        return $invoice->issue_date->format('Y-m');
                    })
                    ->map(function ($group) {
                        return $group->sum('total');
                    });

                foreach ($revenueData as $month => $amount) {
                    if ($months->has($month)) {
                        $months->put($month, (float) $amount);
                    }
                }

                return $months;
            });

            // Hizmet türü dağılımı - null kontrolü ile
            $serviceDistribution = Service::select('service_type', DB::raw('COUNT(*) as count'))
                ->where('status', 'active')
                ->whereNotNull('service_type')
                ->groupBy('service_type')
                ->pluck('count', 'service_type')
                ->filter(function($count, $type) {
                    return $type && $count > 0;
                });

            // Sağlayıcı performansı
            $providerPerformance = Provider::withCount(['services'])
                ->withSum('services', 'sell_price')
                ->orderBy('services_count', 'desc')
                ->limit(5)
                ->get();

            // Son aktiviteler
            $recentActivities = collect();
            
            try {
                // Son faturalar
                $recentInvoices = Invoice::with('customer')
                    ->whereNotNull('issue_date')
                    ->latest('issue_date')
                    ->limit(4)
                    ->get();
                
                foreach ($recentInvoices as $invoice) {
                    $recentActivities->push([
                        'type' => 'invoice',
                        'title' => 'Yeni fatura oluşturuldu',
                        'description' => $invoice->invoice_number . ' - ' . ($invoice->customer->name ?? 'Bilinmeyen Müşteri'),
                        'date' => $invoice->issue_date,
                        'amount' => $invoice->total,
                        'icon' => '📄',
                        'color' => 'blue'
                    ]);
                }

                            // Son müşteriler
            $recentCustomers = Customer::select(['id', 'name', 'surname', 'customer_type', 'created_at'])
                ->latest('created_at')
                ->limit(3)
                ->get();
            foreach ($recentCustomers as $customer) {
                $recentActivities->push([
                    'type' => 'customer',
                    'title' => 'Yeni müşteri kaydı',
                    'description' => $customer->customer_type === 'corporate' ? $customer->name : $customer->name . ' ' . ($customer->surname ?? ''),
                    'date' => $customer->created_at,
                    'amount' => null,
                    'icon' => '👤',
                    'color' => 'green'
                ]);
            }

                            // Son hizmetler
            $recentServices = Service::with(['customer:id,name,surname,customer_type'])
                ->latest('created_at')
                ->limit(3)
                ->get();
            
            foreach ($recentServices as $service) {
                $customerName = $service->customer ? 
                    ($service->customer->customer_type === 'corporate' ? $service->customer->name : $service->customer->name . ' ' . ($service->customer->surname ?? '')) 
                    : 'Bilinmeyen Müşteri';
                
                $recentActivities->push([
                    'type' => 'service',
                    'title' => 'Yeni hizmet eklendi',
                    'description' => $customerName,
                    'date' => $service->created_at,
                    'amount' => $service->sell_price,
                    'icon' => '🔧',
                    'color' => 'purple'
                ]);
            }

            } catch (\Exception $e) {
                Log::error('Son aktiviteler yüklenirken hata: ' . $e->getMessage());
            }

            $recentActivities = $recentActivities->sortByDesc('date')->take(10);

            // Yakında biten hizmetler (detaylı)
            $expiringServices = Service::select([
                'id', 'customer_id', 'service_type', 'end_date', 'status'
            ])
                ->with(['customer:id,name,surname,email,customer_type'])
                ->active()
                ->whereNotNull('end_date')
                ->whereDate('end_date','<=', $now->copy()->addDays(30))
                ->whereDate('end_date','>=', $now->copy())
                ->orderBy('end_date')
                ->limit(10)
                ->get()
                ->map(function ($service) {
                    if ($service->end_date) {
                        // Kalan gün sayısını hesapla (pozitif değer)
                        $days = now()->diffInDays($service->end_date, false);
                        $service->days_remaining = max(0, (int)$days);
                    } else {
                        $service->days_remaining = null;
                    }
                    return $service;
                })
                ->filter(function ($service) {
                    return $service->customer; // Sadece customer'ı olan hizmetleri göster
                });

            // Aylık büyüme
            $monthlyGrowth = $this->calculateMonthlyGrowth();

            $user = Auth::user();

            return view('dashboard', compact(
                'cards',
                'mrrByType',
                'totals',
                'invoiceStats', 
                'expiringIn30',
                'expiringIn7',
                'revenueSeries',
                'serviceDistribution',
                'providerPerformance',
                'recentActivities',
                'expiringServices',
                'overdueInvoices',
                'recentCustomers',
                'monthlyGrowth',
                'user',
                'range'
            ));
            
        } catch (\Exception $e) {
            Log::error('Dashboard yüklenirken hata: ' . $e->getMessage());
            
            // Hata durumunda varsayılan değerler
            return view('dashboard', [
                'cards' => [
                    'totalCustomers' => 0,
                    'activeServices' => 0,
                    'thisMonthRevenue' => 0,
                    'overdueCount' => 0,
                    'overdueAmount' => 0,
                    'mrr' => 0
                ],
                'mrrByType' => collect(),
                'totals' => ['customers' => 0, 'activeCustomers' => 0, 'providers' => 0, 'services' => 0, 'activeServices' => 0, 'expiredServices' => 0],
                'invoiceStats' => [
                    'issuedThisMonth' => 0,
                    'paidThisMonth' => 0,
                    'pendingThisMonth' => 0,
                    'unpaidTotal' => 0,
                    'overdueCount' => 0,
                    'sentCount' => 0,
                    'totalRevenue' => 0,
                    'currencySymbol' => '₺'
                ],
                'expiringIn30' => collect(),
                'expiringIn7' => collect(),
                'mrr' => 0,
                'revenueSeries' => collect(),
                'serviceDistribution' => collect(),
                'providerPerformance' => collect(),
                'recentActivities' => collect(),
                'expiringServices' => collect(),
                'overdueInvoices' => collect(),
                'recentCustomers' => collect(),
                'monthlyGrowth' => ['current' => 0, 'previous' => 0, 'percentage' => 0, 'trend' => 'up'],
                'user' => Auth::user(),
                'range' => '12m'
            ]);
        }
    }

    private function calculateMonthlyGrowth()
    {
        try {
            $currentMonth = Invoice::where('status', 'paid')
                ->whereNotNull('issue_date')
                ->whereMonth('issue_date', now()->month)
                ->sum('total') ?? 0;

            $lastMonth = Invoice::where('status', 'paid')
                ->whereNotNull('issue_date')
                ->whereMonth('issue_date', now()->subMonth()->month)
                ->sum('total') ?? 0;

            if ($lastMonth > 0) {
                $growth = (($currentMonth - $lastMonth) / $lastMonth) * 100;
            } else {
                $growth = 0;
            }

            return [
                'current' => $currentMonth,
                'previous' => $lastMonth,
                'percentage' => round($growth, 1),
                'trend' => $growth >= 0 ? 'up' : 'down'
            ];
        } catch (\Exception $e) {
            Log::error('Aylık büyüme hesaplanırken hata: ' . $e->getMessage());
            return [
                'current' => 0,
                'previous' => 0,
                'percentage' => 0,
                'trend' => 'up'
            ];
        }
    }
}
