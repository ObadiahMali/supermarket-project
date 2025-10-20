FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev gnupg \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd

# Install Node.js and npm (LTS version)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# ✅ Use production environment file
COPY .env.production .env

# ✅ Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# ✅ Build frontend assets
RUN npm install && npm run build

# ✅ Generate app key
RUN php artisan key:generate

# ✅ Clear and cache config to ensure correct DB driver
RUN php artisan config:clear && php artisan config:cache

# ✅ Run database migrations
RUN php artisan migrate --force

# ✅ Expose port for Laravel's internal server
EXPOSE 8000

# ✅ Start Laravel
CMD php artisan serve --host=0.0.0.0 --port=8000