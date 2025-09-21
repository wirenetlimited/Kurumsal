<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiyat Teklifi - {{ $companyInfo['name'] }}</title>
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
        .quote-title {
            font-size: 28px;
            color: #2563eb;
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
            border-left: 4px solid #2563eb;
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
        .warning.expired {
            background: #f3e8ff;
            border-color: #8b5cf6;
        }
        .services-list {
            background: #f9fafb;
            padding: 20px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            margin: 20px 0;
        }
        .services-list ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .services-list li {
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
            color: #6b7280;
        }
        .services-list li:last-child {
            border-bottom: none;
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
        .btn[type="submit"] {
            border: none;
            cursor: pointer;
        }
        .contact-info {
            background: #f0f9ff;
            padding: 20px;
            border-radius: 6px;
            border: 1px solid #bae6fd;
            margin: 25px 0;
        }
        .payment-methods {
            background: #f9fafb;
            padding: 20px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            margin: 20px 0;
        }
        .payment-methods h4 {
            margin: 0 0 15px 0;
            color: #1f2937;
            font-size: 16px;
        }
        .bank-info {
            background: #ecfdf5;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #10b981;
            margin: 15px 0;
        }
        .bank-info p {
            margin: 8px 0;
            color: #065f46;
        }
        .other-methods {
            background: #f0f9ff;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #0ea5e9;
            margin: 15px 0;
        }
        .other-methods ul {
            list-style: none;
            padding: 0;
            margin: 10px 0 0 0;
        }
        .other-methods li {
            padding: 5px 0;
            color: #0369a1;
        }
        .tax-info {
            background: #fef3c7;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #f59e0b;
            margin: 15px 0;
            text-align: center;
        }
        .tax-info p {
            margin: 0;
            color: #92400e;
        }
        .text-sm {
            font-size: 14px;
        }
        .text-gray-600 {
            color: #6b7280;
        }
        .mt-2 {
            margin-top: 8px;
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
            <div class="quote-title">💼 Fiyat Teklifi</div>
            <div class="customer-name">Sayın {{ $customer->name }},</div>
        </div>

        <div class="section">
            <p>{{ $companyInfo['name'] }} olarak size özel fiyat teklifimizi sunuyoruz.</p>
        </div>

        <div class="section">
            <div class="section-title">📋 Teklif Detayları</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Teklif No</div>
                    <div class="info-value">{{ $quote->number }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Teklif Tarihi</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($quote->quote_date)->format('d.m.Y') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Geçerlilik</div>
                    <div class="info-value">{{ $validUntil ?? 'Belirtilmemiş' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Toplam Tutar</div>
                    <div class="info-value"><strong>{{ \App\Models\Setting::get('currency_symbol', '₺') }}{{ number_format($quote->total, 2, ',', '.') }}</strong></div>
                </div>
            </div>

            @if($daysRemaining !== null)
                @if($daysRemaining < 0)
                    <div class="warning expired">
                        ⚠️ <strong>Bu teklifin geçerlilik süresi {{ abs($daysRemaining) }} gün önce dolmuştur.</strong>
                    </div>
                @elseif($daysRemaining <= 7)
                    <div class="warning urgent">
                        ⚠️ <strong>Bu teklifin geçerlilik süresine {{ $daysRemaining }} gün kalmıştır.</strong>
                    </div>
                @else
                    <div class="warning">
                        📅 <strong>Bu teklif {{ $daysRemaining }} gün geçerlidir.</strong>
                    </div>
                @endif
            @endif
        </div>

        <div class="section">
            <div class="section-title">📝 Teklif Açıklaması</div>
            <div class="services-list">
                <h4><strong>{{ $quote->title }}</strong></h4>
                @if($quote->description)
                    <p>{{ $quote->description }}</p>
                @endif
            </div>
        </div>

        <div class="section">
            <div class="section-title">🎯 Teklif Edilen Hizmetler</div>
            <div class="services-list">
                @if($quote->items && count($quote->items) > 0)
                    <ul>
                        @foreach($quote->items as $item)
                            <li><strong>{{ $item['description'] }}</strong> - ₺{{ number_format($item['amount'], 2, ',', '.') }}</li>
                        @endforeach
                    </ul>
                @else
                    <ul>
                        <li>Domain kayıt hizmetleri</li>
                        <li>Hosting paketleri</li>
                        <li>SSL sertifikaları</li>
                        <li>E-posta hizmetleri</li>
                    </ul>
                @endif
            </div>
        </div>

        <div class="section">
            <div class="section-title">✅ Teklifi Kabul Etmek İçin</div>
            <p>Aşağıdaki butonları kullanarak teklifimizi kabul edebilir veya görüntüleyebilirsiniz:</p>
        </div>

        <div class="button-container">
            <a href="{{ route('quotes.show', $quote) }}" class="btn btn-primary">Teklifi Görüntüle</a>
            <a href="{{ route('quotes.show', $quote) }}?pdf=1" class="btn btn-success">PDF İndir</a>
            <form method="POST" action="{{ route('quotes.accept', $quote) }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-success" onclick="return confirm('Bu teklifi kabul etmek istediğinizden emin misiniz?')">Teklifi Kabul Et</button>
            </form>
        </div>

        <div class="section">
            <div class="section-title">💳 Ödeme Seçenekleri</div>
            <div class="payment-methods">
                @if($companyInfo['bank_name'] && $companyInfo['bank_iban'])
                    <p>Aşağıdaki yöntemlerle ödeme yapabilirsiniz:</p>
                    
                    <div class="bank-info">
                        <h4>🏦 Banka Havalesi</h4>
                        <p><strong>Banka:</strong> {{ $companyInfo['bank_name'] }}</p>
                        <p><strong>IBAN:</strong> {{ $companyInfo['bank_iban'] }}</p>
                        <p><strong>Açıklama:</strong> Teklif #{{ $quote->number }} - {{ $customer->name }}</p>
                    </div>

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
                            <div class="other-methods">
                                <h4>🔄 Diğer Ödeme Yöntemleri</h4>
                                <ul>
                                    @foreach($methods as $method)
                                        @if($method !== 'bank_transfer')
                                            <li><strong>{{ $methodLabels[$method] ?? ucfirst($method) }}</strong></li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endif

                    @if($companyInfo['tax_number'])
                        <div class="tax-info">
                            <p><strong>🏢 Vergi Numarası:</strong> {{ $companyInfo['tax_number'] }}</p>
                        </div>
                    @endif
                @else
                    <p>Aşağıdaki yöntemlerle ödeme yapabilirsiniz:</p>
                    <ul>
                        <li><strong>Banka Havalesi:</strong> TR12 3456 7890 1234 5678 9012 34</li>
                        <li><strong>Kredi Kartı:</strong> Online ödeme sistemi</li>
                        <li><strong>Nakit:</strong> Ofisimizde</li>
                    </ul>
                    <p class="text-sm text-gray-600 mt-2">💡 Banka bilgileri site ayarlarından güncellenebilir.</p>
                @endif
            </div>
        </div>

        <div class="section">
            <div class="section-title">📞 İletişim</div>
            <div class="contact-info">
                <p>Teklifimiz hakkında sorularınız varsa bizimle iletişime geçebilirsiniz:</p>
                <ul>
                    <li><strong>E-posta:</strong> {{ $companyInfo['email'] }}</li>
                    <li><strong>Telefon:</strong> {{ $companyInfo['phone'] }}</li>
                    <li><strong>Web Sitesi:</strong> {{ $companyInfo['website'] }}</li>
                </ul>
            </div>
        </div>

        <div class="section">
            <div class="section-title">🏢 Şirket Bilgileri</div>
            <div class="contact-info">
                <strong>{{ $companyInfo['name'] }}</strong><br>
                {{ $companyInfo['address'] }}<br>
                {{ $companyInfo['email'] }} | {{ $companyInfo['phone'] }}
            </div>
        </div>

        <div class="footer">
            <p><strong>Bu e-posta otomatik olarak gönderilmiştir. Lütfen yanıtlamayınız.</strong></p>
            <p>Eğer butonlar çalışmıyorsa, aşağıdaki linkleri kullanabilirsiniz:</p>
            <p>Teklifi Görüntüle: {{ route('quotes.show', $quote) }}<br>
            PDF İndir: {{ route('quotes.show', $quote) }}?pdf=1<br>
            Teklifi Kabul Et: {{ route('quotes.accept', $quote) }} (POST metodu)</p>
        </div>
    </div>
</body>
</html>
