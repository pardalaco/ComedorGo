<?php
require_once(__DIR__ . "/../config/db.php");
require_once 'Comensal.php';

class Mesa
{
    private $id;
    private $nombre;
    private $descripcion;
    private array $comensales; // array de objetos Comensal
    private array $comensalesMenusNormaeles; // array de objeto Comensal con menú normal
    private array $comensalesMenusRegimen; // array de objetos Comensal con menú de régimen
    private array $comensalesMenusRegimenMesclat;
    private array $comensalesMenusRegimenTrituratPoc;
    private array $comensalesMenusRegimenTrituratMolt;

    public function __construct($id, $nombre, $descripcion = null)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->comensales = $this->obtenerComensales();
        $this->comensalesMenusNormaeles = $this->obtenerComensalesMenusNormales();
        $this->comensalesMenusRegimen = $this->obtenerComensalesMenusRegimen();
        $this->comensalesMenusRegimenMesclat = array_filter($this->comensalesMenusRegimen, function ($comensal) {
            return $comensal->getMenuId() == 2;
        });
        $this->comensalesMenusRegimenTrituratPoc = array_filter($this->comensalesMenusRegimen, function ($comensal) {
            return $comensal->getMenuId() == 3;
        });
        $this->comensalesMenusRegimenTrituratMolt = array_filter($this->comensalesMenusRegimen, function ($comensal) {
            return $comensal->getMenuId() == 4;
        });
    }

    // Guarda o actualiza el menú en la base de datos
    public function save()
    {
        $conn = getConnection();

        if (isset($this->id) && !empty($this->id)) {
            // Ya existe → UPDATE
            $stmt = $conn->prepare("
            UPDATE Mesa 
            SET Nombre = :nombre, Descripcion = :descripcion
            WHERE ID = :id
        ");
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        } else {
            // No existe → INSERT
            $stmt = $conn->prepare("
            INSERT INTO Mesa (Nombre, Descripcion) 
            VALUES (:nombre, :descripcion)
        ");
        }

        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':descripcion', $this->descripcion);

        $resultado = $stmt->execute();

        if (!isset($this->id) || empty($this->id)) {
            $this->id = $conn->lastInsertId(); // asignar el id recién creado
        }

        return $resultado;
    }

    // Elimina el menú de la base de datos
    public function delete()
    {
        if (!isset($this->id) || empty($this->id)) {
            throw new Exception("No se puede eliminar una mesa sin ID.");
        }

        $conn = getConnection();

        // Primero, eliminar los comensales asociados a esta mesa
        $stmtComensales = $conn->prepare("DELETE FROM Comensales WHERE Mesa_ID = :mesaId");
        $stmtComensales->bindParam(':mesaId', $this->id, PDO::PARAM_INT);
        $stmtComensales->execute();

        // Luego, eliminar la mesa
        $stmtMesa = $conn->prepare("DELETE FROM Mesa WHERE ID = :id");
        $stmtMesa->bindParam(':id', $this->id, PDO::PARAM_INT);
        return $stmtMesa->execute();
    }

    // Obtener los comensales asociados a esta mesa
    private function obtenerComensales()
    {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT * FROM Comensales WHERE Mesa_ID = :mesaId");
        $stmt->bindParam(':mesaId', $this->id, PDO::PARAM_INT);
        $stmt->execute();
        $comensales = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $comensales[] = new Comensal(
                $row['ID'],
                $row['Nombre'],
                $row['Apellidos'],
                $row['Intolerancias'],
                $row['Menu_ID'],
                $row['Mesa_ID'],
                $row['Autobus_ID'],
            );
        }
        return $comensales;
    }

    // Obtener los comensales con menú normal
    private function obtenerComensalesMenusNormales()
    {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT * FROM Comensales WHERE Mesa_ID = :mesaId AND Menu_ID = 1");
        $stmt->bindParam(':mesaId', $this->id, PDO::PARAM_INT);
        $stmt->execute();
        $comensales = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $comensales[] = new Comensal(
                $row['ID'],
                $row['Nombre'],
                $row['Apellidos'],
                $row['Intolerancias'],
                $row['Menu_ID'],
                $row['Mesa_ID'],
                $row['Autobus_ID'],
            );
        }
        return $comensales;
    }

    // Obtener los comensales con menú de régimen
    private function obtenerComensalesMenusRegimen()
    {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT * FROM Comensales WHERE Mesa_ID = :mesaId AND (Menu_ID = 2 OR Menu_ID = 3 OR Menu_ID = 4)");
        $stmt->bindParam(':mesaId', $this->id, PDO::PARAM_INT);
        $stmt->execute();
        $comensales = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $comensales[] = new Comensal(
                $row['ID'],
                $row['Nombre'],
                $row['Apellidos'],
                $row['Intolerancias'],
                $row['Menu_ID'],
                $row['Mesa_ID'],
                $row['Autobus_ID'],
            );
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
    public function getComensalesMenusNormaeles()
    {
        return $this->comensalesMenusNormaeles;
    }
    public function getComensalesMenusRegimen()
    {
        return $this->comensalesMenusRegimen;
    }
    public function getComensalesMenusRegimenMesclat()
    {
        return $this->comensalesMenusRegimenMesclat;
    }
    public function getComensalesMenusRegimenTrituratPoc()
    {
        return $this->comensalesMenusRegimenTrituratPoc;
    }
    public function getComensalesMenusRegimenTrituratMolt()
    {
        return $this->comensalesMenusRegimenTrituratMolt;
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
}

// Función para obtener todas las mesas
function getAllMesas()
{
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT * FROM Mesa");
    $stmt->execute();
    $mesas = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $mesas[] = new Mesa($row['ID'], $row['Nombre'], $row['Descripcion']);
    }
    return $mesas;
}

// Obtener una mesa por su ID
function getMesaById($id)
{
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT * FROM Mesa WHERE ID = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        return new Mesa($row['ID'], $row['Nombre'], $row['Descripcion']);
    }
    return null; // Si no se encuentra la mesa
}
