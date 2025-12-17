# Use an official PHP image with Apache
FROM php:8.2-apache

# Install MySQL extensions for PHP (mysqli and pdo_mysql)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy application source code into the web directory
COPY ./app /var/www/html/

# Set permissions for the uploads folder to allow image saving
RUN chown -R www-data:www-data /var/www/html/uploads && \
    chmod -R 755 /var/www/html/uploads

# Expose port 80 for web traffic
EXPOSE 80