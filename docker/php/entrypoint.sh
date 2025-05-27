#!/bin/sh

until nc -z -v -w30 mysql 3306
do
  echo "Aguardando MySQL estar disponível..."
  sleep 5
done

composer install
php artisan migrate --force
php artisan test
exec php artisan serve --host=0.0.0.0 --port=8000