<?php

class EditorController
{

    private $view;
    private $model;


    public function __construct($model, $view)
    {
        $this->model = $model;
        $this->view = $view;
    }

    public function mostrar()
    {
        $preguntasExistentes = $this->mostrarPreguntasExistentes();
        $preguntasSugeridas = $this->mostrarPreguntasSugeridas();
        $preguntasReportadas = $this->mostrarPreguntasReportadas();

        $this->view->render("editor", ["showLogout" => true, "noEsJugador" => true,
            "preguntasExistentes" => $preguntasExistentes, "preguntasSugeridas" => $preguntasSugeridas,
            "preguntasReportadas" => $preguntasReportadas]);
    }

    public function mostrarPreguntasExistentes()
    {
        return $this->model->obtenerPreguntasExistentes();
    }

    public function mostrarPreguntasSugeridas()
    {
        return $this->model->obtenerPreguntasSugeridas();
    }

    public function mostrarPreguntasReportadas()
    {
        return $this->model->obtenerPreguntasReportadas();
    }
}