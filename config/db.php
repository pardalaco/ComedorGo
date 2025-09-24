<?php
function getConnection()
{
    // $host = "127.0.0.1";    // o el nombre de tu contenedor si usas Docker
    $host = "db";    // o el nombre de tu contenedor si usas Docker
    $port = 3306;
    $user = "root";
    $pass = "root";
    $dbname = "ComedorGo";

    try {
        $conn = new PDO(
            "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8",
            $user,
            $pass
        );
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}
