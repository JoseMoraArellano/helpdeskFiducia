-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-03-2025 a las 23:04:11
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
-- Base de datos: `helpdesk`
--

DELIMITER $$
--
-- Funciones
--
CREATE DEFINER=`root`@`localhost` FUNCTION `CalcularHorasLaborales` (`fecha_inicio` DATETIME, `fecha_fin` DATETIME) RETURNS DECIMAL(10,2)  BEGIN
    DECLARE dias_laborales INT;
    DECLARE horas_totales DECIMAL(10,2);
    DECLARE fecha_actual DATETIME;
    DECLARE dia_semana INT;
    
    -- Si alguna fecha es NULL, devolvemos NULL
    IF fecha_inicio IS NULL OR fecha_fin IS NULL THEN
        RETURN NULL;
    END IF;
    
    -- Si la fecha de fin es anterior a la de inicio, devolvemos 0
    IF fecha_fin < fecha_inicio THEN
        RETURN 0;
    END IF;
    
    SET horas_totales = 0;
    SET fecha_actual = fecha_inicio;
    
    -- Iteramos por cada día entre las fechas
    WHILE DATE(fecha_actual) <= DATE(fecha_fin) DO
        SET dia_semana = DAYOFWEEK(fecha_actual); -- 1 = Domingo, 7 = Sábado
        
        -- Solo contamos días laborales (Lunes a Viernes)
        IF dia_semana BETWEEN 2 AND 6 THEN
            -- Para el primer día
            IF DATE(fecha_actual) = DATE(fecha_inicio) THEN
                -- Si la hora de inicio es después de las 18, no sumamos horas
                IF HOUR(fecha_inicio) >= 18 THEN
                    SET horas_totales = horas_totales + 0;
                -- Si la hora de inicio es antes de las 9, contamos desde las 9
                ELSEIF HOUR(fecha_inicio) < 9 THEN
                    -- Si es el mismo día que el fin
                    IF DATE(fecha_actual) = DATE(fecha_fin) THEN
                        -- Si la hora de fin es antes de las 9, no hay horas
                        IF HOUR(fecha_fin) < 9 THEN
                            SET horas_totales = horas_totales + 0;
                        -- Si la hora de fin es después de las 18, contamos hasta las 18
                        ELSEIF HOUR(fecha_fin) >= 18 THEN
                            SET horas_totales = horas_totales + 9; -- 9 horas laborales completas
                        ELSE
                            SET horas_totales = horas_totales + (HOUR(fecha_fin) + MINUTE(fecha_fin)/60) - 9;
                        END IF;
                    ELSE
                        SET horas_totales = horas_totales + 9; -- 9 horas laborales completas
                    END IF;
                -- Si la hora está entre las 9 y las 18
                ELSE
                    -- Si es el mismo día que el fin
                    IF DATE(fecha_actual) = DATE(fecha_fin) THEN
                        -- Si la hora de fin es antes de la de inicio, no hay horas
                        IF HOUR(fecha_fin) < HOUR(fecha_inicio) THEN
                            SET horas_totales = horas_totales + 0;
                        -- Si la hora de fin es después de las 18, contamos hasta las 18
                        ELSEIF HOUR(fecha_fin) >= 18 THEN
                            SET horas_totales = horas_totales + (18 - (HOUR(fecha_inicio) + MINUTE(fecha_inicio)/60));
                        ELSE
                            SET horas_totales = horas_totales + ((HOUR(fecha_fin) + MINUTE(fecha_fin)/60) - (HOUR(fecha_inicio) + MINUTE(fecha_inicio)/60));
                        END IF;
                    ELSE
                        SET horas_totales = horas_totales + (18 - (HOUR(fecha_inicio) + MINUTE(fecha_inicio)/60));
                    END IF;
                END IF;
            -- Para el último día
            ELSEIF DATE(fecha_actual) = DATE(fecha_fin) THEN
                -- Si la hora de fin es antes de las 9, no sumamos horas
                IF HOUR(fecha_fin) < 9 THEN
                    SET horas_totales = horas_totales + 0;
                -- Si la hora de fin es después de las 18, contamos hasta las 18
                ELSEIF HOUR(fecha_fin) >= 18 THEN
                    SET horas_totales = horas_totales + 9; -- 9 horas laborales completas
                ELSE
                    SET horas_totales = horas_totales + ((HOUR(fecha_fin) + MINUTE(fecha_fin)/60) - 9);
                END IF;
            -- Para días intermedios, sumamos 9 horas completas
            ELSE
                SET horas_totales = horas_totales + 9; -- 9 horas laborales completas
            END IF;
        END IF;
        
        -- Avanzamos al siguiente día
        SET fecha_actual = DATE_ADD(fecha_actual, INTERVAL 1 DAY);
    END WHILE;
    
    RETURN horas_totales;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `calcular_tiempo_laboral` (`fecha_inicio` DATETIME, `fecha_cierre` DATETIME) RETURNS INT(11) DETERMINISTIC BEGIN
    DECLARE total_horas INT DEFAULT 0;
    DECLARE fecha_actual DATETIME;
    
    SET fecha_actual = fecha_inicio;
    
    WHILE fecha_actual < fecha_cierre DO
        -- Si es día laboral (Lunes a Viernes)
        IF WEEKDAY(fecha_actual) < 5 THEN
            -- Si está dentro del horario de oficina (09:00 - 18:00)
            IF TIME(fecha_actual) < '18:00:00' THEN
                SET total_horas = total_horas + 1;
            END IF;
        END IF;
        
        -- Avanzar una hora
        SET fecha_actual = DATE_ADD(fecha_actual, INTERVAL 1 HOUR);
    END WHILE;
    
    RETURN total_horas;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_acceso`
--

CREATE TABLE `t_acceso` (
  `id_acces` int(9) NOT NULL,
  `user` int(11) NOT NULL,
  `Fecha_Error` date NOT NULL,
  `Intent_Fallid` int(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='control de accesos';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_archivos_reportes`
