# Revenue Cache Sistemi

## 📊 Genel Bakış

Revenue raporu için geliştirilen cache sistemi, her sayfa yüklemesinde 2 sorgu yerine cache'den veri alarak performansı önemli ölçüde artırır.

## 🚀 Performans Kazanımları

- **Hızlanma**: 146x daha hızlı (20.30ms → 0.14ms)
- **Sorgu Azaltma**: Her sayfa yüklemesinde 2 veritabanı sorgusu yerine cache'den okuma
- **Otomatik Temizleme**: Veri değiştiğinde cache otomatik olarak temizlenir
- **TTL**: 30 dakika cache süresi

## 🏗️ Mimari

### RevenueCacheService
Ana cache servisi, tüm revenue verilerini yönetir:

```php
use App\Services\RevenueCacheService;

$revenueCache = app(RevenueCacheService::class);

// Aylık revenue verileri
$data = $revenueCache->getMonthlyRevenueData(12);

// MRR verileri
$mrr = $revenueCache->getMRRData();

// MRR hizmet türüne göre
$mrrByType = $revenueCache->getMRRByType();
```

### Cache Keys
- `revenue_data_{period}` - Aylık revenue verileri (12, 6, 3 ay)
- `mrr_current` - Mevcut MRR değeri
- `mrr_by_type` - MRR hizmet türüne göre dağılım
- `monthly_revenue_{YYYY-MM}` - Belirli ay gelir verileri
- `total_revenue_stats` - Toplam gelir istatistikleri

## 🔄 Otomatik Cache Temizleme

### Observer'lar
Aşağıdaki model değişikliklerinde cache otomatik olarak temizlenir:

- **InvoiceObserver**: Fatura oluşturma/güncelleme/silme
- **PaymentObserver**: Ödeme oluşturma/güncelleme/silme  
- **ServiceObserver**: Hizmet oluşturma/güncelleme/silme

### Cache Temizleme Stratejisi
- **Invoice/Payment değişiklikleri**: Tüm revenue cache'leri temizlenir
- **Service değişiklikleri**: Sadece MRR cache'leri temizlenir

## 🛠️ Kullanım

### Controller'larda
```php
use App\Services\RevenueCacheService;

class RevenueReportController extends Controller
{
    public function __construct(private RevenueCacheService $revenueCache) {}

    public function index()
    {
        $data = $this->revenueCache->getMonthlyRevenueData(12);
        return view('reports.revenue', compact('data'));
    }
}
```

### View'larda
```php
@php
$revenueCache = app(\App\Services\RevenueCacheService::class);
$revenueData = $revenueCache->getMonthlyRevenueData(12);
$mrrData = $revenueCache->getMRRData();
@endphp
```

## 📋 Artisan Komutları

### Cache Durumu Kontrolü
```bash
php artisan revenue:cache-status
```

### Cache Temizleme
```bash
# Tüm revenue cache'lerini temizle
php artisan revenue:clear-cache

# Sadece MRR cache'lerini temizle
php artisan revenue:clear-cache --mrr

# Tüm cache'leri temizle
php artisan revenue:clear-cache --all
```

## 🧪 Test

### Unit Testler
```bash
php artisan test tests/Feature/RevenueCacheTest.php
```

### Performans Testi
```bash
php benchmark_revenue_cache.php
```

## 📈 Performans Metrikleri

| İşlem | İlk Çağrı | Cache'den | Hızlanma |
|-------|-----------|-----------|----------|
| Aylık Revenue | 20.30ms | 0.14ms | 146x |
| MRR Hesaplama | 1.51ms | 0.14ms | 11x |
| MRR by Type | 1.81ms | 0.14ms | 13x |
| Bu Ay Gelir | 0.90ms | 0.14ms | 6x |

## 🔧 Konfigürasyon

### Cache TTL
`RevenueCacheService.php` dosyasında TTL değeri değiştirilebilir:

```php
private const CACHE_TTL = 1800; // 30 dakika
```

### Cache Driver
`.env` dosyasında cache driver ayarlanabilir:

```env
CACHE_DRIVER=redis  # veya file, database, memcached
```

## 🚨 Önemli Notlar

1. **Veri Tutarlılığı**: Cache otomatik olarak temizlendiği için veri tutarlılığı garanti edilir
2. **Memory Kullanımı**: Cache boyutu yaklaşık 2.5KB (12 aylık veri için)
3. **TTL**: 30 dakika sonra cache otomatik olarak yenilenir
4. **Observer'lar**: Model değişikliklerinde cache otomatik temizlenir

## 🔍 Debug

### Cache Durumu Kontrolü
```bash
php artisan revenue:cache-status
```

### Manuel Cache Temizleme
```bash
php artisan revenue:clear-cache
```

### Log Kontrolü
Cache işlemleri Laravel log'larında kayıt altına alınır.

## 📝 Gelecek Geliştirmeler

1. **Redis Desteği**: Daha hızlı cache için Redis entegrasyonu
2. **Cache Warming**: Uygulama başlangıcında cache'leri önceden doldurma
3. **Cache Analytics**: Cache hit/miss oranları takibi
4. **Distributed Cache**: Çoklu sunucu desteği

## 🤝 Katkıda Bulunma

1. Cache sisteminde değişiklik yaparken testleri çalıştırın
2. Yeni cache key'leri eklerken dokümantasyonu güncelleyin
3. Performance testlerini yeni değişiklikler için çalıştırın

