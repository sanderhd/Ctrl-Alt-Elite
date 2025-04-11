FROM php:8.2-apache

# Kopieer je app naar de Apache folder
COPY . /var/www/html/

# Zorg dat de juiste rechten zijn ingesteld
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html