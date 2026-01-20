-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3308
-- Tiempo de generación: 13-01-2026 a las 21:09:24
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ces_reservas`
--
CREATE DATABASE IF NOT EXISTS `ces_reservas` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `ces_reservas`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bookings`
--

CREATE TABLE `bookings` (
  `id` int(10) UNSIGNED NOT NULL,
  `colony_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `shift_id` int(11) NOT NULL,
  `fecha_drop` date NOT NULL,
  `turno_drop` enum('M','T') NOT NULL,
  `fecha_pick` date NOT NULL,
  `turno_pick` enum('M','T') NOT NULL,
  `gatos_count` int(11) NOT NULL,
  `estado` enum('reservado','entregado_vet','listo_recoger','recogido','cancelado') NOT NULL DEFAULT 'reservado',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bookings`
--

INSERT INTO `bookings` (`id`, `colony_id`, `user_id`, `shift_id`, `fecha_drop`, `turno_drop`, `fecha_pick`, `turno_pick`, `gatos_count`, `estado`, `created_at`) VALUES
(1, 3, 4, 2, '2025-11-20', 'T', '2025-11-21', 'M', 2, 'reservado', '2025-11-12 20:47:53'),
(2, 9, 4, 5, '2025-11-18', 'M', '2025-11-18', 'T', 2, 'reservado', '2025-11-16 13:14:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cages`
--

CREATE TABLE `cages` (
  `id` int(11) NOT NULL,
  `clinic_id` int(11) NOT NULL,
  `cage_type_id` int(11) NOT NULL,
  `numero_interno` varchar(11) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cage_loans`
--

CREATE TABLE `cage_loans` (
  `id` int(11) NOT NULL,
  `cage_id` int(11) NOT NULL,
  `from_clinic_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `colony_id` int(11) DEFAULT NULL,
  `fecha_prestamo` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_prevista_devolucion` datetime DEFAULT NULL,
  `fecha_devolucion` datetime DEFAULT NULL,
  `estado` enum('prestado','devuelto') NOT NULL DEFAULT 'prestado',
  `observaciones` varchar(130) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cage_types`
--

CREATE TABLE `cage_types` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cage_types`
--

INSERT INTO `cage_types` (`id`, `nombre`) VALUES
(3, 'Jaula Drop'),
(1, 'Jaula trampa '),
(4, 'Transportín metálico');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `campaigns`
--

CREATE TABLE `campaigns` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `activa` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `campaigns`
--

INSERT INTO `campaigns` (`id`, `nombre`, `fecha_inicio`, `fecha_fin`, `activa`) VALUES
(1, 'Campaña 2025', '2025-11-17', '2025-11-30', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clinics`
--

CREATE TABLE `clinics` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `telefono` varchar(30) NOT NULL,
  `capacidad_ma` int(11) NOT NULL,
  `capacidad_ta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clinics`
--

INSERT INTO `clinics` (`id`, `nombre`, `direccion`, `telefono`, `capacidad_ma`, `capacidad_ta`) VALUES
(1, 'Clínica Vet Norte', 'C/ Mayor 12', '966000111', 8, 8),
(2, 'Clínica Vet Sur', 'Av. Libertad 33', '966000222', 6, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clinic_cages`
--

CREATE TABLE `clinic_cages` (
  `id` int(11) NOT NULL,
  `clinic_id` int(11) NOT NULL,
  `cage_type_id` int(11) NOT NULL,
  `cantidad_total` int(11) NOT NULL DEFAULT 0,
  `cantidad_prestada` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clinic_cages`
--

INSERT INTO `clinic_cages` (`id`, `clinic_id`, `cage_type_id`, `cantidad_total`, `cantidad_prestada`) VALUES
(1, 1, 1, 3, 0),
(2, 1, 3, 1, 0),
(4, 2, 4, 3, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `colonies`
--

CREATE TABLE `colonies` (
  `id` int(11) NOT NULL,
  `code` varchar(30) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `zona` varchar(100) NOT NULL,
  `gestor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `colonies`
--

INSERT INTO `colonies` (`id`, `code`, `nombre`, `zona`, `gestor_id`) VALUES
(3, 'ELC-001', 'San Antón', 'Este', 4),
(4, 'ELC-023', 'Carrús', 'Norte', 5),
(5, 'ELC-003', 'Tráfico', 'Parque Infatil de tráfico', 6),
(6, 'ELC-004', 'Municipal', 'Parque municipal', 7),
(7, 'ELC-005', 'UMH-EOI', 'Escuela de Idiomas', NULL),
(8, 'ELC-006', 'San Crispín', 'San Crispin', NULL),
(9, 'ELC-007', 'Viveros', 'Viveros Parque de Trafuci', NULL),
(10, 'ELC-008', 'Multiaventura', 'Parque Multiaventura', NULL),
(11, 'ELC-010', 'CEU', 'Univerdad CEU Ladera del Rio', NULL),
(12, 'ELC-011', 'Aeropuerto', 'Parking Aeropuerto 1', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shifts`
--

CREATE TABLE `shifts` (
  `id` int(11) NOT NULL,
  `clinic_id` int(11) NOT NULL,
  `campaign_id` int(10) UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `turno` enum('M','T') NOT NULL,
  `capacidad` int(11) NOT NULL,
  `ocupados` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='M: Mañana T: Tarde';

--
-- Volcado de datos para la tabla `shifts`
--

INSERT INTO `shifts` (`id`, `clinic_id`, `campaign_id`, `fecha`, `turno`, `capacidad`, `ocupados`) VALUES
(1, 1, 1, '2025-11-20', 'M', 8, 0),
(2, 1, 1, '2025-11-20', 'T', 8, 0),
(3, 1, 1, '2025-11-21', 'M', 8, 0),
(4, 1, 1, '2025-11-21', 'T', 8, 0),
(5, 2, 1, '2025-11-20', 'M', 6, 0),
(6, 2, 1, '2025-11-20', 'T', 10, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(120) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `rol` enum('admin','voluntario') NOT NULL DEFAULT 'voluntario',
  `telefono` varchar(30) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `colony_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `nombre`, `apellido`, `email`, `pass`, `rol`, `telefono`, `activo`, `colony_id`, `created_at`) VALUES
(2, 'Laura', 'Coordinadora', 'admin@ces.test', '00000', 'admin', '611111111', 1, NULL, '2025-11-12 20:35:41'),
(4, 'Alejandra', 'Voluntaria', 'vol1@ces.test', '0000', 'voluntario', '622222222', 1, 3, '2025-11-12 20:37:28'),
(5, 'Pablo', 'Voluntario', 'vol2@ces.test', '$2y$10$zzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz', 'voluntario', '633333333', 1, 4, '2025-11-12 20:38:05'),
(6, 'Juan', 'Perez', 'juanperez@gmail.com', '10293993', 'voluntario', '666666666', 1, 4, '2025-11-16 13:06:22'),
(7, 'Suni', 'Gonzalez', 'suni11@gmail.com', '120390aaa2', 'voluntario', '666666666', 1, 7, '2025-11-16 13:12:05'),
(9, 'Alejandro', 'Quivera', 'a.quivera1991@gmail.com', '$2y$10$NmFpRhtcVMleLX3hvdu9dO24AgeTJx3YtUMJ07gxMEz5mU/DX7APy', 'admin', '636910718', 1, NULL, '2025-12-21 16:37:29');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `colony_id` (`colony_id`,`user_id`,`shift_id`),
  ADD KEY `id` (`id`),
  ADD KEY `fk_bookings_user` (`user_id`),
  ADD KEY `fk_bookings_shift` (`shift_id`);

--
-- Indices de la tabla `cages`
--
ALTER TABLE `cages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clinic_id` (`clinic_id`,`numero_interno`),
  ADD KEY `id` (`id`,`clinic_id`,`cage_type_id`),
  ADD KEY `cage_type_id` (`cage_type_id`);

--
-- Indices de la tabla `cage_loans`
--
ALTER TABLE `cage_loans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`,`cage_id`,`from_clinic_id`,`user_id`,`colony_id`),
  ADD KEY `cage_id` (`cage_id`),
  ADD KEY `from_clinic_id` (`from_clinic_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `colony_id` (`colony_id`);

--
-- Indices de la tabla `cage_types`
--
ALTER TABLE `cage_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD KEY `id` (`id`);

--
-- Indices de la tabla `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indices de la tabla `clinics`
--
ALTER TABLE `clinics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indices de la tabla `clinic_cages`
--
ALTER TABLE `clinic_cages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clinic_id` (`clinic_id`),
  ADD KEY `cage_type_id` (`cage_type_id`);

--
-- Indices de la tabla `colonies`
--
ALTER TABLE `colonies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `gestor_id` (`gestor_id`),
  ADD KEY `id` (`id`);

--
-- Indices de la tabla `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clinic_id_2` (`clinic_id`,`campaign_id`,`fecha`,`turno`),
  ADD KEY `id` (`id`),
  ADD KEY `clinic_id` (`clinic_id`),
  ADD KEY `fecha` (`fecha`),
  ADD KEY `fk_shifts_campaigns` (`campaign_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `colony_id` (`colony_id`),
  ADD KEY `id` (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `cages`
--
ALTER TABLE `cages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `cage_loans`
--
ALTER TABLE `cage_loans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `cage_types`
--
ALTER TABLE `cage_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `clinics`
--
ALTER TABLE `clinics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `clinic_cages`
--
ALTER TABLE `clinic_cages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `colonies`
--
ALTER TABLE `colonies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `shifts`
--
ALTER TABLE `shifts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `fk_bookings_colony` FOREIGN KEY (`colony_id`) REFERENCES `colonies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bookings_shift` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bookings_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `cages`
--
ALTER TABLE `cages`
  ADD CONSTRAINT `cages_ibfk_1` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `cages_ibfk_2` FOREIGN KEY (`cage_type_id`) REFERENCES `cage_types` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `cage_loans`
--
ALTER TABLE `cage_loans`
  ADD CONSTRAINT `cage_loans_ibfk_1` FOREIGN KEY (`cage_id`) REFERENCES `cages` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `cage_loans_ibfk_2` FOREIGN KEY (`from_clinic_id`) REFERENCES `clinics` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `cage_loans_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `cage_loans_ibfk_4` FOREIGN KEY (`colony_id`) REFERENCES `colonies` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `clinic_cages`
--
ALTER TABLE `clinic_cages`
  ADD CONSTRAINT `clinic_cages_ibfk_1` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `clinic_cages_ibfk_2` FOREIGN KEY (`cage_type_id`) REFERENCES `cage_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `colonies`
--
ALTER TABLE `colonies`
  ADD CONSTRAINT `fk_colonies_gestor` FOREIGN KEY (`gestor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `shifts`
--
ALTER TABLE `shifts`
  ADD CONSTRAINT `fk_shifts_campaigns` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_shifts_clinic` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_colony` FOREIGN KEY (`colony_id`) REFERENCES `colonies` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
