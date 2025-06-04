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

    public function  obtenerRankingGlobalOrdenado($idUsuario)
    {
        $ranking = [
            ["nickname" => "puchi", "puntaje" => 576],
            ["nickname" => "luketo", "puntaje" => 280],
            ["nickname" => "carli", "puntaje" => 310],
            ["nickname" => $idUsuario["nickname"], "puntaje" => $idUsuario["puntaje_alcanzado"]],
            ["nickname" => "tinto", "puntaje" => 250],
            ["nickname" => "nico", "puntaje" => 1],
        ];

        //  Ordenar el ranking de mayor a menor puntaje
        usort($ranking, function($a, $b) {
            return $b["puntaje"] - $a["puntaje"];
        });

        return $ranking;
    }

    public function calcularPosicionEnElRankig($idUsuario, $ranking){
        $posicion = null;
        foreach ($ranking as $index => $jugador) {
            if ($jugador["nickname"] === $idUsuario["nickname"]) {
                $posicion = $index + 1; // porque los arrays empiezan en 0
                break;
            }
        }

       return $posicion;
    }


}