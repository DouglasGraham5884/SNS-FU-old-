FROM php:8.1.12-apache

RUN apt update \
    && docker-php-ext-install pdo_mysql

COPY src/ /var/www/html/
# COPY ./php/php.ini /usr/local/etc/php/php.ini