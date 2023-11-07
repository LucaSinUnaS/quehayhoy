-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 07-11-2023 a las 04:03:19
-- Versión del servidor: 10.5.19-MariaDB-cll-lve
-- Versión de PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `u956478100_quehayhoy`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `id_comment` int(11) NOT NULL,
  `id_event` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `username` varchar(16) NOT NULL,
  `email` varchar(50) NOT NULL,
  `foto` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `comment` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unvalidated_events`
--

CREATE TABLE `unvalidated_events` (
  `id` int(11) NOT NULL,
  `username` varchar(16) NOT NULL,
  `email` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  `portada` varchar(60) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descripcion` varchar(2000) NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `generos` varchar(50) NOT NULL,
  `fecha` varchar(100) NOT NULL,
  `primera_fecha` varchar(50) NOT NULL,
  `primera_hora` varchar(50) NOT NULL,
  `hora` varchar(100) NOT NULL,
  `lugar` varchar(100) NOT NULL,
  `ubicacion` varchar(300) NOT NULL,
  `departamento` varchar(50) NOT NULL,
  `barrio` varchar(50) NOT NULL,
  `precio` int(50) NOT NULL,
  `link` varchar(300) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(16) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` char(60) NOT NULL,
  `created` datetime NOT NULL,
  `admin` tinyint(1) NOT NULL,
  `descripcion` varchar(1000) NOT NULL,
  `foto` varchar(100) NOT NULL,
  `pagina` varchar(300) NOT NULL,
  `instagram` varchar(100) NOT NULL,
  `facebook` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `validated_events`
--

CREATE TABLE `validated_events` (
  `id` int(11) NOT NULL,
  `username` varchar(16) NOT NULL,
  `email` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  `portada` varchar(60) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descripcion` varchar(2000) NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `generos` varchar(50) NOT NULL,
  `fecha` varchar(100) NOT NULL,
  `primera_fecha` varchar(50) NOT NULL,
  `primera_hora` varchar(50) NOT NULL,
  `hora` varchar(100) NOT NULL,
  `lugar` varchar(100) NOT NULL,
  `ubicacion` varchar(300) NOT NULL,
  `departamento` varchar(50) NOT NULL,
  `barrio` varchar(50) NOT NULL,
  `precio` int(50) NOT NULL,
  `link` varchar(300) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `unvalidated_events`
--
ALTER TABLE `unvalidated_events`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `validated_events`
--
ALTER TABLE `validated_events`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `unvalidated_events`
--
ALTER TABLE `unvalidated_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `validated_events`
--
ALTER TABLE `validated_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
