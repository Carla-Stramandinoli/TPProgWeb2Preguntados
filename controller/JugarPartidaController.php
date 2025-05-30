<?php

class JugarPartidaController{
    private $view;
    private $model;
    public function __construct($model, $view)
    {
        $this->view = $view;
        $this->model = $model;

    }

    public function mostrar()
    {
        $this-> view->render("jugarPartida" ,["showLogout" => true]);
    }

    public function categoria()
    {
        $categoria = isset($_GET['cat']) ? $_GET['cat'] : '';

        $preguntasPorCategoria = $this->model->obtenerPreguntasPorCategoria($categoria);
        error_log("Categoría recibida: " . $_GET['cat']);

        $puntos = 0;
        $pregunta = $preguntasPorCategoria[0];

        // Mezclar las opciones
        $opciones = $pregunta['incorrectas'];
        $opciones[] = $pregunta['correcta'];
        shuffle($opciones);

        // Renderizar con Mustache
        $this->view->render("pregunta", [
            "categoria" => $categoria,
            "pregunta" => $pregunta["pregunta"],
            "opciones" => $opciones,
            "puntos" => $puntos,
         "showLogout" => true] );
    }


}


