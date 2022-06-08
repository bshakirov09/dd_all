#!/bin/bash
composer install
set -m
php-fpm &
fg %1
