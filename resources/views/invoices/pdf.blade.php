<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fatura #{{ $invoice->invoice_number ?? $invoice->id }}</title>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none !important; }
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f8fafc;
            color: #1e293b;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .header p {
            margin: 10px 0 0 0;
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .company-info {
            background: #f8fafc;
            padding: 25px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .company-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        
        .info-section h3 {
            margin: 0 0 15px 0;
            color: #475569;
            font-size: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .info-label {
            color: #64748b;
            font-weight: 500;
        }
        
        .info-value {
            color: #1e293b;
            font-weight: 600;
        }
        
        .invoice-items {
            padding: 25px;
        }
        
        .items-header {
            margin-bottom: 20px;
        }
        
        .items-header h3 {
            margin: 0;
            color: #475569;
            font-size: 1.2rem;
            font-weight: 600;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .items-table th {
            background: #f1f5f9;
            color: #475569;
            font-weight: 600;
            text-align: left;
            padding: 15px 12px;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .items-table td {
            padding: 15px 12px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }
        
        .items-table tr:last-child td {
            border-bottom: none;
        }
        
        .items-table tr:hover {
            background: #f8fafc;
        }
        
        .description-cell {
            max-width: 250px;
        }
        
        .description-main {
            font-weight: 500;
            color: #1e293b;
            margin-bottom: 4px;
        }
        
        .description-sub {
            font-size: 0.8rem;
            color: #64748b;
        }
        
        .number-cell {
            text-align: center;
            font-weight: 500;
        }
        
        .price-cell {
            text-align: right;
            font-weight: 500;
        }
        
        .total-cell {
            text-align: right;
            font-weight: 600;
            color: #059669;
        }
        
        .financial-summary {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            padding: 25px;
            border-top: 1px solid #e2e8f0;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #bae6fd;
        }
        
        .summary-item:last-child {
            border-bottom: none;
        }
        
        .summary-label {
            color: #0369a1;
            font-weight: 500;
        }
        
        .summary-value {
            color: #0c4a6e;
            font-weight: 600;
        }
        
        .grand-total {
            grid-column: 1 / -1;
            padding: 20px 0 0 0;
            border-top: 2px solid #0ea5e9;
            margin-top: 15px;
        }
        
        .grand-total .summary-label {
            font-size: 1.2rem;
            font-weight: 700;
            color: #0c4a6e;
        }
        
        .grand-total .summary-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0c4a6e;
        }
        
        .footer {
            background: #1e293b;
            color: white;
            padding: 25px;
            text-align: center;
        }
        
        .footer h4 {
            margin: 0 0 15px 0;
            font-size: 1.1rem;
            font-weight: 600;
        }
        
        .footer p {
            margin: 5px 0;
            opacity: 0.8;
            font-size: 0.9rem;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-paid {
            background: #dcfce7;
            color: #166534;
        }
        
        .status-sent {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .status-overdue {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .status-draft {
            background: #f3f4f6;
            color: #374151;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #059669;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.2s;
        }
        
        .print-button:hover {
            background: #047857;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        
        @media (max-width: 768px) {
            .company-grid,
            .summary-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .items-table {
                font-size: 0.8rem;
            }
            
            .items-table th,
            .items-table td {
                padding: 10px 8px;
            }
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-button no-print">
        🖨️ Yazdır
    </button>
    
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <h1>🧾 FATURA</h1>
            <p>{{ \App\Models\Setting::get('company_name', 'WH Kurumsal') }}</p>
        </div>
        
        <!-- Company Information -->
        <div class="company-info">
            <div class="company-grid">
                <div class="info-section">
                    <h3>📋 Fatura Bilgileri</h3>
                    <div class="info-item">
                        <span class="info-label">Fatura No:</span>
                        <span class="info-value">#{{ $invoice->invoice_number ?? $invoice->id }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Fatura Tarihi:</span>
                        <span class="info-value">{{ $invoice->issue_date?->format('d.m.Y') ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Vade Tarihi:</span>
                        <span class="info-value">{{ $invoice->due_date?->format('d.m.Y') ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Durum:</span>
                        <span class="status-badge 
                            @if($invoice->status->value === 'paid') status-paid
                            @elseif($invoice->status->value === 'sent') status-sent
                            @elseif($invoice->status->value === 'overdue') status-overdue
                            @else status-draft @endif">
                            @if($invoice->status->value === 'paid') ✅ Ödendi
                            @elseif($invoice->status->value === 'sent') 📤 Gönderildi
                            @elseif($invoice->status->value === 'overdue') ⏰ Gecikmiş
                            @elseif($invoice->status->value === 'draft') 📝 Taslak
                            @else {{ ucfirst($invoice->status->value ?? $invoice->status) }}
                            @endif
                        </span>
                    </div>
                </div>
                
                <div class="info-section">
                    <h3>👤 Müşteri Bilgileri</h3>
                    <div class="info-item">
                        <span class="info-label">Müşteri:</span>
                        <span class="info-value">
                            @if($invoice->customer)
                                {{ $invoice->customer->name }}@if($invoice->customer->surname) {{ ' ' . $invoice->customer->surname }}@endif
                            @else
                                -
                            @endif
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Para Birimi:</span>
                        <span class="info-value">{{ $invoice->currency ?? 'TRY' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Oluşturulma:</span>
                        <span class="info-value">{{ $invoice->created_at?->format('d.m.Y H:i') ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Invoice Items -->
        @if($invoice->items && $invoice->items->count() > 0)
        <div class="invoice-items">
            <div class="items-header">
                <h3>🛒 Fatura Kalemleri</h3>
            </div>
            
            <table class="items-table">
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
                        <td class="description-cell">
                            <div class="description-main">{{ $item->description }}</div>
                            @if($item->service)
                                <div class="description-sub">{{ ucfirst($item->service->service_type) }}</div>
                            @endif
                        </td>
                        <td class="number-cell">{{ $item->qty }}</td>
                        <td class="price-cell">{{ $invoice->currency ?? '₺' }}{{ number_format($item->unit_price, 2, ',', '.') }}</td>
                        <td class="number-cell">%{{ $item->tax_rate }}</td>
                        <td class="total-cell">{{ $invoice->currency ?? '₺' }}{{ number_format($item->line_total, 2, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        
        <!-- Financial Summary -->
        <div class="financial-summary">
            <div class="summary-grid">
                <div class="summary-item">
                    <span class="summary-label">Ara Toplam:</span>
                    <span class="summary-value">{{ $invoice->currency ?? '₺' }}{{ number_format($invoice->subtotal ?? 0, 2, ',', '.') }}</span>
                </div>
                
                <div class="summary-item">
                    <span class="summary-label">KDV Toplam:</span>
                    <span class="summary-value">{{ $invoice->currency ?? '₺' }}{{ number_format($invoice->tax_total ?? 0, 2, ',', '.') }}</span>
                </div>
                
                <div class="summary-item grand-total">
                    <span class="summary-label">Genel Toplam:</span>
                    <span class="summary-value">{{ $invoice->currency ?? '₺' }}{{ number_format($invoice->total ?? 0, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <h4>{{ \App\Models\Setting::get('site_name', 'WH Kurumsal') }}</h4>
            <p>{{ \App\Models\Setting::get('contact_address', 'Adres bilgisi') }}</p>
            <p>{{ \App\Models\Setting::get('contact_email', 'info@example.com') }} | {{ \App\Models\Setting::get('contact_phone', '+90 xxx xxx xx xx') }}</p>
            <p>Bu fatura {{ \App\Models\Setting::get('site_name', 'WH Kurumsal') }} tarafından oluşturulmuştur.</p>
        </div>
        
        <!-- Payment Information -->
        @if(\App\Models\Setting::get('bank_name') || \App\Models\Setting::get('bank_iban'))
        <div style="background: #f8fafc; padding: 25px; border-top: 1px solid #e2e8f0;">
            <h3 style="margin: 0 0 20px 0; color: #374151; font-size: 1.1rem; font-weight: 600; text-align: center;">Ödeme Bilgileri</h3>
            
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; max-width: 800px; margin: 0 auto;">
                @if(\App\Models\Setting::get('bank_name'))
                <div style="background: white; padding: 16px; border-radius: 8px; border: 1px solid #e5e7eb; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);">
                    <div style="font-size: 0.75rem; color: #6b7280; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 500;">Banka Adı</div>
                    <div style="color: #111827; font-weight: 600; font-size: 0.9rem;">{{ \App\Models\Setting::get('bank_name') }}</div>
                </div>
                @endif
                
                @if(\App\Models\Setting::get('bank_iban'))
                <div style="background: white; padding: 16px; border-radius: 8px; border: 1px solid #e5e7eb; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);">
                    <div style="font-size: 0.75rem; color: #6b7280; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 500;">IBAN</div>
                    <div style="color: #111827; font-weight: 600; font-size: 0.9rem; white-space: nowrap;">{{ \App\Models\Setting::get('bank_iban') }}</div>
                </div>
                @endif
                
                @if(\App\Models\Setting::get('tax_number'))
                <div style="background: white; padding: 16px; border-radius: 8px; border: 1px solid #e5e7eb; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);">
                    <div style="font-size: 0.75rem; color: #6b7280; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 500;">Vergi Numarası</div>
                    <div style="color: #111827; font-weight: 600; font-size: 0.9rem;">{{ \App\Models\Setting::get('tax_number') }}</div>
                </div>
                @endif
                
                @if(\App\Models\Setting::get('payment_methods'))
                <div style="background: white; padding: 16px; border-radius: 8px; border: 1px solid #e5e7eb; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);">
                    <div style="font-size: 0.75rem; color: #6b7280; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 500;">Ödeme Yöntemleri</div>
                    <div style="color: #111827; font-weight: 500; font-size: 0.85rem;">
                        @php
                            $methods = json_decode(\App\Models\Setting::get('payment_methods'), true);
                            $methodLabels = [
                                'bank_transfer' => 'Banka Havalesi',
                                'credit_card' => 'Kredi Kartı',
                                'cash' => 'Nakit',
                                'online_payment' => 'Online Ödeme',
                                'check' => 'Çek',
                                'mobile_payment' => 'Mobil Ödeme'
                            ];
                        @endphp
                        @if(is_array($methods))
                            @foreach($methods as $method)
                                <div style="margin: 2px 0; padding: 2px 0;">{{ $methodLabels[$method] ?? ucfirst($method) }}</div>
                            @endforeach
                        @else
                            {{ \App\Models\Setting::get('payment_methods') }}
                        @endif
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Quick Payment Button (Future Feature) -->
            <div style="text-align: center; margin-top: 20px;">
                <div style="background: #f3f4f6; color: #6b7280; padding: 10px 20px; border-radius: 6px; display: inline-block; font-weight: 500; font-size: 0.85rem; border: 1px solid #d1d5db;">
                    Online Ödeme Sistemi
                </div>
                <p style="margin: 6px 0 0 0; font-size: 0.75rem; color: #9ca3af;">
                    Yakında aktif olacak
                </p>
            </div>
        </div>
        @endif
    </div>
</body>
</html>
