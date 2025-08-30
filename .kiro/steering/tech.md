# Technology Stack

## Framework & Core
- **Laravel 12.0** - PHP web framework
- **PHP 8.2+** - Backend language
- **Vite** - Frontend build tool
- **Tailwind CSS** - Utility-first CSS framework
- **Alpine.js** - Lightweight JavaScript framework

## Key Dependencies
- **Spatie Laravel Permission** - Role and permission management
- **Laravel Breeze** - Authentication scaffolding
- **Laravel Scout** - Full-text search
- **DomPDF** - PDF generation
- **Maatwebsite Excel** - Excel import/export
- **Spatie Laravel Backup** - Database and file backups
- **Laravel Telescope** - Debug assistant (dev only)

## Development Tools
- **Pest** - Testing framework
- **Laravel Pint** - Code style fixer
- **Laravel Sail** - Docker development environment
- **Laravel Pail** - Log viewer

## Common Commands

### Development
```bash
# Start development server with all services
composer run dev

# Individual services
php artisan serve
php artisan queue:listen --tries=1
php artisan pail --timeout=0
npm run dev
```

### Testing
```bash
# Run tests
php artisan test
# or
./vendor/bin/pest
```

### Database
```bash
# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Fresh migration with seeding
php artisan migrate:fresh --seed
```

### Code Quality
```bash
# Fix code style
./vendor/bin/pint

# Clear caches
php artisan optimize:clear
```

### Production
```bash
# Build assets
npm run build

# Optimize for production
php artisan optimize
```