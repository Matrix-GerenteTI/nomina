<?php
require_once  $_SERVER['DOCUMENT_ROOT']."/nomina/ajax/modelo/socioeconomico.php";
require_once  $_SERVER['DOCUMENT_ROOT']."/nomina/ajax/modelo/empleados.php";


class SocioeconomicoController
{
    
    public function verificarExistenciaSocioeconomico( $trabajador )
    {
        $socioeconomico = new Socioeconomico;
        $evaluacion = $socioeconomico->getUltimoSocioeconomico( $trabajador );

        if (  empty( $evaluacion) ) {
            
            $evaluacion = self::creaRegistroSocioeconomico( $trabajador );;
            return $evaluacion;
        }else{
            return $evaluacion[0]['id'];
        }
    }

    public function getEvaluacion( $evaluacion )
    {
        $socioeconomico = new Socioeconomico;
        if( $evaluacion != ''){
            //obteniendo todas las preguntas con sus respectivas respuestas del trabajador
            $preguntasRespuestas = $socioeconomico->getPreguntasRespuestasEvaluacion( $evaluacion); 
            foreach ($preguntasRespuestas as $i => $preguntaRespuesta) {
                //obteniendo las respuestas tipo checkbox
                $respuestasPredeterminadas =$socioeconomico->getRespuestaPredeterminada( $preguntaRespuesta['id']);
                foreach ($respuestasPredeterminadas as $j => $respuesta) {
                    //Decodificando los caracteres especiales
                    $respuestasPredeterminadas[ $j ] ['descripcion'] = utf8_encode( $respuesta['descripcion']);
                    $respuestasPredeterminadas[ $j ] ['descripcion'] = utf8_decode( $respuestasPredeterminadas[ $j ] ['descripcion'] );

                    $buscaOpcion = strpos($preguntaRespuesta['respuesta'] ,  $respuestasPredeterminadas[ $j]['descripcion'] );

                    if ( $buscaOpcion !== false  ) {
                        $respuestasPredeterminadas[$j]['checked'] = "checked";
                    } else {
                        $respuestasPredeterminadas[$j]['checked'] = "";
                    }
                    
                }
                $preguntasRespuestas[$i]['opciones'] = $respuestasPredeterminadas;
                            //Cargamos las observaciones , fecha de evaluación y el evaluador del estudio socioeconomico
                $detalleSocioeconomico['evaluacion'] = $preguntasRespuestas;
                $detalleSocioeconomico['generalesEvaluacion'] = $socioeconomico->getDetalleGeneral( $evaluacion )[0];
                
            }
            return $detalleSocioeconomico;
        }else{
            //carga las preguntas unicamente las preguntas
            
            $preguntas = $socioeconomico->getAllPreguntas();
            foreach ($preguntas as $i => $pregunta) {
                $respuestasPredeterminadas = $socioeconomico->getRespuestaPredeterminada( $pregunta['id'] );
                foreach ($respuestasPredeterminadas as $j => $respuesta) {
                    $respuestasPredeterminadas[ $j ] ['descripcion'] = utf8_encode( $respuesta['descripcion']);
                    $respuestasPredeterminadas[ $j ] ['descripcion'] = utf8_decode( $respuestasPredeterminadas[ $j ] ['descripcion'] );
                }
                $preguntas[$i]['opciones'] = $respuestasPredeterminadas;
            }


            return $preguntas;
        }


    }

    public function guardaSeccionRespuestas( $socioeconomicoId, $preguntas )
    {
        $listadoPreguntas = json_decode( $preguntas );
        $socioeconomico = new Socioeconomico;
        $cantInserciones = 0;
        foreach ($listadoPreguntas as  $pregunta) {
            $dataInsert = array(
                'preguntaId' => $pregunta->id,
                'socioeconomicoId' => $socioeconomicoId,
                'respuesta' => $pregunta->respuesta
            );
         $inserciones = $socioeconomico->registraRespuestaEvaluacion( $dataInsert);
         
            if ( $inserciones == 0) {
                $cantInserciones++;
            }elseif( $inserciones == -1){
                $actualizacion = $socioeconomico->actualizaRespuestaEvaluacion( $dataInsert);
                if ( $actualizacion > 0) {
                    $cantInserciones++;
                }
            }
        }

        return $cantInserciones;
    }
    public function creaRegistroSocioeconomico( $trabajador )
    {
        $socioeconomico = new Socioeconomico;
        
        return ( $socioeconomico->creaRegistroSocioeconomico( $trabajador ) );
    }

    public function finalizaEvaluacion( $dataFinEvaluacion )
    {
        $socioeconomico = new Socioeconomico;
        return $socioeconomico->finalizarEvaluacion( $dataFinEvaluacion );
    }

    public function actualizaDatosPersonales( $datosPersonales )
    {
        
        $empleado = new Empleados;
        //Actualizando datos personales del trabajador de la tabla pempleados
        $statusDatosPersonales = $empleado->actualizaDatosPersonales( $datosPersonales );
        //Actualizando la dirección del usuario
        $statusDirecion = $empleado->actualizaDireccionTrabajador( $datosPersonales );
        //Agregando el lugar de nacimiento del trabajador
        $statusNacimiento = $empleado->agregaLugarNacimiento( $datosPersonales );
        if ( $statusDatosPersonales == 0) {
            $socioeconomico = new Socioeconomico;
            $statusNacimiento = $socioeconomico->actualizaRespuestaEvaluacion(['respuesta' => $datosPersonales['lugarNac'],
                                                                                                        'socioeconomicoId' => $datosPersonales['socioeconomico'],
                                                                                                        'preguntaId' => 1  ] );
        }

        return $statusDatosPersonales.''.$statusDirecion.$statusNacimiento;
    }

}


$opc = isset( $_GET['opc'] ) ?  $_GET['opc'] : $_POST['opc'];

switch ($opc) {
    case 'verificaEvaluacionRealizada':
        echo SocioeconomicoController::verificarExistenciaSocioeconomico( $_GET['trabajador'] );
        break;
    
    case 'getEvaluacion':
    
        echo json_encode( SocioeconomicoController::getEvaluacion( $_GET['evaluacion']) );
    break;

    case 'guardaSeccion':
    
            echo SocioeconomicoController::guardaSeccionRespuestas( $_POST['socioeconomico'] , $_POST['preguntas']);
        break;
    case 'guardaFinal':
            echo SocioeconomicoController::finalizaEvaluacion($_POST);
        break;
    case 'guardaPersonalData':
        echo SocioeconomicoController::actualizaDatosPersonales($_POST );
        break;
    case 'getImagenesSocioeconomico':

        if ( ! file_exists($_SERVER['DOCUMENT_ROOT']."/nomina/socioeconomico/".$_POST['empleado']) ) {
            mkdir( $_SERVER['DOCUMENT_ROOT']."/nomina/socioeconomico/".$_POST['empleado'], 0777, true);
        }
        $extension = pathinfo($_FILES['imagen']['name'] , PATHINFO_EXTENSION );

        $pathToSave = $_SERVER['DOCUMENT_ROOT']."/nomina/socioeconomico/".$_POST['empleado']."/";
        $imagenSubida = move_uploaded_file($_FILES['imagen']['tmp_name'], $pathToSave.$_POST['nombreFoto'].".$extension" );

        if ( $imagenSubida ) {
            echo 1;
        }else{
            echo -1;
        }
        break;
    default:
        # code...
        break;
}
