<?php

class VerRankingModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtenerRankingGlobalCompleto($idUsuarioActual)
    {
        $query = "SELECT usuario.id, nickname, puntaje_alcanzado, foto_perfil 
              FROM usuario 
              JOIN jugador ON jugador.id = usuario.id 
              ORDER BY puntaje_alcanzado DESC";

        $resultado = $this->database->query($query);

        $ranking = [];
        $posicion = 1;

        foreach ($resultado as $jugador) {
            $jugador["posicion"] = $posicion;
            $jugador["esUsuarioActual"] = ($jugador["id"] == $idUsuarioActual);
            $ranking[] = $jugador;
            $posicion++;
        }

        return $ranking;
    }


}