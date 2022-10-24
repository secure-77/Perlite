FROM php:fpm-alpine

MAINTAINER sec77 https://github.com/secure-77/Perlite

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && install-php-extensions yaml

WORKDIR /var/www/perlite/
COPY ./*.php ./
COPY ./*.svg ./
COPY ./*.ico ./
COPY ./.styles/ ./.styles/
COPY ./.js/ ./.js/

VOLUME /var/www/perlite/

EXPOSE 9000
