<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/nomina/ajax/reportes/prepareExcel.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/nomina/ajax/clases/Asistencias.php';


class ReporteAsistenciaFaltas extends PrepareExcel
{
    public function __construct()
    {
        parent::__construct();
        $this->creaEmptySheet('Reporte Faltas',1);
        $this->creaEmptySheet("Reporte Retardos",0);
    }

    public function generaFaltasRetardos( $fechaInicio = '', $fechaFin= '')
    {
        if ( $fechaInicio == '' || $fechaFin == '' ) {
            $fechaInicio = date('Y-m-1');
            $diasDelMes = cal_days_in_month(CAL_GREGORIAN, date('m'), date('d'));
            $fechaFin = date('Y-m-'.$diasDelMes);
        }
 
        $objAsistencia = new Asistencia;
        $listadoAsistencias = $objAsistencia->getAsistencia(array(
                'fechaInicio' => $fechaInicio,
                'fechaFin' => $fechaFin,
                'trabajador' => '',
                'puesto' => '',
                'nip' => ''
        ));
        
        $trabajador = array();
        foreach ($listadoAsistencias as  $asistenciaTrabajador) {
            $asistenciaTrabajador['dia'] = $asistenciaTrabajador['dia'] < 10 ? '0'.$asistenciaTrabajador['dia'] : $asistenciaTrabajador['dia'];
            $asistenciaTrabajador['mes'] = $asistenciaTrabajador['mes'] < 10 ? '0'.$asistenciaTrabajador['mes'] : $asistenciaTrabajador['mes'];
            $asistenciaTrabajador['hora'] = $asistenciaTrabajador['hora'] < 10 ? '0'.$asistenciaTrabajador['hora'] : $asistenciaTrabajador['hora'];
            $asistenciaTrabajador['minuto'] = $asistenciaTrabajador['minuto'] < 10 ? '0'.$asistenciaTrabajador['minuto'] : $asistenciaTrabajador['minuto'];

            if ( (isset($trabajador[$asistenciaTrabajador['nip']] ) ) ) {
                array_push( $trabajador[$asistenciaTrabajador['nip']]['asistencias'] ,  $asistenciaTrabajador['dia']."/".$asistenciaTrabajador['mes']."/".$asistenciaTrabajador['anio'] );
                if ( $asistenciaTrabajador['RETARDO'] > 0) {
                    array_push( $trabajador[$asistenciaTrabajador['nip']]['retardos'] ,   array('fecha' =>$asistenciaTrabajador['dia']."/".$asistenciaTrabajador['mes']."/".$asistenciaTrabajador['anio'], 'hora' => $asistenciaTrabajador['hora'].':'.$asistenciaTrabajador['minuto'] ) );
                }
            } else {
                $trabajador[$asistenciaTrabajador['nip']]['nip'] =  $asistenciaTrabajador['nip'];
                $trabajador[$asistenciaTrabajador['nip']]['nombre'] =  $asistenciaTrabajador['nombre'];
                $trabajador[$asistenciaTrabajador['nip']]['depto'] =  $asistenciaTrabajador['departamento'];
                $trabajador[$asistenciaTrabajador['nip']]['puesto'] =  $asistenciaTrabajador['puesto'];
                $trabajador[$asistenciaTrabajador['nip']]['sucursal'] =  $asistenciaTrabajador['sucursal'];
                $trabajador[$asistenciaTrabajador['nip']]['faltas'] =  array();
                $trabajador[$asistenciaTrabajador['nip']]['asistencias'] =  array($asistenciaTrabajador['dia']."/".$asistenciaTrabajador['mes']."/".$asistenciaTrabajador['anio']);
                if ( $asistenciaTrabajador['RETARDO'] > 0) {
                    $trabajador[$asistenciaTrabajador['nip']]['retardos'] =  array( array('fecha' =>$asistenciaTrabajador['dia']."/".$asistenciaTrabajador['mes']."/".$asistenciaTrabajador['anio'], 'hora' => $asistenciaTrabajador['hora'].':'.$asistenciaTrabajador['minuto'] ) );
                } else {
                    $trabajador[$asistenciaTrabajador['nip']]['retardos'] = array();
                }
                
            }
            
        }

        foreach ($trabajador as $nip => $trabajadorAsistencia) {
           $trabajador[$nip] = self::verificaFaltasTrabajador( array( 
                'fechaInicio' => $fechaInicio,
                'fechaFin' => $fechaFin,
                'asistencias' => $trabajadorAsistencia
            ));
        }
        return ($trabajador);
    }

