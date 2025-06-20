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
        return $this->database->query("SELECT COUNT(*)  FROM jugador WHERE cuenta_activada = 1");
    }
    public function obtenerCantidadTotalDePartidasJugadas()
    {
        return $this->database->query("SELECT COUNT(*)  FROM partida");
    }
    public function obtenerCantidadTotalDePreguntasEnJuego()
    {
        return $this->database->query("SELECT COUNT(*)  FROM pregunta");
    }
    public function obtenerCantidadTotalDePreguntasCreadas()
    {
        return $this->database->query("SELECT COUNT(*)  FROM pregunta WHERE pregunta_creada = 1");
    }
    public function obtenerPorcentajeDeAciertosDePreguntasJugadasPorJugadores()
    {
        return $this->database->query("SELECT AVG(porcentaje) AS promedio
                                FROM (SELECT(cantidad_aciertos / cantidad_jugada) * 100 AS porcentaje
                                FROM jugador WHERE cantidad_jugada > 0) AS subconsulta");
    }
    public function obtenerCantidadDeUsuariosPorPais($paisUsuario)
    {
        return $this->database->query("SELECT COUNT(*)  FROM jugador WHERE pais LIKE '%$paisUsuario%'");
    }
}