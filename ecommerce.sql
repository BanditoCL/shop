-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 16, 2024 at 12:27 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `precio` varchar(11) NOT NULL,
  `cantidad` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `id_cliente`, `id_producto`, `descripcion`, `precio`, `cantidad`) VALUES
(10, 3, 6, 'MARTILLO COMPACTO SDS-PLUS 1250W 32MM 3-MODOS', '499', 2),
(11, 3, 5, 'ROTOMARTILLO SDS PLUS 26mm', '589', 2);

-- --------------------------------------------------------

--
-- Table structure for table `clientes`
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
  `fecha_registro` varchar(11) DEFAULT NULL,
  `estado_cliente` enum('activo','inactivo') DEFAULT 'activo',
  `ult_session` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `email`, `pass`, `nombres`, `apellidos`, `doc`, `direccion`, `pais`, `estado`, `ciudad`, `distrito`, `postal`, `geolocalizacion`, `telefono`, `fecha_registro`, `estado_cliente`, `ult_session`) VALUES
(3, 'willian@example.com', '$2y$10$BjWKqv5A75o8XEs8SjrgJuxjAeJLeT1WAWbeGMzo5rkaytX/.uThO', 'willian', 'caro', 54125587, 'Carreterra Panamericana km 18', 'Peru', 'Lima', 'Lima', 'Puente Piedra', '15121', '', '987 745 634', NULL, 'activo', ''),
(4, 'realeza@example.com', '$2y$10$uGzJbMhkyzpzjk9rMJo6AuBqRvOapgfY6hM9ImySSPkPlAnzwbRhu', 'makanaki', 'realeza', 54742575, '', '', '', '', '', '', '', NULL, NULL, 'activo', '');

-- --------------------------------------------------------

--
-- Table structure for table `detalle_venta`
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
-- Table structure for table `imagenes`
--

CREATE TABLE `imagenes` (
  `id_imagen` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `ruta` text NOT NULL,
  `img_principal` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `imagenes`
--

INSERT INTO `imagenes` (`id_imagen`, `id_producto`, `ruta`, `img_principal`) VALUES
(15, 5, 'imagenes_productos/herramientas-electricas/maquinarias/5/1728926692_D25133K_K2.jpg', '1'),
(16, 5, 'imagenes_productos/herramientas-electricas/maquinarias/5/1728926692_D25133K_4.jpg', '0'),
(17, 5, 'imagenes_productos/herramientas-electricas/maquinarias/5/1728926692_D25133K_2.jpg', '0'),
(18, 5, 'imagenes_productos/herramientas-electricas/maquinarias/5/1728926692_D25133K_1.jpg', '0'),
(19, 5, 'imagenes_productos/herramientas-electricas/maquinarias/5/1728926692_D25133K_3.jpg', '0'),
(20, 6, 'imagenes_productos/herramientas-electricas/maquinarias/6/1728936597_326901-1200-1200.webp', '1'),
(21, 6, 'imagenes_productos/herramientas-electricas/maquinarias/6/1728936597_326904-1200-1200.webp', '0'),
(22, 6, 'imagenes_productos/herramientas-electricas/maquinarias/6/1728936597_326903-1200-1200.webp', '0'),
(23, 6, 'imagenes_productos/herramientas-electricas/maquinarias/6/1728936598_326902-1200-1200.webp', '0');

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `modelo` varchar(128) NOT NULL,
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
-- Dumping data for table `productos`
--

INSERT INTO `productos` (`id_producto`, `descripcion`, `modelo`, `detalles`, `categoria`, `subcategoria`, `marca`, `material`, `origen`, `peso`, `fecha_ingreso`, `stock`, `precio`, `oferta`, `destacado`, `documentacion`) VALUES
(5, 'ROTOMARTILLO SDS PLUS 26mm', 'D25133K-B2', 'Excelente relación peso - energía de impacto\r\nDiseño compacto y liviano para un cómodo uso y contínuo\r\nEmbrague de seguridad en caso de que la broca se atasque\r\n\r\nMás características\r\nVelocidad variable para mayor control en la aplicación y modo reversible para poder retirar las brocas con mayor facilidad luego de la perforación\r\nSelector de 3 modos de uso rotación, percusión y cincelado', 'herramientas-electricas', 'maquinarias', 'DeWalt', 'Resina, Acero', 'Tailandia', '2.6', '2024-10-14', 9, '589', '', '', ''),
(6, 'MARTILLO COMPACTO SDS-PLUS 1250W 32MM 3-MODOS', 'STHR1232K', 'Poderoso motor de 1250W para un amplio rango de aplicaciones de taladrado y cincelado\r\nDiseño con pistón en \"L\" para mayor poder\r\nIndicador de servicio y consumo de carbones para facilitar mantenimiento', 'herramientas-electricas', 'maquinarias', 'Standley', 'Resina, Acero', 'China', '3.5', '2024-10-14', 23, '499', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
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
-- Dumping data for table `usuarios`
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
-- Table structure for table `ventas`
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
  `impuestos` varchar(11) DEFAULT NULL,
  `descuento` varchar(11) DEFAULT NULL,
  `fecha_envio` varchar(11) DEFAULT NULL,
  `notas` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Indexes for table `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `fkidProd` (`id_venta`),
  ADD KEY `fkidVenta` (`id_producto`);

--
-- Indexes for table `imagenes`
--
ALTER TABLE `imagenes`
  ADD PRIMARY KEY (`id_imagen`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- Indexes for table `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `fkidCli` (`id_cliente`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `imagenes`
--
ALTER TABLE `imagenes`
  MODIFY `id_imagen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `detalle_venta_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `imagenes`
--
ALTER TABLE `imagenes`
  ADD CONSTRAINT `imagenes_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
