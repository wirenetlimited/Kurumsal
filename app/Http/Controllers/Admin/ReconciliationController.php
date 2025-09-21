<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReconciliationService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class ReconciliationController extends Controller
{
    protected ReconciliationService $reconciliationService;

    public function __construct(ReconciliationService $reconciliationService)
    {
        $this->reconciliationService = $reconciliationService;
    }

    /**
     * Muhasebe mutabakatı ana sayfası
     */
    public function index(Request $request): View
    {
        // Cache süresi (30 dakika)
        $cacheKey = 'reconciliation_data';
        $cacheDuration = 1800;

        // Cache'den veri al veya yeni hesapla
        $data = cache()->remember($cacheKey, $cacheDuration, function () {
            return [
                'invoice_check' => $this->reconciliationService->checkInvoices(),
                'customer_balances' => $this->reconciliationService->checkCustomerBalances(),
                'summary' => $this->reconciliationService->getSummary()
            ];
        });

        // Filtreleme seçenekleri
        $filters = [
            'status' => $request->get('status', 'all'),
            'category' => $request->get('category', 'all'),
            'customer' => $request->get('customer', 'all')
        ];

        // Filtreleme uygula - Collection filtering optimize edildi
        if ($filters['status'] !== 'all' || $filters['category'] !== 'all' || $filters['customer'] !== 'all') {
            $filteredData = $data;
            
            if ($filters['status'] !== 'all') {
                $filteredData['invoice_check']['errors'] = $data['invoice_check']['errors']->filter(fn($item) => $item['status'] === $filters['status']);
                $filteredData['invoice_check']['warnings'] = $data['invoice_check']['warnings']->filter(fn($item) => $item['status'] === $filters['status']);
                $filteredData['invoice_check']['ok'] = $data['invoice_check']['ok']->filter(fn($item) => $item['status'] === $filters['status']);
            }
            
            if ($filters['category'] !== 'all') {
                $filteredData['invoice_check']['errors'] = $filteredData['invoice_check']['errors']->filter(fn($item) => $item['category'] === $filters['category']);
                $filteredData['invoice_check']['warnings'] = $filteredData['invoice_check']['warnings']->filter(fn($item) => $item['category'] === $filters['category']);
                $filteredData['invoice_check']['ok'] = $filteredData['invoice_check']['ok']->filter(fn($item) => $item['category'] === $filters['category']);
            }
            
            if ($filters['customer'] !== 'all') {
                $filteredData['invoice_check']['errors'] = $filteredData['invoice_check']['errors']->filter(fn($item) => $item['customer_id'] == $filters['customer']);
                $filteredData['invoice_check']['warnings'] = $filteredData['invoice_check']['warnings']->filter(fn($item) => $item['customer_id'] == $filters['customer']);
                $filteredData['invoice_check']['ok'] = $filteredData['invoice_check']['ok']->filter(fn($item) => $item['customer_id'] == $filters['customer']);
            }
            
            $data = $filteredData;
        }

        // Müşteri listesi (filtre için) - Cache ile optimize edildi
        $customers = cache()->remember('reconciliation_customers', 3600, function () {
            return \App\Models\Customer::select('id', 'name')->orderBy('name')->get();
        });

        return view('admin.reconciliation.index', compact('data', 'filters', 'customers'));
    }

    /**
     * Fatura durumlarını senkronize et
     */
    public function syncStatuses(): \Illuminate\Http\RedirectResponse
    {
        try {
            $result = $this->reconciliationService->syncInvoiceStatuses();
            
            if ($result['success']) {
                return redirect()->route('admin.reconciliation.index')
                    ->with('status', $result['message']);
            } else {
                return redirect()->route('admin.reconciliation.index')
                    ->with('error', $result['message']);
            }
        } catch (\Exception $e) {
            \Log::error('Invoice status sync failed in controller', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.reconciliation.index')
                ->with('error', 'Durum güncelleme sırasında hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Mutabakat raporunu export et
     */
    public function export(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        $format = $request->get('format', 'csv');
        
        $data = [
            'invoice_check' => $this->reconciliationService->checkInvoices(),
            'customer_balances' => $this->reconciliationService->checkCustomerBalances(),
            'summary' => $this->reconciliationService->getSummary()
        ];

        if ($format === 'csv') {
            return $this->exportToCsv($data);
        }
        
        if ($format === 'pdf') {
            return $this->exportToPdf($data);
        }

        return $this->exportToJson($data);
    }

    /**
     * CSV export
     */
    private function exportToCsv(array $data): \Symfony\Component\HttpFoundation\Response
    {
        $filename = 'mutabakat_raporu_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($file, [
                'Tip', 'Fatura ID', 'Müşteri', 'Durum', 'Beklenen', 'Gerçek', 'Fark', 'Açıklama'
            ]);

            // Errors
            foreach ($data['invoice_check']['errors'] as $item) {
                fputcsv($file, [
                    'HATA',
                    $item['invoice_number'],
                    $item['customer_name'],
                    $item['status'],
                    $item['expected'],
                    $item['actual'],
                    $item['difference'],
                    $item['description']
                ]);
            }

            // Warnings
            foreach ($data['invoice_check']['warnings'] as $item) {
                fputcsv($file, [
                    'UYARI',
                    $item['invoice_number'],
                    $item['customer_name'],
                    $item['status'],
                    $item['expected'],
                    $item['actual'],
                    $item['difference'],
                    $item['description']
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * PDF export
     */
    private function exportToPdf(array $data): \Symfony\Component\HttpFoundation\Response
    {
        $filename = 'mutabakat_raporu_' . now()->format('Y-m-d_H-i-s') . '.pdf';
        
        $pdf = PDF::loadView('admin.reconciliation.pdf', [
            'data' => $data,
            'generatedAt' => now(),
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

    /**
     * JSON export
     */
    private function exportToJson(array $data): \Symfony\Component\HttpFoundation\Response
    {
        $filename = 'mutabakat_raporu_' . now()->format('Y-m-d_H-i-s') . '.json';
        
        return response()->json($data, 200, [
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}


