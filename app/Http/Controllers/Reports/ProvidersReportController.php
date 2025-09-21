<?php
namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\Service;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProvidersReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 12);
        $startDate = now()->subMonths($period);
        
        // Sağlayıcı istatistikleri
        $totalProviders = Provider::count();
        $activeProviders = Provider::count(); // Tüm sağlayıcılar aktif olarak kabul ediliyor
        $thisMonthProviders = Provider::whereMonth('created_at', now()->month)
                                    ->whereYear('created_at', now()->year)
                                    ->count();
        
        // Aylık sağlayıcı verileri
        $values = [];
        $labels = [];
        
        for ($i = $period - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();
            
            $count = Provider::whereBetween('created_at', [$monthStart, $monthEnd])->count();
            $values[] = $count;
            $labels[] = $date->isoFormat('MMM YY');
        }
        
        // En çok kullanılan sağlayıcılar
        $topProviders = Service::select('provider_id', 'providers.name', DB::raw('count(*) as service_count'))
                               ->join('providers', 'services.provider_id', '=', 'providers.id')
                               ->groupBy('provider_id', 'providers.name')
                               ->orderBy('service_count', 'desc')
                               ->limit(5)
                               ->get();
        
        // En çok kullanılan sağlayıcı adı
        $topProviderName = $topProviders->first()?->name ?? '-';
        
        return view('reports.providers', compact(
            'period',
            'totalProviders',
            'activeProviders',
            'thisMonthProviders',
            'values',
            'labels',
            'topProviders',
            'topProviderName'
        ));
    }
}

