FROM php:8.4-fpm

# -----------------------------
# 1. Instalar dependências do sistema
# -----------------------------
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip unzip \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    gnupg \
    ca-certificates \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql zip bcmath gd


# -----------------------------
# # Instalar extensão Redis
# # -----------------------------
# RUN pecl install redis && docker-php-ext-enable redis

# -----------------------------
# 3. Instalar Composer
# -----------------------------
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# -----------------------------
# 4. Definir diretório de trabalho
# -----------------------------
WORKDIR /var/www

COPY . .

RUN composer install

# -----------------------------
# 5. Permissões de cache e storage
# -----------------------------
RUN mkdir -p /var/www/storage /var/www/bootstrap/cache && \
    chown -R www-data:www-data /var/www && \
    chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# -----------------------------
# 6. Expor porta FPM
# -----------------------------
EXPOSE 9000

CMD ["php-fpm"]
