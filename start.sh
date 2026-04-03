#!/bin/sh
set -e

echo "Running migrations..."
php artisan migrate --force --seed

echo "Starting PHP server on port ${PORT:-8080}..."
exec php -S 0.0.0.0:${PORT:-8080} -t public
