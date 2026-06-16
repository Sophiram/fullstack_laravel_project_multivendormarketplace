#!/bin/bash

# បង្ហាញសារថាកំពុងដំណើរការ Migration
echo "Running Migrations and Seeders..."

# ដំណើរការ Migration និង Seeder (ប្រើ --force ព្រោះនេះជា Production environment)
php artisan migrate --force
php artisan db:seed --force

# ចាប់ផ្តើមដំណើរការ Apache Web Server
echo "Starting Apache..."
apache2-foreground
