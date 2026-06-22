-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 22-06-2026 a las 18:13:47
-- Versión del servidor: 9.1.0
-- Versión de PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `pnks`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fotos_propiedades`
--

DROP TABLE IF EXISTS `fotos_propiedades`;
CREATE TABLE IF NOT EXISTS `fotos_propiedades` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_propiedad` int NOT NULL,
  `nombre_archivo` varchar(255) NOT NULL,
  `es_principal` tinyint(1) DEFAULT '0',
  `estado` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_propiedad` (`id_propiedad`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `fotos_propiedades`
--

INSERT INTO `fotos_propiedades` (`id`, `id_propiedad`, `nombre_archivo`, `es_principal`, `estado`, `created_at`) VALUES
(8, 24, 'prop_24_6a389d743204a.jpg', 0, 1, '2026-06-22 02:27:00'),
(10, 24, 'prop_24_6a38a1c891729.jpeg', 1, 1, '2026-06-22 02:45:28'),
(13, 31, 'prop_31_6a39367d5fc19.jpeg', 1, 1, '2026-06-22 13:19:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfiles`
--

DROP TABLE IF EXISTS `perfiles`;
CREATE TABLE IF NOT EXISTS `perfiles` (
  `idperfil` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `estado` int NOT NULL,
  PRIMARY KEY (`idperfil`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `perfiles`
--

INSERT INTO `perfiles` (`idperfil`, `nombre`, `estado`) VALUES
(1, 'Administrador', 1),
(2, 'Propietario', 1),
(3, 'Gestor Inmobiliario', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `propiedades`
--

DROP TABLE IF EXISTS `propiedades`;
CREATE TABLE IF NOT EXISTS `propiedades` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `tipo_propiedad` enum('Casa','Departamento','Terreno') NOT NULL,
  `descripcion` text NOT NULL,
  `banos` int DEFAULT '0',
  `dormitorios` int DEFAULT '0',
  `area_terreno` decimal(10,2) DEFAULT '0.00',
  `area_construida` decimal(10,2) DEFAULT '0.00',
  `precio_pesos` bigint DEFAULT '0',
  `precio_uf` decimal(10,2) DEFAULT '0.00',
  `region` varchar(100) NOT NULL,
  `provincia` varchar(100) NOT NULL,
  `comuna` varchar(100) NOT NULL,
  `sector` varchar(150) DEFAULT '',
  `bodega` tinyint(1) DEFAULT '0',
  `estacionamiento` tinyint(1) DEFAULT '0',
  `logia` tinyint(1) DEFAULT '0',
  `cocina_amoblada` tinyint(1) DEFAULT '0',
  `antejardin` tinyint(1) DEFAULT '0',
  `patio_trasero` tinyint(1) DEFAULT '0',
  `piscina` tinyint(1) DEFAULT '0',
  `solicitar_visita` tinyint(1) DEFAULT '0',
  `fecha_publicacion` date NOT NULL,
  `estado` enum('activa','inactiva','vendida') DEFAULT 'activa',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_prop_tipo` (`tipo_propiedad`),
  KEY `idx_prop_region` (`region`),
  KEY `idx_prop_comuna` (`comuna`),
  KEY `idx_prop_estado` (`estado`),
  KEY `idx_prop_usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `propiedades`
--

INSERT INTO `propiedades` (`id`, `id_usuario`, `tipo_propiedad`, `descripcion`, `banos`, `dormitorios`, `area_terreno`, `area_construida`, `precio_pesos`, `precio_uf`, `region`, `provincia`, `comuna`, `sector`, `bodega`, `estacionamiento`, `logia`, `cocina_amoblada`, `antejardin`, `patio_trasero`, `piscina`, `solicitar_visita`, `fecha_publicacion`, `estado`, `created_at`) VALUES
(24, 3, 'Casa', 'Hermosa casa de campo ubicada en el corazón del Valle del Elqui, rodeada de viñedos y cielos despejados. Ideal para descanso familiar o turismo rural.', 3, 4, 1200.00, 280.00, 180000000, 4750.00, '', '', '', '', 1, 1, 1, 1, 1, 1, 1, 1, '2026-06-22', 'activa', '2026-06-22 01:49:32'),
(31, 3, 'Casa', 'Casa familiar de buen tamaño en sector residencial El Milagro, con espacios cómodos y excelente conectividad. Ideal para familias que buscan tranquilidad y cercanía a servicios.', 2, 4, 280.00, 160.00, 285000000, 7500.00, 'Región de Coquimbo', 'Elqui', 'La Serena', 'El milagro', 1, 1, 1, 1, 1, 1, 0, 1, '2026-06-22', 'activa', '2026-06-22 13:19:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `rut` varchar(20) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `nombre` varchar(100) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `apellido` varchar(100) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `genero` varchar(20) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `telefono` varchar(20) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `email` varchar(100) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `clave` varchar(100) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `estado` varchar(10) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL DEFAULT 'activo',
  `fecha_hora` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `foto` varchar(100) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT 'default.png',
  `idperfil` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idperfil` (`idperfil`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `rut`, `nombre`, `apellido`, `fecha_nacimiento`, `genero`, `telefono`, `email`, `clave`, `estado`, `fecha_hora`, `foto`, `idperfil`) VALUES
(3, '33.333.333-3', 'Jorge', 'Cortes', '1992-03-03', 'Masculino', '+56933333333', 'jcortes@pnk.cl', 'qwerty123', 'activo', '2026-05-18 10:09:35', 'foto3.png', 1),
(26, '44.444.444-4	', 'Juanita', 'Perez', '0000-00-00', '', '', 'jperez@pnk.cl', '', '1', '2026-05-18 18:26:41', 'user_6a38299c52665.jpg', 2),
(53, '194922175', 'Pamela Andrea', 'Mejia Berrios', '0000-00-00', '', '', 'pamelamb0808@gmail.com', 'qwerty123', 'activo', '2026-06-21 18:11:46', 'user_6a3829bf50918.webp', 3),
(55, '99.999.999-9', 'Juan', 'Perez', '1999-09-09', 'Masculino', '+56999999999', 'juanperez@pnk.cl', 'qwerty123', '1', '2026-06-22 11:20:38', 'default.png', 2),
(58, '22.078.319-7', 'Nikito', 'Saavedra', '2006-03-16', 'Masculino', '+56911111111', 'nikito@pnk.cl', 'qwerty123', 'activo', '2026-06-22 16:24:05', 'user_6a39623392ec4.jpg', 2);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `fotos_propiedades`
--
ALTER TABLE `fotos_propiedades`
  ADD CONSTRAINT `fotos_propiedades_ibfk_1` FOREIGN KEY (`id_propiedad`) REFERENCES `propiedades` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `propiedades`
--
ALTER TABLE `propiedades`
  ADD CONSTRAINT `propiedades_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`idperfil`) REFERENCES `perfiles` (`idperfil`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
