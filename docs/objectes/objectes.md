# Documentació dels Objectes

## Estructura general de les classes

Tots els objectes del sistema es troben dins de la carpeta **`src/models`**.
Cada objecte del sistema (per exemple, `Autobus`, `Taula`, `Menu`, `Comensal`, etc.) està implementat com una **classe PHP** que representa una entitat de la base de dades.  
Aquestes classes segueixen una estructura comuna que permet:

- **Encapsular les dades** de cada entitat.
- **Gestionar la persistència** a la base de dades (inserció, actualització i eliminació).
- **Relacionar entitats** entre si (per exemple, un autobús pot contindre molts comensals).
- **Facilitar la recuperació i manipulació de dades** mitjançant mètodes dedicats.

