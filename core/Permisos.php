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

        $method = isset($method) ? $method : $this->permisos[$rol]['metodoDefault'];

        if (in_array($controller, $controladoresPermitidos)) $this->router->go($controller, $method);
//        else $this->router->go($this->permisos[$rol]['controladorDefault'], $this->permisos[$rol]['metodoDefault']); // hacemos uso de los controller y method default que tiene el .ini
        else $this->redirectTo('/'.$this->permisos[$rol]['controladorDefault'].'/'.$this->permisos[$rol]['metodoDefault']);
    }

    public function redirectTo($str)
    {
        header("location:" . $str);
        exit();
    }
}