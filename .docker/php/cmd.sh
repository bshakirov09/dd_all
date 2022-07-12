#!/bin/bash
sleep 30
composer install
set -m
php-fpm &
fg %1
