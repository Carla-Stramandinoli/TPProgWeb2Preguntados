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
        $sql = "
        SELECT COUNT(*) + 1 AS puesto
        FROM usuario u2
        JOIN jugador j2 ON j2.id = u2.id
        WHERE j2.puntaje_alcanzado > (
            SELECT j.puntaje_alcanzado 
            FROM jugador j 
            WHERE j.id = '$idUsuario'
        )
    ";
        $resultado = $this->database->query($sql);
        return $resultado[0]['puesto'] ?? null;
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