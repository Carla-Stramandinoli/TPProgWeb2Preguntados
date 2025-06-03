<?php

class JugarPartidaModel{
    private $database;
    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtenerPreguntaSinFiltroNoRespondida($descripcionCategoria, $idJugador)
    {
        $resultado = $this->database->query("SELECT enunciado FROM pregunta 
                                            JOIN categoria ON pregunta.id_categoria = categoria.id
                                            WHERE categoria.descripcion = '$descripcionCategoria' 
                                                AND pregunta.id NOT IN (
                                                        SELECT id_pregunta FROM contesta WHERE id_jugador = '$idJugador'
                                                    )
                                            ORDER BY RAND()
                                            LIMIT 1");
        return isset($resultado[0]['enunciado']) ? $resultado[0]['enunciado'] : false;
    }

    public function obtenerPreguntasFiltradasNoRespondidasPorUsuario($descripcionCategoria, $idJugador)
    {
        $nivelJugador = $this->obtenerNivelJugador($idJugador);
        $stringBetween = "";

        if ($nivelJugador <= 30){
            $stringBetween = "BETWEEN 70 AND 100";
        } elseif ($nivelJugador >= 70){
            $stringBetween = "BETWEEN 0.01 AND 30";
        } else {
            $stringBetween = "BETWEEN 30.01 AND 69";
        }

        $resultado = $this->database->query("SELECT enunciado 
                                            FROM pregunta 
                                            JOIN categoria ON pregunta.id_categoria = categoria.id
                                            WHERE categoria.descripcion = '$descripcionCategoria'
                                                AND pregunta.cantidad_jugada > 2 /*Cambiar luego de testeo*/
                                                AND (pregunta.cantidad_aciertos / pregunta.cantidad_jugada * 100) $stringBetween
                                                AND pregunta.id NOT IN (
                                                    SELECT id_pregunta FROM contesta WHERE id_jugador = '$idJugador'
                                                )
                                            ORDER BY RAND()
                                            LIMIT 1");

        if (isset($resultado[0]['enunciado'])){
            return $resultado[0]['enunciado'];
        }

        return $this->obtenerPreguntaSinFiltroNoRespondida($descripcionCategoria, $idJugador);
    }

    public function obtenerEnunciadoPregunta($descripcionCategoria, $idJugador)
    {

        $cantidadPreguntasRespondidasJugador = $this->database->query("SELECT cantidad_jugada FROM jugador WHERE id = '$idJugador'");

        if ($cantidadPreguntasRespondidasJugador > 10){

            $resultado = $this->obtenerPreguntasFiltradasNoRespondidasPorUsuario($descripcionCategoria, $idJugador);

            if ($resultado) {
                return $resultado;
            }

            $resultadoSubConsulta = $this->database->execute("DELETE FROM contesta 
                                                                WHERE id_jugador = '$idJugador' 
                                                                AND id_pregunta IN (SELECT pregunta.id
                                                                                    FROM pregunta
                                                                                    JOIN categoria ON pregunta.id_categoria = categoria.id
                                                                                    WHERE categoria.descripcion = '$descripcionCategoria')");

            if ($resultadoSubConsulta){
                return $this->obtenerPreguntasFiltradasNoRespondidasPorUsuario($descripcionCategoria, $idJugador);
            }
        }

        return $this->obtenerPreguntaSinFiltroNoRespondida($descripcionCategoria, $idJugador);
    }

    public function obtenerIdPregunta($pregunta)
    {
        $resultado = $this->database->query("SELECT id FROM pregunta WHERE enunciado = '$pregunta'");
        return isset($resultado[0]['id']) ? $resultado[0]['id'] : false;
    }

    public function obtenerRespuestasPorPregunta($idPregunta) {
        //$id = $pregunta['id'];
        $result = $this->database->query("
                            SELECT descripcion
                            FROM respuesta
                            WHERE id_pregunta = '$idPregunta'");
        return $result;
    }

    public function validarRespuestaCorrecta($idPregunta, $respuesta)
    {
        $resultado = $this->database->query("SELECT descripcion 
                                            FROM respuesta
                                            WHERE es_correcta = 1 AND id_pregunta = '$idPregunta'");
        $enunciadoCorrecto = isset($resultado[0]['descripcion']) ? $resultado[0]['descripcion'] : false;
        if ($enunciadoCorrecto == $respuesta) {
            return 1;
        }
        return "Error";
    }

    public function elegirCategoriaRandom()
    {
        $categorias = ["Historia", "Ciencia", "Geografía", "Deportes", "Entretenimiento", "Arte"];
        $indiceElegido = array_rand($categorias);
        $categoria = $categorias[$indiceElegido];
        return $categoria;
    }

    public function crearInstanciaDePartida($id)
    {
        $this->database->execute("INSERT INTO partida (fecha_partida, resultado, id_jugador) 
                                    VALUES (CURRENT_TIMESTAMP(), 0, $id)");
    }

    public function actualizarPuntosPartida($idPartida)
    {
        $this->database->execute("UPDATE partida SET resultado = resultado + 1 WHERE id_partida = '$idPartida'");
    }

    public function obtenerPartidaPorJugador($id)
    {
        $resultado = $this->database->query("SELECT id_partida FROM partida WHERE id_jugador = $id ORDER BY fecha_partida DESC LIMIT 1");
        return isset($resultado[0]['id_partida']) ? $resultado[0]['id_partida'] : false;
    }

    public function almacenarPreguntaDePartidaEnTablaCompuesta($id_partida, $id)
    {
        // Verificamos si ya existe la relación
        $resultado = $this->database->query("SELECT 1 FROM compuesta WHERE id_partida = $id_partida AND id_pregunta = $id");

        // Si no existe, insertamos
        if (empty($resultado)) {
            $this->database->execute("INSERT INTO compuesta (id_partida, id_pregunta) VALUES ($id_partida, $id)");
        }
    }

    public function almacenarPreguntasContestadasEnTablaContesta($idJugador, $id)
    {
        $this->database->execute("INSERT INTO contesta (id_jugador, id_pregunta)
                                    VALUES ($idJugador, $id)");
    }

    //De aca para abajo van los calculas de DIFICULTAD
    //Cantidad total de veces que se hizo una pregunta
    public function actualizarCantidadDeVecesJugadaPregunta($idPregunta)
    {
        $this->database->execute("UPDATE pregunta SET cantidad_jugada = cantidad_jugada + 1 WHERE id = $idPregunta");
    }

    //Cantidad total de veces que se respondio bien una pregunta
    public function actualizarRespuestaExitosaPregunta($idPregunta)
    {
        $this->database->execute("UPDATE pregunta SET cantidad_aciertos = cantidad_aciertos + 1 WHERE id = $idPregunta");
    }

    public function obtenerNivelPregunta($idPregunta)
    {
        $resultado = $this->database->query("SELECT cantidad_aciertos / cantidad_jugada * 100 as DIFICULTAD
                                                FROM pregunta
                                                WHERE id_pregunta = '$idPregunta'");
        return isset($resultado[0]['DIFICULTAD']) ? $resultado[0]['DIFICULTAD'] : false;
    }

    //Cantidad total de preguntas que se le hizo a un usuario
    public function actualizarCantidadTotalPreguntasJugador($idJugador)
    {
        $this->database->execute("UPDATE jugador SET cantidad_jugada = cantidad_jugada + 1 WHERE id = $idJugador");
    }

    //Cantidad total de respuestas correctas que hizo un usuario
    public function actualizarCantidadTotalPreguntasCorrectasJugador($idJugador)
    {
        $this->database->execute("UPDATE jugador SET cantidad_aciertos = cantidad_aciertos + 1 WHERE id = $idJugador");
    }

    public function obtenerNivelJugador($idJugador)
    {
        $resultado = $this->database->query("SELECT cantidad_aciertos / cantidad_jugada * 100 as DIFICULTAD
                                                FROM jugador
                                                WHERE id = '$idJugador'");
        return isset($resultado[0]['DIFICULTAD']) ? $resultado[0]['DIFICULTAD'] : false;
    }

    public function obtenerRachaMasLargaJugador($idJugador)
    {
        $resultado = $this->database->query("SELECT MAX(resultado) AS mejor_resultado FROM partida WHERE id_jugador='$idJugador'");
        return isset($resultado[0]['mejor_resultado']) ? ($resultado[0]['mejor_resultado']) : false;
    }

}