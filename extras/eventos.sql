-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 22-10-2018 a las 19:48:35
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

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `eventos_organizador_id_foreign` (`organizador_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `eventos_organizador_id_foreign` FOREIGN KEY (`organizador_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
