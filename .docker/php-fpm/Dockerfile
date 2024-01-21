FROM php:8.3-fpm

RUN apt-get update && \
    apt-get install -y libzip-dev zip && \
    docker-php-ext-install pdo pdo_mysql zip

# Copiar www.conf a la ubicación adecuada
COPY .docker/php-fpm/php-fpm.d/www.conf /usr/local/etc/php-fpm.d/www.conf

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY . /var/www/html

# Establecer permisos en el directorio
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 775 /var/www/html

# Establecer permisos específicos
RUN chmod -R 777 storage bootstrap
