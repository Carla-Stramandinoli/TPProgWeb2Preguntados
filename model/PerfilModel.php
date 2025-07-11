<?php

class PerfilModel
{
    private $database;
    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtenerDatosUsuario($idUsuario){
        return $this->database->query("SELECT u.id, u.nickname, u.nombre_completo, j.fecha_registro, j.email, j.foto_perfil, j.latitud, j.longitud, j.puntaje_alcanzado
                                        FROM usuario u
                                        JOIN jugador j ON u.id = j.id
                                        WHERE u.id='$idUsuario'");
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

    public function obtenerRachaMasLarga($idUsuario) {

        $sql = "
        SELECT MAX(p.resultado) AS racha
        FROM jugador j JOIN partida p ON j.id = p.id_jugador
        WHERE j.id = '$idUsuario'
        ";
        $resultado = $this->database->query($sql);
        return isset($resultado[0]['racha']) ? $resultado[0]['racha'] : 0;
    }
}