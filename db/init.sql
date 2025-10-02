-- MySQL dump 10.13  Distrib 9.4.0, for macos15.4 (arm64)
--
-- Host: 127.0.0.1    Database: ComedorGo
-- ------------------------------------------------------
-- Server version	8.0.43

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Asistencia`
--

DROP TABLE IF EXISTS `Asistencia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Asistencia` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `Asistencia` tinyint(1) DEFAULT NULL,
  `Comensal_ID` int NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Asistencia_UNIQUE` (`fecha`,`Comensal_ID`),
  KEY `Asistencia_Comensales_FK` (`Comensal_ID`),
  CONSTRAINT `Asistencia_Comensales_FK` FOREIGN KEY (`Comensal_ID`) REFERENCES `Comensales` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=194 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Asistencia`
--

LOCK TABLES `Asistencia` WRITE;
/*!40000 ALTER TABLE `Asistencia` DISABLE KEYS */;
INSERT INTO `Asistencia` VALUES (29,'2025-09-15',NULL,1),(54,'2025-09-16',NULL,1),(55,'2025-09-16',NULL,2),(57,'2025-09-16',NULL,32),(59,'2025-09-17',NULL,1),(65,'2025-09-17',NULL,2),(66,'2025-09-18',NULL,1),(67,'2025-09-18',NULL,32),(70,'2025-09-18',NULL,2),(129,'2025-09-19',NULL,2),(139,'2025-09-19',NULL,32),(150,'2025-09-21',NULL,32),(151,'2025-09-21',NULL,1),(162,'2025-09-24',NULL,32),(164,'2025-09-24',NULL,2),(165,'2025-09-24',NULL,34),(166,'2025-09-24',NULL,1),(167,'2025-09-29',NULL,32),(168,'2025-09-29',NULL,34),(169,'2025-09-29',NULL,1),(173,'2025-09-30',NULL,1),(174,'2025-09-30',NULL,34),(175,'2025-10-01',NULL,38),(176,'2025-10-01',NULL,32),(177,'2025-10-01',NULL,2),(189,'2025-10-02',NULL,32),(191,'2025-10-02',NULL,1),(192,'2025-10-02',NULL,39),(193,'2025-10-02',NULL,38);
/*!40000 ALTER TABLE `Asistencia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Autobus`
--

DROP TABLE IF EXISTS `Autobus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Autobus` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Descripcion` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Autobus_UNIQUE` (`Nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Autobus`
--

LOCK TABLES `Autobus` WRITE;
/*!40000 ALTER TABLE `Autobus` DISABLE KEYS */;
INSERT INTO `Autobus` VALUES (1,'Autobus 1',NULL),(3,'Autobus 2','baaa');
/*!40000 ALTER TABLE `Autobus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Comensales`
--

DROP TABLE IF EXISTS `Comensales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Comensales` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(100) NOT NULL,
  `Apellidos` varchar(100) NOT NULL,
  `Intolerancias` varchar(300) DEFAULT NULL,
  `Menu_ID` int DEFAULT NULL,
  `Mesa_ID` int DEFAULT NULL,
  `Autobus_ID` int DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Comensales_UNIQUE` (`Nombre`,`Apellidos`),
  KEY `Comensales_Menu_FK` (`Menu_ID`),
  KEY `Comensales_Mesa_FK` (`Mesa_ID`),
  KEY `Comensales_Autobus_FK` (`Autobus_ID`),
  CONSTRAINT `Comensales_Autobus_FK` FOREIGN KEY (`Autobus_ID`) REFERENCES `Autobus` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `Comensales_Menu_FK` FOREIGN KEY (`Menu_ID`) REFERENCES `Menu` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `Comensales_Mesa_FK` FOREIGN KEY (`Mesa_ID`) REFERENCES `Mesa` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Comensales`
--

LOCK TABLES `Comensales` WRITE;
/*!40000 ALTER TABLE `Comensales` DISABLE KEYS */;
INSERT INTO `Comensales` VALUES (1,'Paco','Jones',NULL,1,4,1),(2,'Pepe','Martínez','Atun',3,9,3),(32,'Laura','GG',NULL,2,9,3),(34,'Jose','Castillas',NULL,3,9,1),(38,'bbb','b',NULL,3,9,1),(39,'aaa','b','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, ',4,4,1);
/*!40000 ALTER TABLE `Comensales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Menu`
--

DROP TABLE IF EXISTS `Menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Menu` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(100) NOT NULL,
  `Descripcion` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Regimen` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Menu_UNIQUE` (`Nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Menu`
--

LOCK TABLES `Menu` WRITE;
/*!40000 ALTER TABLE `Menu` DISABLE KEYS */;
INSERT INTO `Menu` VALUES (1,'Normal',NULL,0),(2,'Règim Mesclat','',1),(3,'Règim Triturat Molt		','',1),(4,'Règim Triturat Poc',NULL,1),(5,'Especial',NULL,0);
/*!40000 ALTER TABLE `Menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Mesa`
--

DROP TABLE IF EXISTS `Mesa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Mesa` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Descripcion` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Mesa_UNIQUE` (`Nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Mesa`
--

LOCK TABLES `Mesa` WRITE;
/*!40000 ALTER TABLE `Mesa` DISABLE KEYS */;
INSERT INTO `Mesa` VALUES (4,'Taula 1','aa'),(9,'Taula 2','aaa');
/*!40000 ALTER TABLE `Mesa` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-02 17:42:58
