<?php

require_once(__DIR__ . "/../../config/db.php");
require_once 'Comensal.php';


class Menu
{
    private $id;
    private $nombre;
    private $descripcion;
    private bool $especial;
    private array $comensales; // array de objetos Comensal
    private int $numeroComensales;

    public function __construct($id, $nombre, $descripcion = null, $especial = false)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->especial = $especial;
        $this->comensales = $this->obtenerComensales();
        $this->numeroComensales = count($this->comensales);
    }

    public function save()
    {
        $conn = getConnection();

        if (isset($this->id) && !empty($this->id)) {
            // Ya existe → UPDATE
            $stmt = $conn->prepare("
            UPDATE Menu 
            SET Nombre = :nombre, Descripcion = :descripcion, Especial = :especial
            WHERE ID = :id
        ");
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        } else {
            // No existe → INSERT
            $stmt = $conn->prepare("
            INSERT INTO Menu (Nombre, Descripcion, Especial) 
            VALUES (:nombre, :descripcion, :especial)
        ");
        }

        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':especial', $this->especial, PDO::PARAM_BOOL);

        $resultado = $stmt->execute();

        if (!isset($this->id) || empty($this->id)) {
            $this->id = $conn->lastInsertId(); // asignar el id recién creado
        }

        return $resultado;
    }

    public function delete()
    {
        if (!isset($this->id) || empty($this->id)) {
            throw new Exception("No se puede eliminar un menú sin ID.");
        }

        $conn = getConnection();

        // Primero, eliminar los comensales asociados a este menú
        $stmt = $conn->prepare("DELETE FROM Comensales WHERE Menu_ID = :menuId");
        $stmt->bindParam(':menuId', $this->id, PDO::PARAM_INT);
        $stmt->execute();

        // Luego, eliminar el menú
        $stmt = $conn->prepare("DELETE FROM Menu WHERE ID = :id");
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    private function obtenerComensales()
    {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT * FROM Comensales WHERE Menu_ID = :menuId");
        $stmt->bindParam(':menuId', $this->id, PDO::PARAM_INT);
        $stmt->execute();
        $comensales = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $comensales[] = new Comensal($row['ID'], $row['Nombre'], $row['Apellidos'], $row['Menu_ID'], $row['Mesa_ID']);
        }
        return $comensales;
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
    public function getDescripcion()
    {
        return $this->descripcion;
    }
    public function getComensales()
    {
        return $this->comensales;
    }
    public function isEspecial()
    {
        return $this->especial;
    }
    public function getNumeroComensales()
    {
        return $this->numeroComensales;
    }
    // Setters
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }
    public function setEspecial($especial)
    {
        $this->especial = $especial;
    }
}

function getAllMenus()
{
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT * FROM Menu");
    $stmt->execute();
    $menus = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $menus[] = new Menu($row['ID'], $row['Nombre'], $row['Descripcion'], $row['Especial']);
    }
    return $menus;
}
function getMenuById($id)
{
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT * FROM Menu WHERE ID = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        return new Menu($row['ID'], $row['Nombre'], $row['Descripcion'], $row['Especial']);
    }
    return null;
}
