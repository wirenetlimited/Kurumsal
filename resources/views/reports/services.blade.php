<x-app-layout>
@php
    // Grafik için veri hazırla
    $maxMonthlyServices = max($values);
    $avgMonthlyServices = $values ? round(array_sum($values) / count($values), 1) : 0;
@endphp

<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto p-6 space-y-8">
        <!-- Başlık ve Navigasyon -->
        <div class="flex items-center justify-between">
            <div class="space-y-2">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Hizmet Analizi</h1>
                        <p class="text-gray-600 dark:text-gray-400">Domain, hosting ve SSL hizmetlerinin dağılımını analiz edin</p>
                    </div>
                </div>
            </div>
            <a href="{{ route('reports.index') }}" class="inline-flex items-center px-6 py-3 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Geri Dön
            </a>
        </div>

        <!-- Dönem Seçici -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Analiz Dönemi</h3>
                <div class="flex space-x-2">
                    @foreach([1, 3, 6, 12] as $p)
                    <a href="?period={{ $p }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ $period == $p ? 'bg-green-600 text-white shadow-lg' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        {{ $p }} {{ $p == 1 ? 'Ay' : 'Ay' }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Özet Kartları -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Eklenen Card -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-900/10 rounded-xl p-6 border border-green-200 dark:border-green-700 hover:shadow-lg transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900/30 px-3 py-1 rounded-full">Bu Ay</span>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Eklenen</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($thisMonthServices) }}</p>
                </div>
            </div>
            
            <!-- Toplam Hizmet Card -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-900/10 rounded-xl p-6 border border-blue-200 dark:border-blue-700 hover:shadow-lg transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-900/30 px-3 py-1 rounded-full">Toplam</span>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Hizmet</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalServices) }}</p>
                </div>
            </div>
            
            <!-- Aktif Hizmet Card -->
            <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-900/10 rounded-xl p-6 border border-orange-200 dark:border-orange-700 hover:shadow-lg transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-orange-600 dark:text-orange-400 bg-orange-100 dark:bg-orange-900/30 px-3 py-1 rounded-full">Aktif</span>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Hizmet</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($activeServices) }}</p>
                </div>
            </div>
            
            <!-- Aylık Ortalama Card -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-900/10 rounded-xl p-6 border border-purple-200 dark:border-purple-700 hover:shadow-lg transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-purple-600 dark:text-purple-400 bg-purple-100 dark:bg-purple-900/30 px-3 py-1 rounded-full">Ortalama</span>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Aylık</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($avgMonthlyServices, 1) }}</p>
                </div>
            </div>
        </div>

        <!-- Ana Grafik -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Hizmet Trendi (Son {{ $period }} Ay)</h3>
                    <p class="text-gray-600 dark:text-gray-400">Aylık hizmet ekleme değişimini görsel olarak analiz edin</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Aylık Hizmet</span>
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        En Yüksek: {{ number_format($maxMonthlyServices) }}
                    </div>
                </div>
            </div>
            <div class="relative" style="height: 400px;">
                <canvas id="servicesLine"></canvas>
            </div>
        </div>

        <!-- Hizmet Dağılımı -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Hizmet Türleri -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Hizmet Türleri</h3>
                <div class="space-y-3">
                    @forelse($serviceTypes as $type)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ ucfirst($type->service_type) }}</span>
                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ $type->count }}</span>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p>Henüz hizmet eklenmemiş</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Hizmet Durumları -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Hizmet Durumları</h3>
                <div class="space-y-3">
                    @forelse($serviceStatuses as $status)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            @switch($status->status)
                                @case('active')
                                    Aktif
                                    @break
                                @case('cancelled')
                                    İptal Edildi
                                    @break
                                @case('suspended')
                                    Askıya Alındı
                                    @break
                                @default
                                    {{ ucfirst($status->status) }}
                            @endswitch
                        </span>
                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ $status->count }}</span>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p>Durum bilgisi bulunamadı</p>
                    </div>
                    @endforelse
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
                            <th class="text-right py-3 px-4 text-sm font-medium text-gray-600 dark:text-gray-400">Hizmet Sayısı</th>
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
                                    <span class="text-gray-400">-</span>
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

<script src="{{ asset('js/chart.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Chart === 'undefined') {
        console.error('Chart.js yüklenemedi');
        document.getElementById('servicesLine').parentNode.innerHTML = '<div class="h-full flex items-center justify-center text-gray-500">Chart.js yüklenemedi</div>';
        return;
    }

    const servicesCtx = document.getElementById('servicesLine');
    if (!servicesCtx) {
        console.error('Canvas element bulunamadı');
        return;
    }

    try {
        if (window.servicesChart) {
            window.servicesChart.destroy();
        }

        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? '#374151' : '#e5e7eb';
        const textColor = isDark ? '#f3f4f6' : '#374151';

        window.servicesChart = new Chart(servicesCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    label: 'Aylık Hizmet Sayısı',
                    data: {!! json_encode($values) !!},
                    tension: 0.4,
                    fill: true,
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
                }]
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
                                return 'Hizmet Sayısı: ' + context.parsed.y.toLocaleString('tr-TR');
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
                                return value.toLocaleString('tr-TR');
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
                        hoverBackgroundColor: 'rgb(34, 197, 94)',
                        hoverBorderColor: '#ffffff'
                    }
                }
            }
        });

        console.log('Hizmet grafiği başarıyla oluşturuldu');
    } catch (error) {
        console.error('Grafik oluşturulurken hata:', error);
        document.getElementById('servicesLine').parentNode.innerHTML = '<div class="h-full flex items-center justify-center text-red-500">Grafik yüklenirken hata oluştu</div>';
    }
});
</script>
</x-app-layout>
