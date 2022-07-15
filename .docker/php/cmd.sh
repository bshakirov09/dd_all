#!/bin/bash
sleep 10
composer install --no-scripts
php artisan migrate
set -m
php-fpm &
fg %1
