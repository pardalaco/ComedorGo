<?php
require_once(__DIR__ . "/../config/db.php");
require_once 'Comensal.php';
require_once 'Menu.php';
require_once 'Mesa.php';

// require_once(__DIR__ . '../src/models/Comensal.php');
// require_once(__DIR__ . '../src/models/Menu.php');
// require_once(__DIR__ . '../src/models/Mesa.php');

class DatosDia
{
    private $fecha;
    private $comensales;
    private $comensalesTotales; // Número total de comensales en la base de datos
    private $asistentes; // Número de comensales que asisten
    private array $asistentesIDs; // IDs de comensales que asisten

    // Menús
    private array $menus; // array de objetos Menu
    private array $menusNormales;
    private array $menusRegimenes;
    private $menusTotales; // Array con el MenuID y Número total de menús en la base de datos
    private $asistentesMenus; // Array con el MenuID y NumeroAsistentes
    private $menusRegimenesTotales;

    // Mesas
    private array $mesas; // array de objetos Mesa
    private $mesasTotales; // Número total de mesas en la base de datos
    private $asistentesMesas; // Array con el MesaID y NumeroAsistentes

    private $historialAsistencias; // Array con la historia de asistencias (fecha => numAsistentes)

    function __construct($fecha)
    {
        $this->fecha = $fecha;

        // Comensales
        $this->comensales = getComensales();
        $this->comensalesTotales = count($this->comensales);

        // Asistentes
        $this->asistentesIDs = getAsistenciasFecha($fecha);
        $this->asistentes = count(getAsistenciasFecha($fecha));
        $this->historialAsistencias = $this->getHistoricoAsistencias();

        // Menús
        $this->menus = getAllMenus();
        $this->menusTotales = $this->getTotalMenus();
        $this->asistentesMenus = $this->getAsistentesPorMenu($fecha);
        $this->menusNormales = array_filter($this->menus, fn($menu) => !$menu->isRegimen());
        $this->menusRegimenes = array_filter($this->menus, fn($menu) => $menu->isRegimen());
        $this->menusRegimenesTotales = $this->getTotalMenusRegimenes();

        // Mesas
        $this->mesas = getAllMesas();
        $this->mesasTotales = $this->getTotalMesas();
        $this->asistentesMesas = $this->getAsistentesPorMesa($fecha);
    }

    // Funciones privadas para obtener datos adicionales
    private function getTotalMenus()
    {
        // MenuID => Número de comensales con ese menú
        $conn = getConnection();
        $stmt = $conn->prepare(
            "SELECT m.ID, COUNT(c.ID) AS NumComensales
            FROM Menu m
            LEFT JOIN Comensales c ON m.ID = c.Menu_ID
            GROUP BY m.ID;
            "
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // Devuelve un array asociativo [Menu_ID => NumComensales]
    }

    // Obtener el total de menús regimenes
    private function getTotalMenusRegimenes()
    {
        // MenuID => Número de comensales con ese menú regimen
        $conn = getConnection();
        $stmt = $conn->prepare("
            SELECT m.ID AS Menu_ID, COUNT(c.ID) AS NumComensales
            FROM Menu m
            LEFT JOIN Comensales c ON c.Menu_ID = m.ID
            WHERE m.Regimen = 1
            GROUP BY m.ID
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // Devuelve un array asociativo [Menu_ID => NumComensales]
    }

    // Obtener el número de asistentes por menú
    private function getAsistentesPorMenu($fecha)
    {
        $conn = getConnection();
        $stmt = $conn->prepare("
        SELECT m.ID as MenuID, COUNT(a.Comensal_ID) AS NumAsistentes
        FROM Menu m
        LEFT JOIN Comensales c ON c.Menu_ID = m.ID
        LEFT JOIN Asistencia a ON a.Comensal_ID = c.ID AND a.fecha = :fecha
        GROUP BY m.ID
    ");
        $stmt->bindParam(':fecha', $fecha);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // Ahora sí funciona
    }

    // Obtener el total de mesas
    private function getTotalMesas()
    {
        // MesaID => Número de comensales en esa mesa
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT Mesa_ID, COUNT(*) AS NumComensales FROM Comensales GROUP BY Mesa_ID");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // Devuelve un array asociativo [Mesa_ID => NumComensales]
    }

    // Obtener el número de asistentes por mesa
    private function getAsistentesPorMesa($fecha)
    {
        $conn = getConnection();
        $stmt = $conn->prepare("
            SELECT ms.ID as MesaID, COUNT(a.Comensal_ID) AS NumAsistentes
            FROM Mesa ms
            LEFT JOIN Comensales c ON c.Mesa_ID = ms.ID
            LEFT JOIN Asistencia a ON a.Comensal_ID = c.ID AND a.fecha = :fecha
            GROUP BY ms.ID, ms.Nombre
        ");
        $stmt->bindParam(':fecha', $fecha);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // Devuelve un array asociativo [Mesa_ID => NumAsistentes]
    }

    // Obtener el histórico de asistencias (últimos 30 días, excluyendo fines de semana)
    private function getHistoricoAsistencias()
    {
        $conn = getConnection();
        // $stmt = $conn->prepare("
        //     SELECT fecha, COUNT(Comensal_ID) AS NumAsistentes
        //     FROM Asistencia
        //     GROUP BY fecha
        //     ORDER BY fecha DESC
        //     LIMIT 30
        // ");
        $stmt = $conn->prepare("
        SELECT fecha, COUNT(Comensal_ID) AS NumAsistentes
        FROM Asistencia
        WHERE DAYOFWEEK(fecha) 
        GROUP BY fecha
        ORDER BY fecha DESC
        LIMIT 30
    ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // Devuelve un array asociativo [fecha => NumAsistentes]

    }

    // Getters
    public function getFecha()
    {
        return $this->fecha;
    }
    public function getComensales()
    {
        return $this->comensales;
    }
    public function getComensalesTotales()
    {
        return $this->comensalesTotales;
    }
    public function getAsistentes()
    {
        return $this->asistentes;
    }
    public function getHistorialAsistencias()
    {
        return $this->historialAsistencias;
    }
    public function getAsistentesIDs()
    {
        return $this->asistentesIDs;
    }
    public function getMenus()
    {
        return $this->menus;
    }
    public function getMenusNormales()
    {
        return $this->menusNormales;
    }
    public function getMenusRegimenes()
    {
        return $this->menusRegimenes;
    }
    public function getMenusRegimenesTotales()
    {
        return $this->menusRegimenesTotales;
    }

    public function getMenusTotales()
    {
        return $this->menusTotales;
    }
    public function getAsistentesMenus()
    {
        return $this->asistentesMenus;
    }
    public function getMesas()
    {
        return $this->mesas;
    }
    public function getMesasTotales()
    {
        return $this->mesasTotales;
    }
    public function getAsistentesMesas()
    {
        return $this->asistentesMesas;
    }
}
