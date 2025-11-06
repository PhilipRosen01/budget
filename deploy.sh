#!/bin/bash

# Laravel Production Deployment Script
# Run this before uploading to Hostinger

echo "🚀 Preparing Laravel app for production deployment..."

# 1. Install production dependencies
echo "📦 Installing production dependencies..."
composer install --optimize-autoloader --no-dev

# 2. Build production assets
echo "🎨 Building production assets..."
npm run build

# 3. Clear all caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# 4. Generate optimized config cache
echo "⚡ Optimizing configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Create production zip file (excluding unnecessary files)
echo "📁 Creating deployment package..."
mkdir -p deployment
cp -r app/ deployment/
cp -r bootstrap/ deployment/
cp -r config/ deployment/
cp -r database/ deployment/
cp -r public/ deployment/
cp -r resources/ deployment/
cp -r routes/ deployment/
cp -r storage/ deployment/
cp -r vendor/ deployment/
cp artisan deployment/
cp composer.json deployment/
cp composer.lock deployment/
cp .env.production deployment/.env

# Clean up storage for deployment
rm -rf deployment/storage/logs/*
rm -rf deployment/storage/framework/cache/*
rm -rf deployment/storage/framework/sessions/*
rm -rf deployment/storage/framework/views/*

echo "✅ Production build complete!"
echo "📤 Upload the 'deployment' folder contents to your Hostinger public_html directory"
echo "🔧 Don't forget to:"
echo "   - Update database credentials in .env"
echo "   - Set proper file permissions (755 for storage/)"
echo "   - Run 'php artisan migrate --force' on the server"
echo "   - Point your domain to the public/ folder"