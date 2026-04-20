#!/bin/bash

# Gofishi Production Deployment Script (aaPanel + OpenLiteSpeed)
# Optimization using @laravel-specialist & @php-pro techniques

echo "🚀 Starting Deployment Optimization..."

# 0. Create Necessary Directories
echo "📂 Creating required framework directories..."
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache/data
mkdir -p storage/logs
mkdir -p bootstrap/cache
rm -f public/hot

# 1. Update Dependencies
echo "📦 Installing PHP dependencies..."
composer install --optimize-autoloader --no-dev


# 3. Environment Setup
echo "🔑 Preparing Environment..."
if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate
    echo "⚠️  Created new .env file. Please update your DB credentials!"
fi

# 4. Storage & Permissions
echo "📂 Setting up storage and permissions..."
php artisan storage:link
chmod -R 775 storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# 5. Database
echo "🗄️ Running migrations..."
php artisan migrate --force

# 6. Laravel Production Optimization
echo "⚡ Optimizing Laravel Cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 7. aaPanel / OpenLiteSpeed Reminder
echo ""
echo "✅ Deployment Complete!"
echo "--------------------------------------------------------"
echo "🌐 SEGERA LAKUKAN INI DI AAPANEL (OpenLiteSpeed):"
echo "1. Buka Site Settings -> Rewrite Rules."
echo "2. Masukkan aturan berikut untuk Laravel:"
echo "--------------------------------------------------------"
echo "rewrite {
    base /
    rewrite /.* - [L]
    rewrite (.*) /index.php?\$1 [L]
}"
echo "--------------------------------------------------------"
echo "3. Restart OpenLiteSpeed di dashboard aaPanel."
echo "4. Pastikan PHP version adalah 8.2 atau 8.3."
echo "--------------------------------------------------------"
