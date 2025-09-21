<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gelir Analiz Raporu</title>
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
            width: 20%;
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
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 9px;
        }
        
        th, td {
            border: 1px solid #e2e8f0;
            padding: 6px 8px;
            text-align: left;
        }
        
        th {
            background-color: #f1f5f9;
            font-weight: bold;
            color: #475569;
            font-size: 8px;
        }
        
        .positive {
            color: #059669;
        }
        
        .negative {
            color: #dc2626;
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
        <h1>Gelir Analiz Raporu</h1>
        <p>Rapor Tarihi: {{ now()->format('d.m.Y H:i') }}</p>
        <p>Rapor Dönemi: {{ $period }} Ay</p>
    </div>

    <div class="period-info">
        <strong>Dönem:</strong> {{ $startDate->format('d.m.Y') }} - {{ $endDate->format('d.m.Y') }}
    </div>

    <div class="summary">
        <div class="summary-grid">
            <div class="summary-item">
                <h3>Toplam Fatura</h3>
                <div class="value">₺{{ number_format($totals['total_issued'], 2, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <h3>Toplam Ödeme</h3>
                <div class="value">₺{{ number_format($totals['total_paid'], 2, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <h3>Kalan Tutar</h3>
                <div class="value {{ $totals['total_remaining'] >= 0 ? 'positive' : 'negative' }}">
                    ₺{{ number_format($totals['total_remaining'], 2, ',', '.') }}
                </div>
            </div>
            <div class="summary-item">
                <h3>Ort. Aylık Fatura</h3>
                <div class="value">₺{{ number_format($totals['avg_issued'], 2, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <h3>Ort. Aylık Ödeme</h3>
                <div class="value">₺{{ number_format($totals['avg_paid'], 2, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Ay</th>
                <th>Fatura Edilen (₺)</th>
                <th>Ödenen (₺)</th>
                <th>Kalan (₺)</th>
                <th>Ödeme Oranı (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                <td>{{ $row['month_name'] }}</td>
                <td>{{ number_format($row['issued'], 2, ',', '.') }}</td>
                <td>{{ number_format($row['paid'], 2, ',', '.') }}</td>
                <td class="{{ $row['remaining'] >= 0 ? 'positive' : 'negative' }}">
                    {{ number_format($row['remaining'], 2, ',', '.') }}
                </td>
                <td class="{{ $row['issued'] > 0 ? ($row['paid'] / $row['issued'] * 100 >= 80 ? 'positive' : ($row['paid'] / $row['issued'] * 100 >= 50 ? 'neutral' : 'negative')) : 'neutral' }}">
                    {{ $row['issued'] > 0 ? number_format($row['paid'] / $row['issued'] * 100, 1, ',', '.') : '0' }}%
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Bu rapor {{ config('app.name') }} sistemi tarafından otomatik olarak oluşturulmuştur.</p>
        <p>Rapor oluşturma tarihi: {{ now()->format('d.m.Y H:i:s') }}</p>
    </div>
</body>
</html>

