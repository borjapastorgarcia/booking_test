FROM php:8.2-fpm-alpine

LABEL maintainer=<borjapg616@gmail.com>

RUN apk add --no-cache --virtual .deps zlib-dev libzip-dev zip git sqlite-dev bash \
    #&& docker-php-ext-configure zip --with-libzip \
    && docker-php-ext-install zip pdo_sqlite mysqli pdo pdo_mysql

ADD start.sh /start.sh

RUN chmod 775 /start.sh

CMD ["/start.sh"]
