-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-06-2018 a las 22:02:23
-- Versión del servidor: 10.1.16-MariaDB
-- Versión de PHP: 5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `archivo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contenedor`
--

CREATE TABLE `contenedor` (
  `id_contenedor` int(11) NOT NULL,
  `identificador` varchar(50) DEFAULT NULL,
  `tipo_contenedor` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direccion`
--

CREATE TABLE `direccion` (
  `cve_direccion` char(5) NOT NULL,
  `nombre` varchar(80) DEFAULT NULL,
  `director` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documento`
--

CREATE TABLE `documento` (
  `id_documento` int(11) NOT NULL,
  `id_contenedor` int(11) NOT NULL,
  `id_documento_tipo` int(11) NOT NULL,
  `fecha_entrega` date DEFAULT NULL,
  `descripcion` varchar(300) DEFAULT NULL,
  `destinatario` varchar(100) DEFAULT NULL,
  `emisor` varchar(100) DEFAULT NULL,
  `fecha_documento` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documento_tipo`
--

CREATE TABLE `documento_tipo` (
  `id_documento_tipo` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `id_factura` int(11) NOT NULL,
  `id_oficio` int(11) DEFAULT NULL,
  `n_factura` varchar(80) DEFAULT NULL,
  `empresa` varchar(250) DEFAULT NULL,
  `descripcion` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oficio`
--

CREATE TABLE `oficio` (
  `id_oficio` int(11) NOT NULL,
  `id_documento` int(11) DEFAULT NULL,
  `ccp1` varchar(80) DEFAULT NULL,
  `ccp2` varchar(80) DEFAULT NULL,
  `ccp3` varchar(80) DEFAULT NULL,
  `ccp4` varchar(80) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turno`
--

CREATE TABLE `turno` (
  `id_turno` int(11) NOT NULL,
  `entrega` varchar(100) NOT NULL,
  `fecha_entrega` date NOT NULL,
  `tipo_documento` varchar(100) DEFAULT NULL,
  `fecha_documento` date NOT NULL,
  `descripcion` varchar(250) DEFAULT NULL,
  `turnado` varchar(100) DEFAULT NULL,
  `documento` varchar(100) NOT NULL,
  `isDelete` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `turno`
--

INSERT INTO `turno` (`id_turno`, `entrega`, `fecha_entrega`, `tipo_documento`, `fecha_documento`, `descripcion`, `turnado`, `documento`, `isDelete`) VALUES
(1, 'sonia', '2018-05-20', 'oficio jjjjj', '2018-06-02', 'oficio 2014', 'clementina', 'data/turno/8549.png', 0),
(2, 'diana', '2018-05-22', 'memorandum', '0000-00-00', 'memorandum para emilio el loquis', 'dr emilio', '', 0),
(3, 'maru', '2018-02-22', 'oficio', '0000-00-00', 'entrega de contratos', 'expediente', '', 0),
(4, 'karen', '2018-05-12', 'oficio', '2018-11-09', 'algo nada mas', 'archivo', '', 0),
(5, 'alicia', '2018-06-02', 'oficio', '2018-06-03', 'ninugan', 'gerardo', 'data/turno/9814.png', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `contenedor`
--
ALTER TABLE `contenedor`
  ADD PRIMARY KEY (`id_contenedor`);

--
-- Indices de la tabla `direccion`
--
ALTER TABLE `direccion`
  ADD PRIMARY KEY (`cve_direccion`);

--
-- Indices de la tabla `documento`
--
ALTER TABLE `documento`
  ADD PRIMARY KEY (`id_documento`),
  ADD KEY `id_contenedor` (`id_contenedor`),
  ADD KEY `id_tipo_documento` (`id_documento_tipo`);

--
-- Indices de la tabla `documento_tipo`
--
ALTER TABLE `documento_tipo`
  ADD PRIMARY KEY (`id_documento_tipo`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`id_factura`),
  ADD UNIQUE KEY `n_factura` (`n_factura`),
  ADD KEY `id_oficio` (`id_oficio`);

--
-- Indices de la tabla `oficio`
--
ALTER TABLE `oficio`
  ADD PRIMARY KEY (`id_oficio`),
  ADD KEY `id_documento` (`id_documento`);

--
-- Indices de la tabla `turno`
--
ALTER TABLE `turno`
  ADD PRIMARY KEY (`id_turno`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `contenedor`
--
ALTER TABLE `contenedor`
  MODIFY `id_contenedor` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `turno`
--
ALTER TABLE `turno`
  MODIFY `id_turno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `documento`
--
ALTER TABLE `documento`
  ADD CONSTRAINT `documento_ibfk_1` FOREIGN KEY (`id_contenedor`) REFERENCES `contenedor` (`id_contenedor`),
  ADD CONSTRAINT `documento_ibfk_2` FOREIGN KEY (`id_documento_tipo`) REFERENCES `documento_tipo` (`id_documento_tipo`);

--
-- Filtros para la tabla `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `factura_ibfk_1` FOREIGN KEY (`id_oficio`) REFERENCES `oficio` (`id_oficio`);

--
-- Filtros para la tabla `oficio`
--
ALTER TABLE `oficio`
  ADD CONSTRAINT `oficio_ibfk_1` FOREIGN KEY (`id_documento`) REFERENCES `documento` (`id_documento`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
