<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route as FacadesRoute;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () { return redirect()->route('dashboard'); });

// Changelog route
Route::get('/changelog', function() {
    $changelogContent = file_get_contents(base_path('CHANGELOG.md'));
    return view('changelog', compact('changelogContent'));
})->name('changelog');

Route::get('/dashboard', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth','demo_readonly'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/test-theme', function() {
        return view('test-theme');
    })->name('test-theme');
    
    Route::post('/profile/theme', function(\Illuminate\Http\Request $request) {
        try {
            $request->validate([
                'theme' => ['required','in:light,dark'],
                'theme_color' => ['nullable','in:blue,green,purple,orange'],
            ]);
            
            $user = $request->user();
            $oldTheme = $user->theme;
            
            $user->theme = $request->string('theme');
            if ($request->filled('theme_color')) {
                $user->theme_color = $request->string('theme_color');
            }
            $user->save();
            
            // Log theme change for debugging
            \Log::info('Theme updated', [
                'user_id' => $user->id,
                'old_theme' => $oldTheme,
                'new_theme' => $user->theme,
                'theme_color' => $user->theme_color
            ]);
            
            // Return JSON response for AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tema güncellendi',
                    'theme' => $user->theme,
                    'theme_color' => $user->theme_color,
                    'old_theme' => $oldTheme
                ]);
            }
            
            return back()->with('status','Tema güncellendi');
            
        } catch (\Exception $e) {
            \Log::error('Theme update failed', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id ?? 'unknown',
                'request_data' => $request->all()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tema güncellenirken hata oluştu: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->withErrors(['theme' => 'Tema güncellenirken hata oluştu']);
        }
    })->name('profile.theme');
    
    Route::post('/profile/dashboard-title', function(\Illuminate\Http\Request $request) {
        $request->validate([
            'dashboard_title' => ['required','string','max:255'],
        ]);
        $user = $request->user();
        $user->dashboard_title = $request->string('dashboard_title');
        $user->save();
        return back()->with('status','dashboard-title-updated');
    })->name('profile.dashboard-title');
    Route::resource('customers', \App\Http\Controllers\CustomerController::class);
    Route::resource('providers', \App\Http\Controllers\ProviderController::class)->except(['show']);
    Route::resource('services', \App\Http\Controllers\ServiceController::class);
    
    // Service specific routes
    Route::post('services/{service}/create-invoice', [\App\Http\Controllers\ServiceController::class, 'createInvoice'])->name('services.create-invoice');
    Route::post('services/{service}/send-reminder', [\App\Http\Controllers\ServiceController::class, 'sendReminderEmail'])->name('services.send-reminder');
    Route::post('services/{service}/add-reminder', [\App\Http\Controllers\ServiceController::class, 'addReminder'])->name('services.add-reminder');
    
    Route::resource('invoices', \App\Http\Controllers\InvoiceController::class);
    Route::post('invoices/{invoice}/send-email', [\App\Http\Controllers\InvoiceController::class, 'sendEmail'])->name('invoices.send-email');
    Route::resource('quotes', \App\Http\Controllers\QuoteController::class);

