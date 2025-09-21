<x-mail::message>
# Teklifiniz Hazır

Merhaba,

#{{ $quote->number }} numaralı fiyat teklifimizi ekte PDF olarak bulabilirsiniz.

<x-mail::button :url="route('quotes.show',$quote)">
Teklifi Görüntüle
</x-mail::button>

Saygılarımızla,
{{ config('app.name') }}
</x-mail::message>
