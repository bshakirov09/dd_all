#!/bin/bash
sleep 60
composer update
#php artisan config:clear
#php artisan config:cache
#php artisan migrate
set -m
php-fpm &
fg %1
