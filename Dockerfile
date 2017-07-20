FROM php:7.1-alpine
MAINTAINER iLyK Necromancer <necromancer@toavalon.com>

RUN set -ex \
  && apk --no-cache add \
    postgresql-dev

RUN docker-php-ext-install pdo pdo_pgsql pdo_mysql

WORKDIR /app
COPY . /app

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

CMD php artisan config:clear &&\
    php artisan optimize &&\
    php artisan config:cache &&\
    php artisan migrate &&\
    php artisan serve --host=0.0.0.0 --port=8181

EXPOSE 8181
