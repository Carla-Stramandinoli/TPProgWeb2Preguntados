<?php

class EditarPreguntaController
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

        $preguntaAEditar = $this->obtenerDatosPregunta();

        $this->view->render("editarPregunta", ['preguntaAEditar' => $preguntaAEditar]);
    }

    public function obtenerDatosPregunta()
    {
        $idPregunta = $_POST['id'] ?? null;

        $datos = $this->model->obtenerDatosPreguntaYRespuesta($idPregunta);

        $pregunta = [
            'id' =>  $idPregunta,
            'enunciado' => $datos[0]['enunciado'],
            'categoria' => $datos[0]['categoria'],
            'respuesta_correcta' => $datos[0]['descripcion'],
            'respuesta_1' => $datos[1]['descripcion'],
            'respuesta_2' => $datos[2]['descripcion'],
            'respuesta_3' => $datos[3]['descripcion'],
            'idRespuestaCorrecta' => $datos[0]['id'],
            'idRespuesta_1' => $datos[1]['id'],
            'idRespuesta_2' => $datos[2]['id'],
            'idRespuesta_3' => $datos[3]['id']
        ];

        $pregunta['categoria_' . $pregunta['categoria']] = true;
        return $pregunta;
    }

    public function actualizarDatos()
    {
        $idPregunta = isset($_POST['idPregunta']) ? $_POST['idPregunta'] : null;
        $enunciado = isset($_POST['enunciadoPregunta']) ? $_POST['enunciadoPregunta'] : null;
        $respuestaCorrecta = isset($_POST['respuesta_correcta']) ? $_POST['respuesta_correcta'] : null;
        $respuesta_1 = isset($_POST['respuesta_1']) ? $_POST['respuesta_1'] : null;
        $respuesta_2 = isset($_POST['respuesta_2']) ? $_POST['respuesta_2'] : null;
        $respuesta_3 = isset($_POST['respuesta_3']) ? $_POST['respuesta_3'] : null;
        $categoria = isset($_POST['categoria']) ? $_POST['categoria'] : null;
        $idRespuestaCorrecta = isset($_POST['idRespuestaCorrecta']) ? $_POST['idRespuestaCorrecta'] : null;
        $idRespuesta_1 = isset($_POST['idRespuesta_1']) ? $_POST['idRespuesta_1'] : null;
        $idRespuesta_2 = isset($_POST['idRespuesta_2']) ? $_POST['idRespuesta_2'] : null;
        $idRespuesta_3 = isset($_POST['idRespuesta_3']) ? $_POST['idRespuesta_3'] : null;

        $datosPregunta = [
            'id' =>  $idPregunta,
            'enunciado' => $enunciado,
            'respuesta_correcta' => $respuestaCorrecta,
            'respuesta_1' => $respuesta_1,
            'respuesta_2' => $respuesta_2,
            'respuesta_3' => $respuesta_3,
            'categoria' => $categoria,
            'idRespuestaCorrecta' =>  $idRespuestaCorrecta,
            'idRespuesta_1' =>  $idRespuesta_1,
            'idRespuesta_2' =>  $idRespuesta_2,
            'idRespuesta_3' =>  $idRespuesta_3
        ];

        $resultado = $this->model->actualizarPreguntaYRespuestasModel($datosPregunta);
        $msj = '';

        if ($resultado) {
            $msj = "Se ha actualizado correctamente";
            header("Location: /editor/mostrar?msjExito=" . urlencode($msj));
            exit();
        } else {
            $msj = "Error al actualizar la pregunta";
            header("Location: /editor/mostrar?msjError=" . urlencode($msj));
            exit();
        }
    }

}