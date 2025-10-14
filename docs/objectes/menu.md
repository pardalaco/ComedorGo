# Documentació dels Objectes

## Classe `Menu`

### Descripció general

La classe `Menu`, ubicada dins de **`src/models/Menu.php`**, representa un **tipus de menú disponible al menjador**.  
Cada objecte `Menu` conté informació sobre el seu **nom, descripció, i si és un menú de règim especial**, així com els **comensals que el tenen assignat**. També manté un recompte automàtic del nombre de comensals associats.

La classe permet **crear, modificar i eliminar menús** i consultar els comensals relacionats.

---

### Atributs principals

|Atribut|Tipus|Descripció|
|---|---|---|
|`$id`|`int`|Identificador únic del menú.|
|`$nombre`|`string`|Nom del menú.|
|`$descripcion`|`string|null`|
|`$regimen`|`bool`|Indica si és un menú de règim especial (`true`) o no (`false`).|
|`$comensales`|`array`|Array d’objectes `Comensal` que tenen assignat aquest menú.|
|`$numeroComensales`|`int`|Nombre total de comensals que tenen aquest menú assignat.|

---

### Mètodes principals

#### `__construct($id, $nombre, $descripcion = null, $regimen = false)`

Crea una nova instància de `Menu`.  
Carrega automàticament els comensals que tenen assignat aquest menú i compta el seu nombre.

#### `save()`

Guarda o actualitza el menú a la base de dades.

- Si ja existeix (`$id` definit), realitza un **UPDATE**.
- Si no existeix, crea un nou registre mitjançant **INSERT** i assigna el nou identificador.

#### `delete()`

Elimina el menú de la base de dades.

- Primer elimina els comensals associats a aquest menú.
- Després elimina el registre del menú.
- Comprova que l’objecte tinga un identificador vàlid abans d’eliminar-lo.

---

### Getters i Setters

Permeten accedir i modificar les propietats del menú:

- `getId()`, `getNombre()`, `getDescripcion()`, `getComensales()`, `isRegimen()`, `getNumeroComensales()`
- `setNombre()`, `setDescripcion()`, `setRegimen()`

---

### Funcions auxiliars globals

#### `getAllMenus()`

Retorna un array amb **tots els menús** de la base de dades en forma d’objectes `Menu`.

#### `getMenuById($id)`

Retorna un objecte `Menu` corresponent a l’identificador indicat.  
Si no existeix, retorna `null`.

---

### Relacions amb altres classes

- **Comensal** → Cada menú pot estar assignat a múltiples comensals. La relació es manté automàticament mitjançant l’array `$comensales`.

---

### Notes addicionals

- Totes les operacions de base de dades utilitzen **PDO** a través de la funció `getConnection()` definida a `src/config/db.php`.
- Els mètodes `INSERT` i `UPDATE` utilitzen **consultes preparades** per garantir seguretat i evitar injeccions SQL.
- L’atribut `$numeroComensales` es calcula automàticament en crear l’objecte, basant-se en els comensals actuals del menú.
