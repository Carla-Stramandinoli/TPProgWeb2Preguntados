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
        $_SESSION["puntos"] = 0;
        $this-> view->render("jugarPartida" ,["showLogout" => true, "primerInicio" => true]);
    }



    public function categoria()
    {
        $categoria = isset($_GET['cat']) ? $_GET['cat'] : '';


        error_log("Categoría recibida: " . $_GET['cat']);

        $preguntas = $this->model->obtenerPreguntasPorCategoria($categoria);

        $ids = $this->model->obtenerArrayDeIds($preguntas);
        $num = $ids[array_rand($ids)];
        if (!isset($_SESSION["puntos"])) {
            $_SESSION["puntos"] = 0;
        }
        $puntos =  $_SESSION["puntos"];
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
        $trofeos = "no tenes";
        $racha = "22 preguntas";
        $puntos = $_SESSION["puntos"];
       if ($resultado==1){
           $this->model->actualizarPreguntaRespuestaExitosaMasUnoPorId($idPregunta);
           $_SESSION["puntos"] += 1;
           $puntos = $_SESSION["puntos"];
           $trofeos = "no tenes";
           $racha = "22 preguntas";
           $this-> view->render("jugarPartida" ,[
               "puntos" => $puntos,
               "trofeos" => $trofeos,
               "racha" => $racha,
               "showLogout" => true,
               "partidaEnCurso" => true]);
       } else{  $this-> view->render("perdiste" ,[
        "puntos" => $puntos,
        "trofeos" => $trofeos,
        "racha" => $racha,
        "showLogout" => true]);
       }
    }
}


