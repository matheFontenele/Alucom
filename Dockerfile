# Use a imagem oficial do PHP com extensões necessárias
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
    npm \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala extensões PHP
RUN docker-php-ext-install pdo pdo_pgsql zip

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Instalação do PHP (Ignore platform reqs para evitar erro de ext-curl ou similares se faltarem no CLI)
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs

# Build dos assets (Vite)
RUN npm install && npm run build

# Ajuste de permissões para o Laravel
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache /app/public
RUN chmod -R 775 /app/storage /app/bootstrap/cache

# O Render usa a variável de ambiente PORT. 
# Importante: o comando serve não é ideal, mas para rodar com CLI no Render use:
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}