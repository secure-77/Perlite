FROM php:fpm-alpine

LABEL maintainer="sec77 https://github.com/secure-77/Perlite"

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

WORKDIR /var/www/perlite/
COPY ./index.php ./
COPY ./helper.php ./
COPY ./content.php ./
COPY ./*.svg ./
COPY ./*.ico ./
COPY ./.styles/ ./.styles/
COPY ./.js/ ./.js/
COPY ./.src/ ./.src/
COPY ./vendor/ ./vendor/

VOLUME /var/www/perlite/

EXPOSE 9000
