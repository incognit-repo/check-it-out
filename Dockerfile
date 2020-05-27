FROM php:7.4.2-fpm-alpine
WORKDIR /app

RUN apk --update upgrade \
    && apk add --no-cache autoconf automake make gcc g++ icu-dev zsh git supervisor rabbitmq-c rabbitmq-c-dev vim inotify-tools\
    && pecl install apcu-5.1.18 \
    && pecl install xdebug-2.9.5 \
    && pecl install amqp-1.9.4 \
    && docker-php-ext-install -j$(nproc) \
        opcache \
        intl \
    && docker-php-ext-enable \
        amqp \
        apcu \
        opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY etc/infrastructure/php/ /usr/local/etc/php/
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
