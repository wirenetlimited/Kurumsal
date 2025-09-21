#!/bin/bash

# WH Kurumsal - Otomatik Deployment Script
# Bu script projeyi hosting'e yüklemek için hazırlar

echo "🚀 WH Kurumsal - Otomatik Deployment Başlıyor..."
echo "=================================================="

# 1. Gereksiz dosyaları temizle
echo "🧹 Gereksiz dosyalar temizleniyor..."
rm -rf .git
rm -rf node_modules
rm -f .env
rm -f database/database.sqlite
rm -rf storage/logs/*
rm -rf storage/framework/cache/*
rm -rf storage/framework/sessions/*
rm -rf storage/framework/views/*

# 2. Production için .env dosyası oluştur
echo "⚙️ Production .env dosyası oluşturuluyor..."
cat > .env << 'EOF'
APP_NAME="WH Kurumsal"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
EOF

# 3. Composer optimizasyonu
echo "📚 Composer optimizasyonu yapılıyor..."
composer install --optimize-autoloader --no-dev

# 4. Laravel optimizasyonları
echo "⚡ Laravel optimizasyonları..."
php artisan config:cache
php artisan view:cache
php artisan route:cache

# 5. Storage link oluştur
echo "🔗 Storage link oluşturuluyor..."
php artisan storage:link

# 6. Dosya izinlerini ayarla
echo "🔐 Dosya izinleri ayarlanıyor..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod 644 .env

# 7. Deployment paketi oluştur
echo "📦 Deployment paketi oluşturuluyor..."
tar -czf wh_kurumsal_deploy_$(date +%Y%m%d_%H%M%S).tar.gz \
    --exclude='.git' \
    --exclude='node_modules' \
    --exclude='storage/logs' \
    --exclude='storage/framework/cache' \
    --exclude='storage/framework/sessions' \
    --exclude='storage/framework/views' \
    --exclude='database/database.sqlite' \
    --exclude='*.tar.gz' \
    .

echo ""
echo "✅ Deployment hazırlığı tamamlandı!"
echo "=================================================="
echo "📁 Oluşturulan paket: wh_kurumsal_deploy_$(date +%Y%m%d_%H%M%S).tar.gz"
echo ""
echo "🚀 Hosting'e yükleme adımları:"
echo "1. Paketi hosting'e yükle"
echo "2. public_html klasörüne çıkar"
echo "3. .env dosyasını düzenle (database bilgileri)"
echo "4. php artisan key:generate çalıştır"
echo "5. Veritabanını import et (wh_kurumsal.sql)"
echo ""
echo "📋 Gerekli bilgiler:"
echo "- Database adı, kullanıcı adı, şifre"
echo "- SMTP ayarları"
echo "- Domain adı"
echo ""
echo "🎯 Hesaplar:"
echo "- Admin: admin@whkurumsal.com / admin123"
echo "- Demo: demo@example.com / demo123"
