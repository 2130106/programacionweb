-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 13-04-2024 a las 22:40:49
-- Versión del servidor: 8.0.31
-- Versión de PHP: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `usuarios`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `archivos`
--

DROP TABLE IF EXISTS `archivos`;
CREATE TABLE IF NOT EXISTS `archivos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int DEFAULT NULL,
  `nombre_archivo` varchar(255) DEFAULT NULL,
  `ruta_archivo` varchar(255) DEFAULT NULL,
  `visibilidad` enum('privado','publico') DEFAULT 'publico',
  `fecha_upload` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `archivos`
--

INSERT INTO `archivos` (`id`, `id_usuario`, `nombre_archivo`, `ruta_archivo`, `visibilidad`, `fecha_upload`) VALUES
(29, 8, 'Edita tu horario directamente..jpg', '../archivos/Edita tu horario directamente..jpg', 'privado', '2024-03-04 07:34:39'),
(28, 8, '5.jpg', '../archivos/5.jpg', 'privado', '2024-04-09 07:34:53'),
(22, 8, 'valentino.JPEG', '../archivos/valentino.JPEG', 'privado', '2024-02-08 07:34:57'),
(23, 8, 'IMG_9593.JPEG', '../archivos/IMG_9593.JPEG', 'privado', '2024-02-09 07:35:03'),
(24, 8, '1.jpeg', '../archivos/1.jpeg', 'privado', '2024-04-30 07:35:09'),
(32, 8, 'Captura de pantalla 2024-02-27 082202.png', '../archivos/Captura de pantalla 2024-02-27 082202.png', 'privado', '2024-03-13 07:35:12'),
(33, 11, 'valentino (2).JPEG', '../archivos/valentino (2).JPEG', 'publico', '2024-04-02 07:46:43'),
(34, 8, 'WhatsApp Image 2024-02-29 at 9.02.42 AM.jpeg', '../archivos/WhatsApp Image 2024-02-29 at 9.02.42 AM.jpeg', 'privado', '0000-00-00 00:00:00'),
(35, 8, 'IMG-3742.JPG', '../archivos/IMG-3742.JPG', 'privado', '0000-00-00 00:00:00'),
(36, 8, '1.jpeg', '../archivos/1.jpeg', 'privado', '2024-04-08 13:56:28'),
(37, 8, 'Captura de pantalla 2024-02-27 074841.png', '../archivos/Captura de pantalla 2024-02-27 074841.png', 'privado', '2024-04-08 13:56:55'),
(38, 8, 'tini.JPEG', '../archivos/tini.JPEG', 'privado', '2024-04-08 13:57:36'),
(39, 11, 'Captura de pantalla 2024-02-27 074743.png', '../archivos/Captura de pantalla 2024-02-27 074743.png', 'publico', '2024-04-08 14:32:57'),
(40, 11, 'Captura de pantalla 2023-10-27 093651.png', '../archivos/Captura de pantalla 2023-10-27 093651.png', 'publico', '2024-04-08 14:33:04'),
(41, 12, 'wallpaperflare.com_wallpaper.jpg', '../archivos/wallpaperflare.com_wallpaper.jpg', 'privado', '2024-04-08 15:14:14'),
(42, 12, 'fdea8b349bd204f911d7a8cc22058f14.jpg', '../archivos/fdea8b349bd204f911d7a8cc22058f14.jpg', 'publico', '2024-04-10 15:26:00'),
(43, 15, '006d7f439286ea2183fbbe7243644846.jpg', '../archivos/006d7f439286ea2183fbbe7243644846.jpg', 'publico', '0000-00-00 00:00:00'),
(45, 16, '006d7f439286ea2183fbbe7243644846.jpg', '../archivos/006d7f439286ea2183fbbe7243644846.jpg', 'publico', '2024-04-10 19:01:52'),
(46, 19, '006d7f439286ea2183fbbe7243644846.jpg', '../archivos/006d7f439286ea2183fbbe7243644846.jpg', 'publico', '2024-04-11 14:14:54'),
(47, 19, 'aaa.jpg', '../archivos/aaa.jpg', 'publico', '2024-04-11 14:15:03'),
(48, 8, 'cinamon.gif', '../archivos/cinamon.gif', 'publico', '2024-04-11 14:18:47'),
(49, 18, 'cinamon.gif', '../archivos/cinamon.gif', 'publico', '2024-04-12 18:38:54'),
(50, 18, '006d7f439286ea2183fbbe7243644846.jpg', '../archivos/006d7f439286ea2183fbbe7243644846.jpg', 'publico', '2024-04-13 05:13:42'),
(51, 18, 'aaa.jpg', '../archivos/aaa.jpg', 'publico', '2024-04-13 05:13:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

DROP TABLE IF EXISTS `comentarios`;
CREATE TABLE IF NOT EXISTS `comentarios` (
  `id_comentario` int NOT NULL AUTO_INCREMENT,
  `id_publicacion` int NOT NULL,
  `Usuario1` varchar(255) NOT NULL,
  `Usuario2` varchar(255) NOT NULL,
  `comentario` text NOT NULL,
  PRIMARY KEY (`id_comentario`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `comentarios`
--

INSERT INTO `comentarios` (`id_comentario`, `id_publicacion`, `Usuario1`, `Usuario2`, `comentario`) VALUES
(1, 47, '15', '', 'golacomo estas'),
(2, 47, '15', '', 'golacomo estas'),
(3, 47, '15', '', 'que onda, bonito perro'),
(4, 46, '15', '', 'bonitos perritos:3'),
(5, 47, '8', '', 'jaja bonitos:3'),
(6, 47, '8', '', 'holaaa'),
(7, 47, '8', '', 'holaaa'),
(8, 46, '8', '', 'ejejejeje'),
(9, 46, '8', '', 'ejejejeje'),
(10, 47, '15', '', 'gege'),
(11, 47, '15', '', 'hola wewewe'),
(12, 46, '15', '', 'aa\r\n\r\n'),
(13, 46, '15', '', 'aa\r\n\r\n'),
(14, 46, '15', '', 'aa\r\n\r\n'),
(15, 47, '15', '', 'fefefe'),
(16, 47, '15', '', 'fefefe'),
(17, 47, '15', '', 'hola'),
(18, 47, '15', '', 'hola'),
(19, 47, '15', '', 'que onda'),
(20, 47, '15', '', 'hola');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `follow`
--

DROP TABLE IF EXISTS `follow`;
CREATE TABLE IF NOT EXISTS `follow` (
  `id_seguimiento` int NOT NULL AUTO_INCREMENT,
  `Usuario1` varchar(255) NOT NULL,
  `Usuario2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `follow` int NOT NULL,
  PRIMARY KEY (`id_seguimiento`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `follow`
--

INSERT INTO `follow` (`id_seguimiento`, `Usuario1`, `Usuario2`, `follow`) VALUES
(15, '15', '19', 1),
(3, '19', '15', 1),
(11, '18', '8', 1),
(12, '8', '19', 1),
(13, '18', '16', 1),
(14, '16', '18', 1),
(16, '18', '15', 1),
(17, '18', '19', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `likes`
--

DROP TABLE IF EXISTS `likes`;
CREATE TABLE IF NOT EXISTS `likes` (
  `id_like` int NOT NULL AUTO_INCREMENT,
  `Usuario1` varchar(255) NOT NULL,
  `Usuario2` varchar(255) NOT NULL,
  `id_publicacion` int NOT NULL,
  `Like` int NOT NULL,
  PRIMARY KEY (`id_like`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `likes`
--

INSERT INTO `likes` (`id_like`, `Usuario1`, `Usuario2`, `id_publicacion`, `Like`) VALUES
(30, '8', '', 46, 1),
(31, '8', '', 47, 1),
(28, '15', '', 47, 1),
(24, '15', '', 46, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `apellidos` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `genero` enum('M','F','X') NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `salt` varchar(255) NOT NULL,
  `es_admin` tinyint(1) NOT NULL DEFAULT '0',
  `correo` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellidos`, `username`, `password`, `genero`, `fecha_nacimiento`, `salt`, `es_admin`, `correo`) VALUES
(10, 'refugio luis', 'hernandez', 'ref@gmail.com', 'ad0524becc2ed120681ae64bc5f3f9f93745122139fbca7dd37a2533a6e804362ec96e8cb68d5f8bf2e16f7e59912749f43e76c1e02ac7283e0fadb70cf662ce', 'X', '2024-04-01', 'e8bf4811b6b8c1d53d9c5c5621d85b2bfb04dc0c94376e98987d9b1772632574', 0, ''),
(11, 'prueba', 'mallo', 'prueba@upv.com', '122b8f83c1ececfdb8f398f3fd4f5325bff4e518c6a3de40d8b10974b99229b8f23b78b87e945c89a0ebecf708c8b14b16cda1320f960f9d3da122abef5488ab', 'M', '2024-01-02', '86af185761a9f33608e22dca69ea8cc9629327eb5c34a57cf5057faae259d71f', 0, ''),
(9, 'Refugio', 'Hernandez Pizaña', 'r@gmail.com', '88381120e60f1c9c59f0f837acc013884628fbfb64fb86b85da73c38539d0a8db6ce7d1b745d6f906344486e7f8eaf24fe8bd7f80df83990e5e9a9559530c7e8', 'M', '2024-04-01', 'a785fc612a69152ab4eb2e53deee231ce2356ceaed8c1fb29b0f547be08d709a', 0, ''),
(8, 'Daniela', 'Mallozzi', 'upv@gmail.com', '5931bebcecbd9ec269d99ed863ae9259d7f6c8234e4f3366736ae3cd13230c3ba6d9f4159d03c481398beb854174590d57302ee4ca3a0d90776443594ab87ef9', 'F', '2015-01-01', '50498c2865c99801f0f11e3516f0bd650db7bf4c0f229caa8e16ca9037243a9f', 0, ''),
(12, 'Alfonso', 'Castillo', 'poncho.pjm.5a@gmail.com', 'f78d2ce8c04bce4913028de2a274a2688165f520307eed4fd9417a42ff2208ca3ed11dcf9aff7e99925da824a9b0c9dd66cb0290fd878f824468f337fb84f922', 'M', '2003-12-01', '6661679e53307613a1286a455f347c8f7208771a724d4a3b2ce1b3b78271bcd8', 0, ''),
(14, 'admin', 'admin', 'admin', '3eaf1f3ae3cfeeb107a7919a16c437e0af1a79072cc1e50919fa7fc43ab7549a860650ca0b510e7e2f3c81bc46bcb7a87758b47152eed3de78c429dbcaa84afa', 'M', '2003-12-01', 'db2af2764c3872624f0ae506c2caedd204185b5866f48c79841f9d0e92114fa3', 1, 'admin@gmail.com'),
(15, '123', '123', '123@123.com', '18626f007b13a68ce1feffcdfe435d81ecba16d04ade3cb2afb4fe014e80a1cf3815dea34c1f0331dcec285b6957f2c7505c5f9ad47d71b0651f2d5a7e15d134', 'M', '2024-04-01', 'd634697539567156ecfd94ff8406091fcfdb5d6b5440170a6a3fab2d2a066338', 0, ''),
(16, 'heri', 'nava', 'heri@upv.com', '18e167892c47daa0fae421eadbd8f93fe6cf613bca0ef83c54e027eb68bc33c63d5b299c576d592b7ca72d9ec10725de49e62e0054e015a5666904a58816bd76', 'M', '2024-04-01', '6068b1228279c553ec985d7979581cd5d782648d31b909b96c44f4751f9133b8', 0, ''),
(17, 'carlos', 'gonzalez', 'Frontdarkk', 'e0f45f15f94b9f47eac6e770662ac32158e36fabee6661e3781691a9c4f1c807c977aebba7f687ef4535596a0a227683e672131344fd2e2f6c56c1f419a4555c', 'M', '2024-04-10', '7033adb8b972318ba68ac583eae63f8998a8b824c0fd7942a748e51a99475814', 0, 'carlos@carlos.com'),
(18, 'Danna', 'Zapata', 'Danazapata03', '9465f27a52220031642326770c046e79c9b433582b15bc467fc58c5c017641a6bc36bf927dccbb8d034b70d37bc5b00791d68ef7487b5c7cf9a077d1f728730d', 'F', '2003-02-14', '9bef16b2d6cd08c6e863f5416cb8406c0c97bf9193e51b9408285d83d031a0ca', 0, 'danna@hotmail.com'),
(19, 'Danna Paola', 'Zapta Cortes', 'danapaola', 'd2865058ec15a1fc6e5fb6062b501e2435d63235ec30bf452ca4d60f2fad523cf34087a9b089cc54a1662d126bec4c05ee4ef160332385650d9d9b2fbe317c58', 'F', '2003-02-14', 'c646aae9d725749c9200f7df9976db1f5afc8a4aaa2e8cdc81dc353561823d34', 0, 'dana@hotmail.comm'),
(20, 'Admin', 'Mallozzi', 'adminmallozzi', 'c27365f8e21c043dd7d710e3838c403573a2aa05519016c13372e6c2edda4cf6bc6ffd778f8ce94e9b83b328e1a471ba58c27ed6d1921fd7b7e533016c1f0ba7', 'M', '2024-04-01', 'eeef53859c5c8f59ef3f9676a30bdb751701b0ed3f97d18c45855f956453c234', 1, 'admin@mallozzi.com');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
