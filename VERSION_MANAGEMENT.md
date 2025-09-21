# 🚀 Sürüm Yönetimi Kılavuzu

Bu doküman, WH Kurumsal projesinde sürüm yönetimi ve güncelleme süreçlerini açıklar.

## 📋 Genel Bakış

Projemiz **Semantic Versioning (SemVer)** standardını kullanır:
- **MAJOR.MINOR.PATCH** formatında
- **MAJOR**: Uyumsuz API değişiklikleri
- **MINOR**: Geriye uyumlu yeni özellikler  
- **PATCH**: Geriye uyumlu hata düzeltmeleri

## 🔧 Sürüm Yönetimi Araçları

### 1. Artisan Komutları

#### Mevcut Sürüm Bilgilerini Görüntüleme
```bash
php artisan app:version
# veya
php artisan app:version show
```

#### Sürüm Güncelleme
```bash
php artisan app:version update
```

#### Changelog Görüntüleme
```bash
php artisan app:version changelog
```

### 2. Konfigürasyon Dosyası

Sürüm bilgileri `config/version.php` dosyasında saklanır:

```php
return [
    'version' => env('APP_VERSION', '2.0.0'),
    'release_date' => env('APP_RELEASE_DATE', '2024-12-29'),
    'codename' => env('APP_VERSION_CODENAME', 'Enterprise'),
    'description' => env('APP_VERSION_DESCRIPTION', 'Major Release - Sistem Yeniden Yapılandırması'),
    // ... diğer bilgiler
];
```

### 3. Environment Variables

`.env` dosyasında sürüm bilgilerini özelleştirebilirsiniz:

```env
APP_VERSION=2.1.0
APP_RELEASE_DATE=2024-12-30
APP_VERSION_CODENAME=Innovation
APP_VERSION_DESCRIPTION=Yeni özellikler ve iyileştirmeler
```

## 📝 Sürüm Güncelleme Süreci

### 1. Yeni Sürüm Planlama
- [ ] Sürüm numarasını belirle (MAJOR.MINOR.PATCH)
- [ ] Yayın tarihini belirle
- [ ] Kod adını belirle (opsiyonel)
- [ ] Sürüm açıklamasını yaz

### 2. Sürüm Güncelleme
```bash
php artisan app:version update
```

Komut size şunları soracak:
- Yeni sürüm numarası
- Yayın tarihi
- Kod adı
- Sürüm açıklaması

### 3. Otomatik Güncellemeler
Komut şunları otomatik olarak yapar:
- ✅ `config/version.php` dosyasını günceller
- ✅ `CHANGELOG.md` dosyasına yeni giriş ekler
- ✅ Sürüm bilgilerini doğrular

### 4. Manuel Güncellemeler (Gerekirse)
Eğer otomatik güncelleme yeterli değilse:

#### Config Dosyası
```php
// config/version.php
'version' => env('APP_VERSION', '2.1.0'),
'features' => [
    'Yeni özellik 1',
    'Yeni özellik 2',
    // ...
],
'improvements' => [
    'İyileştirme 1',
    'İyileştirme 2',
    // ...
],
```

#### CHANGELOG.md
```markdown
## [2.1.0] - 2024-12-30

### 🚀 **Yeni Özellikler ve İyileştirmeler**
- **Yeni Özellik 1**: Açıklama
- **Yeni Özellik 2**: Açıklama

### 🎯 **UI/UX İyileştirmeleri**
- İyileştirme 1
- İyileştirme 2

---
```

## 🎯 Sürüm Türleri ve Örnekler

### Major Release (2.0.0)
- Büyük mimari değişiklikler
- API uyumsuzlukları
- Yeni teknoloji stack'i

### Minor Release (2.1.0)
- Yeni özellikler
- UI/UX iyileştirmeleri
- Performans optimizasyonları

### Patch Release (2.0.1)
- Hata düzeltmeleri
- Güvenlik güncellemeleri
- Küçük iyileştirmeler

## 🔍 Sürüm Bilgilerini Görüntüleme

