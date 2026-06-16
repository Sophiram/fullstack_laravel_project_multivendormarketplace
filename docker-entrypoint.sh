#!/bin/bash

# Clear old config to ensure fresh data from Render/Railway
php artisan config:clear
php artisan cache:clear

# ============================================================
# Wait for MySQL to be ready before running migrations
# ============================================================
echo "Waiting for MySQL database to be ready..."

# 💡 កែប្រែត្រង់ចំណុចនេះ៖ ឱ្យវាយកតម្លៃពី DB_HOST ឬ MYSQLHOST ដែលបានមកពី Environment
TARGET_HOST=${DB_HOST:-$MYSQLHOST}
TARGET_PORT=${DB_PORT:-$MYSQLPORT}

# បើនៅតែទទេ ឱ្យយកតម្លៃ Default នេះ
TARGET_HOST=${TARGET_HOST:-127.0.0.1}
TARGET_PORT=${TARGET_PORT:-3306}

MAX_ATTEMPTS=30
ATTEMPT=1

# Try to connect to database with retry logic
while [ $ATTEMPT -le $MAX_ATTEMPTS ]; do
    echo "Attempt $ATTEMPT/$MAX_ATTEMPTS: Checking database connection to $TARGET_HOST:$TARGET_PORT..."

    if nc -z "$TARGET_HOST" "$TARGET_PORT" 2>/dev/null; then
        echo "✓ Database is reachable!"
        break
    fi

    if [ $ATTEMPT -eq $MAX_ATTEMPTS ]; then
        echo "✗ Failed to connect to database after $MAX_ATTEMPTS attempts"
        echo "Continuing anyway... migrations might fail if database is not ready"
        break
    fi

    ATTEMPT=$((ATTEMPT + 1))
    sleep 2
done

# Cache configurations សម្រាប់ល្បឿនលឿននៅលើ Production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ============================================================
# Run migrations and seeders
# ============================================================
echo ""
echo "Running Migrations..."
php artisan migrate --force

if [ $? -eq 0 ]; then
    echo "✓ Migrations completed successfully"
else
    echo "✗ Migrations failed"
fi

echo ""
echo "Running Seeders..."
php artisan db:seed --force

if [ $? -eq 0 ]; then
    echo "✓ Seeders completed successfully"
else
    echo "⚠ Seeders failed (this is OK if data already exists)"
fi

echo ""
echo "Starting Apache..."
exec apache2-foreground
