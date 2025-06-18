<?php

class PerfilController
{

    private $view;
    private $model;


    public function __construct($model ,$view)
    {
        $this->model = $model;
        $this->view = $view;
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



}