# Use the official PHP Apache image
FROM php:8.2-apache

# Install MySQL extensions needed for your app
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy your source code into the container
# Matches the 'app/' structure in the reference guide
COPY ./app/event-promotion-website /var/www/html/

# Create the uploads directory and set correct permissions
# Fulfills 'Application pod is running' requirement for writable folders
RUN mkdir -p /var/www/html/uploads && \
    chown -R www-data:www-data /var/www/html/uploads && \
    chmod -R 755 /var/www/html/uploads

# Expose port 80 as defined in your service.yaml
EXPOSE 80