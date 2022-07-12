#!/bin/bash
sleep 30
composer install --ignore-platform-reqs
php artisan config:clear
php artisan config:cache
php artisan migrate
set -m
php-fpm &
fg %1
