<?php

class AdministradorController
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
        $this->view->render("administrador", ["showLogout" => true, "noEsJugador" => true]);
    }

    public function estadisticas()
    {

        if(isset($_POST['filtro'])){
            $fecha = $_POST['filtro'];
    }else{ $fecha = "";};
        // cantidad de usuarios por sexo, cantidad
        //de usuarios por grupo de edad (menores, jubilados, medio).
        $anio = null;
        $mes = null;
        $dia = null;
        $fechaStringQuery = "";
        switch ($fecha){
            case "dia":
                $fechaCompleta = $_POST['fecha_dia']; // Formato: YYYY-MM-DD
                list($anio, $mes, $dia) = explode("-", $fechaCompleta);
                $fechaStringQuery = $fechaCompleta;
                break;
            case "mes":
                $fechaMes = $_POST['fecha_mes']; // Formato: YYYY-MM
                list($anio, $mes) = explode("-", $fechaMes);
                $fechaStringQuery = "$anio-$mes-01";
                break;
            case "anio":
                $anio = $_POST['fecha_anio'];
                $fechaStringQuery = "$anio-01-01";
                break;
            default:
                $fechaStringQuery = "1900-01-01";
        }
        $arrayGeneros = $this->model->obtenerCantidadDeJugadoresPorSexosFiltradosPorFecha($anio, $mes, $dia);
        $mapaGeneros = [];

        foreach ($arrayGeneros as $fila) {
            $mapaGeneros[strtolower($fila['genero'])] = $fila['cantidad'];
        }
        $numJugadoresTotales = $this->model->obtenerCantidadTotalDeJugadoresActivos();
        $numPreguntasTotales = $this->model->obtenerCantidadTotalDePreguntasEnJuego();
        $numCantidadDePartidasJugadas = $this->model->obtenerCantidadTotalDePartidasJugadas();
        $numPorcentajeAciertos = $this->model->obtenerPorcentajeDeAciertosDePreguntasJugadasPorJugadores();
        $numPorcentajeAciertos = number_format($numPorcentajeAciertos,1);
        $numCantidadTotalDePreguntasCreadas = $this->model->obtenerCantidadTotalDePreguntasCreadas();
        $numHombres = $mapaGeneros['1'] ?? 0;
        $numMujeres = $mapaGeneros['2'] ?? 0;
        $numOtros = $mapaGeneros['3'] ?? 0; // o como se llame en tu base

        $numMayores = $this->model->obtenerCantidadDeJugadoresPorRangoEtario("mayores", $anio, $mes, $dia);
        $numMenores = $this->model->obtenerCantidadDeJugadoresPorRangoEtario("menores", $anio, $mes, $dia);
        $numJubilados = $this->model->obtenerCantidadDeJugadoresPorRangoEtario("jubilados", $anio, $mes, $dia);

        $numJugadoresPorPais = $this->model->obtenerCantidadDeUsuariosPorPaisFiltradosPorFecha("Arg", $anio, $mes, $dia);
        $numJugadoresNuevos = $this->model->obtenerCantidadJugadoresNuevosDesdeUnaFecha($fechaStringQuery);
        $this->view->render("estadisticas", [
            "paises" => $numJugadoresPorPais,
            "numJugadoresTotales" => $numJugadoresTotales,
            "numPreguntasTotales" => $numPreguntasTotales,
            "numPartidasJugadas" => $numCantidadDePartidasJugadas,
            "numPorcentajeAciertos" => $numPorcentajeAciertos,
            "numPreguntasCreadas" => $numCantidadTotalDePreguntasCreadas,
            "numHombres" => $numHombres,
            "numMujeres" => $numMujeres,
            "numOtros" => $numOtros,
            "numMayores" => $numMayores,
            "numMenores" => $numMenores,
            "numJubilados" => $numJubilados,
            "jugadoresNuevos" => $numJugadoresNuevos,
            "showLogout" => true, "noEsJugador" => true]);
    }


}