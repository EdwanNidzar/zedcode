# ============================================================
# Stage 1: Build frontend assets (Node 20 Alpine)
# ============================================================
FROM node:20-alpine AS node-builder

WORKDIR /app

# Cache layer: copy dependency files first
COPY package.json package-lock.json ./
RUN npm ci --ignore-scripts

# Copy source and build
COPY . .
RUN npm run build


# ============================================================
# Stage 2: Install PHP dependencies (Composer)
# ============================================================
FROM composer:2 AS composer-builder

WORKDIR /app

# Cache layer: copy dependency files first
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --ignore-platform-reqs

# Copy full source and generate optimized autoloader
COPY . .
RUN mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache bootstrap/cache \
    && composer dump-autoload --optimize --no-dev


# ============================================================
# Stage 3: Production image (PHP 8.3-FPM + Nginx + Supervisor)
# ============================================================
FROM php:8.3-fpm-alpine AS production

LABEL maintainer="Zedcore Team"
LABEL org.opencontainers.image.description="Zedcore - Web App Cuti & Handbook SOP"

# Install system dependencies + PHP extensions
RUN apk add --no-cache \
    nginx \
    supervisor \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    libxml2-dev \
    curl \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        bcmath \
        gd \
        intl \
        opcache \
        pcntl \
        zip \
    && apk del libpng-dev libjpeg-turbo-dev freetype-dev libzip-dev icu-dev oniguruma-dev libxml2-dev \
    && rm -rf /var/cache/apk/*

# PHP OPcache tuning for production
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=256" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=20000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.revalidate_freq=0" >> /usr/local/etc/php/conf.d/opcache.ini

# Set PHP upload & execution limits
RUN echo "upload_max_filesize=50M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "post_max_size=50M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "memory_limit=256M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "max_execution_time=60" >> /usr/local/etc/php/conf.d/custom.ini

# Configure PHP-FPM to use www-data user
RUN sed -i 's/user = www-data/user = www-data/g' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i 's/group = www-data/group = www-data/g' /usr/local/etc/php-fpm.d/www.conf

# Copy Nginx & Supervisor configs
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

WORKDIR /var/www/html

# Copy entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Copy PHP vendor from composer-builder
COPY --from=composer-builder /app/vendor ./vendor
COPY --from=composer-builder /app/composer.json ./composer.json

# Copy built frontend assets from node-builder
COPY --from=node-builder /app/public/build ./public/build

# Copy application source (after vendor & build to maximize cache hits)
COPY . .

# Create required directories & set permissions
RUN mkdir -p \
    storage/framework/sessions \
    storage/framework/views \
    storage/framework/cache \
    storage/logs \
    bootstrap/cache \
    public/storage \
    /var/log/nginx \
    /var/log/supervisor \
    /run/nginx \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Expose HTTP port
EXPOSE 80

# Health check — Laravel has /up route built-in since v11
HEALTHCHECK --interval=30s --timeout=10s --start-period=60s --retries=3 \
    CMD curl -f http://localhost/up || exit 1

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
