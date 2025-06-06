<?php
class VerRankingController {

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

        $ranking = $this->model->obtenerRankingGlobalCompleto($idUsuario);

        // Buscar tu posición en el array
        $miPosicion = null;
        foreach ($ranking as $jugador) {
            if ($jugador["esUsuarioActual"]) {
                $miPosicion = $jugador["posicion"];
                break;
            }
        }

        $this->view->render("verRanking", [


            "ranking" => $ranking,
            "miPosicion" => $miPosicion
        ]);
    }
}
