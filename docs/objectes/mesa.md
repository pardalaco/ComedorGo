# Documentació dels Objectes

## Classe `Mesa`

### Descripció general

La classe `Mesa`, ubicada dins de **`src/models/Mesa.php`**, representa una **taula del menjador**.  
Cada objecte `Mesa` conté informació sobre el seu **nom, descripció** i els **comensals assignats** a la taula.  
També manté informació específica sobre:

- Quins comensals han assistit **avui**.
- Quins comensals tenen **menús normals, especials o de règim**, incloent subcategories de règim segons tipus de triturat.

La classe permet **crear, modificar i eliminar taules** i consultar els comensals associats segons diferents criteris.

---

### Atributs principals

|Atribut|Tipus|Descripció|
|---|---|---|
|`$id`|`int`|Identificador únic de la taula.|
|`$nombre`|`string`|Nom de la taula.|
|`$descripcion`|`string|null`|
|`$comensales`|`array`|Array d’objectes `Comensal` assignats a aquesta taula.|
|`$comensalesAsistenciaHoy`|`array`|Comensals que han assistit avui.|
|`$comensalesMenusNormaeles`|`array`|Comensals amb **menú normal**.|
|`$comensalesMenusEspeciales`|`array`|Comensals amb **menú especial**.|
|`$comensalesMenusRegimen`|`array`|Comensals amb **menú de règim**.|
|`$comensalesMenusRegimenMesclat`|`array`|Comensals amb **menú de règim mesclat** (tipus 2).|
|`$comensalesMenusRegimenTrituratMolt`|`array`|Comensals amb **menú de règim triturat molt** (tipus 3).|
|`$comensalesMenusRegimenTrituratPoc`|`array`|Comensals amb **menú de règim triturat poc** (tipus 4).|

---

### Mètodes principals

#### `__construct($id, $nombre, $descripcion = null)`

Crea una nova instància de `Mesa`.  
Carrega automàticament els comensals assignats a la taula i els organitza segons la seva assistència i tipus de menú.

#### `save()`

Guarda o actualitza la taula a la base de dades.

- Si ja existeix (`$id` definit), realitza un **UPDATE**.
- Si no existeix, crea un nou registre mitjançant **INSERT** i assigna el nou identificador.

#### `delete()`

Elimina la taula de la base de dades.

- Primer elimina els comensals associats a aquesta taula.
- Després elimina el registre de la taula.
- Comprova que l’objecte tinga un identificador vàlid abans d’eliminar-lo.

---

### Getters i Setters

Permeten accedir i modificar les propietats de la taula:

- **Getters**: `getId()`, `getNombre()`, `getDescripcion()`, `getComensales()`, `getComensalesAsistenciaHoy()`, `getComensalesMenusNormaeles()`, `getComensalesMenusEspeciales()`, `getComensalesMenusRegimen()`, `getComensalesMenusRegimenMesclat()`, `getComensalesMenusRegimenTrituratMolt()`, `getComensalesMenusRegimenTrituratPoc()`
- **Setters**: `setNombre()`, `setDescripcion()`

---

### Funcions auxiliars globals

#### `getAllMesas()`

Retorna un array amb **totes les taules** de la base de dades en forma d’objectes `Mesa`.

#### `getMesaById($id)`

Retorna un objecte `Mesa` corresponent a l’identificador indicat.  
Si no existeix, retorna `null`.

---

### Relacions amb altres classes

- **Comensal** → Cada taula pot tenir múltiples comensals assignats.
- Les diferents llistes de comensals permeten consultar **assistència i tipus de menú** per a informes i gestió.

---

### Notes addicionals

- Totes les operacions de base de dades utilitzen **PDO** a través de la funció `getConnection()` definida a `src/config/db.php`.
- Els mètodes `INSERT` i `UPDATE` utilitzen **consultes preparades** per garantir seguretat i evitar injeccions SQL.
- Les consultes d’assistència utilitzen la data actual (`date('Y-m-d')`) per filtrar els comensals que han assistit avui.
- Els comensals de règim es filtren automàticament per tipus de menú (mesclat, triturat molt o triturat poc).


