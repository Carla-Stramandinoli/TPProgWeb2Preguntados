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
    pregunta_creada TINYINT DEFAULT 0,
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

CREATE TABLE usuario (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nickname VARCHAR(100) NOT NULL,
    nombre_completo VARCHAR(100) NOT NULL,
    contrasenia VARCHAR(100) NOT NULL,
    rol varchar(100) DEFAULT 'jugador',
    UNIQUE (nickname)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- insert de usuarios (un admin, un editor y un jugador)
INSERT INTO `usuario` (`id`, `nickname`, `nombre_completo`, `contrasenia`, `rol`) VALUES
(1, 'admin', 'admin', '$2y$10$p67lrz3trA60A41TVR3P6.ZMav.yJm8fvuuOi8Xanq1rEA7EATTUO', 'administrador'),
(2, 'editor', 'editor', '$2y$10$vOGtq0T7ctVVUDEwZGJ7l.FS2JMKivFbVIRuL168zaUbyJ.kWZ01S', 'editor'),
(3, 'JugadorTest', 'JugadorTest', '$2y$10$GYlEgvBrW4NDdXf3MyGJI.f6OIf4mRlZhWrbshXmzOPRTHWcczySG', 'jugador');


CREATE TABLE jugador (
    id INT PRIMARY KEY,
    puntaje_alcanzado INT DEFAULT 0,
    anio_nacimiento DATE NOT NULL,
    fecha_registro date NOT NULL,
    foto_perfil VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    genero VARCHAR(100) NOT NULL,
    pais VARCHAR(100) NOT NULL,
    latitud DOUBLE NOT NULL,
    longitud DOUBLE NOT NULL,
    cuenta_activada TINYINT DEFAULT 0,
    nickname_hash VARCHAR(250), -- NOT NULL,
    cantidad_jugada INT DEFAULT 0,
    cantidad_aciertos INT DEFAULT 0,
    FOREIGN KEY (id) REFERENCES usuario(id)
);


CREATE TABLE administrador (
    id INT PRIMARY KEY,
    FOREIGN KEY (id) REFERENCES usuario(id)
);

CREATE TABLE editor (
    id INT PRIMARY KEY,
    FOREIGN KEY (id) REFERENCES usuario(id)
);

-- inserts de herencia de los usuarios  administrador, editor y jugador

INSERT INTO administrador (id) VALUES (1);

INSERT INTO editor (id) VALUES (2);

INSERT INTO `jugador` (`id`, `puntaje_alcanzado`, `anio_nacimiento`, `fecha_registro`, `foto_perfil`, `email`, `genero`, `pais`, `latitud`, `longitud`, `cuenta_activada`, `nickname_hash`, `cantidad_jugada`, `cantidad_aciertos`) VALUES
    (3, 0, '2025-06-04', '2025-06-04', 'foto_perfil.jpg', 'jugador@gmail.com', '3','AR', -34.67064, -58.562598, 1, NULL, 0, 0);

CREATE TABLE partida (
    id_partida INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    fecha_partida DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    resultado INT,
    id_jugador INT NOT NULL,
    FOREIGN KEY (id_jugador) REFERENCES jugador(id)
);

CREATE TABLE contesta (
    id_jugador INT NOT NULL,
    id_pregunta INT NOT NULL,
    PRIMARY KEY (id_jugador, id_pregunta),
    FOREIGN KEY (id_jugador) REFERENCES jugador(id),
    FOREIGN KEY (id_pregunta) REFERENCES pregunta(id)
);

CREATE TABLE sugerencia (
    id_sugerencia INT NOT NULL AUTO_INCREMENT,
    id_jugador INT NOT NULL,
    enunciado VARCHAR(255) NOT NULL,
    respuesta_correcta VARCHAR(255) NOT NULL,
    respuesta_1 VARCHAR(255) NOT NULL,
    respuesta_2 VARCHAR(255) NOT NULL,
    respuesta_3 VARCHAR(255) NOT NULL,
    categoria INT NOT NULL,
    PRIMARY KEY (id_sugerencia),
    FOREIGN KEY (id_jugador) REFERENCES jugador(id),
    FOREIGN KEY (categoria) REFERENCES categoria(id)
);

--
-- Volcado de datos para la tabla `usuario`
--

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `usuario`
--
/*ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nickname` (`nickname`);*/

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuario`
--
/*ALTER TABLE `usuario`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;*/

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

INSERT INTO `usuario` (`id`, `nickname`, `nombre_completo`, `contrasenia`, `rol`) VALUES
(4, 'jugador1', 'jugador1', '$2y$10$jxQUdzjGPOdqUEvY55USq.wEN8kTEITMMoUjUWpzE8UD0ZhbPLSX.', 'jugador'),
(5, 'jugador2', 'jugador2', '$2y$10$ArvXA8TL67L7ffXWlw46OOwNZ9cjhz8y4KoJaTGIGo25cOm.b8MYS', 'jugador'),
(6, 'jugador3', 'jugador3', '$2y$10$B1l8OG735rVvo.044v2n7.OMnfL1Bjn2dnlIeqX1SL4IqrLRyT3ya', 'jugador'),
(7, 'jugador4', 'jugador4', '$2y$10$OLFqXhSNTwgudW6MekyFueVC2g22YBYIbJLvhzyWlXVVlSBkjnpNm', 'jugador'),
(8, 'jugador5', 'jugador5', '$2y$10$4UOF1GMGmpzmYtm0g77JD.Kr.AowYvsKKYRpNgrveTxbfIWLItxky', 'jugador'),
(9, 'jugador6', 'jugador6', '$2y$10$/lmQ49xcBIBDTYKldJWdrO2KlG9Sn0tCrsgcffT4OYyM3OVxogYBm', 'jugador'),
(10, 'jugador7', 'jugador7', '$2y$10$sobsv6ILbu8fkTQlvdzZa.yFnSdewxElrd0B2XlaESMId5VcHN7ZS', 'jugador'),
(11, 'jugador8', 'jugador8', '$2y$10$7KEyUWZPaMP4Fk9lVse/DeblXtfqRre8Phm1iXQGRUJcNPphw/oLC', 'jugador'),
(12, 'jugador9', 'jugador9', '$2y$10$QWJ3EDcrjFs/I9nY9vdCE.6laiEngvQvKBJ.RASZZ5Oak/BjBNxvq', 'jugador'),
(13, 'jugador10', 'jugador10', '$2y$10$dwETeQVshlqyqT94CNPUnOCBeHB62IVsCCwhnvmnzN1BRzrFLX5KK', 'jugador'),
(14, 'jugador11', 'jugador11', '$2y$10$frpAF/3.FaUkcjsW5U0jde4XTyYaymqk9jB4HkqjxusLc./JedIuy', 'jugador'),
(15, 'jugador12', 'jugador12', '$2y$10$OkFFBkiXrsoimLxnQ4Ie8uWakE1D2roKw39i1FKYcDxxSOp2V6O02', 'jugador'),
(16, 'jugador13', 'jugador13', '$2y$10$OYgFDWNaxONx7f5WE.XFv.lovDckPkWUM79zqIaLIgYE6u7lK7qXS', 'jugador'),
(17, 'jugador14', 'jugador14', '$2y$10$P.P47fdBSQSLLs4P7SEfJ.PuIm8WL0bhieXXlMKwWBuBu0YbfYF/K', 'jugador'),
(18, 'jugador15', 'jugador15', '$2y$10$HisS6U234QE3lbFpAAWsY.Jkv6j787U2GU4PgJPb.ji4BhAGwgOWm', 'jugador'),
(19, 'jugador16', 'jugador16', '$2y$10$HisS6U234QE3lbFpAAWsY.Jkv6j787U2GU4PgJPb.ji4BhAGwgOWm', 'jugador'),
(20, 'jugador17', 'jugador17', '$2y$10$KljrYR8SRUNMwIA3New40.SJlVbfCA0U3PQohzcSXiknmeb.99zgi', 'jugador'),
(21, 'jugador18', 'jugador18', '$2y$10$61s8WwuNLBvlr.ioO88wPOjn0NnOD.zwVy3TVyZp33bBkfTRVkgza', 'jugador'),
(22, 'jugador19', 'jugador19', '$2y$10$IaF9BpNAYW.jLSRL5gfs..ILKxyzS/M89ve3xlLBcUPLEodZbmKBS', 'jugador'),
(23, 'jugador20', 'jugador20', '$2y$10$B7r6v39G2mOu0a9tgCOmaOzg34KTGwB0fsi6AFnVXIbCkobBD7A02', 'jugador'),
(24, 'jugador21', 'jugador21', '$2y$10$H/SH7MPbxuuB2Qj6rjM6LOBHKfp2OySqw4tDz8ojo3/0UDza2hM1i', 'jugador'),
(25, 'jugador22', 'jugador22', '$2y$10$RZVM5SsbyFpCW1nKz5D/wOBc1OfscJCy5V4Zf8EqCI23TkD6Dvlhi', 'jugador'),
(26, 'jugador23', 'jugador23', '$2y$10$SEh45QvklQs7IPCQQ9x5GeXZ5bImjFqUErDbRduB023aCovnoLIaq', 'jugador'),
(27, 'jugador24', 'jugador24', '$2y$10$6T34Zxak/fPgT9j/Dw2JPOg6fU8bNxeUk6R2s.kMsU5SOCBBjEZBu', 'jugador'),
(28, 'jugador25', 'jugador25', '$2y$10$GnzWiiPMotZzwMMn9SPdMuRa8c1W5fLTHdL28GOc7JLmVFqpW9uQ6', 'jugador'),
(29, 'jugador26', 'jugador26', '$2y$10$VQBqwPts.1er4kaqFCh2ZuIoIJ1Ov.veXNEYczABlAN2M1doIIRaW', 'jugador'),
(30, 'jugador27', 'jugador27', '$2y$10$AgYy4Mmyc14j6ts2BFxrFOMpHMfand4Jaxlre0nu5iBEBCHnp/lhO', 'jugador'),
(31, 'jugador28', 'jugador28', '$2y$10$iylWRYu5tohV4B/73t1fyO6foM1BGINCuP99NRxd2i4PAVT10UfaS', 'jugador'),
(32, 'jugador29', 'jugador29', '$2y$10$JRrx6OJNt2Qz6o3SQHllW.Nb/pJ5lEnNFOCt8u8qBqrnvMrH0yOTW', 'jugador'),
(33, 'jugador30', 'jugador30', '$2y$10$lxvkOdDdJOeRT/4B6mfvme4vZHZV1RasEzJ0qzWaVVY8uxhtOUBHa', 'jugador');

INSERT INTO `jugador` (`id`, `puntaje_alcanzado`, `anio_nacimiento`, `fecha_registro`, `foto_perfil`, `email`, `genero`, `pais`, `latitud`, `longitud`, `cuenta_activada`, `nickname_hash`, `cantidad_jugada`, `cantidad_aciertos`) VALUES
(4, 32, '2005-06-01', '2022-06-01', 'foto_perfil.jpg', 'jugador1@gmail.com', '1', 'AR', -34.6, -58.4, 1, NULL, 32, 15),
(5, 25, '1990-07-11', '2020-04-12', 'foto_perfil.jpg', 'jugador2@gmail.com', '2', 'CL', -33.4, -70.6, 1, NULL, 25, 12),
(6, 14, '1980-12-21', '2019-12-22', 'foto_perfil.jpg', 'jugador3@gmail.com', '1', 'BR', -15.7, -47.9, 1, NULL, 14, 10),
(7, 42, '2000-11-01', '2023-11-03', 'foto_perfil.jpg', 'jugador4@gmail.com', '3', 'UY', -34.9, -56.2, 1, NULL, 42, 24),
(8, 10, '2010-01-05', '2021-10-21', 'foto_perfil.jpg', 'jugador5@gmail.com', '2', 'PY', -25.3, -57.6, 1, NULL, 10, 9),
(9, 19, '1995-08-02', '2018-06-10', 'foto_perfil.jpg', 'jugador6@gmail.com', '1', 'PE', -12.0, -77.0, 1, NULL, 19, 11),
(10, 8, '2003-04-07', '2025-07-03', 'foto_perfil.jpg', 'jugador7@gmail.com', '2', 'IT', 4.7, -74.1, 1, NULL, 8, 7),
(11, 15, '1985-03-12', '2020-03-14', 'foto_perfil.jpg', 'jugador8@gmail.com', '1', 'EC', -0.2, -78.5, 1, NULL, 15, 9),
(12, 5, '1965-10-17', '2020-09-14', 'foto_perfil.jpg', 'jugador9@gmail.com', '2', 'US', -16.5, -68.1, 1, NULL, 6, 5),
(13, 30, '1998-02-26', '2021-06-12', 'foto_perfil.jpg', 'jugador10@gmail.com', '3', 'MX', 19.4, -99.1, 1, NULL, 30, 15),
(14, 13, '2006-11-12', '2018-10-04', 'foto_perfil.jpg', 'jugador11@gmail.com', '1', 'ES', 40.4, -3.7, 1, NULL, 13, 10),
(15, 39, '2001-09-02', '2023-11-24', 'foto_perfil.jpg', 'jugador12@gmail.com', '2', 'US', 41.9, 12.5, 1, NULL, 39, 28),
(16, 9, '2009-07-17', '2021-02-01', 'foto_perfil.jpg', 'jugador13@gmail.com', '1', 'RU', 48.8, 2.3, 1, NULL, 9, 8),
(17, 34, '1988-05-10', '2024-01-22', 'foto_perfil.jpg', 'jugador14@gmail.com', '2', 'DE', 52.5, 13.4, 1, NULL, 34, 16),
(18, 22, '1993-01-10', '2019-08-04', 'foto_perfil.jpg', 'jugador15@gmail.com', '3', 'CA', 45.4, -75.6, 1, NULL, 22, 12),
(19, 37, '1975-12-08', '2018-09-11', 'foto_perfil.jpg', 'jugador16@gmail.com', '1', 'CN', 38.9, -77.0, 1, NULL, 37, 19),
(20, 11, '1999-10-17', '2021-05-15', 'foto_perfil.jpg', 'jugador17@gmail.com', '2', 'CU', 23.1, -82.3, 1, NULL, 11, 8),
(21, 26, '1997-07-02', '2020-05-04', 'foto_perfil.jpg', 'jugador18@gmail.com', '3', 'PA', 8.9, -79.5, 1, NULL, 26, 12),
(22, 29, '2002-08-22', '2023-07-25', 'foto_perfil.jpg', 'jugador19@gmail.com', '1', 'HN', 14.0, -87.2, 1, NULL, 29, 14),
(23, 35, '2007-01-18', '2018-08-05', 'foto_perfil.jpg', 'jugador20@gmail.com', '2', 'NI', 12.1, -86.3, 1, NULL, 35, 26),
(24, 6, '2008-09-13', '2018-10-11', 'foto_perfil.jpg', 'jugador21@gmail.com', '1', 'CR', 9.9, -84.1, 1, NULL, 6, 5),
(25, 16, '2008-05-28', '2018-01-28', 'foto_perfil.jpg', 'jugador22@gmail.com', '3', 'VE', 10.5, -66.9, 1, NULL, 16, 11),
(26, 18, '1992-06-06', '2021-02-13', 'foto_perfil.jpg', 'jugador23@gmail.com', '2', 'GT', 14.6, -90.5, 1, NULL, 18, 10),
(27, 21, '1982-03-12', '2020-03-10', 'foto_perfil.jpg', 'jugador24@gmail.com', '1', 'FI', 13.7, -89.2, 1, NULL, 21, 13),
(28, 40, '1978-04-11', '2019-12-21', 'foto_perfil.jpg', 'jugador25@gmail.com', '3', 'HT', 18.5, -72.3, 1, NULL, 40, 20),
(29, 45, '2004-08-21', '2021-11-04', 'foto_perfil.jpg', 'jugador26@gmail.com', '1', 'PT', 38.7, -9.1, 1, NULL, 45, 31),
(30, 23, '1989-07-26', '2020-10-25', 'foto_perfil.jpg', 'jugador27@gmail.com', '2', 'CH', 46.2, 6.1, 1, NULL, 23, 13),
(31, 17, '1994-11-10', '2025-04-18', 'foto_perfil.jpg', 'jugador28@gmail.com', '1', 'NO', 59.9, 10.8, 1, NULL, 17, 10),
(32, 36, '1987-06-13', '2024-07-21', 'foto_perfil.jpg', 'jugador29@gmail.com', '3', 'SE', 59.3, 18.0, 1, NULL, 36, 18),
(33, 27,'1991-09-19', '2022-03-27', 'foto_perfil.jpg', 'jugador30@gmail.com', '2', 'FI', 60.2, 24.9, 1, NULL, 27, 15);


-- Geografia
INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (1, '¿Cuál es la capital de Australia?', 0, 0, CURRENT_TIMESTAMP, 0, 1);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (1, 'Canberra', 1, 1),
                                                                      (2, 'Sídney', 0, 1),
                                                                      (3, 'Melbourne', 0, 1),
                                                                      (4, 'Brisbane', 0, 1);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (2, '¿Cuál es el río más largo del mundo?', 0, 0, CURRENT_TIMESTAMP, 0, 1);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (5, 'Nilo', 1, 2),
                                                                      (6, 'Amazonas', 0, 2),
                                                                      (7, 'Yangtsé', 0, 2),
                                                                      (8, 'Misisipi', 0, 2);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (3, '¿En qué continente se encuentra Kazajistán?', 0, 0, CURRENT_TIMESTAMP, 0, 1);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (9, 'Asia', 1, 3),
                                                                      (10, 'Europa', 0, 3),
                                                                      (11, 'África', 0, 3),
                                                                      (12, 'Oceanía', 0, 3);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (4, '¿Qué país tiene la mayor cantidad de islas en el mundo?', 0, 0, CURRENT_TIMESTAMP, 0, 1);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (13, 'Suecia', 1, 4),
                                                                      (14, 'Indonesia', 0, 4),
                                                                      (15, 'Filipinas', 0, 4),
                                                                      (16, 'Grecia', 0, 4);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (5, '¿Qué cordillera separa Europa de Asia?', 0, 0, CURRENT_TIMESTAMP, 0, 1);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (17, 'Montes Urales', 1, 5),
                                                                      (18, 'Alpes', 0, 5),
                                                                      (19, 'Cáucaso', 0, 5),
                                                                      (20, 'Himalayas', 0, 5);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (6, '¿Cuál es el país más pequeño del mundo?', 0, 0, CURRENT_TIMESTAMP, 0, 1);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (21, 'Ciudad del Vaticano', 1, 6),
                                                                      (22, 'Mónaco', 0, 6),
                                                                      (23, 'San Marino', 0, 6),
                                                                      (24, 'Liechtenstein', 0, 6);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (7, '¿Qué país tiene más fronteras terrestres?', 0, 0, CURRENT_TIMESTAMP, 0, 1);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (25, 'China', 1, 7),
                                                                      (26, 'Rusia', 0, 7),
                                                                      (27, 'Brasil', 0, 7),
                                                                      (28, 'Alemania', 0, 7);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (8, '¿Dónde se encuentra el desierto del Sahara?', 0, 0, CURRENT_TIMESTAMP, 0, 1);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (29, 'África', 1, 8),
                                                                      (30, 'Asia', 0, 8),
                                                                      (31, 'América del Sur', 0, 8),
                                                                      (32, 'Oceanía', 0, 8);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (9, '¿Cuál es el país más grande del mundo en superficie?', 0, 0, CURRENT_TIMESTAMP, 0, 1);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (33, 'Rusia', 1, 9),
                                                                      (34, 'Canadá', 0, 9),
                                                                      (35, 'China', 0, 9),
                                                                      (36, 'Estados Unidos', 0, 9);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (10, '¿Qué océano baña la costa este de Estados Unidos?', 0, 0, CURRENT_TIMESTAMP, 0, 1);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (37, 'Océano Atlántico', 1, 10),
                                                                      (38, 'Océano Pacífico', 0, 10),
                                                                      (39, 'Océano Ártico', 0, 10),
                                                                      (40, 'Océano Índico', 0, 10);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (11, '¿Cuál es la montaña más alta del mundo?', 0, 0, CURRENT_TIMESTAMP, 0, 1);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (41, 'Monte Everest', 1, 11),
                                                                      (42, 'K2', 0, 11),
                                                                      (43, 'Kangchenjunga', 0, 11),
                                                                      (44, 'Aconcagua', 0, 11);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (12, '¿Cuál es la capital de Canadá?', 0, 0, CURRENT_TIMESTAMP, 0, 1);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (45, 'Ottawa', 1, 12),
                                                                      (46, 'Toronto', 0, 12),
                                                                      (47, 'Vancouver', 0, 12),
                                                                      (48, 'Montreal', 0, 12);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (13, '¿Qué mar separa Europa de África?', 0, 0, CURRENT_TIMESTAMP, 0, 1);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (49, 'Mar Mediterráneo', 1, 13),
                                                                      (50, 'Mar Rojo', 0, 13),
                                                                      (51, 'Mar Negro', 0, 13),
                                                                      (52, 'Mar Caspio', 0, 13);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (14, '¿En qué país se encuentra el Kilimanjaro?', 0, 0, CURRENT_TIMESTAMP, 0, 1);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (53, 'Tanzania', 1, 14),
                                                                      (54, 'Kenia', 0, 14),
                                                                      (55, 'Etiopía', 0, 14),
                                                                      (56, 'Uganda', 0, 14);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (15, '¿Qué país tiene forma de bota?', 0, 0, CURRENT_TIMESTAMP, 0, 1);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (57, 'Italia', 1, 15),
                                                                      (58, 'España', 0, 15),
                                                                      (59, 'Grecia', 0, 15),
                                                                      (60, 'Francia', 0, 15);

-- Historia

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (16, '¿En qué año comenzó la Segunda Guerra Mundial?', 0, 0, CURRENT_TIMESTAMP, 0, 2);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (61, '1939', 1, 16),
                                                                      (62, '1941', 0, 16),
                                                                      (63, '1936', 0, 16),
                                                                      (64, '1945', 0, 16);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (17, '¿Quién fue el primer presidente de Estados Unidos?', 0, 0, CURRENT_TIMESTAMP, 0, 2);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (65, 'George Washington', 1, 17),
                                                                      (66, 'Thomas Jefferson', 0, 17),
                                                                      (67, 'Abraham Lincoln', 0, 17),
                                                                      (68, 'Benjamin Franklin', 0, 17);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (18, '¿Qué civilización construyó Machu Picchu?', 0, 0, CURRENT_TIMESTAMP, 0, 2);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (69, 'Los incas', 1, 18),
                                                                      (70, 'Los aztecas', 0, 18),
                                                                      (71, 'Los mayas', 0, 18),
                                                                      (72, 'Los olmecas', 0, 18);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (19, '¿En qué año cayó el Muro de Berlín?', 0, 0, CURRENT_TIMESTAMP, 0, 2);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (73, '1989', 1, 19),
                                                                      (74, '1991', 0, 19),
                                                                      (75, '1985', 0, 19),
                                                                      (76, '1990', 0, 19);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (20, '¿Quién lideró la Revolución Cubana?', 0, 0, CURRENT_TIMESTAMP, 0, 2);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (77, 'Fidel Castro', 1, 20),
                                                                      (78, 'Che Guevara', 0, 20),
                                                                      (79, 'Camilo Cienfuegos', 0, 20),
                                                                      (80, 'Raúl Castro', 0, 20);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (21, '¿Qué evento marcó el inicio de la Edad Media?', 0, 0, CURRENT_TIMESTAMP, 0, 2);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (81, 'La caída del Imperio Romano de Occidente', 1, 21),
                                                                      (82, 'La coronación de Carlomagno', 0, 21),
                                                                      (83, 'La invasión de los bárbaros', 0, 21),
                                                                      (84, 'El descubrimiento de América', 0, 21);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (22, '¿Qué país fue el principal colonizador de Brasil?', 0, 0, CURRENT_TIMESTAMP, 0, 2);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (85, 'Portugal', 1, 22),
                                                                      (86, 'España', 0, 22),
                                                                      (87, 'Francia', 0, 22),
                                                                      (88, 'Países Bajos', 0, 22);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (23, '¿Quién escribió "El Príncipe"?', 0, 0, CURRENT_TIMESTAMP, 0, 2);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (89, 'Nicolás Maquiavelo', 1, 23),
                                                                      (90, 'Thomas Hobbes', 0, 23),
                                                                      (91, 'Platón', 0, 23),
                                                                      (92, 'Aristóteles', 0, 23);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (24, '¿En qué país ocurrió la Revolución Francesa?', 0, 0, CURRENT_TIMESTAMP, 0, 2);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (93, 'Francia', 1, 24),
                                                                      (94, 'Italia', 0, 24),
                                                                      (95, 'Alemania', 0, 24),
                                                                      (96, 'Inglaterra', 0, 24);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (25, '¿Qué líder militar fue derrotado en Waterloo?', 0, 0, CURRENT_TIMESTAMP, 0, 2);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (97, 'Napoleón Bonaparte', 1, 25),
                                                                      (98, 'Julio César', 0, 25),
                                                                      (99, 'Alejandro Magno', 0, 25),
                                                                      (100, 'Winston Churchill', 0, 25);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (26, '¿Qué país utilizó por primera vez armas nucleares en guerra?', 0, 0, CURRENT_TIMESTAMP, 0, 2);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (101, 'Estados Unidos', 1, 26),
                                                                      (102, 'Alemania', 0, 26),
                                                                      (103, 'Japón', 0, 26),
                                                                      (104, 'Unión Soviética', 0, 26);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (27, '¿Cuál fue la primera civilización conocida?', 0, 0, CURRENT_TIMESTAMP, 0, 2);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (105, 'Sumerios', 1, 27),
                                                                      (106, 'Egipcios', 0, 27),
                                                                      (107, 'Griegos', 0, 27),
                                                                      (108, 'Persas', 0, 27);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (28, '¿Quién fue el autor del Comunismo en el siglo XIX?', 0, 0, CURRENT_TIMESTAMP, 0, 2);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (109, 'Karl Marx', 1, 28),
                                                                      (110, 'Vladimir Lenin', 0, 28),
                                                                      (111, 'Joseph Stalin', 0, 28),
                                                                      (112, 'Friedrich Engels', 0, 28);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (29, '¿En qué año llegó Cristóbal Colón a América?', 0, 0, CURRENT_TIMESTAMP, 0, 2);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (113, '1492', 1, 29),
                                                                      (114, '1500', 0, 29),
                                                                      (115, '1475', 0, 29),
                                                                      (116, '1519', 0, 29);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (30, '¿Qué tratado puso fin a la Primera Guerra Mundial?', 0, 0, CURRENT_TIMESTAMP, 0, 2);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (117, 'Tratado de Versalles', 1, 30),
                                                                      (118, 'Tratado de París', 0, 30),
                                                                      (119, 'Tratado de Utrecht', 0, 30),
                                                                      (120, 'Tratado de Gante', 0, 30);

-- Arte
INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (31, '¿Quién pintó "La noche estrellada"?', 0, 0, CURRENT_TIMESTAMP, 0, 3);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (121, 'Vincent van Gogh', 1, 31),
                                                                      (122, 'Claude Monet', 0, 31),
                                                                      (123, 'Pablo Picasso', 0, 31),
                                                                      (124, 'Salvador Dalí', 0, 31);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (32, '¿Qué escultor renacentista creó la escultura de David?', 0, 0, CURRENT_TIMESTAMP, 0, 3);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (125, 'Miguel Ángel', 1, 32),
                                                                      (126, 'Donatello', 0, 32),
                                                                      (127, 'Bernini', 0, 32),
                                                                      (128, 'Leonardo da Vinci', 0, 32);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (33, '¿Qué famoso pintor español es conocido por el "Guernica"?', 0, 0, CURRENT_TIMESTAMP, 0, 3);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (129, 'Pablo Picasso', 1, 33),
                                                                      (130, 'Joan Miró', 0, 33),
                                                                      (131, 'Diego Velázquez', 0, 33),
                                                                      (132, 'Francisco Goya', 0, 33);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (34, '¿Cuál es la técnica utilizada en la pintura mural de la Capilla Sixtina?', 0, 0, CURRENT_TIMESTAMP, 0, 3);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (133, 'Fresco', 1, 34),
                                                                      (134, 'Óleo', 0, 34),
                                                                      (135, 'Acuarela', 0, 34),
                                                                      (136, 'Temple', 0, 34);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (35, '¿Quién pintó "La joven de la perla"?', 0, 0, CURRENT_TIMESTAMP, 0, 3);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (137, 'Johannes Vermeer', 1, 35),
                                                                      (138, 'Rembrandt', 0, 35),
                                                                      (139, 'Velázquez', 0, 35),
                                                                      (140, 'Rubens', 0, 35);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (36, '¿A qué movimiento artístico pertenece Salvador Dalí?', 0, 0, CURRENT_TIMESTAMP, 0, 3);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (141, 'Surrealismo', 1, 36),
                                                                      (142, 'Impresionismo', 0, 36),
                                                                      (143, 'Cubismo', 0, 36),
                                                                      (144, 'Expresionismo', 0, 36);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (37, '¿Qué pintor es conocido por su serie de nenúfares?', 0, 0, CURRENT_TIMESTAMP, 0, 3);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (145, 'Claude Monet', 1, 37),
                                                                      (146, 'Édouard Manet', 0, 37),
                                                                      (147, 'Henri Matisse', 0, 37),
                                                                      (148, 'Paul Cézanne', 0, 37);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (38, '¿Qué artista pintó el techo de la Capilla Sixtina?', 0, 0, CURRENT_TIMESTAMP, 0, 3);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (149, 'Miguel Ángel', 1, 38),
                                                                      (150, 'Rafael', 0, 38),
                                                                      (151, 'Caravaggio', 0, 38),
                                                                      (152, 'Leonardo da Vinci', 0, 38);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (39, '¿Qué obra representa a un hombre gritando bajo un cielo ondulado?', 0, 0, CURRENT_TIMESTAMP, 0, 3);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (153, 'El Grito', 1, 39),
                                                                      (154, 'La Persistencia de la Memoria', 0, 39),
                                                                      (155, 'El Jardín de las Delicias', 0, 39),
                                                                      (156, 'La Libertad guiando al pueblo', 0, 39);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (40, '¿Quién pintó "Las Meninas"?', 0, 0, CURRENT_TIMESTAMP, 0, 3);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (157, 'Diego Velázquez', 1, 40),
                                                                      (158, 'Francisco de Goya', 0, 40),
                                                                      (159, 'El Greco', 0, 40),
                                                                      (160, 'Murillo', 0, 40);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (41, '¿Qué artista desarrolló el concepto de "arte pop"?', 0, 0, CURRENT_TIMESTAMP, 0, 3);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (161, 'Andy Warhol', 1, 41),
                                                                      (162, 'Roy Lichtenstein', 0, 41),
                                                                      (163, 'Keith Haring', 0, 41),
                                                                      (164, 'Basquiat', 0, 41);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (42, '¿Cuál de estos artistas NO pertenece al Renacimiento?', 0, 0, CURRENT_TIMESTAMP, 0, 3);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (165, 'Caravaggio', 1, 42),
                                                                      (166, 'Leonardo da Vinci', 0, 42),
                                                                      (167, 'Rafael', 0, 42),
                                                                      (168, 'Donatello', 0, 42);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (43, '¿Qué artista es famoso por sus relojes derretidos?', 0, 0, CURRENT_TIMESTAMP, 0, 3);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (169, 'Salvador Dalí', 1, 43),
                                                                      (170, 'René Magritte', 0, 43),
                                                                      (171, 'Max Ernst', 0, 43),
                                                                      (172, 'Giorgio de Chirico', 0, 43);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (44, '¿Qué pintor italiano es conocido por "La Última Cena"?', 0, 0, CURRENT_TIMESTAMP, 0, 3);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (173, 'Leonardo da Vinci', 1, 44),
                                                                      (174, 'Rafael', 0, 44),
                                                                      (175, 'Botticelli', 0, 44),
                                                                      (176, 'Tiziano', 0, 44);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (45, '¿Qué técnica artística utiliza pigmentos mezclados con cera caliente?', 0, 0, CURRENT_TIMESTAMP, 0, 3);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (177, 'Encáustica', 1, 45),
                                                                      (178, 'Fresco', 0, 45),
                                                                      (179, 'Óleo', 0, 45),
                                                                      (180, 'Acuarela', 0, 45);

-- Entretenimiento
INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (46, '¿Cuál es el nombre del mago protagonista en la saga escrita por J.K. Rowling?', 0, 0, CURRENT_TIMESTAMP, 0, 4);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (181, 'Harry Potter', 1, 46),
                                                                      (182, 'Merlín', 0, 46),
                                                                      (183, 'Gandalf', 0, 46),
                                                                      (184, 'Albus Dumbledore', 0, 46);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (47, '¿En qué serie aparece el personaje Walter White?', 0, 0, CURRENT_TIMESTAMP, 0, 4);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (185, 'Breaking Bad', 1, 47),
                                                                      (186, 'Better Call Saul', 0, 47),
                                                                      (187, 'Narcos', 0, 47),
                                                                      (188, 'The Wire', 0, 47);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (48, '¿Qué película ganó el Oscar a Mejor Película en 1994?', 0, 0, CURRENT_TIMESTAMP, 0, 4);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (189, 'Forrest Gump', 1, 48),
                                                                      (190, 'Pulp Fiction', 0, 48),
                                                                      (191, 'Cadena Perpetua', 0, 48),
                                                                      (192, 'Cuatro bodas y un funeral', 0, 48);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (49, '¿Qué banda compuso el álbum "The Dark Side of the Moon"?', 0, 0, CURRENT_TIMESTAMP, 0, 4);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (193, 'Pink Floyd', 1, 49),
                                                                      (194, 'The Beatles', 0, 49),
                                                                      (195, 'Led Zeppelin', 0, 49),
                                                                      (196, 'Queen', 0, 49);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (50, '¿Cuál es el nombre de la princesa en "La Bella Durmiente"?', 0, 0, CURRENT_TIMESTAMP, 0, 4);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (197, 'Aurora', 1, 50),
                                                                      (198, 'Ariel', 0, 50),
                                                                      (199, 'Bella', 0, 50),
                                                                      (200, 'Jazmín', 0, 50);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (51, '¿Qué actor interpretó a Jack en "Titanic"?', 0, 0, CURRENT_TIMESTAMP, 0, 4);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (201, 'Leonardo DiCaprio', 1, 51),
                                                                      (202, 'Brad Pitt', 0, 51),
                                                                      (203, 'Matt Damon', 0, 51),
                                                                      (204, 'Johnny Depp', 0, 51);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (52, '¿Cuál es el nombre del dragón en "Shrek"?', 0, 0, CURRENT_TIMESTAMP, 0, 4);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (205, 'No tiene un nombre específico', 1, 52),
                                                                      (206, 'Fuego', 0, 52),
                                                                      (207, 'Draca', 0, 52),
                                                                      (208, 'Ruby', 0, 52);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (53, '¿Qué videojuego popular incluye personajes como Mario, Luigi y Bowser?', 0, 0, CURRENT_TIMESTAMP, 0, 4);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (209, 'Super Mario Bros.', 1, 53),
                                                                      (210, 'Zelda', 0, 53),
                                                                      (211, 'Sonic', 0, 53),
                                                                      (212, 'Minecraft', 0, 53);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (54, '¿Quién canta la canción "Rolling in the Deep"?', 0, 0, CURRENT_TIMESTAMP, 0, 4);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (213, 'Adele', 1, 54),
                                                                      (214, 'Taylor Swift', 0, 54),
                                                                      (215, 'Rihanna', 0, 54),
                                                                      (216, 'Lady Gaga', 0, 54);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (55, '¿En qué ciudad se desarrolla la serie "Friends"?', 0, 0, CURRENT_TIMESTAMP, 0, 4);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (217, 'Nueva York', 1, 55),
                                                                      (218, 'Los Ángeles', 0, 55),
                                                                      (219, 'Chicago', 0, 55),
                                                                      (220, 'Boston', 0, 55);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (56, '¿Qué actor interpretó a Tony Stark en el Universo Cinematográfico de Marvel?', 0, 0, CURRENT_TIMESTAMP, 0, 4);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (221, 'Robert Downey Jr.', 1, 56),
                                                                      (222, 'Chris Evans', 0, 56),
                                                                      (223, 'Mark Ruffalo', 0, 56),
                                                                      (224, 'Chris Hemsworth', 0, 56);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (57, '¿Qué película de Disney fue la primera en ser completamente animada?', 0, 0, CURRENT_TIMESTAMP, 0, 4);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (225, 'Blancanieves y los siete enanitos', 1, 57),
                                                                      (226, 'Pinocho', 0, 57),
                                                                      (227, 'Dumbo', 0, 57),
                                                                      (228, 'Bambi', 0, 57);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (58, '¿Cuál es el nombre del parque temático de Disney en París?', 0, 0, CURRENT_TIMESTAMP, 0, 4);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (229, 'Disneyland Paris', 1, 58),
                                                                      (230, 'EuroDisney', 0, 58),
                                                                      (231, 'Disney World Europe', 0, 58),
                                                                      (232, 'Disney Europa', 0, 58);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (59, '¿Qué película popular cuenta la historia de un tiburón blanco que ataca una playa?', 0, 0, CURRENT_TIMESTAMP, 0, 4);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (233, 'Tiburón (Jaws)', 1, 59),
                                                                      (234, 'Deep Blue Sea', 0, 59),
                                                                      (235, 'Megalodón', 0, 59),
                                                                      (236, 'Sharknado', 0, 59);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (60, '¿Qué director de cine es conocido por películas como "Inception" y "Interstellar"?', 0, 0, CURRENT_TIMESTAMP, 0, 4);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (237, 'Christopher Nolan', 1, 60),
                                                                      (238, 'Steven Spielberg', 0, 60),
                                                                      (239, 'James Cameron', 0, 60),
                                                                      (240, 'Martin Scorsese', 0, 60);

-- Ciencia
INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (61, '¿Cuál es el elemento más abundante en la atmósfera terrestre?', 0, 0, CURRENT_TIMESTAMP, 0, 5);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (241, 'Nitrógeno', 1, 61),
                                                                      (242, 'Oxígeno', 0, 61),
                                                                      (243, 'Dióxido de carbono', 0, 61),
                                                                      (244, 'Hidrógeno', 0, 61);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (62, '¿Quién propuso la teoría de la relatividad?', 0, 0, CURRENT_TIMESTAMP, 0, 5);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (245, 'Albert Einstein', 1, 62),
                                                                      (246, 'Isaac Newton', 0, 62),
                                                                      (247, 'Nikola Tesla', 0, 62),
                                                                      (248, 'Stephen Hawking', 0, 62);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (63, '¿Qué tipo de célula no tiene núcleo?', 0, 0, CURRENT_TIMESTAMP, 0, 5);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (249, 'Procariota', 1, 63),
                                                                      (250, 'Eucariota', 0, 63),
                                                                      (251, 'Célula madre', 0, 63),
                                                                      (252, 'Célula animal', 0, 63);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (64, '¿Cuál es la fórmula química del agua?', 0, 0, CURRENT_TIMESTAMP, 0, 5);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (253, 'H2O', 1, 64),
                                                                      (254, 'CO2', 0, 64),
                                                                      (255, 'O2H', 0, 64),
                                                                      (256, 'H2O2', 0, 64);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (65, '¿Qué planeta es el más cercano al Sol?', 0, 0, CURRENT_TIMESTAMP, 0, 5);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (257, 'Mercurio', 1, 65),
                                                                      (258, 'Venus', 0, 65),
                                                                      (259, 'Tierra', 0, 65),
                                                                      (260, 'Marte', 0, 65);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (66, '¿Cuál es la unidad básica de la vida?', 0, 0, CURRENT_TIMESTAMP, 0, 5);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (261, 'La célula', 1, 66),
                                                                      (262, 'El átomo', 0, 66),
                                                                      (263, 'El gen', 0, 66),
                                                                      (264, 'La molécula', 0, 66);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (67, '¿Qué órgano humano produce insulina?', 0, 0, CURRENT_TIMESTAMP, 0, 5);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (265, 'Páncreas', 1, 67),
                                                                      (266, 'Hígado', 0, 67),
                                                                      (267, 'Estómago', 0, 67),
                                                                      (268, 'Riñón', 0, 67);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (68, '¿Qué gas utilizan las plantas en la fotosíntesis?', 0, 0, CURRENT_TIMESTAMP, 0, 5);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (269, 'Dióxido de carbono', 1, 68),
                                                                      (270, 'Oxígeno', 0, 68),
                                                                      (271, 'Nitrógeno', 0, 68),
                                                                      (272, 'Hidrógeno', 0, 68);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (69, '¿Qué científico formuló las leyes del movimiento?', 0, 0, CURRENT_TIMESTAMP, 0, 5);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (273, 'Isaac Newton', 1, 69),
                                                                      (274, 'Albert Einstein', 0, 69),
                                                                      (275, 'Galileo Galilei', 0, 69),
                                                                      (276, 'Aristóteles', 0, 69);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (70, '¿Qué planeta tiene el mayor número de lunas conocidas?', 0, 0, CURRENT_TIMESTAMP, 0, 5);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (277, 'Saturno', 1, 70),
                                                                      (278, 'Júpiter', 0, 70),
                                                                      (279, 'Urano', 0, 70),
                                                                      (280, 'Neptuno', 0, 70);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (71, '¿Qué nombre recibe el proceso mediante el cual un sólido pasa directamente a estado gaseoso?', 0, 0, CURRENT_TIMESTAMP, 0, 5);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (281, 'Sublimación', 1, 71),
                                                                      (282, 'Evaporación', 0, 71),
                                                                      (283, 'Condensación', 0, 71),
                                                                      (284, 'Fusión', 0, 71);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (72, '¿Cuál es el número atómico del carbono?', 0, 0, CURRENT_TIMESTAMP, 0, 5);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (285, '6', 1, 72),
                                                                      (286, '12', 0, 72),
                                                                      (287, '14', 0, 72),
                                                                      (288, '8', 0, 72);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (73, '¿Qué parte del ojo humano controla la cantidad de luz que entra?', 0, 0, CURRENT_TIMESTAMP, 0, 5);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (289, 'La pupila', 1, 73),
                                                                      (290, 'La retina', 0, 73),
                                                                      (291, 'El cristalino', 0, 73),
                                                                      (292, 'La córnea', 0, 73);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (74, '¿Cómo se llama el proceso por el cual una célula se divide en dos células hijas idénticas?', 0, 0, CURRENT_TIMESTAMP, 0, 5);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (293, 'Mitosis', 1, 74),
                                                                      (294, 'Meiosis', 0, 74),
                                                                      (295, 'Fagocitosis', 0, 74),
                                                                      (296, 'Fermentación', 0, 74);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (75, '¿Qué tipo de energía se obtiene a partir del calor interno de la Tierra?', 0, 0, CURRENT_TIMESTAMP, 0, 5);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (297, 'Geotérmica', 1, 75),
                                                                      (298, 'Solar', 0, 75),
                                                                      (299, 'Eólica', 0, 75),
                                                                       (300, 'Hidráulica', 0, 75);

-- Deportes
INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (76, '¿En qué deporte se utiliza un disco llamado puck?', 0, 0, CURRENT_TIMESTAMP, 0, 6);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (301, 'Hockey sobre hielo', 1, 76),
                                                                      (302, 'Fútbol americano', 0, 76),
                                                                      (303, 'Baloncesto', 0, 76),
                                                                      (304, 'Rugby', 0, 76);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (77, '¿Cuántos jugadores hay en un equipo de fútbol en cancha?', 0, 0, CURRENT_TIMESTAMP, 0, 6);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (305, '11', 1, 77),
                                                                      (306, '9', 0, 77),
                                                                      (307, '10', 0, 77),
                                                                      (308, '12', 0, 77);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (78, '¿En qué país se originaron los Juegos Olímpicos modernos?', 0, 0, CURRENT_TIMESTAMP, 0, 6);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (309, 'Grecia', 1, 78),
                                                                      (310, 'Italia', 0, 78),
                                                                      (311, 'Estados Unidos', 0, 78),
                                                                      (312, 'Francia', 0, 78);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (79, '¿Cuántos sets se juegan en un partido de tenis masculino en Grand Slam?', 0, 0, CURRENT_TIMESTAMP, 0, 6);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (313, '5', 1, 79),
                                                                      (314, '3', 0, 79),
                                                                      (315, '7', 0, 79),
                                                                      (316, '4', 0, 79);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (80, '¿Cuál es el deporte conocido como "el rey de los deportes"?', 0, 0, CURRENT_TIMESTAMP, 0, 6);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (317, 'Fútbol', 1, 80),
                                                                      (318, 'Baloncesto', 0, 80),
                                                                      (319, 'Béisbol', 0, 80),
                                                                      (320, 'Boxeo', 0, 80);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (81, '¿En qué deporte se utiliza un "tee"?', 0, 0, CURRENT_TIMESTAMP, 0, 6);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (321, 'Golf', 1, 81),
                                                                      (322, 'Cricket', 0, 81),
                                                                      (323, 'Fútbol americano', 0, 81),
                                                                      (324, 'Béisbol', 0, 81);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (82, '¿Quién es conocido como "El rey del boxeo"?', 0, 0, CURRENT_TIMESTAMP, 0, 6);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (325, 'Muhammad Ali', 1, 82),
                                                                      (326, 'Mike Tyson', 0, 82),
                                                                      (327, 'Floyd Mayweather', 0, 82),
                                                                      (328, 'Sugar Ray Robinson', 0, 82);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (83, '¿En qué ciudad se celebraron los Juegos Olímpicos de 2016?', 0, 0, CURRENT_TIMESTAMP, 0, 6);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (329, 'Río de Janeiro', 1, 83),
                                                                      (330, 'Londres', 0, 83),
                                                                      (331, 'Tokio', 0, 83),
                                                                      (332, 'Beijing', 0, 83);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (84, '¿Cuál es el país con más títulos mundiales de fútbol?', 0, 0, CURRENT_TIMESTAMP, 0, 6);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (333, 'Brasil', 1, 84),
                                                                      (334, 'Alemania', 0, 84),
                                                                      (335, 'Italia', 0, 84),
                                                                      (336, 'Argentina', 0, 84);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (85, '¿En qué deporte se usa una raqueta y una pelota amarilla pequeña?', 0, 0, CURRENT_TIMESTAMP, 0, 6);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (337, 'Tenis', 1, 85),
                                                                      (338, 'Bádminton', 0, 85),
                                                                      (339, 'Squash', 0, 85),
                                                                      (340, 'Ping pong', 0, 85);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (86, '¿Qué país es conocido por la tradición del sumo?', 0, 0, CURRENT_TIMESTAMP, 0, 6);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (341, 'Japón', 1, 86),
                                                                      (342, 'China', 0, 86),
                                                                      (343, 'Corea del Sur', 0, 86),
                                                                      (344, 'Mongolia', 0, 86);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (87, '¿Cuántos puntos vale un touchdown en fútbol americano?', 0, 0, CURRENT_TIMESTAMP, 0, 6);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (345, '6', 1, 87),
                                                                      (346, '3', 0, 87),
                                                                      (347, '7', 0, 87),
                                                                      (348, '1', 0, 87);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (88, '¿En qué deporte es famoso el trofeo "Wimbledon"?', 0, 0, CURRENT_TIMESTAMP, 0, 6);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (349, 'Tenis', 1, 88),
                                                                      (350, 'Golf', 0, 88),
                                                                      (351, 'Cricket', 0, 88),
                                                                      (352, 'Baloncesto', 0, 88);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (89, '¿En qué deporte se utiliza un casco con una visera y guantes especiales?', 0, 0, CURRENT_TIMESTAMP, 0, 6);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (353, 'Fútbol americano', 1, 89),
                                                                      (354, 'Béisbol', 0, 89),
                                                                      (355, 'Hockey sobre hielo', 0, 89),
                                                                      (356, 'Lacrosse', 0, 89);

INSERT INTO pregunta (id, enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
VALUES (90, '¿Cuál es el nombre del torneo anual más importante de rugby?', 0, 0, CURRENT_TIMESTAMP, 0, 6);
INSERT INTO respuesta (id, descripcion, es_correcta, id_pregunta) VALUES
                                                                      (357, 'Copa Mundial de Rugby', 1, 90),
                                                                      (358, 'Six Nations', 0, 90),
                                                                      (359, 'Super Rugby', 0, 90),
                                                                      (360, 'The Rugby Championship', 0, 90);
