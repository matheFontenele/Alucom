# Alterado para 8.4 para bater com suas dependências
FROM php:8.4-cli 

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpq-dev \
    libzip-dev \
    nodejs \
    npm

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala extensões PHP
RUN docker-php-ext-install pdo_pgsql zip

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Comando de instalação com ignore para evitar travas de plataforma
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs

# Gera os assets (Vite)
RUN npm install
RUN npm run build

# Permissões
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

CMD php artisan serve --host=0.0.0.0 --port=${PORT:-10000}