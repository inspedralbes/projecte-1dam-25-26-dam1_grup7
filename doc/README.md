# Documentació
Llistat d'alguns dels punts que han de quedar explicats en aquesta carpeta. Poden ser tots en aquest fitxer o en diversos fitxers enllaçats.

És obligatori modificar aquest document!!

## Documentació bàsica MÍNIMA
 * Objectius

L'objectiu d'aquest projecte es fer un gestor d'incidències utilitzant un servidor web, php, bases de dades i un gestor de bases de dades. Aquest gestor ha d'estar distribuït en 3 rols:
  - Usuaris
  - Tècnics
  - Administrador

 * Arquitectura bàsica
   * Tecnologies utilitzades

    Hem utilitzat les següents tecnologíes:

    - PHP
    - SQL
    - HTML/CSS/JS
    - Apache
    - MySQL
    - MongoDB
    - BOOTSTRAP
    - Docker

   * Interrelació entre els diversos components

    - Hem utilitzat HTML dins PHP per fer l'estructura de les webs. 
    - SQL dins PHP per fer les consultes.
    - Javascript i CSS per a la gestió d'errors i la creació d'estils

 * Com crees l'entorn de desenvolupament

Per l'entorn de desenvolupament utilitzam docker. Bàsicament l'inicialitzam amb un docker compose up -d i seguidament s'inicialitza l'aplicació al port 8080, el adminer al port 8081 i el mogo express al 8082

 * Com desplegues l'aplicació a producció

L'aplicació la pujam a producció a través de Filezilla i allà assignam les noves credencials de connexió a la BBDD al fitxer connexio.php.

Estructura de pantalles :
https://design.penpot.app/#/view?file-id=ceed1600-61c0-8087-8007-e8664ac101ad&page-id=43c73c51-5517-8066-8007-e867d2f7f933&section=interactions&index=0&share-id=d59e570a-957f-8047-8007-eedf188d8e81
