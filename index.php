<?php
require_once("Configuration.php");
session_start();

$configuration = new Configuration();
$router = $configuration->getRouter();

$controller = (isset($_GET["controller"])) ? $_GET["controller"] : null;
$method = (isset($_GET["method"])) ? $_GET["method"] : null;



$router->go(
    $controller,
    $method
);