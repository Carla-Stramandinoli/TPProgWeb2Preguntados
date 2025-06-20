<?php

class AdministradorController
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
        $this->view->render("administrador", ["showLogout" => true, "noEsJugador" => true]);
    }

    public function estadisticas()
    {
        $this->view->render("estadisticas", ["showLogout" => true, "noEsJugador" => true]);
    }
}