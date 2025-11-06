# Hostinger Deployment Guide for Laravel Budget App

## Prerequisites

-   Hostinger shared hosting or VPS account
-   Your domain configured in Hostinger
-   FTP/SFTP access to your hosting account
-   MySQL database access in Hostinger control panel

## Step 1: Prepare Your Application Files

### 1.1 Create Production Environment File

-   Copy `.env.production` to `.env` on your server
-   Update the following values:
    ```
    APP_URL=https://yourdomain.com
    DB_HOST=localhost (or your Hostinger database host)
    DB_DATABASE=your_hostinger_database_name
    DB_USERNAME=your_hostinger_database_user
    DB_PASSWORD=your_hostinger_database_password
    ```

### 1.2 Files to Upload

Upload ALL files EXCEPT:

-   `.env` (use `.env.production` instead)
-   `node_modules/` (not needed on server)
-   `.git/` (optional, not needed)
-   `storage/logs/` (will be created automatically)

## Step 2: Hostinger Setup

### 2.1 Create MySQL Database

1. Log into Hostinger control panel
2. Go to "Databases" → "MySQL Databases"
3. Create new database (note the name, username, password)
4. Add user to database with all privileges

### 2.2 Upload Files

1. Use File Manager or FTP client
2. Upload to `public_html/` directory (or subdirectory if using subdomain)
3. Make sure `public/` folder contents are in the web root

### 2.3 Configure Web Root

-   If using main domain: Point to `public_html/public/`
-   If using subdomain: Create subdomain pointing to your app's `public/` folder

## Step 3: Server Configuration

### 3.1 Set Permissions

Set these folder permissions:

```
storage/ - 755 or 775
storage/app/ - 755 or 775
storage/framework/ - 755 or 775
storage/logs/ - 755 or 775
bootstrap/cache/ - 755 or 775
```

### 3.2 Create .htaccess for Laravel (if not present)

Create/update `public/.htaccess`:

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

## Step 4: Database Migration

### 4.1 Run Migrations

SSH into your server (if available) or use Hostinger's terminal:

```bash
cd /path/to/your/app
php artisan migrate --force
```

### 4.2 Alternative: Import Database

If SSH not available:

1. Export your local database
2. Import via phpMyAdmin in Hostinger control panel

## Step 5: Final Configuration

### 5.1 Generate Application Key (if needed)

```bash
php artisan key:generate --force
```

### 5.2 Clear Caches

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 5.3 Set Storage Link

```bash
php artisan storage:link
```

## Step 6: Test Your Site

1. Visit your domain
2. Test registration/login
3. Test budget creation
4. Test purchase creation
5. Verify all features work

## Troubleshooting

### Common Issues:

1. **500 Error**: Check permissions on storage/ and bootstrap/cache/
2. **Database Connection**: Verify database credentials in .env
3. **Missing Assets**: Make sure public/build/ folder uploaded correctly
4. **Routing Issues**: Ensure .htaccess is properly configured

### Debug Mode:

Temporarily set `APP_DEBUG=true` in .env to see detailed errors

## Security Checklist

-   [ ] APP_DEBUG=false in production
-   [ ] Strong database password
-   [ ] Proper file permissions
-   [ ] SSL certificate enabled
-   [ ] .env file not in web-accessible directory

## File Structure on Server

```
public_html/
├── your-app/
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── storage/ (writable)
│   ├── vendor/
│   ├── .env (production settings)
│   └── artisan
└── public/ (web root)
    ├── build/
    ├── index.php
    └── .htaccess
```

Your Laravel budget app should now be live on your domain!
