<?php
require_once("core/Database.php");
require_once("core/MustachePresenter.php");
require_once("core/Router.php");
require_once("core/Permisos.php");

require_once("controller/LobbyController.php");
require_once("controller/IngresoController.php");
require_once("controller/PerfilController.php");
require_once ("controller/JugarPartidaController.php");
require_once ("controller/EditorController.php");
require_once ("controller/AdministradorController.php");

require_once("model/IngresoModel.php");
require_once("model/PerfilModel.php");
require_once ("model/JugarPartidaModel.php");
require_once ("model/EditorModel.php");
require_once("model/AdministradorModel.php");

include_once('vendor/mustache/src/Mustache/Autoloader.php');

class Configuration
{
    public function getDatabase()
    {
        $config = $this->getIniConfig();

        return new Database(
            $config["database"]["server"],
            $config["database"]["user"],
            $config["database"]["dbname"],
            $config["database"]["pass"]
        );
    }

    public function getIniConfig()
    {
        return parse_ini_file("configuration/config.ini", true);
    }

    public function getLobbyController()
    {
        return new LobbyController($this->getViewer());
    }

    public function getIngresoController(){
        return new IngresoController(new IngresoModel($this->getDatabase()) ,$this->getViewer());
    }

    public function getPerfilController(){
        return new PerfilController(new PerfilModel($this->getDatabase()) ,$this->getViewer());
    }

    public function getJugarPartidaController()
    {
        return new JugarPartidaController(new JugarPartidaModel($this->getDatabase()) ,$this->getViewer());
    }

    public function getEditorController()
    {
        return new EditorController(new EditorModel($this->getDatabase()) ,$this->getViewer());
    }

    public function getAdministradorController()
    {
        return new AdministradorController(new AdministradorModel($this->getDatabase()) ,$this->getViewer());
    }

    public function getRouter()
    {

//        $defaultController='getIngresoController';
//        $defaultMethod='login';
//
//        if(isset($_SESSION['nickname'])){
//            $defaultController = 'getLobbyController';
//            $defaultMethod = 'mostrar';
//        }
//
//        // analizar switch para tipos luego.

        return new Router('getIngresoController', 'login', $this);
    }

    public function getViewer()
    {
        return new MustachePresenter("view");
    }

    private function getPermisosIni()
    {
        return parse_ini_file("configuration/permisos.ini", true);
    }
    public function getPermisos()
    {
        return new Permisos($this->getPermisosIni(), $this->getRouter());
    }



}