# This file defines the Docker image for the PHP-FPM service in the Steam City application. Installs necessary PHP extensions and dependencies, configures OPcache for performance, and sets up a non-root user for security.
# Finally, it exposes port 9000 for PHP-FPM to listen on.
FROM php:8.2-fpm-alpine

# Install php extensions and dependencies
RUN apk add --no-cache \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        mysqli \
        gd \
        opcache

# Configuration OPcache for performance
RUN { \
        echo 'opcache.enable=1'; \
        echo 'opcache.memory_consumption=128'; \
        echo 'opcache.interned_strings_buffer=8'; \
        echo 'opcache.max_accelerated_files=4000'; \
        echo 'opcache.revalidate_freq=2'; \
        echo 'opcache.fast_shutdown=1'; \
    } > /usr/local/etc/php/conf.d/opcache.ini

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create a non-root user and group for better security
RUN addgroup -g 1000 www && \
    adduser -D -u 1000 -G www www

# Define the working directory and set permissions
RUN chown -R www:www /var/www/html

USER www

WORKDIR /var/www/html

EXPOSE 9000

CMD ["php-fpm"]