    public function preparaReporte( $listadoAsistencias, $fechas)
    {
        extract( $fechas );
        $i = $j = 8;
        
        $this->libro->setActiveSheetIndex(1);
        $this->libro->getActiveSheet()->mergeCells("B5:E5");;
        $this->libro->getActiveSheet()->setCellValue("B5","Lista de inasistencias del ".str_replace('-','/', $fechaInicio)." al ".str_replace('-','/', $fechaFin));
        $this->libro->getActiveSheet()->getStyle("B5")->applyFromArray($this->centrarTexto);
        $this->libro->getActiveSheet()->getStyle("B5")->applyFromArray($this->labelBold);

        $this->libro->getActiveSheet()->setCellValue("B7", "Sucursal");
        $this->libro->getActiveSheet()->setCellValue("C7", "Departamento");
        $this->libro->getActiveSheet()->setCellValue("D7", 'Puesto');
        $this->libro->getActiveSheet()->setCellValue("E7","Nombre");
        $this->libro->getActiveSheet()->setCellValue("F7", "Fecha"); 
        $this->libro->getActiveSheet()->getStyle("A7:F7")->applyFromArray($this->centrarTexto);
        $this->libro->getActiveSheet()->getStyle("A7:F7")->applyFromArray($this->labelBold);
        $this->libro->getActiveSheet()->getStyle("A7:F7")->applyFromArray($this->setColorText('ffffff',11));
        $this->libro->getActiveSheet()->getStyle("A7:F7")->getFill()->applyFromArray( $this->setColorFill("cc0000")  );
        $this->libro->getActiveSheet()->setAutoFilter("A7:F7");
        $this->putLogo("C1",300,200);
        
        $this->libro->setActiveSheetIndex(0);
        $this->libro->getActiveSheet()->setCellValue("B7", "Sucursal");
        $this->libro->getActiveSheet()->setCellValue("C7", "Departamento");
        $this->libro->getActiveSheet()->setCellValue("D7", 'Puesto');
        $this->libro->getActiveSheet()->setCellValue("E7","Nombre");
        $this->libro->getActiveSheet()->setCellValue("F7", "Fecha");            
        $this->libro->getActiveSheet()->setCellValue("G7", "Fecha");        
        $this->libro->getActiveSheet()->getStyle("A7:G7")->applyFromArray($this->centrarTexto);
        $this->libro->getActiveSheet()->getStyle("A7:G7")->applyFromArray($this->labelBold); 
        $this->libro->getActiveSheet()->getStyle("A7:G7")->applyFromArray($this->setColorText('ffffff',11));
        $this->libro->getActiveSheet()->getStyle("A7:G7")->getFill()->applyFromArray( $this->setColorFill("cc0000")  );
        $this->putLogo("C1",300,200);
        $this->libro->getActiveSheet()->setAutoFilter("A7:F7");

        foreach ($listadoAsistencias as $nip => $trabajador) {
            
            foreach ($trabajador['faltas'] as $falta) {
                $this->libro->setActiveSheetIndex(1);
                $this->libro->getActiveSheet()->setCellValue("A$i", $i-7);
                $this->libro->getActiveSheet()->getStyle("A$i")->applyFromArray($this->labelBold); 
                $this->libro->getActiveSheet()->setCellValue("B$i", $trabajador['sucursal']);
                $this->libro->getActiveSheet()->setCellValue("C$i", $trabajador['depto']);
                $this->libro->getActiveSheet()->setCellValue("D$i", $trabajador['puesto']);
                $this->libro->getActiveSheet()->setCellValue("E$i", $trabajador['nombre']);
                $this->libro->getActiveSheet()->setCellValue("F$i", $falta['fecha']);
                $this->libro->getActiveSheet()->getRowDimension($i)->setRowHeight(30);
                $this->libro->getActiveSheet()->getStyle("B$i:F$i")->applyFromArray( $this->verticalCenter);
                $i++;
            }

            foreach ($trabajador['retardos'] as $retardo) {
                $this->libro->setActiveSheetIndex(0);
                $this->libro->getActiveSheet()->setCellValue("A$j", $j-7);
                $this->libro->getActiveSheet()->getStyle("A$j")->applyFromArray($this->labelBold); 
                $this->libro->getActiveSheet()->setCellValue("B$j", $trabajador['sucursal']);
                $this->libro->getActiveSheet()->setCellValue("C$j", $trabajador['depto']);
                $this->libro->getActiveSheet()->setCellValue("D$j", $trabajador['puesto']);
                $this->libro->getActiveSheet()->setCellValue("E$j", $trabajador['nombre']);
                $this->libro->getActiveSheet()->setCellValue("F$j", $retardo['fecha']);
                $this->libro->getActiveSheet()->setCellValue("G$j", $retardo['hora']);
                $this->libro->getActiveSheet()->getRowDimension($j)->setRowHeight(30);
                $this->libro->getActiveSheet()->getStyle("B$j:G$j")->applyFromArray( $this->verticalCenter);
                $j++;
            }
        }
        
        $this->libro->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
        $this->libro->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
        $this->libro->getActiveSheet()->getColumnDimension("C")->setAutoSize(true);
        $this->libro->getActiveSheet()->getColumnDimension("D")->setAutoSize(true);
        $this->libro->getActiveSheet()->getColumnDimension("E")->setAutoSize(true);
        $this->libro->getActiveSheet()->getColumnDimension("F")->setAutoSize(true);
        $this->libro->getActiveSheet()->getColumnDimension("G")->setAutoSize(true);

        $this->libro->setActiveSheetIndex(1);
        $this->libro->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
        $this->libro->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
        $this->libro->getActiveSheet()->getColumnDimension("C")->setAutoSize(true);
        $this->libro->getActiveSheet()->getColumnDimension("D")->setAutoSize(true);
        $this->libro->getActiveSheet()->getColumnDimension("E")->setAutoSize(true);
        $this->libro->getActiveSheet()->getColumnDimension("F")->setAutoSize(true);        
        $reporteTerminado = new PHPExcel_Writer_Excel2007( $this->libro);
        $reporteTerminado->save('Reporte retardos y faltas.xlsx');
    }

