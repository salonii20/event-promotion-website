# Using the library path which is more stable for cluster pulls
FROM docker.io/library/php:8.2-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

# Ensure code is copied to the correct Apache directory
COPY ./app/event-promotion-website /var/www/html/

# Set permissions for uploads
RUN mkdir -p /var/www/html/uploads && \
    chown -R www-data:www-data /var/www/html/uploads && \
    chmod -R 755 /var/www/html/uploads

EXPOSE 80