--

CREATE TABLE `t_archivos_reportes` (
  `id_archivo` int(11) NOT NULL,
  `id_reporte` int(11) NOT NULL,
  `nombre_original` varchar(255) NOT NULL,
  `nombre_sistema` varchar(255) NOT NULL,
  `ruta` varchar(255) NOT NULL,
  `extension` varchar(10) NOT NULL,
  `tipo_mime` varchar(100) NOT NULL,
  `tamano` int(11) NOT NULL,
  `fecha_subida` date DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `t_archivos_reportes`
--

INSERT INTO `t_archivos_reportes` (`id_archivo`, `id_reporte`, `nombre_original`, `nombre_sistema`, `ruta`, `extension`, `tipo_mime`, `tamano`, `fecha_subida`) VALUES
(1, 1, 'IN2412-0219-WhatsApp Image 2024-12-05 at 14.56.41.jpeg', 'file_67be01f1ebd15.jpeg', '../../archivos/reportes/1/file_67be01f1ebd15.jpeg', 'jpeg', 'image/jpeg', 21518, '2025-02-25'),
(3, 1, 'C.V Nubia Ortiz.docx', 'file_67be0375cec2d.docx', '../../archivos/reportes/1/file_67be0375cec2d.docx', 'docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 55685, '2025-02-25'),
(4, 8, 'Salida.pdf', 'file_67be1f8fbdfda.pdf', '../../archivos/reportes/8/file_67be1f8fbdfda.pdf', 'pdf', 'application/pdf', 245021, '2025-02-25'),
(5, 10, 'Help-Desk (1).xlsx', 'file_67c11d0bf0755.xlsx', '../../archivos/reportes/10/file_67c11d0bf0755.xlsx', 'xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 17319, '2025-02-27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_area`
--

CREATE TABLE `t_area` (
  `idrarea` int(11) NOT NULL,
  `Nomb_area` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `t_area`
--

INSERT INTO `t_area` (`idrarea`, `Nomb_area`) VALUES
(1, 'Direccion General Presidenci'),
(2, 'Administracion y Fianzas'),
(3, 'Comercial'),
(4, 'Juridico'),
(5, 'Normatividad'),
(6, 'Fiduciaria'),
(7, 'Sistemas'),
(8, 'Otra');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_asignacion`
--

CREATE TABLE `t_asignacion` (
  `id_asignacion` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `id_equipo` int(11) NOT NULL,
  `marca` varchar(245) DEFAULT NULL,
  `modelo` varchar(245) DEFAULT NULL,
  `color` varchar(245) DEFAULT NULL,
  `descripcion` varchar(245) DEFAULT NULL,
  `memoria` varchar(245) DEFAULT NULL,
  `disco_duro` varchar(245) DEFAULT NULL,
  `procesador` varchar(245) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `t_asignacion`
--

INSERT INTO `t_asignacion` (`id_asignacion`, `id_persona`, `id_equipo`, `marca`, `modelo`, `color`, `descripcion`, `memoria`, `disco_duro`, `procesador`) VALUES
(9, 1, 2, '', '', '', '', '', '', ''),
(15, 17, 10, '', '', '', '', '', '', ''),
(16, 16, 6, 'Excel', 'excel', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_cat_equipo`
--

CREATE TABLE `t_cat_equipo` (
  `id_equipo` int(11) NOT NULL,
  `nombre` varchar(245) NOT NULL,
  `descripcion` varchar(245) DEFAULT NULL,
  `Categ_SH` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `t_cat_equipo`
--

INSERT INTO `t_cat_equipo` (`id_equipo`, `nombre`, `descripcion`, `Categ_SH`) VALUES
(1, 'PC', '', 'Hardware'),
(2, 'Laptop', '', 'Hardware'),
(3, 'Mouse', '', 'Hardware'),
(4, 'Teclado', '', 'Hardware'),
(5, 'Monitor', '', 'Hardware'),
(6, 'Paqueteria office', '', 'Software'),
(7, 'Camara', '', 'Hardware'),
(8, 'Licencias', 'Licencias de programas', 'Software'),
(9, 'OTRO', 'OTROS', 'OTROS'),
(10, '@Fi', 'Afi2', 'Software'),
(14, 'Cel M16', 'Telefono Celular Motorola M16', 'Hardware'),
(15, 'Office', 'Paqueteria Office', 'Software');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_cat_roles`
--

CREATE TABLE `t_cat_roles` (
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(245) NOT NULL,
  `descripcion` varchar(245) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `t_cat_roles`
--

INSERT INTO `t_cat_roles` (`id_rol`, `nombre`, `descripcion`) VALUES
(1, 'cliente', 'Usuario Standar'),
(2, 'admin', 'Super admin'),
(3, 'Tecnico', 'Tecnico Nivel 1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_oficina`
--

CREATE TABLE `t_oficina` (
  `id_ofici` int(4) NOT NULL,
  `Nomb_oficina` varchar(245) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_persona`
--

CREATE TABLE `t_persona` (
  `id_persona` int(11) NOT NULL,
  `paterno` varchar(100) NOT NULL,
  `materno` varchar(100) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `sexo` varchar(2) DEFAULT NULL,
  `telefono` varchar(45) DEFAULT NULL,
  `correo` varchar(100) NOT NULL,
  `fechaInsert` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_modif` date DEFAULT NULL,
  `user_edita` int(11) DEFAULT NULL,
  `token_recuperacion` varchar(100) DEFAULT NULL,
  `token_expira` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `t_persona`
--

INSERT INTO `t_persona` (`id_persona`, `paterno`, `materno`, `nombre`, `fecha_nacimiento`, `sexo`, `telefono`, `correo`, `fechaInsert`, `fecha_modif`, `user_edita`, `token_recuperacion`, `token_expira`) VALUES
(1, 'Mora', 'Arellano', 'Jose', '1989-01-19', 'M', '554838076', 'jmora@fianzasfiducia.com', '2021-08-09 14:18:27', '0000-00-00', NULL, 'cb610e91beabe44dc9793337419fb260b55c3b5f7c26c8126b9a062bf8c064d7', '2025-02-26 17:47:45'),
(5, 'mora', 'rellano', 'jose', '2024-11-19', 'O', '5548380767', 'j-mora-a@outlook.com', '2024-11-19 09:53:00', NULL, NULL, NULL, NULL),
(6, 'jose', 'mora', 'arellano', '2024-11-19', 'O', '5548380767', 'josejose', '2024-11-19 09:54:06', NULL, NULL, NULL, NULL),
(7, 'xxx', 'xxx', 'xxx', '2024-11-19', 'F', '1111111111', 'prueba@hotmail.com', '2024-11-19 21:05:27', NULL, NULL, NULL, NULL),
(8, 'xxx', 'xxx', 'xxx', '0000-00-00', 'F', '1111111111', 'prueba@hotmail.com', '2024-11-19 21:05:41', NULL, NULL, NULL, NULL),
(9, 'xxx', 'xxx', 'xxx', '0000-00-00', 'F', '1111111111', 'prueba@hotmail.com', '2024-11-19 21:05:52', NULL, NULL, NULL, NULL),
(10, 'xxx', 'xxx', 'xxx', '0000-00-00', 'F', '1111111111', 'prueba@hotmail.com', '2024-11-19 21:06:01', NULL, NULL, NULL, NULL),
(11, 'xxx', 'xxx', 'xxx', '0000-00-00', 'F', '1111111111', 'prueba@hotmail.com', '2024-11-19 21:06:10', NULL, NULL, NULL, NULL),
(12, 'xxx', 'xxx', 'xxx', '0000-00-00', 'F', '1111111111', 'prueba@hotmail.com', '2024-11-19 21:07:33', NULL, NULL, NULL, NULL),
(13, 'xxx', 'xxx', 'xxx', '0000-00-00', 'F', '1111111111', 'prueba@hotmail.com', '2024-11-19 21:07:43', NULL, NULL, NULL, NULL),
(14, 'arellano', 'morales', 'jose', '2024-01-31', 'M', '5548380769', 'josemoraarellano@gmail.com', '2024-11-19 21:10:34', NULL, NULL, NULL, NULL),
(15, 'zzzzzz', 'xxx', 'xxx', '0000-00-00', 'F', '1111111111', 'prueba@hotmail.com', '2024-11-19 21:14:50', NULL, NULL, NULL, NULL),
(16, 'Fuenleal', 'Ortiz', 'Joshua', '2025-01-01', 'M', '123456789', 'soporte@gcabcompany.com', '2025-01-07 12:49:13', NULL, NULL, NULL, NULL),
(17, 'TesP', 'TesM', 'TesN', '0000-00-00', 'F', '1234567890', 'correo@correo2.com', '2025-02-14 12:51:46', NULL, NULL, NULL, NULL),
(18, 'Paterno', 'Materno', 'Nombre', '2025-02-15', 'O', '5555555555', 'j-mora-a@outlook.com', '2025-02-15 19:59:55', NULL, NULL, NULL, NULL),
(19, 'Mendoza', 'Mimbrera', 'Daniel', '0000-00-00', 'M', '5554818210', 'dmendoza@fianzasfiducia.com', '2025-02-27 12:12:04', NULL, NULL, NULL, NULL),
(20, 'Tierno', 'Huerta', 'Erick', '0000-00-00', 'M', '5554818210', 'etierno@fianzasfiducia.com', '2025-02-27 12:52:54', NULL, NULL, NULL, NULL),
(21, 'Paterno', 'Materno', 'Nombre', '2025-03-10', 'M', '5647168207', 'correo@correo2.com', '2025-03-10 16:40:32', NULL, NULL, NULL, NULL),
(22, 'Paterno', 'Materno', 'Nombre', '2025-03-10', 'M', '5647168207', 'correo@correo2.com', '2025-03-10 16:42:54', NULL, NULL, NULL, NULL),
(23, 'Paterno', 'Materno', 'Nombre', '2025-03-10', 'M', '5647168207', 'correo@correo2.com', '2025-03-10 16:58:20', NULL, NULL, NULL, NULL),
(24, 'Arguello', 'Ambrocio', 'Alejandra', '1992-10-14', 'F', '5555555555', 'prueba@correo.com.mx', '2025-03-11 13:50:54', NULL, NULL, NULL, NULL),
(25, 'Arguello', 'Ambrocio', 'Alejandra', '1992-10-14', 'F', '5555555555', 'prueba@correo.com.mx', '2025-03-11 13:53:45', NULL, NULL, NULL, NULL),
(26, 'Arguello', 'Ambrocio', 'Alejandra', '1992-10-14', 'F', '5555555555', 'prueba@correo.com.mx', '2025-03-11 13:53:49', NULL, NULL, NULL, NULL),
(27, 'Arguello', 'Ambrocio', 'Alejandra', '1992-10-14', 'F', '5555555555', 'prueba@correo.com.mx', '2025-03-11 14:49:57', NULL, NULL, NULL, NULL),
(28, 'Arguello', 'Ambrocio', 'Alejandra', '1992-10-14', 'F', '5555555555', 'prueba@correo.com.mx', '2025-03-11 14:50:49', NULL, NULL, NULL, NULL),
(29, 'Arguello', 'Ambrocio', 'Alejandra', '1992-10-14', 'F', '5555555555', 'prueba@correo.com.mx', '2025-03-11 14:51:43', NULL, NULL, NULL, NULL),
(30, 'Arguello', 'Ambrocio', 'Alejandra', '1992-10-14', 'F', '5555555555', 'prueba@correo.com.mx', '2025-03-11 14:52:23', NULL, NULL, NULL, NULL),
(31, 'Arguello', 'Ambrocio', 'Alejandra', '1992-10-14', 'F', '5555555555', 'prueba@correo.com.mx', '2025-03-11 14:53:40', NULL, NULL, NULL, NULL),
(32, 'Arguello', 'Ambrocio', 'Alejandra', '1992-10-14', 'F', '5555555555', 'prueba@correo.com.mx', '2025-03-11 14:55:55', NULL, NULL, NULL, NULL),
(33, 'Arguello', 'Ambrocio', 'Alejandra', '1992-10-14', 'F', '5555555555', 'prueba@correo.com.mx', '2025-03-11 14:57:07', NULL, NULL, NULL, NULL),
(34, 'Arguello', 'Ambrocio', 'Alejandra', '1992-10-14', 'F', '5555555555', 'prueba@correo.com.mx', '2025-03-11 14:57:14', NULL, NULL, NULL, NULL),
(35, 'Arguello', 'Ambrocio', 'Alejandra', '1992-10-14', 'F', '5555555555', 'prueba@correo.com.mx', '2025-03-11 14:57:25', NULL, NULL, NULL, NULL),
(36, 'Arguello', 'Ambrocio', 'Alejandra', '1992-10-14', 'F', '5555555555', 'prueba@correo.com.mx', '2025-03-11 14:59:32', NULL, NULL, NULL, NULL),
(37, 'Arguello', 'Ambrocio', 'Alejandra', '1992-10-14', 'F', '5555555555', 'prueba@correo.com.mx', '2025-03-11 15:01:41', NULL, NULL, NULL, NULL),
(38, 'Arguello', 'Ambrocio', 'Alejandra', '1992-10-14', 'F', '5555555555', 'prueba@correo.com.mx', '2025-03-11 15:02:28', NULL, NULL, NULL, NULL),
(39, 'Arguello', 'Ambrocio', 'Alejandra', '1992-10-14', 'F', '5555555555', 'prueba@correo.com.mx', '2025-03-11 15:02:41', NULL, NULL, NULL, NULL),
(40, 'Arguello', 'Ambrocio', 'Alejandra', '1992-10-14', 'F', '5555555555', 'prueba@correo.com.mx', '2025-03-11 15:04:57', NULL, NULL, NULL, NULL),
(41, 'Arguello', 'Ambrocio', 'Alejandra', '1992-10-14', 'F', '5555555555', 'prueba@correo.com.mx', '2025-03-11 15:17:21', NULL, NULL, NULL, NULL),
(42, 'Arguello', 'Ambrocio', 'Alejandra', '1992-10-14', 'F', '5555555555', 'prueba@correo.com.mx', '2025-03-11 15:21:48', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_reportes`
--

CREATE TABLE `t_reportes` (
  `id_reporte` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_equipo` int(11) NOT NULL,
  `id_usuario_tecnico` int(11) DEFAULT NULL,
  `descripcion_problema` text NOT NULL,
  `solucion_problema` text DEFAULT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `fechaRApert` datetime DEFAULT NULL COMMENT 'fecha de re apertura',
  `fechaCierre` datetime DEFAULT NULL COMMENT 'Fecha cierre',
  `fechaAct` datetime DEFAULT NULL COMMENT 'Fecha Actual'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `t_reportes`
--

INSERT INTO `t_reportes` (`id_reporte`, `id_usuario`, `id_equipo`, `id_usuario_tecnico`, `descripcion_problema`, `solucion_problema`, `estatus`, `fecha`, `fechaRApert`, `fechaCierre`, `fechaAct`) VALUES
(1, 17, 2, 2, 'no enciende', 'Apertura por el usuario', 0, '2024-11-19 15:56:22', '0000-00-00 00:00:00', '2024-11-20 09:56:22', '0000-00-00 00:00:00'),
(2, 2, 10, 1, 'problema en la facturaciÃ³n ', 'Solucionado tes', 0, '2024-10-19 21:14:54', '0000-00-00 00:00:00', '2024-10-20 21:14:54', '0000-00-00 00:00:00'),
(7, 17, 10, 19, 'Prueba 3', NULL, 0, '2025-02-25 11:52:53', '2025-03-03 18:46:55', '2025-02-26 11:52:53', '0000-00-00 00:00:00'),
(8, 17, 10, 1, 'Convertir a PDF', 'Solucion 1', 0, '2025-02-25 13:52:47', '2025-02-26 14:52:47', '2025-02-26 13:52:47', '0000-00-00 00:00:00'),
(9, 17, 10, 1, 'Prueba', 'bbb', 0, '2025-02-26 18:04:49', '0000-00-00 00:00:00', '2025-02-26 18:04:49', '0000-00-00 00:00:00'),
(10, 2, 6, NULL, 'Tengo un problema con mi excel ayuda con una formula', NULL, 0, '2025-02-27 20:18:51', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 2, 6, NULL, 'Se trabo excel', NULL, 1, '2025-02-28 09:34:37', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 2, 6, NULL, 'Prueba FechaACt', NULL, 0, '2025-02-28 09:40:23', '0000-00-00 00:00:00', '2025-03-04 09:40:23', '0000-00-00 00:00:00'),
(14, 2, 6, 20, 'prueba 4', 'Se re instalra paqueteria office', 2, '2025-02-27 10:55:52', '2025-03-03 18:46:55', '2025-03-01 13:00:00', '2025-03-12 18:14:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_usuarios`
--

CREATE TABLE `t_usuarios` (
  `id_usuario` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `usuario` varchar(245) NOT NULL,
  `password` varchar(245) NOT NULL,
  `ubicacion` text DEFAULT NULL,
  `activo` int(11) NOT NULL DEFAULT 1,
  `fecha_insert` varchar(45) DEFAULT NULL,
  `ult_acce` date DEFAULT NULL,
  `id_ofici` int(4) NOT NULL,
  `accfall` int(11) NOT NULL,
  `id_area` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `t_usuarios`
--

INSERT INTO `t_usuarios` (`id_usuario`, `id_rol`, `id_persona`, `usuario`, `password`, `ubicacion`, `activo`, `fecha_insert`, `ult_acce`, `id_ofici`, `accfall`, `id_area`) VALUES
(1, 2, 1, 'Admin', '0d2ffdcf26690474900d42cd074549a157c55dd8', 'Modulo 2', 1, NULL, '2025-03-13', 1, 0, NULL),
(2, 1, 16, 'Tecnico1', '333691e27b99ad6afcfd6d10fd3bd8e0ef813567', 'Modulo 1', 1, NULL, NULL, 6, 0, NULL),
(17, 1, 17, 'User1', '333691e27b99ad6afcfd6d10fd3bd8e0ef813567', '', 1, NULL, '2025-03-11', 5, 0, NULL),
(19, 3, 19, 'Daniel', '333691e27b99ad6afcfd6d10fd3bd8e0ef813567', 'plaza Artz', 1, NULL, '2025-03-11', 4, 0, NULL),
(20, 3, 20, 'Etierno', '333691e27b99ad6afcfd6d10fd3bd8e0ef813567', '', 1, NULL, NULL, 3, 0, NULL),
(23, 1, 23, 'User2', '333691e27b99ad6afcfd6d10fd3bd8e0ef813567', 'comentario', 1, '2025-03-10 16:58:20', NULL, 2, 0, NULL),
(24, 1, 42, 'cromero', '333691e27b99ad6afcfd6d10fd3bd8e0ef813567', 'Abogado principal', 1, '2025-03-11 15:21:48', '2025-03-11', 1, 0, 4);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `t_acceso`
--
ALTER TABLE `t_acceso`
  ADD PRIMARY KEY (`id_acces`),
  ADD KEY `fkuser_idx` (`user`) USING BTREE;

--
-- Indices de la tabla `t_archivos_reportes`
--
ALTER TABLE `t_archivos_reportes`
  ADD PRIMARY KEY (`id_archivo`),
  ADD KEY `id_reporte` (`id_reporte`);

--
-- Indices de la tabla `t_area`
--
ALTER TABLE `t_area`
  ADD PRIMARY KEY (`idrarea`) USING BTREE;

--
-- Indices de la tabla `t_asignacion`
--
ALTER TABLE `t_asignacion`
  ADD PRIMARY KEY (`id_asignacion`),
  ADD KEY `fkPersona_idx` (`id_persona`),
  ADD KEY `fkPersonaAsignacion_idx` (`id_persona`),
  ADD KEY `fkequipoAsignacion_idx` (`id_equipo`);

--
-- Indices de la tabla `t_cat_equipo`
--
ALTER TABLE `t_cat_equipo`
  ADD PRIMARY KEY (`id_equipo`);

--
-- Indices de la tabla `t_cat_roles`
--
ALTER TABLE `t_cat_roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `t_oficina`
--
ALTER TABLE `t_oficina`
  ADD PRIMARY KEY (`id_ofici`);

--
-- Indices de la tabla `t_persona`
--
ALTER TABLE `t_persona`
  ADD PRIMARY KEY (`id_persona`),
  ADD KEY `fk_user_edita` (`user_edita`);

--
-- Indices de la tabla `t_reportes`
--
ALTER TABLE `t_reportes`
  ADD PRIMARY KEY (`id_reporte`),
  ADD KEY `fkUsuarioReporte_idx` (`id_usuario`),
  ADD KEY `fkEquipoReporte_idx` (`id_equipo`);

--
-- Indices de la tabla `t_usuarios`
--
ALTER TABLE `t_usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `fkPersona_idx` (`id_persona`),
  ADD KEY `fkRoles_idx` (`id_rol`),
  ADD KEY `fkid_ofici_idx` (`id_ofici`) USING BTREE,
  ADD KEY `fk_t_usuarios_area` (`id_area`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `t_acceso`
--
ALTER TABLE `t_acceso`
  MODIFY `id_acces` int(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `t_archivos_reportes`
--
ALTER TABLE `t_archivos_reportes`
  MODIFY `id_archivo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `t_area`
--
ALTER TABLE `t_area`
  MODIFY `idrarea` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `t_asignacion`
--
ALTER TABLE `t_asignacion`
  MODIFY `id_asignacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `t_cat_equipo`
--
ALTER TABLE `t_cat_equipo`
  MODIFY `id_equipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `t_cat_roles`
--
ALTER TABLE `t_cat_roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `t_oficina`
--
ALTER TABLE `t_oficina`
  MODIFY `id_ofici` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `t_persona`
--
ALTER TABLE `t_persona`
  MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de la tabla `t_reportes`
--
ALTER TABLE `t_reportes`
  MODIFY `id_reporte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `t_usuarios`
--
ALTER TABLE `t_usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `t_acceso`
--
ALTER TABLE `t_acceso`
  ADD CONSTRAINT `fkUser` FOREIGN KEY (`user`) REFERENCES `t_usuarios` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `t_archivos_reportes`
--
ALTER TABLE `t_archivos_reportes`
  ADD CONSTRAINT `t_archivos_reportes_ibfk_1` FOREIGN KEY (`id_reporte`) REFERENCES `t_reportes` (`id_reporte`) ON DELETE CASCADE;

--
-- Filtros para la tabla `t_asignacion`
--
ALTER TABLE `t_asignacion`
  ADD CONSTRAINT `fkPersonaAsignacion` FOREIGN KEY (`id_persona`) REFERENCES `t_persona` (`id_persona`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fkequipoAsignacion` FOREIGN KEY (`id_equipo`) REFERENCES `t_cat_equipo` (`id_equipo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `t_oficina`
--
ALTER TABLE `t_oficina`
  ADD CONSTRAINT `fkid_ofici` FOREIGN KEY (`id_ofici`) REFERENCES `t_usuarios` (`id_ofici`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `t_persona`
--
ALTER TABLE `t_persona`
  ADD CONSTRAINT `fk_user_edita` FOREIGN KEY (`user_edita`) REFERENCES `t_usuarios` (`id_usuario`);

--
-- Filtros para la tabla `t_reportes`
--
ALTER TABLE `t_reportes`
  ADD CONSTRAINT `fkEquipoReporte` FOREIGN KEY (`id_equipo`) REFERENCES `t_cat_equipo` (`id_equipo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fkUsuarioReporte` FOREIGN KEY (`id_usuario`) REFERENCES `t_usuarios` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `t_usuarios`
--
ALTER TABLE `t_usuarios`
  ADD CONSTRAINT `fkPersona` FOREIGN KEY (`id_persona`) REFERENCES `t_persona` (`id_persona`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fkRoles` FOREIGN KEY (`id_rol`) REFERENCES `t_cat_roles` (`id_rol`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_t_usuarios_area` FOREIGN KEY (`id_area`) REFERENCES `t_area` (`idrarea`) ON DELETE SET NULL ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
