<?php
require_once(__DIR__ . "/../config/db.php");

class Comensal
{
    private $id;
    private $nombre;
    private $apellidos;

    // Menus
    private $menu_id;
    private $menu_name;

    // Mesas
    private $mesa_id;
    private $mesa_name;

    // Autobus
    private $autobus_id;
    private $autobus_name;

    public function __construct($id, $nombre, $apellidos, $menu_id = null, $mesa_id = null, $autobus_id = null)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;

        // Menus
        $this->menu_id = $menu_id;
        $this->menu_name = $this->menu_id ? getMenuNameById($this->menu_id) : null;

        // Mesas
        $this->mesa_id = $mesa_id;
        $this->mesa_name = $this->mesa_id ? getMesaNameById($this->mesa_id) : null;

        // Autobus
        $this->autobus_id = $autobus_id;
        $this->autobus_name = $this->autobus_id ? getAutobusNameById($this->autobus_id) : null;
    }

    // Guarda o actualiza el comensal en la base de datos
    public function save()
    {
        $conn = getConnection();

        if (isset($this->id) && !empty($this->id)) {
            // Ya existe → UPDATE
            $stmt = $conn->prepare("
            UPDATE Comensales 
            SET Nombre = :nombre, Apellidos = :apellidos, Menu_ID = :menu_id, Mesa_ID = :mesa_id, Autobus_ID = :autobus_id
            WHERE ID = :id
        ");
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        } else {
            // No existe → INSERT
            $stmt = $conn->prepare("
            INSERT INTO Comensales (Nombre, Apellidos, Menu_ID, Mesa_ID, Autobus_ID) 
            VALUES (:nombre, :apellidos, :menu_id, :mesa_id, :autobus_id)
        ");
        }

        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':apellidos', $this->apellidos);
        $stmt->bindParam(':menu_id', $this->menu_id);
        $stmt->bindParam(':mesa_id', $this->mesa_id);
        $stmt->bindParam(':autobus_id', $this->autobus_id);

        $resultado = $stmt->execute();

        if (!isset($this->id) || empty($this->id)) {
            $this->id = $conn->lastInsertId(); // asignar el id recién creado
        }

        return $resultado;
    }

    // Elimina el comensal de la base de datos
    public function delete()
    {
        if (!isset($this->id) || empty($this->id)) {
            throw new Exception("No se puede eliminar un comensal sin ID.");
        }

        $conn = getConnection();
        $stmt = $conn->prepare("DELETE FROM Comensales WHERE ID = :id");
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        return $stmt->execute();
    }


    // Getters
    public function getId()
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getApellidos()
    {
        return $this->apellidos;
    }

    public function getMenuId()
    {
        return $this->menu_id;
    }

    public function getMesaId()
    {
        return $this->mesa_id;
    }
    public function getMenuName()
    {
        return $this->menu_name;
    }
    public function getMesaName()
    {
        return $this->mesa_name;
    }
    // Autobuses
    public function getAutobusId()
    {
        return $this->autobus_id;
    }
    public function getAutobusName()
    {
        return $this->autobus_name;
    }

    // Setters
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    }

    public function setMenuId($menu_id)
    {
        $this->menu_id = $menu_id;
    }

    public function setMesaId($mesa_id)
    {
        $this->mesa_id = $mesa_id;
    }
    public function setAutobusId($autobus_id)
    {
        $this->autobus_id = $autobus_id;
    }
}

function getComensalById($id)
{
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT * FROM Comensales WHERE ID = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        return new Comensal(
            $row['ID'],
            $row['Nombre'],
            $row['Apellidos'],
            $row['Menu_ID'],
            $row['Mesa_ID']
        );
    }
    return null;
}

// Obtiene todos los comensales de la base de datos
function getComensales()
{
    $conn = getConnection();

    $stmt = $conn->query("SELECT * FROM Comensales");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $comensales = [];
    foreach ($rows as $row) {
        $comensales[] = new Comensal(
            $row['ID'],
            $row['Nombre'],
            $row['Apellidos'],
            $row['Menu_ID'],
            $row['Mesa_ID'],
            $row['Autobus_ID'],
        );
    }
    return $comensales;
}

// Guarda la asistencia de un comensal para hoy
// $id: ID del comensal
// $asistencia: 1 para todos asisten, 0 para ninguno asiste
function guardarAsistencia($id, $asistencia)
{
    $conn = getConnection();
    if ($asistencia == 0) {
        // Si no asiste, eliminamos cualquier registro previo de asistencia para hoy
        $fecha = date('Y-m-d');
        $stmt = $conn->prepare("DELETE FROM Asistencia WHERE Comensal_ID = :comensal_id AND fecha = :fecha");
        $stmt->bindParam(':comensal_id', $id);
        $stmt->bindParam(':fecha', $fecha);
        return $stmt->execute();
    } else {
        // Si asiste, insertamos un nuevo registro de asistencia para hoy
        $fecha = date('Y-m-d');
        $stmt = $conn->prepare("INSERT IGNORE INTO Asistencia (Comensal_ID, fecha) VALUES (:comensal_id, :fecha)");
        $stmt->bindParam(':comensal_id', $id);
        $stmt->bindParam(':fecha', $fecha);
        return $stmt->execute();
    }
}

// Guarda la asistencia de todos los comensales para hoy
// $asistencia: 1 para todos asisten, 0 para ninguno asiste
function guardarTodosAsistencias($asistencia)
{
    $conn = getConnection();
    $comensales = getComensales();
    $fecha = date('Y-m-d');

    if ($asistencia == 0) {
        // Si no asisten, eliminamos todas las asistencias de hoy
        $stmt = $conn->prepare("DELETE FROM Asistencia WHERE fecha = :fecha");
        $stmt->bindParam(':fecha', $fecha);
        return $stmt->execute();
    } else {
        if (empty($comensales)) {
            return false;
        }

        // Construimos la consulta de manera dinámica
        $values = [];
        $params = [];
        foreach ($comensales as $index => $comensal) {
            $values[] = "(:comensal_id{$index}, :fecha{$index})";
            $params[":comensal_id{$index}"] = $comensal->getId();
            $params[":fecha{$index}"] = $fecha;
        }

        $sql = "INSERT IGNORE INTO Asistencia (Comensal_ID, fecha) VALUES " . implode(", ", $values);
        $stmt = $conn->prepare($sql);

        // Asignamos los parámetros
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        return $stmt->execute();
    }
}


// Obtiene los IDs de los comensales que asistieron en una fecha específica
function getAsistenciasFecha($fecha)
{
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT Comensal_ID FROM Asistencia WHERE fecha = :fecha");
    $stmt->bindParam(':fecha', $fecha);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_COLUMN, 0); // Devuelve un array con los IDs de comensales que asistieron
}

// Funciones auxiliares para obtener nombres de Menu y Mesa por ID
function getMenuNameById($id)
{
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT Nombre FROM Menu WHERE ID = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetchColumn();
}

// Función para obtener el nombre de la mesa por su ID
function getMesaNameById($id)
{
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT Nombre FROM Mesa WHERE ID = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetchColumn();
}

// Función para obtener el nombre del autobus por su ID
function getAutobusNameById($id)
{
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT Nombre FROM Autobus WHERE ID = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetchColumn();
}
