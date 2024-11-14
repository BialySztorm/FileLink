FROM php:8.2-fpm
LABEL authors="andrz"

# Instalujemy niezbędne rozszerzenia
RUN docker-php-ext-install pdo pdo_mysql

# Instalacja Xdebug
# RUN pecl install xdebug-3.1.5 && docker-php-ext-enable xdebug



# Konfiguracja Xdebug
RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/xdebug.ini

WORKDIR /var/www/html
