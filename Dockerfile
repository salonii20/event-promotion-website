# MANDATORY: Pull from the local Nexus mirror
FROM nexus-service-for-docker-hosted-registry.nexus.svc.cluster.local:8085/php:8.2-apache

# Rest of the file remains the same to maintain app functionality
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy source code to the container
COPY ./app/event-promotion-website /var/www/html/

# Set permissions for uploads
RUN mkdir -p /var/www/html/uploads && \
    chown -R www-data:www-data /var/www/html/uploads && \
    chmod -R 755 /var/www/html/uploads

EXPOSE 80