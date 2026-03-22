FROM php:8.2-cli

# Instala dependências do sistema (PostgreSQL, Node para o Vite, etc)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpq-dev \
    nodejs \
    npm

# Limpa o cache para deixar o servidor mais leve
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala a conexão do PHP com o PostgreSQL
RUN docker-php-ext-install pdo_pgsql

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define a pasta principal do projeto
WORKDIR /app

# Copia todos os seus arquivos para dentro do servidor
COPY . .

# Instala as dependências do PHP
RUN composer install --no-dev --optimize-autoloader

# Gera o CSS e JS da sua interface (Vite)
RUN npm install
RUN npm run build

# Dá permissão para o Laravel criar logs
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# O comando que mantém o site no ar
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-10000}