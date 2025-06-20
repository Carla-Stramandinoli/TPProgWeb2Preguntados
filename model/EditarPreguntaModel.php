<?php

class EditarPreguntaModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtenerDatosPreguntaYRespuesta($idPregunta)
    {
        return $this->database->query("SELECT P.enunciado, P.id_categoria as categoria, R.descripcion, R.es_correcta, R.id
                                        FROM respuesta R JOIN pregunta P ON R.id_pregunta = P.id WHERE R.id_pregunta = '$idPregunta'");
    }

    public function actualizarPreguntaYRespuestasModel($datosPregunta)
    {
        $this->actualizarPregunta($datosPregunta['enunciado'], $datosPregunta['categoria'], $datosPregunta['id']);

        $this->actualizarRespuestas($datosPregunta['respuesta_correcta'], $datosPregunta['idRespuestaCorrecta']);
        $this->actualizarRespuestas($datosPregunta['respuesta_1'], $datosPregunta['idRespuesta_1']);
        $this->actualizarRespuestas($datosPregunta['respuesta_2'], $datosPregunta['idRespuesta_2']);
        $this->actualizarRespuestas($datosPregunta['respuesta_3'], $datosPregunta['idRespuesta_3']);

        return true;
    }

    public function actualizarRespuestas($respuesta, $idRespuesta)
    {
        $this->database->execute("UPDATE respuesta SET descripcion = '$respuesta' WHERE id = '$idRespuesta'");
    }

    public function actualizarPregunta($enunciado, $categoria, $idPregunta)
    {
        $this->database->execute("UPDATE pregunta 
                      SET enunciado = '$enunciado', id_categoria = '$categoria' 
                      WHERE id = '$idPregunta'");
    }
}