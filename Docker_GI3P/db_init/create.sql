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
  `dataInici` timestamp NOT NULL,
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

INSERT INTO `departament` (`idDept`, `nom`) VALUES
(1,	'Català'),
(2,	'Castellà'),
(3,	'Anglés'),
(4,	'Matemàtiques'),
(5,	'Història'),
(6,	'Informàtica');

INSERT INTO `tecnic` (`idTecnic`, `nom`) VALUES
(1,	'Pere'),
(2,	'Toni'),
(3,	'Pau'),
(4,	'Maria'),
(5,	'Ermengol'),
(6,	'Victoria');

INSERT INTO `tipologia` (`idTipo`, `nom`) VALUES
(1,	'Xarxa'),
(2,	'Hardware'),
(3,	'Software'),
(4,	'Seguretat'),
(5,	'Sistema Operatiu');

INSERT INTO `incidencia` (`id`, `dataInici`, `prioritat`, `descripcio`, `dataFi`, `tecnic`, `departament`, `tipologia`) VALUES
(1,	'2026-04-29 09:39:09',	'Alt',	'Cable de Xarxa no funciona al ordinador D8.',	NULL,	6,	6,	1),
(2,	'2026-04-29 09:41:51',	'Mitjà',	'Projector no encén',	NULL,	4,	4,	2),
(3,	'2026-04-29 09:42:41',	'Baix',	'El ordenador no detecta els altaveus quan els connect a la torre.',	NULL,	5,	3,	2),
(4,	'2026-04-29 09:43:36',	'Baix',	'No puc descàrregar un programa per mostrar als alumnes la història de Roma.',	NULL,	2,	5,	3);

INSERT INTO `actuacions` (`idActuacio`, `dataActuacio`, `descActuacio`, `visible`, `temps`, `incidencia`) VALUES
(1,	'2026-04-29',	'He canviat el cable de xarxa.',	0,	5,	1),
(2,	'2026-04-29',	'He comprovat a corrent i si funciona. Demà miraré el Hardware.',	1,	10,	2),
(3,	'2026-04-30',	'He comprovat el Hardware i la font d\'almentació no funciona. Ja l\'he canviada.',	1,	20,	2),
(4,	'2026-05-02',	'El connector no funcionava. Ja està canviat.',	1,	15,	3);