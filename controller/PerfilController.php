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
        $id = $_GET["id"] ?? $_SESSION["usuarioId"];

        $this->model->almacenarPuntajeAlcanzado($id);

        $datosUsuario = $this->model->obtenerDatosUsuario($id);
        $puntajesDePartidasDelJugador = $this->model->obtenerPuntajesDePartidasDelJugador($id);
        $puntajesDePartidaConIndices = $this->model->agregarIndicesAPartidasYPuntajes($puntajesDePartidasDelJugador);

        $mostrarLogout = !isset($_GET["id"]); // Solo mostrar logout si es el perfil propio

        $this->view->render("perfil", [
            "datosUsuario" => $datosUsuario,
            "partidasYPuntajesUsuario" => $puntajesDePartidaConIndices,
            "showLogout" => $mostrarLogout
        ]);
    }



}