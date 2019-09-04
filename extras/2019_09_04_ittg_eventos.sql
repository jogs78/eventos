-- MySQL dump 10.13  Distrib 5.6.44, for Linux (x86_64)
--
-- Host: localhost    Database: ittg_eventos
-- ------------------------------------------------------
-- Server version	5.6.44-cll-lve

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
INSERT INTO `asistente_evento` VALUES (11,3,'0',NULL,'2019-08-31 06:22:42','2019-08-31 06:22:42'),(12,3,'2','HBbm5JidYFxdkz6XlUsoMacq7aQwcca4m5lpEBfZ.jpeg','2019-08-31 06:52:53','2019-08-31 06:53:40'),(14,3,'0',NULL,'2019-08-31 07:15:24','2019-08-31 07:15:24');
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
INSERT INTO `asistente_subevento` VALUES (3,2,12,'2',NULL,'2019-08-31 06:54:00','2019-08-31 06:54:00');
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
INSERT INTO `colaborador_subevento` VALUES (9,2,'R'),(10,2,'A'),(9,3,'A'),(9,4,'R'),(10,4,'A');
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eventos`
--

LOCK TABLES `eventos` WRITE;
/*!40000 ALTER TABLE `eventos` DISABLE KEYS */;
INSERT INTO `eventos` VALUES (3,'1er. Congreso Nacional en Sistemas y Tecnologías Aplicados','Objetivos generales:\n	Fortalecer en los estudiantes la competencia de Investigación profesional en cada uno de los ejes temáticos de las asignaturas que integran los planes de estudio.\n	Fomentar la investigación científica, tecnológica y la innovación de las tecnologías de la información y la comunicación a nivel regional y nacional\n	Divulgar resultados de investigación realizadas, tanto a la comunidad académica y estudiantil, como al público interesado\n	Fortalecer la investigación básica, aplicada y tecnológica\n	Facilitar un espacio de encuentro que permita el intercambio de opiniones y experiencias que enriquezcan la investigación actual y conduzcan a la conjugación de nuevos proyectos de investigación interdisciplinarios e interinstitucionales',NULL,'http://www.ittg.edu.mx','2019-10-23','2019-10-26','1','Deposito',1,8,'2019-08-31 05:45:51','2019-08-31 05:53:56');
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `precios`
--

LOCK TABLES `precios` WRITE;
/*!40000 ALTER TABLE `precios` DISABLE KEYS */;
INSERT INTO `precios` VALUES (2,'Socios colegiados',2000,1,'App\\Evento','2018-08-13 05:05:03','2018-08-13 05:05:03'),(3,'Público en general',2500,1,'App\\Evento','2018-08-13 05:05:03','2018-08-13 05:05:03'),(4,'Ponente',1000,3,'App\\Evento','2019-08-31 05:45:51','2019-08-31 05:45:51'),(5,'Colegiado',200,3,'App\\Evento','2019-08-31 05:45:51','2019-08-31 05:45:51'),(6,'Estudiante(Asistente)',400,3,'App\\Evento','2019-08-31 05:45:51','2019-08-31 05:45:51');
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subeventos`
--

