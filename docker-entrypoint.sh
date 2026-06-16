#!/bin/bash

# សម្អាត Config ចាស់ៗសិន ដើម្បីឱ្យប្រាកដថាវាទទួលយកទិន្នន័យថ្មីពី Render
php artisan config:clear

# បង្ហាញសារថាកំពុងដំណើរការ Migration
echo "Running Migrations and Seeders..."

# ដំណើរការ Migration និង Seeder
php artisan migrate --force
php artisan db:seed --force

# ចាប់ផ្តើមដំណើរការ Apache Web Server
echo "Starting Apache..."
apache2-foreground
