<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function revenue(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $startDate = $request->get('start_date', now()->startOfYear());
        $endDate = $request->get('end_date', now());

        $query = Invoice::where('status', 'paid')
            ->whereBetween('issue_date', [$startDate, $endDate]);

        switch ($period) {
            case 'daily':
                $revenue = $query->get()
                    ->groupBy(function($invoice) {
                        return $invoice->issue_date->format('Y-m-d');
                    })
                    ->map(function($group) {
                        return [
                            'date' => $group->first()->issue_date->format('Y-m-d'),
                            'total_revenue' => $group->sum('total'),
                            'invoice_count' => $group->count()
                        ];
                    })
                    ->values()
                    ->sortBy('date');
                break;
            
            case 'monthly':
                $revenue = $query->get()
                    ->groupBy(function($invoice) {
                        return $invoice->issue_date->format('Y-m');
                    })
                    ->map(function($group) {
                        return [
                            'month' => $group->first()->issue_date->format('Y-m'),
                            'total_revenue' => $group->sum('total'),
                            'invoice_count' => $group->count()
                        ];
                    })
                    ->values()
                    ->sortBy('month');
                break;
            
            case 'yearly':
                $revenue = $query->get()
                    ->groupBy(function($invoice) {
                        return $invoice->issue_date->format('Y');
                    })
                    ->map(function($group) {
                        return [
                            'year' => $group->first()->issue_date->format('Y'),
                            'total_revenue' => $group->sum('total'),
                            'invoice_count' => $group->count()
                        ];
                    })
                    ->values()
                    ->sortBy('year');
                break;
        }

        return view('reports.revenue', compact('revenue', 'period', 'startDate', 'endDate'));
    }

    public function services(Request $request)
    {
        $status = $request->get('status', 'all');
        $type = $request->get('type', 'all');

        $query = Service::with(['customer', 'provider']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($type !== 'all') {
            $query->where('service_type', $type);
        }

        $services = $query->paginate(20);

        // İstatistikler
        $stats = [
            'total' => Service::count(),
            'active' => Service::active()->count(),
            'expired' => Service::expired()->count(),
            'domains' => Service::where('service_type', 'domain')->count(),
            'hostings' => Service::where('service_type', 'hosting')->count(),
            'ssl' => Service::where('service_type', 'ssl')->count(),
        ];

        return view('reports.services', compact('services', 'stats', 'status', 'type'));
    }

    public function customers(Request $request)
    {
        $query = Customer::withCount(['services', 'invoices']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $customers = $query->paginate(20);

        // Müşteri istatistikleri
        $customerStats = [
            'total' => Customer::count(),
            'active' => Customer::where('is_active', true)->count(),
            'inactive' => Customer::where('is_active', false)->count(),
            'with_services' => Customer::has('services')->count(),
            'with_invoices' => Customer::has('invoices')->count(),
        ];

        return view('reports.customers', compact('customers', 'customerStats'));
    }

    public function providers()
    {
        $providers = Provider::withCount(['services'])
            ->withSum('services', 'sell_price')
            ->orderBy('services_count', 'desc')
            ->get();

        return view('reports.providers', compact('providers'));
    }

    public function export(Request $request)
    {
        $type = $request->get('type', 'revenue');
        $format = $request->get('format', 'csv');

        switch ($type) {
            case 'revenue':
                $data = Invoice::where('status', 'paid')
                    ->with('customer')
                    ->get();
                break;
            case 'services':
                $data = Service::with(['customer', 'provider'])->get();
                break;
            case 'customers':
                $data = Customer::withCount(['services', 'invoices'])->get();
                break;
        }

        // CSV export
        if ($format === 'csv') {
            $filename = $type . '_' . now()->format('Y-m-d') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($data, $type) {
                $file = fopen('php://output', 'w');
                
                // Headers
                if ($type === 'revenue') {
                    fputcsv($file, ['Fatura No', 'Müşteri', 'Tarih', 'Tutar', 'Durum']);
                    foreach ($data as $row) {
                        fputcsv($file, [
                            $row->id,
                            $row->customer->name,
                            $row->issue_date,
                            $row->total,
                            $row->status
                        ]);
                    }
                } elseif ($type === 'services') {
                    fputcsv($file, ['ID', 'Müşteri', 'Hizmet Türü', 'Durum', 'Başlangıç', 'Bitiş', 'Fiyat']);
                    foreach ($data as $row) {
                        fputcsv($file, [
                            $row->id,
                            $row->customer->name,
                            $row->service_type,
                            $row->status,
                            $row->start_date,
                            $row->end_date,
                            $row->sell_price
                        ]);
                    }
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return back()->with('error', 'Desteklenmeyen format');
    }
}
