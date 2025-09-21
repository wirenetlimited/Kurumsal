<?php
namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\RevenueCacheService;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class RevenueReportController extends Controller
{
    public function __construct(private RevenueCacheService $revenueCache) {}

    public function index()
    {
        return view('reports.revenue');
    }

    public function export($format)
    {
        $period = request('period', 12);
        
        // Cache'den verileri al
        $revenueData = $this->revenueCache->getMonthlyRevenueData($period);
        $data = $revenueData['data'];
        
        if ($format === 'csv') {
            return $this->exportToCsv($data);
        }
        
        if ($format === 'pdf') {
            return $this->exportToPdf($data, $period);
        }
        
        return response('Desteklenmeyen format', 400);
    }

    /**
     * CSV export
     */
    private function exportToCsv(array $data): \Symfony\Component\HttpFoundation\Response
    {
        $filename = 'gelir_raporu_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($file, ['Ay', 'Fatura Edilen', 'Ödenen', 'Kalan']);
            
            // Data
            foreach ($data as $row) {
                fputcsv($file, [
                    $row['month_name'],
                    $row['issued'],
                    $row['paid'],
                    $row['remaining']
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * PDF export
     */
    private function exportToPdf(array $data, int $period): \Symfony\Component\HttpFoundation\Response
    {
        $filename = 'gelir_raporu_' . now()->format('Y-m-d_H-i-s') . '.pdf';
        
        // Toplam istatistikler
        $totals = [
            'total_issued' => collect($data)->sum('issued'),
            'total_paid' => collect($data)->sum('paid'),
            'total_remaining' => collect($data)->sum('remaining'),
            'avg_issued' => collect($data)->avg('issued'),
            'avg_paid' => collect($data)->avg('paid'),
        ];
        
        $pdf = PDF::loadView('reports.exports.revenue_pdf', [
            'data' => $data,
            'totals' => $totals,
            'period' => $period,
            'startDate' => Carbon::now()->subMonths($period - 1)->startOfMonth(),
            'endDate' => Carbon::now()->endOfMonth(),
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

