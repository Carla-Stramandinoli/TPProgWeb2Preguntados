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
        $datosUsuario = $this->model->obtenerDatosUsuario($this->verificarSession());
        $this->view->render("perfil", [ "datosUsuario" => $datosUsuario, "showLogout" => true]);
    }

    private function verificarSession(){
       $usuarioExiste = isset($_SESSION["usuarioId"]);

       if($usuarioExiste) return $_SESSION["usuarioId"];

       $this->redirectTo("/ingreso/login");
    }

    private function redirectTo($str)
    {
        header("location:" . $str);
        exit();
    }

}