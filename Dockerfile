FROM php:7.3-fpm-alpine

WORKDIR /var/www/

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

COPY . .

RUN composer install