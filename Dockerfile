# Use a imagem oficial do PHP
FROM php:8.4-cli 

# Instala dependências do sistema e bibliotecas gráficas
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && curl -sL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Configura e instala extensões PHP (Adicionado o GD)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql zip gd

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Instalação das dependências do PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs

# Build dos assets (Vite)
RUN npm install && npm run build

# Ajuste de permissões (Crucial para o Laravel não dar erro de escrita)
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache
RUN chmod -R 775 /app/storage /app/bootstrap/cache

# Expõe a porta que o Render vai usar (informativo)
EXPOSE 10000

CMD sh -c "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT"