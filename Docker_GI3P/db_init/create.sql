-- Aquest script NOMÉS s'executa la primera vegada que es crea el contenidor.
-- Si es vol recrear les taules de nou cal esborrar el contenidor, o bé les dades del contenidor
-- és a dir, 
-- esborrar el contingut de la carpeta db_data 
-- o canviant el nom de la carpeta, però atenció a no pujar-la a git


-- És un exemple d'script per crear una base de dades i una taula
-- i afegir-hi dades inicials

-- Si creem la BBDD aquí podem control·lar la codificació i el collation
-- en canvi en el docker-compose no podem especificar el collation ni la codificació

-- Per assegurar-nes de que la codificació dels caràcters d'aquest script és la correcta
SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS incidenciesbbdd
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

-- Donem permisos a l'usuari 'usuari' per accedir a la base de dades 'incidenciesbbdd'
-- sinó, aquest usuari no podrà veure la base de dades i no podrà accedir a les taules
GRANT ALL PRIVILEGES ON incidenciesbbdd.* TO 'usuari'@'%';
FLUSH PRIVILEGES;


-- Després de crear la base de dades, cal seleccionar-la per treballar-hi
USE incidenciesbbdd;

CREATE TABLE `departament` (
  `idDept` int(9) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  PRIMARY KEY (`idDept`)
) ENGINE=InnoDB;

CREATE TABLE `tecnic` (
  `idTecnic` int(9) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  PRIMARY KEY (`idTecnic`)
) ENGINE=InnoDB;

CREATE TABLE `tipologia` (
  `idTipo` int(9) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  PRIMARY KEY (`idTipo`)
) ENGINE=InnoDB;

CREATE TABLE `incidencia` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `dataInici` date NOT NULL,
  `prioritat` enum('Baix','Mitjà','Alt') NOT NULL,
  `descripcio` varchar(255) NOT NULL,
  `dataFi` date DEFAULT NULL,
  `tecnic` int(11) DEFAULT NULL,
  `departament` int(11) DEFAULT NULL,
  `tipologia` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_inc_tecnic` FOREIGN KEY (`tecnic`) REFERENCES `tecnic` (`idTecnic`) ON DELETE SET NULL,
  CONSTRAINT `fk_inc_dept` FOREIGN KEY (`departament`) REFERENCES `departament` (`idDept`) ON DELETE SET NULL,
  CONSTRAINT `fk_inc_tipo` FOREIGN KEY (`tipologia`) REFERENCES `tipologia` (`idTipo`) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE `actuacions` (
  `idActuacio` int(9) NOT NULL AUTO_INCREMENT,
  `dataActuacio` date NOT NULL,
  `descActuacio` varchar(255) NOT NULL,
  `visible` tinyint(1) NOT NULL,
  `temps` int(11) NOT NULL,
  `incidencia` int(9) NOT NULL,
  PRIMARY KEY (`idActuacio`),
  CONSTRAINT `fk_act_incidencia` FOREIGN KEY (`incidencia`) REFERENCES `incidencia` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

