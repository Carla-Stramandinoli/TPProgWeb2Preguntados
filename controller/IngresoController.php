<?php

class IngresoController
{
    private $model;
    private $view;

    private $emailSender;

    private $hostData;

    public function __construct($model, $view, $emailSender, $hostData)
    {
        $this->model = $model;
        $this->view = $view;
        $this->emailSender = $emailSender;
        $this->hostData = $hostData;
    }

    public function mostrar(){
        $this->login();
    }

    public function login(){
        if (isset($_GET['msjError'])) $this->view->render('login', ['msjError' => $_GET['msjError']]);
        if (isset($_GET['msjExito'])) $this->view->render('login', ['msjExito' => $_GET['msjExito']]);
        if (!isset($_GET['msjExito']) && !isset($_GET['msjError'])) $this->view->render('login');
    }

    public function register(){
        if (isset($_GET['msjError'])) $this->view->render('register', ['msjError' => $_GET['msjError']]);
        if (isset($_GET['msjExito'])) $this->view->render('register', ['msjExito' => $_GET['msjExito']]);
        if (!isset($_GET['msjExito']) && !isset($_GET['msjError'])) $this->view->render('register');
    }


    public function loginValidator(){

        if (isset($_POST['nickname']) && isset($_POST['contrasenia'])){
            $usuario = $this->model->obtenerUsuario($_POST['nickname'], $_POST['contrasenia']);


            if(!empty($usuario[0])){

                if(!($usuario[0]['rol'] ==  "administrador" ||  $usuario[0]['rol'] == "editor") && $usuario[0]['cuenta_activada'] == 0) {
                    $msjError = "El usuario esta inactivo, por favor verifique su casilla de correo.";
                    $this->redirectTo("/ingreso/login?msjError=" . urlencode($msjError));
                }

                $_SESSION['rol'] = $usuario[0]['rol'];
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

        $pais = isset($_POST['pais']) && !empty($_POST['pais']) ? trim($_POST['pais']) : 'Argentina';
        // coordenadas default de la unlam
        $latitud = isset($_POST['latitud']) && !empty($_POST['latitud']) ? trim($_POST['latitud']) : -34.67064;
        $longitud = isset($_POST['longitud']) && !empty($_POST['longitud']) ? trim($_POST['longitud']) : -58.562598;

        if ($nombreCompleto
            && $fechaNacimiento
            && $email
            && $nickname
            && $contrasenia
            && $contraseniaRepetida
            && $genero
            && $latitud
            && $longitud
            && $pais
            && $_FILES['foto-perfil']['error'] === UPLOAD_ERR_OK){

            $informe = $this->model->registrarUsuario(
                        $nombreCompleto,
                        $fechaNacimiento,
                        $email,
                        $nickname,
                        $contrasenia,
                        $contraseniaRepetida,
                        $genero,
                        $latitud,
                        $longitud,
                        $pais,
                        $_FILES['foto-perfil']);

            $msjExito ="";
            $msjError ="";

            if($informe === true){
                $msjExito = "Registro exitoso! Verifique su correo para activar su cuenta.";
                $usuario = $this->model->obtenerUsuario($nickname, $contrasenia);

                $this->mandarEmailDeValidacionDeCuenta($usuario[0]);

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

    public function validarCuenta(){
        $hash = isset($_GET['hash']) ? trim($_GET['hash']) : '';

        $resultado = $this->model->existeUsuarioConHash($hash);
        $idJugador = $this->model->obtenerUsuarioParaJugador($hash);

        $msj = '';

        if ($resultado){
            $this->model->activarJugador($idJugador);
            $msj='Validacion exitosa! Ahora puede loguearse.';
            $this->redirectTo("/ingreso/login?msjExito=" . urlencode($msj));
        }
        $msj='Esta intentando validar una cuenta que ya esta validada o no esta registrada en el sitio.';
        $this->redirectTo("/ingreso/register?msjError=" . urlencode($msj));
    }

    public function redirectTo($str)
    {
        header("location:" . $str);
        exit();
    }

    private function mandarEmailDeValidacionDeCuenta($usuario)
    {
        $body = $this->generadorDeBodyParaCorreo($usuario['nickname'], $usuario['nickname_hash']);
        $this->emailSender->send($usuario, $body);
    }

    private function generadorDeBodyParaCorreo($nickname, $nickname_hash)
    {
        return '<div style="max-width: 600px; margin: auto; padding: 20px; font-family: Arial, sans-serif; border: 1px solid #ccc; border-radius: 8px; text-align: center;">
                    <h1 style="color: #333;">¡Hola '.$nickname.'!</h1>
                    <h2 style="color: #333;"> Ya casi tienes tu cuenta, validala para poder iniciar sesión en nuestra página.</h2>
                    <a href="http://'.$this->hostData['host'].':'.$this->hostData['port'].'/ingreso/validarCuenta?hash='.$nickname_hash.'" 
                    style="background-color: #1e903c; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">¡Validar Cuenta!</a>
                </div>';
    }

}