<?php

class JugarPartidaController{
    private $view;
    private $model;
    public function __construct($model, $view)
    {
        $this->view = $view;
        $this->model = $model;

    }

    public function mostrar()
    {
        $this-> view->render("jugarPartida" ,["showLogout" => true]);
    }

    public function categoria()
    {
        $categoria = isset($_GET['cat']) ? $_GET['cat'] : '';


        error_log("Categoría recibida: " . $_GET['cat']);

        $preguntas = $this->model->obtenerPreguntasPorCategoria($categoria);

        $ids = $this->model->obtenerArrayDeIds($preguntas);
        $num = $ids[array_rand($ids)];
        $puntos = 0;

        $pregunta = $this->model->obtenerPreguntaPorId($num, $preguntas);
        if (!$pregunta) {
            die("No se encontró la pregunta con ID $num");
        }
        $respuestas = $this->model->obtenerRespuestasPorPregunta($pregunta);
        // Mezclar las opciones

        shuffle($respuestas);

        // Renderizar con Mustache
        $this->view->render("pregunta", [
            "categoria" => $categoria,
            "pregunta" => $pregunta["enunciado"],
            "id" => $num,
            "respuestas" => $respuestas,
            "puntos" => $puntos,
         "showLogout" => true] );
    }

    public function validarResultado()
    {
       $idPregunta = $_POST['pregunta_id'];
       $respuesta = $_POST['respuesta'];

       $resultado = $this->model->validarRespuestaCorrecta($idPregunta,$respuesta);

       $this->model->actualizarPreguntaCantidadDeVecesJugadaMasUnoPorId($idPregunta);
       if ($resultado==1){
           $this->model->actualizarPreguntaRespuestaExitosaMasUnoPorId($idPregunta);
           $this->view->render("ganaste", [
               "showLogout" => true] );
       }    else  echo "Perdisteeee manquito";

    }
}


