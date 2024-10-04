-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-10-2024 a las 05:32:18
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ecommerce`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `nombres` varchar(255) NOT NULL,
  `apellidos` varchar(255) NOT NULL,
  `doc` int(20) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `pais` varchar(128) NOT NULL,
  `estado` varchar(128) NOT NULL,
  `ciudad` varchar(128) NOT NULL,
  `distrito` varchar(128) NOT NULL,
  `postal` varchar(64) NOT NULL,
  `geolocalizacion` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `fecha_registro` varchar(11) DEFAULT current_timestamp(),
  `estado_cliente` enum('activo','inactivo') DEFAULT 'activo',
  `ult_session` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta`
--

CREATE TABLE `detalle_venta` (
  `id_detalle` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `cantidad` varchar(11) NOT NULL,
  `precio` varchar(11) NOT NULL,
  `descuento_aplicado` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagenes`
--

CREATE TABLE `imagenes` (
  `id_imagen` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `ruta` text NOT NULL,
  `img_principal` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `imagenes`
--

INSERT INTO `imagenes` (`id_imagen`, `id_producto`, `ruta`, `img_principal`) VALUES
(1, 1, 'img/productos/herramientas-electricas/maquinarias/326904-1200-1200.webp', ''),
(2, 1, 'img/productos/herramientas-electricas/maquinarias/326903-1200-1200.webp', ''),
(3, 1, 'img/productos/herramientas-electricas/maquinarias/326902-1200-1200.webp', ''),
(4, 1, 'img/productos/herramientas-electricas/maquinarias/326901-1200-1200.webp', 'si');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `detalles` text NOT NULL,
  `categoria` varchar(128) NOT NULL,
  `subcategoria` varchar(128) NOT NULL,
  `marca` varchar(128) NOT NULL,
  `material` varchar(128) NOT NULL,
  `origen` varchar(128) NOT NULL,
  `peso` varchar(11) NOT NULL,
  `fecha_ingreso` varchar(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `precio` varchar(11) NOT NULL,
  `oferta` varchar(11) NOT NULL,
  `destacado` varchar(11) NOT NULL,
  `documentacion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `descripcion`, `detalles`, `categoria`, `subcategoria`, `marca`, `material`, `origen`, `peso`, `fecha_ingreso`, `stock`, `precio`, `oferta`, `destacado`, `documentacion`) VALUES
(1, 'Rotomartillo Stanley Con Maletín Y Accesorios 1250W', 'Poderoso motor de 1250W para un amplio rango de aplicaciones de taladrado y cincelado\r\nDiseño con piston en \"L\" para mayor poder\r\nIndicador de servicio y consumo de carbones para facilitar mantenimiento\r\nEmbrague de seguridad para proteger al usuario\r\nFácil acceso a los carbones\r\nVelocidad variable para mayor control\r\nViene con carbón adicional y adaptador para brocas regulares\r\n\r\nCAPACIDAD DE CORTE/PERFORACIÓN:\r\nMadera: 40mm\r\nMetal: 32mm\r\nHormigón: 13mm\r\nINCLUYE: Maletín, Empuñadura lateral, Limitador de profundidad, Mechas, Punta, Cincel, Manual\r\n', 'herramientas-electricas', 'maquinarias', 'Stanley', '', 'China', '8.375', '30/09/2024', 21, '569', '', '', 'documentos/productos/herramientas-electricas/maquinarias/Ficha Técnica de Barra Hexagonal.pdf'),
(2, '', '', '', '', 'gaa', '', '', '', '', 2, '12', '', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `nombres` varchar(255) NOT NULL,
  `apellidos` varchar(255) NOT NULL,
  `rol` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `email`, `pass`, `nombres`, `apellidos`, `rol`) VALUES
(1, 'rsmendozarod@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', 'Richard Sebastian', 'Mendoza Rodriguez', 'Administrador'),
(2, 'richard@example.com', 'pass123', 'Richard', 'Mendoza', 'usuario'),
(3, 'sebastian@example.com', 'pass234', 'Sebastian', 'Gomez', 'usuario'),
(5, 'maria@example.com', 'pass456', 'Maria', 'Fernandez', 'usuario'),
(6, 'juan@example.com', 'pass567', 'Juan', 'Perez', 'usuario'),
(7, 'luisa@example.com', 'pass678', 'Luisa', 'Martinez', 'usuario'),
(8, 'carlos@example.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Carlos', 'Garcia aaaa', 'usuario'),
(9, 'sofia@example.com', 'pass890', 'Sofia', 'Rodriguez', 'usuario'),
(10, 'pedro@example.com', 'pass901', 'Pedro', 'Hernandez', 'usuario'),
(11, 'laura@example.com', '202cb962ac59075b964b07152d234b70', 'Laura', 'Gutierrez', 'usuario'),
(12, 'diego@example.com', 'pass222', 'Diego', 'Ramirez', 'usuario'),
(13, 'valentina@example.com', 'pass333', 'Valentina', 'Ortega', 'usuario'),
(14, 'fernando@example.com', 'pass444', 'Fernando', 'Sanchez', 'usuario'),
(15, 'paula@example.com', 'pass555', 'Paula', 'Morales', 'usuario'),
(19, 'niancita@gmail.com', '202cb962ac59075b964b07152d234b70', 'niancita', 'rodriguez', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id_venta` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `fecha` varchar(11) NOT NULL,
  `monto_total` decimal(10,2) NOT NULL,
  `metodo_pago` varchar(50) NOT NULL,
  `estado_pago` enum('pendiente','completado','cancelado') DEFAULT 'pendiente',
  `numero_transaccion` varchar(255) DEFAULT NULL,
  `direccion_envio` varchar(255) DEFAULT NULL,
  `codigo_rastreo` varchar(100) DEFAULT NULL,
  `impuestos` decimal(10,2) DEFAULT 0.00,
  `descuento` decimal(10,2) DEFAULT 0.00,
  `fecha_envio` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Indices de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `fkidProd` (`id_venta`),
  ADD KEY `fkidVenta` (`id_producto`);

--
-- Indices de la tabla `imagenes`
--
ALTER TABLE `imagenes`
  ADD PRIMARY KEY (`id_imagen`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `fkidCli` (`id_cliente`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `imagenes`
--
ALTER TABLE `imagenes`
  MODIFY `id_imagen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `detalle_venta_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `imagenes`
--
ALTER TABLE `imagenes`
  ADD CONSTRAINT `imagenes_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
