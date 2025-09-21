# WH Kurumsal - FTP Yükleme Rehberi

## 🚀 **FTP Yükleme Adımları**

### **1. Dosyaları Hazırlama**

#### ✅ **Yüklenecek Dosyalar:**
```
📁 app/                    (Laravel uygulama dosyaları)
📁 bootstrap/              (Laravel bootstrap)
📁 config/                 (Konfigürasyon dosyaları)
📁 database/               (Migration'lar ve SQL)
📁 public/                 (Web erişilebilir dosyalar)
📁 resources/              (View'lar ve assets)
📁 routes/                 (Route tanımları)
📁 storage/                (Boş klasör yapısı)
📁 vendor/                 (Composer paketleri - YÜKLENECEK)
📄 .env.example            (Environment örneği)
📄 .htaccess               (Apache yapılandırması)
📄 artisan                 (Laravel komut satırı)
📄 composer.json           (Composer bağımlılıkları)
📄 composer.lock           (Composer kilit dosyası)
📄 database/wh_kurumsal.sql (Veritabanı şeması)
```

#### ❌ **Yüklenmeyecek Dosyalar:**
```
📁 .git/                   (Git geçmişi)
📁 node_modules/           (npm paketleri)
📄 .env                    (Hassas bilgiler)
📄 database/database.sqlite (SQLite dosyası)
📄 storage/logs/           (Log dosyaları)
📄 storage/framework/cache/ (Cache dosyaları)
```

### **2. FTP Yükleme Sırası**

#### **Adım 1: Ana Klasörleri Yükle**
```
1. app/
2. bootstrap/
3. config/
4. database/
5. public/
6. resources/
7. routes/
8. storage/
9. vendor/
```

#### **Adım 2: Kök Dosyaları Yükle**
```
1. .env.example
2. .htaccess
3. artisan
4. composer.json
5. composer.lock
```

#### **Adım 3: SQL Dosyasını Hazırla**
```
database/wh_kurumsal.sql dosyasını ayrıca yedekleyin
```

### **3. cPanel'de Yapılacaklar**

#### **3.1 Veritabanı Oluşturma**
```
1. cPanel > MySQL Databases
2. Yeni veritabanı oluşturun (örn: wh_kurumsal)
3. Yeni kullanıcı oluşturun
4. Kullanıcıyı veritabanına ekleyin (tüm izinler)
```

#### **3.2 SQL Import**
```
1. cPanel > phpMyAdmin
2. Oluşturduğunuz veritabanını seçin
3. Import sekmesine tıklayın
4. database/wh_kurumsal.sql dosyasını seçin
5. Import edin
```

#### **3.3 Dosya İzinleri**
```bash
# SSH ile bağlanın veya File Manager kullanın
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod 644 .env.example
```

### **4. Kurulum Sihirbazı**

#### **4.1 Environment Dosyası**
```bash
# .env.example dosyasını .env olarak kopyalayın
cp .env.example .env
```

#### **4.2 .env Dosyasını Düzenleyin**
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

CACHE_STORE=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

#### **4.3 Kurulum Sihirbazını Çalıştırın**
```
1. https://yourdomain.com/install adresine gidin
2. Adım adım kurulumu takip edin:
   - Gereksinimler kontrolü
   - Database ayarları
   - Migration
   - Admin kullanıcı oluşturma
```

### **5. Composer Bağımlılıkları**

#### **5.1 SSH ile Yükleme (Önerilen)**
```bash
# SSH ile cPanel'e bağlanın
cd public_html
composer install --optimize-autoloader --no-dev
```

#### **5.2 Manuel Yükleme**
```
vendor/ klasörünü FTP ile yükleyin
```

### **6. Laravel Komutları**

#### **6.1 Application Key**
```bash
php artisan key:generate
```

#### **6.2 Cache Temizleme**
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

#### **6.3 Storage Link**
```bash
php artisan storage:link
```

### **7. Test Etme**

#### **7.1 Ana Sayfa Testi**
```
https://yourdomain.com
```

#### **7.2 Admin Paneli Testi**
```
https://yourdomain.com/login
```

#### **7.3 PDF Export Testi**
```
https://yourdomain.com/reports/revenue
Export > PDF İndir butonuna tıklayın
```

### **8. Güvenlik Kontrolleri**

#### **8.1 Dosya İzinleri**
```bash
chmod 644 .env
chmod 644 composer.json
chmod 644 composer.lock
```

#### **8.2 SSL Sertifikası**
```
cPanel > SSL/TLS Status
Let's Encrypt veya ücretli SSL aktif edin
```

#### **8.3 Backup**
```
cPanel > Backup
Düzenli backup planı oluşturun
```

### **9. Sorun Giderme**

#### **9.1 500 Internal Server Error**
```
- .htaccess dosyasını kontrol edin
- PHP error log'larını inceleyin
- Dosya izinlerini kontrol edin
```

#### **9.2 Database Bağlantı Hatası**
```
- .env dosyasındaki database bilgilerini kontrol edin
- cPanel'de database oluşturduğunuzdan emin olun
- Kullanıcı izinlerini kontrol edin
```

#### **9.3 PDF Export Çalışmıyor**
```
- PHP GD extension'ının aktif olduğundan emin olun
- storage/ klasörüne yazma izni verin
```

### **10. Kurulum Tamamlandı!**

Kurulum başarıyla tamamlandıktan sonra:

✅ **Admin Paneli:** `https://yourdomain.com/login`  
✅ **Dashboard:** Ana sayfa  
✅ **Raporlar:** `/reports` adresinden erişim  
✅ **PDF Export:** Tüm raporlarda mevcut  
✅ **E-posta:** Admin panelinden yapılandırma  

**Sistem özellikleri:**
- ✅ Müşteri yönetimi
- ✅ Fatura/teklif sistemi
- ✅ Hizmet takibi
- ✅ PDF export (CSV + PDF)
- ✅ E-posta bildirimleri
- ✅ Raporlama sistemi
- ✅ Admin paneli
- ✅ Responsive tasarım
- ✅ Otomatik kurulum sistemi

---

## 📞 **Destek**

Sorun yaşarsanız:
1. cPanel error log'larını kontrol edin
2. Laravel log'larını inceleyin: `storage/logs/laravel.log`
3. PHP error log'larını kontrol edin
4. Kurulum rehberini tekrar gözden geçirin
