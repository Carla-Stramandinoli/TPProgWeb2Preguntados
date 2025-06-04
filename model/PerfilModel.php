<?php

class PerfilModel
{
    private $database;
    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtenerDatosUsuario($idUsuario){
        return $this->database->query("SELECT nickname, nombre_completo, fecha_registro, email, foto_perfil, pais, ciudad, puntaje_alcanzado, jugador.id as 'IdJugador' 
                                        FROM usuario
                                        JOIN jugador ON usuario.id = jugador.id
                                        WHERE usuario.id='$idUsuario'");
    }

    //Esta funcion lo que hace es recorrer la tabla de partidas buscando todas las de un jugador para sumar los puntajes de las mismas y almacenarlo
    public function almacenarPuntajeAlcanzado($id_jugador)
    {
        $resultadoQuery = $this->database->query("SELECT SUM(resultado) as total FROM partida WHERE id_jugador='$id_jugador'");
        $puntajeTotal = isset($resultadoQuery[0]['total']) ? $resultadoQuery[0]['total'] : 0;
        $this->database->execute("UPDATE jugador
                                SET puntaje_alcanzado='$puntajeTotal'
                                WHERE id='$id_jugador'");
    }

    public function obtenerPuntajesDePartidasDelJugador($id_jugador)
    {
        return $this->database->query("SELECT resultado 
                                        FROM partida 
                                        WHERE id_jugador='$id_jugador'");
    }

    public function agregarIndicesAPartidasYPuntajes($partidasYPuntajes){
        $partidasYPuntajesConIndices = array();
        $indice= 1;
        foreach ($partidasYPuntajes as $registro) {
            $registro['indice'] = $indice++;
            $partidasYPuntajesConIndices[] = $registro;
        }
        return $partidasYPuntajesConIndices;
    }
}