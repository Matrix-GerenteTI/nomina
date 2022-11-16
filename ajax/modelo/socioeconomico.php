<?php

require_once $_SERVER[ 'DOCUMENT_ROOT']."/nomina/ajax/clases/DB.php";

class Socioeconomico extends DB
{
    public function getUltimoSocioeconomico( $trabajador )
    {
        $querySocioeconomico = "SELECT * FROM psocioeconomico WHERE  idempleado = $trabajador 
                                                                    -- AND fechaRealizacion = (SELECT MAX(fechaRealizacion) FROM psocioeconomico WHERE idempleado = $trabajador  ) ";
        return $this->select( $querySocioeconomico );
    }

    public function getPreguntasSocioecomomicoRealizado( $id )
    {
        $queryPreguntas = "SELECT ps.*, es.*
                                                from cpreguntas_socioeconomico as ps
                                                left join pevaluacion_socioeconomico as es on es.idpregunta = ps.id 
                                                order by ps.depende_pregunta, ps.seccion";
        return $this->select( $queryPreguntas );
    }

    public function getAllPreguntas()
    {
        $queryPreguntas = " SELECT ps.*
                                                from cpreguntas_socioeconomico as ps
                                                order by ps.depende_pregunta, ps.seccion";
        return $this->select( $queryPreguntas );                                                
    }

    public function getRespuestaPredeterminada( $idPregunta )
    {
        $queryRespuesta = "SELECT * FROM copciones_socioeconomico WHERE idpregunta = $idPregunta";
        return $this->select( $queryRespuesta );
    }

    public function creaRegistroSocioeconomico( $trabajador)
    {
        //funcion: Crea un registro en la tabla psocioeconomico para  poder realizar la evaluacion socioeconomica de un
        //trabajador, dejando campos vacíos para ser actualizados cuando la evaluacion haya culminado

        $queryRegistroSocioeconomico = "INSERT INTO  psocioeconomico(idempleado) VALUES( $trabajador) ";
        return $this->insert( $queryRegistroSocioeconomico );
    }

    public function getPreguntasRespuestasEvaluacion( $socioeconomicoId)
    {
        $queryPreguntasRespuestas = "SELECT ps.*, eval.*
                        FROM pevaluacion_socioeconomico as eval
                        INNER JOIN  psocioeconomico on psocioeconomico.id = eval.idsocioeconomico
                        RIGHT JOIN cpreguntas_socioeconomico as ps on ps.id = eval.idpregunta and eval.idsocioeconomico= $socioeconomicoId
                        order by ps.depende_pregunta, ps.seccion";
        return $this->select( $queryPreguntasRespuestas );
    }

    public function getDetalleGeneral( $id)
    {
         $queryDetalle = "SELECT * FROM psocioeconomico WHERE id = $id ";

         return $this->select( $queryDetalle );
    }
    public function registraRespuestaEvaluacion( $params )
    {
        extract( $params );
        $querySetRespuestas = "INSERT INTO pevaluacion_socioeconomico VALUES($preguntaId, $socioeconomicoId,'$respuesta')";
        
        return $this->insert( $querySetRespuestas );
    }

    public function actualizaRespuestaEvaluacion( $params)
    {
        extract( $params );
        $queryActualizaRespuesta ="UPDATE pevaluacion_socioeconomico SET respuesta ='$respuesta' WHERE idpregunta = $preguntaId AND idsocioeconomico= $socioeconomicoId ";
        
        return $this->update( $queryActualizaRespuesta );
    }

    public function finalizarEvaluacion( $params )
    {
        extract( $params );
        $queryFinalizaActualizacion = "UPDATE psocioeconomico set fechaRealizacion='$fecha' 
                                                                , evaluador='$evaluador',
                                                                comentarios ='$comentarios'  
                                                                WHERE id = $evaluacion  ";
        return $this->update( $queryFinalizaActualizacion );
    }

    public function getEmpleadosConSocioeconomico()
    {
        $querySocioeconomicos ="SELECT *, COUNT(es.respuesta) AS numeroPreguntas
                        FROM pempleado
                        INNER JOIN psocioeconomico ON psocioeconomico.idempleado = pempleado.nip
                        INNER JOIN pevaluacion_socioeconomico AS es ON es.idsocioeconomico = psocioeconomico.id
                        WHERE pempleado.status = 1
                        GROUP BY pempleado.nip ";
        return $this->select( $querySocioeconomicos );
    }

    public function getEmpleadosSinSocioeconomico()
    {
        $querySinSocioeconomico = "SELECT *, COUNT(es.respuesta) AS numeroPreguntas
                FROM pempleado
                LEFT JOIN psocioeconomico ON psocioeconomico.idempleado = pempleado.nip
                LEFT JOIN pevaluacion_socioeconomico AS es ON es.idsocioeconomico = psocioeconomico.id
                WHERE pempleado.status = 1 
                GROUP BY pempleado.nip ";
        return $this->select( $querySinSocioeconomico );
    }
}
