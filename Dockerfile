FROM php:8.2-cli

# Instala dependências do sistema (incluindo libzip-dev para o erro atual)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpq-dev \
    libzip-dev \
    nodejs \
    npm

# Limpa o cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala extensões PHP (Adicionado 'zip' aqui)
RUN docker-php-ext-install pdo_pgsql zip

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Instala dependências do PHP (Agora deve passar)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Gera o CSS e JS (Vite)
RUN npm install
RUN npm run build

# Permissões
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

CMD php artisan serve --host=0.0.0.0 --port=${PORT:-10000}