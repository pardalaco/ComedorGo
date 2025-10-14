# Documentació dels Objectes

## Classe `Comensal`

### Descripció general

La classe `Comensal`, ubicada dins de **`src/models/Comensal.php`**, representa un **alumne o usuari del menjador**.  
Cada objecte `Comensal` conté la informació personal bàsica (nom, cognoms, intoleràncies alimentàries), així com les seues **relacions amb altres entitats** del sistema: el menú assignat, la taula on menja i l’autobús que utilitza.

Aquesta classe permet **crear, modificar i eliminar comensals**, i també gestionar la **seua assistència diària i mensual** al menjador.

---

### Atributs principals

|Atribut|Tipus|Descripció|
|---|---|---|
|`$id`|`int`|Identificador únic del comensal.|
|`$nombre`|`string`|Nom del comensal.|
|`$apellidos`|`string`|Cognoms del comensal.|
|`$intolerancias`|`string|null`|
|`$menu_id`|`int|null`|
|`$menu_name`|`string|null`|
|`$mesa_id`|`int|null`|
|`$mesa_name`|`string|null`|
|`$autobus_id`|`int|null`|
|`$autobus_name`|`string|null`|

---

### Mètodes principals

#### `__construct($id, $nombre, $apellidos, $intolerancias = null, $menu_id = null, $mesa_id = null, $autobus_id = null)`

Crea una nova instància de `Comensal`.  
Si es passen identificadors de menú, taula o autobús, es carreguen automàticament els noms corresponents des de la base de dades.

#### `save()`

Guarda o actualitza el comensal en la base de dades.

- Si ja existeix (`$id` definit), realitza un **UPDATE**.
- Si no existeix, crea un nou registre mitjançant **INSERT** i assigna el nou identificador.

#### `delete()`

Elimina el comensal de la base de dades.  
Abans de realitzar l’operació comprova que l’objecte tinga un identificador vàlid.

---

### Getters i Setters

Permeten accedir i modificar les propietats del comensal.  
Inclouen mètodes per a obtindre i modificar el nom, cognoms, intoleràncies, i les relacions amb **menús**, **taules** i **autobusos**:

- `getId()`, `getNombre()`, `getApellidos()`, `getIntolerancias()`
- `getMenuId()`, `getMenuName()`
- `getMesaId()`, `getMesaName()`
- `getAutobusId()`, `getAutobusName()`
- `setNombre()`, `setApellidos()`, `setIntolerancias()`, `setMenuId()`, `setMesaId()`, `setAutobusId()`

---

### Funcions auxiliars globals

A més dels mètodes de la classe, el fitxer `Comensal.php` defineix **funcions globals** per a la gestió completa dels comensals i la seua assistència.

#### `getComensalById($id)`

Retorna un objecte `Comensal` corresponent a l’identificador indicat.  
Si no existeix, retorna `null`.

#### `getComensales()`

Obté **tots els comensals** de la base de dades i els retorna en forma d’array d’objectes `Comensal`.

---

### Gestió de l’assistència

El sistema incorpora funcionalitats per a registrar i consultar l’assistència dels comensals.

#### `guardarAsistencia($id, $asistencia)`

Guarda la presència d’un comensal en el dia actual.

- Si `$asistencia` és `1`, s’insereix un registre d’assistència.
- Si `$asistencia` és `0`, s’elimina qualsevol registre existent per a eixe dia.

#### `guardarTodosAsistencias($asistencia)`

Marca l’assistència de **tots els comensals** alhora.  
Pot marcar-los com a presents o absents segons el valor de `$asistencia`.

#### `getAsistenciasFecha($fecha)`

Retorna un **array d’identificadors** dels comensals que van assistir en una data concreta.

#### `getAsistenciasMes($mes, $anio)`

Retorna un **diccionari associatiu** on la clau és l’ID del comensal i el valor és una llista dels dies del mes en què va assistir.

---

### Funcions de suport per a relacions

Aquestes funcions permeten obtindre els noms associats a altres entitats del sistema:

- `getMenuNameById($id)` → Nom del menú.
- `getMesaNameById($id)` → Nom de la taula.
- `getAutobusNameById($id)` → Nom de l’autobús.

---

### Relacions amb altres classes

- **Menu** → Cada comensal pot tindre assignat un tipus de menú.
- **Mesa** → Cada comensal pertany a una taula específica del menjador.
- **Autobus** → Cada comensal pot estar assignat a un autobús concret.

Aquestes relacions es gestionen mitjançant els identificadors i noms obtinguts automàticament en el constructor.

---

### Notes addicionals

- Totes les operacions de base de dades utilitzen **PDO** a través de la funció `getConnection()` definida a `src/config/db.php`.
- Els mètodes `INSERT` i `UPDATE` utilitzen **consultes preparades** per garantir la seguretat i evitar injeccions SQL.
- Els noms de menús, taules i autobusos s’obtenen de manera dinàmica per evitar inconsistències i mantindre la coherència amb les taules relacionades.



