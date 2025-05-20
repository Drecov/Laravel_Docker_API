FROM php:8.2-fpm

WORKDIR /var/www

# Atualizar e instalar dependências do sistema (git, curl, zip, etc) em uma única camada
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar composer globalmente
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copiar arquivos composer.json e composer.lock primeiro para aproveitar cache do Docker
COPY ./app/composer.json ./app/composer.lock ./

# Instalar dependências do composer
RUN composer install --no-interaction --optimize-autoloader

# Copiar o restante do código da aplicação
COPY ./app ./