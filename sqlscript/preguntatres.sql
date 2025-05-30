-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3307
-- Tiempo de generación: 25-05-2025 a las 07:23:39
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
-- Base de datos: `preguntatres`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(10) UNSIGNED NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `anio_nacimiento` date NOT NULL,
  `contrasenia` varchar(100) NOT NULL,
  `fecha_registro` date NOT NULL,
  `foto_perfil` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `genero` varchar(100) NOT NULL,
  `pais` varchar(100), -- NOT NULL,
  `ciudad` varchar(100), -- NOT NULL
  `nickname_hash` varchar(250) NOT NULL,
  `cuenta_activada` TINYINT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nickname` (`nickname`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


CREATE TABLE categoria (
                           id INT AUTO_INCREMENT PRIMARY KEY,
                           descripcion VARCHAR(100) NOT NULL
);

INSERT INTO categoria (id, descripcion) VALUES (1, 'Geografía');
INSERT INTO categoria (id, descripcion) VALUES (2, 'Historia');
INSERT INTO categoria (id, descripcion) VALUES (3, 'Arte');
INSERT INTO categoria (id, descripcion) VALUES (4, 'Entretenimiento');
INSERT INTO categoria (id, descripcion) VALUES (5, 'Ciencia');
INSERT INTO categoria (id, descripcion) VALUES (6, 'Deportes');


CREATE TABLE pregunta (
                          id INT AUTO_INCREMENT PRIMARY KEY,
                          enunciado TEXT NOT NULL,
                          cantidad_jugada INT DEFAULT 0,
                          cantidad_aciertos INT DEFAULT 0,
                          fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
                          cantidad_reportes INT DEFAULT 0,
                          id_categoria INT NOT NULL,
                          FOREIGN KEY (id_categoria) REFERENCES categoria(id)
);


CREATE TABLE respuesta (
                           id INT AUTO_INCREMENT PRIMARY KEY,
                           descripcion TEXT NOT NULL,
                           es_correcta TINYINT(1) DEFAULT 0,
                           id_pregunta INT NOT NULL,
                           FOREIGN KEY (id_pregunta) REFERENCES pregunta(id) ON DELETE CASCADE
);


INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria) VALUES (1, '¿Cuál es la capital de Australia?', 0, 0, CURRENT_TIMESTAMP, 0, 1);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (1, 'Canberra', 1, 1);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (2, 'Sídney', 0, 1);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (3, 'Melbourne', 0, 1);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (4, 'Brisbane', 0, 1);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria) VALUES (2, '¿En qué continente se encuentra el desierto del Sahara?', 0, 0, CURRENT_TIMESTAMP, 0, 1);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (5, 'África', 1, 2);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (6, 'Asia', 0, 2);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (7, 'América', 0, 2);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (8, 'Europa', 0, 2);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria) VALUES (3, '¿Qué río es el más largo del mundo?', 0, 0, CURRENT_TIMESTAMP, 0, 1);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (9, 'Amazonas', 1, 3);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (10, 'Nilo', 0, 3);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (11, 'Yangtsé', 0, 3);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (12, 'Misisipi', 0, 3);


INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria) VALUES (4, '¿En qué año cayó el Muro de Berlín?', 0, 0, CURRENT_TIMESTAMP, 0, 2);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (13, '1989', 1, 4);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (14, '1990', 0, 4);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (15, '1985', 0, 4);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (16, '1979', 0, 4);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria) VALUES (5, '¿Quién fue el primer presidente de Estados Unidos?', 0, 0, CURRENT_TIMESTAMP, 0, 2);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (17, 'George Washington', 1, 5);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (18, 'Thomas Jefferson', 0, 5);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (19, 'Abraham Lincoln', 0, 5);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (20, 'John Adams', 0, 5);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria) VALUES (6, '¿Qué civilización construyó las pirámides de Egipto?', 0, 0, CURRENT_TIMESTAMP, 0, 2);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (21, 'Egipcia', 1, 6);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (22, 'Romana', 0, 6);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (23, 'Maya', 0, 6);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (24, 'Griega', 0, 6);


-- Pregunta 7
INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria) VALUES (7, '¿Quién pintó "La noche estrellada"?', 0, 0, CURRENT_TIMESTAMP, 0, 3);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (25, 'Vincent van Gogh', 1, 7);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (26, 'Pablo Picasso', 0, 7);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (27, 'Claude Monet', 0, 7);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (28, 'Salvador Dalí', 0, 7);

-- Pregunta 8
INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria) VALUES (8, '¿En qué país nació Leonardo da Vinci?', 0, 0, CURRENT_TIMESTAMP, 0, 3);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (29, 'Italia', 1, 8);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (30, 'Francia', 0, 8);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (31, 'España', 0, 8);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (32, 'Países Bajos', 0, 8);

-- Pregunta 9
INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria) VALUES (9, '¿Qué técnica usó Miguel Ángel en la Capilla Sixtina?', 0, 0, CURRENT_TIMESTAMP, 0, 3);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (33, 'Fresco', 1, 9);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (34, 'Óleo', 0, 9);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (35, 'Acuarela', 0, 9);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (36, 'Tinta', 0, 9);


-- Pregunta 10
INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria) VALUES (10, '¿Quién interpretó a Harry Potter en las películas?', 0, 0, CURRENT_TIMESTAMP, 0, 4);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (37, 'Daniel Radcliffe', 1, 10);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (38, 'Elijah Wood', 0, 10);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (39, 'Rupert Grint', 0, 10);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (40, 'Tom Felton', 0, 10);

-- Pregunta 11
INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria) VALUES (11, '¿Qué serie popular tiene un trono hecho de espadas?', 0, 0, CURRENT_TIMESTAMP, 0, 4);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (41, 'Game of Thrones', 1, 11);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (42, 'Vikings', 0, 11);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (43, 'The Witcher', 0, 11);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (44, 'The Crown', 0, 11);

-- Pregunta 12
INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria) VALUES (12, '¿Cuál es el nombre del personaje principal en "Breaking Bad"?', 0, 0, CURRENT_TIMESTAMP, 0, 4);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (45, 'Walter White', 1, 12);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (46, 'Jesse Pinkman', 0, 12);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (47, 'Hank Schrader', 0, 12);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (48, 'Saul Goodman', 0, 12);


-- Pregunta 13
INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria) VALUES (13, '¿Cuál es el símbolo químico del agua?', 0, 0, CURRENT_TIMESTAMP, 0, 5);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (49, 'H2O', 1, 13);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (50, 'O2', 0, 13);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (51, 'CO2', 0, 13);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (52, 'NaCl', 0, 13);

-- Pregunta 14
INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria) VALUES (14, '¿Cuál es el planeta más grande del sistema solar?', 0, 0, CURRENT_TIMESTAMP, 0, 5);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (53, 'Júpiter', 1, 14);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (54, 'Saturno', 0, 14);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (55, 'Neptuno', 0, 14);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (56, 'Tierra', 0, 14);

-- Pregunta 15
INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria) VALUES (15, '¿Qué científico propuso la teoría de la relatividad?', 0, 0, CURRENT_TIMESTAMP, 0, 5);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (57, 'Albert Einstein', 1, 15);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (58, 'Isaac Newton', 0, 15);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (59, 'Galileo Galilei', 0, 15);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (60, 'Nikola Tesla', 0, 15);


-- Pregunta 16
INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria) VALUES (16, '¿Cuántos jugadores hay en un equipo de fútbol?', 0, 0, CURRENT_TIMESTAMP, 0, 6);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (61, '11', 1, 16);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (62, '10', 0, 16);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (63, '12', 0, 16);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (64, '9', 0, 16);

-- Pregunta 17
INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria) VALUES (17, '¿Qué país ganó el Mundial de Fútbol 2022?', 0, 0, CURRENT_TIMESTAMP, 0, 6);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (65, 'Argentina', 1, 17);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (66, 'Francia', 0, 17);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (67, 'Brasil', 0, 17);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (68, 'Alemania', 0, 17);

-- Pregunta 18
INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria) VALUES (18, '¿En qué deporte se usa una raqueta y una red en cancha rectangular?', 0, 0, CURRENT_TIMESTAMP, 0, 6);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (69, 'Tenis', 1, 18);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (70, 'Bádminton', 0, 18);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (71, 'Ping pong', 0, 18);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES (72, 'Squash', 0, 18);

