<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fatura Bildirimi - {{ \App\Models\Setting::get('site_name', 'WH Kurumsal') }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .invoice-title {
            font-size: 28px;
            color: #dc2626;
            margin-bottom: 10px;
        }
        .customer-name {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 20px;
            color: #1f2937;
            margin-bottom: 15px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 5px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        .info-item {
            background: #f3f4f6;
            padding: 12px;
            border-radius: 6px;
            border-left: 4px solid #dc2626;
        }
        .info-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 5px;
        }
        .info-value {
            color: #6b7280;
        }
        .warning {
            background: #fef3c7;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #f59e0b;
            margin: 20px 0;
            text-align: center;
        }
        .warning.urgent {
            background: #fee2e2;
            border-color: #ef4444;
        }
        .warning.overdue {
            background: #f3e8ff;
            border-color: #8b5cf6;
        }
        .invoice-table {
            background: #f8fafc;
            border-radius: 8px;
            padding: 16px;
            margin: 16px 0;
            overflow-x: auto;
        }
        .invoice-table table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        .invoice-table th {
            text-align: left;
            padding: 8px;
            color: #64748b;
            font-weight: 600;
            border-bottom: 2px solid #e2e8f0;
        }
        .invoice-table td {
            padding: 8px;
            color: #1e293b;
            border-bottom: 1px solid #e2e8f0;
        }
        .invoice-table th:last-child,
        .invoice-table td:last-child {
            text-align: right;
        }
        .invoice-table th:nth-child(2),
        .invoice-table td:nth-child(2),
        .invoice-table th:nth-child(4),
        .invoice-table td:nth-child(4) {
            text-align: center;
        }
        .financial-summary {
            background: #f0f9ff;
            border-radius: 8px;
            padding: 16px;
            margin: 16px 0;
        }
        .financial-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            color: #0369a1;
            font-weight: 500;
        }
        .financial-row.total {
            padding-top: 8px;
            border-top: 2px solid #0ea5e9;
            color: #0c4a6e;
            font-weight: 700;
            font-size: 16px;
        }
        .financial-row.total .amount {
            font-size: 18px;
        }
        .payment-methods {
            background: #f9fafb;
            padding: 20px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            margin: 20px 0;
        }
        .payment-methods ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .payment-methods li {
            padding: 8px 0;
            color: #6b7280;
        }
        .button-container {
            text-align: center;
            margin: 25px 0;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            margin: 0 10px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: #2563eb;
            color: white;
        }
        .btn-primary:hover {
            background: #1d4ed8;
        }
        .btn-success {
            background: #10b981;
            color: white;
        }
        .btn-success:hover {
            background: #059669;
        }
        .contact-info {
            background: #f0f9ff;
            padding: 20px;
            border-radius: 6px;
            border: 1px solid #bae6fd;
            margin: 25px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="invoice-title">🧾 Fatura Bildirimi</div>
            <div class="customer-name">Sayın {{ $customer->name }},</div>
        </div>

        <div class="section">
            <p>{{ \App\Models\Setting::get('site_name', 'WH Kurumsal') }} olarak size yeni faturanızı gönderiyoruz.</p>
        </div>

        <div class="section">
            <div class="section-title">📋 Fatura Detayları</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Fatura No</div>
                    <div class="info-value">#{{ $invoice->invoice_number ?? $invoice->id }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Fatura Tarihi</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($invoice->issue_date)->format('d.m.Y') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Vade Tarihi</div>
                    <div class="info-value">{{ $dueDate ?? 'Belirtilmemiş' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Toplam Tutar</div>
                    <div class="info-value"><strong>{{ \App\Models\Setting::get('currency_symbol', '₺') }}{{ number_format($invoice->total, 2, ',', '.') }}</strong></div>
                </div>
            </div>

            @if($daysRemaining !== null)
                @if($daysRemaining < 0)
                    <div class="warning overdue">
                        ⚠️ <strong>Bu fatura {{ abs($daysRemaining) }} gün gecikmiştir.</strong>
                    </div>
                @elseif($daysRemaining <= 7)
                    <div class="warning urgent">
                        ⚠️ <strong>Bu faturanın vadesine {{ $daysRemaining }} gün kalmıştır.</strong>
                    </div>
                @else
                    <div class="warning">
                        📅 <strong>Bu faturanın vadesine {{ $daysRemaining }} gün kalmıştır.</strong>
                    </div>
                @endif
            @endif
        </div>

        @if($invoice->items && $invoice->items->count() > 0)
        <div class="section">
            <div class="section-title">🛒 Fatura Kalemleri</div>
            <div class="invoice-table">
                <table>
                    <thead>
                        <tr>
                            <th>Açıklama</th>
                            <th>Miktar</th>
                            <th>Birim Fiyat</th>
                            <th>KDV %</th>
                            <th>Tutar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->items as $item)
                        <tr>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>{{ \App\Models\Setting::get('currency_symbol', '₺') }}{{ number_format($item->unit_price, 2, ',', '.') }}</td>
                            <td>%{{ $item->tax_rate }}</td>
                            <td><strong>{{ \App\Models\Setting::get('currency_symbol', '₺') }}{{ number_format($item->line_total, 2, ',', '.') }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="section-title">💰 Finansal Özet</div>
            <div class="financial-summary">
                <div class="financial-row">
                    <span>Ara Toplam:</span>
                    <span>{{ \App\Models\Setting::get('currency_symbol', '₺') }}{{ number_format($invoice->subtotal ?? 0, 2, ',', '.') }}</span>
                </div>
                <div class="financial-row">
                    <span>KDV Toplam:</span>
                    <span>{{ \App\Models\Setting::get('currency_symbol', '₺') }}{{ number_format($invoice->tax_total ?? 0, 2, ',', '.') }}</span>
                </div>
                <div class="financial-row total">
                    <span>Genel Toplam:</span>
                    <span class="amount">{{ \App\Models\Setting::get('currency_symbol', '₺') }}{{ number_format($invoice->total ?? 0, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>
        @endif

        <div class="section">
            <div class="section-title">💳 Ödeme Seçenekleri</div>
            <div class="payment-methods">
                @if(\App\Models\Setting::get('bank_name') || \App\Models\Setting::get('bank_iban'))
                    <p>Aşağıdaki yöntemlerle ödeme yapabilirsiniz:</p>
                    
                    @if(\App\Models\Setting::get('bank_name') && \App\Models\Setting::get('bank_iban'))
                        <ul>
                            <li><strong>🏦 Banka Havalesi:</strong> {{ \App\Models\Setting::get('bank_name') }} - {{ \App\Models\Setting::get('bank_iban') }}</li>
                        </ul>
                    @endif

                    @if(\App\Models\Setting::get('payment_methods'))
                        @php
                            $methods = json_decode(\App\Models\Setting::get('payment_methods'), true);
                            $methodLabels = [
                                'credit_card' => '💳 Kredi Kartı',
                                'cash' => '💰 Nakit',
                                'online_payment' => '🌐 Online Ödeme',
                                'check' => '📄 Çek',
                                'mobile_payment' => '📱 Mobil Ödeme'
                            ];
                        @endphp
                        @if(is_array($methods))
                            <ul>
                                @foreach($methods as $method)
                                    @if($method !== 'bank_transfer')
                                        <li><strong>{{ $methodLabels[$method] ?? ucfirst($method) }}</strong></li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                    @endif

                    @if(\App\Models\Setting::get('tax_number'))
                        <p><strong>🏢 Vergi Numarası:</strong> {{ \App\Models\Setting::get('tax_number') }}</p>
                    @endif
                @else
                    <p>Aşağıdaki yöntemlerle ödeme yapabilirsiniz:</p>
                    <ul>
                        <li><strong>Banka Havalesi:</strong> TR12 3456 7890 1234 5678 9012 34</li>
                        <li><strong>Kredi Kartı:</strong> Online ödeme sistemi</li>
                        <li><strong>Nakit:</strong> Ofisimizde</li>
                    </ul>
                @endif
            </div>
        </div>

        <div class="button-container">
            <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-primary">Faturayı Görüntüle</a>
            <a href="{{ route('invoices.show', $invoice) }}?pdf=1" class="btn btn-success">PDF Görüntüle</a>
        </div>

        <div class="section">
            <div class="section-title">📞 İletişim</div>
            <div class="contact-info">
                <p>Herhangi bir sorunuz varsa bizimle iletişime geçebilirsiniz:</p>
                <ul>
                    <li><strong>E-posta:</strong> {{ \App\Models\Setting::get('contact_email', 'info@example.com') }}</li>
                    <li><strong>Telefon:</strong> {{ \App\Models\Setting::get('contact_phone', '+90 xxx xxx xx xx') }}</li>
                    <li><strong>Web Sitesi:</strong> {{ \App\Models\Setting::get('website', 'https://example.com') }}</li>
                </ul>
            </div>
        </div>

        <div class="section">
            <div class="section-title">🏢 Şirket Bilgileri</div>
            <div class="contact-info">
                <strong>{{ \App\Models\Setting::get('site_name', 'WH Kurumsal') }}</strong><br>
                {{ \App\Models\Setting::get('contact_address', 'Adres bilgisi') }}<br>
                {{ \App\Models\Setting::get('contact_email', 'info@example.com') }} | {{ \App\Models\Setting::get('contact_phone', '+90 xxx xxx xx xx') }}
            </div>
        </div>

        <div class="footer">
            <p><strong>Bu e-posta {{ \App\Models\Setting::get('site_name', 'WH Kurumsal') }} tarafından otomatik olarak gönderilmiştir. Lütfen yanıtlamayınız.</strong></p>
            <p>Eğer butonlar çalışmıyorsa, aşağıdaki linkleri kullanabilirsiniz:</p>
            <p>Fatura Görüntüle: {{ route('invoices.show', $invoice) }}<br>
            PDF Görüntüle: {{ route('invoices.show', $invoice) }}?pdf=1</p>
        </div>
    </div>
</body>
</html>
