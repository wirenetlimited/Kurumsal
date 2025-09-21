<x-mail::message>
# Hizmet Yenileme Hatırlatması

Merhaba,

{{ $service->service_type === 'domain' ? ($service->domain->domain_name ?? 'Domain') : ucfirst($service->service_type) }} hizmetinizin süresi {{ $daysLeft }} gün içinde dolacak.

Son tarih: {{ \Carbon\Carbon::parse($service->end_date)->format('d.m.Y') }}

<x-mail::button :url="config('app.url').'/services/'.$service->id">
Hizmeti Görüntüle
</x-mail::button>

Saygılarımızla,
{{ config('app.name') }}
</x-mail::message>
