# Usa una imagen oficial de PHP con Apache
FROM php:8.2.31-apache

# Instalar extensiones necesarias
RUN docker-php-ext-install pdo pdo_mysql

# Instalar Node.js y npm
RUN apt-get update && apt-get install -y nodejs npm

# Establecer el directorio de trabajo en la raíz del servidor
WORKDIR /var/www/html

# Copiar archivos de dependencias primero para aprovechar la caché de capas de Docker
COPY package*.json ./

# Instalar dependencias de Node.js en la raíz (/var/www/html/node_modules)
RUN npm install

# Copiar el resto del código del proyecto
# COPY . /var/www/html