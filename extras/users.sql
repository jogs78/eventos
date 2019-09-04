-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 22-10-2018 a las 20:07:59
-- Versión del servidor: 5.7.22-0ubuntu18.04.1
-- Versión de PHP: 7.1.16-1+ubuntu18.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ittg_eventos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `nombre`, `apellido_paterno`, `apellido_materno`, `sexo`, `ocupacion`, `procedencia`, `telefono`, `email`, `password`, `tipo`, `verificado`, `token_verificacion`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'administrador', 'adminap', 'adminam', 'm', NULL, NULL, NULL, 'administrador@eventos.ittg.mx', '$2y$10$W2OLcXMK/GcpVCytOQ8wOu.YwA38GLUH4PuRmLiEU3rnogqLr0Ti6', '0', '1', NULL, 'NrkARSnX4Qdhm9dljMGqnq2jOqAGe3Rxbg3kGa96UKgdS02DTpcz32zVNFq7', '2018-08-13 04:07:07', '2018-08-13 04:07:07'),
(2, 'luis javier', 'valencia', 'ramírez', 'm', NULL, NULL, NULL, 'javier_lujavar@hotmail.com', '$2y$10$W2OLcXMK/GcpVCytOQ8wOu.YwA38GLUH4PuRmLiEU3rnogqLr0Ti6', '0', '1', '3dAjfJV4CU7tNFibZngPKfZokwpnyT5sXwl1FIV2', 'UBQXrw6yxXgqM8C0ZB7CRdkDQrU8vYWbKiyFv08mGUV191bS8JgtM8JK5CpH', '2018-08-04 09:52:34', '2018-08-04 09:52:34'),
(3, 'hector', 'guerra', 'crespo', 'm', NULL, NULL, NULL, 'hgcrespo@hotmail.com', '$2y$10$W2OLcXMK/GcpVCytOQ8wOu.YwA38GLUH4PuRmLiEU3rnogqLr0Ti6', '1', '1', NULL, '9TFjM1nGI0iwKDShgngqwCGPmqX8vVEorfFmdDMjMBnTSedajlGcbFUuC9NM', '2018-08-04 09:57:04', '2018-08-04 20:16:10'),
(5, 'colegio', 'de', 'ingenieros', 'm', NULL, NULL, '9612502122', 'expocongresochiapas.cime@gmail.com', '$2y$10$W2OLcXMK/GcpVCytOQ8wOu.YwA38GLUH4PuRmLiEU3rnogqLr0Ti6', '1', '0', 'KI9DaWVFU0k2ZYkBGsCzQx5iBhvCDimxWIO9zriV', '54gWkb6BWgCFk3xFWFNofoBVfBNMIYcUOnJrRDZ8hATMxGKcVLRTFPNiJute', '2018-08-08 23:03:28', '2018-08-08 23:03:28'),
(6, 'nimia silvana', 'peñaloza', 'albores', 'f', 'Gerente', 'CIME Chiapas', '9611165294', 'silvana_1210@hotmail.com', '$2y$10$W2OLcXMK/GcpVCytOQ8wOu.YwA38GLUH4PuRmLiEU3rnogqLr0Ti6', '2', '1', NULL, NULL, '2018-08-09 00:22:34', '2018-08-09 00:23:53'),
(7, 'roberto', 'sk', 'sdk', 'm', 'nada', NULL, NULL, 'roberto@yopmail.com', '$2y$10$W2OLcXMK/GcpVCytOQ8wOu.YwA38GLUH4PuRmLiEU3rnogqLr0Ti6', '2', '1', '9HLIO39kssy9BKOfdUSmoDjceONXU2FEt7bOvkJm', '1gpMmpcQ3TM4redBhWm8pJNeFl3PtrH3f3CPrecRYmoZ9zIU8QStGe2MZ3ij', '2018-10-16 19:36:52', '2018-10-16 19:36:52');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
