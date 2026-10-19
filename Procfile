web: vendor/bin/heroku-php-apache2 public/
worker: php artisan queue:work redis --sleep=3 --tries=3 --timeout=90 --max-time=3600
release: php artisan school:check-production && php artisan migrate --force
