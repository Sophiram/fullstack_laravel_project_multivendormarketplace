#!/bin/bash

echo "Cleaning and caching configuration..."
# សម្អាត Cache គ្រប់ប្រភេទមុនពេលចាប់ផ្ដើម Apache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# បង្កើត Cache ថ្មី (នេះជាកន្លែងដែលវាអាន Environment Variables ថ្មីៗចូល)
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Starting Apache..."
exec apache2-foreground
