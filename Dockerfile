FROM php:8.2-apache

# Enable needed modules
RUN docker-php-ext-install opcache

# Copy app
COPY public/ /var/www/html/
COPY secret /secret

# Make uploads writable by www-data
RUN chown -R www-data:www-data /var/www/html/uploads \
    && chmod -R 755 /var/www/html \
    && chmod 777 /var/www/html/uploads

# Apache: allow .php in uploads and follow the app
ENV APACHE_DOCUMENT_ROOT=/var/www/html
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

EXPOSE 80

CMD ["apache2-foreground"]
