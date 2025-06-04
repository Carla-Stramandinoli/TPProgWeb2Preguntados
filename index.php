<?php
require_once("Configuration.php");
session_start();
// hay que hacer un controlador de cerrar sesion que puedan ser accedido por todos excepto el usuario no logueado
$configuration = new Configuration();
//$router = $configuration->getRouter();

$controller = (isset($_GET["controller"])) ? $_GET["controller"] : null;
$method = (isset($_GET["method"])) ? $_GET["method"] : null;

// si el usuario no esta logueado, solo puede acceder al IngresoController. En caso de que vaya a uno que no corresponda su default sera el Login del IngresoController
// si el usuario es un jugador, solo puede acceder al LobbyController, al PerfilController, y al JugarPartidaController. En caso de que vaya a uno que no corresponda su default sera el mostrar del LobbyController
// si el usuario es editor, SOLO puede acceder al EditorController. En caso de que vaya a uno que no corresponda su default sera mostrar del EditorController.
// si el usuario es administrador, SOLO puede acceder al AdministradorController. En caso de que vaya a uno que no corresponda su default sera mostrar del AdministradorController.


$permisos = $configuration->getPermisos();

$rol = (isset($_SESSION["rol"])) ? $_SESSION["rol"] : 'noLogueado';

$permisos->validarPermisos($rol, $controller, $method);

//$router->go(
//    $controller,
//    $method
//);