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
        if (isset($_GET['msjError'])) $this->view->render('register', ['msjError' => $_GET['msjError']]);
        if (isset($_GET['msjExito'])) $this->view->render('register', ['msjExito' => $_GET['msjExito']]);
        if (!isset($_GET['msjExito']) && !isset($_GET['msjError'])) $this->view->render('register');
    }

    public function cerrarSesion() {
        session_destroy();
        $this->redirectTo("/ingreso/login");
    }

    public function loginValidator(){

        if (isset($_POST['nickname']) && isset($_POST['contrasenia'])){
            $usuarioId = $this->model->obtenerIdUsuario($_POST['nickname'], $_POST['contrasenia']);

            if(!empty($usuarioId[0]['id'])){
                $_SESSION['usuarioId'] = $usuarioId[0]['id'];
                $this->redirectTo("/lobby/home");
            }
        }

        $msjError = "Usuario o contraseña incorrectos";
        $this->redirectTo("/ingreso/login?msjError=" . urlencode($msjError));

    }

    public function registerValidator(){
        $nombreCompleto = isset($_POST['nombre-completo']) ? trim($_POST['nombre-completo']) : '';
        $fechaNacimiento = isset($_POST['fecha-nacimiento']) ? trim($_POST['fecha-nacimiento']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $nickname = isset($_POST['nickname']) ? trim($_POST['nickname']) : '';
        $contrasenia = isset($_POST['contrasenia']) ? trim($_POST['contrasenia']) : '';
        $contraseniaRepetida = isset($_POST['contrasenia-repetida']) ? trim($_POST['contrasenia-repetida']) : '';
        $genero = isset($_POST['genero']) ? trim($_POST['genero']) : '';

        if ($nombreCompleto
            && $fechaNacimiento
            && $email
            && $nickname
            && $contrasenia
            && $contraseniaRepetida
            && $genero
            && $_FILES['foto-perfil']['error'] === UPLOAD_ERR_OK){

            $informe = $this->model->registrarUsuario(
                        $nombreCompleto,
                        $fechaNacimiento,
                        $email,
                        $nickname,
                        $contrasenia,
                        $contraseniaRepetida,
                        $genero,
                        $_FILES['foto-perfil']);

            $msjExito ="";
            $msjError ="";

            ($informe === true)
                ? $msjExito = "Registro exitoso!"
                : $msjError = $informe;

            if (empty($msjExito)) $this->redirectTo("/ingreso/register?msjError=" . urlencode($msjError));
            else $this->redirectTo("/ingreso/register?msjExito=" . urlencode($msjExito));

        }else{
            $msjError = "Por favor complete todos los datos para el registro.";
            $this->redirectTo("/ingreso/register?msjError=" . urlencode($msjError));
        }
    }

    public function mostrar(){
        $this->login();
    }

    private function redirectTo($str)
    {
        header("location:" . $str);
        exit();
    }

}