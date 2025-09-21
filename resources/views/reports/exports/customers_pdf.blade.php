<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Müşteri Analiz Raporu</title>
    <style>
        @charset "UTF-8";
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', 'Liberation Sans', 'Arial Unicode MS', Arial, sans-serif !important;
            font-size: 10px;
            line-height: 1.4;
            color: #1e293b;
            background: white;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #1e293b;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .header h1 {
            font-size: 20px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 9px;
            color: #64748b;
        }
        
        .summary {
            margin-bottom: 20px;
        }
        
        .summary-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .summary-item {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 10px 5px;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
        }
        
        .summary-item h3 {
            font-size: 8px;
            color: #64748b;
            margin-bottom: 5px;
            font-weight: normal;
        }
        
        .summary-item .value {
            font-size: 12px;
            font-weight: bold;
            color: #1e293b;
        }
        
        .period-info {
            background-color: #f1f5f9;
            padding: 8px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 9px;
            color: #475569;
        }
        
        .section {
            margin-bottom: 20px;
        }
        
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 10px;
            padding: 5px;
            background-color: #f1f5f9;
            border-left: 3px solid #3b82f6;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 8px;
        }
        
        th, td {
            border: 1px solid #e2e8f0;
            padding: 4px 6px;
            text-align: left;
        }
        
        th {
            background-color: #f1f5f9;
            font-weight: bold;
            color: #475569;
            font-size: 7px;
        }
        
        .positive {
            color: #059669;
        }
        
        .neutral {
            color: #6b7280;
        }
        
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
        
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Müşteri Analiz Raporu</h1>
        <p>Rapor Tarihi: {{ now()->format('d.m.Y H:i') }}</p>
        <p>Rapor Dönemi: {{ $period }} Ay</p>
    </div>

    <div class="period-info">
        <strong>Dönem:</strong> {{ $startDate->format('d.m.Y') }} - {{ $endDate->format('d.m.Y') }}
    </div>

    <div class="summary">
        <div class="summary-grid">
            <div class="summary-item">
                <h3>Toplam Müşteri</h3>
                <div class="value">{{ $stats['total_customers'] }}</div>
            </div>
            <div class="summary-item">
                <h3>Aktif Müşteri</h3>
                <div class="value positive">{{ $stats['active_customers'] }}</div>
            </div>
            <div class="summary-item">
                <h3>Pasif Müşteri</h3>
                <div class="value neutral">{{ $stats['inactive_customers'] }}</div>
            </div>
            <div class="summary-item">
                <h3>Bu Ay Yeni</h3>
                <div class="value">{{ $stats['this_month_customers'] }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">📈 Aylık Müşteri Verileri</div>
        <table>
            <thead>
                <tr>
                    <th>Ay</th>
                    <th>Yeni Müşteri</th>
                    <th>Toplam Müşteri</th>
                    <th>Büyüme Oranı (%)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($monthlyData as $index => $row)
                @php
                    $previousTotal = $index > 0 ? $monthlyData[$index - 1]['total_customers'] : 0;
                    $growthRate = $previousTotal > 0 ? (($row['new_customers'] / $previousTotal) * 100) : 0;
                @endphp
                <tr>
                    <td>{{ $row['month_name'] }}</td>
                    <td>{{ $row['new_customers'] }}</td>
                    <td>{{ $row['total_customers'] }}</td>
                    <td class="{{ $growthRate > 0 ? 'positive' : 'neutral' }}">
                        {{ number_format($growthRate, 1, ',', '.') }}%
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($customerTypes->count() > 0)
    <div class="section">
        <div class="section-title">👥 Müşteri Türleri Dağılımı</div>
        <table>
            <thead>
                <tr>
                    <th>Müşteri Türü</th>
                    <th>Sayı</th>
                    <th>Oran (%)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customerTypes as $type)
                @php
                    $percentage = $stats['total_customers'] > 0 ? ($type->count / $stats['total_customers']) * 100 : 0;
                @endphp
                <tr>
                    <td>{{ $type->customer_type ?? 'Belirtilmemiş' }}</td>
                    <td>{{ $type->count }}</td>
                    <td>{{ number_format($percentage, 1, ',', '.') }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer">
        <p>Bu rapor {{ config('app.name') }} sistemi tarafından otomatik olarak oluşturulmuştur.</p>
        <p>Rapor oluşturma tarihi: {{ now()->format('d.m.Y H:i:s') }}</p>
    </div>
</body>
</html>

