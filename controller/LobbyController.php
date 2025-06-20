<?php

class LobbyController
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
        $idUsuario = $_SESSION["usuarioId"];
        $datosUsuarioLobby = $this->model->obtenerDatosUsuarioLobby($idUsuario);
        $puestoRanking = $this->model->obtenerPuestoRanking($idUsuario);
        $rachaMasLarga = $this->model->obtenerRachaMasLarga($idUsuario);
        $historialPartidas = $this->model->obtenerHistorialPartidas($idUsuario);

        $this->view->render("lobby", [
            "datosUsuario" => $datosUsuarioLobby,
            "puestoRanking" => $puestoRanking,
            "rachaMasLarga" => $rachaMasLarga,
            "historialPartidas" => $historialPartidas,
            'msjExito' => (isset($_GET['msjExito'])) ? $_GET['msjExito'] : null,
            'msjError' => (isset($_GET['msjError'])) ? $_GET['msjError'] : null,
            "showLogout" => true
        ]);
    }

    private function redirectTo($str)
    {
        header("location:" . $str);
        exit();
    }

    public function sugerirPregunta()
    {
        $idJugador = $_SESSION["usuarioId"];
        $enunciadoSugerido = $_POST['enunciadoPregunta'] ?? '';
        $respuestaCorrecta = $_POST['respuesta_correcta'] ?? '';
        $respuestaIncorrecta1 = $_POST['respuesta_1'] ?? '';
        $respuestaIncorrecta2 = $_POST['respuesta_2'] ?? '';
        $respuestaIncorrecta3 = $_POST['respuesta_3'] ?? '';
        $categoria = $_POST['categoria'] ?? '';

        $registrarPregunta = $this->model->registrarPreguntaSugerida($idJugador, $enunciadoSugerido, $respuestaCorrecta, $respuestaIncorrecta1, $respuestaIncorrecta2, $respuestaIncorrecta3, $categoria);

        $msj = '';

        if ($registrarPregunta) {
            $msj = 'La pregunta sugerida fue creada correctamente.';
            header("Location: /lobby/mostrar?msjExito=" . urlencode($msj));
            exit();
        } else {
            $msj = 'No se pudo crear la pregunta.';
            header("Location: /lobby/mostrar?msjError=" . urlencode($msj));
            exit();
        }
    }
}