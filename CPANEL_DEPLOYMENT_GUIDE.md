# cPanel Deployment Rehberi

## 📋 Ön Gereksinimler

### cPanel Gereksinimleri:
- **PHP:** 8.2 veya üzeri
- **MySQL:** 5.7 veya üzeri (veya MariaDB)
- **SSL Sertifikası:** Önerilen
- **Disk Alanı:** En az 500MB

### PHP Extensions:
- `pdo_mysql`
- `mbstring`
- `openssl`
- `tokenizer`
- `xml`
- `ctype`
- `json`
- `bcmath`
- `fileinfo`
- `gd` (PDF oluşturma için)

## 🚀 Yükleme Adımları

### 1. Dosya Yükleme
```bash
# Tüm proje dosyalarını public_html klasörüne yükleyin
# .git, node_modules, vendor klasörlerini hariç tutun
```

### 2. Environment Dosyası
```bash
# .env.example dosyasını .env olarak kopyalayın
cp .env.example .env
```

### 3. .env Dosyasını Düzenleyin
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

MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email
MAIL_FROM_NAME="${APP_NAME}"
```

### 4. Composer Bağımlılıkları
```bash
# SSH ile cPanel'e bağlanın
cd public_html
composer install --optimize-autoloader --no-dev
```

### 5. Laravel Komutları
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

# Storage link oluşturun
php artisan storage:link
```

### 6. Dosya İzinleri
```bash
# Klasör izinlerini ayarlayın
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod 644 .env
```

### 7. cPanel File Manager'da:
- `storage/` klasörüne yazma izni verin
- `bootstrap/cache/` klasörüne yazma izni verin
- `.env` dosyasına yazma izni verin

## 🔧 cPanel Özel Ayarlar

### 1. PHP Versiyonu
- cPanel > Software > MultiPHP Manager
- PHP 8.2 veya üzerini seçin

### 2. PHP Extensions
- cPanel > Software > Select PHP Version
- Gerekli extension'ları aktif edin

### 3. Cron Jobs (Opsiyonel)
```bash
# Günlük cache temizleme
0 2 * * * cd /home/username/public_html && php artisan cache:clear

# Haftalık log temizleme
0 3 * * 0 cd /home/username/public_html && php artisan log:clear
```

### 4. .htaccess Dosyası
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Security Headers
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>

# Compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>
```

## 🧪 Test Etme

### 1. Ana Sayfa Testi
```
https://yourdomain.com
```

### 2. Admin Paneli Testi
```
https://yourdomain.com/login
```

### 3. PDF Export Testi
```
https://yourdomain.com/reports/revenue
# Export > PDF İndir butonuna tıklayın
```

### 4. Database Testi
```bash
php artisan tinker
# Test komutları çalıştırın
```

## ⚠️ Güvenlik Kontrolleri

### 1. Dosya İzinleri
```bash
# Hassas dosyaları koruyun
chmod 644 .env
chmod 644 composer.json
chmod 644 composer.lock
```

### 2. SSL Sertifikası
- cPanel > SSL/TLS Status
- Let's Encrypt veya ücretli SSL aktif edin

### 3. Backup
- cPanel > Backup
- Düzenli backup planı oluşturun

## 🔍 Sorun Giderme

### Yaygın Sorunlar:

1. **500 Internal Server Error:**
   - .htaccess dosyasını kontrol edin
   - PHP error log'larını inceleyin

2. **Database Bağlantı Hatası:**
   - .env dosyasındaki database bilgilerini kontrol edin
   - cPanel'de database oluşturduğunuzdan emin olun

3. **PDF Export Çalışmıyor:**
   - PHP GD extension'ının aktif olduğundan emin olun
   - storage/ klasörüne yazma izni verin

4. **Cache Sorunları:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```

## 📞 Destek

Sorun yaşarsanız:
1. cPanel error log'larını kontrol edin
2. Laravel log'larını inceleyin: `storage/logs/laravel.log`
3. PHP error log'larını kontrol edin

