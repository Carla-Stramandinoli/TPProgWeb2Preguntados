<?php

class JugarPartidaModel{
    private $database;
    public function __construct($database)
    {
        $this->database = $database;
    }


    public function obtenerPreguntasPorCategoria($categoria)
    {
        $result = $this->database->query("
                            SELECT pregunta.enunciado, categoria.descripcion,  pregunta.id 
                            FROM pregunta 
                            INNER JOIN categoria ON pregunta.id_categoria = categoria.id 
                            WHERE categoria.descripcion = '$categoria'");

        return $result;
    }
    public function obtenerArrayDeIds($preguntas)
    {
        $nums = [];
        foreach ($preguntas as $pregunta) {
            $nums [] = $pregunta['id'];
            }
        return $nums;
    }
    public function obtenerPreguntaPorId($num, $preguntas)
    {
        foreach ($preguntas as $pregunta) {
            if( $pregunta['id'] == $num){
                return $pregunta;
            }

        }
        return null;
    }
    public function obtenerRespuestasPorPregunta($pregunta) {
        $id = $pregunta['id'];
        $result = $this->database->query("
                            SELECT descripcion
                            FROM respuesta
                            WHERE id_pregunta = '$id'");

        return $result;
    }

    public function validarRespuestaCorrecta($num, $descripcion)
    {
        $result = $this->database->query("
                            SELECT es_correcta
                            FROM respuesta
                            WHERE id_pregunta = '$num'
                            AND descripcion = '$descripcion'");

        return $result[0]['es_correcta'];
    }




}