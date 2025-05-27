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
        (isset($_SESSION['usuarioId']))
            ? $this->view->render("lobby", ["showLogout" => true])
            : $this->redirectTo('/');
    }

    private function redirectTo($str)
    {
        header("location:" . $str);
        exit();
    }

}