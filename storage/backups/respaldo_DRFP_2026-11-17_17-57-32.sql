-- MySQL dump 10.16  Distrib 10.1.8-MariaDB, for Win32 (AMD64)
--
-- Host: 127.0.0.1    Database: DRFP
-- ------------------------------------------------------
-- Server version	10.1.8-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `actividades`
--

DROP TABLE IF EXISTS `actividades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `actividades` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `plan_vacacional_id` int(10) unsigned NOT NULL,
  `actividad` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hora` time NOT NULL,
  `fecha` date NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `objetivo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cantidad` int(11) NOT NULL,
  `sede_id` int(10) unsigned NOT NULL,
  `responsable` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `encargado_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `actividades_plan_vacacional_id_index` (`plan_vacacional_id`),
  KEY `actividades_sede_id_index` (`sede_id`),
  KEY `actividades_fecha_index` (`fecha`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `actividades`
--

LOCK TABLES `actividades` WRITE;
/*!40000 ALTER TABLE `actividades` DISABLE KEYS */;
/*!40000 ALTER TABLE `actividades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `autores`
--

DROP TABLE IF EXISTS `autores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `autores` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `autores_nombre_unique` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=161 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `autores`
--

LOCK TABLES `autores` WRITE;
/*!40000 ALTER TABLE `autores` DISABLE KEYS */;
INSERT INTO `autores` VALUES (1,'Miguel de Cervantes','2026-11-17 18:43:26','2026-11-17 18:43:26'),(2,'William Shakespeare','2026-11-17 18:43:26','2026-11-17 18:43:26'),(3,'Gabriel García Márquez','2026-11-17 18:43:26','2026-11-17 18:43:26'),(4,'Jorge Luis Borges','2026-11-17 18:43:26','2026-11-17 18:43:26'),(5,'Fyodor Dostoyevsky','2026-11-17 18:43:26','2026-11-17 18:43:26'),(6,'León Tolstói','2026-11-17 18:43:26','2026-11-17 18:43:26'),(7,'Victor Hugo','2026-11-17 18:43:26','2026-11-17 18:43:26'),(8,'Charles Dickens','2026-11-17 18:43:26','2026-11-17 18:43:26'),(9,'Jane Austen','2026-11-17 18:43:26','2026-11-17 18:43:26'),(10,'Mark Twain','2026-11-17 18:43:26','2026-11-17 18:43:26'),(11,'Edgar Allan Poe','2026-11-17 18:43:26','2026-11-17 18:43:26'),(12,'Franz Kafka','2026-11-17 18:43:26','2026-11-17 18:43:26'),(13,'Hermann Hesse','2026-11-17 18:43:26','2026-11-17 18:43:26'),(14,'Albert Camus','2026-11-17 18:43:26','2026-11-17 18:43:26'),(15,'Jean-Paul Sartre','2026-11-17 18:43:26','2026-11-17 18:43:26'),(16,'Simone de Beauvoir','2026-11-17 18:43:26','2026-11-17 18:43:26'),(17,'Marcel Proust','2026-11-17 18:43:26','2026-11-17 18:43:26'),(18,'James Joyce','2026-11-17 18:43:26','2026-11-17 18:43:26'),(19,'Federico García Lorca','2026-11-17 18:43:26','2026-11-17 18:43:26'),(20,'Antonio Machado','2026-11-17 18:43:26','2026-11-17 18:43:26'),(21,'Gustavo Adolfo Bécquer','2026-11-17 18:43:26','2026-11-17 18:43:26'),(22,'Lope de Vega','2026-11-17 18:43:26','2026-11-17 18:43:26'),(23,'Francisco de Quevedo','2026-11-17 18:43:26','2026-11-17 18:43:26'),(24,'Rubén Darío','2026-11-17 18:43:26','2026-11-17 18:43:26'),(25,'Pablo Neruda','2026-11-17 18:43:26','2026-11-17 18:43:26'),(26,'Octavio Paz','2026-11-17 18:43:26','2026-11-17 18:43:26'),(27,'Julio Cortázar','2026-11-17 18:43:26','2026-11-17 18:43:26'),(28,'Mario Vargas Llosa','2026-11-17 18:43:26','2026-11-17 18:43:26'),(29,'Carlos Fuentes','2026-11-17 18:43:26','2026-11-17 18:43:26'),(30,'Juan Rulfo','2026-11-17 18:43:26','2026-11-17 18:43:26'),(31,'Miguel Ángel Asturias','2026-11-17 18:43:26','2026-11-17 18:43:26'),(32,'Isabel Allende','2026-11-17 18:43:26','2026-11-17 18:43:26'),(33,'Jorge Ibargüengoitia','2026-11-17 18:43:26','2026-11-17 18:43:26'),(34,'Elena Poniatowska','2026-11-17 18:43:26','2026-11-17 18:43:26'),(35,'Rosario Castellanos','2026-11-17 18:43:26','2026-11-17 18:43:26'),(36,'Manuel Puig','2026-11-17 18:43:26','2026-11-17 18:43:26'),(37,'Julio Ramón Ribeyro','2026-11-17 18:43:26','2026-11-17 18:43:26'),(38,'César Vallejo','2026-11-17 18:43:26','2026-11-17 18:43:26'),(39,'José Martí','2026-11-17 18:43:26','2026-11-17 18:43:26'),(40,'José Hernández','2026-11-17 18:43:26','2026-11-17 18:43:26'),(41,'Domingo Faustino Sarmiento','2026-11-17 18:43:26','2026-11-17 18:43:26'),(42,'Esteban Echeverría','2026-11-17 18:43:26','2026-11-17 18:43:26'),(43,'Ricardo Güiraldes','2026-11-17 18:43:26','2026-11-17 18:43:26'),(44,'Roberto Arlt','2026-11-17 18:43:26','2026-11-17 18:43:26'),(45,'Adolfo Bioy Casares','2026-11-17 18:43:26','2026-11-17 18:43:26'),(46,'Ernesto Sábato','2026-11-17 18:43:26','2026-11-17 18:43:26'),(47,'Leopoldo Lugones','2026-11-17 18:43:26','2026-11-17 18:43:26'),(48,'Horacio Quiroga','2026-11-17 18:43:26','2026-11-17 18:43:26'),(49,'Delmira Agustini','2026-11-17 18:43:26','2026-11-17 18:43:26'),(50,'Juana de Ibarbourou','2026-11-17 18:43:26','2026-11-17 18:43:26'),(51,'Mario Benedetti','2026-11-17 18:43:26','2026-11-17 18:43:26'),(52,'Juan Carlos Onetti','2026-11-17 18:43:26','2026-11-17 18:43:26'),(53,'Felisberto Hernández','2026-11-17 18:43:26','2026-11-17 18:43:26'),(54,'José Enrique Rodó','2026-11-17 18:43:26','2026-11-17 18:43:26'),(55,'Gabriela Mistral','2026-11-17 18:43:26','2026-11-17 18:43:26'),(56,'Vicente Huidobro','2026-11-17 18:43:26','2026-11-17 18:43:26'),(57,'Nicanor Parra','2026-11-17 18:43:26','2026-11-17 18:43:26'),(58,'Joaquín Edwards Bello','2026-11-17 18:43:26','2026-11-17 18:43:26'),(59,'Mariano Latorre','2026-11-17 18:43:26','2026-11-17 18:43:26'),(60,'Volodia Teitelboim','2026-11-17 18:43:26','2026-11-17 18:43:26'),(61,'Rómulo Gallegos','2026-11-17 18:43:26','2026-11-17 18:43:26'),(62,'Teresa de la Parra','2026-11-17 18:43:26','2026-11-17 18:43:26'),(63,'Arturo Uslar Pietri','2026-11-17 18:43:26','2026-11-17 18:43:26'),(64,'Miguel Otero Silva','2026-11-17 18:43:26','2026-11-17 18:43:26'),(65,'Adriano González León','2026-11-17 18:43:26','2026-11-17 18:43:26'),(66,'Salvador Garmendia','2026-11-17 18:43:26','2026-11-17 18:43:26'),(67,'José Rafael Pocaterra','2026-11-17 18:43:26','2026-11-17 18:43:26'),(68,'Rufino Blanco Fombona','2026-11-17 18:43:26','2026-11-17 18:43:26'),(69,'Manuel Vicente Magallanes','2026-11-17 18:43:26','2026-11-17 18:43:26'),(70,'Andrés Eloy Blanco','2026-11-17 18:43:26','2026-11-17 18:43:26'),(71,'Aquiles Nazoa','2026-11-17 18:43:26','2026-11-17 18:43:26'),(72,'Alfredo Sadel','2026-11-17 18:43:26','2026-11-17 18:43:26'),(73,'Armando Reverón','2026-11-17 18:43:26','2026-11-17 18:43:26'),(74,'Antonio Lauro','2026-11-17 18:43:26','2026-11-17 18:43:26'),(75,'Ramón Díaz Sánchez','2026-11-17 18:43:26','2026-11-17 18:43:26'),(76,'Miguel Ramón Utrera','2026-11-17 18:43:26','2026-11-17 18:43:26'),(77,'Guillermo Meneses','2026-11-17 18:43:26','2026-11-17 18:43:26'),(78,'Antonia Palacios','2026-11-17 18:43:26','2026-11-17 18:43:26'),(79,'Luisa Josefina Hernández','2026-11-17 18:43:26','2026-11-17 18:43:26'),(80,'Julio Garmendia','2026-11-17 18:43:26','2026-11-17 18:43:26'),(81,'Pedro Sotillo','2026-11-17 18:43:26','2026-11-17 18:43:26'),(82,'Enrique Bernardo Núñez','2026-11-17 18:43:26','2026-11-17 18:43:26'),(83,'Alfredo Armas Alfonzo','2026-11-17 18:43:26','2026-11-17 18:43:26'),(84,'Francisco Herrera Luque','2026-11-17 18:43:26','2026-11-17 18:43:26'),(85,'Francisco Massiani','2026-11-17 18:43:26','2026-11-17 18:43:26'),(86,'Edmundo Aray','2026-11-17 18:43:26','2026-11-17 18:43:26'),(87,'Alfredo Chacón','2026-11-17 18:43:26','2026-11-17 18:43:26'),(88,'Elizabeth Schön','2026-11-17 18:43:26','2026-11-17 18:43:26'),(89,'Hannibal Vaux','2026-11-17 18:43:26','2026-11-17 18:43:26'),(90,'Fernando Cabrices','2026-11-17 18:43:26','2026-11-17 18:43:26'),(91,'Juan Sánchez Peláez','2026-11-17 18:43:26','2026-11-17 18:43:26'),(92,'Rafael Cadenas','2026-11-17 18:43:26','2026-11-17 18:43:26'),(93,'Eugenio Montejo','2026-11-17 18:43:26','2026-11-17 18:43:26'),(94,'Yolanda Osweta','2026-11-17 18:43:26','2026-11-17 18:43:26'),(95,'Blanca Strepponi','2026-11-17 18:43:26','2026-11-17 18:43:26'),(96,'Ana Enriqueta Terán','2026-11-17 18:43:26','2026-11-17 18:43:26'),(97,'Ida Gramcko','2026-11-17 18:43:26','2026-11-17 18:43:26'),(98,'Anteo Ortega','2026-11-17 18:43:26','2026-11-17 18:43:26'),(99,'Sergio Medina','2026-11-17 18:43:26','2026-11-17 18:43:26'),(100,'Delfina Torrealba','2026-11-17 18:43:26','2026-11-17 18:43:26'),(101,'Hans Christian Andersen','2026-11-17 18:43:26','2026-11-17 18:43:26'),(102,'Los Hermanos Grimm','2026-11-17 18:43:26','2026-11-17 18:43:26'),(103,'Lewis Carroll','2026-11-17 18:43:26','2026-11-17 18:43:26'),(104,'Robert Louis Stevenson','2026-11-17 18:43:26','2026-11-17 18:43:26'),(105,'Jules Verne','2026-11-17 18:43:26','2026-11-17 18:43:26'),(106,'H. G. Wells','2026-11-17 18:43:26','2026-11-17 18:43:26'),(107,'Rudyard Kipling','2026-11-17 18:43:26','2026-11-17 18:43:26'),(108,'Astrid Lindgren','2026-11-17 18:43:26','2026-11-17 18:43:26'),(109,'Roald Dahl','2026-11-17 18:43:26','2026-11-17 18:43:26'),(110,'Antoine de Saint-Exupéry','2026-11-17 18:43:26','2026-11-17 18:43:26'),(111,'J. R. R. Tolkien','2026-11-17 18:43:26','2026-11-17 18:43:26'),(112,'C. S. Lewis','2026-11-17 18:43:26','2026-11-17 18:43:26'),(113,'Laura Ingalls Wilder','2026-11-17 18:43:26','2026-11-17 18:43:26'),(114,'Louisa May Alcott','2026-11-17 18:43:26','2026-11-17 18:43:26'),(115,'Johanna Spyri','2026-11-17 18:43:26','2026-11-17 18:43:26'),(116,'Carlo Collodi','2026-11-17 18:43:26','2026-11-17 18:43:26'),(117,'Daniel Defoe','2026-11-17 18:43:26','2026-11-17 18:43:26'),(118,'Jonathan Swift','2026-11-17 18:43:26','2026-11-17 18:43:26'),(119,'Mary Shelley','2026-11-17 18:43:26','2026-11-17 18:43:26'),(120,'Bram Stoker','2026-11-17 18:43:26','2026-11-17 18:43:26'),(121,'Arthur Conan Doyle','2026-11-17 18:43:26','2026-11-17 18:43:26'),(122,'Agatha Christie','2026-11-17 18:43:26','2026-11-17 18:43:26'),(123,'Jules Renard','2026-11-17 18:43:26','2026-11-17 18:43:26'),(124,'Kenneth Grahame','2026-11-17 18:43:26','2026-11-17 18:43:26'),(125,'Frances Hodgson Burnett','2026-11-17 18:43:26','2026-11-17 18:43:26'),(126,'L. Frank Baum','2026-11-17 18:43:26','2026-11-17 18:43:26'),(127,'Eleanor H. Porter','2026-11-17 18:43:26','2026-11-17 18:43:26'),(128,'Louise Erdrich','2026-11-17 18:43:26','2026-11-17 18:43:26'),(129,'Elena Fortún','2026-11-17 18:43:26','2026-11-17 18:43:26'),(130,'Gloria Fuertes','2026-11-17 18:43:26','2026-11-17 18:43:26'),(131,'Platón','2026-11-17 18:43:26','2026-11-17 18:43:26'),(132,'Aristóteles','2026-11-17 18:43:26','2026-11-17 18:43:26'),(133,'Sócrates','2026-11-17 18:43:26','2026-11-17 18:43:26'),(134,'Sun Tzu','2026-11-17 18:43:26','2026-11-17 18:43:26'),(135,'Niccolò Maquiavelo','2026-11-17 18:43:26','2026-11-17 18:43:26'),(136,'Thomas More','2026-11-17 18:43:26','2026-11-17 18:43:26'),(137,'René Descartes','2026-11-17 18:43:26','2026-11-17 18:43:26'),(138,'Immanuel Kant','2026-11-17 18:43:26','2026-11-17 18:43:26'),(139,'Friedrich Nietzsche','2026-11-17 18:43:26','2026-11-17 18:43:26'),(140,'Karl Marx','2026-11-17 18:43:26','2026-11-17 18:43:26'),(141,'Friedrich Engels','2026-11-17 18:43:26','2026-11-17 18:43:26'),(142,'Simón Rodríguez','2026-11-17 18:43:26','2026-11-17 18:43:26'),(143,'Simón Bolívar','2026-11-17 18:43:26','2026-11-17 18:43:26'),(144,'Andrés Bello','2026-11-17 18:43:26','2026-11-17 18:43:26'),(145,'Miguel de Unamuno','2026-11-17 18:43:26','2026-11-17 18:43:26'),(146,'Ortega y Gasset','2026-11-17 18:43:26','2026-11-17 18:43:26'),(147,'Bertrand Russell','2026-11-17 18:43:26','2026-11-17 18:43:26'),(148,'Sigmund Freud','2026-11-17 18:43:26','2026-11-17 18:43:26'),(149,'Carl Jung','2026-11-17 18:43:26','2026-11-17 18:43:26'),(150,'Stephen Hawking','2026-11-17 18:43:26','2026-11-17 18:43:26'),(151,'Carl Sagan','2026-11-17 18:43:26','2026-11-17 18:43:26'),(152,'Charles Darwin','2026-11-17 18:43:26','2026-11-17 18:43:26'),(153,'Isaac Newton','2026-11-17 18:43:26','2026-11-17 18:43:26'),(154,'Marie Curie','2026-11-17 18:43:26','2026-11-17 18:43:26'),(155,'Albert Einstein','2026-11-17 18:43:26','2026-11-17 18:43:26'),(156,'Galileo Galilei','2026-11-17 18:43:26','2026-11-17 18:43:26'),(157,'Johannes Kepler','2026-11-17 18:43:26','2026-11-17 18:43:26'),(158,'Nicolás Copérnico','2026-11-17 18:43:26','2026-11-17 18:43:26'),(159,'Antoine Lavoisier','2026-11-17 18:43:26','2026-11-17 18:43:26'),(160,'Alexander von Humboldt','2026-11-17 18:43:26','2026-11-17 18:43:26');
/*!40000 ALTER TABLE `autores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bitacoras`
--

DROP TABLE IF EXISTS `bitacoras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bitacoras` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `user_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accion` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modulo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `datos_antes` text COLLATE utf8mb4_unicode_ci,
  `datos_despues` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bitacoras_user_id_index` (`user_id`),
  KEY `bitacoras_modulo_index` (`modulo`),
  KEY `bitacoras_accion_index` (`accion`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bitacoras`
--

LOCK TABLES `bitacoras` WRITE;
/*!40000 ALTER TABLE `bitacoras` DISABLE KEYS */;
INSERT INTO `bitacoras` VALUES (1,2,'Recepcion General','logout','autenticacion','El usuario \'Recepcion General\' cerró sesión.',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 18:35:12','2026-11-17 18:35:12'),(2,2,'Recepcion General','backup','sistema','Se generó un respaldo de la base de datos al cerrar sesión \'Recepcion General\'.',NULL,'{\"archivo\":\"respaldo_DRFP_2026-11-17_14-35-12.sql\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 18:35:13','2026-11-17 18:35:13'),(3,NULL,'Sistema','login-fallido','autenticacion','Intento de acceso con credenciales incorrectas para el correo \'admin@gmail.com\'.',NULL,'{\"email\":\"admin@gmail.com\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 18:35:27','2026-11-17 18:35:27'),(4,1,'Administrador General','login','autenticacion','El usuario \'Administrador General\' inició sesión correctamente.',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 18:35:39','2026-11-17 18:35:39'),(5,1,'Administrador General','logout','autenticacion','El usuario \'Administrador General\' cerró sesión.',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 18:36:02','2026-11-17 18:36:02'),(6,1,'Administrador General','backup','sistema','Se generó un respaldo de la base de datos al cerrar sesión \'Administrador General\'.',NULL,'{\"archivo\":\"respaldo_DRFP_2026-11-17_14-36-02.sql\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 18:36:02','2026-11-17 18:36:02'),(7,3,'Almacenista General','login-fallido','autenticacion','Acceso denegado por sede incorrecta: \'Almacenista General\' (al@gmail.com) intentó ingresar a una sede que no le corresponde.',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 18:36:19','2026-11-17 18:36:19'),(8,3,'Almacenista General','login','autenticacion','El usuario \'Almacenista General\' inició sesión correctamente.',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 18:37:02','2026-11-17 18:37:02'),(9,3,'Almacenista General','logout','autenticacion','El usuario \'Almacenista General\' cerró sesión.',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 18:37:13','2026-11-17 18:37:13'),(10,3,'Almacenista General','backup','sistema','Se generó un respaldo de la base de datos al cerrar sesión \'Almacenista General\'.',NULL,'{\"archivo\":\"respaldo_DRFP_2026-11-17_14-37-13.sql\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 18:37:13','2026-11-17 18:37:13'),(11,23,'Admin Biblioteca Publica Simon Rodriguez','login','autenticacion','El usuario \'Admin Biblioteca Publica Simon Rodriguez\' inició sesión correctamente.',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 18:49:52','2026-11-17 18:49:52'),(12,23,'Admin Biblioteca Publica Simon Rodriguez','logout','autenticacion','El usuario \'Admin Biblioteca Publica Simon Rodriguez\' cerró sesión.',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 18:50:01','2026-11-17 18:50:01'),(13,23,'Admin Biblioteca Publica Simon Rodriguez','backup','sistema','Se generó un respaldo de la base de datos al cerrar sesión \'Admin Biblioteca Publica Simon Rodriguez\'.',NULL,'{\"archivo\":\"respaldo_DRFP_2026-11-17_14-50-01.sql\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 18:50:01','2026-11-17 18:50:01'),(14,23,'Admin Biblioteca Publica Simon Rodriguez','login-fallido','autenticacion','Acceso denegado por sede incorrecta: \'Admin Biblioteca Publica Simon Rodriguez\' (admin15@drfp.com) intentó ingresar a una sede que no le corresponde.',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 18:50:36','2026-11-17 18:50:36'),(15,25,'Admin S.L Arturo Uslar Pietri','login-fallido','autenticacion','Acceso denegado por sede incorrecta: \'Admin S.L Arturo Uslar Pietri\' (admin17@drfp.com) intentó ingresar a una sede que no le corresponde.',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 18:50:45','2026-11-17 18:50:45'),(16,NULL,'Sistema','login-fallido','autenticacion','Intento de acceso con credenciales incorrectas para el correo \'admin@gmail.com\'.',NULL,'{\"email\":\"admin@gmail.com\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 18:51:03','2026-11-17 18:51:03'),(17,1,'Administrador General','login','autenticacion','El usuario \'Administrador General\' inició sesión correctamente.',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 18:51:14','2026-11-17 18:51:14'),(18,1,'Administrador General','logout','autenticacion','El usuario \'Administrador General\' cerró sesión.',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 18:51:20','2026-11-17 18:51:20'),(19,1,'Administrador General','backup','sistema','Se generó un respaldo de la base de datos al cerrar sesión \'Administrador General\'.',NULL,'{\"archivo\":\"respaldo_DRFP_2026-11-17_14-51-20.sql\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 18:51:20','2026-11-17 18:51:20'),(20,NULL,'Sistema','login-fallido','autenticacion','Intento de acceso con credenciales incorrectas para el correo \'admin@gmail.com\'.',NULL,'{\"email\":\"admin@gmail.com\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 19:17:40','2026-11-17 19:17:40'),(21,1,'Administrador General','login','autenticacion','El usuario \'Administrador General\' inició sesión correctamente.',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 19:17:48','2026-11-17 19:17:48'),(22,1,'Administrador General','logout','autenticacion','El usuario \'Administrador General\' cerró sesión.',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 19:17:54','2026-11-17 19:17:54'),(23,1,'Administrador General','backup','sistema','Se generó un respaldo de la base de datos al cerrar sesión \'Administrador General\'.',NULL,'{\"archivo\":\"respaldo_DRFP_2026-11-17_15-17-54.sql\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 19:17:54','2026-11-17 19:17:54'),(24,NULL,'Sistema','login-fallido','autenticacion','Intento de acceso con credenciales incorrectas para el correo \'admin@gmail.com\'.',NULL,'{\"email\":\"admin@gmail.com\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 19:28:04','2026-11-17 19:28:04'),(25,1,'Administrador General','login','autenticacion','El usuario \'Administrador General\' inició sesión correctamente.',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 19:28:19','2026-11-17 19:28:19'),(26,1,'Administrador General','logout','autenticacion','El usuario \'Administrador General\' cerró sesión.',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 19:28:46','2026-11-17 19:28:46'),(27,1,'Administrador General','backup','sistema','Se generó un respaldo de la base de datos al cerrar sesión \'Administrador General\'.',NULL,'{\"archivo\":\"respaldo_DRFP_2026-11-17_15-28-46.sql\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 19:28:46','2026-11-17 19:28:46'),(28,23,'Admin Biblioteca Publica Simon Rodriguez','login','autenticacion','El usuario \'Admin Biblioteca Publica Simon Rodriguez\' inició sesión correctamente.',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 19:29:36','2026-11-17 19:29:36'),(29,23,'Admin Biblioteca Publica Simon Rodriguez','logout','autenticacion','El usuario \'Admin Biblioteca Publica Simon Rodriguez\' cerró sesión.',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 19:29:59','2026-11-17 19:29:59'),(30,23,'Admin Biblioteca Publica Simon Rodriguez','backup','sistema','Se generó un respaldo de la base de datos al cerrar sesión \'Admin Biblioteca Publica Simon Rodriguez\'.',NULL,'{\"archivo\":\"respaldo_DRFP_2026-11-17_15-29-59.sql\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 19:29:59','2026-11-17 19:29:59'),(31,1,'Administrador General','login','autenticacion','El usuario \'Administrador General\' inició sesión correctamente.',NULL,NULL,'127.0.0.1','curl/8.13.0','2026-11-17 19:41:38','2026-11-17 19:41:38'),(32,1,'Administrador General','export','sistema','Se exportó el respaldo del sistema (254 registros en 19 tablas, alcance: todo el sistema).',NULL,'{\"archivo\":\"respaldo_sistema_20261117_154147.sql\",\"total\":254,\"tablas\":{\"sedes\":26,\"autores\":160,\"bitacoras\":31,\"users\":30,\"encargados\":0,\"salas\":7,\"pasillos\":0,\"estanterias\":0,\"personas\":0,\"inventario\":0,\"carnets\":0,\"prestamo\":0,\"prestamos_internos\":0,\"sanciones\":0,\"historial_danos_libros\":0,\"visitas\":0,\"visitas_guiadas\":0,\"planes_vacacionales\":0,\"actividades\":0}}','127.0.0.1','curl/8.13.0','2026-11-17 19:41:47','2026-11-17 19:41:47'),(33,1,'Administrador General','export','sistema','Se exportó el respaldo del sistema (255 registros en 19 tablas, alcance: todo el sistema).',NULL,'{\"archivo\":\"respaldo_sistema_20261117_154232.sql\",\"total\":255,\"tablas\":{\"sedes\":26,\"autores\":160,\"bitacoras\":32,\"users\":30,\"encargados\":0,\"salas\":7,\"pasillos\":0,\"estanterias\":0,\"personas\":0,\"inventario\":0,\"carnets\":0,\"prestamo\":0,\"prestamos_internos\":0,\"sanciones\":0,\"historial_danos_libros\":0,\"visitas\":0,\"visitas_guiadas\":0,\"planes_vacacionales\":0,\"actividades\":0}}','127.0.0.1','curl/8.13.0','2026-11-17 19:42:32','2026-11-17 19:42:32'),(34,1,'Administrador General','import','sistema','Se importó un respaldo del sistema (255 registros en 5 tablas, alcance: todo el sistema).',NULL,'{\"archivo\":\"import_sistema_20261117_154241.sql\",\"total\":255,\"tablas\":{\"sedes\":26,\"autores\":160,\"bitacoras\":32,\"users\":30,\"salas\":7}}','127.0.0.1','curl/8.13.0','2026-11-17 19:42:41','2026-11-17 19:42:41'),(35,13,'Admin B.C Manuelita Sáenz','login','autenticacion','El usuario \'Admin B.C Manuelita Sáenz\' inició sesión correctamente.',NULL,NULL,'127.0.0.1','curl/8.13.0','2026-11-17 19:42:56','2026-11-17 19:42:56'),(36,13,'Admin B.C Manuelita Sáenz','export','sistema','Se exportó el respaldo del sistema (1 registros en 15 tablas, alcance: sede 5).',NULL,'{\"archivo\":\"respaldo_sede_5_20261117_154258.sql\",\"total\":1,\"tablas\":{\"users\":1,\"encargados\":0,\"salas\":0,\"pasillos\":0,\"estanterias\":0,\"inventario\":0,\"carnets\":0,\"prestamo\":0,\"prestamos_internos\":0,\"sanciones\":0,\"historial_danos_libros\":0,\"visitas\":0,\"visitas_guiadas\":0,\"planes_vacacionales\":0,\"actividades\":0}}','127.0.0.1','curl/8.13.0','2026-11-17 19:42:58','2026-11-17 19:42:58'),(37,13,'Admin B.C Manuelita Sáenz','import-fallido','sistema','Intento de importación rechazado por validación: la tabla \'sedes\' no está permitida para tu nivel de acceso.',NULL,'{\"archivo\":\"respaldo_principal2.sql\"}','127.0.0.1','curl/8.13.0','2026-11-17 19:43:10','2026-11-17 19:43:10'),(38,13,'Admin B.C Manuelita Sáenz','import-fallido','sistema','Intento de importación rechazado por validación: comando no permitido en la sentencia: DROP TABLE `users`;',NULL,'{\"archivo\":\"malicioso.sql\"}','127.0.0.1','curl/8.13.0','2026-11-17 19:43:23','2026-11-17 19:43:23'),(39,13,'Admin B.C Manuelita Sáenz','import','sistema','Se importó un respaldo del sistema (1 registros en 1 tablas, alcance: sede 5).',NULL,'{\"archivo\":\"import_sede_5_20261117_154324.sql\",\"total\":1,\"tablas\":{\"users\":1}}','127.0.0.1','curl/8.13.0','2026-11-17 19:43:24','2026-11-17 19:43:24'),(40,2,'Recepcion General','login','autenticacion','El usuario \'Recepcion General\' inició sesión correctamente.',NULL,NULL,'127.0.0.1','curl/8.13.0','2026-11-17 19:43:35','2026-11-17 19:43:35'),(41,1,'Administrador General','login','autenticacion','El usuario \'Administrador General\' inició sesión correctamente.',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 19:44:49','2026-11-17 19:44:49'),(42,1,'Administrador General','export','sistema','Se exportó el respaldo del sistema (264 registros en 19 tablas, alcance: todo el sistema).',NULL,'{\"archivo\":\"respaldo_sistema_20261117_154510.sql\",\"total\":264,\"tablas\":{\"sedes\":26,\"autores\":160,\"bitacoras\":41,\"users\":30,\"encargados\":0,\"salas\":7,\"pasillos\":0,\"estanterias\":0,\"personas\":0,\"inventario\":0,\"carnets\":0,\"prestamo\":0,\"prestamos_internos\":0,\"sanciones\":0,\"historial_danos_libros\":0,\"visitas\":0,\"visitas_guiadas\":0,\"planes_vacacionales\":0,\"actividades\":0}}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 19:45:10','2026-11-17 19:45:10'),(43,1,'Administrador General','create','visitas','Se registró la visita de \'Eberth Gabriel Carino Montilla\' (cédula 31684230).',NULL,'{\"persona_id\":1,\"nombre\":\"Eberth Gabriel Carino Montilla\",\"cedula\":\"31684230\",\"fecha\":\"2026-11-17\",\"departamento\":\"Administración\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 19:50:07','2026-11-17 19:50:07'),(44,1,'Administrador General','logout','autenticacion','El usuario \'Administrador General\' cerró sesión.',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 19:51:27','2026-11-17 19:51:27'),(45,1,'Administrador General','backup','sistema','Se generó un respaldo de la base de datos al cerrar sesión \'Administrador General\'.',NULL,'{\"archivo\":\"respaldo_DRFP_2026-11-17_15-51-27.sql\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 19:51:27','2026-11-17 19:51:27'),(46,1,'Administrador General','login','autenticacion','El usuario \'Administrador General\' inició sesión correctamente.',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 19:57:21','2026-11-17 19:57:21'),(47,1,'Administrador General','create','visitas_guiadas','Se registró una visita guiada de la institución \'UPTYAB\'.',NULL,'{\"institucion\":\"UPTYAB\",\"cantidad\":\"4\",\"fecha_visita\":\"2026-11-17\",\"hora\":\"07:29\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 19:57:44','2026-11-17 19:57:44'),(48,1,'Administrador General','create','visitas_guiadas','Se registró una visita guiada de la institución \'Universidad Politecinica territorial...\'.',NULL,'{\"institucion\":\"Universidad Politecinica territorial...\",\"cantidad\":\"3\",\"fecha_visita\":\"2026-11-17\",\"hora\":\"07:27\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 19:58:27','2026-11-17 19:58:27'),(49,1,'Administrador General','create','planes_vacacionales','Se registró el plan vacacional \'Plan Vacacional\'.',NULL,'{\"nombre\":\"Plan Vacacional\",\"desde\":{\"date\":\"2027-12-18 00:00:00.000000\",\"timezone_type\":3,\"timezone\":\"America\\/Caracas\"},\"hasta\":{\"date\":\"2028-01-17 00:00:00.000000\",\"timezone_type\":3,\"timezone\":\"America\\/Caracas\"}}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 21:24:42','2026-11-17 21:24:42'),(50,13,'Admin B.C Manuelita Sáenz','login','autenticacion','El usuario \'Admin B.C Manuelita Sáenz\' inició sesión correctamente.',NULL,NULL,'127.0.0.1','curl/8.13.0','2026-11-17 21:32:14','2026-11-17 21:32:14'),(51,1,'Administrador General','login','autenticacion','El usuario \'Administrador General\' inició sesión correctamente.',NULL,NULL,'127.0.0.1','curl/8.13.0','2026-11-17 21:32:43','2026-11-17 21:32:43'),(52,1,'Administrador General','create','sedes','Se registró la sede \'hola\' en \'aristides-bastidas\'.',NULL,'{\"biblioteca\":\"hola\",\"comunidad\":\"aristides-bastidas\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 21:34:23','2026-11-17 21:34:23'),(53,1,'Administrador General','logout','autenticacion','El usuario \'Administrador General\' cerró sesión.',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 21:34:32','2026-11-17 21:34:32'),(54,1,'Administrador General','backup','sistema','Se generó un respaldo de la base de datos al cerrar sesión \'Administrador General\'.',NULL,'{\"archivo\":\"respaldo_DRFP_2026-11-17_17-34-32.sql\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 21:34:32','2026-11-17 21:34:32'),(55,23,'Admin Biblioteca Publica Simon Rodriguez','login','autenticacion','El usuario \'Admin Biblioteca Publica Simon Rodriguez\' inició sesión correctamente.',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 21:36:01','2026-11-17 21:36:01'),(56,23,'Admin Biblioteca Publica Simon Rodriguez','logout','autenticacion','El usuario \'Admin Biblioteca Publica Simon Rodriguez\' cerró sesión.',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 21:36:20','2026-11-17 21:36:20'),(57,23,'Admin Biblioteca Publica Simon Rodriguez','backup','sistema','Se generó un respaldo de la base de datos al cerrar sesión \'Admin Biblioteca Publica Simon Rodriguez\'.',NULL,'{\"archivo\":\"respaldo_DRFP_2026-11-17_17-36-20.sql\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 21:36:21','2026-11-17 21:36:21'),(58,NULL,'Sistema','login-fallido','autenticacion','Intento de acceso con credenciales incorrectas para el correo \'admin@gmail.com\'.',NULL,'{\"email\":\"admin@gmail.com\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 21:37:40','2026-11-17 21:37:40'),(59,1,'Administrador General','login','autenticacion','El usuario \'Administrador General\' inició sesión correctamente.',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 21:37:52','2026-11-17 21:37:52'),(60,1,'Administrador General','logout','autenticacion','El usuario \'Administrador General\' cerró sesión.',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 21:38:20','2026-11-17 21:38:20'),(61,1,'Administrador General','backup','sistema','Se generó un respaldo de la base de datos al cerrar sesión \'Administrador General\'.',NULL,'{\"archivo\":\"respaldo_DRFP_2026-11-17_17-38-20.sql\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 21:38:20','2026-11-17 21:38:20'),(62,NULL,'Sistema','login-fallido','autenticacion','Intento de acceso con credenciales incorrectas para el correo \'admin@gmail.com\'.',NULL,'{\"email\":\"admin@gmail.com\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 21:54:31','2026-11-17 21:54:31'),(63,1,'Administrador General','login','autenticacion','El usuario \'Administrador General\' inició sesión correctamente.',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 21:54:41','2026-11-17 21:54:41'),(64,1,'Administrador General','create','inventario','Se registró el libro \'Don Quijote de la Mancha II\' con cota \'0500\'.',NULL,'{\"titulo\":\"Don Quijote de la Mancha II\",\"cota\":\"0500\",\"autor\":\"Miguel de Cervantes\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 21:55:49','2026-11-17 21:55:49'),(65,1,'Administrador General','create','prestamos_internos','Se registró un préstamo interno de \'Don Quijote de la Mancha II\' para \'Eberth Gabriel Carino Montilla\'.',NULL,'{\"nombre_lector\":\"Eberth Gabriel Carino Montilla\",\"cedula\":\"31684230\",\"titulo_libro\":\"Don Quijote de la Mancha II\",\"cota_libro\":\"0500\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 21:56:57','2026-11-17 21:56:57'),(66,1,'Administrador General','create','visitas','Se registró la visita de \'Hillary Dorante\' (cédula 31113404).',NULL,'{\"persona_id\":2,\"nombre\":\"Hillary Dorante\",\"cedula\":\"31113404\",\"fecha\":\"2026-11-17\",\"departamento\":\"Administración\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 21:57:20','2026-11-17 21:57:20'),(67,1,'Administrador General','logout','autenticacion','El usuario \'Administrador General\' cerró sesión.',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0','2026-11-17 21:57:32','2026-11-17 21:57:32');
/*!40000 ALTER TABLE `bitacoras` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carnets`
--

DROP TABLE IF EXISTS `carnets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carnets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sede_id` bigint(20) unsigned DEFAULT NULL,
  `persona_id` int(10) unsigned NOT NULL,
  `num_carnet` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profesion` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_emision` date NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `estado` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Activo',
  `tipo_bloqueo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sancionado_hasta` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `carnets_num_carnet_unique` (`num_carnet`),
  KEY `carnets_persona_id_foreign` (`persona_id`),
  KEY `carnets_sede_id_foreign` (`sede_id`),
  CONSTRAINT `carnets_persona_id_foreign` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`),
  CONSTRAINT `carnets_sede_id_foreign` FOREIGN KEY (`sede_id`) REFERENCES `sedes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carnets`
--

LOCK TABLES `carnets` WRITE;
/*!40000 ALTER TABLE `carnets` DISABLE KEYS */;
/*!40000 ALTER TABLE `carnets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codigos_recuperacion`
--

DROP TABLE IF EXISTS `codigos_recuperacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codigos_recuperacion` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `codigo_hash` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `generado_por` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `intentos` int(11) NOT NULL DEFAULT '0',
  `expira` timestamp NULL DEFAULT NULL,
  `usado` tinyint(1) NOT NULL DEFAULT '0',
  `usado_en` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `codigos_recuperacion_user_id_index` (`user_id`),
  KEY `codigos_recuperacion_expira_index` (`expira`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codigos_recuperacion`
--

LOCK TABLES `codigos_recuperacion` WRITE;
/*!40000 ALTER TABLE `codigos_recuperacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `codigos_recuperacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `encargados`
--

DROP TABLE IF EXISTS `encargados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `encargados` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sede_id` bigint(20) unsigned DEFAULT NULL,
  `cedula` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono2` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `encargados_cedula_unique` (`cedula`),
  KEY `encargados_sede_id_foreign` (`sede_id`),
  CONSTRAINT `encargados_sede_id_foreign` FOREIGN KEY (`sede_id`) REFERENCES `sedes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `encargados`
--

LOCK TABLES `encargados` WRITE;
/*!40000 ALTER TABLE `encargados` DISABLE KEYS */;
INSERT INTO `encargados` VALUES (1,1,'31684230','Plan Vacacional','04125163265','04125163265','2026-11-17 21:34:23','2026-11-17 21:34:23');
/*!40000 ALTER TABLE `encargados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estanterias`
--

DROP TABLE IF EXISTS `estanterias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estanterias` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `numero` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `genero` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pasillo_id` int(10) unsigned NOT NULL,
  `sede_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `estanterias_pasillo_id_numero_unique` (`pasillo_id`,`numero`),
  KEY `estanterias_sede_id_index` (`sede_id`),
  CONSTRAINT `estanterias_pasillo_id_foreign` FOREIGN KEY (`pasillo_id`) REFERENCES `pasillos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `estanterias_sede_id_foreign` FOREIGN KEY (`sede_id`) REFERENCES `sedes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estanterias`
--

LOCK TABLES `estanterias` WRITE;
/*!40000 ALTER TABLE `estanterias` DISABLE KEYS */;
/*!40000 ALTER TABLE `estanterias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `historial_danos_libros`
--

DROP TABLE IF EXISTS `historial_danos_libros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `historial_danos_libros` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `libro_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prestamo_id` int(10) unsigned DEFAULT NULL,
  `sancion_id` int(10) unsigned DEFAULT NULL,
  `persona_id` int(10) unsigned DEFAULT NULL,
  `tipo_evento` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dano',
  `estado` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente',
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `solucion` text COLLATE utf8mb4_unicode_ci,
  `fecha_evento` timestamp NULL DEFAULT NULL,
  `fecha_resolucion` timestamp NULL DEFAULT NULL,
  `sede_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `historial_danos_libros_prestamo_id_foreign` (`prestamo_id`),
  KEY `historial_danos_libros_persona_id_foreign` (`persona_id`),
  KEY `historial_danos_libros_sede_id_foreign` (`sede_id`),
  KEY `historial_danos_libros_libro_id_index` (`libro_id`),
  KEY `historial_danos_libros_sancion_id_index` (`sancion_id`),
  CONSTRAINT `historial_danos_libros_libro_id_foreign` FOREIGN KEY (`libro_id`) REFERENCES `inventario` (`cota`) ON DELETE CASCADE,
  CONSTRAINT `historial_danos_libros_persona_id_foreign` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`) ON DELETE SET NULL,
  CONSTRAINT `historial_danos_libros_prestamo_id_foreign` FOREIGN KEY (`prestamo_id`) REFERENCES `prestamo` (`id`) ON DELETE SET NULL,
  CONSTRAINT `historial_danos_libros_sancion_id_foreign` FOREIGN KEY (`sancion_id`) REFERENCES `sanciones` (`id`) ON DELETE SET NULL,
  CONSTRAINT `historial_danos_libros_sede_id_foreign` FOREIGN KEY (`sede_id`) REFERENCES `sedes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historial_danos_libros`
--

LOCK TABLES `historial_danos_libros` WRITE;
/*!40000 ALTER TABLE `historial_danos_libros` DISABLE KEYS */;
/*!40000 ALTER TABLE `historial_danos_libros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventario`
--

DROP TABLE IF EXISTS `inventario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventario` (
  `cota` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sede_id` bigint(20) unsigned DEFAULT NULL,
  `estanteria_id` int(10) unsigned DEFAULT NULL,
  `titulo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `autor` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `volumen` int(11) DEFAULT NULL,
  `dias_prestamo` smallint(5) unsigned DEFAULT NULL COMMENT 'Límite de días de préstamo. NULL = usa el plazo estándar',
  `categoria` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ubicacion` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ingreso` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` enum('bueno','deteriorado','almacen') COLLATE utf8mb4_unicode_ci NOT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`cota`),
  KEY `inventario_sede_id_foreign` (`sede_id`),
  KEY `inventario_estanteria_id_foreign` (`estanteria_id`),
  CONSTRAINT `inventario_estanteria_id_foreign` FOREIGN KEY (`estanteria_id`) REFERENCES `estanterias` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventario_sede_id_foreign` FOREIGN KEY (`sede_id`) REFERENCES `sedes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventario`
--

LOCK TABLES `inventario` WRITE;
/*!40000 ALTER TABLE `inventario` DISABLE KEYS */;
INSERT INTO `inventario` VALUES ('0500',1,NULL,'Don Quijote de la Mancha II','Miguel de Cervantes',1,NULL,'literatura','1','Donacion 2026','bueno',NULL,'2026-11-17 21:55:49','2026-11-17 21:55:49');
/*!40000 ALTER TABLE `inventario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pasillos`
--

DROP TABLE IF EXISTS `pasillos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pasillos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `numero` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sala_id` int(10) unsigned NOT NULL,
  `sede_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pasillos_sala_id_numero_unique` (`sala_id`,`numero`),
  KEY `pasillos_sede_id_index` (`sede_id`),
  CONSTRAINT `pasillos_sala_id_foreign` FOREIGN KEY (`sala_id`) REFERENCES `salas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pasillos_sede_id_foreign` FOREIGN KEY (`sede_id`) REFERENCES `sedes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pasillos`
--

LOCK TABLES `pasillos` WRITE;
/*!40000 ALTER TABLE `pasillos` DISABLE KEYS */;
/*!40000 ALTER TABLE `pasillos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personas`
--

DROP TABLE IF EXISTS `personas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cedula` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sexo` char(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono2` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comunidad` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personas_cedula_unique` (`cedula`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personas`
--

LOCK TABLES `personas` WRITE;
/*!40000 ALTER TABLE `personas` DISABLE KEYS */;
INSERT INTO `personas` VALUES (1,'31684230','Eberth Gabriel Carino Montilla','M','04125163265','04125163265','eberth@gmail.com','cocorote','hola','2026-11-17 19:50:07','2026-11-17 19:50:07'),(2,'31113404','Hillary Dorante','F','04125163265','04125163265','eberth@gmail.com','bolivar','hola','2026-11-17 21:57:20','2026-11-17 21:57:20');
/*!40000 ALTER TABLE `personas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `planes_vacacionales`
--

DROP TABLE IF EXISTS `planes_vacacionales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `planes_vacacionales` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sede_id` bigint(20) unsigned DEFAULT NULL,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `desde` date NOT NULL,
  `hasta` date NOT NULL,
  `estado` enum('activo','inactivo','completado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inactivo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `planes_vacacionales_sede_id_foreign` (`sede_id`),
  CONSTRAINT `planes_vacacionales_sede_id_foreign` FOREIGN KEY (`sede_id`) REFERENCES `sedes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `planes_vacacionales`
--

LOCK TABLES `planes_vacacionales` WRITE;
/*!40000 ALTER TABLE `planes_vacacionales` DISABLE KEYS */;
INSERT INTO `planes_vacacionales` VALUES (1,1,'Plan Vacacional','2027-12-18','2028-01-17','inactivo','2026-11-17 21:24:42','2026-11-17 21:24:42');
/*!40000 ALTER TABLE `planes_vacacionales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prestamo`
--

DROP TABLE IF EXISTS `prestamo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prestamo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sede_id` bigint(20) unsigned DEFAULT NULL,
  `lector_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `libro_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_salida` date NOT NULL,
  `fecha_devolucion` date NOT NULL,
  `condiciones_salida` text COLLATE utf8mb4_unicode_ci,
  `condiciones_regreso` text COLLATE utf8mb4_unicode_ci,
  `estatus` enum('pendiente','entregado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prestamo_sede_id_foreign` (`sede_id`),
  CONSTRAINT `prestamo_sede_id_foreign` FOREIGN KEY (`sede_id`) REFERENCES `sedes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prestamo`
--

LOCK TABLES `prestamo` WRITE;
/*!40000 ALTER TABLE `prestamo` DISABLE KEYS */;
/*!40000 ALTER TABLE `prestamo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prestamos_internos`
--

DROP TABLE IF EXISTS `prestamos_internos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prestamos_internos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sede_id` bigint(20) unsigned DEFAULT NULL,
  `cedula` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_lector` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cota_libro` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `titulo_libro` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha` date NOT NULL,
  `hora_prestamo` time NOT NULL,
  `hora_devolucion` time DEFAULT NULL,
  `estado` enum('activo','devuelto') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prestamos_internos_sede_id_foreign` (`sede_id`),
  CONSTRAINT `prestamos_internos_sede_id_foreign` FOREIGN KEY (`sede_id`) REFERENCES `sedes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prestamos_internos`
--

LOCK TABLES `prestamos_internos` WRITE;
/*!40000 ALTER TABLE `prestamos_internos` DISABLE KEYS */;
INSERT INTO `prestamos_internos` VALUES (1,1,'31684230','Eberth Gabriel Carino Montilla','0500','Don Quijote de la Mancha II','2026-11-17','17:56:57',NULL,'activo','2026-11-17 21:56:57','2026-11-17 21:56:57');
/*!40000 ALTER TABLE `prestamos_internos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `salas`
--

DROP TABLE IF EXISTS `salas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `salas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sede_id` bigint(20) unsigned DEFAULT NULL,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `salas_sede_id_nombre_unique` (`sede_id`,`nombre`),
  KEY `salas_sede_id_index` (`sede_id`),
  CONSTRAINT `salas_sede_id_foreign` FOREIGN KEY (`sede_id`) REFERENCES `sedes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `salas`
--

LOCK TABLES `salas` WRITE;
/*!40000 ALTER TABLE `salas` DISABLE KEYS */;
INSERT INTO `salas` VALUES (1,1,'Sala General','Sala general de libros','2026-11-17 18:32:40','2026-11-17 18:32:40'),(2,1,'Sala Infantil','Sala de literatura infantil','2026-11-17 18:32:40','2026-11-17 18:32:40'),(3,1,'Centro didáctico','Centro didáctico','2026-11-17 18:32:40','2026-11-17 18:32:40'),(4,1,'Sala estadal','Sala estadal','2026-11-17 18:32:40','2026-11-17 18:32:40'),(5,1,'Centro de Investigación','Centro de investigación','2026-11-17 18:32:40','2026-11-17 18:32:40'),(6,1,'Administración','Administración','2026-11-17 18:32:40','2026-11-17 18:32:40'),(7,1,'Presidencia','Presidencia','2026-11-17 18:32:40','2026-11-17 18:32:40');
/*!40000 ALTER TABLE `salas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sanciones`
--

DROP TABLE IF EXISTS `sanciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sanciones` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sede_id` bigint(20) unsigned DEFAULT NULL,
  `prestamo_id` int(10) unsigned NOT NULL,
  `persona_id` int(10) unsigned NOT NULL,
  `tipo_dano` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` enum('pendiente','solventado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente',
  `observacion_resolucion` text COLLATE utf8mb4_unicode_ci,
  `fecha_resolucion` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sanciones_prestamo_id_foreign` (`prestamo_id`),
  KEY `sanciones_persona_id_foreign` (`persona_id`),
  KEY `sanciones_sede_id_foreign` (`sede_id`),
  CONSTRAINT `sanciones_persona_id_foreign` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sanciones_prestamo_id_foreign` FOREIGN KEY (`prestamo_id`) REFERENCES `prestamo` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sanciones_sede_id_foreign` FOREIGN KEY (`sede_id`) REFERENCES `sedes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sanciones`
--

LOCK TABLES `sanciones` WRITE;
/*!40000 ALTER TABLE `sanciones` DISABLE KEYS */;
/*!40000 ALTER TABLE `sanciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sedes`
--

DROP TABLE IF EXISTS `sedes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sedes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comunidad` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `biblioteca` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `encargado_id` int(10) unsigned DEFAULT NULL,
  `cedula` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sedes_encargado_id_foreign` (`encargado_id`),
  CONSTRAINT `sedes_encargado_id_foreign` FOREIGN KEY (`encargado_id`) REFERENCES `encargados` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sedes`
--

LOCK TABLES `sedes` WRITE;
/*!40000 ALTER TABLE `sedes` DISABLE KEYS */;
INSERT INTO `sedes` VALUES (1,'san-felipe','Biblioteca Dr. Félix Pifano','esquina de la Calle 13 con Avenida 2, dentro del Complejo Cultural Andrés Bello en San Felipe, Estado Yaracuy.',NULL,NULL,'2026-11-17 18:32:40','2026-11-17 18:32:40'),(2,'san-felipe','S.L Luis Alberto Principal','Avenida libertador entre Calle 5 y 6, Sede del ateneo \"Cirilo Menoza\" Marin',NULL,NULL,'2026-11-17 18:32:40','2026-11-17 18:32:40'),(3,'san-felipe','S.L San Javier','Calle principal, Entre Calle 1 y 2 Casa de la Cultura San Juan Javier',NULL,NULL,'2026-11-17 18:32:40','2026-11-17 18:32:40'),(4,'san-felipe','B.C Moral y Luces','Casa de la Cultura, Comunnidad Valle Verde Trasnversal 3 Entre Calle 9 y 10 Transversal 3 entres calles 9 y 10',NULL,NULL,'2026-11-17 18:32:40','2026-11-17 18:32:40'),(5,'san-felipe','B.C Manuelita Sáenz','Urb. Juan José de Maya, manzana 4',NULL,NULL,'2026-11-17 18:32:40','2026-11-17 18:32:40'),(6,'san-felipe','B.C Ciudadela','Av. Robert Serra con Av. Williams Lara. Ciudadela',NULL,NULL,'2026-11-17 18:32:40','2026-11-17 18:32:40'),(7,'san-felipe','B.C Higuerón','Calle principal C.C. Higuerón',NULL,NULL,'2026-11-17 18:32:40','2026-11-17 18:32:40'),(8,'pena','B.P Soc. Oswaldo Méndez','Calle 19 esquina carrera 8 y 9 Yaritagua',NULL,NULL,'2026-11-17 18:32:40','2026-11-17 18:32:40'),(9,'pena','S.L Simón Rodríguez','Av. principal Las Velas casa vacacional. Las Velas',NULL,NULL,'2026-11-17 18:32:40','2026-11-17 18:32:40'),(10,'pena','B.C El Palmar','Calle principal, centro integral comunitario',NULL,NULL,'2026-11-17 18:32:40','2026-11-17 18:32:40'),(11,'bolivar','B.P Josefa Marín de Narváez','Calle Sucre, frente a la plaza Bolívar. Aroa',NULL,NULL,'2026-11-17 18:32:40','2026-11-17 18:32:40'),(12,'bolivar','P.P Ingrid Fernández','Calle principal, sector El Cruce, comunidad Quebrada Honda',NULL,NULL,'2026-11-17 18:32:40','2026-11-17 18:32:40'),(13,'nirgua','B.P Rómulo Gallegos','Av. 5 entre calles 8 y 9 frente a la plaza Bolívar',NULL,NULL,'2026-11-17 18:32:40','2026-11-17 18:32:40'),(14,'nirgua','S.L Euclides Herrera','Casa de la cultura \"Euclides Herrera\" frente a la plaza Bolívar de Salom',NULL,NULL,'2026-11-17 18:32:40','2026-11-17 18:32:40'),(15,'cocorote','Biblioteca Publica Simon Rodriguez','centro de la calle 7 entre Av 6 y 5',NULL,NULL,'2026-11-17 18:32:40','2026-11-17 18:32:40'),(16,'paez','B.P José Alfonso Daza','Casa de la cultura \"Alberto Beto Ramírez\" carrera 4 esquina calle 9. Sector El Silencio',NULL,NULL,'2026-11-17 18:32:40','2026-11-17 18:32:40'),(17,'paez','S.L Arturo Uslar Pietri','Frente a la plaza Bolívar comunidad La Blanquera',NULL,NULL,'2026-11-17 18:32:40','2026-11-17 18:32:40'),(18,'independencia','S.L Celsa Mendoza Reyes','Prolongación calle 39, módulo de funda comunal. Sabaneta',NULL,NULL,'2026-11-17 18:32:40','2026-11-17 18:32:40'),(19,'veroes','S.L Simón Rodríguez','Calle Ecuador, al lado de la plaza Bolívar comunidad Casimiro Vásquez',NULL,NULL,'2026-11-17 18:32:40','2026-11-17 18:32:40'),(20,'aristides-bastidas','B.P Julia Rosa de López','Casa de la cultura Arístides Bastidas calle principal sector centro. San Pablo',NULL,NULL,'2026-11-17 18:32:40','2026-11-17 18:32:40'),(21,'manuel-monge','B.P Simón Bolívar','Casa de cultura Alí Primera urb. El Centro II Yumare',NULL,NULL,'2026-11-17 18:32:40','2026-11-17 18:32:40'),(22,'san-felipe','','',NULL,NULL,'2026-11-17 18:32:40','2026-11-17 18:32:40'),(23,'urachiche','B.P Domingo Oviedo Parada','Casa de cultura José Félix Rivas entre calles Páez y Ayacucho Guama',NULL,NULL,'2026-11-17 18:32:40','2026-11-17 18:32:40'),(24,'sucre','B.P Mercedes Cordido','Casa de cultura José Félix Rivas entre calles Páez y Ayacucho Guama',NULL,NULL,'2026-11-17 18:32:40','2026-11-17 18:32:40'),(25,'bruzual','B.P Riben Suárez Moreno','Calle 11 cruce Av. 5 casa de cultura José Parra Chivacoa',NULL,NULL,'2026-11-17 18:32:40','2026-11-17 18:32:40'),(26,'bruzual','B.C Poa Poa','Calle principal diagonal a la escuela, comunidad Poa Poa',NULL,NULL,'2026-11-17 18:32:40','2026-11-17 18:32:40'),(27,'aristides-bastidas','hola','hola',1,'31684230','2026-11-17 21:34:23','2026-11-17 21:34:23');
/*!40000 ALTER TABLE `sedes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rol` int(11) NOT NULL DEFAULT '2',
  `estado` enum('habilitado','inhabilitado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'habilitado',
  `sede_id` bigint(20) unsigned DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_sede_id_foreign` (`sede_id`),
  CONSTRAINT `users_sede_id_foreign` FOREIGN KEY (`sede_id`) REFERENCES `sedes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrador General','admin@gmail.com','$2y$10$N2q4wgwcHraQol3eLcnfqOyUOogAg94G3yMDNPe0WEnpP463N.uk6',1,'habilitado',1,'TRfqyjUTmzfHcMQvoRBoE6398bBnj66KiswN4iunSTBPdhHgJHAf7yUST5oZ','2026-11-17 18:32:40','2026-11-17 18:32:40'),(2,'Recepcion General','re@gmail.com','$2y$10$ze/txbCMcK8Mq8Uo34v8MOS8wPULqBQ9HX4ka8.6M5yKWDKESqHmm',2,'habilitado',1,'ILVZymr4Db4F0BUP4O5Hs7KEqkQlc5T3KRb9ZlmxCbyvcVMvFn6XP34r8oE0','2026-11-17 18:32:40','2026-11-17 18:32:40'),(3,'Almacenista General','al@gmail.com','$2y$10$XzHM88IPyv4N06joSAz5AO3.4hJJgxnRa7TNjBzQfKuWrz9KGktBK',3,'habilitado',1,'6dg8CjbQyYssle1HdM1HKSJNcqAUBqUB6m2i3vXAHEQf7Owh1MYhD8N5FzLW','2026-11-17 18:32:40','2026-11-17 18:32:40'),(4,'cocorote','cocorote@gmail.com','$2y$10$AiWanVTFZM7OABPACM0oq.ubymyGstaPBW8r/jUtkHWaq//JRZV2a',1,'habilitado',15,NULL,'2026-11-17 18:32:40','2026-11-17 18:32:40'),(9,'Admin Biblioteca Dr. Félix Pifano','admin01@drfp.com','$2y$10$oPHqmX6wS5KpoMLY7edtvODLOM0byPdYarSOlPSBBCYsZtzfWDvEy',1,'habilitado',1,NULL,'2026-11-17 18:43:24','2026-11-17 18:43:24'),(10,'Admin S.L Luis Alberto Principal','admin02@drfp.com','$2y$10$zeRATcVBY9r8BAajeUUeR.qRyNLQbxsN81B7jd5IpDwdiAWtb7bXe',1,'habilitado',2,NULL,'2026-11-17 18:43:24','2026-11-17 18:43:24'),(11,'Admin S.L San Javier','admin03@drfp.com','$2y$10$RDJHqsvQlzV0xTu6EhSa.u6eo.AYApYhxbXLD1WT.m8sJZZkSiVGi',1,'habilitado',3,NULL,'2026-11-17 18:43:24','2026-11-17 18:43:24'),(12,'Admin B.C Moral y Luces','admin04@drfp.com','$2y$10$1Y06EklnzDckeechXXcG5eY1ihzS4.qj6VlgYqVlfiGaVBzEzqtPy',1,'habilitado',4,NULL,'2026-11-17 18:43:24','2026-11-17 18:43:24'),(13,'Admin B.C Manuelita Sáenz','admin05@drfp.com','$2y$10$fabJzA1BYhZIZ7estdeHcO/DkmwqBksYJjFFr1W1te1zYjMg2ZeZa',1,'habilitado',5,'Q0RruAoQBqjYEuIdmS0mfdu5ykocShTozBjtigeUdcjfBweFDY8mAmDAyXXX','2026-11-17 18:43:24','2026-11-17 18:43:24'),(14,'Admin B.C Ciudadela','admin06@drfp.com','$2y$10$SWfLKwtH2YY4vzSmFwGAnOeJnovsCpCgyNwu2r21LWjvao7LsN9.a',1,'habilitado',6,NULL,'2026-11-17 18:43:24','2026-11-17 18:43:24'),(15,'Admin B.C Higuerón','admin07@drfp.com','$2y$10$hiikzbqLi0yS8XAzjIn8l.6bxzKoA.7rAkxuM0iUJgE1FN0ITUusG',1,'habilitado',7,NULL,'2026-11-17 18:43:24','2026-11-17 18:43:24'),(16,'Admin B.P Soc. Oswaldo Méndez','admin08@drfp.com','$2y$10$Dj0.g8gzCxVopmyrrV4C/OZksvUS/mG1nrANcEJdEn3BTxSllMAPW',1,'habilitado',8,NULL,'2026-11-17 18:43:24','2026-11-17 18:43:24'),(17,'Admin S.L Simón Rodríguez','admin09@drfp.com','$2y$10$dAI37N.tg2CVzpstx9RL3uwJtzp6H0BK9hiN9Jcrs0MJLtYc16Tei',1,'habilitado',9,NULL,'2026-11-17 18:43:25','2026-11-17 18:43:25'),(18,'Admin B.C El Palmar','admin10@drfp.com','$2y$10$oBVNTW2kTTp5X.uk3bCb4..tZ7ohfcLTer6w0/f1QE648MkzNaUN.',1,'habilitado',10,NULL,'2026-11-17 18:43:25','2026-11-17 18:43:25'),(19,'Admin B.P Josefa Marín de Narváez','admin11@drfp.com','$2y$10$CYoZniQS5SsS85UYFFFH3uFYS5NczdmqtE/FGO3edbeWk3TIdF.HO',1,'habilitado',11,NULL,'2026-11-17 18:43:25','2026-11-17 18:43:25'),(20,'Admin P.P Ingrid Fernández','admin12@drfp.com','$2y$10$RhvciKxqRxZQeS5CJSfhtuvh4BChbwc04LM4RY7qtnxJ.qYMlra8K',1,'habilitado',12,NULL,'2026-11-17 18:43:25','2026-11-17 18:43:25'),(21,'Admin B.P Rómulo Gallegos','admin13@drfp.com','$2y$10$WoURdjvzLVpCGl4LdN1GB.K9Typ95L1t0Q.nnWMydV5qfmQCx6556',1,'habilitado',13,NULL,'2026-11-17 18:43:25','2026-11-17 18:43:25'),(22,'Admin S.L Euclides Herrera','admin14@drfp.com','$2y$10$1aq28EZsuOc/2iUh.Jh0uu1Ei/QF4dw/p4VZSFeINYnojWkLL8GgS',1,'habilitado',14,NULL,'2026-11-17 18:43:25','2026-11-17 18:43:25'),(23,'Admin Biblioteca Publica Simon Rodriguez','admin15@drfp.com','$2y$10$iNa94oIpovEjVGfv678/HeB5o0V3am9ceOWdaV3Zt0aHf3jKnXL.2',1,'habilitado',15,'hD9w7y0URo5bSXqRbelPEiN5cDBRtKjbHb2WGN5xQHhcSYqwi509V0ki5ElW','2026-11-17 18:43:25','2026-11-17 18:43:25'),(24,'Admin B.P José Alfonso Daza','admin16@drfp.com','$2y$10$gfVdhg2179vk5sMcoOu9mOUt0.jnfyGXdVGSvQXXqmNGuKCMqUNwu',1,'habilitado',16,NULL,'2026-11-17 18:43:25','2026-11-17 18:43:25'),(25,'Admin S.L Arturo Uslar Pietri','admin17@drfp.com','$2y$10$Hb2g63iPnjk.zjYMAfFKPOV4Hh6.wWC9dOROKEy0zyrT6Nbox78sK',1,'habilitado',17,'BjEOhryqjnVjZdqSrogQbFjLSFeiJ49d7SIfjq1VTZSNA4aJBdcU4DTvXeUB','2026-11-17 18:43:25','2026-11-17 18:43:25'),(26,'Admin S.L Celsa Mendoza Reyes','admin18@drfp.com','$2y$10$t4rxmLqPbJcPd5zwtShdzOWdhRl3r6PDEQN0Be6MdYektLvyf3NFC',1,'habilitado',18,NULL,'2026-11-17 18:43:25','2026-11-17 18:43:25'),(27,'Admin S.L Simón Rodríguez','admin19@drfp.com','$2y$10$rZcICckpcEyzq4irWySzzec7jJmyvecpGOIjTA35RCvC/IU7CWo3y',1,'habilitado',19,NULL,'2026-11-17 18:43:25','2026-11-17 18:43:25'),(28,'Admin B.P Julia Rosa de López','admin20@drfp.com','$2y$10$XhhaOcEMGidqxV0kJcq9jOwsxuoKF9TuSsDPeFO.8LADg0v7fZtXu',1,'habilitado',20,NULL,'2026-11-17 18:43:26','2026-11-17 18:43:26'),(29,'Admin B.P Simón Bolívar','admin21@drfp.com','$2y$10$P.coLpi0H.mUudo7hJSF3OjxI3viqM8k3z80CP75rxGsQNIe3jOjW',1,'habilitado',21,NULL,'2026-11-17 18:43:26','2026-11-17 18:43:26'),(30,'Admin Sede 22','admin22@drfp.com','$2y$10$lnXGgOG2jY4tLhR.2g0.DOH9Pfmu8hA2.xyQ8rPvV.Gur061oQIRi',1,'habilitado',22,NULL,'2026-11-17 18:43:26','2026-11-17 18:43:26'),(31,'Admin B.P Domingo Oviedo Parada','admin23@drfp.com','$2y$10$8ECtGQWjEqzPMfaoUuqM3uuVdMlXMf0QTmUJPR394rW.gcbb7fZre',1,'habilitado',23,NULL,'2026-11-17 18:43:26','2026-11-17 18:43:26'),(32,'Admin B.P Mercedes Cordido','admin24@drfp.com','$2y$10$shSiga7wGyLt6H4Q7D/m6O9vEKyXQifAd52mSAYpUz.VXBzzzmGVq',1,'habilitado',24,NULL,'2026-11-17 18:43:26','2026-11-17 18:43:26'),(33,'Admin B.P Riben Suárez Moreno','admin25@drfp.com','$2y$10$/o.mVSCRIojNIgT1ECzm6e14wI8JZTxuQJrvbtREB6ekhtS8vxiiS',1,'habilitado',25,NULL,'2026-11-17 18:43:26','2026-11-17 18:43:26'),(34,'Admin B.C Poa Poa','admin26@drfp.com','$2y$10$V0opmJSea0dWahNky1ARVuv32pn1bfTy2Uug68dhKj3AbrUuafOBC',1,'habilitado',26,NULL,'2026-11-17 18:43:26','2026-11-17 18:43:26');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `visitas`
--

DROP TABLE IF EXISTS `visitas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `visitas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sede_id` bigint(20) unsigned DEFAULT NULL,
  `persona_id` int(10) unsigned NOT NULL,
  `fecha` date NOT NULL,
  `hora_entrada` time NOT NULL,
  `departamento` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sala_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `visitas_persona_id_foreign` (`persona_id`),
  KEY `visitas_sede_id_foreign` (`sede_id`),
  KEY `visitas_sala_id_foreign` (`sala_id`),
  CONSTRAINT `visitas_persona_id_foreign` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `visitas_sala_id_foreign` FOREIGN KEY (`sala_id`) REFERENCES `salas` (`id`) ON DELETE SET NULL,
  CONSTRAINT `visitas_sede_id_foreign` FOREIGN KEY (`sede_id`) REFERENCES `sedes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `visitas`
--

LOCK TABLES `visitas` WRITE;
/*!40000 ALTER TABLE `visitas` DISABLE KEYS */;
INSERT INTO `visitas` VALUES (1,1,1,'2026-11-17','15:50:07','Administración',6,'2026-11-17 19:50:07','2026-11-17 19:50:07'),(2,1,2,'2026-11-17','17:57:20','Administración',6,'2026-11-17 21:57:20','2026-11-17 21:57:20');
/*!40000 ALTER TABLE `visitas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `visitas_guiadas`
--

DROP TABLE IF EXISTS `visitas_guiadas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `visitas_guiadas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sede_id` bigint(20) unsigned DEFAULT NULL,
  `institucion` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `responsable` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `encargado` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cantidad` int(10) unsigned NOT NULL,
  `fecha_visita` date NOT NULL,
  `hora` time DEFAULT NULL,
  `tipo_recorrido` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sala_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `visitas_guiadas_sede_id_foreign` (`sede_id`),
  KEY `visitas_guiadas_sala_id_foreign` (`sala_id`),
  CONSTRAINT `visitas_guiadas_sala_id_foreign` FOREIGN KEY (`sala_id`) REFERENCES `salas` (`id`) ON DELETE SET NULL,
  CONSTRAINT `visitas_guiadas_sede_id_foreign` FOREIGN KEY (`sede_id`) REFERENCES `sedes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `visitas_guiadas`
--

LOCK TABLES `visitas_guiadas` WRITE;
/*!40000 ALTER TABLE `visitas_guiadas` DISABLE KEYS */;
INSERT INTO `visitas_guiadas` VALUES (1,1,'UPTYAB','eberth Carino','Jose Jose',4,'2026-11-17','07:29:00','Administración',6,'2026-11-17 19:57:44','2026-11-17 19:57:44'),(2,1,'Universidad Politecinica territorial...','eberth Carino','Edgar Bogar',3,'2026-11-17','07:27:00','Administración',6,'2026-11-17 19:58:27','2026-11-17 19:58:27');
/*!40000 ALTER TABLE `visitas_guiadas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'DRFP'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-17 18:27:32
