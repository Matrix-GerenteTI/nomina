<?php

require_once $_SERVER['DOCUMENT_ROOT']."/nomina/ajax/reportes/prepareExcel.php";
require_once $_SERVER['DOCUMENT_ROOT']."/intranet/modelos/nomina/trabajadores.php";



class DocumentacionDigitar extends PrepareExcel
{
    protected $modeloTrabajador;

    public function __construct(){
        parent::__construct();
        $this->creaEmptySheet('Documentacion disponible',1);
        $this->modeloTrabajador  = new Trabajador;
    }

    public function generaReporte( )
    {
        $trabajadoresActivos = $this->modeloTrabajador->getEmpleadosActivos('%','%','%','%');
        $documentacion = $this->modeloTrabajador->getDocumentacionAllTrabajadores();
        $listaTipoDoctos = $this->modeloTrabajador->getTipoDocumentos();
        echo "<pre>";
        var_dump($trabajadoresActivos);
        echo "</pre>";
        die();
        //asigando la letra de la columna al tipo de documento
        $letraInicio = 67;
        $columnas = [];
        $ultimaColumna = "C";
        $this->putLogo("C1", 350, 200  );
        $this->libro->getActiveSheet()->setCellValue("B6", "Reporte de Documentación Disponible de Empleados" );
        $this->libro->getActiveSheet()->mergeCells("B6:H6");
        $this->libro->getActiveSheet()->getStyle("B6")->applyFromArray($this->centrarTexto);
        $this->libro->getActiveSheet()->getStyle("B6")->applyFromArray($this->labelBold);        

        
        $this->libro->getActiveSheet()->setCellValue( "A8", "NOMBRE" );
        $this->libro->getActiveSheet()->setCellValue( "B8",  "SUCURSAL");
        $this->libro->getActiveSheet()->getStyle("A8:B8")->applyFromArray($this->centrarTexto);
        $this->libro->getActiveSheet()->getStyle("A8:B8")->applyFromArray($this->labelBold);        

        $this->libro->getActiveSheet()->getStyle("A8:B8")->getFill()->applyFromArray( $this->setColorFill("cc0000")  );   

        foreach ( $listaTipoDoctos as $tipoDocto) {
            $columnas[ $tipoDocto['id'] ] = chr( $letraInicio );
            $this->libro->getActiveSheet()->setCellValue( $columnas[ $tipoDocto['id'] ]."8",  $tipoDocto['descripcion'] );
            $this->libro->getActiveSheet()->getStyle($columnas[ $tipoDocto['id'] ]."8")->applyFromArray($this->centrarTexto);
            $this->libro->getActiveSheet()->getStyle($columnas[ $tipoDocto['id'] ]."8")->applyFromArray($this->labelBold);
            $this->libro->getActiveSheet()->getStyle($columnas[ $tipoDocto['id'] ]."8") ->getAlignment()->setWrapText(true); 
            $this->libro->getActiveSheet()->getStyle($columnas[ $tipoDocto['id'] ]."8")->applyFromArray($this->setColorText('ffffff',11));
            $this->libro->getActiveSheet()->getStyle($columnas[ $tipoDocto['id'] ]."8")->getFill()->applyFromArray( $this->setColorFill("cc0000")  );        
            $ultimaColumna = chr( $letraInicio );   
            $letraInicio++;

        }
        

        $ultimoEmpleado = '';
        $i = 8;
        $j = 0;
        foreach ( $trabajadoresActivos as $trabajador ) {
            $documento = $this->modeloTrabajador->getDocumentacionTrabajador($trabajador['nip']);
            
            if ( $ultimoEmpleado != $trabajador['nombre'] ) {
                $i++;
                $ultimoEmpleado = $trabajador['nombre'];
                $j = 0;
            }


            $this->libro->getActiveSheet()->setCellValue("A$i", $documento['empleado'] );
            $this->libro->getActiveSheet()->setCellValue("B$i", $documento['sucursal'] );
            if(count($documento)>0){
                echo "<pre>";
                var_dump($documento);
                echo "</pre>";
                foreach($documento as $doc){
                    $this->libro->getActiveSheet()->setCellValue( $columnas[$doc['iddoct']]."$i", "Sí" );
                }
            }else{
                foreach ( $listaTipoDoctos as $tipoDocto2) {
                    $this->libro->getActiveSheet()->setCellValue( $columnas[$tipoDocto2['id']]."$i", "Sí" );
                }
            }
            
            //Verificando si tiene foto de perfil del empleado
            
            $formatos = ['jpg','jpeg'];
            foreach ($formatos as  $formato) {
                if( file_exists( $_SERVER['DOCUMENT_ROOT']."/intranet/Empresa/foto_empleado/".$documento['nip'].".$formato")  ){
                    $this->libro->getActiveSheet()->setCellValue( $columnas[ 13 ]."$i", "Sí" );
                    $this->libro->getActiveSheet()->getStyle($columnas[13]."$i")->applyFromArray($this->labelBold);
                    $this->libro->getActiveSheet()->getStyle($columnas[13]."$i")->applyFromArray($this->centrarTexto);
                    $this->libro->getActiveSheet()->getStyle($columnas[13]."$i")->applyFromArray($this->setColorText('ffffff',11));
                    $this->libro->getActiveSheet()->getStyle($columnas[13]."$i")->getFill()->applyFromArray( $this->setColorFill("aed581")  );        

                    $this->libro->getActiveSheet()->getColumnDimension( $columnas[13] )->setAutoSize(false );
                    $this->libro->getActiveSheet()->getColumnDimension( $columnas[13]  )->setWidth( 15);
                }
            }




            $this->libro->getActiveSheet()->getStyle($columnas[$documento['iddoct']]."$i")->applyFromArray($this->labelBold);
            $this->libro->getActiveSheet()->getStyle($columnas[$documento['iddoct']]."$i")->applyFromArray($this->centrarTexto);
            $this->libro->getActiveSheet()->getStyle($columnas[$documento['iddoct']]."$i")->applyFromArray($this->setColorText('ffffff',11));
            $this->libro->getActiveSheet()->getStyle($columnas[$documento['iddoct']]."$i")->getFill()->applyFromArray( $this->setColorFill("aed581")  );        

            $this->libro->getActiveSheet()->getColumnDimension( $columnas[$documento['iddoct']] )->setAutoSize(false );
            $this->libro->getActiveSheet()->getColumnDimension( $columnas[$documento['iddoct']]  )->setWidth( 15);
        }

        $this->libro->getActiveSheet()->getColumnDimension("A")->setAutoSize(false );
        $this->libro->getActiveSheet()->getColumnDimension('A')->setWidth( 40);
        $this->libro->getActiveSheet()->getColumnDimension("B")->setAutoSize(false );
        $this->libro->getActiveSheet()->getColumnDimension('B')->setWidth( 25);
        $this->libro->getActiveSheet()->getStyle("A8:$ultimaColumna".( $i )  )->applyFromArray( $this->bordes );

        $reporteTerminado = new PHPExcel_Writer_Excel2007( $this->libro);
        $reporteTerminado->save( 'documentacion'.(strtotime(date("Y-m-d H:i:s")) * 1000).'.xlsx');
        echo 'documentacion'.(strtotime(date("Y-m-d H:i:s")) * 1000).".xlsx";

    }

    
}


$documentacion = new DocumentacionDigitar;
$documentacion->generaReporte();