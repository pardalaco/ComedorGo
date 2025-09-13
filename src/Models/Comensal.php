<?php
require_once "../config/db.php";

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
