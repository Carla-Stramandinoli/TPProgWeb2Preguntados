<?php

class IngresoController
{
    private $model;
    private $view;

    public function __construct($model, $view)
    {
        $this->model = $model;
        $this->view = $view;
    }

    public function login(){
        (isset($_GET['msjError'])) // verificar despues si se puede limpiar la ruta
            ? $this->view->render('login', ['msjError' => $_GET['msjError']])
            : $this->view->render('login');
    }

    public function register(){
        $this->view->render('register', array('msjError' => 'HOLA'));
    }

    public function loginValidator(){

        $usuarioId = $this->model->obtenerIdUsuario($_POST['nickname'], $_POST['contrasenia']);

        if(empty($usuarioId)){
            $msjError = "Usuario o contraseña incorrectos";
            header("Location: /ingreso/login?msjError=" . urlencode($msjError));
            exit();
        }

        $_SESSION['usuarioId'] = $usuarioId;
        header("Location: /lobby/home");
        exit();
    }

    public function registerValidator(){

    }
}