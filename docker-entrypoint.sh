#!/bin/bash

# Clear old config to ensure fresh data from Render/Railway
php artisan config:clear
php artisan cache:clear

# Optimize for production configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ============================================================
# 🚀 បើកដំណើរការ Web Server ភ្លាមៗ (ការពារកំហុស 502)
# ============================================================
echo "Starting Apache Web Server..."
exec apache2-foreground
