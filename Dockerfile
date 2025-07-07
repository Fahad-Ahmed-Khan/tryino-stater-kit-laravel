FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libpq-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    vim \
    gnupg \
    ca-certificates \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd intl

# Install Node.js (LTS version) and npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application directory contents
COPY . /var/www

# Set proper permissions
RUN chown -R www-data:www-data /var/www

# Optional: install global npm packages (e.g., vite or laravel-mix)
# RUN npm install -g vite

# Expose port if using Vite (default is 5173)
# EXPOSE 5173
