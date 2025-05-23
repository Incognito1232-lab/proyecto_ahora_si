-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-05-2025 a las 19:03:30
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema_estadistica`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `admin_id` int(11) NOT NULL,
  `admin_usuario` varchar(50) NOT NULL,
  `admin_contrasena` varchar(255) NOT NULL,
  `admin_nombre` varchar(100) NOT NULL,
  `admin_correo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`admin_id`, `admin_usuario`, `admin_contrasena`, `admin_nombre`, `admin_correo`) VALUES
(6, 'test', 'test', 'a', 'aaaaa@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `archivo`
--

CREATE TABLE `archivo` (
  `archivo_id` int(11) NOT NULL,
  `archivo_nom` varchar(255) NOT NULL,
  `archivo_ruta` varchar(255) NOT NULL,
  `ciclo_num` int(11) NOT NULL,
  `malla_id` int(11) NOT NULL,
  `cur_id` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrera`
--

CREATE TABLE `carrera` (
  `carr_id` int(11) NOT NULL,
  `carr_nom` varchar(50) NOT NULL,
  `malla_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carrera`
--

INSERT INTO `carrera` (`carr_id`, `carr_nom`, `malla_id`) VALUES
(1, 'Economía', 1),
(2, 'Gestión Empresarial', 1),
(3, 'Estadística', 1),
(4, 'Generales', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `curso`
--

CREATE TABLE `curso` (
  `cur_id` varchar(11) NOT NULL,
  `cur_nom` varchar(50) NOT NULL,
  `cur_cred` int(11) NOT NULL,
  `malla_id` int(11) NOT NULL,
  `ciclo_num` int(11) NOT NULL,
  `carrera_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `curso`
--

INSERT INTO `curso` (`cur_id`, `cur_nom`, `cur_cred`, `malla_id`, `ciclo_num`, `carrera_id`) VALUES
('EP1019', 'Administracion general', 4, 1, 3, 2),
('EP1054', 'Introducción a la ingeniería en gestión empresaria', 4, 1, 1, 2),
('EP1056', 'Dibujo de ingeniería ', 4, 1, 2, 2),
('EP2011', 'Contabilidad general', 4, 1, 4, 2),
('EP2052', 'Matematica financiera', 4, 1, 3, 2),
('EP2093', 'Derecho empresarial', 4, 1, 2, 2),
('EP2096', 'Ingeniería de métodos', 3, 1, 3, 2),
('EP3034', 'Investigación de operaciones', 4, 1, 4, 2),
('EP3074', 'Marketing', 4, 1, 4, 2),
('EP3080', 'Comportamiento y cultura de las organizaciones', 4, 1, 3, 2),
('EP3192', 'Investigación e inteligencia de mercados', 4, 1, 3, 2),
('EP3193', 'Historia de la tecnología', 4, 1, 4, 2),
('EP3196', 'Contabilidad gerencial y de costos', 4, 1, 5, 2),
('EP3197', 'Gerencia de ventas', 4, 1, 5, 2),
('EP3198', 'Sistemas de informacion gerencial', 4, 1, 5, 2),
('EP3200', 'Gestión del talento', 4, 1, 5, 2),
('EP4059', 'Organización y métodos', 4, 1, 5, 2),
('EP5029', 'Estadistica aplicada a la economía y los negocios ', 4, 1, 5, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mallacurricular`
--

CREATE TABLE `mallacurricular` (
  `malla_id` int(11) NOT NULL,
  `malla_anio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mallacurricular`
--

INSERT INTO `mallacurricular` (`malla_id`, `malla_anio`) VALUES
(1, 2019),
(2, 2021);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `admin_usuario` (`admin_usuario`);

--
-- Indices de la tabla `archivo`
--
ALTER TABLE `archivo`
  ADD PRIMARY KEY (`archivo_id`),
  ADD KEY `cur_id` (`cur_id`),
  ADD KEY `malla_id` (`malla_id`);

--
-- Indices de la tabla `carrera`
--
ALTER TABLE `carrera`
  ADD PRIMARY KEY (`carr_id`);

--
-- Indices de la tabla `curso`
--
ALTER TABLE `curso`
  ADD PRIMARY KEY (`cur_id`),
  ADD KEY `malla_id` (`malla_id`),
  ADD KEY `carrera_id` (`carrera_id`);

--
-- Indices de la tabla `mallacurricular`
--
ALTER TABLE `mallacurricular`
  ADD PRIMARY KEY (`malla_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administradores`
--
ALTER TABLE `administradores`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `archivo`
--
ALTER TABLE `archivo`
  MODIFY `archivo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `carrera`
--
ALTER TABLE `carrera`
  MODIFY `carr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `mallacurricular`
--
ALTER TABLE `mallacurricular`
  MODIFY `malla_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `archivo`
--
ALTER TABLE `archivo`
  ADD CONSTRAINT `archivo_ibfk_1` FOREIGN KEY (`cur_id`) REFERENCES `curso` (`cur_id`),
  ADD CONSTRAINT `archivo_ibfk_2` FOREIGN KEY (`malla_id`) REFERENCES `mallacurricular` (`malla_id`);

--
-- Filtros para la tabla `curso`
--
ALTER TABLE `curso`
  ADD CONSTRAINT `curso_ibfk_1` FOREIGN KEY (`malla_id`) REFERENCES `mallacurricular` (`malla_id`),
  ADD CONSTRAINT `curso_ibfk_2` FOREIGN KEY (`carrera_id`) REFERENCES `carrera` (`carr_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
