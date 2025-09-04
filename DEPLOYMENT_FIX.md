# Deployment Fix: SQLite Database Issue

## Problem
The error indicates that the SQLite database file doesn't exist on the production server:
```
Database file at path [/home/u622957711/domains/aliandothman.com.kw/public_html/cmsys/database/database.sqlite] does not exist
```

## Solution Options

### Option 1: Create SQLite Database (Recommended for Simple Deployment)

1. **SSH into your server** and navigate to your project directory:
   ```bash
   cd /home/u622957711/domains/aliandothman.com.kw/public_html/cmsys
   ```

2. **Create the SQLite database file**:
   ```bash
   touch database/database.sqlite
   ```

3. **Set proper permissions**:
   ```bash
   chmod 664 database/database.sqlite
   chown www-data:www-data database/database.sqlite
   ```

4. **Run migrations**:
   ```bash
   php artisan migrate
   ```

5. **Seed the database** (if needed):
   ```bash
   php artisan db:seed
   ```

### Option 2: Switch to MySQL (Recommended for Production)

1. **Update your `.env` file** on the server:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

2. **Create MySQL database** (if not exists):
   ```sql
   CREATE DATABASE your_database_name;
   ```

3. **Run migrations**:
   ```bash
   php artisan migrate
   ```

4. **Seed the database**:
   ```bash
   php artisan db:seed
   ```

### Option 3: Use Absolute Path for SQLite

1. **Update your `.env` file**:
   ```env
   DB_CONNECTION=sqlite
   DB_DATABASE=/home/u622957711/domains/aliandothman.com.kw/public_html/cmsys/database/database.sqlite
   ```

2. **Create the database file**:
   ```bash
   touch /home/u622957711/domains/aliandothman.com.kw/public_html/cmsys/database/database.sqlite
   ```

3. **Set permissions**:
   ```bash
   chmod 664 /home/u622957711/domains/aliandothman.com.kw/public_html/cmsys/database/database.sqlite
   ```

## Quick Fix Commands

Run these commands in your project directory:

```bash
# Create database file
touch database/database.sqlite

# Set permissions
chmod 664 database/database.sqlite

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Clear cache
php artisan cache:clear
php artisan config:clear
```

## Additional Steps

1. **Check file permissions**:
   ```bash
   ls -la database/
   ```

2. **Verify database connection**:
   ```bash
   php artisan tinker --execute="echo 'Database connected successfully';"
   ```

3. **Test the application**:
   Visit your website to ensure it's working properly.

## Troubleshooting

If you still get errors:

1. **Check if SQLite is installed**:
   ```bash
   php -m | grep sqlite
   ```

2. **Verify PHP PDO extension**:
   ```bash
   php -m | grep pdo
   ```

3. **Check Laravel logs**:
   ```bash
   tail -f storage/logs/laravel.log
   ```

## Recommended Production Setup

For production, consider using MySQL instead of SQLite:

1. **Install MySQL** (if not already installed)
2. **Create a dedicated database**
3. **Update environment variables**
4. **Run migrations and seeders**

This provides better performance and reliability for production environments.

