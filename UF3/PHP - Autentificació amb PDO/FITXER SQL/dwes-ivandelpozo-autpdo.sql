-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-11-2022 a las 18:35:20
-- Versión del servidor: 10.4.25-MariaDB
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `dwes-ivandelpozo-autpdo`
--
CREATE DATABASE IF NOT EXISTS `dwes-ivandelpozo-autpdo` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `dwes-ivandelpozo-autpdo`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `connections`
--

CREATE TABLE `connections` (
  `ip` varchar(15) NOT NULL,
  `user` varchar(50) NOT NULL,
  `time` datetime NOT NULL,
  `status` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `connections`
--

INSERT INTO `connections` (`ip`, `user`, `time`, `status`) VALUES
('::1', 'idelpozo@boscdelacoma.cat', '2022-11-06 16:41:40', 'signup_success'),
('::1', 'idelpozo@boscdelacoma.cat', '2022-11-06 16:41:43', 'logoff'),
('::1', 'idelpozo@boscdelacoma.cat', '2022-11-06 16:41:46', 'signin_success'),
('::1', 'idelpozo@boscdelacoma.cat', '2022-11-06 16:41:47', 'logoff'),
('::1', 'idelpozo@boscdelacoma.cat', '2022-11-06 16:41:50', 'signin_password_error'),
('::1', 'idelpozo@boscdelacoma.ca', '2022-11-06 16:41:54', 'signin_password_error'),
('::1', 'idelpozo@boscdelacoma.cat', '2022-11-06 16:42:33', 'signin_success'),
('::1', 'idelpozo@boscdelacoma.cat', '2022-11-06 16:43:23', 'logoff'),
('::1', 'idelpozo@boscdelacoma.es', '2022-11-06 16:43:34', 'signin_password_error'),
('::1', '', '2022-11-06 16:43:36', 'signin_password_error'),
('::1', 'idelpozo@boscdelacoma.cat', '2022-11-06 16:44:52', 'signup_exist_error'),
('::1', 'test@test.cat', '2022-11-06 16:45:55', 'signin_email_error'),
('::1', 'idelpozo@boscdelacoma.cat', '2022-11-06 16:46:20', 'signin_success'),
('::1', 'idelpozo@boscdelacoma.cat', '2022-11-06 16:46:21', 'logoff'),
('::1', 'idelpozo@boscdelacoma.cat', '2022-11-06 16:46:28', 'signin_password_error'),
('::1', 'idelpozo@boscdelacoma.cat', '2022-11-07 15:26:55', 'signin_success');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `email` varchar(50) NOT NULL,
  `password` varchar(60) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`email`, `password`, `name`) VALUES
('idelpozo@boscdelacoma.cat', 'b6d767d2f8ed5d21a44b0e5886680cb9', 'ivan');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
