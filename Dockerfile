# Use an official PHP image with Apache
FROM php:8.2-apache

# Install MySQL extensions for PHP (mysqli and pdo_mysql)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy application source code into the web directory
COPY ./app/event-promotion-website /var/www/html/

# Create the uploads folder and set permissions to allow image saving
RUN mkdir -p /var/www/html/uploads && \
    chown -R www-data:www-data /var/www/html/uploads && \
    chmod -R 755 /var/www/html/uploads

# Expose port 80 for web traffic
EXPOSE 80