// API route for customer info
Route::get('/api/customers/{customer}', function(\App\Models\Customer $customer) {
    return response()->json([
        'success' => true,
        'customer' => $customer
    ]);
})->middleware(['auth']);
Route::get('quotes/{quote}/pdf', [\App\Http\Controllers\QuoteController::class, 'pdf'])->name('quotes.pdf');
Route::post('quotes/{quote}/send', [\App\Http\Controllers\QuoteController::class, 'sendEmail'])->name('quotes.send');
Route::post('quotes/{quote}/to-invoice', [\App\Http\Controllers\QuoteController::class, 'toInvoice'])->name('quotes.to_invoice');
Route::post('quotes/{quote}/accept', [\App\Http\Controllers\QuoteController::class, 'accept'])->name('quotes.accept');
    Route::post('customers/{customer}/send-welcome-email', [\App\Http\Controllers\CustomerController::class, 'sendWelcomeEmail'])->name('customers.send-welcome-email');
    // Customer statement exports
    Route::get('customers/{customer}/statement.csv', function(\App\Models\Customer $customer) {
        $rows = $customer->ledgerEntries()->orderBy('entry_date')->orderBy('id')->get(['entry_date','debit','credit','notes','related_type','related_id']);
        $running = 0.0;
        $csv = fopen('php://output','w');
        ob_start();
        fputcsv($csv, ['Tarih','Açıklama','Borç','Alacak','Bakiye']);
        foreach ($rows as $r) {
            $running += (float)$r->debit - (float)$r->credit;
            fputcsv($csv, [\Carbon\Carbon::parse($r->entry_date)->format('d.m.Y'), $r->notes ?? class_basename($r->related_type).' #'.$r->related_id, number_format($r->debit,2,',','.'), number_format($r->credit,2,',','.'), number_format($running,2,',','.')]);
        }
        $content = ob_get_clean();
        return response($content, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="cari_'.($customer->id).'.csv"'
        ]);
    })->name('customers.statement.csv');

    Route::get('customers/{customer}/statement.pdf', function(\App\Models\Customer $customer) {
        $rows = $customer->ledgerEntries()->orderBy('entry_date')->orderBy('id')->get(['entry_date','debit','credit','notes','related_type','related_id']);
        $running = 0.0;
        $data = [];
        foreach ($rows as $r) {
            $running += (float)$r->debit - (float)$r->credit;
            $data[] = [
                'date' => \Carbon\Carbon::parse($r->entry_date)->format('d.m.Y'),
                'desc' => $r->notes ?? class_basename($r->related_type).' #'.$r->related_id,
                'debit' => number_format($r->debit,2,',','.'),
                'credit' => number_format($r->credit,2,',','.'),
                'balance' => number_format($running,2,',','.'),
            ];
        }
        $html = view('pdf.statement', ['customer'=>$customer,'rows'=>$data,'balance'=>$running])->render();
        $dompdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
        return $dompdf->download('cari_'.$customer->id.'.pdf');
    })->name('customers.statement.pdf');
    
    // E-posta Şablonları Routes
    Route::prefix('email-templates')->name('email-templates.')->group(function () {
        Route::get('/', [\App\Http\Controllers\EmailTemplateController::class, 'index'])->name('index');
        Route::get('/welcome', [\App\Http\Controllers\EmailTemplateController::class, 'welcome'])->name('welcome');
        Route::get('/invoice', [\App\Http\Controllers\EmailTemplateController::class, 'invoice'])->name('invoice');
        Route::get('/quote', [\App\Http\Controllers\EmailTemplateController::class, 'quote'])->name('quote');
        Route::get('/service-expiry', [\App\Http\Controllers\EmailTemplateController::class, 'serviceExpiry'])->name('service-expiry');
        Route::post('/send-test', [\App\Http\Controllers\EmailTemplateController::class, 'sendTest'])->name('send-test');
    });

    // Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'is_admin', 'demo_readonly'])->group(function () {
    Route::prefix('email-settings')->name('email-settings.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\EmailSettingsController::class, 'index'])->name('index');
        Route::post('/update-smtp', [\App\Http\Controllers\Admin\EmailSettingsController::class, 'updateSmtp'])->name('update-smtp');
        Route::post('/send-test', [\App\Http\Controllers\Admin\EmailSettingsController::class, 'sendTestEmail'])->name('send-test');
    });
    
    Route::prefix('security-settings')->name('security-settings.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\SecuritySettingsController::class, 'index'])->name('index');
        Route::post('/update', [\App\Http\Controllers\Admin\SecuritySettingsController::class, 'update'])->name('update');
        Route::get('/test', [\App\Http\Controllers\Admin\SecuritySettingsController::class, 'testSecurity'])->name('test');
        Route::get('/recommendations', [\App\Http\Controllers\Admin\SecuritySettingsController::class, 'getRecommendations'])->name('recommendations');
    });
    
    Route::prefix('site-settings')->name('site-settings.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\SiteSettingsController::class, 'index'])->name('index');
        Route::post('/update', [\App\Http\Controllers\Admin\SiteSettingsController::class, 'update'])->name('update');
        Route::post('/clear-cache', [\App\Http\Controllers\Admin\SiteSettingsController::class, 'clearCache'])->name('clear-cache');
        Route::get('/logs', [\App\Http\Controllers\Admin\SiteSettingsController::class, 'viewLogs'])->name('logs');
        Route::post('/clear-logs', [\App\Http\Controllers\Admin\SiteSettingsController::class, 'clearLogs'])->name('clear-logs');
    });
    
    // Hizmet Türleri Yönetimi
    Route::prefix('service-types')->name('service-types.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ServiceTypesController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Admin\ServiceTypesController::class, 'store'])->name('store');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\ServiceTypesController::class, 'destroy'])->name('destroy');
    });
    
    // Servis Durumları API
    Route::prefix('service-statuses')->name('service-statuses.')->group(function () {
        Route::get('/', function () {
            return response()->json([
                'service_statuses' => \App\Enums\ServiceStatus::getAllWithInfo(),
                'invoice_statuses' => \App\Enums\InvoiceStatus::getAllWithInfo(),
            ]);
        })->name('index');
    });
    
    // Muhasebe Mutabakatı
    Route::prefix('reconciliation')->name('reconciliation.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ReconciliationController::class, 'index'])->name('index');
        Route::post('/sync-statuses', [\App\Http\Controllers\Admin\ReconciliationController::class, 'syncStatuses'])->name('sync-statuses');
        Route::get('/export', [\App\Http\Controllers\Admin\ReconciliationController::class, 'export'])->name('export');
    });
    
    // Ödemeler Yönetimi
    Route::resource('payments', \App\Http\Controllers\Admin\PaymentsController::class);
    Route::get('invoices/{invoice}/payments/context', [\App\Http\Controllers\Admin\PaymentsController::class, 'context'])->name('payments.context');
    Route::get('payments/customer-invoices/{customer}', [\App\Http\Controllers\Admin\PaymentsController::class, 'customerInvoices'])->name('payments.customer-invoices');

    // Manuel Cari (Ledger) Kayıtları
    Route::post('customers/{customer}/ledger-entries', [\App\Http\Controllers\Admin\CustomerLedgerController::class, 'store'])->name('customers.ledger.store');
    Route::delete('ledger-entries/{entry}', [\App\Http\Controllers\Admin\CustomerLedgerController::class, 'destroy'])->name('ledger.destroy');

    
    // Müşteri Bakiyeleri Raporu
    Route::get('customer-balances', [\App\Http\Controllers\Admin\BalanceController::class, 'index'])->name('customer-balances');
    

});
    
    // Raporlama Routes
    Route::middleware(['auth', 'is_admin'])->group(function () {
        Route::get('/reports', [\App\Http\Controllers\ReportHubController::class, 'index'])->name('reports.index');
        Route::get('/reports/revenue', [\App\Http\Controllers\Reports\RevenueReportController::class, 'index'])->name('reports.revenue');
        Route::get('/reports/services', [\App\Http\Controllers\Reports\ServicesReportController::class, 'index'])->name('reports.services');
        Route::get('/reports/customers', [\App\Http\Controllers\Reports\CustomersReportController::class, 'index'])->name('reports.customers');
        Route::get('/reports/providers', [\App\Http\Controllers\Reports\ProvidersReportController::class, 'index'])->name('reports.providers');
        Route::get('/reports/export/revenue/{format}', [\App\Http\Controllers\Reports\RevenueReportController::class, 'export'])->name('reports.export.revenue');
        Route::get('/reports/export/customers/{format}', [\App\Http\Controllers\Reports\CustomersReportController::class, 'export'])->name('reports.export.customers');
        Route::get('/reports/quick/csv/revenue', [\App\Http\Controllers\ReportHubController::class, 'quickRevenueCsv'])->name('reports.quick.revenue.csv');
        Route::get('/reports/quick/csv/services', [\App\Http\Controllers\ReportHubController::class, 'quickServicesCsv'])->name('reports.quick.services.csv');
    });
});

