# WH Kurumsal - FTP Yükleme Kontrol Listesi

## ✅ **HAZIR DOSYALAR**

### **1. SQL Dosyası**
- ✅ `database/wh_kurumsal.sql` - Güncel veritabanı şeması
- ✅ Tüm tablolar ve ilişkiler dahil
- ✅ Varsayılan ayarlar eklenmiş

### **2. Kurulum Sihirbazı**
- ✅ `/install` route'ları aktif
- ✅ Kurulum controller'ı hazır
- ✅ Kurulum view'ları hazır

### **3. PDF Export Sistemi**
- ✅ Revenue raporları PDF export
- ✅ Reconciliation raporları PDF export  
- ✅ Customer raporları PDF export
- ✅ DomPDF entegrasyonu tamamlandı

## 📋 **FTP YÜKLEME SIRASI**

### **Adım 1: Ana Klasörler**
```
1. app/                    (Laravel uygulama)
2. bootstrap/              (Laravel bootstrap)
3. config/                 (Konfigürasyon)
4. database/               (Migration'lar + SQL)
5. public/                 (Web erişilebilir)
6. resources/              (View'lar + assets)
7. routes/                 (Route tanımları)
8. storage/                (Boş klasör yapısı)
9. vendor/                 (Composer paketleri)
```

### **Adım 2: Kök Dosyalar**
```
1. .env.example            (Environment örneği)
2. .htaccess               (Apache yapılandırması)
3. artisan                 (Laravel komut satırı)
4. composer.json           (Composer bağımlılıkları)
5. composer.lock           (Composer kilit dosyası)
```

### **Adım 3: Özel Dosyalar**
```
1. database/wh_kurumsal.sql (Veritabanı şeması)
2. FTP_UPLOAD_GUIDE.md     (Bu rehber)
3. INSTALLATION_GUIDE.md   (Kurulum rehberi)
```

## 🚫 **YÜKLENMEYECEK DOSYALAR**

```
❌ .git/                   (Git geçmişi)
❌ node_modules/           (npm paketleri)
❌ .env                    (Hassas bilgiler)
❌ database/database.sqlite (SQLite dosyası)
❌ storage/logs/           (Log dosyaları)
❌ storage/framework/cache/ (Cache dosyaları)
❌ .DS_Store               (macOS dosyaları)
```

## 🔧 **cPanel'DE YAPILACAKLAR**

### **1. Veritabanı Oluşturma**
```
✅ cPanel > MySQL Databases
✅ Yeni veritabanı: wh_kurumsal
✅ Yeni kullanıcı oluştur
✅ Kullanıcıyı veritabanına ekle (tüm izinler)
```

### **2. SQL Import**
```
✅ cPanel > phpMyAdmin
✅ Veritabanını seç
✅ Import > database/wh_kurumsal.sql
✅ Import işlemini tamamla
```

### **3. Dosya İzinleri**
```bash
✅ chmod -R 755 storage/
✅ chmod -R 755 bootstrap/cache/
✅ chmod 644 .env.example
```

### **4. Environment Dosyası**
```bash
✅ cp .env.example .env
✅ .env dosyasını düzenle:
   - APP_URL=https://yourdomain.com
   - DB_DATABASE=your_database_name
   - DB_USERNAME=your_username
   - DB_PASSWORD=your_password
```

## 🌐 **KURULUM SİHİRBAZI**

### **1. Kurulum Başlatma**
```
✅ https://yourdomain.com/install
✅ Gereksinimler kontrolü
✅ Database ayarları
✅ Migration çalıştırma
✅ Admin kullanıcı oluşturma
```

### **2. Kurulum Tamamlandı**
```
✅ storage/installed dosyası oluştu
✅ /install route'ları devre dışı
✅ Ana sayfa erişilebilir
```

## 🧪 **TEST EDİLECEKLER**

### **1. Ana Fonksiyonlar**
```
✅ Ana sayfa yükleniyor mu?
✅ Login sayfası çalışıyor mu?
✅ Dashboard erişilebilir mi?
✅ Müşteri ekleme çalışıyor mu?
```

### **2. PDF Export Testleri**
```
✅ Revenue raporu PDF export
✅ Reconciliation raporu PDF export
✅ Customer raporu PDF export
✅ PDF dosyaları düzgün oluşuyor mu?
```

### **3. E-posta Testleri**
```
✅ SMTP ayarları doğru mu?
✅ Test e-postası gönderiliyor mu?
✅ Invoice e-postaları çalışıyor mu?
```

## 🔒 **GÜVENLİK KONTROLLERİ**

### **1. Dosya İzinleri**
```
✅ .env dosyası 644
✅ storage/ klasörü 755
✅ bootstrap/cache/ klasörü 755
```

### **2. SSL Sertifikası**
```
✅ HTTPS aktif
✅ SSL sertifikası geçerli
✅ Mixed content hatası yok
```

### **3. Güvenlik Ayarları**
```
✅ APP_DEBUG=false
✅ APP_ENV=production
✅ Güvenlik header'ları aktif
```

## 📊 **SİSTEM ÖZELLİKLERİ**

### **✅ Tamamlanan Özellikler**
- ✅ Müşteri yönetimi
- ✅ Fatura/teklif sistemi
- ✅ Hizmet takibi
- ✅ PDF export (CSV + PDF)
- ✅ E-posta bildirimleri
- ✅ Raporlama sistemi
- ✅ Admin paneli
- ✅ Responsive tasarım
- ✅ Otomatik kurulum sistemi
- ✅ cPanel uyumluluğu

### **✅ Kurulum Yöntemleri**
- ✅ Web tabanlı kurulum sihirbazı
- ✅ SQL dosyası ile hızlı kurulum
- ✅ Composer ile bağımlılık yönetimi
- ✅ Otomatik migration ve seeding

## 🆘 **SORUN GİDERME**

### **500 Internal Server Error**
```
🔍 .htaccess dosyasını kontrol et
🔍 PHP error log'larını incele
🔍 Dosya izinlerini kontrol et
🔍 .env dosyasını kontrol et
```

### **Database Bağlantı Hatası**
```
🔍 .env dosyasındaki database bilgilerini kontrol et
🔍 cPanel'de database oluşturduğundan emin ol
🔍 Kullanıcı izinlerini kontrol et
🔍 Database adını doğru yazdığından emin ol
```

### **PDF Export Çalışmıyor**
```
🔍 PHP GD extension'ının aktif olduğundan emin ol
🔍 storage/ klasörüne yazma izni ver
🔍 DomPDF cache'ini temizle
🔍 PHP memory limit'ini kontrol et
```

---

## 🎯 **KURULUM TAMAMLANDI!**

Tüm adımları tamamladıktan sonra sistem şu adreslerden erişilebilir:

- **Ana Sayfa:** `https://yourdomain.com`
- **Admin Panel:** `https://yourdomain.com/login`
- **Raporlar:** `https://yourdomain.com/reports`
- **Müşteriler:** `https://yourdomain.com/customers`
- **Faturalar:** `https://yourdomain.com/invoices`

**Sistem tamamen hazır ve çalışır durumda! 🚀**
