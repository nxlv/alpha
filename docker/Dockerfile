FROM php:8.3.7RC1-apache-bullseye

ARG user
ARG uid

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

USER root

RUN a2enmod rewrite

RUN adduser --uid $uid --home /home/$user --disabled-password $user
RUN adduser $user www-data
RUN adduser $user root

#COPY platform-exports/webserver/sites /etc/apache2/sites-available
#RUN a2enmod rewrite

RUN curl -sSLf \
        -o /usr/local/bin/install-php-extensions \
        https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions && \
    chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions curl gd mbstring mysqli pdo pdo_mysql soap xml

USER $user

RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

WORKDIR /var/www
