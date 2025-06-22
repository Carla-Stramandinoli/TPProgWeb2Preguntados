<?php

class AdministradorModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtenerCantidadTotalDeJugadoresActivos()
    {
        $resultado = $this->database->query("SELECT COUNT(*) as cantidad FROM jugador WHERE cuenta_activada = 1");
        return isset($resultado[0]['cantidad']) ? $resultado[0]['cantidad'] : false;
    }
    public function obtenerCantidadTotalDePartidasJugadasDesdeUnaFecha($fechaInicio)
    {
        $sql = "SELECT COUNT(*) as cantidad FROM partida WHERE fecha_partida >= ?";
        $resultado = $this->database->queryWithParams($sql, [$fechaInicio]);
        return $resultado[0]['cantidad'] ?? 0;
    }
    public function obtenerCantidadTotalDePreguntasEnJuego()
    {
        $resultado = $this->database->query("SELECT COUNT(*) as cantidad FROM pregunta");
        return isset($resultado[0]['cantidad']) ? $resultado[0]['cantidad'] : false;
    }
    public function obtenerCantidadTotalDePreguntasCreadasDesdeUnaFecha($fechaInicio)
    {
        $sql = "SELECT COUNT(*) as cantidad FROM pregunta WHERE pregunta_creada = 1 AND fecha_creacion >= ?";
        $resultado = $this->database->queryWithParams($sql, [$fechaInicio]);
        return $resultado[0]['cantidad'] ?? 0;
    }
    public function obtenerPorcentajeDeAciertosDePreguntasJugadasPorJugadoresDesdeUnaFecha($fechaInicio)
    {
        $sql = ("SELECT AVG(porcentaje) AS promedio
                                FROM (SELECT(cantidad_aciertos / cantidad_jugada) * 100 AS porcentaje
                                FROM jugador WHERE cantidad_jugada > 0 AND fecha_registro >= ?) AS subconsulta");
        $resultado = $this->database->queryWithParams($sql, [$fechaInicio]);
        return $resultado[0]['promedio'] ?? 0;

    }

    public function construirFiltroFecha($columnaFecha, $anio = null, $mes = null, $dia = null) {
        $condiciones = [];
        $parametros = [];

        if ($anio && $mes && $dia) {
            $condiciones[] = "DATE($columnaFecha) = ?";
            $parametros[] = "$anio-$mes-$dia";
        } elseif ($anio && $mes) {
            $condiciones[] = "YEAR($columnaFecha) = ?";
            $parametros[] = $anio;
            $condiciones[] = "MONTH($columnaFecha) = ?";
            $parametros[] = $mes;
        } elseif ($anio) {
            $condiciones[] = "YEAR($columnaFecha) = ?";
            $parametros[] = $anio;
        }

        return [
            "condiciones" => $condiciones,
            "parametros" => $parametros
        ];
    }

    public function obtenerCantidadDeUsuariosPorPaisFiltradosPorFecha($paisUsuario,  $anio = null, $mes = null, $dia = null)
    {
        $columnaFecha = "fecha_registro";
        $filtroFecha = $this->construirFiltroFecha( $columnaFecha, $anio, $mes, $dia);
        $condiciones = $filtroFecha["condiciones"];
        $parametros = [];

        $condiciones = array_merge(["pais LIKE ?"], $condiciones);
        $parametros[] = "%$paisUsuario%";

        $parametros = array_merge($parametros, $filtroFecha["parametros"]);

        // Armar la consulta final
        $sql = "SELECT COUNT(*) as cantidad FROM jugador WHERE " . implode(" AND ", $condiciones);

        $resultado = $this->database->queryWithParams($sql, $parametros);
        return isset($resultado[0]['cantidad']) ? $resultado[0]['cantidad'] : false;
    }

    public function obtenerCantidadDeJugadoresPorPaisFiltradosPorFecha($anio = null, $mes = null, $dia = null)
    {
        $columnaFecha = "fecha_registro";
        $filtroFecha = $this->construirFiltroFecha($columnaFecha, $anio, $mes, $dia);
        $condiciones = $filtroFecha["condiciones"];
        $parametros = $filtroFecha["parametros"];

        $sql = "SELECT pais, COUNT(*) as cantidad FROM jugador";
        if (!empty($condiciones)) {
            $sql .= " WHERE " . implode(" AND ", $condiciones);
        }
        $sql .= " GROUP BY pais";

        return $this->database->queryWithParams($sql, $parametros);
    }
    public function obtenerCantidadDeJugadoresPorSexosFiltradosPorFecha($anio = null, $mes = null, $dia = null)
    {
        $columnaFecha = "fecha_registro";
        $filtroFecha = $this->construirFiltroFecha($columnaFecha, $anio, $mes, $dia);
        $condiciones = $filtroFecha["condiciones"];
        $parametros = $filtroFecha["parametros"];

        $sql = "SELECT genero, COUNT(*) as cantidad FROM jugador";
        if (!empty($condiciones)) {
            $sql .= " WHERE " . implode(" AND ", $condiciones);
        }
        $sql .= " GROUP BY genero";

        return $this->database->queryWithParams($sql, $parametros);
    }

    public function obtenerCantidadDeJugadoresPorRangoEtario($rango, $anio = null, $mes = null, $dia = null)
    {
        $columnaFecha = "fecha_registro";
        $filtroFecha = $this->construirFiltroFecha($columnaFecha, $anio, $mes, $dia);
        $condiciones = $filtroFecha["condiciones"];
        $parametros = $filtroFecha["parametros"];

        // Condiciones de rango etario
        switch ($rango) {
            case 'menores':
                $condiciones[] = "YEAR(anio_nacimiento) > (YEAR(CURDATE()) - 18)";
                break;
            case 'mayores': // entre 18 y 65
                $condiciones[] = "YEAR(anio_nacimiento) BETWEEN (YEAR(CURDATE()) - 65) AND (YEAR(CURDATE()) - 18)";
                break;
            case 'jubilados': // 65 a 100 años
                $condiciones[] = "YEAR(anio_nacimiento) <= (YEAR(CURDATE()) - 65)";
                break;
        }

        $sql = "SELECT COUNT(*) as cantidad FROM jugador";
        if (!empty($condiciones)) {
            $sql .= " WHERE " . implode(" AND ", $condiciones);
        }

        $resultado = $this->database->queryWithParams($sql, $parametros);
        return $resultado[0]['cantidad'] ?? 0;
    }
    public function obtenerCantidadJugadoresNuevosDesdeUnaFecha($fechaInicio)
    {
        $sql = "SELECT COUNT(genero) as cantidad FROM jugador WHERE fecha_registro >= ?";
        $resultado = $this->database->queryWithParams($sql, [$fechaInicio]);
        return $resultado[0]['cantidad'] ?? 0;
    }



}