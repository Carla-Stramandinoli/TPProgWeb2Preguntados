<?php

class JugarPartidaModel{


    private $database;
    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtenerPreguntasPorCategoria($categoria)
    {
        $resultado = $this->database->query("
                            SELECT pregunta.enunciado, categoria.descripcion,  pregunta.id 
                            FROM pregunta 
                            INNER JOIN categoria ON pregunta.id_categoria = categoria.id 
                            WHERE categoria.descripcion = '$categoria'");

        return isset($resultado[0]['descripcion']) ? $resultado[0]['descripcion'] : false;
    }

    /*public function obtenerArrayDeIds($preguntas)
    {
        $nums = [];
        foreach ($preguntas as $pregunta) {
            $nums [] = $pregunta['id'];
            }
        return $nums;
    }*/

    public function obtenerEnunciadoPregunta($descripcionCategoria)
    {
        /*foreach ($preguntas as $pregunta) {
            if( $pregunta['id'] == $num){
                return $pregunta;
            }
        }
        return null;*/
        $resultado = $this->database->query("SELECT enunciado FROM pregunta 
                                            JOIN categoria ON pregunta.id_categoria = categoria.id
                                            WHERE categoria.descripcion = '$descripcionCategoria' 
                                            ORDER BY RAND() 
                                            LIMIT 1");
        return isset($resultado[0]['enunciado']) ? $resultado[0]['enunciado'] : false;
    }

    public function obtenerIdPregunta($pregunta)
    {
        $resultado = $this->database->query("SELECT id FROM pregunta WHERE enunciado = '$pregunta'");
        return isset($resultado[0]['id']) ? $resultado[0]['id'] : false;
    }

    public function obtenerRespuestasPorPregunta($idPregunta) {
        //$id = $pregunta['id'];
        $result = $this->database->query("
                            SELECT descripcion
                            FROM respuesta
                            WHERE id_pregunta = '$idPregunta'");
        return $result;
    }

    public function validarRespuestaCorrecta($idPregunta, $respuesta)
    {
        $resultado = $this->database->query("SELECT descripcion 
                                            FROM respuesta
                                            WHERE es_correcta = 1 AND id_pregunta = '$idPregunta'");
        $enunciadoCorrecto = isset($resultado[0]['descripcion']) ? $resultado[0]['descripcion'] : false;
        if ($enunciadoCorrecto == $respuesta) {
            return 1;
        }
        return "Error";
    }

    public function actualizarPreguntaCantidadDeVecesJugadaMasUnoPorId($num)
    {
        $this->database->execute("UPDATE pregunta SET cantidad_jugada = cantidad_jugada + 1 WHERE id = $num");
    }

    public function elegirCategoriaRandom()
    {
        $categorias = ["Historia", "Ciencia", "Geografía", "Deportes", "Entretenimiento", "Arte"];
        $indiceElegido = array_rand($categorias);
        $categoria = $categorias[$indiceElegido];
        return $categoria;
    }

    public function actualizarPreguntaRespuestaExitosaMasUnoPorId($num)
    {
        $this->database->execute("UPDATE pregunta SET cantidad_aciertos = cantidad_aciertos + 1 WHERE id = $num");
    }

    public function crearInstanciaDePartida($id)
    {
        /*date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fechaActual = date("Y-m-d");*/
        $this->database->execute("INSERT INTO partida (fecha_partida, resultado, id_jugador) 
                                    VALUES (CURRENT_DATE(), 0, $id)");
    }

    public function actualizarPuntosPartida($idPartida)
    {
        $this->database->execute("UPDATE partida SET resultado = resultado + 1 WHERE id_partida = '$idPartida'");
    }

    public function obtenerPartidaPorJugador($id)
    {
        $resultado = $this->database->query("SELECT id_partida FROM partida WHERE id_jugador = $id ORDER BY fecha_partida DESC LIMIT 1");
        return isset($resultado[0]['id_partida']) ? $resultado[0]['id_partida'] : false;
    }

    public function almacenarPreguntaDePartidaEnTablaCompuesta($id_partida, $id)
    {
        $this->database->execute("INSERT INTO compuesta (id_partida, id_pregunta)
                                    VALUES ($id_partida, $id)");
    }
}