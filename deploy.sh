#!/bin/bash

# WH Kurumsal v3.0.0 cPanel Deployment Script
echo "🚀 WH Kurumsal v3.0.0 Deployment Başlıyor..."

# 1. Dosyaları çıkar
echo "📦 Dosyalar çıkarılıyor..."
tar -xzf bootstrap.tar.gz
tar -xzf app.tar.gz
tar -xzf config.tar.gz
tar -xzf resources.tar.gz
tar -xzf routes.tar.gz
tar -xzf database.tar.gz
tar -xzf storage.tar.gz

# 2. Public build klasörünü çıkar
echo "🎨 Frontend assets çıkarılıyor..."
cd public_html
tar -xzf build.tar.gz
cd ..

# 3. Composer install (vendor klasörü için)
echo "📚 Composer dependencies kuruluyor..."
composer install --optimize-autoloader --no-dev

# 4. Veritabanını import et
echo "🗄️ Veritabanı import ediliyor..."
mysql -u whkurum_whkurumsal -p'WhKurumsal2025!' whkurum_whkurumsal < wh_kurumsal_backup.sql

# 5. Laravel optimizasyonları
echo "⚡ Laravel optimizasyonları..."
php artisan config:cache
php artisan view:cache
php artisan storage:link

# 6. Permissions ayarla
echo "🔐 Permissions ayarlanıyor..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod 644 .env

# 7. Temizlik
echo "🧹 Geçici dosyalar temizleniyor..."
rm -f *.tar.gz
rm -f wh_kurumsal_backup.sql

echo "✅ Deployment tamamlandı!"
echo "🌐 Site: https://whkurumsal.com"
echo "👤 Admin: admin@whkurumsal.com / admin123"
echo "👤 Demo: demo@example.com / demo123"
