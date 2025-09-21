<?php
namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Service;

class ServicesReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 12);
        
        // Son X ayın verilerini al
        $startDate = \Carbon\Carbon::now()->subMonths($period);
        
        // Hizmet türlerine göre dağılım
        $serviceTypes = DB::table('services')
            ->selectRaw('service_type, COUNT(*) as count')
            ->groupBy('service_type')
            ->get();
            
        // Aylık hizmet ekleme trendi
        $monthlyData = DB::table('services')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
            
        // Hizmet durumlarına göre dağılım
        $serviceStatuses = DB::table('services')
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();
            
        // Toplam hizmet sayısı
        $totalServices = Service::count();
        
        // Bu ay eklenen hizmetler
        $thisMonthServices = Service::where('created_at', '>=', \Carbon\Carbon::now()->startOfMonth())->count();
        
        // Aktif hizmetler
        $activeServices = Service::where('status', 'aktif')->count();
        
        // Pasif hizmetler
        $inactiveServices = Service::count() - $activeServices;
        
        // Grafik için veri hazırla
        $labels = [];
        $values = [];
        
        for ($i = $period - 1; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subMonths($i);
            $labels[] = $date->isoFormat('MMM YY');
            
            $monthData = $monthlyData->where('month', $date->format('Y-m'))->first();
            $values[] = $monthData ? $monthData->count : 0;
        }
        
        return view('reports.services', compact(
            'period',
            'labels',
            'values',
            'serviceTypes',
            'serviceStatuses',
            'totalServices',
            'thisMonthServices',
            'activeServices',
            'inactiveServices'
        ));
    }
}

