# Laravel Deployment on Hostinger - Complete Guide

## Prerequisites
- SSH access to Hostinger server
- Laravel application files uploaded to server
- Domain configured in Hostinger

## Step-by-Step Deployment Process

### Step 1: Connect to Server and Navigate to Project
```bash
ssh u622957711@your-server
cd /home/u622957711/public_html/public_html/cmapp
```

### Step 2: Update Composer to Version 2
```bash
# Download Composer 2
curl -sS https://getcomposer.org/installer | php

# Verify version
php composer.phar --version
```

### Step 3: Install Dependencies
```bash
php composer.phar install --optimize-autoloader --no-dev
```

### Step 4: Create Missing Laravel Directories
```bash
mkdir -p bootstrap/cache
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p routes
mkdir -p app
mkdir -p config
mkdir -p resources
mkdir -p public
mkdir -p lang
```

### Step 5: Create Essential Laravel Files

#### Create bootstrap/app.php
```bash
cat > bootstrap/app.php << 'EOF'
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
EOF
```

#### Create routes/web.php
```bash
cat > routes/web.php << 'EOF'
<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
EOF
```

#### Create routes/console.php
```bash
cat > routes/console.php << 'EOF'
<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
EOF
```

#### Create resources/views/welcome.blade.php
```bash
mkdir -p resources/views
cat > resources/views/welcome.blade.php << 'EOF'
<!DOCTYPE html>
<html>
<head>
    <title>Laravel</title>
</head>
<body>
    <h1>Welcome to Laravel!</h1>
    <p>Your Laravel application is working.</p>
</body>
</html>
EOF
```

#### Create config/app.php
```bash
cat > config/app.php << 'EOF'
<?php

return [
    'name' => env('APP_NAME', 'Laravel'),
    'env' => env('APP_ENV', 'production'),
    'debug' => env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'timezone' => 'UTC',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'faker_locale' => 'en_US',
    'key' => env('APP_KEY'),
    'cipher' => 'AES-256-CBC',
];
EOF
```

#### Create public/index.php
```bash
cat > public/index.php << 'EOF'
<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
EOF
```

### Step 6: Create Database (if using SQLite)
```bash
mkdir -p database
touch database/database.sqlite
chmod 664 database/database.sqlite
```

### Step 7: Set Proper Permissions
```bash
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### Step 8: Test Laravel Installation
```bash
php artisan list
```

### Step 9: Run Migrations
```bash
php artisan migrate
```

### Step 10: Run Laravel Optimization Commands
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 11: Configure Document Root in Hostinger
In your Hostinger control panel:
1. Go to "Domains" or "File Manager"
2. Set document root for your domain to:
   ```
   /home/u622957711/public_html/public_html/cmapp/public
   ```

### Step 12: Test Your Application
Visit your domain: `https://your-domain.com`

## Troubleshooting Commands

### If artisan commands don't work:
```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Check for errors
tail -n 50 storage/logs/laravel.log
```

### For future package management:
```bash
# Use Composer 2 for package management
php composer.phar install
php composer.phar update
```

### For future deployments:
```bash
# After configuration changes
php artisan config:cache

# After adding new routes
php artisan route:cache

# After updating views
php artisan view:cache
```

## Important Notes
- Always use `php composer.phar` instead of `composer` on Hostinger
- Make sure document root points to the `public` directory
- Keep storage and bootstrap/cache directories writable
- Monitor `storage/logs/laravel.log` for errors

## Common Issues and Solutions

### Issue: "This Page Does Not Exist"
**Solution:** Check that document root is pointing to the `public` directory, not the Laravel root.

### Issue: Composer version errors
**Solution:** Use `php composer.phar` instead of `composer` command.

### Issue: Permission denied errors
**Solution:** Set proper permissions with `chmod -R 755 storage bootstrap/cache`.

### Issue: Missing directories
**Solution:** Create all required Laravel directories as shown in Step 4.

This process should work for any Laravel 12 application on Hostinger shared hosting. 