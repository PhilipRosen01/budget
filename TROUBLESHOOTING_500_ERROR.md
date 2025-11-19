# Troubleshooting 500 Error on Hostinger

## Common Causes & Solutions for Laravel 500 Errors on Hostinger

### 1. **File Permissions Issues** (Most Common)

```bash
# Set correct permissions on server
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod -R 644 .env
```

### 2. **Missing Storage Directories**

```bash
# Create missing directories
mkdir -p storage/logs
mkdir -p storage/app/public
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p bootstrap/cache
```

### 3. **Environment Configuration**

Check your `.env` file on the server:

```env
APP_ENV=production
APP_DEBUG=false  # Set to true temporarily to see errors
APP_KEY=your-generated-key
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=your_hostinger_database
DB_USERNAME=your_hostinger_user
DB_PASSWORD=your_hostinger_password
```

### 4. **Missing Application Key**

```bash
php artisan key:generate --force
```

### 5. **Database Connection Issues**

Test database connection:

```bash
php artisan migrate --force
```

### 6. **Composer Dependencies**

```bash
composer install --optimize-autoloader --no-dev
```

### 7. **Clear All Caches**

```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

### 8. **Web Server Configuration**

Ensure `.htaccess` exists in `public/` folder:

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
```

## Debug Steps

### Step 1: Enable Debug Mode

Temporarily set in `.env`:

```env
APP_DEBUG=true
LOG_LEVEL=debug
```

### Step 2: Check Error Logs

Look at these locations:

-   `storage/logs/laravel.log`
-   Hostinger Control Panel → Error Logs
-   cPanel → Error Logs (if using cPanel)

### Step 3: Test Basic PHP

Create `test.php` in your public folder:

```php
<?php
phpinfo();
echo "PHP is working!";
```

Visit `yourdomain.com/test.php`

### Step 4: Test Laravel Bootstrap

Create `debug.php` in your public folder:

```php
<?php
require_once __DIR__.'/../bootstrap/app.php';
echo "Laravel bootstrap works!";
```

## Most Likely Issues for Your Setup

Based on your setup, check these in order:

1. **Document Root**: Ensure it points to `/public_html/public/`
2. **File Permissions**: Run the permission commands above
3. **Database Credentials**: Verify in Hostinger control panel
4. **Application Key**: Ensure it's generated
5. **Storage Directories**: Create missing directories

## Quick Fix Script

Run this on your server:

```bash
#!/bin/bash
# Quick Laravel 500 error fix

# Set permissions
chmod -R 755 storage bootstrap/cache
chmod 644 .env

# Create directories
mkdir -p storage/{logs,app/public,framework/{cache,sessions,views}}
mkdir -p bootstrap/cache

# Clear caches
php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true

# Generate key if missing
php artisan key:generate --force

# Test database
php artisan migrate --force

echo "Fix attempt complete. Check your site now."
```

## Contact Information

If the error persists:

1. Check `storage/logs/laravel.log` for specific error messages
2. Enable `APP_DEBUG=true` temporarily
3. Look at Hostinger error logs in control panel
4. Verify all environment variables are correct

Remember to set `APP_DEBUG=false` after debugging!
