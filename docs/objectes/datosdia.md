# Classe `DatosDia`

### Descripció general

La classe `DatosDia` permet obtenir **informació completa d’un dia concret** del menjador.  
Agrupa dades de **comensals, assistència, menús i taules**, així com un **historial d’assistència**.

- La informació és generada a partir de les dades de la base de dades.
- S’utilitza principalment per **informes i estadístiques**.

---

### Atributs principals

|Atribut|Tipus|Descripció|
|---|---|---|
|`$fecha`|`string`|Data concreta en format `YYYY-MM-DD`.|
|`$comensales`|`array`|Array amb tots els objectes `Comensal`.|
|`$comensalesTotales`|`int`|Número total de comensals a la base de dades.|
|`$asistentes`|`int`|Número de comensals que assistiran aquest dia.|
|`$asistentesIDs`|`array`|IDs dels comensals que assistiran.|
|`$menus`|`array`|Array amb tots els objectes `Menu`.|
|`$menusNormales`|`array`|Menús no de règim.|
|`$menusRegimenes`|`array`|Menús de règim.|
|`$menusTotales`|`array`|Associatiu `[MenuID => número de comensals amb aquest menú]`.|
|`$asistentesMenus`|`array`|Associatiu `[MenuID => número d’assistents amb aquest menú]`.|
|`$menusRegimenesTotales`|`array`|Associatiu `[MenuID de règim => número de comensals]`.|
|`$mesas`|`array`|Array amb tots els objectes `Mesa`.|
|`$mesasTotales`|`array`|Associatiu `[MesaID => número de comensals a la taula]`.|
|`$asistentesMesas`|`array`|Associatiu `[MesaID => número d’assistents a la taula]`.|
|`$historialAsistencias`|`array`|Associatiu `[fecha => número d’assistents]` dels últims 30 dies (excloent caps de setmana).|

---

### Constructor

#### `__construct($fecha)`

- Inicialitza la classe amb la data especificada.
- Crea tots els arrays de comensals, menús i taules.
- Calcula totals i assistències.
- Recupera l’historial dels últims 30 dies.

---

### Funcions privades

|Funció|Descripció|
|---|---|
|`getTotalMenus()`|Retorna `[MenuID => número de comensals amb aquest menú]`.|
|`getTotalMenusRegimenes()`|Retorna `[MenuID de règim => número de comensals]`.|
|`getAsistentesPorMenu($fecha)`|Retorna `[MenuID => número d’assistents amb aquest menú en la data $fecha]`.|
|`getTotalMesas()`|Retorna `[MesaID => número de comensals a la taula]`.|
|`getAsistentesPorMesa($fecha)`|Retorna `[MesaID => número d’assistents a la taula en la data $fecha]`.|
|`getHistoricoAsistencias()`|Retorna `[fecha => número d’assistents]` dels últims 30 dies (excloent caps de setmana).|

---

### Getters

Permeten accedir a totes les propietats calculades:

- **Comensals**: `getComensales()`, `getComensalesTotales()`, `getAsistentes()`, `getAsistentesIDs()`, `getHistorialAsistencias()`.
- **Menús**: `getMenus()`, `getMenusNormales()`, `getMenusRegimenes()`, `getMenusTotales()`, `getAsistentesMenus()`, `getMenusRegimenesTotales()`.
- **Mesas**: `getMesas()`, `getMesasTotales()`, `getAsistentesMesas()`.
- **Data**: `getFecha()`.

---

### Notes addicionals

- Aquesta classe depèn de les funcions globals: `getComensales()` i `getAsistenciasFecha($fecha)`.
- Utilitza les classes `Comensal`, `Menu` i `Mesa` per generar els objectes associats.
- Les consultes a la base de dades es fan amb **PDO** i **consultes preparades**, per seguretat.
- Ideal per a **generació de informes diaris, gràfics d’assistència i estadístiques de menjador**.

