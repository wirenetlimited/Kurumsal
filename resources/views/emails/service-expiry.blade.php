<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hizmet Süresi Dolma Uyarısı - {{ $companyInfo['name'] }}</title>
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
        .warning-title {
            font-size: 28px;
            color: #f59e0b;
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
            border-left: 4px solid #f59e0b;
        }
        .info-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 5px;
        }
        .info-value {
            color: #6b7280;
        }
        .alert {
            background: #fef3c7;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #f59e0b;
            margin: 20px 0;
            text-align: center;
        }
        .alert.urgent {
            background: #fee2e2;
            border-color: #ef4444;
        }
        .alert.expired {
            background: #f3e8ff;
            border-color: #8b5cf6;
        }
        .alert.warning {
            background: #fef3c7;
            border-color: #f59e0b;
        }
        .renewal-options {
            background: #f9fafb;
            padding: 20px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            margin: 20px 0;
        }
        .renewal-options h4 {
            margin: 0 0 10px 0;
            color: #1f2937;
        }
        .renewal-options p {
            margin: 5px 0;
            color: #6b7280;
        }
        .price-highlight {
            background: #ecfdf5;
            padding: 20px;
            border-radius: 6px;
            border: 1px solid #10b981;
            margin: 20px 0;
            text-align: center;
        }
        .price-highlight strong {
            color: #059669;
            font-size: 18px;
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
        .quick-renewal {
            background: #fef3c7;
            padding: 20px;
            border-radius: 6px;
            border: 1px solid #f59e0b;
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
            <div class="warning-title">⚠️ Hizmet Süresi Dolma Uyarısı</div>
            <div class="customer-name">Sayın {{ $customer->name }},</div>
        </div>

        <div class="section">
            <p>{{ $companyInfo['name'] }} olarak hizmetinizin süresi dolmak üzere olduğunu bildirmek isteriz.</p>
        </div>

        <div class="section">
            <div class="section-title">🚨 Önemli Bilgilendirme</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Hizmet Türü</div>
                    <div class="info-value">{{ $serviceType ?? ucfirst($service->service_type) }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Hizmet Adı</div>
                    <div class="info-value">
                        @if($service->service_type === 'domain')
                            {{ $service->domain->domain_name ?? 'Domain Hizmeti' }}
                        @elseif($service->service_type === 'hosting')
                            {{ $service->hosting->plan_name ?? 'Hosting Hizmeti' }}
                        @else
                            {{ ucfirst($service->service_type) }} Hizmeti
                        @endif
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Bitiş Tarihi</div>
                    <div class="info-value">{{ $expiryDate ?? 'Belirtilmemiş' }}</div>
                </div>
            </div>

            @if($daysRemaining < 0)
                <div class="alert expired">
                    🔴 <strong>Bu hizmetiniz {{ abs($daysRemaining) }} gün önce sona ermiştir!</strong>
                </div>
            @elseif($daysRemaining <= 7)
                <div class="alert urgent">
                    🟠 <strong>Bu hizmetinizin süresi {{ $daysRemaining }} gün sonra dolacaktır!</strong>
                </div>
            @else
                <div class="alert warning">
                    🟡 <strong>Bu hizmetinizin süresi {{ $daysRemaining }} gün sonra dolacaktır.</strong>
                </div>
            @endif
        </div>

        <div class="section">
            <div class="section-title">💡 Yenileme Seçenekleri</div>
            <div class="renewal-options">
                <p>Hizmetinizin kesintisiz devam etmesi için aşağıdaki seçeneklerden birini tercih edebilirsiniz:</p>
                
                <h4>🔄 Otomatik Yenileme</h4>
                <p>Hizmetinizi otomatik olarak yenilemek için hesabınızda yeterli bakiye bulundurun.</p>
                
                <h4>📞 Manuel Yenileme</h4>
                <p>Bizimle iletişime geçerek hizmetinizi manuel olarak yenileyebilirsiniz.</p>
                
                <h4>🆕 Yeni Paket</h4>
                <p>Daha uygun bir pakete geçiş yapabilirsiniz.</p>
            </div>
        </div>

        <div class="section">
            <div class="section-title">💰 Yenileme Fiyatı</div>
            <div class="price-highlight">
                <strong>Yenileme Tutarı: ₺{{ number_format($service->sell_price, 2, ',', '.') }}</strong>
            </div>
        </div>

        <div class="button-container">
            <a href="{{ route('services.show', $service) }}" class="btn btn-primary">Hizmeti Görüntüle</a>
            <a href="{{ route('services.edit', $service) }}" class="btn btn-success">Hizmeti Yenile</a>
        </div>

        <div class="section">
            <div class="section-title">📞 Acil İletişim</div>
            <div class="contact-info">
                <p>Hizmetinizin kesintisiz devam etmesi için hemen bizimle iletişime geçin:</p>
                <ul>
                    <li><strong>E-posta:</strong> {{ $companyInfo['email'] }}</li>
                    <li><strong>Telefon:</strong> {{ $companyInfo['phone'] }}</li>
                    <li><strong>Web Sitesi:</strong> {{ $companyInfo['website'] }}</li>
                </ul>
            </div>
        </div>

        <div class="section">
            <div class="section-title">⚡ Hızlı Yenileme</div>
            <div class="quick-renewal">
                <p>Aşağıdaki banka hesabına ödeme yaparak hızlıca yenileme yapabilirsiniz:</p>
                @if($companyInfo['bank_name'] && $companyInfo['bank_iban'])
                    <p><strong>Banka:</strong> {{ $companyInfo['bank_name'] }}<br>
                    <strong>IBAN:</strong> {{ $companyInfo['bank_iban'] }}<br>
                    <strong>Açıklama:</strong> Hizmet Yenileme - {{ $service->id }}</p>
                @else
                    <p><strong>Banka:</strong> Örnek Bank<br>
                    <strong>IBAN:</strong> TR12 3456 7890 1234 5678 9012 34<br>
                    <strong>Açıklama:</strong> Hizmet Yenileme - {{ $service->id }}</p>
                    <p class="text-sm text-gray-600 mt-2">💡 Banka bilgileri site ayarlarından güncellenebilir.</p>
                @endif
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
            <p>Hizmeti Görüntüle: {{ route('services.show', $service) }}<br>
            Hizmeti Yenile: {{ route('services.edit', $service) }}</p>
        </div>
    </div>
</body>
</html>
