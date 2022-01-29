FROM nginx:1.16.1-alpine

RUN adduser -u 1000 -g 1000 -G www-data -D -H www-data

COPY ./.docker/nginx/sites /etc/nginx/conf.d
COPY ./.docker/nginx/nginx.conf /etc/nginx/

#COPY ./ /var/www

ENTRYPOINT ["nginx"]

EXPOSE 80
