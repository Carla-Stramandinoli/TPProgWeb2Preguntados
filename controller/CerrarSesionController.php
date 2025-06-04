<?php

class CerrarSesionController
{

    public function __construct()
    {
    }

    public function mostrar() {
        $this->logout();
    }

    public function logout() {
        session_destroy();
        $this->redirectTo("/");
    }

    private function redirectTo($str)
    {
        header("location:" . $str);
        exit();
    }
}