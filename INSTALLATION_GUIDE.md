# WH Kurumsal - Kurulum Rehberi

## 🚀 Hızlı Kurulum (Önerilen)

### 1. Dosyaları Yükleyin
- Tüm proje dosyalarını cPanel'de `public_html` klasörüne yükleyin
- `.git`, `node_modules`, `vendor` klasörlerini hariç tutun

### 2. Veritabanı Oluşturun
- cPanel > MySQL Databases
- Yeni veritabanı oluşturun (örn: `wh_kurumsal`)
- Yeni kullanıcı oluşturun ve veritabanına ekleyin

### 3. SQL Dosyasını Import Edin
- cPanel > phpMyAdmin
- Oluşturduğunuz veritabanını seçin
- `Import` sekmesine tıklayın
- `database/wh_kurumsal.sql` dosyasını seçin ve import edin

### 4. Kurulum Sihirbazını Çalıştırın
- Tarayıcınızda `https://yourdomain.com/install` adresine gidin
- Adım adım kurulumu takip edin:
  1. **Gereksinimler Kontrolü** - Sistem uyumluluğu
  2. **Database Ayarları** - Veritabanı bağlantısı
  3. **Migration** - Tablolar oluşturulur
  4. **Admin Kullanıcı** - Yönetici hesabı

### 5. Kurulum Tamamlandı!
- Sistem otomatik olarak `/install` sayfasını devre dışı bırakır
- Admin hesabınızla giriş yapabilirsiniz

---

## 🔧 Manuel Kurulum

### Gereksinimler
- **PHP:** 8.2 veya üzeri
- **MySQL:** 5.7 veya üzeri
- **PHP Extensions:** pdo_mysql, mbstring, openssl, tokenizer, xml, ctype, json, bcmath, fileinfo, gd

### Adım 1: Dosya Yükleme
```bash
# FTP ile dosyaları yükleyin
# veya cPanel File Manager kullanın
```

### Adım 2: Composer Bağımlılıkları
```bash
# SSH ile cPanel'e bağlanın
cd public_html
composer install --optimize-autoloader --no-dev
```

### Adım 3: Environment Dosyası
```bash
cp .env.example .env
```

`.env` dosyasını düzenleyin:
```env
APP_NAME="WH Kurumsal"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### Adım 4: Laravel Komutları
```bash
# Application key oluşturun
php artisan key:generate

# Cache'i temizleyin
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Database migration
php artisan migrate

# Seed'leri çalıştırın
php artisan db:seed

# Storage link oluşturun
php artisan storage:link
```

### Adım 5: Dosya İzinleri
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod 644 .env
```

---

## 📋 Kurulum Kontrol Listesi

### ✅ Ön Kurulum
- [ ] PHP 8.2+ kontrol edildi
- [ ] Gerekli PHP extension'ları aktif
- [ ] MySQL veritabanı oluşturuldu
- [ ] Dosyalar yüklendi

### ✅ Kurulum Sırasında
- [ ] Gereksinimler kontrol edildi
- [ ] Database bağlantısı test edildi
- [ ] Migration'lar çalıştırıldı
- [ ] Admin kullanıcısı oluşturuldu
- [ ] Kurulum tamamlandı işareti konuldu

### ✅ Kurulum Sonrası
- [ ] Admin paneline giriş yapıldı
- [ ] PDF export test edildi
- [ ] E-posta ayarları yapılandırıldı
- [ ] Yedekleme planı oluşturuldu

---

## 🎯 Kurulum Yöntemleri

### Yöntem 1: Otomatik Kurulum (Önerilen)
```
1. Dosyaları yükle
2. SQL import et
3. /install adresine git
4. Adımları takip et
```

### Yöntem 2: Manuel Kurulum
```
1. Dosyaları yükle
2. Composer install
3. .env ayarla
4. Migration çalıştır
5. Admin oluştur
```

### Yöntem 3: SQL + Kurulum Sihirbazı
```
1. Dosyaları yükle
2. SQL import et
3. /install ile admin oluştur
```

---

## 🔍 Sorun Giderme

### Yaygın Sorunlar

#### 1. "500 Internal Server Error"
```bash
# .htaccess dosyasını kontrol edin
# PHP error log'larını inceleyin
# Dosya izinlerini kontrol edin
```

#### 2. "Database Connection Failed"
```bash
# .env dosyasındaki database bilgilerini kontrol edin
# cPanel'de database oluşturduğunuzdan emin olun
# Kullanıcı izinlerini kontrol edin
```

#### 3. "PDF Export Çalışmıyor"
```bash
# PHP GD extension'ının aktif olduğundan emin olun
# storage/ klasörüne yazma izni verin
```

#### 4. "Composer Install Hatası"
```bash
# PHP memory limit'i artırın
# SSH ile bağlanıp manuel çalıştırın
```

### Log Dosyaları
- **Laravel Log:** `storage/logs/laravel.log`
- **PHP Error Log:** cPanel > Error Log
- **Apache Error Log:** cPanel > Error Log

---

## 📞 Destek

### Kurulum Öncesi
1. Hosting sağlayıcınızla PHP 8.2+ desteğini kontrol edin
2. Gerekli PHP extension'larının aktif olduğundan emin olun
3. MySQL veritabanı oluşturma izniniz olduğunu kontrol edin

### Kurulum Sırasında
1. Adım adım rehberi takip edin
2. Hata mesajlarını not alın
3. Gerekirse hosting sağlayıcınızla iletişime geçin

### Kurulum Sonrası
1. Sistem yedeklerini alın
2. Güvenlik ayarlarını kontrol edin
3. SSL sertifikası aktif edin

---

## 🎉 Kurulum Tamamlandı!

Kurulum başarıyla tamamlandıktan sonra:

1. **Admin Paneli:** `https://yourdomain.com/login`
2. **Dashboard:** Ana sayfa
3. **Raporlar:** `/reports` adresinden erişim
4. **PDF Export:** Tüm raporlarda mevcut
5. **E-posta:** Admin panelinden yapılandırma

**Sistem özellikleri:**
- ✅ Müşteri yönetimi
- ✅ Fatura/teklif sistemi
- ✅ Hizmet takibi
- ✅ PDF export (CSV + PDF)
- ✅ E-posta bildirimleri
- ✅ Raporlama sistemi
- ✅ Admin paneli
- ✅ Responsive tasarım

