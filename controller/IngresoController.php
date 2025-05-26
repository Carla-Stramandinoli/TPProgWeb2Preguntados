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
        header("Location: /ingreso/login");
        exit();
    }

    public function loginValidator(){

        if (isset($_POST['nickname']) && isset($_POST['contrasenia'])){
            $usuarioId = $this->model->obtenerIdUsuario($_POST['nickname'], $_POST['contrasenia']);

            if(!empty($usuarioId[0]['id'])){
                $_SESSION['usuarioId'] = $usuarioId[0]['id'];
                header("Location: /lobby/home");
                exit();
            }
        }

        $msjError = "Usuario o contraseña incorrectos";
        header("Location: /ingreso/login?msjError=" . urlencode($msjError));
        exit();

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

            if (empty($msjExito)){
                header ("Location: /ingreso/register?msjError=" . urlencode($msjError));
                exit();
            } else{
                header ("Location: /ingreso/register?msjExito=" . urlencode($msjExito));
                exit();
            }
        }else{
            $msjError = "Por favor complete todos los datos para el registro.";
            header ("Location: /ingreso/register?msjError=" . urlencode($msjError));
            exit();
        }
    }
}