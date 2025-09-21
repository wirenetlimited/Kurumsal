<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoş Geldiniz - {{ $companyInfo['name'] }}</title>
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
        .welcome-title {
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
        .subsection-title {
            font-size: 16px;
            color: #374151;
            margin-bottom: 10px;
            margin-top: 20px;
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
        .services-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }
        .service-category {
            background: #f9fafb;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }
        .service-category h4 {
            margin: 0 0 10px 0;
            color: #1f2937;
        }
        .service-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .service-list li {
            padding: 5px 0;
            color: #6b7280;
        }
        .service-list li:before {
            content: "✓ ";
            color: #10b981;
            font-weight: bold;
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
        .highlight {
            background: #fef3c7;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #f59e0b;
            margin: 20px 0;
            text-align: center;
        }
        .highlight strong {
            color: #d97706;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="welcome-title">🎉 Hoş Geldiniz!</div>
            <div class="customer-name">Sayın {{ $customer->name }},</div>
        </div>

        <div class="section">
            <p>{{ $companyInfo['name'] }} ailesine katıldığınız için teşekkür ederiz! Artık profesyonel web hosting ve domain hizmetlerimizden yararlanabilirsiniz.</p>
        </div>

        <div class="section">
            <div class="section-title">👋 Sizi Tanıyoruz</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Müşteri Türü</div>
                    <div class="info-value">{{ $customerType }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">E-posta</div>
                    <div class="info-value">{{ $customer->email }}</div>
                </div>
                @if($customer->phone)
                <div class="info-item">
                    <div class="info-label">Telefon</div>
                    <div class="info-value">{{ $customer->phone }}</div>
                </div>
                @endif
                @if($customer->tax_number)
                <div class="info-item">
                    <div class="info-label">Vergi No</div>
                    <div class="info-value">{{ $customer->tax_number }}</div>
                </div>
                @endif
            </div>
        </div>

        <div class="section">
            <div class="section-title">🚀 Hizmetlerimiz</div>
            <p>{{ $companyInfo['name'] }} olarak size aşağıdaki hizmetleri sunuyoruz:</p>
            
            <div class="services-grid">
                <div class="service-category">
                    <h4>🌐 Domain Hizmetleri</h4>
                    <ul class="service-list">
                        <li>Domain kayıt ve transfer</li>
                        <li>WHOIS gizlilik koruması</li>
                        <li>DNS yönetimi</li>
                        <li>Subdomain oluşturma</li>
                    </ul>
                </div>
                
                <div class="service-category">
                    <h4>🖥️ Hosting Hizmetleri</h4>
                    <ul class="service-list">
                        <li>SSD disk alanı</li>
                        <li>Sınırsız trafik</li>
                        <li>cPanel kontrol paneli</li>
                        <li>Günlük yedekleme</li>
                        <li>SSL sertifikası</li>
                    </ul>
                </div>
                
                <div class="service-category">
                    <h4>🔒 Güvenlik Hizmetleri</h4>
                    <ul class="service-list">
                        <li>SSL sertifikaları</li>
                        <li>DDoS koruması</li>
                        <li>Güvenlik duvarı</li>
                        <li>Malware taraması</li>
                    </ul>
                </div>
                
                <div class="service-category">
                    <h4>📧 E-posta Hizmetleri</h4>
                    <ul class="service-list">
                        <li>Kurumsal e-posta</li>
                        <li>Webmail erişimi</li>
                        <li>Spam koruması</li>
                        <li>E-posta yönlendirme</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">📱 Hesap Bilgileriniz</div>
            <p>Hesabınıza erişim için aşağıdaki bilgileri kullanabilirsiniz:</p>
            <ul>
                <li><strong>Müşteri Paneli:</strong> {{ $companyInfo['website'] }}/login</li>
                <li><strong>Destek Merkezi:</strong> {{ $companyInfo['website'] }}/support</li>
                <li><strong>Bilgi Bankası:</strong> {{ $companyInfo['website'] }}/kb</li>
            </ul>
        </div>

        <div class="button-container">
            <a href="{{ $companyInfo['website'] }}" class="btn btn-primary">Müşteri Paneline Git</a>
            <a href="{{ $companyInfo['website'] }}/support" class="btn btn-success">Destek Al</a>
        </div>

        <div class="section">
            <div class="section-title">📞 İletişim Kanallarımız</div>
            <p>Size en iyi hizmeti sunabilmek için her zaman yanınızdayız:</p>
            
            <div class="contact-info">
                <div class="subsection-title">🎯 Genel İletişim</div>
                <ul>
                    <li><strong>E-posta:</strong> {{ $companyInfo['email'] }}</li>
                    <li><strong>Telefon:</strong> {{ $companyInfo['phone'] }}</li>
                    <li><strong>Web Sitesi:</strong> {{ $companyInfo['website'] }}</li>
                </ul>
                
                <div class="subsection-title">🆘 Teknik Destek</div>
                <ul>
                    <li><strong>E-posta:</strong> {{ $companyInfo['support_email'] ?? 'destek@whkurumsal.com' }}</li>
                    <li><strong>Canlı Destek:</strong> {{ $companyInfo['website'] }}/chat</li>
                    <li><strong>Destek Merkezi:</strong> {{ $companyInfo['website'] }}/support</li>
                </ul>
            </div>
        </div>

        <div class="highlight">
            <div class="section-title">🎁 Hoş Geldin Hediyesi</div>
            <p>Yeni müşterilerimize özel %20 indirim fırsatı! İlk siparişinizde <strong>WELCOME20</strong> kodunu kullanarak indirimden yararlanabilirsiniz.</p>
        </div>

        <div class="section">
            <div class="section-title">📚 Faydalı Kaynaklar</div>
            <ul>
                <li><strong>Başlangıç Rehberi:</strong> {{ $companyInfo['website'] }}/guide</li>
                <li><strong>SSS:</strong> {{ $companyInfo['website'] }}/faq</li>
                <li><strong>Video Eğitimler:</strong> {{ $companyInfo['website'] }}/videos</li>
                <li><strong>Blog:</strong> {{ $companyInfo['website'] }}/blog</li>
            </ul>
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
            <p>Müşteri Paneli: {{ $companyInfo['website'] }}<br>
            Destek Merkezi: {{ $companyInfo['website'] }}/support</p>
        </div>
    </div>
</body>
</html>
