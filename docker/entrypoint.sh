#!/bin/sh
set -eu
: "${PORT:=10000}"
case "$PORT" in ''|*[!0-9]*) echo "PORT must be numeric" >&2; exit 1;; esac
if [ "$PORT" -lt 1024 ] || [ "$PORT" -gt 65535 ]; then echo "PORT must be between 1024 and 65535" >&2; exit 1; fi
printf 'Listen %s\n' "$PORT" > /etc/apache2/ports.conf
sed -i "s/__PORT__/$PORT/g" /etc/apache2/sites-available/000-default.conf
mkdir -p storage/framework/cache/data storage/framework/cache/locks storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
php artisan package:discover --no-interaction
php artisan school:prepare-deployment --no-interaction
php artisan config:cache --no-interaction
php artisan route:cache --no-interaction
php artisan view:cache --no-interaction
chown -R www-data:www-data storage bootstrap/cache
exec "$@"
