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
        $datosUsuario["datosUsuario"] = $this->model->obtenerDatosUsuario($this->verificarSession());
        $this->view->render("perfil", $datosUsuario);
    }

    private function verificarSession(){
       $usuarioExiste = isset($_SESSION["usuarioId"]);

       if($usuarioExiste){
           $usuarioLogueado = $_SESSION["usuarioId"];
           return $usuarioLogueado;
       }
       header("location: /ingreso/login");
       exit();
    }

}