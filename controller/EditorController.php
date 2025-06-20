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
        $textoIngresado = $_GET['textoIngresado'] ?? '';

        $idPregunta = $_GET['id'] ?? null;
        $seccion = $_GET['seccion'] ?? '';

        $abrirModal = false;
        $respuestasObtenidas = null;

        if ($idPregunta) {
            $respuestasObtenidas = $this->verDetallePregunta($idPregunta);
            $abrirModal = true;
        }

        $collapseReportadaAbierto = false;
        $collapseSugerenciaAbierto = false;
        $collapseCrearPreguntaAbierto = false;

        if (!empty($textoIngresado)) {
            $preguntasExistentes = $this->model->obtenerPreguntaBuscada($textoIngresado);;
            $hayBusqueda = true;
            $collapseAbierto = true;
        } else {
            $preguntasExistentes = $this->model->obtenerPreguntasExistentes();
            $hayBusqueda = false;
            $collapseAbierto = false;
        }

        $preguntasSugeridas = $this->model->obtenerPreguntasSugeridas();
        $preguntasReportadas = $this->model->obtenerPreguntasReportadas();

        switch ($seccion) {
            case 'reportadas':
                $collapseReportadaAbierto = true;
                break;
            case 'existentes':
                $collapseAbierto = true;
                break;
            case 'sugerencia':
                $collapseSugerenciaAbierto = true;
                break;
            case 'crearPregunta':
                $collapseCrearPreguntaAbierto = true;
                break;
        }

        $this->view->render("editor", [
            "showLogout" => true,
            "noEsJugador" => true,
            "preguntasExistentes" => $preguntasExistentes,
            "preguntasSugeridas" => $preguntasSugeridas,
            "preguntasReportadas" => $preguntasReportadas,
            "hayBusqueda" => $hayBusqueda,
            "respuestasObtenidas" => $respuestasObtenidas,
            'abrirModal' => $abrirModal,
            "collapseAbierto" => $collapseAbierto,
            'collapseReportadaAbierto' => $collapseReportadaAbierto,
            'collapseSugerenciaAbierto' => $collapseSugerenciaAbierto,
            'collapseCrearPreguntaAbierto' => $collapseCrearPreguntaAbierto,
            'seccionOrigen' => $seccion, // al cerrar el modal de respuestas deja abierto el de la seccion correspondiente
            'msjExito' => $_GET['msjExito'] ?? null,
            'msjError' => $_GET['msjError'] ?? null,
        ]);
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

        $msj = '';

        if ($resultado) {
            $msj = 'La pregunta sugerida fue aceptada.';
            $this->model->eliminarPreguntaSugeridaModel($id_sugerencia);
            header("Location: /editor/mostrar?seccion=sugerencia&msjExito=" . urlencode($msj));
            exit();
        } else {
            $msj = 'No se pudo confirmar la pregunta sugerida.';
            header("Location: /editor/mostrar?seccion=sugerencia&msjError=" . urlencode($msj));
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
            'categoria' => $categoria
        ];

        $resultado = $this->model->guardarPreguntaEnBaseDeDatos($pregunta);

        $msj = '';

        if ($resultado) {
            $msj = 'La pregunta fue creada correctamente.';
            header("Location: /editor/mostrar?seccion=crearPregunta&msjExito=" . urlencode($msj));
            exit();
        } else {
            $msj = 'La pregunta no pudo ser creada.';
            header("Location: /editor/mostrar?seccion=crearPregunta&msjExito=" . urlencode($msj));
            exit();
        }
    }

    public function eliminarPreguntaSugeridaController()
    {
        $id_sugerencia = $_POST['id_sugerencia'] ?? null;

        $resultado = $this->model->eliminarPreguntaSugeridaModel($id_sugerencia);

        $msj = '';

        if ($resultado) {
            $msj = 'La pregunta sugerida fue descartada.';
            header("Location: /editor/mostrar?seccion=sugerencia&msjExito=" . urlencode($msj));
            exit();
        } else {
            $msj = 'No se pudo eliminar la pregunta sugerida.';
            header("Location: /editor/mostrar?seccion=sugerencia&msjError=" . urlencode($msj));
            exit();
        }
    }

    public function eliminarPregunta()
    {
        $idPregunta = $_POST['id'] ?? null;
        $seccion = $_POST['seccion'] ?? '';

        $resultado = $this->model->eliminarPreguntaRespuestas($idPregunta);

        $msj = '';

        if ($resultado) {
            $msj = 'La pregunta se ha eliminado correctamente.';
            header("Location: /editor/mostrar?seccion=$seccion&msjExito=" . urlencode($msj));
            exit();
        } else {
            $msj = 'Error al eliminar la pregunta.';
            header("Location: /editor/mostrar?seccion=$seccion&msjError=" . urlencode($msj));
            exit();
        }
    }

    public function verDetallePregunta($idPregunta)
    {
        return $this->model->obtenerDetallePregunta($idPregunta);
    }

    public function reiniciarReportes()
    {
        $idPregunta = $_POST['id'] ?? null;
        $resultado = $this->model->reiniciarReportesEnBaseDeDatos($idPregunta);

        $msj = '';

        if ($resultado) {
            $msj = 'Reportes reiniciados.';
            header("Location: /editor/mostrar?seccion=reportadas&msjExito=" . urlencode($msj));
            exit();
        } else {
            $msj = 'Error al reiniciar los reportes.';
            header("Location: /editor/mostrar?seccion=reportadas&msjError=" . urlencode($msj));
            exit();
        }
    }

}