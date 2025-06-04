<?php

class LobbyController
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
        $datosUsuarioLobby = $this->model->obtenerDatosUsuarioLobby($_SESSION["usuarioId"]);
        $ranking = $this->model->obtenerRankingGlobalOrdenado($datosUsuarioLobby);
        $puestoEnElRanking = $this ->model -> calcularPosicionEnElRankig ($datosUsuarioLobby, $ranking );
        $this->view->render("lobby", [ "datosUsuario" => $datosUsuarioLobby, "puestoRanking" => $puestoEnElRanking,  "showLogout" => true] );
    }



    private function redirectTo($str)
    {
        header("location:" . $str);
        exit();
    }

}