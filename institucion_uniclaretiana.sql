-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-06-2025 a las 21:39:45
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
-- Base de datos: `institucion_uniclaretiana`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaturas`
--

CREATE TABLE `asignaturas` (
  `id` int(11) NOT NULL,
  `programa_id` int(11) NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `creditos` int(11) NOT NULL,
  `semestre` enum('1','2','3','4','5','6','7','8','9','10') NOT NULL,
  `horas_teoricas` int(11) DEFAULT NULL,
  `horas_practicas` int(11) DEFAULT NULL,
  `tipo_asignatura` enum('obligatoria','electiva','fundamentacion','libre_eleccion') DEFAULT NULL,
  `estado` enum('activa','inactiva') DEFAULT 'activa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos_matriculados`
--

CREATE TABLE `cursos_matriculados` (
  `id` int(11) NOT NULL,
  `matricula_id` int(11) NOT NULL,
  `asignatura_id` int(11) NOT NULL,
  `docente_id` int(11) DEFAULT NULL,
  `grupo` varchar(10) DEFAULT NULL,
  `nota_final` decimal(3,1) DEFAULT NULL,
  `estado` enum('cursando','aprobado','reprobado','cancelado') DEFAULT 'cursando'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentes`
--

CREATE TABLE `docentes` (
  `id` int(11) NOT NULL,
  `persona_id` varchar(20) NOT NULL,
  `codigo_docente` varchar(20) NOT NULL,
  `titulo_maximo` varchar(100) DEFAULT NULL,
  `especialidad` varchar(100) DEFAULT NULL,
  `tipo_contrato` enum('tiempo_completo','medio_tiempo','catedra','visitante') DEFAULT NULL,
  `fecha_vinculacion` date DEFAULT NULL,
  `estado` enum('activo','inactivo','licencia','jubilado') DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entregas_tareas`
--

CREATE TABLE `entregas_tareas` (
  `id` int(11) NOT NULL,
  `tarea_id` int(11) NOT NULL,
  `estudiante_id` int(11) NOT NULL,
  `ruta_archivo` varchar(255) NOT NULL,
  `comentarios` text DEFAULT NULL,
  `fecha_entrega` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `id` int(11) NOT NULL,
  `persona_id` varchar(20) NOT NULL,
  `programa_id` int(11) NOT NULL,
  `codigo_estudiante` varchar(20) NOT NULL,
  `semestre_actual` enum('1','2','3','4','5','6','7','8','9','10') NOT NULL,
  `jornada` enum('mañana','tarde','noche','fin de semana') NOT NULL,
  `modalidad` enum('presencial','virtual','mixta') NOT NULL,
  `fecha_ingreso` date NOT NULL,
  `estado` enum('matriculado','egresado','graduado','retirado','suspendido') DEFAULT 'matriculado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log_actividades`
--

CREATE TABLE `log_actividades` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `accion` varchar(100) NOT NULL,
  `tabla_afectada` varchar(50) DEFAULT NULL,
  `registro_afectado` varchar(50) DEFAULT NULL,
  `fecha_hora` datetime DEFAULT current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL,
  `detalles` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materiales`
--

CREATE TABLE `materiales` (
  `id` int(11) NOT NULL,
  `asignatura_id` int(11) NOT NULL,
  `docente_id` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `ruta_archivo` varchar(255) NOT NULL,
  `fecha_subida` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `matriculas`
--

CREATE TABLE `matriculas` (
  `id` int(11) NOT NULL,
  `estudiante_id` int(11) NOT NULL,
  `programa_id` int(11) NOT NULL,
  `semestre` varchar(10) NOT NULL,
  `fecha_matricula` date NOT NULL,
  `fecha_cierre` date DEFAULT NULL,
  `estado` enum('activa','cancelada','finalizada') DEFAULT 'activa',
  `valor` decimal(12,2) DEFAULT NULL,
  `descuento` decimal(12,2) DEFAULT 0.00,
  `valor_final` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE `personas` (
  `documento_id` varchar(20) NOT NULL,
  `tipo_documento_id` int(11) NOT NULL,
  `nombres` varchar(50) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `genero` enum('M','F','OTRO','NO_ESPECIFICA') NOT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `ciudad` varchar(50) DEFAULT NULL,
  `departamento` varchar(50) DEFAULT NULL,
  `pais` varchar(50) DEFAULT 'Colombia',
  `telefono` varchar(15) DEFAULT NULL,
  `celular` varchar(15) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `estado` enum('activo','inactivo','pendiente') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programas`
--

CREATE TABLE `programas` (
  `id` int(11) NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `duracion_semestres` int(11) DEFAULT NULL,
  `nivel_academico` enum('tecnico','tecnologo','pregrado','especializacion','maestria','doctorado') DEFAULT NULL,
  `facultad` varchar(100) DEFAULT NULL,
  `estado` enum('activo','inactivo','en_desarrollo') DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `programas`
--

INSERT INTO `programas` (`id`, `codigo`, `nombre`, `descripcion`, `duracion_semestres`, `nivel_academico`, `facultad`, `estado`) VALUES
(1, 'IS-001', 'Ingeniería de Sistemas', 'Formación en desarrollo de software y sistemas informáticos', 10, 'pregrado', 'Ingenierías', 'activo'),
(2, 'DER-001', 'Derecho', 'Formación en ciencias jurídicas y legislación', 10, 'pregrado', 'Ciencias Sociales', 'activo'),
(3, 'MED-001', 'Medicina', 'Formación en ciencias médicas y salud humana', 12, 'pregrado', 'Ciencias de la Salud', 'activo'),
(4, 'ADM-001', 'Administración de Empresas', 'Formación en gestión organizacional', 8, 'pregrado', 'Ciencias Económicas', 'activo'),
(5, 'PSI-001', 'Psicología', 'Formación en ciencias del comportamiento humano', 10, 'pregrado', 'Ciencias Sociales', 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `nivel_acceso` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tareas`
--

CREATE TABLE `tareas` (
  `id` int(11) NOT NULL,
  `asignatura_id` int(11) NOT NULL,
  `docente_id` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `fecha_limite` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tareas_estudiantes`
--

CREATE TABLE `tareas_estudiantes` (
  `id` int(11) NOT NULL,
  `tarea_id` int(11) NOT NULL,
  `estudiante_id` int(11) NOT NULL,
  `estado` enum('pendiente','entregada','calificada') DEFAULT 'pendiente',
  `fecha_entrega` datetime DEFAULT NULL,
  `calificacion` decimal(3,1) DEFAULT NULL,
  `comentario_docente` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_documento`
--

CREATE TABLE `tipos_documento` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `persona_id` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `rol_id` int(11) NOT NULL,
  `ultimo_acceso` datetime DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asignaturas`
--
ALTER TABLE `asignaturas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `programa_id` (`programa_id`,`codigo`);

--
-- Indices de la tabla `cursos_matriculados`
--
ALTER TABLE `cursos_matriculados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `matricula_id` (`matricula_id`),
  ADD KEY `asignatura_id` (`asignatura_id`),
  ADD KEY `docente_id` (`docente_id`);

--
-- Indices de la tabla `docentes`
--
ALTER TABLE `docentes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_docente` (`codigo_docente`),
  ADD KEY `persona_id` (`persona_id`);

--
-- Indices de la tabla `entregas_tareas`
--
ALTER TABLE `entregas_tareas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tarea_id` (`tarea_id`),
  ADD KEY `estudiante_id` (`estudiante_id`);

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_estudiante` (`codigo_estudiante`),
  ADD KEY `persona_id` (`persona_id`),
  ADD KEY `programa_id` (`programa_id`);

--
-- Indices de la tabla `log_actividades`
--
ALTER TABLE `log_actividades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `materiales`
--
ALTER TABLE `materiales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `asignatura_id` (`asignatura_id`),
  ADD KEY `docente_id` (`docente_id`);

--
-- Indices de la tabla `matriculas`
--
ALTER TABLE `matriculas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estudiante_id` (`estudiante_id`),
  ADD KEY `programa_id` (`programa_id`);

--
-- Indices de la tabla `personas`
--
ALTER TABLE `personas`
  ADD PRIMARY KEY (`documento_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `tipo_documento_id` (`tipo_documento_id`);

--
-- Indices de la tabla `programas`
--
ALTER TABLE `programas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `asignatura_id` (`asignatura_id`),
  ADD KEY `docente_id` (`docente_id`);

--
-- Indices de la tabla `tareas_estudiantes`
--
ALTER TABLE `tareas_estudiantes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tarea_id` (`tarea_id`,`estudiante_id`),
  ADD KEY `estudiante_id` (`estudiante_id`);

--
-- Indices de la tabla `tipos_documento`
--
ALTER TABLE `tipos_documento`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `persona_id` (`persona_id`),
  ADD KEY `rol_id` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asignaturas`
--
ALTER TABLE `asignaturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cursos_matriculados`
--
ALTER TABLE `cursos_matriculados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `docentes`
--
ALTER TABLE `docentes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `entregas_tareas`
--
ALTER TABLE `entregas_tareas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `log_actividades`
--
ALTER TABLE `log_actividades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `materiales`
--
ALTER TABLE `materiales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `matriculas`
--
ALTER TABLE `matriculas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `programas`
--
ALTER TABLE `programas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tareas`
--
ALTER TABLE `tareas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tareas_estudiantes`
--
ALTER TABLE `tareas_estudiantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipos_documento`
--
ALTER TABLE `tipos_documento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asignaturas`
--
ALTER TABLE `asignaturas`
  ADD CONSTRAINT `asignaturas_ibfk_1` FOREIGN KEY (`programa_id`) REFERENCES `programas` (`id`);

--
-- Filtros para la tabla `cursos_matriculados`
--
ALTER TABLE `cursos_matriculados`
  ADD CONSTRAINT `cursos_matriculados_ibfk_1` FOREIGN KEY (`matricula_id`) REFERENCES `matriculas` (`id`),
  ADD CONSTRAINT `cursos_matriculados_ibfk_2` FOREIGN KEY (`asignatura_id`) REFERENCES `asignaturas` (`id`),
  ADD CONSTRAINT `cursos_matriculados_ibfk_3` FOREIGN KEY (`docente_id`) REFERENCES `docentes` (`id`);

--
-- Filtros para la tabla `docentes`
--
ALTER TABLE `docentes`
  ADD CONSTRAINT `docentes_ibfk_1` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`documento_id`);

--
-- Filtros para la tabla `entregas_tareas`
--
ALTER TABLE `entregas_tareas`
  ADD CONSTRAINT `entregas_tareas_ibfk_1` FOREIGN KEY (`tarea_id`) REFERENCES `tareas` (`id`),
  ADD CONSTRAINT `entregas_tareas_ibfk_2` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`);

--
-- Filtros para la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD CONSTRAINT `estudiantes_ibfk_1` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`documento_id`),
  ADD CONSTRAINT `estudiantes_ibfk_2` FOREIGN KEY (`programa_id`) REFERENCES `programas` (`id`);

--
-- Filtros para la tabla `log_actividades`
--
ALTER TABLE `log_actividades`
  ADD CONSTRAINT `log_actividades_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `materiales`
--
ALTER TABLE `materiales`
  ADD CONSTRAINT `materiales_ibfk_1` FOREIGN KEY (`asignatura_id`) REFERENCES `asignaturas` (`id`),
  ADD CONSTRAINT `materiales_ibfk_2` FOREIGN KEY (`docente_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `matriculas`
--
ALTER TABLE `matriculas`
  ADD CONSTRAINT `matriculas_ibfk_1` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`),
  ADD CONSTRAINT `matriculas_ibfk_2` FOREIGN KEY (`programa_id`) REFERENCES `programas` (`id`);

--
-- Filtros para la tabla `personas`
--
ALTER TABLE `personas`
  ADD CONSTRAINT `personas_ibfk_1` FOREIGN KEY (`tipo_documento_id`) REFERENCES `tipos_documento` (`id`);

--
-- Filtros para la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD CONSTRAINT `tareas_ibfk_1` FOREIGN KEY (`asignatura_id`) REFERENCES `asignaturas` (`id`),
  ADD CONSTRAINT `tareas_ibfk_2` FOREIGN KEY (`docente_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `tareas_estudiantes`
--
ALTER TABLE `tareas_estudiantes`
  ADD CONSTRAINT `tareas_estudiantes_ibfk_1` FOREIGN KEY (`tarea_id`) REFERENCES `tareas` (`id`),
  ADD CONSTRAINT `tareas_estudiantes_ibfk_2` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`documento_id`),
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
