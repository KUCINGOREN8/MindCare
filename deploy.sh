#!/bin/bash

# Install dependencies dengan legacy-peer-deps
npm install --legacy-peer-deps

# Fix permission untuk Vite
chmod +x node_modules/.bin/vite 2>/dev/null || true

# Coba build dengan berbagai cara
echo "Building assets..."
if [ -f node_modules/.bin/vite ]; then
    ./node_modules/.bin/vite build
elif command -v npx &> /dev/null; then
    npx vite build
else
    echo "Vite not found, skipping build..."
fi

# Laravel optimization
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Start server
exec php artisan serve --host=0.0.0.0 --port=${PORT}
