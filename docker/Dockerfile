# Nginx + PHP-FPM в одном контейнере (supervisord)
FROM php:8.2-fpm-alpine

RUN apk add --no-cache nginx supervisor \
    && mkdir -p /var/log/supervisor /run/nginx

# PHP-FPM только на localhost (nginx в том же контейнере)
RUN sed -i 's|listen = 9000|listen = 127.0.0.1:9000|' /usr/local/etc/php-fpm.d/www.conf

COPY supervisord.conf /etc/supervisord.conf
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Заготовка nginx (в проде перекрывается томом ./nginx.conf)
COPY nginx.conf /etc/nginx/http.d/default.conf

WORKDIR /usr/share/nginx/html

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
