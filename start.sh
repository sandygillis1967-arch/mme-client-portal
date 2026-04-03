#!/bin/sh
set -e

echo "PORT is: ${PORT}"
echo "PHP version:"
php --version

echo "Running migrations..."
php artisan migrate --force --seed

echo "Starting server on 0.0.0.0:${PORT}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT}
