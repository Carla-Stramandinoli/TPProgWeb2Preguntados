<?php

class EditorModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtenerPreguntasExistentes(){
        return $this->database->query("SELECT id, enunciado FROM pregunta");
    }

    public function obtenerPreguntasSugeridas(){
        return $this->database->query("SELECT id_sugerencia, enunciado, respuesta_correcta, respuesta_1, respuesta_2, respuesta_3, categoria  
                                        FROM sugerencia");
    }

    public function obtenerPreguntasReportadas(){
        return $this->database->query("SELECT id, enunciado, cantidad_reportes FROM pregunta WHERE cantidad_reportes > 0 ORDER BY cantidad_reportes DESC");
    }

    public function guardarPreguntaEnBaseDeDatos($pregunta){
//        $preguntaSugerida = $this->obtenerPreguntaSugerida($id_sugerencia);

        $preguntaAgregada = $this->guardarPregunta($pregunta);
        if ($preguntaAgregada) {
            $id_pregunta = $this->obtenerUltimoIdPregunta();
            $this->guardarRespuestas($pregunta, $id_pregunta);
            return true;
        }
        return false;
    }

    public function guardarPregunta($pregunta){
        return $this->database->execute("INSERT INTO pregunta (enunciado, cantidad_jugada, cantidad_aciertos, fecha_creacion, cantidad_reportes, id_categoria)
                                    VALUES('{$pregunta["enunciado"]}', 0, 0, NOW(), 0, '{$pregunta["categoria"]}')");
    }

    public function guardarRespuestas($respuestas, $id_pregunta){
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

    public function obtenerUltimoIdPregunta(){
        $resultado = $this->database->query("SELECT id FROM pregunta ORDER BY id DESC LIMIT 1");
         return $resultado[0]['id'];
    }

    public function obtenerPreguntaSugerida($id_sugerencia){
        $resultado =  $this->database->query("SELECT * FROM sugerencia WHERE id_sugerencia = $id_sugerencia");
        return $resultado[0] ?? null;
    }

    public function eliminarPreguntaSugeridaModel($id_sugerencia){
        return $this->database->execute("DELETE FROM sugerencia WHERE id_sugerencia = $id_sugerencia");
    }

    public function obtenerPreguntaBuscada($textoIngresado) {
        return $this->database->query("SELECT id, enunciado FROM pregunta WHERE enunciado LIKE '%$textoIngresado%'");
    }
}