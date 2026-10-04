FROM php:8.2-apache

WORKDIR /var/www/html

# Copy application files
COPY public/ /var/www/html/
COPY secret /secret

# Ensure uploads directory exists and is writable
RUN mkdir -p /var/www/html/uploads \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod 777 /var/www/html/uploads

EXPOSE 80

CMD ["apache2-foreground"]
