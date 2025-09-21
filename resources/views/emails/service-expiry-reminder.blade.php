<x-mail::message>
# Hizmet Süresi Dolum Hatırlatması

Sayın {{ $reminderData['customer']->name }},

**{{ ucfirst($reminderData['service']->service_type) }}** hizmetinizin süresi yakında dolacak.

## Hizmet Bilgileri
- **Hizmet Türü:** {{ ucfirst($reminderData['service']->service_type) }}
- **Kalan Gün:** {{ $reminderData['daysRemaining'] }} gün
- **Bitiş Tarihi:** {{ $reminderData['expiryDate'] }}

@if($reminderData['daysRemaining'] <= 7)
⚠️ **UYARI:** Hizmetinizin süresi çok yakında dolacak!
@elseif($reminderData['daysRemaining'] <= 30)
⚠️ **HATIRLATMA:** Hizmetinizin süresi yakında dolacak.
@endif

Hizmetinizin kesintisiz devam etmesi için lütfen yenileme işlemini gerçekleştirin.

<x-mail::button :url="route('services.show', $reminderData['service'])">
Hizmeti Görüntüle
</x-mail::button>

Teşekkürler,<br>
{{ config('app.name') }}
</x-mail::message>
