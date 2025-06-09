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
        $datosUsuarioActual = null;

        foreach ($resultado as $jugador) {
            $jugador["posicion"] = $posicion;
            $jugador["esUsuarioActual"] = ($jugador["id"] == $idUsuarioActual);
            $jugador["esPrimero"] = ($posicion === 1);
            $jugador["esSegundo"] = ($posicion === 2);
            $jugador["esTercero"] = ($posicion === 3);
            $jugador["posicionMenorA7"] = ($posicion <= 6);

            if ($jugador["esUsuarioActual"]) {
                $datosUsuarioActual = $jugador; // Guardamos la info para usar abajo
            }

            $ranking[] = $jugador;
            $posicion++;
        }

        return [
            "ranking" => $ranking,
            "datosUsuarioActual" => $datosUsuarioActual
        ];
    }

    public function getDatabase()
    {
        return $this->database;
    }
}
