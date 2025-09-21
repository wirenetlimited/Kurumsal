<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportHubController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function quickRevenueCsv()
    {
        return response("date,invoice,total\n2025-08-01,INV-0001,1000.00\n", 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="revenue.csv"',
        ]);
    }

    public function quickServicesCsv()
    {
        return response("service_type,customer,sell_price\nhosting,ACME,499.00\n", 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="services.csv"',
        ]);
    }
}


