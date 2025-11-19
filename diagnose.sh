#!/bin/bash

# Laravel Hostinger Diagnostic Script
# Run this on your Hostinger server to diagnose 500 errors

echo "🔍 Laravel Hostinger Diagnostic Report"
echo "======================================="
echo ""

# Check PHP version
echo "📋 PHP Version:"
php -v | head -1
echo ""

# Check current directory and permissions
echo "📁 Current Directory & Permissions:"
pwd
ls -la | head -10
echo ""

# Check storage permissions
echo "🔒 Storage Directory Permissions:"
ls -ld storage
ls -la storage/ | head -5
echo ""

# Check if .env exists
echo "⚙️ Environment File:"
if [ -f .env ]; then
    echo "✅ .env file exists"
    echo "APP_ENV: $(grep APP_ENV .env || echo 'Not set')"
    echo "APP_DEBUG: $(grep APP_DEBUG .env || echo 'Not set')"
    echo "APP_KEY: $(grep APP_KEY .env | cut -c1-20)..." 
    echo "DB_CONNECTION: $(grep DB_CONNECTION .env || echo 'Not set')"
else
    echo "❌ .env file missing!"
fi
echo ""

# Check Laravel key
echo "🔑 Application Key:"
php artisan key:generate --show 2>/dev/null || echo "❌ Cannot generate key"
echo ""

# Check database connection
echo "🗄️ Database Connection:"
php artisan migrate:status 2>/dev/null || echo "❌ Database connection failed"
echo ""

# Check required directories
echo "📂 Required Directories:"
directories=("storage/logs" "storage/app" "storage/framework/cache" "storage/framework/sessions" "storage/framework/views" "bootstrap/cache")
for dir in "${directories[@]}"; do
    if [ -d "$dir" ]; then
        echo "✅ $dir exists ($(ls -ld $dir | awk '{print $1}'))"
    else
        echo "❌ $dir missing"
    fi
done
echo ""

# Check composer
echo "📦 Composer Dependencies:"
if [ -d "vendor" ]; then
    echo "✅ vendor directory exists"
    if [ -f "vendor/autoload.php" ]; then
        echo "✅ autoload.php exists"
    else
        echo "❌ autoload.php missing"
    fi
else
    echo "❌ vendor directory missing - run: composer install"
fi
echo ""

# Check web server files
echo "🌐 Web Server Files:"
if [ -f "public/.htaccess" ]; then
    echo "✅ .htaccess exists"
else
    echo "❌ .htaccess missing in public/"
fi

if [ -f "public/index.php" ]; then
    echo "✅ index.php exists"
else
    echo "❌ index.php missing in public/"
fi
echo ""

# Check recent errors
echo "🚨 Recent Laravel Errors:"
if [ -f "storage/logs/laravel.log" ]; then
    echo "Last 5 lines from laravel.log:"
    tail -5 storage/logs/laravel.log 2>/dev/null || echo "Cannot read log file"
else
    echo "❌ No Laravel log file found"
fi
echo ""

# Check web server error log (common locations)
echo "🌐 Web Server Error Log:"
error_logs=("/home/*/public_html/error_log" "/var/log/apache2/error.log" "/var/log/nginx/error.log")
for log in "${error_logs[@]}"; do
    if [ -f "$log" ]; then
        echo "Found error log: $log"
        echo "Last 3 lines:"
        tail -3 "$log" 2>/dev/null
        break
    fi
done
echo ""

echo "🎯 Quick Fix Commands:"
echo "If you see permission issues, run:"
echo "chmod -R 755 storage bootstrap/cache"
echo ""
echo "If vendor is missing, run:"
echo "composer install --no-dev"
echo ""
echo "If .env issues, run:"
echo "php artisan key:generate --force"
echo ""
echo "If database issues, check your database credentials in .env"
echo ""
echo "======================================="
echo "🔍 Diagnostic complete!"