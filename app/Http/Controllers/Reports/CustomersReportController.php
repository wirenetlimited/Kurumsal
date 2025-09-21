<?php
namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class CustomersReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 12);
        $startDate = now()->subMonths($period);
        
        // Müşteri istatistikleri
        $totalCustomers = Customer::count();
        $activeCustomers = Customer::where('is_active', true)->count();
        $thisMonthCustomers = Customer::whereMonth('created_at', now()->month)
                                    ->whereYear('created_at', now()->year)
                                    ->count();
        
        // Aylık müşteri verileri
        $values = [];
        $labels = [];
        
        for ($i = $period - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();
            
            $count = Customer::whereBetween('created_at', [$monthStart, $monthEnd])->count();
            $values[] = $count;
            $labels[] = $date->isoFormat('MMM YY');
        }
        
        // Müşteri türleri
        $customerTypes = Customer::select('customer_type', DB::raw('count(*) as count'))
                                ->groupBy('customer_type')
                                ->orderBy('count', 'desc')
                                ->get();
        
        return view('reports.customers', compact(
            'period',
            'totalCustomers',
            'activeCustomers',
            'thisMonthCustomers',
            'values',
            'labels',
            'customerTypes'
        ));
    }

    public function export($format)
    {
        $period = request('period', 12);
        $startDate = now()->subMonths($period);
        
        // Müşteri verilerini hazırla
        $monthlyData = [];
        $labels = [];
        
        for ($i = $period - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();
            
            $count = Customer::whereBetween('created_at', [$monthStart, $monthEnd])->count();
            $monthlyData[] = [
                'month' => $date->format('Y-m'),
                'month_name' => $date->translatedFormat('F Y'),
                'new_customers' => $count,
                'total_customers' => Customer::where('created_at', '<=', $monthEnd)->count()
            ];
            $labels[] = $date->isoFormat('MMM YY');
        }
        
        // Müşteri türleri
        $customerTypes = Customer::select('customer_type', DB::raw('count(*) as count'))
                                ->groupBy('customer_type')
                                ->orderBy('count', 'desc')
                                ->get();
        
        // Genel istatistikler
        $stats = [
            'total_customers' => Customer::count(),
            'active_customers' => Customer::where('is_active', true)->count(),
            'inactive_customers' => Customer::where('is_active', false)->count(),
            'this_month_customers' => Customer::whereMonth('created_at', now()->month)
                                            ->whereYear('created_at', now()->year)
                                            ->count(),
        ];
        
        if ($format === 'csv') {
            return $this->exportToCsv($monthlyData, $customerTypes, $stats);
        }
        
        if ($format === 'pdf') {
            return $this->exportToPdf($monthlyData, $customerTypes, $stats, $period);
        }
        
        return response('Desteklenmeyen format', 400);
    }

    /**
     * CSV export
     */
    private function exportToCsv(array $monthlyData, $customerTypes, array $stats): \Symfony\Component\HttpFoundation\Response
    {
        $filename = 'musteri_raporu_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($monthlyData, $customerTypes, $stats) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Genel istatistikler
            fputcsv($file, ['GENEL İSTATİSTİKLER']);
            fputcsv($file, ['Toplam Müşteri', $stats['total_customers']]);
            fputcsv($file, ['Aktif Müşteri', $stats['active_customers']]);
            fputcsv($file, ['Pasif Müşteri', $stats['inactive_customers']]);
            fputcsv($file, ['Bu Ay Yeni Müşteri', $stats['this_month_customers']]);
            fputcsv($file, []);
            
            // Aylık veriler
            fputcsv($file, ['AYLIK MÜŞTERİ VERİLERİ']);
            fputcsv($file, ['Ay', 'Yeni Müşteri', 'Toplam Müşteri']);
            
            foreach ($monthlyData as $row) {
                fputcsv($file, [
                    $row['month_name'],
                    $row['new_customers'],
                    $row['total_customers']
                ]);
            }
            
            fputcsv($file, []);
            
            // Müşteri türleri
            fputcsv($file, ['MÜŞTERİ TÜRLERİ']);
            fputcsv($file, ['Tür', 'Sayı']);
            
            foreach ($customerTypes as $type) {
                fputcsv($file, [
                    $type->customer_type ?? 'Belirtilmemiş',
                    $type->count
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * PDF export
     */
    private function exportToPdf(array $monthlyData, $customerTypes, array $stats, int $period): \Symfony\Component\HttpFoundation\Response
    {
        $filename = 'musteri_raporu_' . now()->format('Y-m-d_H-i-s') . '.pdf';
        
        $pdf = PDF::loadView('reports.exports.customers_pdf', [
            'monthlyData' => $monthlyData,
            'customerTypes' => $customerTypes,
            'stats' => $stats,
            'period' => $period,
            'startDate' => now()->subMonths($period),
            'endDate' => now(),
        ]);
        
        // PDF ayarları
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'DejaVu Sans',
            'chroot' => public_path(),
            'defaultMediaType' => 'screen',
            'defaultPaperSize' => 'a4',
            'defaultPaperOrientation' => 'portrait',
            'dpi' => 150,
            'fontHeightRatio' => 0.9,
            'enableFontSubsetting' => true,
            'isFontSubsettingEnabled' => true,
        ]);
        
        return $pdf->download($filename);
    }
}

