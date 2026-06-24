#!/bin/bash

# ទុកអាជួរ Echo នេះដើម្បីផ្ទៀងផ្ទាត់មើល Log ក្នុង Render
echo "DB_HOST=$DB_HOST"
echo "DB_PORT=$DB_PORT"
echo "DB_DATABASE=$DB_DATABASE"
echo "DB_USERNAME=$DB_USERNAME"

echo "Cleaning caches..."
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# echo "Running database migrations and seeding..."
# # === កែប្រែជួរកូដខាងក្រោមនេះ ដោយថែម --seed ទៅខាងចុង ===
# php artisan migrate:fresh --force --seed
php artisan migrate --force

# echo "Running database migrations..."
# php artisan migrate:fresh --force

# echo "Running database seeds..."
# # ត្រូវប្រាកដថាបានថែម --force ដូចខាងក្រោមនេះ
# php artisan db:seed --force

echo "Starting Apache..."
exec apache2-foreground
