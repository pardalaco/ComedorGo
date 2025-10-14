# Classe `Autobus`

### Descripció general

La classe `Autobus` representa un **autobús assignat als comensals del menjador**.  
Conté la informació bàsica de cada autobús (nom i descripció), així com una llista d’objectes `Comensal` que viatgen en eixe autobús.

Aquesta classe permet **crear, modificar i eliminar autobusos**, així com **consultar els comensals associats** a cadascun.

---

### Atributs principals

| Atribut        | Tipus    | Descripció                                               |
| -------------- | -------- | -------------------------------------------------------- |
| `$id`          | `int`    | Identificador únic de l’autobús a la base de dades.      |
| `$nombre`      | `string` | Nom de l’autobús.                                        |
| `$descripcion` | `string  | null`                                                    |
| `$comensales`  | `array`  | Llista d’objectes `Comensal` assignats a aquest autobús. |
### Mètodes

#### `__construct($id, $nombre, $descripcion = null)`

Constructor de la classe.  
Inicialitza les propietats bàsiques i obté automàticament els comensals associats.

#### `save()`

Guarda l’objecte a la base de dades.

- Si l’objecte ja té un `id`, realitza un **UPDATE**.
- Si no el té, fa un **INSERT** i assigna el nou `id` generat.

#### `delete()`

Elimina l’autobús de la base de dades.  
Abans d’eliminar-lo, també elimina tots els comensals associats per mantenir la coherència de les dades.

#### `obtenerComensales()`

Mètode privat que recupera tots els comensals relacionats amb aquest autobús des de la base de dades, creant un array d’objectes `Comensal`.

#### Getters i Setters

Permeten accedir i modificar les propietats de l’objecte:  
`getId()`, `getNombre()`, `getDescripcion()`, `getComensales()`, `setNombre()`, `setDescripcion()`.

---

### Funcions auxiliars globals

Fora de la classe també s’han definit diverses funcions de suport:

#### `getAllAutobuses()`

Retorna un **array d’objectes `Autobus`** amb tots els autobusos existents a la base de dades.

#### `getAutobusById($id)`

Busca un autobús concret pel seu identificador i el retorna com a objecte `Autobus`.  
Si no existeix, retorna `null`.

---

### Relacions amb altres classes

- **Comensal**:  
    Cada objecte `Autobus` pot contindre diversos `Comensal`, als quals s’accedeix a través de l’atribut `$comensales`.
- **Base de dades (`db.php`)**:  
    Cada classe utilitza la funció `getConnection()` per establir connexions PDO amb la base de dades MySQL.
    

---

## Estructura comuna aplicada a la resta d’objectes

Totes les classes del sistema (`Autobus`, `Taula`, `Menu`, `Comensal`, etc.) comparteixen el mateix patró:

1. **Atributs** que representen les columnes de la seua taula SQL.
2. **Constructor** que inicialitza l’objecte i carrega relacions si escau.
3. **Mètodes de persistència**: `save()`, `delete()`.
4. **Funcions de consulta global**: `getAllX()`, `getXById()`.
5. **Relacions** entre objectes segons les claus foranes de la base de dades.