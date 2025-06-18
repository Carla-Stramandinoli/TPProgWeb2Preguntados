<?php

class EditorController
{

    private $view;
    private $model;


    public function __construct($model, $view)
    {
        $this->model = $model;
        $this->view = $view;
    }

    public function mostrar()
    {
        $preguntasExistentes = $this->mostrarPreguntasExistentes();
        $preguntasSugeridas = $this->mostrarPreguntasSugeridas();
        $preguntasReportadas = $this->mostrarPreguntasReportadas();

        $this->view->render("editor", ["showLogout" => true, "noEsJugador" => true,
            "preguntasExistentes" => $preguntasExistentes, "preguntasSugeridas" => $preguntasSugeridas,
            "preguntasReportadas" => $preguntasReportadas]);
    }

    public function mostrarPreguntasExistentes()
    {
        return $this->model->obtenerPreguntasExistentes();
    }

    public function mostrarPreguntasSugeridas()
    {
        return $this->model->obtenerPreguntasSugeridas();
    }

    public function mostrarPreguntasReportadas()
    {
        return $this->model->obtenerPreguntasReportadas();
    }

    public function confirmarPreguntaJugador()
    {
        $id_sugerencia = $_POST['id_sugerencia'] ?? null;

        $preguntaSugerida = $this->model->obtenerPreguntaSugerida($id_sugerencia);

        $resultado = $this->model->guardarPreguntaEnBaseDeDatos($preguntaSugerida);

        if ($resultado) {
            $this->eliminarPreguntaSugeridaController();
            header("Location: /editor/mostrar");
            exit();
        }
    }

    public function confirmarPreguntaEditor()
    {
        $enunciado = $_POST['enunciadoPregunta'] ?? '';
        $respuestaCorrecta = $_POST['respuesta_correcta'] ?? '';
        $respuestaIncorrecta1 = $_POST['respuesta_1'] ?? '';
        $respuestaIncorrecta2 = $_POST['respuesta_2'] ?? '';
        $respuestaIncorrecta3 = $_POST['respuesta_3'] ?? '';
        $categoria = $_POST['categoria'] ?? '';

        $pregunta = [
            'enunciado' => $enunciado,
            'respuesta_correcta' => $respuestaCorrecta,
            'respuesta_1' => $respuestaIncorrecta1,
            'respuesta_2' => $respuestaIncorrecta2,
            'respuesta_3' => $respuestaIncorrecta3,
            'categoria' => $categoria];

        $resultado = $this->model->guardarPreguntaEnBaseDeDatos($pregunta);

        if ($resultado) {
            header("Location: /editor/mostrar");
            exit();
        }
    }

    public function eliminarPreguntaSugeridaController()
    {
        $id_sugerencia = $_POST['id_sugerencia'] ?? null;

        $resultado =  $this->model->eliminarPreguntaSugeridaModel($id_sugerencia);

        if ($resultado) {
            header("Location: /editor/mostrar");
            exit();
        }
    }
}