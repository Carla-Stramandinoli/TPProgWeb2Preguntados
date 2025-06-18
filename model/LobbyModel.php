<?php

class LobbyModel  {

    private $database;
    public function __construct($database)
    {
        $this->database = $database;
    }
    public function obtenerDatosUsuarioLobby($idUsuario){
        $resultado = $this->database->query("SELECT nickname, nombre_completo, fecha_registro, email, foto_perfil, pais, ciudad, puntaje_alcanzado  
                                         FROM usuario 
                                         JOIN jugador ON jugador.id = usuario.id 
                                         WHERE usuario.id='$idUsuario'");
        return $resultado[0] ; // Esto saca el array interno que sí tiene las claves
    }

    public function obtenerPuestoRanking($idUsuario) {

//        $query = "SELECT usuario.id, usuario.nickname, MAX(partida.resultado) AS racha, jugador.foto_perfil
//                  FROM usuario
//                  JOIN jugador ON jugador.id = usuario.id
//                  JOIN partida ON partida.id_jugador = jugador.id
//                  GROUP BY jugador.id
//                  ORDER BY racha DESC, id DESC";

        $sql = "
        SELECT *
        FROM (
            SELECT j.id, MAX(p.resultado) AS racha,
                   ROW_NUMBER() OVER (ORDER BY racha DESC, j.puntaje_alcanzado DESC , j.id DESC) AS puesto
            FROM jugador j JOIN partida p ON p.id_jugador = j.id
            GROUP BY j.id
        ) AS ranking
        WHERE id = '$idUsuario'
    ";
        $resultado = $this->database->query($sql);
        return isset($resultado[0]['puesto']) ? $resultado[0]['puesto'] : null;
    }

    public function obtenerRachaMasLarga($idUsuario) {

        $sql = "
        SELECT MAX(p.resultado) AS racha
        FROM jugador j JOIN partida p ON j.id = p.id_jugador
        WHERE j.id = '$idUsuario'
        ";
        $resultado = $this->database->query($sql);
        return isset($resultado[0]['racha']) ? $resultado[0]['racha'] : null;
    }


    public function obtenerHistorialPartidas($idUsuario) {
        $sql = "
        SELECT id_jugador, resultado, fecha_partida
        FROM partida
        WHERE id_jugador = '$idUsuario'
        ORDER BY fecha_partida DESC
        LIMIT 10
    ";
        return $this->database->query($sql);
    }

    public function registrarPreguntaSugerida($idJugador, $enunciadoSugerido, $respuestaCorrecta, $respuestaIncorrecta1, $respuestaIncorrecta2, $respuestaIncorrecta3, $categoria) {
        return $this->database->execute("INSERT INTO sugerencia (id_jugador, enunciado, respuesta_correcta, respuesta_1, respuesta_2, respuesta_3, categoria) 
                                    VALUES ('$idJugador', '$enunciadoSugerido', '$respuestaCorrecta', '$respuestaIncorrecta1', '$respuestaIncorrecta2', '$respuestaIncorrecta3', '$categoria')");
    }

    public function getDatabase()
    {
        return $this->database;
    }


}