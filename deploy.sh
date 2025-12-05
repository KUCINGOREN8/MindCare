#!/bin/bash
echo "=== CDN-ONLY DEPLOYMENT ==="

# Skip semua npm processes
echo "Skipping npm install & build (using CDN)"

# Laravel setup
php artisan migrate --force
php artisan db:seed --force

# Optimize dan cache
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start server
exec php artisan serve --host=0.0.0.0 --port=${PORT}
