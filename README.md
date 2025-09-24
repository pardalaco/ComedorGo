# ComedorGo - Aplicación PHP con Docker

Este proyecto contiene una aplicación PHP con Apache y MySQL lista para ejecutarse usando Docker Compose.

## Requisitos

- Docker
- Docker Compose

---

## Estructura del proyecto

```

.
├── config           # Configuración, p.ej. db.php
├── db               # Inicialización de base de datos (init.sql)
├── docker-compose.yml
├── dockerfile
├── index.php
├── src               # Código PHP
├── templates         # Plantillas y recursos
└── view              # Vistas y componentes HTML

```

---

## Instrucciones para levantar la aplicación

1. Clonar el proyecto (si no lo has hecho):

```bash
git clone <URL_DEL_REPOSITORIO>
cd <NOMBRE_PROYECTO>
```

2. Construir y levantar los contenedores:

```bash
docker compose up -d --build
```

Esto hará lo siguiente:

- Levanta un contenedor `web` con PHP 8.2 y Apache, montando tu proyecto en `/var/www/html`.
- Levanta un contenedor `db` con MySQL 8.0, creando la base de datos y tablas definidas en `db/init.sql`.

3. Verificar que los contenedores están corriendo:

```bash
docker compose ps
```

4. Abrir la aplicación en el navegador:

```
http://localhost:8080
```

---

## Detalles de conexión MySQL

- Host: `db` (nombre del servicio Docker)
- Puerto: `3306`
- Usuario: definido en `docker-compose.yml` (`usuario` o `root`)
- Contraseña: definida en `docker-compose.yml`
- Base de datos: definida en `docker-compose.yml` (`mi_base` o `ComedorGo`)

**Ejemplo de conexión PDO en PHP:**

```php
<?php
$host = "db";
$port = 3306;
$dbname = "mi_base";
$user = "usuario";
$pass = "password";

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión exitosa a MySQL!";
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
```

---

## Detener la aplicación

```bash
docker compose down
```

Si quieres eliminar **volúmenes y datos de MySQL**:

```bash
docker compose down -v
```

---

## Notas

- Si realizas cambios en el `dockerfile` o dependencias de PHP, usa:

```bash
docker compose up -d --build
```

- Todos los `include` de PHP deben usar rutas absolutas o `__DIR__` para funcionar correctamente dentro de Docker.
