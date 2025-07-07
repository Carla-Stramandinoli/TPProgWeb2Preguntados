<?php

class AdministradorController
{

    private $view;
    private $model;
    private $graficos;


    public function __construct($model, $view, $graficos)
    {
        $this->model = $model;
        $this->view = $view;
        $this->graficos = $graficos;
    }

    public function mostrar()
    {
        $this->view->render("administrador", ["showLogout" => true, "noEsJugador" => true]);
    }

    public function estadisticas()
    {
        $filtroPorFecha = false;

        if (isset($_POST['filtro'])) {
            $fecha = $_POST['filtro'];
            $filtroPorFecha = true;
        } else {
            $fecha = "";
        };
        $anio = null;
        $mes = null;
        $dia = null;
        $fechaStringQuery = "";
        switch ($fecha) {
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
        $arrayPaises = $this->model->obtenerCantidadDeJugadoresPorPaisFiltradosPorFecha($anio, $mes, $dia);
        $mapaGeneros = [];

        foreach ($arrayGeneros as $fila) {
            $mapaGeneros[strtolower($fila['genero'])] = $fila['cantidad'];
        }
        $numJugadoresTotales = $this->model->obtenerCantidadTotalDeJugadoresActivos();
        $numPreguntasTotales = $this->model->obtenerCantidadTotalDePreguntasEnJuego();
        $numCantidadDePartidasJugadas = $this->model->obtenerCantidadTotalDePartidasJugadasDesdeUnaFecha($fechaStringQuery);
        $numPorcentajeAciertos = $this->model->obtenerPorcentajeDeAciertosDePreguntasJugadasPorJugadoresDesdeUnaFecha($fechaStringQuery);
        $numPorcentajeAciertos = number_format($numPorcentajeAciertos, 1);
        $numCantidadTotalDePreguntasCreadas = $this->model->obtenerCantidadTotalDePreguntasCreadasDesdeUnaFecha($fechaStringQuery);
        $numHombres = $mapaGeneros['1'] ?? 0;
        $numMujeres = $mapaGeneros['2'] ?? 0;
        $numOtros = $mapaGeneros['3'] ?? 0; // o como se llame en tu base

        $numMayores = $this->model->obtenerCantidadDeJugadoresPorRangoEtario("mayores", $anio, $mes, $dia);
        $numMenores = $this->model->obtenerCantidadDeJugadoresPorRangoEtario("menores", $anio, $mes, $dia);
        $numJubilados = $this->model->obtenerCantidadDeJugadoresPorRangoEtario("jubilados", $anio, $mes, $dia);
        $numJugadoresNuevos = $this->model->obtenerCantidadJugadoresNuevosDesdeUnaFecha($fechaStringQuery);

        $comparativaJugadores = [
            "Num jugadores totales" => $numJugadoresTotales,
            "Num jugadores nuevos" => $numJugadoresNuevos,
            "Num partidas jugadas" => $numCantidadDePartidasJugadas,
        ];

        $comparativaJugadoresIterable = [];
        foreach ($comparativaJugadores as $key => $value) {
            $comparativaJugadoresIterable[] = [
                'dato' => $key,
                'valor' => $value
            ];
        }

        $distribucionPorEdad = [
            "Num mayores" => $numMayores,
            "Num menores" => $numMenores,
            "Num jubilados" => $numJubilados,
        ];

        $distribucionPorEdadIterable = [];
        foreach ($distribucionPorEdad as $key => $value) {
            $distribucionPorEdadIterable[] = [
                'dato' => $key,
                'valor' => $value
            ];
        }

        $distribucionPorDatosPreguntas = [
            "Preguntas totales" => $numPreguntasTotales,
            "Preguntas creadas" => $numCantidadTotalDePreguntasCreadas,
            "Porcentaje aciertos" => $numPorcentajeAciertos,
        ];

        $distribucionPorDatosPreguntasIterable = [];
        foreach ($distribucionPorDatosPreguntas as $key => $value) {
            $distribucionPorDatosPreguntasIterable[] = [
                'dato' => $key,
                'valor' => $value
            ];
        }

        $distribucionPorGenero = [
            "Total hombres" => $numHombres,
            "Total mujeres" => $numMujeres,
            "Total otros" => $numOtros,
        ];

        $distribucionPorGeneroIterable = [];
        foreach ($distribucionPorGenero as $key => $value) {
            $distribucionPorGeneroIterable[] = [
                'dato' => $key,
                'valor' => $value
            ];
        }
        $arrayPaisesIterable = [];
        foreach ($arrayPaises as $value) {
            $arrayPaisesIterable[] = [
                 'dato' => $value['pais'],
                 'valor' => $value['cantidad']
            ];
        }

        //$numJugadoresPorPais = $this->model->obtenerCantidadDeUsuariosPorPaisFiltradosPorFecha("Arg", $anio, $mes, $dia);

        $this->view->render("estadisticas", [
            "paises" => $arrayPaises,
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
            "filtroPorFecha" => $filtroPorFecha,
            "filtroSeleccionado" => $_POST['filtro'] ?? 'historico',
            "fechaDia" => $_POST['fecha_dia'] ?? '',
            "fechaMes" => $_POST['fecha_mes'] ?? '',
            "fechaAnio" => $_POST['fecha_anio'] ?? '',
            "leyenda" => $_POST['leyenda_fecha'] ?? '',
            "showLogout" => true, "noEsJugador" => true,
            "comparativaJugadoresIterable" => $comparativaJugadoresIterable,
            "distribucionPorEdadIterable" => $distribucionPorEdadIterable,
            "distribucionPorDatosPreguntasIterable" => $distribucionPorDatosPreguntasIterable,
            "distribucionPorGeneroIterable" => $distribucionPorGeneroIterable,
            "arrayPaisesIterable" => $arrayPaisesIterable
        ]);
    }
}