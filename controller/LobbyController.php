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
        $idUsuario = $_SESSION["usuarioId"];
        $datosUsuarioLobby = $this->model->obtenerDatosUsuarioLobby($idUsuario);
        $puestoRanking = $this->model->obtenerPuestoRanking($idUsuario);
        $rachaMasLarga = $this->model->obtenerRachaMasLarga($idUsuario);
        $historialPartidas = $this->model->obtenerHistorialPartidas($idUsuario);

        $this->view->render("lobby", [
            "datosUsuario" => $datosUsuarioLobby,
            "puestoRanking" => $puestoRanking,
            "rachaMasLarga" => $rachaMasLarga,
            "historialPartidas" => $historialPartidas,
            "showLogout" => true
        ]);
    }



    private function redirectTo($str)
    {
        header("location:" . $str);
        exit();
    }

}