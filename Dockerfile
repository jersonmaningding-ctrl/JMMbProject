# Mas lightweight kaysa FPM – sapat na ang CLI server para sa Render
FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev zip libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql zip gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# I-cache muna ang dependencies bago kopyahin ang buong source
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Saka kopyahin ang buong code
COPY . .

# Tiyaking may tamang permission at present ang storage at cache bago ang artisan commands
RUN mkdir -p /var/www/storage /var/www/bootstrap/cache \
    && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Patakbuhin ang artisan package:discover (ngayon writable na ang cache directory)
RUN php artisan package:discover --ansi

# Ilipat ang entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 10000

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["start"]