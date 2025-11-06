# Budget Manager

A comprehensive Laravel budget management application with automatic GitHub deployment to Hostinger.

## Features

- Personal budget tracking and management
- Monthly budget templates with automatic generation
- Purchase tracking with categories and budget assignment
- Investment and savings budgets with smart progress indicators
- Visual progress bars with intelligent color coding
- Monthly spending analysis and breakdowns
- Responsive design with Tailwind CSS
- Month-to-month budget comparison

## Production Deployment via GitHub

This application is configured for automatic deployment from GitHub to Hostinger.

### Environment Configuration

The application uses different environment files:
- `.env.example` - Template for local development
- `.env.production` - Production configuration template

### Automatic Deployment Process

1. Push changes to the `main` branch
2. Hostinger automatically pulls updates
3. Post-deployment script runs automatically
4. Application rebuilds assets and clears caches

### Server Requirements

- PHP 8.1+
- MySQL 5.7+
- Node.js 18+ (for asset building)
- Composer

### First Time Setup on Hostinger

1. Connect GitHub repository to Hostinger
2. Configure database credentials in `.env`
3. Run initial migration: `php artisan migrate --force`
4. Set storage permissions: `chmod -R 755 storage bootstrap/cache`
5. Create storage link: `php artisan storage:link`

### Post-Deployment Commands (Automated)

```bash
composer install --optimize-autoloader --no-dev
npm ci
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Local Development

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/budget-manager.git
   cd budget-manager
   ```

2. Install dependencies:
   ```bash
   composer install
   npm install
   ```

3. Environment setup:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Database setup:
   ```bash
   php artisan migrate
   php artisan db:seed  # Optional: Add sample data
   ```

5. Build assets and start development:
   ```bash
   npm run dev
   php artisan serve
   ```

## Technologies Used

- **Backend**: Laravel 11, PHP 8.1+
- **Database**: SQLite (development), MySQL (production)
- **Frontend**: Blade templates, Tailwind CSS, Alpine.js
- **Build Tools**: Vite
- **Deployment**: GitHub + Hostinger Auto-Deploy

## Project Structure

- **Budget Templates**: Reusable monthly budget configurations
- **Monthly Budgets**: Generated from templates for specific months
- **Purchases**: Individual transactions linked to budgets
- **Categories**: Smart categorization with color-coded progress
- **Dashboard**: Comprehensive overview with visual analytics

## Key Features

### Smart Budget Progress
- **Investment/Savings**: Green progress bars (good to complete)
- **Spending Categories**: Blue → Yellow → Red progression
- **Visual Feedback**: Immediate understanding of budget health

### Intelligent Category Handling
- Automatic category inheritance from budgets
- Flexible purchase categorization
- Monthly breakdown analysis

### Responsive Design
- Mobile-first approach
- Clean, intuitive interface
- Accessible color schemes and typography

## Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature-name`
3. Make your changes and test locally
4. Commit with clear messages: `git commit -m "Add feature description"`
5. Push to your fork: `git push origin feature-name`
6. Create a Pull Request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).