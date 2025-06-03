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
        $categoria = $this->model->elegirCategoriaRandom();
        $_SESSION["categoria_actual"] = $categoria;
        $this->model->crearInstanciaDePartida($_SESSION["usuarioId"]);

        $this-> view->render("jugarPartida" ,["showLogout" => true, "primerInicio" => true, "categoria" =>$categoria]);
    }

    public function categoria()
    {

        $descripcionCategoria = $_SESSION["categoria_actual"];
        //$_SESSION["pregunta_actual"];

        //$ids = $this->model->obtenerArrayDeIds($preguntas);
        //$num = $ids[array_rand($ids)];
        if (!isset($_SESSION["puntos"])) {
            $_SESSION["puntos"] = 0;
        }
        $puntos =  $_SESSION["puntos"];

        if (isset($_SESSION["pregunta_actual"])){
            $pregunta = $_SESSION["pregunta_actual"];
        } else {
            $pregunta = "";
        }
        $idPregunta = $this->model->obtenerIdPregunta($pregunta);

        if (!isset($_SESSION["pregunta_actual"])){
            $pregunta = $this->model->obtenerEnunciadoPregunta($descripcionCategoria, $_SESSION["usuarioId"]);
            $idPregunta = $this->model->obtenerIdPregunta($pregunta);
            $partidaActual = $this->model->obtenerPartidaPorJugador($_SESSION["usuarioId"]);

            $this->model->almacenarPreguntaDePartidaEnTablaCompuesta($partidaActual, $idPregunta);
            $this->model->almacenarPreguntasContestadasEnTablaContesta($_SESSION["usuarioId"], $idPregunta);

            $_SESSION["pregunta_actual"] = $pregunta;
        }
        if (!$pregunta) {
            die("No se encontró la pregunta con ID");
        }

        $respuestas = $this->model->obtenerRespuestasPorPregunta($idPregunta);

        // Mezclar las opciones
        shuffle($respuestas);

        if (!isset($_SESSION['inicio_pregunta'])){
            $_SESSION['inicio_pregunta'] = time();
        }

        // Renderizar con Mustache
        $this->view->render("pregunta", [
            "categoria" => $descripcionCategoria,
            "pregunta" => $pregunta,
            "id" => $idPregunta,
            "respuestas" => $respuestas,
            "puntos" => $puntos,
         "showLogout" => true] );
    }
    public function timeOut()
    {
        $puntos = $_SESSION["puntos"];
        unset($_SESSION['pregunta_actual']);

        $this-> view->render("perdiste" ,[
            "puntos" => $puntos,
            "showLogout" => true
        ]);
    }

    public function validarResultado()
    {
        $idPregunta = $_POST['pregunta_id'];
        $respuesta = $_POST['respuesta'];

        //Verificar
        $this->model->actualizarCantidadDeVecesJugadaPregunta($idPregunta);

        $resultado = $this->model->validarRespuestaCorrecta($idPregunta, $respuesta);
        if ($resultado == 1){
            $this->model->actualizarPuntosPartida($this->model->obtenerPartidaPorJugador($_SESSION["usuarioId"]));
        }

        unset($_SESSION['pregunta_actual']);

        $puntos = $_SESSION["puntos"];
        $tiempo_actual = time();
        if ($resultado==1 && $tiempo_actual - $_SESSION['inicio_pregunta'] <10){

            unset($_SESSION['inicio_pregunta']);

           //Verificar
           $this->model->actualizarRespuestaExitosaPregunta($idPregunta);

           //Este metodo tiene que actualizar la cantidad de preguntas que respondió bien un usuario
           $this->model->actualizarCantidadTotalPreguntasCorrectasJugador($_SESSION["usuarioId"]);

           $_SESSION["puntos"] += 1;
           $puntos = $_SESSION["puntos"];
           $categoria = $this->model->elegirCategoriaRandom();
           $_SESSION["categoria_actual"] = $categoria;
           $this-> view->render("jugarPartida" ,[
               "puntos" => $puntos,
               "showLogout" => true,
               "partidaEnCurso" => true,
               "categoria"=> $categoria]);
        } else{  $this-> view->render("perdiste" ,[
        "puntos" => $puntos,
        "showLogout" => true]);
        }
    }
}


