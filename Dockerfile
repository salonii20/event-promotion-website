FROM nexus-service-for-docker-hosted-registry.nexus.svc.cluster.local:8085/php:8.2-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY ./app/event-promotion-website /var/www/html/

RUN mkdir -p /var/www/html/uploads && \
    chown -R www-data:www-data /var/www/html/uploads && \
    chmod -R 755 /var/www/html/uploads

EXPOSE 80