<?php

require_once("core/Database.php");
require_once("core/MustachePresenter.php");
require_once("core/Router.php");
require_once("core/Permisos.php");
require_once("core/EmailSender.php");
require_once("core/QrGenerator.php");

require_once("controller/LobbyController.php");
require_once("controller/IngresoController.php");
require_once("controller/PerfilController.php");
require_once ("controller/JugarPartidaController.php");
require_once ("controller/EditorController.php");
require_once ("controller/AdministradorController.php");
require_once ("controller/CerrarSesionController.php");
require_once ("controller/VerRankingController.php");
require_once ("controller/EditarPreguntaController.php");


require_once ("model/VerRankingModel.php");
require_once("model/IngresoModel.php");
require_once("model/PerfilModel.php");
require_once ("model/JugarPartidaModel.php");
require_once ("model/EditorModel.php");
require_once("model/AdministradorModel.php");
require_once("model/LobbyModel.php");
require_once("model/EditarPreguntaModel.php");


require_once('vendor/mustache/src/Mustache/Autoloader.php');
require_once('vendor/phpmailer/autoloader.php');
require_once('vendor/phpqrcode/qrlib.php');

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
        return new LobbyController(new LobbyModel($this->getDatabase()) , $this->getViewer());
    }

    public function getIngresoController(){
        return new IngresoController(new IngresoModel($this->getDatabase()) ,$this->getViewer(), $this->getEmailSender(), $this->getIniConfig()['server']);
    }

    public function getVerRankingController()
    {
        return new VerRankingController (new VerRankingModel ($this->getDatabase()),$this->getViewer());
    }

    public function getPerfilController(){
        return new PerfilController(new PerfilModel($this->getDatabase()) ,$this->getViewer(), $this->getQrGenerator(), $this->getIniConfig()['server']);
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

    public function getEditarPreguntaController()
    {
        return new EditarPreguntaController(new EditarPreguntaModel($this->getDatabase()) ,$this->getViewer());
    }

    public function getCerrarSesionController()
    {
        return new CerrarSesionController();
    }

    public function getRouter()
    {
        return new Router('getIngresoController', 'mostrar', $this);
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

    private function getEmailSender()
    {
        return new EmailSender();
    }

    private function getQrGenerator()
    {
        return new QrGenerator();
    }
}