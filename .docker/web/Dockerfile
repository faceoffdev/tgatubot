FROM ghcr.io/faceoffdev/workspace/php-nginx:81

COPY --chown=${USER}:${USER} .docker/web/rootfs /
COPY --chown=${USER}:${USER} . ${WORKDIR}

RUN composer install --no-dev --classmap-authoritative --no-scripts \
    && composer dump-autoload --no-interaction --no-scripts --classmap-authoritative

HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/ping
