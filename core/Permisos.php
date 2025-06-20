<?php

class Permisos
{

    private $permisos;
    private $router;

    public function __construct($permisosIni, $router)
    {
        $this->permisos = $permisosIni;
        $this->router = $router;
    }

    public function validarPermisos($rol, $controller, $method){

        $controladoresPermitidos = $this->permisos[$rol]['controladores'];

        $validMethod = isset($method) ? $method : $this->permisos[$rol]['metodoDefault'];

        if (in_array($controller, $controladoresPermitidos)) $this->router->go($controller, $validMethod);
        else $this->redirectTo('/'.$this->permisos[$rol]['controladorDefault'].'/'.$this->permisos[$rol]['metodoDefault']);
    }

    public function redirectTo($str)
    {
        header("location:" . $str);
        exit();
    }
}