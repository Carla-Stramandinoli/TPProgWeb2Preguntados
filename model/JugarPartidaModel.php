<?php

class JugarPartidaModel{


    private $database;
    public function __construct($database)
    {
        $this->database = $database;
    }


    public function obtenerPreguntasPorCategoria($categoria) {
        $json = file_get_contents('./public/preguntas_snk_completas.json');
        $preguntasPorCategoria = json_decode($json, true);

        return isset($preguntasPorCategoria[$categoria]) ? $preguntasPorCategoria[$categoria] : [];
    }


}