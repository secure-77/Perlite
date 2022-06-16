FROM php:7.4-fpm

MAINTAINER sec77 https://github.com/secure-77/Perlite

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

WORKDIR /var/www/perlite/
COPY ./*.php ./
COPY ./*.svg ./
COPY ./*.ico ./
COPY ./.styles/ ./.styles/
COPY ./.js/ ./.js/

VOLUME /var/www/perlite/

EXPOSE 9000
