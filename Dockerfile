# REPLACE the old 'FROM php:8.2-apache' with this mandatory line:
FROM nexus-service-for-docker-hosted-registry.nexus.svc.cluster.local:8085/php:8.2-apache

# Keep the rest of your installation steps exactly the same
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Ensure code is copied to the correct Apache directory
COPY ./app/event-promotion-website /var/www/html/

# Ensure proper permissions for image uploads
RUN mkdir -p /var/www/html/uploads && \
    chown -R www-data:www-data /var/www/html/uploads && \
    chmod -R 755 /var/www/html/uploads

EXPOSE 80