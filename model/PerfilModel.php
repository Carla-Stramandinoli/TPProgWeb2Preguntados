<?php

class PerfilModel
{
    private $database;
    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtenerDatosUsuario($idUsuario){
        return $this->database->query("SELECT nickname, nombre_completo, fecha_registro, email, foto_perfil, pais, ciudad FROM usuario WHERE id='$idUsuario'");
    }
}