<?php

class EditorModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtenerPreguntasExistentes()
    {
        return $this->database->query("SELECT P.id, P.enunciado, C.descripcion as categoria_descripcion 
                                        FROM pregunta P JOIN categoria C ON C.id = P.id_categoria");
    }

    public function obtenerPreguntasSugeridas()
    {
        return $this->database->query("SELECT S.id_sugerencia, S.enunciado, S.respuesta_correcta, S.respuesta_1, S.respuesta_2, S.respuesta_3, C.descripcion as categoria_descripcion  
                                         FROM sugerencia S JOIN categoria C ON C.id = S.categoria");
    }

    public function obtenerPreguntasReportadas()
    {
        return $this->database->query("SELECT P.id, P.enunciado, P.cantidad_reportes, C.descripcion as categoria_descripcion 
                                       FROM pregunta P JOIN categoria C ON C.id = P.id_categoria 
                                        WHERE cantidad_reportes > 0 ORDER BY cantidad_reportes DESC");
    }

    public function guardarPreguntaEnBaseDeDatos($pregunta)
    {
        $preguntaAgregada = $this->guardarPregunta($pregunta);

        if ($preguntaAgregada) {
            $id_pregunta = $this->obtenerUltimoIdPregunta();
            $this->guardarRespuestas($pregunta, $id_pregunta);
            return true;
        }
        return false;
    }

    public function guardarPregunta($pregunta)
    {
        return $this->database->execute("INSERT INTO pregunta (enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria, pregunta_creada)
                                    VALUES('{$pregunta["enunciado"]}', 0, 0, NOW(), 0, '{$pregunta["categoria"]}', 1)");
    }

    public function guardarRespuestas($respuestas, $id_pregunta)
    {
        $this->database->execute("INSERT INTO respuesta (descripcion, es_correcta, id_pregunta)
                             VALUES ('{$respuestas['respuesta_correcta']}', 1, '$id_pregunta')");

        // Respuestas incorrectas
        $this->database->execute("INSERT INTO respuesta (descripcion, es_correcta, id_pregunta)
                             VALUES ('{$respuestas['respuesta_1']}', 0, '$id_pregunta')");

        $this->database->execute("INSERT INTO respuesta (descripcion, es_correcta, id_pregunta)
                             VALUES ('{$respuestas['respuesta_2']}', 0, '$id_pregunta')");

        $this->database->execute("INSERT INTO respuesta (descripcion, es_correcta, id_pregunta)
                             VALUES ('{$respuestas['respuesta_3']}', 0, '$id_pregunta')");
    }

    public function obtenerUltimoIdPregunta()
    {
        $resultado = $this->database->query("SELECT id FROM pregunta ORDER BY id DESC LIMIT 1");
        return $resultado[0]['id'];
    }

    public function obtenerPreguntaSugerida($idSugerencia)
    {
        $resultado = $this->database->query("SELECT * FROM sugerencia WHERE id_sugerencia = '$idSugerencia'");
        return $resultado[0] ?? null;
    }

    public function eliminarPreguntaSugeridaModel($id_sugerencia)
    {
        return $this->database->execute("DELETE FROM sugerencia WHERE id_sugerencia = '$id_sugerencia'");
    }

    public function obtenerPreguntaBuscada($textoIngresado)
    {
        return $this->database->query("SELECT P.id, P.enunciado, C.descripcion as categoria_descripcion 
                                    FROM pregunta P JOIN categoria C ON C.id = P.id_categoria WHERE enunciado LIKE '%$textoIngresado%'");
    }

    public function eliminarPreguntaExistente($idPregunta)
    {
        return $this->database->execute("DELETE FROM pregunta WHERE id = '$idPregunta'");
    }

    public function eliminarReferenciaEnTablaContestaPreguntaExistente($idPregunta){
        return $this->database->execute("DELETE FROM contesta WHERE id_pregunta = '$idPregunta'");
    }

    public function eliminarRespuestasDePregunta($idPregunta){
        return $this->database->execute("DELETE FROM respuesta WHERE id_pregunta = '$idPregunta'");
    }

    public function eliminarPreguntaRespuestas($idPregunta)
    {
        $this->eliminarReferenciaEnTablaContestaPreguntaExistente($idPregunta);
        $this->eliminarRespuestasDePregunta($idPregunta);
        return $this->eliminarPreguntaExistente($idPregunta);
    }

    public function obtenerDetallePregunta($idPregunta)
    {
        return $this->database->query("SELECT * FROM respuesta WHERE id_pregunta = '$idPregunta'");
    }

    public function reiniciarReportesEnBaseDeDatos($idPregunta)
    {
        return $this->database->execute("UPDATE pregunta SET cantidad_reportes = 0 WHERE id = '$idPregunta'");
    }
}