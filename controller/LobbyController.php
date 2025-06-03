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
        $this->view->render("lobby", [ "datosUsuario" => $datosUsuarioLobby,  "showLogout" => true] );
    }

    private function redirectTo($str)
    {
        header("location:" . $str);
        exit();
    }

}