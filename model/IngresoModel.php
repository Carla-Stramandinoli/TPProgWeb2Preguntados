<?php

class IngresoModel
{

    private $database;
    public function __construct($database)
    {
        $this->database = $database;
    }


    public function obtenerUsuario($nickname, $contrasenia){
        if (!$this->validarUsuario($nickname, $contrasenia)) return [];

        $usuario = $this->database->query("SELECT * FROM usuario WHERE nickname = '$nickname'");

        if ($usuario[0]['rol'] == 'jugador')
            return $this->database->query("SELECT * FROM usuario 
                                            JOIN jugador ON usuario.id = jugador.id 
                                            WHERE nickname = '$nickname'");

        else return $usuario;
    }

    public function obtenerUsuarioParaJugador($hash)
    {
        $resultado = $this->database->query("SELECT id FROM jugador WHERE nickname_hash = '$hash'");
        return isset($resultado[0]['id']) ? $resultado[0]['id'] : false;
    }

    public function existeUsuarioConHash($hash): bool
    {
        $usuario = $this->database->query("SELECT nickname_hash FROM jugador WHERE nickname_hash = '$hash'");
        return !empty($usuario);
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
            $nicknameHasheado = md5($nickname . time());
            date_default_timezone_set('America/Argentina/Buenos_Aires');
            $fechaActual = date("Y-m-d");



            $resultado = $this->database->execute("INSERT INTO usuario (nickname, nombre_completo, contrasenia) 
                                                VALUES ('$nickname', '$nombreCompleto', '$contraseniaHasheada')");
            // falta pasarle pais y ciudad cuando vemaos el mapa.
            $idUsuario = $this->obtenerIdUsuario($nickname);

            $this->registrarJugador($idUsuario, $fechaNacimiento, $fechaActual, $imagenPerfilGuardada, $email, $genero, $nicknameHasheado);
            return $resultado;
        }

        if(!$formatoEmailValido) return "El formato del email no es valido.";
        if(!$emailLibre) return "El email ya esta registrado en el sistema.";
        if(!$nicknameValido) return "El nickname ya lo usa otro usuario.";
        if(!$contraseniaValida) return "Las contrasenias no coinciden.";
        if(!$imagenValida) return "El formato de imagen no es valido.";

    }

    private function obtenerIdUsuario($nickname)
    {
        $resultado = $this->database->query("SELECT id FROM usuario WHERE nickname = '$nickname'");
        return isset($resultado[0]['id']) ? $resultado[0]['id'] : false;
    }

    public function registrarJugador($idUsuario, $fechaNacimiento, $fechaActual, $imagenPerfilGuardada, $email, $genero, $nicknameHasheado)
    {

           return $this->database->execute("INSERT INTO jugador (id, anio_nacimiento, fecha_registro, foto_perfil, 
                                                email, genero, nickname_hash, puntaje_alcanzado, qr, cantidad_jugada, cantidad_aciertos) 
                                            VALUES ($idUsuario, '$fechaNacimiento', '$fechaActual', '$imagenPerfilGuardada', 
                                                    '$email', '$genero', '$nicknameHasheado', 0, null, 0, 0)");
//        }
    }

    public function activarJugador($idJugador){
        $this->database->execute("UPDATE jugador 
                                    SET cuenta_activada = 1, nickname_hash = NULL 
                                    WHERE id = '$idJugador'");
    }

    private function validarFormatoEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function correoLibreEnLaBd($email)
    {
        $emailDeLaBd = $this->database->query("SELECT email FROM jugador WHERE email = '$email'");
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
        $usuario = $this->database->query("SELECT * FROM usuario WHERE nickname = '$nickname'");

        return !empty($usuario) && password_verify($contrasenia, $usuario[0]["contrasenia"]);
    }

    public function obtenerIdAdmin($idUsuario)
    {
        $resultado = $this->database->query("SELECT id FROM administrador WHERE id = '$idUsuario'");
        return isset($resultado[0]) ? $resultado[0] : false;
    }

    public function obtenerIdEditor($idUsuario)
    {
        $resultado = $this->database->query("SELECT id FROM editor WHERE id = '$idUsuario'");
        return isset($resultado[0]) ? $resultado[0] : false;
    }

}