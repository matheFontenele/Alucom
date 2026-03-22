FROM php:8.2-cli

# Instala dependências do sistema e extensões PHP necessárias
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpq-dev nodejs npm \
    libpng-dev libonig-dev libxml2-dev # Extensões extras para o Laravel

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala extensões PHP
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Tente rodar o composer sem o --no-dev primeiro para testar, ou garanta o lock
RUN composer install --optimize-autoloader --no-interaction --no-progress

# Gera o CSS e JS da sua interface (Vite)
RUN npm install
RUN npm run build

# Dá permissão para o Laravel criar logs
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# O comando que mantém o site no ar
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-10000}