-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-08-2024 a las 06:47:56
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
-- Base de datos: `user_sessions`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `permissions`
--

INSERT INTO `permissions` (`id`, `name`) VALUES
(1, 'dashboard'),
(6, 'logOut'),
(2, 'userFoto'),
(3, 'userList'),
(4, 'userNew'),
(5, 'userUpdate');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'admin'),
(2, 'editor'),
(3, 'viewer');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_permissions`
--

CREATE TABLE `role_permissions` (
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `role_permissions`
--

INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(2, 1),
(2, 2),
(2, 5),
(2, 6),
(3, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `login_time` datetime NOT NULL,
  `logout_time` datetime DEFAULT NULL,
  `session_token` varchar(255) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `login_time`, `logout_time`, `session_token`, `is_active`) VALUES
(1, 1, '2024-08-19 08:30:00', '2024-08-20 00:32:59', 'a1b2c3d4e5f6a7b8c9d0e1f2a3b4c5d6e7f8g9h0i1j2k3l4m5n6o7p8', 0),
(2, 2, '2024-08-19 09:00:00', NULL, 'b2c3d4e5f6a7b8c9d0e1f2a3b4c5d6e7f8g9h0i1j2k3l4m5n6o7p8a1', 1),
(4, 1, '2024-08-19 23:54:57', NULL, '4903de4db1d16e362f2cee686362de0b8e178c3d9b45ee452664eb76fc33e233', 0),
(5, 1, '2024-08-20 00:09:23', NULL, '09647bf0b3c652f6972941adb454823c129fa6f860bac5a76cc0c459f4f4db53', 0),
(6, 1, '2024-08-20 00:22:14', NULL, '1882a77a8b2713f7b7542ba50bf23598aa1a37cdec1d3e78da656f4ce1112227', 0),
(7, 1, '2024-08-20 00:23:45', NULL, 'd667d012a7e043fffe5813fc9e3b84eae914b3720938db019fa2d68fafc985c0', 0),
(8, 1, '2024-08-20 00:30:40', NULL, 'db9718564c0da5228920366f47f6dd255958528037848c84f31ddfaf635998b0', 0),
(10, 1, '2024-08-20 00:32:55', NULL, '6c6c339cf2fd776cedbc57038d33ca25a42da1726dfb1d336724f0940021d5af', 0),
(11, 1, '2024-08-20 00:48:54', '2024-08-20 00:49:06', '5e547c804792f1a9cbda1b678fd76c62052beb12af2532460dfbb631884204f4', 0),
(12, 1, '2024-08-20 00:49:57', '2024-08-20 01:23:47', 'd4700203000f1f9fb5ced5480da69aa46bd7e147724cd08541403644a1045a76', 0),
(13, 1, '2024-08-20 07:16:12', '2024-08-20 07:51:23', 'b3a6d94a85192c81be60432bda48dc37fdcda20049ecf9199ec4a0a40b653b25', 0),
(14, 1, '2024-08-20 07:51:58', '2024-08-20 07:52:02', '50f99e5e9d5ff4748ac65a61e0b22dba4d7a1bf5643fd77162462daf70c1e7bf', 0),
(15, 1, '2024-08-20 07:52:19', '2024-08-20 08:07:46', '99a371ccb0a32a2fbb43c61a5c0e5f312c683c0486f5a2643c6eece3bc89e9c6', 0),
(16, 1, '2024-08-20 08:16:22', '2024-08-20 14:20:33', 'c7bc1c85e500dbc1ae3d12cc4ac9ff6889a0ab037de568c39e76de1ac1becd8d', 0),
(17, 1, '2024-08-20 14:20:42', NULL, 'ad548d2b6173fc3e3b7b2f75da3cd70dbe27540b184ffa97d2dae5c9ac60df35', 0),
(18, 1, '2024-08-20 14:48:50', '2024-08-20 15:20:30', '3228dae0a42372204ec5902747610a9ea6f87bf1f7fd995694b992eda42f72ff', 0),
(19, 1, '2024-08-20 15:37:20', '2024-08-20 18:13:24', 'ad8313056be1aebfbd238fe079dce552a5f0569a010b40bc9e4d8964694b0f9c', 0),
(20, 1, '2024-08-20 18:13:44', NULL, '5cabad7e81af2372e978c97dc80ce05522c4ce4d9bddb879012217c5c0cc2b57', 0),
(21, 1, '2024-08-21 14:11:06', '2024-08-21 14:11:22', '0b86abf32fb07359ec0362cc23ab33c3a76dabe8c247c6db0a26af1d468ac0e3', 0),
(22, 1, '2024-08-21 14:11:46', '2024-08-21 14:31:44', '1a7816a6663d3e6ee4138967ed95d539ab75f6fc33d502e07ad7551fb7157288', 0),
(23, 1, '2024-08-21 14:32:02', NULL, '0df87a0e516686c679ee665aa89d9a0a0dcb1ab9d2f2da8e60a4d8f20ff29eb8', 0),
(24, 1, '2024-08-22 11:37:46', '2024-08-22 16:10:57', '64ea15bdb7fc128ed3ed880d284cbbfeabfdbfa20280775442d6f038fc46b517', 0),
(25, 1, '2024-08-22 16:11:15', NULL, 'bc977ee420e1c3621d15d4d87ed98be117fe21701640c7a8ba49db865efef104', 0),
(26, 1, '2024-08-22 23:00:32', '2024-08-22 23:09:32', '61d9a66e024593faf2b6b91a29ac7267bbfad68131e2fa04f63174f26306b700', 0),
(27, 1, '2024-08-22 23:10:07', '2024-08-22 23:30:53', 'ecb944669d7a2dd50d5c8728f0d9aab82ed79da77c56cef850ab15c1b92a4935', 0),
(28, 13, '2024-08-22 23:29:25', NULL, '702e81fe6f63365f62ca2f0e53935361baf460e7812e2b1838e466e8539c16c0', 0),
(29, 13, '2024-08-22 23:32:49', '2024-08-22 23:40:46', '8d278bb66f5c798c64c2277f6f38c876e6411f2d56889f9847dda719ee478e52', 0),
(30, 1, '2024-08-22 23:41:02', '2024-08-22 23:42:36', 'a9c38db983f448caedad92dde20f83b6dbd36914f4e9b2726ad6743c38ea8553', 0),
(31, 13, '2024-08-22 23:42:57', '2024-08-22 23:44:55', '393d67ad6cd6d8db6bf9440d2e5cbc0284291b39251738a6a09eedd5a80f3c70', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `cedula` varchar(10) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `cedula`, `nombre`, `apellido`, `email`, `username`, `password`, `photo`) VALUES
(1, '1234567890', 'Victor', 'Mendoza', 'carlos.mendoza@example.com', 'Victor354', '$2y$10$jbwnT71H7ohiD1Z9hZ7P2.Z4FgJrahisELA6yL.ueKM8mfZvIkNTO', 'Carlos_70.jpg'),
(2, '0987654321', 'María', 'Lópe', 'maria.lopez@example.com', 'mlopez3', '$2y$10$dztPpXouQk/GjqfdZG5AeOZ70D9KN2MzOVZx2Yfuujm9oua9qu7fm', 'uploads/maria_photo.jpg'),
(13, '0952869709', 'VICTOR', 'CAMPOVERDE', 'Mesias354@gmail.com', 'Mesias354', '$2y$10$6.FMIm/Q374alOdWMjPSp.kJy2jiBrWXm/a0Dr/K.VvBl1ZW4DLla', 'VICTOR_84.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_roles`
--

CREATE TABLE `user_roles` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `user_roles`
--

INSERT INTO `user_roles` (`user_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(2, 3),
(13, 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indices de la tabla `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `session_token` (`session_token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indices de la tabla `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
