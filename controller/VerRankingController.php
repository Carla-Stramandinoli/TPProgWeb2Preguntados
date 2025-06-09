<?php
class VerRankingController
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

        $datos = $this->model->obtenerRankingGlobalCompleto($idUsuario);
        $ranking = $datos["ranking"];
        $usuarioActual = $datos["datosUsuarioActual"];

        $miPosicion = $usuarioActual["posicion"] ?? null;
        $mostrarMiPosicionAbajo = ($miPosicion > 6);

        $this->view->render("verRanking", [
            "ranking" => $ranking,
            "miPosicion" => $miPosicion,
            "miNombre" => $usuarioActual["nickname"] ?? '',
            "miFoto" => $usuarioActual["foto_perfil"] ?? '',
            "miPuntaje" => $usuarioActual["puntaje_alcanzado"] ?? 0,
            "mostrarMiPosicionAbajo" => $mostrarMiPosicionAbajo,
             "showLogout" => true,
        ]);
    }
}
