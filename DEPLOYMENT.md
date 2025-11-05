# Laravel Budget Tracker - Deployment Guide

## Production Deployment Steps

### 1. Environment Configuration

Create a production `.env` file with these settings:

```bash
APP_NAME="Budget Tracker"
APP_ENV=production
APP_KEY=base64:YOUR_PRODUCTION_KEY_HERE
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database Configuration (for production database)
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password

# Or keep SQLite for simplicity
# DB_CONNECTION=sqlite

# Session & Cache
SESSION_DRIVER=database
CACHE_DRIVER=file

# Mail Configuration (optional)
MAIL_MAILER=smtp
MAIL_HOST=your-mail-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 2. Server Requirements

-   PHP 8.2 or higher
-   Composer
-   Node.js and npm
-   Web server (Apache/Nginx)
-   Database (MySQL/SQLite)

### 3. Deployment Commands

```bash
# 1. Clone/upload your project to server
git clone your-repo-url /path/to/your/domain

# 2. Install PHP dependencies
composer install --optimize-autoloader --no-dev

# 3. Install and build frontend assets
npm install
npm run build

# 4. Set up environment
cp .env.example .env
# Edit .env with your production settings
php artisan key:generate

# 5. Set up database
php artisan migrate --force

# 6. Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Set proper permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 4. Web Server Configuration

#### Apache (.htaccess already included)

Point your domain to the `public` directory.

#### Nginx Configuration

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    root /path/to/your/project/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 5. SSL Certificate (Let's Encrypt)

```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx

# Get SSL certificate
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Auto-renewal (add to crontab)
0 12 * * * /usr/bin/certbot renew --quiet
```

### 6. Database Setup

#### For MySQL:

```sql
CREATE DATABASE budget_tracker;
CREATE USER 'budget_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON budget_tracker.* TO 'budget_user'@'localhost';
FLUSH PRIVILEGES;
```

#### For SQLite (simpler):

Just ensure the database file has proper permissions:

```bash
touch database/database.sqlite
chmod 664 database/database.sqlite
chown www-data:www-data database/database.sqlite
```

### 7. Queue Workers (Optional for background jobs)

```bash
# Install supervisor
sudo apt install supervisor

# Create supervisor config
sudo nano /etc/supervisor/conf.d/budget-tracker.conf
```

Config content:

```ini
[program:budget-tracker-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/your/project/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/path/to/your/project/storage/logs/worker.log
stopwaitsecs=3600
```

### 8. Monitoring & Maintenance

#### Log Rotation

```bash
# Add to /etc/logrotate.d/budget-tracker
/path/to/your/project/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    notifempty
    create 0644 www-data www-data
}
```

#### Backup Script

```bash
#!/bin/bash
# backup.sh
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backups/budget-tracker"
PROJECT_DIR="/path/to/your/project"

# Create backup directory
mkdir -p $BACKUP_DIR

# Backup database (SQLite)
cp $PROJECT_DIR/database/database.sqlite $BACKUP_DIR/database_$DATE.sqlite

# Backup uploaded files
tar -czf $BACKUP_DIR/storage_$DATE.tar.gz -C $PROJECT_DIR storage/app

# Keep only last 7 days of backups
find $BACKUP_DIR -name "*.sqlite" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete
```

### 9. Security Checklist

-   [ ] Set `APP_DEBUG=false` in production
-   [ ] Use strong `APP_KEY` (generate new one)
-   [ ] Set proper file permissions (755 for directories, 644 for files)
-   [ ] Use HTTPS (SSL certificate)
-   [ ] Keep Laravel and dependencies updated
-   [ ] Use strong database passwords
-   [ ] Enable firewall and limit access to database ports
-   [ ] Regular backups
-   [ ] Monitor error logs

### 10. Performance Optimization

```bash
# Enable OPcache in PHP
# Add to php.ini:
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
opcache.revalidate_freq=2
opcache.fast_shutdown=1

# Laravel optimizations
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
```

## Quick Deployment Commands Summary

```bash
# One-time setup
git clone your-repo
cd project-directory
composer install --no-dev --optimize-autoloader
npm install && npm run build
cp .env.example .env
# Edit .env file
php artisan key:generate
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
chmod -R 755 storage bootstrap/cache

# For updates
git pull
composer install --no-dev --optimize-autoloader
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Your Laravel Budget Tracker is now ready for production deployment!
