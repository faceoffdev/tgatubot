FROM php:8.1-fpm-alpine

RUN apk add --no-cache \
    autoconf \
    make \
    git \
    gcc \
    libc-dev \
    libgcrypt-dev \
    oniguruma-dev \
    postgresql-dev \
    bash

RUN docker-php-ext-configure pdo_pgsql --with-pdo-pgsql \
    && docker-php-ext-configure pdo_mysql --with-pdo-mysql \
    && docker-php-ext-configure mbstring --enable-mbstring \
    && docker-php-ext-configure opcache --enable-opcache \
    && docker-php-ext-install \
        bcmath \
        mbstring \
        pdo_pgsql \
        opcache \
    && pecl install xdebug && docker-php-ext-enable xdebug

COPY ./.docker/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
COPY ./.docker/php/serialize_precision.ini /usr/local/etc/php/conf.d/serialize_precision.ini
COPY ./.docker/php/docker-php-ext-opcache.ini /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini

ARG WORKDIR=/var/www/
WORKDIR ${WORKDIR}

RUN deluser www-data  && addgroup -g 1000 www-data \
    && adduser -u 1000 -g 1000 -G www-data -D -H www-data \
    && chown -R www-data:www-data ${WORKDIR} /home/www-data

USER www-data

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ARG GITHUB_TOKEN
RUN composer config -g github-oauth.github.com ${GITHUB_TOKEN}

RUN composer global require franzl/studio

ARG LIBRARY_PATH

ENV LIBS=${LIBRARY_PATH}
ENV PATH="/home/www-data/.composer/vendor/bin:${PATH}"

RUN bash -c "$(curl -fsSL https://raw.githubusercontent.com/ohmybash/oh-my-bash/master/tools/install.sh)" && \
sed -i 's/OSH_THEME="font"/OSH_THEME="agnoster"/' ~/.bashrc

ENTRYPOINT ["php-fpm"]

EXPOSE 9000
