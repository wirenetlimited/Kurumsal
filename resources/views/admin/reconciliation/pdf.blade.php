<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mutabakat Raporu</title>
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
        
        .error {
            color: #dc2626;
            font-weight: bold;
        }
        
        .warning {
            color: #d97706;
            font-weight: bold;
        }
        
        .ok {
            color: #059669;
            font-weight: bold;
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
        <h1>Muhasebe Mutabakat Raporu</h1>
        <p>Rapor Tarihi: {{ $generatedAt->format('d.m.Y H:i') }}</p>
    </div>

    <div class="summary">
        <div class="summary-grid">
            <div class="summary-item">
                <h3>Toplam Hata</h3>
                <div class="value error">{{ $data['invoice_check']['errors']->count() }}</div>
            </div>
            <div class="summary-item">
                <h3>Toplam Uyarı</h3>
                <div class="value warning">{{ $data['invoice_check']['warnings']->count() }}</div>
            </div>
            <div class="summary-item">
                <h3>Doğru Kayıtlar</h3>
                <div class="value ok">{{ $data['invoice_check']['ok']->count() }}</div>
            </div>
            <div class="summary-item">
                <h3>Müşteri Bakiyeleri</h3>
                <div class="value">{{ $data['customer_balances']->count() }}</div>
            </div>
        </div>
    </div>

    @if($data['invoice_check']['errors']->count() > 0)
    <div class="section">
        <div class="section-title">❌ Hatalar ({{ $data['invoice_check']['errors']->count() }})</div>
        <table>
            <thead>
                <tr>
                    <th>Fatura No</th>
                    <th>Müşteri</th>
                    <th>Durum</th>
                    <th>Beklenen</th>
                    <th>Gerçek</th>
                    <th>Fark</th>
                    <th>Açıklama</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['invoice_check']['errors'] as $item)
                <tr>
                    <td>{{ $item['invoice_number'] }}</td>
                    <td>{{ $item['customer_name'] }}</td>
                    <td>{{ $item['status'] }}</td>
                    <td>{{ number_format($item['expected'], 2, ',', '.') }}</td>
                    <td>{{ number_format($item['actual'], 2, ',', '.') }}</td>
                    <td class="error">{{ number_format($item['difference'], 2, ',', '.') }}</td>
                    <td>{{ $item['description'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($data['invoice_check']['warnings']->count() > 0)
    <div class="section">
        <div class="section-title">⚠️ Uyarılar ({{ $data['invoice_check']['warnings']->count() }})</div>
        <table>
            <thead>
                <tr>
                    <th>Fatura No</th>
                    <th>Müşteri</th>
                    <th>Durum</th>
                    <th>Beklenen</th>
                    <th>Gerçek</th>
                    <th>Fark</th>
                    <th>Açıklama</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['invoice_check']['warnings'] as $item)
                <tr>
                    <td>{{ $item['invoice_number'] }}</td>
                    <td>{{ $item['customer_name'] }}</td>
                    <td>{{ $item['status'] }}</td>
                    <td>{{ number_format($item['expected'], 2, ',', '.') }}</td>
                    <td>{{ number_format($item['actual'], 2, ',', '.') }}</td>
                    <td class="warning">{{ number_format($item['difference'], 2, ',', '.') }}</td>
                    <td>{{ $item['description'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($data['customer_balances']->count() > 0)
    <div class="section">
        <div class="section-title">💰 Müşteri Bakiye Kontrolleri ({{ $data['customer_balances']->count() }})</div>
        <table>
            <thead>
                <tr>
                    <th>Müşteri</th>
                    <th>Hesaplanan Bakiye</th>
                    <th>Kayıtlı Bakiye</th>
                    <th>Fark</th>
                    <th>Durum</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['customer_balances'] as $balance)
                <tr>
                    <td>{{ $balance['customer_name'] }}</td>
                    <td>{{ number_format($balance['calculated_balance'], 2, ',', '.') }}</td>
                    <td>{{ number_format($balance['recorded_balance'], 2, ',', '.') }}</td>
                    <td class="{{ $balance['difference'] == 0 ? 'ok' : 'error' }}">
                        {{ number_format($balance['difference'], 2, ',', '.') }}
                    </td>
                    <td class="{{ $balance['difference'] == 0 ? 'ok' : 'error' }}">
                        {{ $balance['difference'] == 0 ? '✓ Uyumlu' : '✗ Uyumsuz' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer">
        <p>Bu rapor {{ config('app.name') }} sistemi tarafından otomatik olarak oluşturulmuştur.</p>
        <p>Rapor oluşturma tarihi: {{ $generatedAt->format('d.m.Y H:i:s') }}</p>
    </div>
</body>
</html>