### 1. Footer'da
Her sayfada footer'da mevcut sürüm numarası görünür.

### 2. Sürüm Notları Modal'ı
Footer'daki "Sürüm Notları" linkine tıklayarak hızlı bakış yapabilirsiniz.

### 3. Tam Changelog
`/changelog` sayfasında tüm sürüm notlarını görebilirsiniz.

### 4. Dashboard Widget
Dashboard'da sürüm bilgileri widget'ı ekleyebilirsiniz:

```blade
<x-version-widget :showDetails="true" />
```

## 🛠️ Helper Fonksiyonları

### VersionHelper Sınıfı
```php
use App\Helpers\VersionHelper;

// Temel bilgiler
$version = VersionHelper::getVersion();           // "2.0.0"
$versionWithPrefix = VersionHelper::getVersionWithPrefix(); // "v2.0.0"
$releaseDate = VersionHelper::getReleaseDate();   // "2024-12-29"
$codename = VersionHelper::getCodename();         // "Enterprise"

// Formatlanmış bilgi
$formatted = VersionHelper::getFormattedVersion(); // "v2.0.0 (Enterprise) - 2024-12-29"

// Sürüm kontrolü
$isStable = VersionHelper::isStable();           // true/false
$isDev = VersionHelper::isDevelopment();         // true/false

// Özellikler
$features = VersionHelper::getFeatures();
$improvements = VersionHelper::getImprovements();
$bugFixes = VersionHelper::getBugFixes();

// Changelog
$changelog = VersionHelper::getChangelogContent();
$recentEntries = VersionHelper::getRecentChangelogEntries(5);

// Sürüm karşılaştırma
$comparison = VersionHelper::compareVersions('2.0.0', '2.1.0'); // 1, -1, 0

// HTML Badge
$badge = VersionHelper::getVersionBadge();
```

## 📱 Frontend Entegrasyonu

### JavaScript'te Sürüm Bilgisi
```javascript
// Sürüm numarasını al
const version = document.querySelector('[data-version]').getAttribute('data-version');

// Sürüm notları modal'ını göster
showChangelog();
```

### Blade Template'lerde
```blade
{{-- Sürüm numarası --}}
v{{ config('version.version') }}

{{-- Sürüm açıklaması --}}
{{ config('version.description') }}

{{-- Yayın tarihi --}}
{{ config('version.release_date') }}

{{-- Kod adı --}}
{{ config('version.codename') }}
```

## 🔄 Sürüm Güncelleme Kontrol Listesi

### Geliştirme Aşaması
- [ ] Yeni özellikler tamamlandı
- [ ] Testler geçti
- [ ] Dokümantasyon güncellendi
- [ ] Breaking changes belgelendi

### Sürüm Güncelleme
- [ ] `php artisan app:version update` çalıştırıldı
- [ ] Sürüm numarası doğru
- [ ] Yayın tarihi güncel
- [ ] Açıklama detaylı

### Deployment
- [ ] Kod production'a deploy edildi
- [ ] Sürüm bilgileri doğru görünüyor
- [ ] Changelog erişilebilir
- [ ] Footer'da sürüm numarası güncel

### Post-Release
- [ ] Kullanıcı bildirimleri gönderildi
- [ ] Dokümantasyon güncellendi
- [ ] Sonraki sürüm planlandı

## 🚨 Önemli Notlar

1. **Sürüm numaraları asla geri alınmamalı**
2. **Her sürüm için CHANGELOG.md güncellenmelidir**
3. **Breaking changes detaylı olarak belgelenmelidir**
4. **Sürüm güncellemeleri test ortamında denenmelidir**
5. **Production'da sürüm bilgileri doğrulanmalıdır**

## 📞 Destek

Sürüm yönetimi ile ilgili sorularınız için:
- **Geliştirme Ekibi**: development@whkurumsal.com
- **Dokümantasyon**: docs@whkurumsal.com
- **Teknik Destek**: support@whkurumsal.com

---

*Bu kılavuz her sürüm güncellemesinde güncellenir.*
