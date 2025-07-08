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
        $_SESSION['result'] = 0;
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

    public function jugar()
    {
        if(!isset($_SESSION['id_partida_actual'])){ $this->redirectTo('/jugarPartida/mostrar');}
        $_SESSION['result'] = 0;
        if (!isset($_SESSION["puntos"])) {$_SESSION["puntos"] = 0;}

        if (isset($_SESSION["pregunta_actual"])){
            $pregunta = $_SESSION["pregunta_actual"];
            $idPregunta = $this->model->obtenerIdPregunta($pregunta);
        } else {
            $pregunta = "";
        }

        if (!isset($_SESSION["pregunta_actual"])){
            $pregunta = $this->model->obtenerEnunciadoPregunta($_SESSION["categoria_actual"], $_SESSION["usuarioId"]);
            $idPregunta = $this->model->obtenerIdPregunta($pregunta);
            $this->model->almacenarPreguntasContestadasEnTablaContesta($_SESSION["usuarioId"], $idPregunta);
            $this->model->actualizarCantidadTotalPreguntasJugador($_SESSION["usuarioId"]);
            $_SESSION["pregunta_actual"] = $pregunta;
        }
        if (!$pregunta) {die("No se encontró la pregunta con ID");}
        $respuestas = $this->model->obtenerRespuestasPorPregunta($idPregunta);

        $_SESSION['preguntas_array']= $respuestas;
        $_SESSION['ultimo_enunciado'] = $pregunta;

         if (!isset($_SESSION['inicio_pregunta'])){$_SESSION['inicio_pregunta'] = time();}

        $this->view->render("pregunta", [
            "categoria" => $_SESSION["categoria_actual"],
            "pregunta" => $pregunta,
            "id" => $idPregunta,
            "respuestas" => $respuestas,
            "puntos" => $_SESSION["puntos"],
            "showLogout" => true] );
    }
    public function timeOut()
    {
        $puntos = $_SESSION["puntos"];
        unset($_SESSION['pregunta_actual']);
        $racha = $this->model->obtenerRachaMasLargaJugador($_SESSION["usuarioId"]);
        unset($_SESSION['id_partida_actual']);
        unset($_SESSION['inicio_pregunta']);
        $_SESSION['result'] = 0;

        $this-> view->render("perdiste" ,[
            "puntos" => $puntos,
            "racha" => $racha,
            "showLogout" => true
        ]);
    }

    public function validarResultado()
    {
        if (!$_POST['respuesta']) $this->redirectTo('/jugarPartida/timeOut');

        $idPreguntaEnviada = $_POST['pregunta_id'];
        
        if (!isset($_SESSION["pregunta_actual"])) {
            $this->redirectTo('/jugarPartida/timeOut');
        }
        
        $idPreguntaActual = $this->model->obtenerIdPregunta($_SESSION["pregunta_actual"]);
        
        if ($idPreguntaEnviada != $idPreguntaActual) {
            $this->redirectTo('/jugarPartida/timeOut');
        }

        $respuestaTemporal = $_POST['respuesta'];
        
        $respuestaReal = null;
        foreach ($_SESSION['preguntas_array'] as $respuesta) {
            if ($respuesta['id_temporal'] == $respuestaTemporal) {
                $respuestaReal = $respuesta['id_real'];
                break;
            }
        }
        
        if ($respuestaReal === null) {
            $this->redirectTo('/jugarPartida/timeOut');
        }
        
        $_SESSION['ultima_respuesta'] = $respuestaTemporal;

        $this->model->actualizarCantidadDeVecesJugadaPregunta($idPreguntaActual);
        $resultado = $this->model->validarRespuestaCorrecta($idPreguntaActual, $respuestaReal);
        $tiempo_actual = time();
        unset($_SESSION["pregunta_actual"]);

        if ($resultado==1 && $tiempo_actual - $_SESSION['inicio_pregunta'] <10){
            $this->model->actualizarPuntosPartida($this->model->obtenerPartidaPorJugador($_SESSION["usuarioId"]));
            $_SESSION['result'] = 1;
            unset($_SESSION['inicio_pregunta']);
            $this->model->actualizarRespuestaExitosaPregunta($idPreguntaActual);
            $this->model->actualizarCantidadTotalPreguntasCorrectasJugador($_SESSION["usuarioId"]);
            $this->model->almacenarPuntajeAlcanzado($_SESSION["usuarioId"]);
            $_SESSION["puntos"] += 1;
        } else {
            unset($_SESSION['inicio_pregunta']);
            unset($_SESSION['id_partida_actual']);
        }

        header("Location: /jugarPartida/result");
        exit;
    }
    public function result()
    {
        $respuestaElegida = $_SESSION['ultima_respuesta'] ;
        
        $respuestasParaMostrar = [];
        foreach ($_SESSION['preguntas_array'] as $respuesta) {
            $respuestasParaMostrar[] = [
                'descripcion' => $respuesta['descripcion'],
                'id_respuesta' => $respuesta['id_temporal'],
                'es_correcta' => $respuesta['es_correcta']
            ];
        }
        
        $respuestas = $this->model->obtenerArrayDeRespuestasParaMostrar($respuestaElegida, $respuestasParaMostrar);
        $idPregunta = $this->model->obtenerIdPregunta($_SESSION['ultimo_enunciado']);

        $this->view->render("resultado", [
            "categoria" => $_SESSION["categoria_actual"],
            "pregunta" => $_SESSION['ultimo_enunciado'],
            "respuestas" => $respuestas,
            "puntos" => $_SESSION["puntos"],
            "id" => $idPregunta,
            "showLogout" => true] );
    }
    public function redirect()
    {
        $racha = $this->model->obtenerRachaMasLargaJugador($_SESSION["usuarioId"]);

        if ( $_SESSION['result'] ==1){
            unset($_SESSION['inicio_pregunta']);
            $categoria = $this->model->elegirCategoriaRandom();
            $_SESSION["categoria_actual"] = $categoria;
            $this-> view->render("jugarPartida" ,[
                "puntos" => $_SESSION["puntos"],
                "showLogout" => true,
                "racha" => $racha,
                "partidaEnCurso" => true,
                "categoria"=> $categoria]);
        } else{
            unset($_SESSION['inicio_pregunta']);
            unset($_SESSION['id_partida_actual']);
            $this-> view->render("perdiste" ,[
                "puntos" => $_SESSION["puntos"],
                "racha" => $racha,
                "showLogout" => true]);
        }
    }

    public function reportarPreguntaController(){
        $idPregunta = $_POST['id'] ?? null;

        $this->model->reportarPreguntaModel($idPregunta);

        return $this->redirect();
    }

}