    public function verificaFaltasTrabajador($params)
    {
        extract( $params );
        $inicioExplode = explode('-', $fechaInicio);
        $finExplode = explode('-', $fechaFin);
        $mesInicio = $inicioExplode[1]/1;
        $mesFin = $finExplode[1]/1;
        $diaInicio = $inicioExplode[2] /1;
        $diaFin = $finExplode[2] / 1;

        
        for ($i= $mesInicio; $i <= $mesFin ; $i++) { 
            $mes = $i < 10 ? '0'.$i : $i;
            if ( $mesInicio !=  $i) {
                $diaInicio = 1;
            }
            if ( $mesFin != $i) {
                $diaFin = cal_days_in_month(CAL_GREGORIAN, $i, $inicioExplode[0]);
            }

            for ($j= $diaInicio; $j <= $diaFin ; $j++) { 
                $dia =  $j < 10 ? '0'.$j : $j;
                    
                if (strftime("%a", strtotime($mes."/".$dia."/".$inicioExplode[0])) != "Sun" ) {
                    
                    if ( ! in_array($dia.'/'.$mes.'/'.$inicioExplode[0], $asistencias['asistencias']) ) {
                        if ( $j <= ( date('d')/1) && $i <= (date('m')/1 ) ) {
                            array_push( $asistencias['faltas'] , array( 'fecha' =>$dia.'/'.$mes.'/'.$inicioExplode[0]) );
                        }elseif ($j >= ( date('d')/1) && $i < (date('m')/1 ) ) {
                            array_push( $asistencias['faltas'] , array( 'fecha' =>$dia.'/'.$mes.'/'.$inicioExplode[0]) );
                        }
                    }							
                }

            }
            
            
        }        
        return $asistencias;
    }
}

$reporte = new ReporteAsistenciaFaltas;
$reporte->preparaReporte($reporte->generaFaltasRetardos(), array('fechaInicio' => date('Y-m-1'),'fechaFin' => date('Y-m-d') ) ); 