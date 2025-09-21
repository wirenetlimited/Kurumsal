# Enum Kullanım Örnekleri

Bu dosya, yeni eklenen `ServiceStatus` ve `InvoiceStatus` enum'larının nasıl kullanılacağını göstermektedir.

## ServiceStatus Enum

### Temel Kullanım

```php
use App\Enums\ServiceStatus;

// Enum değerlerini alma
$activeStatus = ServiceStatus::ACTIVE;
$expiredStatus = ServiceStatus::EXPIRED;

// String değerini alma
$statusValue = $activeStatus->value; // 'active'

// Türkçe etiket alma
$statusLabel = $activeStatus->label(); // 'Aktif'

// Renk alma (UI için)
$statusColor = $activeStatus->color(); // 'green'
```

### Model'de Kullanım

```php
use App\Models\Service;

// Enum scope'ları kullanarak sorgulama
$activeServices = Service::active()->get();
$expiredServices = Service::expired()->get();
$suspendedServices = Service::suspended()->get();
$cancelledServices = Service::cancelled()->get();

// Enum değerlerini kontrol etme
$service = Service::find(1);
if ($service->isActive()) {
    // Hizmet aktif
}

if ($service->isExpired()) {
    // Hizmet süresi dolmuş
}

// Accessor'lar ile değer alma
$statusLabel = $service->status_label; // 'Aktif'
$statusColor = $service->status_color; // 'green'
```

### Validation'da Kullanım

```php
use App\Enums\ServiceStatus;

$request->validate([
    'status' => ['required', 'in:' . implode(',', ServiceStatus::values())],
]);
```

## InvoiceStatus Enum

### Temel Kullanım

```php
use App\Enums\InvoiceStatus;

// Enum değerlerini alma
$draftStatus = InvoiceStatus::DRAFT;
$sentStatus = InvoiceStatus::SENT;
$paidStatus = InvoiceStatus::PAID;

// String değerini alma
$statusValue = $sentStatus->value; // 'sent'

// Türkçe etiket alma
$statusLabel = $sentStatus->label(); // 'Gönderildi'

// Renk alma (UI için)
$statusColor = $sentStatus->color(); // 'blue'
```

### Model'de Kullanım

```php
use App\Models\Invoice;

// Enum scope'ları kullanarak sorgulama
$draftInvoices = Invoice::draft()->get();
$sentInvoices = Invoice::sent()->get();
$paidInvoices = Invoice::paid()->get();
$overdueInvoices = Invoice::overdue()->get();
$cancelledInvoices = Invoice::cancelled()->get();

// Özel scope'lar
$unpaidInvoices = Invoice::unpaid()->get(); // sent + overdue

// Enum değerlerini kontrol etme
$invoice = Invoice::find(1);
if ($invoice->isDraft()) {
    // Fatura taslak
}

if ($invoice->isUnpaid()) {
    // Fatura ödenmemiş (sent veya overdue)
}

if ($invoice->canBePaid()) {
    // Fatura ödenebilir
}

// Accessor'lar ile değer alma
$statusLabel = $invoice->status_label; // 'Gönderildi'
$statusColor = $invoice->status_color; // 'blue'
```

### Validation'da Kullanım

```php
use App\Enums\InvoiceStatus;

$request->validate([
    'status' => ['required', 'in:' . implode(',', InvoiceStatus::values())],
]);
```

## View'larda Kullanım

### Service Status Select

```blade
<select name="status">
    @foreach(\App\Enums\ServiceStatus::cases() as $status)
        <option value="{{ $status->value }}" {{ old('status', 'active') === $status->value ? 'selected' : '' }}>
            @switch($status)
                @case(\App\Enums\ServiceStatus::ACTIVE)
                    ✅ Aktif
                    @break
                @case(\App\Enums\ServiceStatus::SUSPENDED)
                    ⏸️ Askıya Alınmış
                    @break
                @case(\App\Enums\ServiceStatus::CANCELLED)
                    ❌ İptal Edilmiş
                    @break
                @case(\App\Enums\ServiceStatus::EXPIRED)
                    ⏰ Süresi Dolmuş
                    @break
            @endswitch
        </option>
    @endforeach
</select>
```

### Status Badge

```blade
@php
    $status = $service->status;
    $color = $status->color();
    $label = $status->label();
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $color }}-100 text-{{ $color }}-800">
    {{ $label }}
</span>
```

## Avantajlar

1. **Type Safety**: Yanlış status değeri girilmesi önlenir
2. **IDE Support**: Otomatik tamamlama ve refactoring desteği
3. **Merkezi Yönetim**: Tüm status değerleri tek yerde
4. **Kolay Bakım**: Değişiklikler sadece enum'da yapılır
5. **Tutarlılık**: Tüm uygulamada aynı değerler kullanılır
6. **Validation**: Otomatik validation kuralları
7. **Localization**: Türkçe etiketler hazır
8. **UI Support**: Renk ve stil bilgileri dahil

## Migration Gereksinimleri

Enum'ları kullanmak için mevcut veritabanı yapısında değişiklik yapmaya gerek yoktur. Mevcut string değerler (`'active'`, `'sent'`, vb.) enum case'leri ile uyumludur.

## Test

Enum'ların doğru çalıştığını test etmek için:

```bash
php artisan test tests/Unit/EnumsTest.php
```

## Gelecek Geliştirmeler

1. **Quote Status Enum**: Teklif durumları için enum eklenebilir
2. **Payment Status Enum**: Ödeme durumları için enum eklenebilir
3. **Customer Status Enum**: Müşteri durumları için enum eklenebilir
4. **Service Type Enum**: Hizmet türleri için enum eklenebilir
