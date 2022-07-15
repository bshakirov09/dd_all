FROM php:7.4-fpm

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN apt-get update && echo "1"
RUN apt-get install -y apt-utils libpq-dev unzip libzip-dev libpng-dev libfreetype6-dev git
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
COPY --from=composer/composer /usr/bin/composer /usr/bin/composer
WORKDIR /tmp
COPY composer-setup.php .
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer --1
RUN rm composer-setup.php
RUN docker-php-ext-configure gd --with-freetype
#RUN docker-php-ext-install zip pdo pdo_pgsql pdo_mysql gd
RUN docker-php-ext-install zip pdo pdo_mysql exif gd
RUN install-php-extensions imagick
COPY cmd.sh .
RUN chmod u+x cmd.sh
COPY conf/php.ini $PHP_INI_DIR/conf.d/custom-001.ini
WORKDIR /var/www/html
CMD ["bash","/tmp/cmd.sh"]
