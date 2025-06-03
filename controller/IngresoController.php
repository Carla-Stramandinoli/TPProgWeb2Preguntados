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
        if(isset($_SESSION['nickname'])) $this->redirectTo('/');
        (isset($_GET['msjError'])) // verificar despues si se puede limpiar la ruta
            ? $this->view->render('login', ['msjError' => $_GET['msjError']])
            : $this->view->render('login');
    }

    public function register(){
        if(isset($_SESSION['nickname'])) $this->redirectTo('/');
        if (isset($_GET['msjError'])) $this->view->render('register', ['msjError' => $_GET['msjError']]);
        if (isset($_GET['msjExito'])) $this->view->render('register', ['msjExito' => $_GET['msjExito']]);
        if (!isset($_GET['msjExito']) && !isset($_GET['msjError'])) $this->view->render('register');
    }

    public function cerrarSesion() {
        session_destroy();
        $this->redirectTo("/");
    }

    public function loginValidator(){

        if (isset($_POST['nickname']) && isset($_POST['contrasenia'])){
            $usuario = $this->model->obtenerUsuario($_POST['nickname'], $_POST['contrasenia']);


            if(!empty($usuario[0])){
                $idUsuario = $usuario[0]['id'];

                if (!empty($this->model->obtenerIdAdmin($idUsuario))){
                    $this->redirectTo('/administrador/mostrar');
                } elseif (!empty($this->model->obtenerIdEditor($idUsuario))){
                    $this->redirectTo('/editor/mostrar');
                }

                if($usuario[0]['cuenta_activada'] == 0) {
                    $msjError = "El usuario esta inactivo, por favor verifique su casilla de correo.";
                    $this->redirectTo("/ingreso/login?msjError=" . urlencode($msjError));
                }

                $_SESSION['nickname'] = $usuario[0]['nickname'];
                $_SESSION['usuarioId'] = $usuario[0]['id'];

                $this->redirectTo("/");
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

            if($informe === true){
                $msjExito = "Registro exitoso! Verifique su correo para activar su cuenta.";
                $usuario = $this->model->obtenerUsuario($nickname, $contrasenia);

                // mandar mail $usuario['nickname_hash']

                $this->redirectTo("/ingreso/activar?hash=" . $usuario[0]['nickname_hash']); // esto lo pasara el html del correo por post.

            } else {
                $msjError = $informe;
            }


            if (empty($msjExito)) $this->redirectTo("/ingreso/register?msjError=" . urlencode($msjError));
            else $this->redirectTo("/ingreso/register?msjExito=" . urlencode($msjExito));

        }else{
            $msjError = "Por favor complete todos los datos para el registro.";
            $this->redirectTo("/ingreso/register?msjError=" . urlencode($msjError));
        }
    }

    public function activar(){
        $hash = isset($_GET['hash']) ? trim($_GET['hash']) : '';

        $this->view->render('activar', array('hash' => $hash));
    }

    public function validarCuenta(){
        $hash = isset($_POST['hash']) ? trim($_POST['hash']) : '';

        if ($this->model->existeUsuarioConHash($hash)){
            $this->model->registrarJugador($hash);
            $this->model->activarUsuario($hash);
        }

        $this->redirectTo("/ingreso/login");
    }

    public function redirectTo($str)
    {
        header("location:" . $str);
        exit();
    }

}