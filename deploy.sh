#!/bin/bash
echo "Using CDN for Tailwind and AlpineJS"

# Skip npm install & build
echo "Skipping Vite build (using CDN)"

# Laravel setup
php artisan migrate --force
php artisan db:seed --force
php artisan optimize:clear
php artisan serve --host=0.0.0.0 --port=${PORT}
