#!/bin/sh

sed -i "s/__PORT__/${PORT:-8080}/g" /etc/nginx/sites-enabled/default

php-fpm -D

php artisan storage:link --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan migrate --force

nginx -g "daemon off;"