require __DIR__.'/auth.php';

// Installation Routes
Route::prefix('install')->name('install.')->group(function () {
    Route::get('/', [\App\Http\Controllers\InstallController::class, 'index'])->name('index');
    Route::get('/database', [\App\Http\Controllers\InstallController::class, 'database'])->name('database');
    Route::post('/database', [\App\Http\Controllers\InstallController::class, 'testDatabase'])->name('database');
    Route::get('/migrate', [\App\Http\Controllers\InstallController::class, 'migrate'])->name('migrate');
    Route::get('/admin', [\App\Http\Controllers\InstallController::class, 'admin'])->name('admin');
    Route::post('/admin', [\App\Http\Controllers\InstallController::class, 'createAdmin'])->name('createAdmin');
    
    // Geri dönüş rotaları
    Route::get('/back-to-index', [\App\Http\Controllers\InstallController::class, 'backToIndex'])->name('back-to-index');
    Route::get('/back-to-database', [\App\Http\Controllers\InstallController::class, 'backToDatabase'])->name('back-to-database');
    
    // Kurulum sıfırlama
    Route::get('/reset', [\App\Http\Controllers\InstallController::class, 'reset'])->name('reset');
});

// Health Check Route
Route::get('/health', function () {
    return response('ok', 200);
});


