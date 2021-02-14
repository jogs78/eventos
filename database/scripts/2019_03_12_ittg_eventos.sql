-- MySQL dump 10.13  Distrib 5.7.22, for Linux (x86_64)
--
-- Host: localhost    Database: ittg_eventos
-- ------------------------------------------------------
-- Server version	5.7.22-0ubuntu18.04.1

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
-- Table structure for table `asistente_evento`
--

DROP TABLE IF EXISTS `asistente_evento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asistente_evento` (
  `asistente_id` int(10) unsigned NOT NULL,
  `evento_id` int(10) unsigned NOT NULL,
  `estatus` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `url_baucher` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `asistente_evento_evento_id_foreign` (`evento_id`),
  KEY `asistente_evento_asistente_id_foreign` (`asistente_id`),
  CONSTRAINT `asistente_evento_asistente_id_foreign` FOREIGN KEY (`asistente_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `asistente_evento_evento_id_foreign` FOREIGN KEY (`evento_id`) REFERENCES `eventos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asistente_evento`
--

LOCK TABLES `asistente_evento` WRITE;
/*!40000 ALTER TABLE `asistente_evento` DISABLE KEYS */;
/*!40000 ALTER TABLE `asistente_evento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asistente_subevento`
--

DROP TABLE IF EXISTS `asistente_subevento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asistente_subevento` (
  `evento_id` int(10) unsigned NOT NULL,
  `subevento_id` int(10) unsigned NOT NULL,
  `asistente_id` int(10) unsigned NOT NULL,
  `estatus` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `url_baucher` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `asistente_subevento_asistente_id_foreign` (`asistente_id`),
  KEY `asistente_subevento_evento_id_foreign` (`evento_id`),
  KEY `asistente_subevento_subevento_id_foreign` (`subevento_id`),
  CONSTRAINT `asistente_subevento_asistente_id_foreign` FOREIGN KEY (`asistente_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `asistente_subevento_evento_id_foreign` FOREIGN KEY (`evento_id`) REFERENCES `eventos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `asistente_subevento_subevento_id_foreign` FOREIGN KEY (`subevento_id`) REFERENCES `subeventos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asistente_subevento`
--

LOCK TABLES `asistente_subevento` WRITE;
/*!40000 ALTER TABLE `asistente_subevento` DISABLE KEYS */;
/*!40000 ALTER TABLE `asistente_subevento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `colaborador_subevento`
--

DROP TABLE IF EXISTS `colaborador_subevento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `colaborador_subevento` (
  `colaborador_id` int(10) unsigned NOT NULL,
  `subevento_id` int(10) unsigned NOT NULL,
  `tipo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A',
  KEY `colaborador_subevento_subevento_id_foreign` (`subevento_id`),
  KEY `colaborador_subevento_colaborador_id_foreign` (`colaborador_id`),
  CONSTRAINT `colaborador_subevento_colaborador_id_foreign` FOREIGN KEY (`colaborador_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `colaborador_subevento_subevento_id_foreign` FOREIGN KEY (`subevento_id`) REFERENCES `subeventos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `colaborador_subevento`
--

LOCK TABLES `colaborador_subevento` WRITE;
/*!40000 ALTER TABLE `colaborador_subevento` DISABLE KEYS */;
/*!40000 ALTER TABLE `colaborador_subevento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `eventos`
--

DROP TABLE IF EXISTS `eventos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `eventos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_imagen` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url_mas_info` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_finalizacion` date NOT NULL,
  `visible` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `detalles_pago` text COLLATE utf8mb4_unicode_ci,
  `max_subeventos_elegibles` int(10) unsigned DEFAULT NULL,
  `organizador_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `eventos_organizador_id_foreign` (`organizador_id`),
  CONSTRAINT `eventos_organizador_id_foreign` FOREIGN KEY (`organizador_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eventos`
--

LOCK TABLES `eventos` WRITE;
/*!40000 ALTER TABLE `eventos` DISABLE KEYS */;
INSERT INTO `eventos` VALUES (1,'Congreso CIME','...','PsSVbKDFlBkoedacy2nUlVoVCCtEhmGfrBUF5ZY4.png','http://cimechiapas.com.mx/iii-expocongreso/','2018-09-20','2018-09-21','1','Opción 1:\r\nBanco: HSBC \r\nNúmero de cuenta: 4000944421\r\nClabe interbancaria: 0211000400009444218\r\n\r\nOpción 2:\r\nBanco: Inbursa\r\nNúmero de cuenta: 50014314843\r\nClabe interbancaria: 036100500143148439\r\n\r\nOpción 3: \r\nBanco: Banamex\r\nNúmero de cuenta: 889176suc.7008\r\nClabe interbancaria: 002100700808891761',15,5,'2018-08-04 09:57:59','2018-08-13 05:05:03'),(2,'Congreso ISC','Descripción ISC','nANWDcPMBLb5c5IEG7LbZ6Gzz0Qj92k6Q4v9Cp0E.jpeg','http://phpmyadmin.homestead','2018-10-22','2018-10-24','1',NULL,NULL,5,'2018-10-22 15:09:54','2018-10-22 15:09:54');
/*!40000 ALTER TABLE `eventos` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=256 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (248,'2014_10_12_000000_create_users_table',1),(249,'2014_10_12_100000_create_password_resets_table',1),(250,'2018_01_02_222107_create_eventos_table',1),(251,'2018_01_02_222521_create_subeventos_table',1),(252,'2018_03_03_182220_asistente_evento',1),(253,'2018_03_03_182240_asistente_subevento',1),(254,'2018_03_03_182309_colaborador_subevento',1),(255,'2018_08_09_151420_create_precios_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
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
-- Table structure for table `precios`
--

DROP TABLE IF EXISTS `precios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `precios` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `precio` double unsigned NOT NULL,
  `precio_id` int(10) unsigned NOT NULL,
  `precio_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `precios`
--

LOCK TABLES `precios` WRITE;
/*!40000 ALTER TABLE `precios` DISABLE KEYS */;
INSERT INTO `precios` VALUES (1,'Estudiantes',400,1,'App\\Evento','2018-08-13 05:05:03','2018-08-13 05:05:03'),(2,'Socios colegiados',2000,1,'App\\Evento','2018-08-13 05:05:03','2018-08-13 05:05:03'),(3,'Público en general',2500,1,'App\\Evento','2018-08-13 05:05:03','2018-08-13 05:05:03');
/*!40000 ALTER TABLE `precios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subeventos`
--

DROP TABLE IF EXISTS `subeventos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subeventos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_imagen` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha` datetime NOT NULL,
  `lugar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detalles_pago` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `limite_asistentes` int(10) unsigned DEFAULT NULL,
  `evento_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subeventos_evento_id_foreign` (`evento_id`),
  CONSTRAINT `subeventos_evento_id_foreign` FOREIGN KEY (`evento_id`) REFERENCES `eventos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subeventos`
--

LOCK TABLES `subeventos` WRITE;
/*!40000 ALTER TABLE `subeventos` DISABLE KEYS */;
INSERT INTO `subeventos` VALUES (1,'DISEÑOS DE PUESTA A TIERRA O SELECCIÓN DE SUPRESORES','...',NULL,'2018-09-21 09:00:00','Sala de talleres 1\r\nSalón Bonampak',NULL,50,1,'2018-08-08 23:43:26','2018-08-08 23:52:55');
/*!40000 ALTER TABLE `subeventos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido_paterno` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido_materno` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sexo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ocupacion` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `procedencia` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '2',
  `verificado` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `token_verificacion` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'administrador','adminap','adminam','m',NULL,NULL,NULL,'administrador@eventos.ittg.mx','$2y$10$W2OLcXMK/GcpVCytOQ8wOu.YwA38GLUH4PuRmLiEU3rnogqLr0Ti6','0','1',NULL,'n9mZ2jQ4mehjxjYynrw2THfoj2qCaB9twUi23ma3LarVGqkxCtNrjsJ6ZyII','2018-08-13 04:07:07','2018-08-13 04:07:07'),(2,'luis javier','valencia','ramírez','m',NULL,NULL,NULL,'javier_lujavar@hotmail.com','$2y$10$W2OLcXMK/GcpVCytOQ8wOu.YwA38GLUH4PuRmLiEU3rnogqLr0Ti6','0','1','3dAjfJV4CU7tNFibZngPKfZokwpnyT5sXwl1FIV2','UBQXrw6yxXgqM8C0ZB7CRdkDQrU8vYWbKiyFv08mGUV191bS8JgtM8JK5CpH','2018-08-04 09:52:34','2018-08-04 09:52:34'),(3,'hector','guerra','crespo','m',NULL,NULL,NULL,'hgcrespo@hotmail.com','$2y$10$W2OLcXMK/GcpVCytOQ8wOu.YwA38GLUH4PuRmLiEU3rnogqLr0Ti6','1','1',NULL,'9TFjM1nGI0iwKDShgngqwCGPmqX8vVEorfFmdDMjMBnTSedajlGcbFUuC9NM','2018-08-04 09:57:04','2018-08-04 20:16:10'),(5,'colegio','de','ingenieros','m',NULL,NULL,'9612502122','expocongresochiapas.cime@gmail.com','$2y$10$W2OLcXMK/GcpVCytOQ8wOu.YwA38GLUH4PuRmLiEU3rnogqLr0Ti6','1','0','KI9DaWVFU0k2ZYkBGsCzQx5iBhvCDimxWIO9zriV','54gWkb6BWgCFk3xFWFNofoBVfBNMIYcUOnJrRDZ8hATMxGKcVLRTFPNiJute','2018-08-08 23:03:28','2018-08-08 23:03:28'),(6,'nimia silvana','peñaloza','albores','f','Gerente','CIME Chiapas','9611165294','silvana_1210@hotmail.com','$2y$10$W2OLcXMK/GcpVCytOQ8wOu.YwA38GLUH4PuRmLiEU3rnogqLr0Ti6','2','1',NULL,NULL,'2018-08-09 00:22:34','2018-08-09 00:23:53'),(7,'roberto','sk','sdk','m','nada',NULL,NULL,'roberto@yopmail.com','$2y$10$W2OLcXMK/GcpVCytOQ8wOu.YwA38GLUH4PuRmLiEU3rnogqLr0Ti6','2','1','9HLIO39kssy9BKOfdUSmoDjceONXU2FEt7bOvkJm','1gpMmpcQ3TM4redBhWm8pJNeFl3PtrH3f3CPrecRYmoZ9zIU8QStGe2MZ3ij','2018-10-16 19:36:52','2018-10-16 19:36:52');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-03-13  5:34:03
