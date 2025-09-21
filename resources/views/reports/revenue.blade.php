<x-app-layout>
@php
$period = request('period', 12); // Varsayılan 12 ay

// Cache servisini kullan
$revenueCache = app(\App\Services\RevenueCacheService::class);

// Cache'den verileri al
$revenueData = $revenueCache->getMonthlyRevenueData($period);
$totalRevenueStats = $revenueCache->getTotalRevenueStats();
$thisMonthRevenue = $revenueCache->getThisMonthRevenue();

// Verileri ayır
$labels = $revenueData['labels'];
$issuedValues = $revenueData['issued_values'];
$paidValues = $revenueData['paid_values'];
$monthlyInvoices = $revenueData['monthly_invoices'];
$monthlyPayments = $revenueData['monthly_payments'];

// Bu ay verileri
$kesilen = $thisMonthRevenue['issued'];
$tahsil = $thisMonthRevenue['collected'];
$kalan = $thisMonthRevenue['remaining'];

// Toplam gelir istatistikleri
$totalRevenue = $totalRevenueStats['total_revenue'];
$avgMonthlyRevenue = $totalRevenueStats['avg_monthly_revenue'];
$maxMonthlyRevenue = $totalRevenueStats['max_monthly_revenue'];
@endphp

<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto p-6 space-y-8">
        <!-- Başlık ve Navigasyon -->
        <div class="flex items-center justify-between">
            <div class="space-y-2">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Gelir Analizi</h1>
                        <p class="text-gray-600 dark:text-gray-400">İşletmenizin gelir performansını detaylı analiz edin</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <!-- Export Dropdown -->
                <div class="relative">
                    <button onclick="toggleExportMenu()" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export
                    </button>
                    
                    <div id="exportMenu" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-lg shadow-lg border border-gray-200 dark:border-gray-600 z-10">
                        <a href="{{ route('reports.export.revenue', ['format' => 'csv', 'period' => $period]) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-t-lg">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            CSV İndir
                        </a>
                        <a href="{{ route('reports.export.revenue', ['format' => 'pdf', 'period' => $period]) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-b-lg">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            PDF İndir
                        </a>
                    </div>
                </div>
                
                <a href="{{ route('reports.index') }}" class="inline-flex items-center px-6 py-3 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Geri Dön
                </a>
            </div>
        </div>

        <!-- Genel Açıklama -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl p-6 border border-blue-200 dark:border-blue-700">
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-2">Gelir Analizi Nasıl Çalışır?</h4>
                    <div class="text-sm text-blue-800 dark:text-blue-200 space-y-1">
                        <p><strong>📊 Kesilen:</strong> O ay kesilen faturaların toplam tutarı (fatura kesim tarihi baz alınır)</p>
                        <p><strong>💰 Tahsilat:</strong> O ay kesilen faturaların tahsil edilen kısmı (sadece o ay kesilen faturaların ödemeleri)</p>
                        <p><strong>⏳ Bekleyen:</strong> O ay kesilen faturaların henüz tahsil edilmeyen kısmı</p>
                        <p><strong>📈 Toplam Gelir:</strong> Tüm ödenmiş faturaların toplamı (tüm zamanlar)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dönem Seçici -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Analiz Dönemi</h3>
                <div class="flex space-x-2">
                    @foreach([1, 3, 6, 12] as $p)
                    <a href="?period={{ $p }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ $period == $p ? 'bg-blue-600 text-white shadow-lg' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        {{ $p }} {{ $p == 1 ? 'Ay' : 'Ay' }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Özet Kartları -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Kesilen Card -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-900/10 rounded-xl p-6 border border-green-200 dark:border-green-700 hover:shadow-lg transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900/30 px-3 py-1 rounded-full">Bu Ay</span>
                        <div class="relative group">
                            <svg class="w-4 h-4 text-green-500 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="absolute bottom-full right-0 mb-2 w-64 p-3 bg-gray-900 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-10">
                                Bu ay kesilen toplam fatura tutarı. Fatura kesim tarihi baz alınır.
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Kesilen</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">₺{{ number_format($kesilen,2,',','.') }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Fatura kesim tutarı</p>
                </div>
            </div>
            
            <!-- Tahsil Edilen Card -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-900/10 rounded-xl p-6 border border-blue-200 dark:border-blue-700 hover:shadow-lg transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-900/30 px-3 py-1 rounded-full">Bu Ay</span>
                        <div class="relative group">
                            <svg class="w-4 h-4 text-blue-500 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="absolute bottom-full right-0 mb-2 w-64 p-3 bg-gray-900 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-10">
                                Bu ay kesilen faturaların tahsil edilen kısmı. Sadece bu ay kesilen faturaların ödemeleri sayılır.
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Tahsil Edilen</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">₺{{ number_format($tahsil,2,',','.') }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Bu ay faturalarının tahsilatı</p>
                </div>
            </div>
            
            <!-- Bekleyen Card -->
            <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-900/10 rounded-xl p-6 border border-orange-200 dark:border-orange-700 hover:shadow-lg transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium text-orange-600 dark:text-orange-400 bg-orange-100 dark:bg-orange-900/30 px-3 py-1 rounded-full">Kalan</span>
                        <div class="relative group">
                            <svg class="w-4 h-4 text-orange-500 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="absolute bottom-full right-0 mb-2 w-64 p-3 bg-gray-900 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-10">
                                Bu ay kesilen faturaların henüz tahsil edilmeyen kısmı. Kesilen - Tahsilat = Bekleyen
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Bekleyen</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">₺{{ number_format($kalan,2,',','.') }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Tahsil edilmeyen tutar</p>
                </div>
            </div>
            
            <!-- Aylık Gelir Card -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-900/10 rounded-xl p-6 border border-purple-200 dark:border-purple-700 hover:shadow-lg transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium text-purple-600 dark:text-purple-400 bg-purple-100 dark:bg-purple-900/30 px-3 py-1 rounded-full">Ortalama</span>
                        <div class="relative group">
                            <svg class="w-4 h-4 text-purple-500 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="absolute bottom-full right-0 mb-2 w-64 p-3 bg-gray-900 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-10">
                                Tüm ödenmiş faturaların toplamının 12 aya bölünmüş hali. Genel gelir performansını gösterir.
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Aylık Gelir</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">₺{{ number_format($avgMonthlyRevenue,2,',','.') }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Ortalama aylık gelir</p>
                </div>
            </div>
        </div>

        <!-- Ana Grafik -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Gelir Trendi (Son {{ $period }} Ay)</h3>
                    <p class="text-gray-600 dark:text-gray-400">Fatura kesim ve tahsilat değişimini görsel olarak analiz edin</p>
                    <div class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <p class="text-xs text-blue-700 dark:text-blue-300">
                            <strong>💡 Nasıl Okunur:</strong> Yeşil çizgi (Kesilen) o ay kesilen faturaları, mavi çizgi (Tahsilat) o ay kesilen faturaların tahsilatını gösterir. 
                            Tahsilat çizgisinin kesilen çizginin altında olması normaldir çünkü faturalar genellikle aynı ay içinde tamamen tahsil edilmez.
                            <strong>En Yüksek değerler:</strong> Her çizginin ulaştığı en yüksek noktayı gösterir.
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Kesilen</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-4 h-4 bg-blue-500 rounded-full"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Tahsilat</span>
                    </div>
                    <div class="flex flex-col text-sm text-gray-500 dark:text-gray-400">
                        <div>En Yüksek Kesilen: ₺{{ number_format(max($issuedValues),2,',','.') }}</div>
                        <div>En Yüksek Tahsilat: ₺{{ number_format($maxMonthlyRevenue,2,',','.') }}</div>
                    </div>
                </div>
            </div>
            <div class="relative" style="height: 400px;">
                <canvas id="revLine"></canvas>
            </div>
        </div>

        <!-- Detay Tablosu -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Aylık Detaylar</h3>
                <div class="relative group">
                    <svg class="w-5 h-5 text-gray-500 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="absolute bottom-full right-0 mb-2 w-80 p-3 bg-gray-900 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-10">
                        <strong>Kesilen:</strong> O ay kesilen fatura tutarı<br>
                        <strong>Tahsilat:</strong> O ay kesilen faturaların tahsilatı<br>
                        <strong>Fark:</strong> Kesilen - Tahsilat (negatif = fazla tahsilat)
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-600 dark:text-gray-400">Dönem</th>
                            <th class="text-right py-3 px-4 text-sm font-medium text-gray-600 dark:text-gray-400">Kesilen</th>
                            <th class="text-right py-3 px-4 text-sm font-medium text-gray-600 dark:text-gray-400">Tahsilat</th>
                            <th class="text-right py-3 px-4 text-sm font-medium text-gray-600 dark:text-gray-400">Fark</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($labels as $index => $label)
                        @php
                            $issuedValue = $issuedValues[$index];
                            $paidValue = $paidValues[$index];
                            $difference = $issuedValue - $paidValue;
                        @endphp
                        <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="py-3 px-4 text-sm font-medium text-gray-900 dark:text-white">{{ $label }}</td>
                            <td class="py-3 px-4 text-sm text-right text-gray-900 dark:text-white">₺{{ number_format($issuedValue,2,',','.') }}</td>
                            <td class="py-3 px-4 text-sm text-right text-blue-600 dark:text-blue-400">₺{{ number_format($paidValue,2,',','.') }}</td>
                            <td class="py-3 px-4 text-sm text-right">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $difference <= 0 ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400' }}">
                                    {{ $difference >= 0 ? '+' : '' }}₺{{ number_format($difference,2,',','.') }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/chart.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Chart === 'undefined') {
        console.error('Chart.js yüklenemedi');
        document.getElementById('revLine').parentNode.innerHTML = '<div class="h-full flex items-center justify-center text-gray-500">Chart.js yüklenemedi</div>';
        return;
    }

    const revCtx = document.getElementById('revLine');
    if (!revCtx) {
        console.error('Canvas element bulunamadı');
        return;
    }

    try {
        if (window.revenueChart) {
            window.revenueChart.destroy();
        }

        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? '#374151' : '#e5e7eb';
        const textColor = isDark ? '#f3f4f6' : '#374151';

        window.revenueChart = new Chart(revCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [
                    {
                        label: 'Kesilen Faturalar (₺)',
                        data: {!! json_encode($issuedValues) !!},
                        tension: 0.4,
                        fill: false,
                        borderWidth: 3,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        pointBackgroundColor: 'rgb(34, 197, 94)',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 3,
                        pointHoverBackgroundColor: 'rgb(34, 197, 94)',
                        pointHoverBorderColor: '#ffffff'
                    },
                    {
                        label: 'Tahsilat (₺)',
                        data: {!! json_encode($paidValues) !!},
                        tension: 0.4,
                        fill: false,
                        borderWidth: 3,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 3,
                        pointHoverBackgroundColor: 'rgb(59, 130, 246)',
                        pointHoverBorderColor: '#ffffff'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: isDark ? '#1f2937' : '#ffffff',
                        titleColor: textColor,
                        bodyColor: textColor,
                        borderColor: gridColor,
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                const label = context.dataset.label || '';
                                return label + ': ₺' + context.parsed.y.toLocaleString('tr-TR');
                            }
                        }
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        grid: {
                            color: gridColor,
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                return '₺' + value.toLocaleString('tr-TR');
                            },
                            color: textColor,
                            font: {
                                size: 12
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: gridColor,
                            drawBorder: false
                        },
                        ticks: {
                            color: textColor,
                            font: {
                                size: 12
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                elements: {
                    point: {
                        hoverBackgroundColor: 'rgb(59, 130, 246)',
                        hoverBorderColor: '#ffffff'
                    }
                }
            }
        });

        console.log('Gelir grafiği başarıyla oluşturuldu');
    } catch (error) {
        console.error('Grafik oluşturulurken hata:', error);
        document.getElementById('revLine').parentNode.innerHTML = '<div class="h-full flex items-center justify-center text-red-500">Grafik yüklenirken hata oluştu</div>';
    }
});

// Export menüsü toggle fonksiyonu
function toggleExportMenu() {
    const menu = document.getElementById('exportMenu');
    menu.classList.toggle('hidden');
}

// Export menüsünü dışarı tıklandığında kapat
document.addEventListener('click', function(event) {
    const exportMenu = document.getElementById('exportMenu');
    const exportButton = event.target.closest('button');
    
    if (!exportButton || !exportButton.onclick) {
        exportMenu.classList.add('hidden');
    }
});
</script>
</x-app-layout>
