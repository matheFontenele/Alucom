#!/usr/bin/env bash
# Sair se houver erro
set -o errexit

composer install --no-dev --optimize-autoloader
npm install
npm run build # Gera os arquivos do Vite para o CSS/JS

php artisan migrate --force # Roda as migrações no banco de produção