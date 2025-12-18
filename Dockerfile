# Use the local Nexus mirror to avoid Docker Hub rate limits
FROM nexus-service-for-docker-hosted-registry.nexus.svc.cluster.local:8085/php:8.2-apache

# Install MySQL extensions needed for your app
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy your source code into the container
COPY ./app/event-promotion-website /var/www/html/

# Create the uploads directory and set correct permissions
RUN mkdir -p /var/www/html/uploads && \
    chown -R www-data:www-data /var/www/html/uploads && \
    chmod -R 755 /var/www/html/uploads

# Expose port 80
EXPOSE 80