#!/bin/sh
set -e
cd /var/www/html

if [ ! -d storage/framework ]; then
  mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views
fi
mkdir -p storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache || true

exec "$@"
