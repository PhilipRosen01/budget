# GitHub Auto-Deployment Setup for Hostinger

This guide will help you set up automatic deployment from GitHub to Hostinger, so any changes pushed to your main branch will automatically update your live website.

## Step 1: Prepare Your GitHub Repository

### 1.1 Create GitHub Repository
1. Go to [GitHub.com](https://github.com) and create a new repository
2. Name it something like `budget-manager` or `my-budget-app`
3. Make it public (required for Hostinger's free Git deployment)
4. Don't initialize with README (we already have files)

### 1.2 Push Your Code to GitHub
```bash
cd /Users/devaccount/Desktop/budget

# Initialize git repository
git init

# Add all files
git add .

# Create initial commit
git commit -m "Initial commit: Laravel Budget Manager"

# Add your GitHub repository as origin
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPOSITORY_NAME.git

# Push to GitHub
git branch -M main
git push -u origin main
```

## Step 2: Configure Hostinger for GitHub Deployment

### 2.1 Access Git Deployment in Hostinger
1. Log into your Hostinger control panel
2. Go to **"Advanced"** → **"Git"** (or look for "GitHub Deployment")
3. Click **"Create"** or **"New Repository"**

### 2.2 Connect Your GitHub Repository
1. **Repository URL**: `https://github.com/YOUR_USERNAME/YOUR_REPOSITORY_NAME.git`
2. **Branch**: `main`
3. **Target Path**: 
   - For main domain: `/public_html/`
   - For subdomain: `/public_html/subdomain_name/`
4. **Auto-deploy**: ✅ Enable
5. **Delete files**: ❌ Disable (to preserve uploads/storage)

### 2.3 Configure Build Commands (if available)
If Hostinger provides build command options:
- **Install Command**: `composer install --no-dev && npm ci`
- **Build Command**: `npm run build`
- **Post-deploy**: `php artisan migrate --force && php artisan config:cache`

## Step 3: Set Up Production Environment

### 3.1 Create Production Environment File
1. In Hostinger File Manager, navigate to your app directory
2. Copy `.env.production` to `.env`
3. Update the database credentials:
```env
APP_NAME="Budget Manager"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_hostinger_database_name
DB_USERNAME=your_hostinger_database_user
DB_PASSWORD=your_hostinger_database_password
```

### 3.2 Create MySQL Database
1. In Hostinger control panel, go to **"Databases"**
2. Create new MySQL database
3. Note the database name, username, and password
4. Update your `.env` file with these credentials

### 3.3 Configure Document Root
1. In Hostinger control panel, go to **"Domains"**
2. Click manage next to your domain
3. Set **Document Root** to: `/public_html/public` (or `/public_html/your-app/public`)

## Step 4: Initial Deployment

### 4.1 Trigger First Deployment
1. In Hostinger Git section, click **"Deploy"** or **"Pull Changes"**
2. Wait for deployment to complete
3. Check the deployment log for any errors

### 4.2 Run Initial Setup Commands
Using Hostinger's terminal or SSH:
```bash
cd /public_html  # or wherever your app is deployed

# Set permissions
chmod -R 755 storage bootstrap/cache

# Run migrations
php artisan migrate --force

# Generate application key (if needed)
php artisan key:generate --force

# Create storage symlink
php artisan storage:link

# Cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Step 5: Test Auto-Deployment

### 5.1 Make a Test Change
1. Edit a file locally (like updating a color or text)
2. Commit and push to GitHub:
```bash
git add .
git commit -m "Test auto-deployment"
git push
```

### 5.2 Verify Deployment
1. Check Hostinger Git section for deployment status
2. Visit your website to confirm changes are live
3. Check deployment logs if there are issues

## Step 6: Ongoing Development Workflow

### Daily Development Process:
1. **Develop locally**: Make changes on your computer
2. **Test locally**: Run `php artisan serve` to test
3. **Commit changes**: `git add . && git commit -m "Description of changes"`
4. **Push to GitHub**: `git push`
5. **Auto-deploy**: Hostinger automatically updates your live site!

### For Database Changes:
1. Create new migration: `php artisan make:migration description`
2. Test locally: `php artisan migrate`
3. Push to GitHub: Changes will auto-deploy and migrate

## Troubleshooting

### Common Issues:

1. **Deployment Fails**:
   - Check repository URL is correct
   - Ensure repository is public
   - Verify branch name is correct

2. **Site Shows Errors**:
   - Check `.env` file has correct database credentials
   - Verify storage permissions: `chmod -R 755 storage`
   - Check if migrations need to run: `php artisan migrate --force`

3. **Assets Not Loading**:
   - Ensure `npm run build` ran successfully
   - Check if `public/build` folder exists
   - Verify document root points to `public/` folder

4. **Database Connection Error**:
   - Double-check database credentials in `.env`
   - Ensure database exists in Hostinger control panel
   - Test database connection in Hostinger phpMyAdmin

### Debug Mode:
Temporarily set `APP_DEBUG=true` in `.env` to see detailed error messages.

## Security Notes

- ✅ Keep `APP_DEBUG=false` in production
- ✅ Use strong database passwords
- ✅ Keep `.env` file secure (it's in .gitignore by default)
- ✅ Regularly update dependencies
- ✅ Enable SSL certificate in Hostinger

## Benefits of This Setup

- 🚀 **Instant Updates**: Push code → Live website updates automatically
- 🔒 **Version Control**: Full history of all changes
- 🔄 **Easy Rollbacks**: Can revert to any previous version
- 👥 **Collaboration**: Multiple developers can work on the same project
- 📱 **Development Workflow**: Develop locally, deploy globally

Your Laravel Budget Manager will now automatically update every time you push changes to GitHub!