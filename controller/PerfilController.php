<?php

class PerfilController
{

    private $view;
    private $model;


    public function __construct($model ,$view)
    {
        $this->model = $model;
        $this->view = $view;
    }

    public function mostrar()
    {
        if(!isset($_SESSION['nickname'])) $this->redirectTo('/');

        $this->model->almacenarPuntajeAlcanzado($_SESSION["usuarioId"]);

        $datosUsuario = $this->model->obtenerDatosUsuario($_SESSION["usuarioId"]);

        $puntajesDePartidasDelJugador = $this->model->obtenerPuntajesDePartidasDelJugador($_SESSION["usuarioId"]);

        $puntajesDePartidaConIndices = $this->model->agregarIndicesAPartidasYPuntajes( $puntajesDePartidasDelJugador);


        $this->view->render("perfil", [ "datosUsuario" => $datosUsuario, "partidasYPuntajesUsuario" => $puntajesDePartidaConIndices, "showLogout" => true ]);
    }

    private function redirectTo($str)
    {
        header("location:" . $str);
        exit();
    }

}