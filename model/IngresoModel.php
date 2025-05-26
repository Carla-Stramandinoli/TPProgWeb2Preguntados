<?php

class IngresoModel
{

    private $database;
    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtenerIdUsuario($nickname, $contrasenia){
        if ($this->validarUsuario($nickname, $contrasenia)) return $this->database->query("SELECT id FROM usuario WHERE nickname = '$nickname'");
        else return [];
    }

    public function registrarUsuario($nombreCompleto,
                                     $fechaNacimiento,
                                     $email,
                                     $nickname,
                                     $contrasenia,
                                     $contraseniaRepetida,
                                     $genero,
                                     $fotoPerfil){

        $formatoEmailValido = $this->validarFormatoEmail($email);
        $emailLibre = $this->correoLibreEnLaBd($email);
        $nicknameValido = $this->validarNicknameNuevo($nickname);
        $contraseniaValida = $this->validarContraseniasIguales($contrasenia, $contraseniaRepetida);
        $imagenValida = $this->validarFormatoImagen($fotoPerfil);

        if ($formatoEmailValido && $emailLibre && $nicknameValido && $contraseniaValida && $imagenValida) {

            $imagenPerfilGuardada = $this->guardarFotoDePerfil($fotoPerfil);
            $contraseniaHasheada = password_hash($contrasenia, PASSWORD_DEFAULT);
            $fechaActual = date("Y-m-d");

            return $this->database->execute("INSERT INTO usuario (nickname, nombre_completo, anio_nacimiento, contrasenia, fecha_registro, foto_perfil, email, genero) 
                                      VALUES ('$nickname','$nombreCompleto', '$fechaNacimiento', '$contraseniaHasheada', '$fechaActual', '$imagenPerfilGuardada', '$email', '$genero')");
            // falta pasarle pais y ciudad cuando vemaos el mapa.
        }

        if(!$formatoEmailValido) return "El formato del email no es valido.";
        if(!$emailLibre) return "El email ya esta registrado en el sistema.";
        if(!$nicknameValido) return "El nickname ya lo usa otro usuario.";
        if(!$contraseniaValida) return "Las contrasenias no coinciden.";
        if(!$imagenValida) return "El formato de imagen no es valido.";

    }

    private function validarFormatoEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function correoLibreEnLaBd($email)
    {
        $emailDeLaBd = $this->database->query("SELECT email FROM usuario WHERE email = '$email'");
        return empty($emailDeLaBd);
    }

    private function validarNicknameNuevo($nickname)
    {
        $nicknameDeLaBd = $this->database->query("SELECT nickname FROM usuario WHERE nickname = '$nickname'");
        return empty($nicknameDeLaBd);
    }

    private function validarContraseniasIguales($contrasenia, $contraseniaRepetida)
    {
        return $contrasenia === $contraseniaRepetida;
    }

    private function validarFormatoImagen($imagen)
    {
        $imagenTemporal = $imagen['tmp_name'];
        return getimagesize($imagenTemporal);
    }

    private function guardarFotoDePerfil($fotoPerfil)
    {
        $nuevoNombre = pathinfo($fotoPerfil['name'], PATHINFO_FILENAME).'_'.time().'.'.pathinfo($fotoPerfil['name'], PATHINFO_EXTENSION); // le agrego el tiempo actual para que no se haya conflicto por si se sube alguna con mismo nombre
        $destino = $_SERVER['DOCUMENT_ROOT'].'/public/images/profiles/'.$nuevoNombre;
        move_uploaded_file($fotoPerfil['tmp_name'], $destino);
        return $nuevoNombre;
    }

    private function validarUsuario($nickname, $contrasenia)
    {
        $usuario = $this->database->query("SELECT contrasenia FROM usuario WHERE nickname = '$nickname'");
        return !empty($usuario) && password_verify($contrasenia, $usuario[0]["contrasenia"]);
    }
}