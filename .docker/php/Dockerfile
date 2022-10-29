FROM alpine:latest

ENV PHP_POST_MAX_SIZE=${PHP_POST_MAX_SIZE:-"8M"} \
    PHP_UPLOAD_MAX_FILESIZE=${PHP_UPLOAD_MAX_FILESIZE:-"2M"} \
    PHP_MEMORY_LIMIT=${PHP_MEMORY_LIMIT:-"256M"}

ENV PHP_OPCACHE_MAX_ACCELERATED_FILES=${PHP_OPCACHE_MAX_ACCELERATED_FILES:-16229} \
    PHP_OPCACHE_ENABLE_CLI=${PHP_OPCACHE_ENABLE_CLI:-0} \
    PHP_OPCACHE_MEMORY_CONSUMPTION=${PHP_OPCACHE_MEMORY_CONSUMPTION:-256} \
    PHP_OPCACHE_VALIDATE_TIMESTAMPS=${PHP_OPCACHE_VALIDATE_TIMESTAMPS:-0} \
    PHP_OPCACHE_JIT_BUFFER_SIZE=${PHP_OPCACHE_JIT_BUFFER_SIZE:-"128M"} \
    PHP_OPCACHE_INTERNED_STRINGS_BUFFER=${PHP_OPCACHE_INTERNED_STRINGS_BUFFER:-64}

ENV PHP_FPM_TYPE=${PHP_FPM_TYPE} \
    PHP_FPM_MAX_CHILDREN=${PHP_FPM_MAX_CHILDREN} \
    PHP_FPM_START_SERVERS=${PHP_FPM_START_SERVERS} \
    PHP_FPM_MAX_SPARE_SERVERS=${PHP_FPM_MAX_SPARE_SERVERS} \
    PHP_FPM_MIN_SPARE_SERVERS=${PHP_FPM_MIN_SPARE_SERVERS} \
    PHP_FPM_REQUEST_TERMINATE_TIMEOUT=${PHP_FPM_REQUEST_TERMINATE_TIMEOUT:-60} \
    PHP_FPM_MAX_REQUESTS=${PHP_FPM_MAX_REQUESTS} \
    PHP_FPM_PROCESS_IDLE_TIMEOUT=${PHP_FPM_PROCESS_IDLE_TIMEOUT:-"10s"}

RUN apk add --no-cache --update \
    php81 \
    php81-phar \
    php81-openssl \
    php81-ctype \
    php81-bcmath \
    php81-mbstring \
    php81-json \
    php81-opcache \
    php81-fileinfo \
    php81-curl \
    php81-redis \
    php81-tokenizer \
    php81-dom \
    php81-simplexml \
    php81-iconv \
    php81-zip \
    php81-pdo_pgsql \
    php81-pdo_mysql \
    php81-pcntl \
    tzdata \
    tini \
    supervisor \
    curl \
    nano \
    git \
    bash \
    nginx \
    php81-fpm \
    && mkdir -p /app \
    && rm -rf /tmp/* \
    /var/{cache,log}/* \
    && ln -s /usr/bin/php81 /usr/bin/php

RUN apk add php81-pecl-grpc --repository=http://dl-cdn.alpinelinux.org/alpine/edge/testing

COPY .docker/php/rootfs /

ARG UID=1000
ARG GID=1000
ENV USER='application'
ENV WORKDIR='/app'
ENV TZ=Europe/Moscow

RUN addgroup --gid ${GID} --system ${USER} \
    && adduser --uid ${UID} --system --ingroup ${USER} ${USER} \
    && chown -R ${USER}:${USER} ${WORKDIR} /home/${USER}

RUN chown -R ${USER}:${USER} /app \
    && chown -R ${USER}:${USER} /run \
    && chown -R ${USER}:${USER} /var/log \
    && chown -R ${USER}:${USER} /usr/lib \
    && chown -R ${USER}:${USER} /etc/php81/conf.d

USER ${USER}

WORKDIR ${WORKDIR}

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN bash -c "$(curl -fsSL https://raw.githubusercontent.com/ohmybash/oh-my-bash/master/tools/install.sh)" && \
sed -i 's/OSH_THEME="font"/OSH_THEME="agnoster"/' ~/.bashrc

COPY --chown=${USER}:${USER} . ${WORKDIR}

RUN composer install --no-dev --classmap-authoritative --no-scripts \
    && composer dump-autoload --no-interaction --no-scripts --classmap-authoritative

ENTRYPOINT ["/sbin/tini", "--", "/usr/bin/docker-entrypoint.sh"]

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
