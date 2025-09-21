# 🚀 WH Kurumsal - Otomatik Deployment Rehberi

## 📋 Hızlı Başlangıç

### 1. Yerel Hazırlık (Bu bilgisayarda)
```bash
# Deployment paketini oluştur
./auto_deploy.sh
```

Bu script:
- ✅ Gereksiz dosyaları temizler
- ✅ Production .env dosyası oluşturur
- ✅ Composer optimizasyonu yapar
- ✅ Laravel cache'lerini oluşturur
- ✅ Deployment paketi hazırlar

### 2. Hosting'e Yükleme

#### A) FTP ile Yükleme
1. `wh_kurumsal_deploy_YYYYMMDD_HHMMSS.tar.gz` dosyasını hosting'e yükle
2. cPanel File Manager ile `public_html` klasörüne çıkar
3. SSH ile bağlan ve `hosting_setup.sh` çalıştır

#### B) SSH ile Direkt Yükleme
```bash
# Hosting'de (cPanel SSH)
cd public_html
./hosting_setup.sh
```

## 🎯 Otomatik Kurulum Özellikleri

### ✅ Hazırlanan Dosyalar
- **auto_deploy.sh** - Yerel deployment hazırlığı
- **hosting_setup.sh** - Hosting kurulum scripti
- **Production .env** - Hosting için optimize edilmiş
- **Optimized vendor/** - Composer bağımlılıkları
- **Laravel cache'leri** - Hızlı yükleme için

### ✅ Otomatik İşlemler
- Gereksiz dosya temizliği
- Environment ayarları
- Composer optimizasyonu
- Laravel cache oluşturma
- Dosya izinleri
- Storage link
- Veritabanı import

## 🔧 Manuel Adımlar (Gerekirse)

### 1. cPanel'de Veritabanı
```
1. MySQL Databases > Yeni veritabanı oluştur
2. Yeni kullanıcı oluştur
3. Kullanıcıyı veritabanına ekle (tüm izinler)
```

### 2. .env Dosyası Düzenleme
```env
APP_URL=https://yourdomain.com
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
MAIL_HOST=your_smtp_host
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
```

### 3. SSL Sertifikası
```
cPanel > SSL/TLS Status > Let's Encrypt aktif et
```

## 🧪 Test Etme

### Temel Testler
- ✅ Ana sayfa: `https://yourdomain.com`
- ✅ Login: `https://yourdomain.com/login`
- ✅ Admin paneli: Admin hesabı ile giriş
- ✅ PDF Export: Raporlardan PDF indirme

### Hesaplar
- **Admin:** admin@whkurumsal.com / admin123
- **Demo:** demo@example.com / demo123

## 🚨 Sorun Giderme

### Yaygın Sorunlar

#### 1. 500 Internal Server Error
```bash
# Dosya izinlerini kontrol et
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod 644 .env
```

#### 2. Database Bağlantı Hatası
```bash
# .env dosyasındaki database bilgilerini kontrol et
# cPanel'de veritabanının oluşturulduğundan emin ol
```

#### 3. PDF Export Çalışmıyor
```bash
# PHP GD extension'ının aktif olduğundan emin ol
# storage/ klasörüne yazma izni ver
```

#### 4. Cache Sorunları
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

## 📞 Destek

Sorun yaşarsanız:
1. `storage/logs/laravel.log` dosyasını kontrol edin
2. cPanel error log'larını inceleyin
3. PHP error log'larını kontrol edin
4. Dosya izinlerini kontrol edin

---

## 🎉 Başarılı Kurulum Sonrası

Kurulum tamamlandıktan sonra:
- ✅ Müşteri yönetimi aktif
- ✅ Fatura/teklif sistemi çalışıyor
- ✅ Hizmet takibi aktif
- ✅ PDF export çalışıyor
- ✅ E-posta bildirimleri aktif
- ✅ Raporlama sistemi çalışıyor
- ✅ Admin paneli erişilebilir
- ✅ Responsive tasarım aktif

**Sistem tamamen hazır! 🚀**
