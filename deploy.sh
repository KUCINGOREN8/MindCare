#!/bin/bash

# Build ke dist
echo "Building assets to dist folder..."
npm install --legacy-peer-deps --force
npm run build 2>&1 || npx vite build 2>&1

# Fallback ke dist
if [ ! -f "public/dist/assets/app.css" ]; then
    echo "Manual Tailwind build..."
    npx tailwindcss -i resources/css/app.css -o public/dist/assets/app.css --minify
fi

# Laravel
php artisan migrate --force
php artisan db:seed --force
php artisan optimize:clear
php artisan serve --host=0.0.0.0 --port=${PORT}