LOCK TABLES `subeventos` WRITE;
/*!40000 ALTER TABLE `subeventos` DISABLE KEYS */;
INSERT INTO `subeventos` VALUES (2,'Taller de principios de programación','En este taller se hablara de que se debe tener en cuenta para aprender a programar',NULL,'2019-10-25 04:00:00','Edificio D1',NULL,19,3,'2019-08-31 05:53:56','2019-08-31 06:54:00'),(3,'Conferencia IOT','Se hablará del INTERNET DE LAS COSAS',NULL,'2019-10-25 04:15:00','Sala Adiovisual xyz',NULL,NULL,3,'2019-08-31 05:56:46','2019-08-31 05:56:46'),(4,'Taller de ARDUINO','Se usara el OPEN HARDWARE',NULL,'2019-10-25 04:00:00','D1',NULL,20,3,'2019-08-31 06:10:29','2019-08-31 06:10:29');
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'administrador','adminap','adminam','m',NULL,NULL,NULL,'administrador@eventos.ittg.mx','$2y$10$axWsGdGki.WEgrdV.unOUeRqPo9D9NBiXuaorbOtxJTyXf57cTiB.','0','1',NULL,'ARmsvU49coR0z9lr42Ev3YyqmgD3SZVNwrV35K2PyoWsHo2GYFgk9L1cJN6B','2018-08-13 04:07:07','2018-08-13 04:07:07'),(8,'imelda','valles','lopez','f',NULL,NULL,'9616150380','imevalles@yahoo.com','$2y$10$axWsGdGki.WEgrdV.unOUeRqPo9D9NBiXuaorbOtxJTyXf57cTiB.','1','1','mWuyaeQsibfZA1gQvQZWhbQD51lpmRdLvrgHNH3h','WCrPwVt1b5lPOQB7y1lNTquTbKLSk3mF4hVe7jhvg0TrTRnyubccG59QYfgE','2019-08-31 05:40:33','2019-08-31 05:40:33'),(9,'jorge octavio','guzmán','sánchez','m',NULL,NULL,'9611917915','jguzman@ittg.edu.mx','$2y$10$PV7O5gszXSsnF0WPQDzhne1cQbf1y7CFRoIldna0E9/9K2L3X2IV2','1','1','GBZnr8q1oZ1TF8mRdDK5fYpJCOtFJ90NA6JVqkfB','rdF1sR89dTdPCcAxFxxDtcRD5ZQjX0q5tDRrBzSCQ0aG1e2VsPaLWw7OrieH','2019-08-31 06:00:17','2019-08-31 06:00:17'),(10,'brenda lizeth','pérez','chacon','f',NULL,NULL,'961650380','bperez@ittg.edu.mx','$2y$10$oB3QFxF/MakK4pZ8z/0O6O0IR0YgrhQ1jpla6jITmjKsGIAUUxNLS','1','1','h3i4N6LS8P6xvlSsiqSOa9YapV8BHuqGMQjqBjHC','Keo0ZD562dtHnXQIN2VQHKgJ8grtcpjvu3ccfJCbgcmvZBhpOeaJjlmvxokr','2019-08-31 06:01:15','2019-08-31 06:01:15'),(11,'ponente','guzman','sanchez','m','Docente','ITTG','961','jogs79gmail.com','$2y$10$D/T2R04a1KoyJk6vb38ZLe7X.BR1kUtNbwFgQo3cWiRPmAuphdzky','2','1','8lP8f9IlsyjU1AYasZ2yXrfH2bjvYUSYdNeMou7e','LUYQ2T4YGirgFYPGbqG8ZjVH2Krd2VqsIUAP89mY3w8daGF2gVyWoXQhjTsL','2019-08-31 06:13:03','2019-08-31 06:13:03'),(12,'Ponente J','guzmán','sáchez','m','Docente','ITTG','961','jogs78@gmail.com','$2y$10$CTKx8IJy02.USydBMPgIresMnUJqbR/4DenTeUCRYehEzTGlg3jOm','2','1','XEL8q8ghReOQQyrmetBmZEyOPjk4Hzy23CtH7U10','1Lbi7OdIGyBnVWsDhOyatOfoc1FelNP6UnCr30WIcJkiubc3f3FYhxMalPDr','2019-08-31 06:25:21','2019-08-31 06:25:21'),(13,'héctor','guerra','crespo','m',NULL,NULL,'961','hgcrespo@hotmail.com','$2y$10$l7qEwqIsRjVhQNMYLncFnufq85onBl/ixx1/mHtHWCSRhPKrfpPBS','1','1','qoBmUd5WSqB0SNpycswRUgxHfOkiYMfgomr01eQq','7iyxwFoEka02zNvv3hKmOblRENrRTmaAPo8HZJoFqz610x3k9JXCiukLzE3l','2019-08-31 06:42:50','2019-08-31 06:42:50'),(14,'admin','guzman','sanchez','m',NULL,NULL,'961','jogs78@hotmail.com','$2y$10$exTXTB0Rvlt0jygpO9Qo6O.mGaaPpHiadtd4P.t9ap1.itykcth/e','0','1',NULL,'OQVZiI35nT5tPKzDyn78W96FcMrkBJapLTrNhksAPG4u5U6SPTRHfHIQv2Lu','2019-08-31 07:07:39','2019-08-31 07:15:09');
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

-- Dump completed on 2019-09-04  1:54:30
