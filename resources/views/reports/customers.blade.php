<x-app-layout>
@php
    // Grafik için veri hazırla
    $maxMonthlyCustomers = max($values);
    $avgMonthlyCustomers = $values ? round(array_sum($values) / count($values), 1) : 0;
@endphp

<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto p-6 space-y-8">
        <!-- Başlık ve Navigasyon -->
        <div class="flex items-center justify-between">
            <div class="space-y-2">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Müşteri Analizi</h1>
                        <p class="text-gray-600 dark:text-gray-400">Müşteri segmentasyonu ve hizmet kullanım oranlarını analiz edin</p>
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
                        <a href="{{ route('reports.export.customers', ['format' => 'csv', 'period' => $period]) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-t-lg">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            CSV İndir
                        </a>
                        <a href="{{ route('reports.export.customers', ['format' => 'pdf', 'period' => $period]) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-b-lg">
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
            <!-- Toplam Müşteri Card -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-900/10 rounded-xl p-6 border border-blue-200 dark:border-blue-700 hover:shadow-lg transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-900/30 px-3 py-1 rounded-full">Toplam</span>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Müşteri</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalCustomers) }}</p>
                </div>
            </div>
            
            <!-- Aktif Müşteri Card -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-900/10 rounded-xl p-6 border border-green-200 dark:border-green-700 hover:shadow-lg transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900/30 px-3 py-1 rounded-full">Aktif</span>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Müşteri</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($activeCustomers) }}</p>
                </div>
            </div>
            
            <!-- Yeni Müşteri Card -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-900/10 rounded-xl p-6 border border-purple-200 dark:border-purple-700 hover:shadow-lg transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-purple-600 dark:text-purple-400 bg-purple-100 dark:bg-purple-900/30 px-3 py-1 rounded-full">Bu Ay</span>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Yeni Müşteri</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($thisMonthCustomers) }}</p>
                </div>
            </div>
            
            <!-- Aylık Ortalama Card -->
            <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-900/10 rounded-xl p-6 border border-orange-200 dark:border-orange-700 hover:shadow-lg transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-orange-600 dark:text-orange-400 bg-orange-100 dark:bg-orange-900/30 px-3 py-1 rounded-full">Ortalama</span>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Aylık</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($avgMonthlyCustomers, 1) }}</p>
                </div>
            </div>
        </div>

        <!-- Ana Grafik -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Müşteri Trendi (Son {{ $period }} Ay)</h3>
                    <p class="text-gray-600 dark:text-gray-400">Aylık müşteri ekleme değişimini görsel olarak analiz edin</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-4 h-4 bg-blue-500 rounded-full"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Aylık Müşteri</span>
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        En Yüksek: {{ number_format($maxMonthlyCustomers) }}
                    </div>
                </div>
            </div>
            <div class="relative" style="height: 400px;">
                <canvas id="customersLine"></canvas>
            </div>
        </div>

        <!-- Müşteri Dağılımı -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Müşteri Türleri -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Müşteri Türleri</h3>
                <div class="space-y-3">
                    @forelse($customerTypes as $type)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            @switch($type->customer_type)
                                @case('individual')
                                    Bireysel
                                    @break
                                @case('corporate')
                                    Kurumsal
                                    @break
                                @default
                                    {{ ucfirst($type->customer_type ?? 'Genel') }}
                            @endswitch
                        </span>
                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ $type->count }}</span>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        <p>Müşteri türü bilgisi bulunamadı</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Müşteri Durumları -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Müşteri Durumları</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Aktif Müşteri</span>
                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ number_format($activeCustomers) }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Pasif Müşteri</span>
                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ number_format($totalCustomers - $activeCustomers) }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Bu Ay Eklenen</span>
                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ number_format($thisMonthCustomers) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detay Tablosu -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Aylık Detaylar</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-600 dark:text-gray-400">Dönem</th>
                            <th class="text-right py-3 px-4 text-sm font-medium text-gray-600 dark:text-gray-400">Müşteri Sayısı</th>
                            <th class="text-right py-3 px-4 text-sm font-medium text-gray-600 dark:text-gray-400">Değişim</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($labels as $index => $label)
                        @php
                            $currentValue = $values[$index];
                            $previousValue = $index > 0 ? $values[$index - 1] : 0;
                            $change = $previousValue > 0 ? (($currentValue - $previousValue) / $previousValue) * 100 : 0;
                        @endphp
                        <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="py-3 px-4 text-sm font-medium text-gray-900 dark:text-white">{{ $label }}</td>
                            <td class="py-3 px-4 text-sm text-right text-gray-900 dark:text-white">{{ number_format($currentValue) }}</td>
                            <td class="py-3 px-4 text-sm text-right">
                                @if($index > 0)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $change >= 0 ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                                        {{ $change >= 0 ? '+' : '' }}{{ number_format($change,1) }}%
                                        @if($change >= 0)
                                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                            </svg>
                                        @else
                                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                            </svg>
                                        @endif
                                    </span>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('customersLine').getContext('2d');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Aylık Müşteri',
                data: @json($values),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'rgb(59, 130, 246)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(156, 163, 175, 0.1)',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#6B7280',
                        font: {
                            size: 12
                        }
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(156, 163, 175, 0.1)',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#6B7280',
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
                    hoverBackgroundColor: 'rgb(59, 130, 246)'
                }
            }
        }
    });
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
