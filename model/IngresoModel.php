<?php

class IngresoModel
{

    private $database;
    public function __construct($database)
    {
        $this->database = $database;

    }

    public function obtenerIdUsuario($nickname, $contrasenia){
        if (!isset($nickname) || !isset($contrasenia)) return [];
        else return $this->database->query("SELECT id FROM usuario WHERE nickname = '$nickname' AND contrasenia = '$contrasenia'");
    }
}