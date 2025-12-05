#!/bin/bash
echo "Using pre-built assets from local"

# Hanya install AlpineJS & dependencies ringan
npm install alpinejs --save-dev --legacy-peer-deps 2>/dev/null || true

# Laravel setup
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start server
php artisan serve --host=0.0.0.0 --port=${PORT}
