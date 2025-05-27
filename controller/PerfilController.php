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
        if(!isset($_SESSION['usuarioId'])) $this->redirectTo('/');

        $datosUsuario = $this->model->obtenerDatosUsuario($_SESSION["usuarioId"]);
        $this->view->render("perfil", [ "datosUsuario" => $datosUsuario, "showLogout" => true]);
    }



    private function redirectTo($str)
    {
        header("location:" . $str);
        exit();
    }

}