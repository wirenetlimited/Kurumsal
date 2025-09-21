<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teklif #{{ $quote->number }}</title>
    <style>
        @charset "UTF-8";
        
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }
        
        body { 
            font-family: 'DejaVu Sans', 'Liberation Sans', Arial, sans-serif; 
            background: #ffffff; 
            color: #333333; 
            line-height: 1.4; 
            font-size: 12px; 
        }
        
        .container { 
            width: 210mm; 
            height: 297mm; 
            margin: 0 auto; 
            background: white; 
            padding: 15mm; 
            position: relative;
        }
        
        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #2563eb;
        }
        
        .company-info {
            flex: 1;
        }
        
        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 5px;
        }
        
        .company-tagline {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 10px;
        }
        
        .company-details {
            font-size: 11px;
            color: #64748b;
            line-height: 1.3;
        }
        
        .company-details p {
            margin: 2px 0;
        }
        
        .quote-info {
            text-align: right;
            flex: 0 0 200px;
        }
        
        .quote-title {
            font-size: 24px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 10px;
        }
        
        .quote-number {
            font-size: 16px;
            color: #64748b;
            margin-bottom: 5px;
        }
        
        .quote-date {
            font-size: 12px;
            color: #64748b;
        }
        
        /* Customer Section */
        .customer-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            padding: 15px;
            background: #f8fafc;
            border-radius: 8px;
            border-left: 4px solid #2563eb;
        }
        
        .customer-info {
            flex: 1;
        }
        
        .customer-label {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .customer-name {
            font-size: 16px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 8px;
        }
        
        .customer-details {
            font-size: 11px;
            color: #64748b;
            line-height: 1.4;
        }
        
        .customer-details p {
            margin: 2px 0;
        }
        
        .quote-details {
            flex: 0 0 200px;
            text-align: right;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 11px;
        }
        
        .detail-label {
            color: #64748b;
        }
        
        .detail-value {
            color: #1e293b;
            font-weight: 500;
        }
        
        /* Items Table */
        .items-section {
            margin-bottom: 20px;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .items-table th {
            background: #1e40af;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 11px;
            font-weight: bold;
        }
        
        .items-table td {
            padding: 8px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
        }
        
        .items-table tr:nth-child(even) {
            background: #f8fafc;
        }
        
        .items-table tr:hover {
            background: #f1f5f9;
        }
        
        .item-description {
            font-weight: 500;
            color: #1e293b;
        }
        
        .item-qty, .item-price, .item-total {
            text-align: right;
            color: #64748b;
        }
        
        .item-total {
            font-weight: bold;
            color: #1e293b;
        }
        
        /* Summary Section */
        .summary-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }
        
        .summary-table {
            width: 300px;
            border-collapse: collapse;
        }
        
        .summary-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 12px;
        }
        
        .summary-label {
            text-align: left;
            color: #64748b;
            font-weight: 500;
        }
        
        .summary-value {
            text-align: right;
            color: #1e293b;
            font-weight: bold;
        }
        
        .summary-total {
            background: #1e40af;
            color: white;
            font-size: 14px;
            font-weight: bold;
        }
        
        .summary-total .summary-label {
            color: white;
        }
        
        .summary-total .summary-value {
            color: white;
        }
        
        /* Notes Section */
        .notes-section {
            margin-bottom: 20px;
        }
        
        .notes-content {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #10b981;
            font-size: 11px;
            color: #64748b;
            line-height: 1.4;
        }
        
        /* Terms Section */
        .terms-section {
            margin-bottom: 20px;
        }
        
        .terms-content {
            background: #fef3c7;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #f59e0b;
            font-size: 11px;
            color: #92400e;
            line-height: 1.4;
        }
        
        /* Footer */
        .footer {
            position: absolute;
            bottom: 15mm;
            left: 15mm;
            right: 15mm;
            text-align: center;
            font-size: 10px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
        
        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-draft {
            background: #fef3c7;
            color: #92400e;
        }
        
        .status-sent {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .status-accepted {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .status-expired {
            background: #f3f4f6;
            color: #374151;
        }
        
        /* Utility Classes */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .mb-2 { margin-bottom: 8px; }
        .mb-3 { margin-bottom: 12px; }
        .mt-2 { margin-top: 8px; }
        .mt-3 { margin-top: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <div class="company-name">{{ $site['company_name'] ?? 'Şirket Adı' }}</div>
                <div class="company-tagline">{{ $site['company_tagline'] ?? 'Profesyonel Hizmetler' }}</div>
                <div class="company-details">
                    <p>{{ $site['company_address'] ?? 'Şirket Adresi' }}</p>
                    <p>Tel: {{ $site['contact_phone'] ?? 'Telefon' }} | E-posta: {{ $site['contact_email'] ?? 'E-posta' }}</p>
                    <p>Web: {{ $site['company_website'] ?? 'www.sirket.com' }}</p>
                </div>
            </div>
            <div class="quote-info">
                <div class="quote-title">TEKLİF</div>
                <div class="quote-number">#{{ $quote->number }}</div>
                <div class="quote-date">{{ $quote->quote_date ? $quote->quote_date->format('d.m.Y') : '' }}</div>
            </div>
        </div>
        
        <!-- Customer Section -->
        <div class="customer-section">
            <div class="customer-info">
                <div class="customer-label">Teklif Edilen</div>
                <div class="customer-name">{{ $quote->customer_name ?? 'Müşteri Adı' }}</div>
                <div class="customer-details">
                    @if($quote->customer_email)
                        <p>E-posta: {{ $quote->customer_email }}</p>
                    @endif
                    @if($quote->customer_phone)
                        <p>Telefon: {{ $quote->customer_phone }}</p>
                    @endif
                </div>
            </div>
            <div class="quote-details">
                <div class="detail-row">
                    <span class="detail-label">Durum:</span>
                    <span class="detail-value">
                        <span class="status-badge status-{{ $quote->status }}">
                            @switch($quote->status)
                                @case('draft') Taslak @break
                                @case('sent') Gönderildi @break
                                @case('accepted') Kabul Edildi @break
                                @case('rejected') Reddedildi @break
                                @case('expired') Süresi Doldu @break
                                @default {{ $quote->status }}
                            @endswitch
                        </span>
                    </span>
                </div>
                @if($quote->valid_until)
                <div class="detail-row">
                    <span class="detail-label">Geçerlilik:</span>
                    <span class="detail-value">{{ $quote->valid_until->format('d.m.Y') }}</span>
                </div>
                @endif
                @if($quote->title)
                <div class="detail-row">
                    <span class="detail-label">Başlık:</span>
                    <span class="detail-value">{{ $quote->title }}</span>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Items Section -->
        <div class="items-section">
            <div class="section-title">Teklif Kalemleri</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 50%;">Açıklama</th>
                        <th style="width: 15%;">Miktar</th>
                        <th style="width: 20%;">Birim Fiyat</th>
                        <th style="width: 15%;">Tutar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quote->items as $item)
                    <tr>
                        <td class="item-description">{{ $item->description }}</td>
                        <td class="item-qty">{{ number_format($item->qty, 2, ',', '.') }}</td>
                        <td class="item-price">₺{{ number_format($item->unit_price, 2, ',', '.') }}</td>
                        <td class="item-total">₺{{ number_format($item->line_total, 2, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Summary Section -->
        <div class="summary-section">
            <table class="summary-table">
                <tr>
                    <td class="summary-label">Ara Toplam:</td>
                    <td class="summary-value">₺{{ number_format($quote->subtotal, 2, ',', '.') }}</td>
                </tr>
                @if($quote->discount_amount > 0)
                <tr>
                    <td class="summary-label">İndirim:</td>
                    <td class="summary-value">-₺{{ number_format($quote->discount_amount, 2, ',', '.') }}</td>
                </tr>
                @endif
                @if($quote->tax_rate > 0)
                <tr>
                    <td class="summary-label">KDV ({{ number_format($quote->tax_rate, 0) }}%):</td>
                    <td class="summary-value">₺{{ number_format($quote->tax_amount, 2, ',', '.') }}</td>
                </tr>
                @endif
                <tr class="summary-total">
                    <td class="summary-label">GENEL TOPLAM:</td>
                    <td class="summary-value">₺{{ number_format($quote->total, 2, ',', '.') }}</td>
                </tr>
            </table>
        </div>
        
        <!-- Notes Section -->
        @if($quote->notes)
        <div class="notes-section">
            <div class="section-title">Notlar</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </div>
        @endif
        
        <!-- Terms Section -->
        @if($quote->terms)
        <div class="terms-section">
            <div class="section-title">Şartlar ve Koşullar</div>
            <div class="terms-content">{{ $quote->terms }}</div>
        </div>
        @endif
        
        <!-- Footer -->
        <div class="footer">
            <p>Bu teklif {{ $quote->quote_date ? $quote->quote_date->format('d.m.Y') : '' }} tarihinde hazırlanmıştır.</p>
            <p>{{ $site['company_name'] ?? 'Şirket Adı' }} - {{ $site['contact_phone'] ?? 'Telefon' }} | {{ $site['contact_email'] ?? 'E-posta' }}</p>
        </div>
    </div>
</body>
</html>