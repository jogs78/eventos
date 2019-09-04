-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 22-10-2018 a las 20:07:23
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
-- Estructura de tabla para la tabla `asistente_evento`
--

CREATE TABLE `asistente_evento` (
  `asistente_id` int(10) UNSIGNED NOT NULL,
  `evento_id` int(10) UNSIGNED NOT NULL,
  `estatus` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `url_baucher` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistente_subevento`
--

CREATE TABLE `asistente_subevento` (
  `evento_id` int(10) UNSIGNED NOT NULL,
  `subevento_id` int(10) UNSIGNED NOT NULL,
  `asistente_id` int(10) UNSIGNED NOT NULL,
  `estatus` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `url_baucher` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `colaborador_subevento`
--

CREATE TABLE `colaborador_subevento` (
  `colaborador_id` int(10) UNSIGNED NOT NULL,
  `subevento_id` int(10) UNSIGNED NOT NULL,
  `tipo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos`
--

CREATE TABLE `eventos` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_imagen` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url_mas_info` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_finalizacion` date NOT NULL,
  `visible` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `detalles_pago` text COLLATE utf8mb4_unicode_ci,
  `max_subeventos_elegibles` int(10) UNSIGNED DEFAULT NULL,
  `organizador_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `eventos`
--

INSERT INTO `eventos` (`id`, `nombre`, `descripcion`, `url_imagen`, `url_mas_info`, `fecha_inicio`, `fecha_finalizacion`, `visible`, `detalles_pago`, `max_subeventos_elegibles`, `organizador_id`, `created_at`, `updated_at`) VALUES
(1, 'Congreso CIME', '...', 'PsSVbKDFlBkoedacy2nUlVoVCCtEhmGfrBUF5ZY4.png', 'http://cimechiapas.com.mx/iii-expocongreso/', '2018-09-20', '2018-09-21', '1', 'Opción 1:\r\nBanco: HSBC \r\nNúmero de cuenta: 4000944421\r\nClabe interbancaria: 0211000400009444218\r\n\r\nOpción 2:\r\nBanco: Inbursa\r\nNúmero de cuenta: 50014314843\r\nClabe interbancaria: 036100500143148439\r\n\r\nOpción 3: \r\nBanco: Banamex\r\nNúmero de cuenta: 889176suc.7008\r\nClabe interbancaria: 002100700808891761', 15, 5, '2018-08-04 09:57:59', '2018-08-13 05:05:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(248, '2014_10_12_000000_create_users_table', 1),
(249, '2014_10_12_100000_create_password_resets_table', 1),
(250, '2018_01_02_222107_create_eventos_table', 1),
(251, '2018_01_02_222521_create_subeventos_table', 1),
(252, '2018_03_03_182220_asistente_evento', 1),
(253, '2018_03_03_182240_asistente_subevento', 1),
(254, '2018_03_03_182309_colaborador_subevento', 1),
(255, '2018_08_09_151420_create_precios_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `precios`
--

CREATE TABLE `precios` (
  `id` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `precio` double UNSIGNED NOT NULL,
  `precio_id` int(10) UNSIGNED NOT NULL,
  `precio_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `precios`
--

INSERT INTO `precios` (`id`, `descripcion`, `precio`, `precio_id`, `precio_type`, `created_at`, `updated_at`) VALUES
(1, 'Estudiantes', 400, 1, 'App\\Evento', '2018-08-13 05:05:03', '2018-08-13 05:05:03'),
(2, 'Socios colegiados', 2000, 1, 'App\\Evento', '2018-08-13 05:05:03', '2018-08-13 05:05:03'),
(3, 'Público en general', 2500, 1, 'App\\Evento', '2018-08-13 05:05:03', '2018-08-13 05:05:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subeventos`
--

CREATE TABLE `subeventos` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_imagen` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha` datetime NOT NULL,
  `lugar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detalles_pago` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `limite_asistentes` int(10) UNSIGNED DEFAULT NULL,
  `evento_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `subeventos`
--

INSERT INTO `subeventos` (`id`, `nombre`, `descripcion`, `url_imagen`, `fecha`, `lugar`, `detalles_pago`, `limite_asistentes`, `evento_id`, `created_at`, `updated_at`) VALUES
(1, 'DISEÑOS DE PUESTA A TIERRA O SELECCIÓN DE SUPRESORES', '...', NULL, '2018-09-21 09:00:00', 'Sala de talleres 1\r\nSalón Bonampak', NULL, 50, 1, '2018-08-08 23:43:26', '2018-08-08 23:52:55');

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
-- Indices de la tabla `asistente_evento`
--
ALTER TABLE `asistente_evento`
  ADD KEY `asistente_evento_evento_id_foreign` (`evento_id`),
  ADD KEY `asistente_evento_asistente_id_foreign` (`asistente_id`);

--
-- Indices de la tabla `asistente_subevento`
--
ALTER TABLE `asistente_subevento`
  ADD KEY `asistente_subevento_asistente_id_foreign` (`asistente_id`),
  ADD KEY `asistente_subevento_evento_id_foreign` (`evento_id`),
  ADD KEY `asistente_subevento_subevento_id_foreign` (`subevento_id`);

--
-- Indices de la tabla `colaborador_subevento`
--
ALTER TABLE `colaborador_subevento`
  ADD KEY `colaborador_subevento_subevento_id_foreign` (`subevento_id`),
  ADD KEY `colaborador_subevento_colaborador_id_foreign` (`colaborador_id`);

--
-- Indices de la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `eventos_organizador_id_foreign` (`organizador_id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indices de la tabla `precios`
--
ALTER TABLE `precios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `subeventos`
--
ALTER TABLE `subeventos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subeventos_evento_id_foreign` (`evento_id`);

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
-- AUTO_INCREMENT de la tabla `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=256;

--
-- AUTO_INCREMENT de la tabla `precios`
--
ALTER TABLE `precios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `subeventos`
--
ALTER TABLE `subeventos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asistente_evento`
--
ALTER TABLE `asistente_evento`
  ADD CONSTRAINT `asistente_evento_asistente_id_foreign` FOREIGN KEY (`asistente_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `asistente_evento_evento_id_foreign` FOREIGN KEY (`evento_id`) REFERENCES `eventos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `asistente_subevento`
--
ALTER TABLE `asistente_subevento`
  ADD CONSTRAINT `asistente_subevento_asistente_id_foreign` FOREIGN KEY (`asistente_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `asistente_subevento_evento_id_foreign` FOREIGN KEY (`evento_id`) REFERENCES `eventos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `asistente_subevento_subevento_id_foreign` FOREIGN KEY (`subevento_id`) REFERENCES `subeventos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `colaborador_subevento`
--
ALTER TABLE `colaborador_subevento`
  ADD CONSTRAINT `colaborador_subevento_colaborador_id_foreign` FOREIGN KEY (`colaborador_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `colaborador_subevento_subevento_id_foreign` FOREIGN KEY (`subevento_id`) REFERENCES `subeventos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `eventos_organizador_id_foreign` FOREIGN KEY (`organizador_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `subeventos`
--
ALTER TABLE `subeventos`
  ADD CONSTRAINT `subeventos_evento_id_foreign` FOREIGN KEY (`evento_id`) REFERENCES `eventos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
