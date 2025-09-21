<x-mail::message>
# Vadesi Geçmiş Fatura Hatırlatma

Merhaba,

#{{ $invoice->id }} numaralı faturanızın vadesi geçmiştir.

Tarih: {{ $invoice->issue_date }} | Vade: {{ $invoice->due_date ?? '-' }}
Toplam: ₺{{ number_format($invoice->total,2,',','.') }}

<x-mail::button :url="config('app.url').'/invoices/'.$invoice->id">
Faturayı Görüntüle
</x-mail::button>

Saygılarımızla,
{{ config('app.name') }}
</x-mail::message>
