#!/bin/bash

# Hosting'de çalıştırılacak kurulum scripti
# Bu script hosting'de (cPanel SSH) çalıştırılır

echo "🚀 WH Kurumsal - Hosting Kurulumu Başlıyor..."
echo "=============================================="

# 1. Paketi çıkar
echo "📦 Dosyalar çıkarılıyor..."
tar -xzf wh_kurumsal_deploy_*.tar.gz

# 2. .env dosyasını düzenle (kullanıcıdan bilgileri al)
echo "⚙️ Environment ayarları..."
echo "Lütfen aşağıdaki bilgileri girin:"
echo ""

read -p "Domain adı (örn: yourdomain.com): " DOMAIN
read -p "Database adı: " DB_NAME
read -p "Database kullanıcı adı: " DB_USER
read -p "Database şifresi: " DB_PASS
read -p "SMTP host: " SMTP_HOST
read -p "SMTP kullanıcı adı (email): " SMTP_USER
read -p "SMTP şifresi: " SMTP_PASS

# .env dosyasını güncelle
sed -i "s/https:\/\/yourdomain.com/https:\/\/$DOMAIN/g" .env
sed -i "s/your_database_name/$DB_NAME/g" .env
sed -i "s/your_username/$DB_USER/g" .env
sed -i "s/your_password/$DB_PASS/g" .env
sed -i "s/your_smtp_host/$SMTP_HOST/g" .env
sed -i "s/your_email/$SMTP_USER/g" .env

# 3. Application key oluştur
echo "🔑 Application key oluşturuluyor..."
php artisan key:generate

# 4. Cache temizle
echo "🧹 Cache temizleniyor..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 5. Storage link
echo "🔗 Storage link oluşturuluyor..."
php artisan storage:link

# 6. Dosya izinleri
echo "🔐 Dosya izinleri ayarlanıyor..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod 644 .env

# 7. Veritabanı import et
echo "🗄️ Veritabanı import ediliyor..."
if [ -f "database/wh_kurumsal.sql" ]; then
    mysql -u $DB_USER -p$DB_PASS $DB_NAME < database/wh_kurumsal.sql
    echo "✅ Veritabanı başarıyla import edildi!"
else
    echo "⚠️ wh_kurumsal.sql dosyası bulunamadı. Manuel import gerekli."
fi

# 8. Test
echo "🧪 Sistem test ediliyor..."
php artisan config:cache
php artisan view:cache

echo ""
echo "✅ Kurulum tamamlandı!"
echo "=============================================="
echo "🌐 Site: https://$DOMAIN"
echo "👤 Admin: admin@whkurumsal.com / admin123"
echo "👤 Demo: demo@example.com / demo123"
echo ""
echo "📋 Test edilecek özellikler:"
echo "- Ana sayfa: https://$DOMAIN"
echo "- Login: https://$DOMAIN/login"
echo "- PDF Export: https://$DOMAIN/reports/revenue"
echo ""
echo "🔧 Sorun yaşarsanız:"
echo "- storage/logs/laravel.log dosyasını kontrol edin"
echo "- cPanel error log'larını inceleyin"
echo "- Dosya izinlerini kontrol edin"
