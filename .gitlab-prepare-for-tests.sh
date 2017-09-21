#!/bin/sh

set -e

# Install php libraries.
echo "Start the update and the install"

# Copy over testing configuration.
rm database.sqlite || true
touch database.sqlite
cp .env.testing .env

# Generate an application key. Re-cache.
echo "Run artisan"
php artisan key:generate
php artisan optimize
php artisan config:cache

# Run database migrations.
echo "Run migration"
php artisan migrate --seed
