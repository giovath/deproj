# -------------------------
# Stage 1 — Build (Composer)
# -------------------------
FROM php:8.3-fpm AS builder

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev

# PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql zip

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Workdir
WORKDIR /var/www/html

# Copy app files
COPY . .

# Install dependencies optimized for production
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# -------------------------
# Stage 2 — Production image
# -------------------------
FROM php:8.3-fpm

# Install only what is needed at runtime
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_pgsql zip

WORKDIR /var/www/html

# Copy built app
COPY --from=builder /var/www/html /var/www/html

# Permissions for storage/bootstrap
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 8000

##CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000


