<?php
require_once(__DIR__ . "/../../config/db.php");

class Comensal
{
    private $id;
    private $nombre;
    private $apellidos;
    private $menu_id;
    private $mesa_id;

    public function __construct($id, $nombre, $apellidos, $menu_id = null, $mesa_id = null)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->menu_id = $menu_id;
        $this->mesa_id = $mesa_id;
    }

    public function save()
    {
        $conn = getConnection();
        $stmt = $conn->prepare("INSERT INTO Comensales (Nombre, Apellidos, Menu_ID, Mesa_ID) VALUES (:nombre, :apellidos, :menu_id, :mesa_id)");
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':apellidos', $this->apellidos);
        $stmt->bindParam(':menu_id', $this->menu_id);
        $stmt->bindParam(':mesa_id', $this->mesa_id);
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
}

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
            $row['Mesa_ID']
        );
    }
    return $comensales;
}

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
