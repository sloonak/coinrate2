FROM php:8.3-fpm

RUN apt-get update && apt-get install -y gnupg \
    git \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd mbstring zip exif pcntl bcmath opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Установка Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Копирование файлов проекта и установка зависимостей
COPY . /var/www/html
WORKDIR /var/www/html
RUN composer install --no-dev --optimize-autoloader

# Настройка прав доступа
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Открываем порт для PHP-FPM
EXPOSE 9000

# Запускаем PHP-FPM
CMD ["php-fpm"]
