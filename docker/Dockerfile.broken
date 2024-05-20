FROM php:8.3-fpm-alpine

ARG user
ARG uid

RUN apk update && apk add \
    git \
    curl \
    curl-dev \
    oniguruma-dev \
    libpng-dev \
    libxml2-dev
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd soap xml curl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN adduser --uid $uid --home /home/$user --disabled-password $user
RUN adduser $user www-data
RUN adduser $user root
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

WORKDIR /var/www

USER $user