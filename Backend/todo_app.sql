-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-07-2025 a las 18:25:51
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
-- Base de datos: `todo_app`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tareas`
--

CREATE TABLE `tareas` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` enum('pendiente','completada') DEFAULT 'pendiente',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `usuario_id` int(11) DEFAULT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `clima_actual` varchar(100) DEFAULT NULL,
  `frase_motivacional` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tareas`
--

INSERT INTO `tareas` (`id`, `titulo`, `descripcion`, `estado`, `fecha_creacion`, `usuario_id`, `ciudad`, `clima_actual`, `frase_motivacional`) VALUES
(1, 'Realizar vista login', 'Hacer la vista del login en react', 'pendiente', '2025-07-13 16:20:05', 1, 'Cali', 'NA', 'NA'),
(3, 'Subir cosas al GIT', 'Falta realizar el pull de front a GIT', 'pendiente', '2025-07-14 10:16:17', 1, NULL, NULL, '¡Haz lo mejor que puedas hoy!'),
(4, 'Hacer el README', 'Falta realizar la parte del front de este', 'pendiente', '2025-07-14 10:17:33', 1, NULL, NULL, '¡Haz lo mejor que puedas hoy!'),
(5, 'Prueba', 'a', 'pendiente', '2025-07-14 10:41:33', 1, 'Bogota', 'muy nuboso (9.8°C)', '¡Haz lo mejor que puedas hoy!'),
(6, 'Prueba', 'Update', 'pendiente', '2025-07-14 10:42:07', 1, 'Bogota', 'nubes dispersas (9.8°C)', '¡Haz lo mejor que puedas hoy!'),
(11, 'Prueba segundo usuario', 'Prueba 4', 'pendiente', '2025-07-14 16:04:58', 2, 'Bogota', 'muy nuboso (17.8°C)', 'One of the advantages of being disorganized is that one is always having surprising discoveries.'),
(12, 'Prueba segundo usuario', 'Prueba 5', 'pendiente', '2025-07-14 16:05:23', 2, 'Bogota', 'muy nuboso (17.8°C)', 'What people need and what they want may be very different.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `correo`, `contrasena`) VALUES
(1, 'rojaswilliam285@gmail.com', '$2y$10$oLw.U2s1piWYrrtZC5dmfuXtW86cdLnhs8RJ3G9wPMVGiRCdBni7W'),
(2, 'example@email.com', '$2y$10$/iS7Ws6kunMHhMQou11x5u/.rMuL6wEpsed5nJki.eaHuI./w48c.');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tareas`
--
ALTER TABLE `tareas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD CONSTRAINT `tareas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
