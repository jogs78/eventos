-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 14-02-2021 a las 05:19:27
-- Versión del servidor: 5.6.49-cll-lve
-- Versión de PHP: 7.2.34

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

--
-- Volcado de datos para la tabla `asistente_evento`
--

INSERT INTO `asistente_evento` (`asistente_id`, `evento_id`, `estatus`, `url_baucher`, `created_at`, `updated_at`) VALUES
(11, 3, '0', NULL, '2019-08-31 06:22:42', '2019-08-31 06:22:42'),
(12, 3, '2', 'HBbm5JidYFxdkz6XlUsoMacq7aQwcca4m5lpEBfZ.jpeg', '2019-08-31 06:52:53', '2019-08-31 06:53:40'),
(14, 3, '0', NULL, '2019-08-31 07:15:24', '2019-08-31 07:15:24');

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

--
-- Volcado de datos para la tabla `asistente_subevento`
--

INSERT INTO `asistente_subevento` (`evento_id`, `subevento_id`, `asistente_id`, `estatus`, `url_baucher`, `created_at`, `updated_at`) VALUES
(3, 2, 12, '2', NULL, '2019-08-31 06:54:00', '2019-08-31 06:54:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `colaborador_subevento`
--

CREATE TABLE `colaborador_subevento` (
  `colaborador_id` int(10) UNSIGNED NOT NULL,
  `subevento_id` int(10) UNSIGNED NOT NULL,
  `tipo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `colaborador_subevento`
--

INSERT INTO `colaborador_subevento` (`colaborador_id`, `subevento_id`, `tipo`) VALUES
(9, 2, 'R'),
(10, 2, 'A'),
(9, 3, 'A'),
(9, 4, 'R'),
(10, 4, 'A');

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
(3, '1er. Congreso Nacional en Sistemas y Tecnologías Aplicados', 'Objetivos generales:\n	Fortalecer en los estudiantes la competencia de Investigación profesional en cada uno de los ejes temáticos de las asignaturas que integran los planes de estudio.\n	Fomentar la investigación científica, tecnológica y la innovación de las tecnologías de la información y la comunicación a nivel regional y nacional\n	Divulgar resultados de investigación realizadas, tanto a la comunidad académica y estudiantil, como al público interesado\n	Fortalecer la investigación básica, aplicada y tecnológica\n	Facilitar un espacio de encuentro que permita el intercambio de opiniones y experiencias que enriquezcan la investigación actual y conduzcan a la conjugación de nuevos proyectos de investigación interdisciplinarios e interinstitucionales', 'ZyW0ZnOyBG7YBCjF7g1DDOR0f4mWmcM59FRNlBSP.jpeg', 'http://www.cisc-tuxtla.org', '2019-10-23', '2021-10-26', '1', 'Deposito', 1, 8, '2019-08-31 05:45:51', '2021-02-14 05:33:56'),
(4, '2do Congreso Internacional', 'do Con', 'UnDfziD072RyZ3IbvbwCFgdcx4tUCxeTKp9JpIYh.png', NULL, '2021-02-19', '2021-02-27', '1', NULL, NULL, 9, '2021-02-14 05:31:28', '2021-02-14 05:40:17');

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
(2, 'Socios colegiados', 2000, 1, 'App\\Evento', '2018-08-13 05:05:03', '2018-08-13 05:05:03'),
(3, 'Público en general', 2500, 1, 'App\\Evento', '2018-08-13 05:05:03', '2018-08-13 05:05:03'),
(4, 'Ponente', 1000, 3, 'App\\Evento', '2019-08-31 05:45:51', '2019-08-31 05:45:51'),
(5, 'Colegiado', 200, 3, 'App\\Evento', '2019-08-31 05:45:51', '2019-08-31 05:45:51'),
(6, 'Estudiante(Asistente)', 400, 3, 'App\\Evento', '2019-08-31 05:45:51', '2019-08-31 05:45:51');

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
(2, 'Taller de principios de programación', 'En este taller se hablara de que se debe tener en cuenta para aprender a programar', NULL, '2019-10-25 04:00:00', 'Edificio D1', NULL, 19, 3, '2019-08-31 05:53:56', '2019-08-31 06:54:00'),
(3, 'Conferencia IOT', 'Se hablará del INTERNET DE LAS COSAS', NULL, '2019-10-25 04:15:00', 'Sala Adiovisual xyz', NULL, NULL, 3, '2019-08-31 05:56:46', '2019-08-31 05:56:46'),
(4, 'Taller de ARDUINO', 'Se usara el OPEN HARDWARE', NULL, '2019-10-25 04:00:00', 'D1', NULL, 20, 3, '2019-08-31 06:10:29', '2019-08-31 06:10:29'),
(5, 'Confrerencia 1', 'Aun por definir', 'eH3zpS7TmnJGKJ0ukf6TdzbeomdMRUJiuZ9EWV2t.png', '2021-02-20 22:39:58', 'Aun por definir', NULL, NULL, 4, '2021-02-14 05:40:17', '2021-02-14 05:40:17');

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
(1, 'administrador', 'adminap', 'adminam', 'm', NULL, NULL, NULL, 'administrador@eventos.ittg.mx', '$2y$10$axWsGdGki.WEgrdV.unOUeRqPo9D9NBiXuaorbOtxJTyXf57cTiB.', '0', '1', NULL, 'F1oeh0pOzBDI7fUIX4eyCDO5CY4IjlB8w8SNzuobHQEsgE3BWPWC5HwABewa', '2018-08-13 04:07:07', '2018-08-13 04:07:07'),
(8, 'imelda', 'valles', 'lopez', 'f', NULL, NULL, '9616150380', 'imevalles@yahoo.com', '$2y$10$axWsGdGki.WEgrdV.unOUeRqPo9D9NBiXuaorbOtxJTyXf57cTiB.', '1', '1', 'mWuyaeQsibfZA1gQvQZWhbQD51lpmRdLvrgHNH3h', 'nZw3s7Mhml6w42cdI2KduNnMubvbm5E6GmnKlnGqTs1s9QvVgLRrpFyR0aKQ', '2019-08-31 05:40:33', '2019-08-31 05:40:33'),
(9, 'jorge octavio', 'guzmán', 'sánchez', 'm', NULL, NULL, '9611917915', 'jguzman@ittg.edu.mx', '$2y$10$PV7O5gszXSsnF0WPQDzhne1cQbf1y7CFRoIldna0E9/9K2L3X2IV2', '1', '1', 'GBZnr8q1oZ1TF8mRdDK5fYpJCOtFJ90NA6JVqkfB', 'UBQh4KWxWSmdOp6qLlDqdikZ163ojJRnKA1ybk22LOXnnAEmoxP2ypQ5KZll', '2019-08-31 06:00:17', '2019-08-31 06:00:17'),
(10, 'brenda lizeth', 'pérez', 'chacon', 'f', NULL, NULL, '961650380', 'bperez@ittg.edu.mx', '$2y$10$oB3QFxF/MakK4pZ8z/0O6O0IR0YgrhQ1jpla6jITmjKsGIAUUxNLS', '1', '1', 'h3i4N6LS8P6xvlSsiqSOa9YapV8BHuqGMQjqBjHC', 'p5MPCYr2Bjs5GmvJqNM0fJb1MucXPN2NmzIoZL7yr4CLIf1EshhqKZw32gWo', '2019-08-31 06:01:15', '2019-08-31 06:01:15'),
(11, 'ponente', 'guzman', 'sanchez', 'm', 'Docente', 'ITTG', '961', 'jogs79gmail.com', '$2y$10$D/T2R04a1KoyJk6vb38ZLe7X.BR1kUtNbwFgQo3cWiRPmAuphdzky', '2', '1', '8lP8f9IlsyjU1AYasZ2yXrfH2bjvYUSYdNeMou7e', 'LUYQ2T4YGirgFYPGbqG8ZjVH2Krd2VqsIUAP89mY3w8daGF2gVyWoXQhjTsL', '2019-08-31 06:13:03', '2019-08-31 06:13:03'),
(12, 'Ponente J', 'guzmán', 'sáchez', 'm', 'Docente', 'ITTG', '961', 'jogs78@gmail.com', '$2y$10$CTKx8IJy02.USydBMPgIresMnUJqbR/4DenTeUCRYehEzTGlg3jOm', '2', '1', 'XEL8q8ghReOQQyrmetBmZEyOPjk4Hzy23CtH7U10', '1Lbi7OdIGyBnVWsDhOyatOfoc1FelNP6UnCr30WIcJkiubc3f3FYhxMalPDr', '2019-08-31 06:25:21', '2019-08-31 06:25:21'),
(13, 'héctor', 'guerra', 'crespo', 'm', NULL, NULL, '961', 'hgcrespo@hotmail.com', '$2y$10$l7qEwqIsRjVhQNMYLncFnufq85onBl/ixx1/mHtHWCSRhPKrfpPBS', '1', '1', 'qoBmUd5WSqB0SNpycswRUgxHfOkiYMfgomr01eQq', '7iyxwFoEka02zNvv3hKmOblRENrRTmaAPo8HZJoFqz610x3k9JXCiukLzE3l', '2019-08-31 06:42:50', '2019-08-31 06:42:50'),
(14, 'admin', 'guzman', 'sanchez', 'm', NULL, NULL, '961', 'jogs78@hotmail.com', '$2y$10$exTXTB0Rvlt0jygpO9Qo6O.mGaaPpHiadtd4P.t9ap1.itykcth/e', '0', '1', NULL, 'ShVSqs3dVZuFBiJtftB9ukl0nEFIRyxpAH1t9QMKHn8L9SBx9SgggEs0d34m', '2019-08-31 07:07:39', '2019-08-31 07:15:09'),
(15, 'francisco', 'zenteno', 'estrada', 'm', 'asesor en finanzas digitales', 'independiente', '9612513463', 'fraraze@hotmail.com', '$2y$10$jYbPx6CFeAND0Vm/jQy4Xe3ymojLComCyZFDzusGSBRG9.WAa6Qwu', '2', '1', NULL, NULL, '2019-10-14 17:09:44', '2019-10-14 17:11:20'),
(16, 'cinthia itzel', 'robles', 'chacón', 'f', 'Estudiante', 'Instituto tecnológico de Tapachula', '9621938657', 'cinthia_robles@hotmail.com', '$2y$10$XEbmElu0sU9HxZcjLvT64.WmhvYCA33YnThCj.j1Gu1q49PpddXcq', '2', '1', NULL, 'WEktc2aYkyqlupZy8q7U8jzTIofhHVm04vWLZIiqJdlKclLiKeqrEbwwzutr', '2019-10-15 00:36:18', '2019-10-15 00:38:46');

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=256;

--
-- AUTO_INCREMENT de la tabla `precios`
--
ALTER TABLE `precios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `subeventos`
--
ALTER TABLE `subeventos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

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
