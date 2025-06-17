<?php

class EditorModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtenerPreguntasExistentes(){
        return $this->database->query("SELECT id, enunciado FROM pregunta");
    }

    public function obtenerPreguntasSugeridas(){
        return $this->database->query("SELECT id_sugerencia, enunciado, respuesta_correcta, respuesta_1, respuesta_2, respuesta_3  FROM sugerencia");
    }

    public function obtenerPreguntasReportadas(){
        return $this->database->query("SELECT id, enunciado, cantidad_reportes FROM pregunta WHERE cantidad_reportes > 0 ORDER BY cantidad_reportes DESC");
    }
}