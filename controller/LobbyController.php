<?php

class LobbyController
{
    private $view;

    public function __construct($view)
    {
        $this->view = $view;
    }

    public function mostrar()
    {
        if(!isset($_SESSION['usuarioId'])) $this->redirectTo("/ingreso/login");
        $this->view->render("lobby", ["showLogout" => true]);
    }

    private function redirectTo($str)
    {
        header("location:" . $str);
        exit();
    }
}