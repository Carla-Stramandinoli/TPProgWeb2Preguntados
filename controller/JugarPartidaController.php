<?php

class JugarPartidaController{
    private $view;
    private $model;
    public function __construct($model, $view)
    {
        $this->view = $view;
        $this->model = $model;

    }

    public function mostrar()
    {
        $this-> view->render("jugarPartida" ,["showLogout" => true]);
    }


}


