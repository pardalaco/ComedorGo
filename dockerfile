# Usa una imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instalar extensiones necesarias
RUN docker-php-ext-install pdo pdo_mysql

# Copiar todo el código de tu proyecto
COPY . /var/www/html

# Instalar Node.js y npm
RUN apt-get update && apt-get install -y nodejs npm

# Instalar dependencias de Node.js
WORKDIR /var/www/html
RUN npm install