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
        SELECT *
        FROM (
            SELECT j.id, j.puntaje_alcanzado,
                   ROW_NUMBER() OVER (ORDER BY j.puntaje_alcanzado DESC , id DESC) AS puesto
            FROM jugador j
        ) AS ranking
        WHERE id = '$idUsuario'
    ";
        $resultado = $this->database->query($sql);
        return isset($resultado[0]['puesto']) ? $resultado[0]['puesto'] : null;
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

    public function getDatabase()
    {
        return $this->database;
    }


}