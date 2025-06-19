<?php

class PerfilController
{

    private $view;
    private $model;
    private $qrGenerator;
    private $hostData;


    public function __construct($model ,$view, $qrGenerator, $hostData)
    {
        $this->model = $model;
        $this->view = $view;
        $this->qrGenerator = $qrGenerator;
        $this->hostData = $hostData;
    }
    public function mostrar()
    {
        $id = isset($_GET["id"]) ? $_GET["id"] : $_SESSION["usuarioId"];

        $this->model->almacenarPuntajeAlcanzado($id);

        $datosUsuario = $this->model->obtenerDatosUsuario($id);
        $rachaMasLarga = $this->model->obtenerRachaMasLarga($id);
        $puntajesDePartidasDelJugador = $this->model->obtenerPuntajesDePartidasDelJugador($id);
        $puntajesDePartidaConIndices = $this->model->agregarIndicesAPartidasYPuntajes($puntajesDePartidasDelJugador);

        $this->view->render("perfil", [
            "datosUsuario" => $datosUsuario,
            "rachaMasLarga" => $rachaMasLarga,
            "partidasYPuntajesUsuario" => $puntajesDePartidaConIndices,
            "showLogout" => true
        ]);
    }

    public function generarQr()
    {
        $id = isset($_GET["id"]) ? $_GET["id"] : $_SESSION["usuarioId"];
        $url = 'http://'.$this->hostData['host'].':'.$this->hostData['port'].'/perfil/mostrar?id='.$id;
        return $this->qrGenerator->generate($url);
    }
}