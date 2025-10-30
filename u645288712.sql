-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 29-10-2025 a las 22:15:41
-- Versión del servidor: 11.8.3-MariaDB-log
-- Versión de PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `u645288712_1`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adjunto`
--

CREATE TABLE `adjunto` (
  `id` int(11) NOT NULL,
  `estudio` varchar(45) NOT NULL,
  `informe` text NOT NULL,
  `url` varchar(60) NOT NULL,
  `fecha` date NOT NULL,
  `id_paciente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agenda`
--

CREATE TABLE `agenda` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `duracion` int(11) NOT NULL,
  `hora` time NOT NULL,
  `motivo` varchar(100) NOT NULL,
  `id_paciente` int(11) NOT NULL,
  `estado` int(11) NOT NULL,
  `id_odontologo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuenta`
--

CREATE TABLE `cuenta` (
  `id` int(11) NOT NULL,
  `id_procedimiento` int(11) NOT NULL,
  `estado` varchar(45) NOT NULL,
  `unidad` varchar(10) NOT NULL,
  `costo` decimal(10,2) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paciente`
--

CREATE TABLE `paciente` (
  `id` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `apellido` varchar(45) NOT NULL,
  `ci` int(11) NOT NULL,
  `telefono` varchar(11) NOT NULL,
  `email` varchar(45) DEFAULT NULL,
  `fecha` date NOT NULL DEFAULT current_timestamp(),
  `genero` varchar(10) NOT NULL,
  `direccion` varchar(60) NOT NULL,
  `observaciones` varchar(60) NOT NULL,
  `extension` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `procedimiento`
--

CREATE TABLE `procedimiento` (
  `id` int(11) NOT NULL,
  `pieza` varchar(8) NOT NULL,
  `sector` varchar(8) NOT NULL,
  `patologia` varchar(45) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `estado` varchar(45) NOT NULL,
  `medicacion` varchar(45) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha` date NOT NULL,
  `id_paciente` int(11) NOT NULL,
  `adjunto` varchar(10) NOT NULL,
  `usuario` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `apellido` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `tipo` varchar(15) NOT NULL,
  `nombre_usuario` varchar(45) NOT NULL,
  `cliente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vistapacientesmayoratencion`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vistapacientesmayoratencion` (
`id` int(11)
,`nombre` varchar(45)
,`apellido` varchar(45)
,`ci` int(11)
,`telefono` varchar(11)
,`email` varchar(45)
,`cantidad_atencion` bigint(21)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vistaprocedimientosmasimplementados`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vistaprocedimientosmasimplementados` (
`nombre` varchar(45)
,`cantidad` bigint(21)
);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `agenda`
--
ALTER TABLE `agenda`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cuenta`
--
ALTER TABLE `cuenta`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `paciente`
--
ALTER TABLE `paciente`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `procedimiento`
--
ALTER TABLE `procedimiento`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `agenda`
--
ALTER TABLE `agenda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de la tabla `cuenta`
--
ALTER TABLE `cuenta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de la tabla `paciente`
--
ALTER TABLE `paciente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT de la tabla `procedimiento`
--
ALTER TABLE `procedimiento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=362;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

-- --------------------------------------------------------

--
-- Estructura para la vista `vistapacientesmayoratencion`
--
DROP TABLE IF EXISTS `vistapacientesmayoratencion`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u645288712_cli1`@`127.0.0.1` SQL SECURITY DEFINER VIEW `vistapacientesmayoratencion`  AS SELECT `p`.`id` AS `id`, `p`.`nombre` AS `nombre`, `p`.`apellido` AS `apellido`, `p`.`ci` AS `ci`, `p`.`telefono` AS `telefono`, `p`.`email` AS `email`, count(`pr`.`id`) AS `cantidad_atencion` FROM (`paciente` `p` join `procedimiento` `pr` on(`p`.`id` = `pr`.`id_paciente`)) GROUP BY `p`.`id`, `p`.`nombre`, `p`.`apellido`, `p`.`ci`, `p`.`telefono`, `p`.`email` ORDER BY count(`pr`.`id`) DESC ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vistaprocedimientosmasimplementados`
--
DROP TABLE IF EXISTS `vistaprocedimientosmasimplementados`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u645288712_cli1`@`127.0.0.1` SQL SECURITY DEFINER VIEW `vistaprocedimientosmasimplementados`  AS SELECT `procedimiento`.`nombre` AS `nombre`, count(0) AS `cantidad` FROM `procedimiento` GROUP BY `procedimiento`.`nombre` ORDER BY count(0) DESC ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
