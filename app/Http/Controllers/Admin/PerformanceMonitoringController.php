<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CustomerBalanceMonitoringService;
use App\Services\CustomerBalanceCacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PerformanceMonitoringController extends Controller
{
    protected CustomerBalanceMonitoringService $monitoringService;
    protected CustomerBalanceCacheService $cacheService;

    public function __construct(
        CustomerBalanceMonitoringService $monitoringService,
        CustomerBalanceCacheService $cacheService
    ) {
        $this->monitoringService = $monitoringService;
        $this->cacheService = $cacheService;
    }

    /**
     * Display performance monitoring dashboard
     */
    public function index()
    {
        $report = $this->monitoringService->generateReport();
        $cacheStats = $this->cacheService->getCacheStats();
        $alerts = $this->monitoringService->getCurrentAlerts();
        
        return view('admin.performance-monitoring.index', compact('report', 'cacheStats', 'alerts'));
    }

    /**
     * Get real-time performance metrics
     */
    public function getMetrics()
    {
        $metrics = $this->monitoringService->monitorPerformance();
        
        return response()->json($metrics);
    }

    /**
     * Get cache statistics
     */
    public function getCacheStats()
    {
        $stats = $this->cacheService->getCacheStats();
        
        return response()->json($stats);
    }

    /**
     * Warm up cache
     */
    public function warmUpCache(Request $request)
    {
        $limit = $request->input('limit', 100);
        
        try {
            $this->cacheService->warmUpCache($limit);
            
            return response()->json([
                'success' => true,
                'message' => "Cache warmed up for {$limit} customers"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cache warm-up failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear all caches
     */
    public function clearAllCaches()
    {
        try {
            $this->cacheService->clearAllCaches();
            
            return response()->json([
                'success' => true,
                'message' => 'All caches cleared successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cache clearing failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear specific customer cache
     */
    public function clearCustomerCache(Request $request)
    {
        $customerId = $request->input('customer_id');
        
        if (!$customerId) {
            return response()->json([
                'success' => false,
                'message' => 'Customer ID is required'
            ], 400);
        }
        
        try {
            $this->cacheService->clearCustomerCache($customerId);
            
            return response()->json([
                'success' => true,
                'message' => "Cache cleared for customer {$customerId}"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cache clearing failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get historical performance data
     */
    public function getHistoricalData(Request $request)
    {
        $hours = $request->input('hours', 24);
        $data = $this->monitoringService->getHistoricalData($hours);
        
        return response()->json($data);
    }

    /**
     * Clear performance alerts
     */
    public function clearAlerts()
    {
        $this->monitoringService->clearAlerts();
        
        return response()->json([
            'success' => true,
            'message' => 'Performance alerts cleared'
        ]);
    }

    /**
     * Export performance report
     */
    public function exportReport(Request $request)
    {
        $format = $request->input('format', 'json');
        $report = $this->monitoringService->generateReport();
        
        if ($format === 'csv') {
            return $this->exportCsv($report);
        }
        
        return response()->json($report);
    }

    /**
     * Export CSV format
     */
    private function exportCsv(array $report)
    {
        $filename = 'performance_report_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($report) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, ['Metric', 'Value']);
            
            // Current metrics
            fputcsv($file, ['', '']);
            fputcsv($file, ['Current Metrics', '']);
            foreach ($report['current_metrics'] as $key => $value) {
                if (is_array($value)) {
                    fputcsv($file, [$key, json_encode($value)]);
                } else {
                    fputcsv($file, [$key, $value]);
                }
            }
            
            // Historical averages
            fputcsv($file, ['', '']);
            fputcsv($file, ['Historical Averages', '']);
            foreach ($report['historical_averages'] as $key => $value) {
                fputcsv($file, [$key, $value]);
            }
            
            // Alerts
            fputcsv($file, ['', '']);
            fputcsv($file, ['Alerts', '']);
            foreach ($report['alerts'] as $alert) {
                fputcsv($file, ['Alert', $alert]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
