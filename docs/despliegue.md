# Despliegue de ComedorGo

Este documento explica cómo desplegar el proyecto ComedorGo tanto en entorno de desarrollo como en producción utilizando Docker y Docker Compose.

## Requisitos previos

- Docker instalado (versión 20.10 o superior)
- Docker Compose instalado (versión 2.0 o superior)
- Git (opcional, para clonar el repositorio)

## Estructura de los archivos de Docker Compose

El proyecto incluye dos archivos de configuración:

1. `docker-compose.yml` - Para entorno de producción
2. `docker-compose-dev.yml` - Para entorno de desarrollo

## Despliegue en entorno de desarrollo

El entorno de desarrollo está configurado para facilitar el desarrollo continuo con recarga automática y acceso directo al código fuente.

### Pasos para levantar el entorno de desarrollo:

1. Clonar el repositorio (si no lo ha hecho):

   ```bash
   git clone https://github.com/pardalaco/ComedorGo
   cd ComedorGo
   ```

2. Levantar los contenedores usando el archivo de desarrollo:

   ```bash
   docker compose -f docker-compose-dev.yml up -d --build
   ```

3. Verificar que los contenedores están corriendo:

   ```bash
   docker compose -f docker-compose-dev.yml ps
   ```

4. Abrir la aplicación en el navegador:
   ```
   http://localhost:8080
   ```

### Características del entorno de desarrollo:

- El código fuente se monta directamente desde `./src` al contenedor, permitiendo cambios en tiempo real sin reconstruir.
- Los `node_modules` se montan desde el host para acelerar las instalaciones de dependencias de Node.js.
- Se utiliza un `dockerfile.dev` específico que puede incluir herramientas de desarrollo adicionales.

## Despliegue en entorno de producción

El entorno de producción está optimizado para rendimiento y seguridad, con mínimos privilegios y capas de imagen optimizadas.

### Pasos para levantar el entorno de producción:

1. Clonar el repositorio (si no lo ha hecho):

   ```bash
   git clone https://github.com/pardalaco/ComedorGo
   cd ComedorGo
   ```

2. Levantar los contenedores usando el archivo de producción:

   ```bash
   docker compose up -d --build
   ```

3. Verificar que los contenedores están corriendo:

   ```bash
   docker compose ps
   ```

4. Abrir la aplicación en el navegador:
   ```
   http://localhost:8080
   ```

### Características del entorno de producción:

- Imagen base optimizada de PHP con Apache.
- Solo se copian los archivos necesarios para producción en la imagen.
- Los volúmenes se utilizan principalmente para persistencia de datos (MySQL) y no para código fuente.
- Configuración de reinicio automático (`restart: always`).

## Detalles de la base de datos MySQL

Ambos entornos utilizan la misma configuración de base de datos:

- Host: `db` (nombre del servicio Docker)
- Puerto: `3306`
- Usuario: `root` (o el definido en las variables de entorno)
- Contraseña: `root` (o el definido en las variables de entorno)
- Base de datos: `ComedorGo`

### Conexión desde PHP:

```php
<?php
$host = "db";
$port = 3306;
$dbname = "ComedorGo";
$user = "root";
$pass = "root";

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión exitosa a MySQL!";
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
```

## Detención y limpieza

### Para detener los contenedores (en cualquiera de los entornos):

```bash
# Para desarrollo
docker compose -f docker-compose-dev.yml down

# Para producción
docker compose down
```

### Para eliminar también los volúmenes (y por tanto los datos de MySQL):

```bash
# Para desarrollo
docker compose -f docker-compose-dev.yml down -v

# Para producción
docker compose down -v
```

> **Advertencia**: El comando `down -v` eliminará todos los datos almacenados en la base de datos. Úselo únicamente cuando necesite comenzar desde cero.

## Notas importantes

1. **Persistencia de datos**: Los datos de MySQL se almacenan en un volumen llamado `mysql_data`, lo que significa que sobrevivirán a los reinicios de los contenedores siempre que no se eliminen explícitamente con `-v`.

2. **Variables de entorno**: Las credenciales de la base de datos están definidas directamente en los archivos `docker-compose.yml` y `docker-compose-dev.yml`. Para entornos de producción reales, considere usar un archivo `.env` o secrets de Docker para manejar información sensible.

3. **Puertos**:
   - La aplicación web está accesible en el puerto 8080 del host.
   - MySQL está expuesto en el puerto 3306 solo en localhost (127.0.0.1) para mayor seguridad.

4. **Reconstrucción**: Siempre que cambie el `Dockerfile` o las dependencias, reconstruya los contenedores con `--build`:

   ```bash
   docker compose up -d --build
   ```

5. **Logs**: Para ver los logs de los contenedores:
   ```bash
   docker compose logs -f
   ```

## Solución de problemas comunes

- **Error de dirección ya en uso**: Si obtiene un error al intentar levantar los contenedores indicando que el puerto ya está en uso, detenga cualquier otro servicio que esté utilizando ese puerto o cambie el mapeo de puertos en el archivo `docker-compose.yml`.

- **Problemas de permisos en archivos**: Si experimenta problemas de permisos al acceder a archivos montados, verifique los permisos de los directorios en el host y considere ajustar el usuario dentro del contenedor si es necesario.

- **Base de datos no inicializada**: Si las tablas no se crean al iniciar, asegúrese de que el archivo `db/init.sql` exista y contenga las sentencias SQL correctas para crear la estructura de la base de datos.
