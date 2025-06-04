<?php

class JugarPartidaController{
    private $view;
    private $model;
    public function __construct($model, $view)
    {
        $this->view = $view;
        $this->model = $model;

    }

    private function redirectTo($str)
    {
        header("location:" . $str);
        exit();
    }
    public function mostrar()
    {

        $categoria = $this->model->elegirCategoriaRandom();
        $_SESSION["categoria_actual"] = $categoria;

        if(isset($_SESSION['pregunta_actual'])){
            $this->redirectTo('/jugarPartida/timeOut');
        }
        if (isset($_SESSION['id_partida_actual'])) {
            $puntos = $_SESSION["puntos"];
            $racha = $this->model->obtenerRachaMasLargaJugador($_SESSION["usuarioId"]);
            $this->view->render("jugarPartida", [
                "puntos" => $puntos,
                "showLogout" => true,
                "racha" => $racha,
                "partidaEnCurso" => true,
                "categoria" => $categoria]);
        } else {
            $_SESSION["puntos"] = 0;
            $this->model->crearInstanciaDePartida($_SESSION["usuarioId"]);
            $_SESSION['id_partida_actual'] = $this->model->obtenerPartidaPorJugador($_SESSION["usuarioId"]);
            $this-> view->render("jugarPartida" ,["showLogout" => true, "primerInicio" => true, "categoria" =>$categoria]);
        }

    }

    public function categoria()
    {

        $descripcionCategoria = $_SESSION["categoria_actual"];

        if (!isset($_SESSION["puntos"])) {
            $_SESSION["puntos"] = 0;
        }
        $puntos =  $_SESSION["puntos"];

        if (isset($_SESSION["pregunta_actual"])){
            $pregunta = $_SESSION["pregunta_actual"];
            $idPregunta = $this->model->obtenerIdPregunta($pregunta);
        } else {
            $pregunta = "";
        }


        if (!isset($_SESSION["pregunta_actual"])){
            $pregunta = $this->model->obtenerEnunciadoPregunta($descripcionCategoria, $_SESSION["usuarioId"]);
            $idPregunta = $this->model->obtenerIdPregunta($pregunta);

            $this->model->almacenarPreguntasContestadasEnTablaContesta($_SESSION["usuarioId"], $idPregunta);

            $_SESSION["pregunta_actual"] = $pregunta;
        }
        if (!$pregunta) {
            die("No se encontró la pregunta con ID");
        }

        $respuestas = $this->model->obtenerRespuestasPorPregunta($idPregunta);

        $this->model->actualizarCantidadTotalPreguntasJugador($_SESSION["usuarioId"]);

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
        $racha = $this->model->obtenerRachaMasLargaJugador($_SESSION["usuarioId"]);
        unset($_SESSION['id_partida_actual']);
        unset($_SESSION['inicio_pregunta']);

        $this-> view->render("perdiste" ,[
            "puntos" => $puntos,
            "racha" => $racha,
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
        $racha = $this->model->obtenerRachaMasLargaJugador($_SESSION["usuarioId"]);

        if ($resultado==1 && $tiempo_actual - $_SESSION['inicio_pregunta'] <10){

            unset($_SESSION['inicio_pregunta']);

            //Verificar
            $this->model->actualizarRespuestaExitosaPregunta($idPregunta);

            //Este metodo tiene que actualizar la cantidad de preguntas que respondiÃ³ bien un usuario
            $this->model->actualizarCantidadTotalPreguntasCorrectasJugador($_SESSION["usuarioId"]);

            $_SESSION["puntos"] += 1;
            $puntos = $_SESSION["puntos"];
            $categoria = $this->model->elegirCategoriaRandom();
            $_SESSION["categoria_actual"] = $categoria;
            $this-> view->render("jugarPartida" ,[
                "puntos" => $puntos,
                "showLogout" => true,
                "racha" => $racha,
                "partidaEnCurso" => true,
                "categoria"=> $categoria]);
        } else{
            unset($_SESSION['inicio_pregunta']);
            unset($_SESSION['id_partida_actual']);
            $this-> view->render("perdiste" ,[
                "puntos" => $puntos,
                "racha" => $racha,
                "showLogout" => true]);
        }
    }
}