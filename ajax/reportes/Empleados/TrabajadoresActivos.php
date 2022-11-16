<?php
error_reporting(E_ERROR | E_PARSE);
ini_set('default_charset', 'UTF-8');

require_once $_SERVER['DOCUMENT_ROOT']."/nomina/ajax/reportes/prepareExcel.php";
require_once $_SERVER['DOCUMENT_ROOT']."/intranet/modelos/nomina/trabajadores.php";
//require_once $_SERVER['DOCUMENT_ROOT']."/nomina/ajax/modelo/socioeconomico.php";


class TrabajadoresActivos extends PrepareExcel 
{

    protected $modeloTrabajador;

    public function __construct()
    {
        parent::__construct();
        $this->creaEmptySheet('Trabajadores Activos',1);
        $this->modeloTrabajador  = new Trabajador;
    }


    public function generaReporte( )
    {
        //$socioeconomico = new Socioeconomico;
        $listaTrabajadores = $this->modeloTrabajador->getNominaActiva( );
        $listaTipoDoctos = $this->modeloTrabajador->getTipoDocumentos();

        $this->putLogo("B1",300, 200 );
        $this->libro->getActiveSheet()->mergeCells("A6:E6");
        $this->libro->getActiveSheet()->setCellValue("A6","Listado de Trabajadores Activos en Sistema de Nómina");
        $this->libro->getActiveSheet()->getStyle("A6")->applyFromArray($this->centrarTexto);
        $this->libro->getActiveSheet()->getStyle("A6")->applyFromArray($this->labelBold);        

        $i = 9;

        $this->libro->getActiveSheet()->setCellValue("A8","NIP");
        $this->libro->getActiveSheet()->setCellValue("B8","EMPLEADO");
        $this->libro->getActiveSheet()->setCellValue("C8","DEPARTAMENTO");
        $this->libro->getActiveSheet()->setCellValue("D8","PUESTO");
        $this->libro->getActiveSheet()->setCellValue("E8","SUCURSAL");
        $this->libro->getActiveSheet()->setCellValue("F8","PUESTO ANTERIOR");
        $this->libro->getActiveSheet()->setCellValue("G8","SUCURSAL ANTERIOR");        
        $this->libro->getActiveSheet()->setCellValue("H8","FECHA ULT. CAMBIO");
        $this->libro->getActiveSheet()->setCellValue("I8","FECHA NAC.");
        $this->libro->getActiveSheet()->setCellValue("J8","FECHA DE INCIO LABORAL");
        $this->libro->getActiveSheet()->setCellValue("K7","ANTIGUEDAD LABORAL");
        $this->libro->getActiveSheet()->setCellValue("N8","CODIGO POSTAL");
        $this->libro->getActiveSheet()->setCellValue("O8","NÚMERO TEL.");
        $this->libro->getActiveSheet()->setCellValue("P8","EDAD");
        $this->libro->getActiveSheet()->setCellValue("Q8","RELIGIÓN");
        $this->libro->getActiveSheet()->setCellValue("R8","ESTADO CIVIL");
        $this->libro->getActiveSheet()->setCellValue("S8","DESCENDENCIAS");
        $this->libro->getActiveSheet()->setCellValue("T8","No. HIJOS");
        $this->libro->getActiveSheet()->setCellValue("U8","ASEGURADO");
        $this->libro->getActiveSheet()->setCellValue("V8","SEXO");
        $this->libro->getActiveSheet()->setCellValue("W8","ESCOLARIDAD");
        $this->libro->getActiveSheet()->setCellValue("X8","TIPO DE SANGRE");
        $this->libro->getActiveSheet()->setCellValue("Y8","ALERGIA A MEDICAMENTOS");
        $this->libro->getActiveSheet()->setCellValue("Z8","DOMICILIO");
        $this->libro->getActiveSheet()->setCellValue("AA8","NSS");
        $this->libro->getActiveSheet()->setCellValue("AB8","CURP");
        $this->libro->getActiveSheet()->setCellValue("AC8","RFC");
        $this->libro->getActiveSheet()->setCellValue("AD8","EMAIL");
        $this->libro->getActiveSheet()->setCellValue("AE8","SOCIOECONOMICO");
        /*$this->libro->getActiveSheet()->setCellValue("AW8","TIPO DE CONTRATO");
        $this->libro->getActiveSheet()->setCellValue("AX8","TIPO DE CONTRATO");
        $this->libro->getActiveSheet()->setCellValue("AY8","TIPO DE CONTRATO");
        $this->libro->getActiveSheet()->setCellValue("AZ8","TIPO DE CONTRATO");*/

        $letraInicio = 70;
        $columnas = [];
        $ultimaColumna = "F";
        foreach ( $listaTipoDoctos as $tipoDocto) {
            $columnas[ $tipoDocto['id'] ] = chr( $letraInicio );
            $this->libro->getActiveSheet()->setCellValue( "A".$columnas[ $tipoDocto['id'] ]."8",  $tipoDocto['descripcion'] );
            // $this->libro->getActiveSheet()->getStyle("A".$columnas[ $tipoDocto['id'] ]."8")->applyFromArray($this->centrarTexto);
            // $this->libro->getActiveSheet()->getStyle("A".$columnas[ $tipoDocto['id'] ]."8")->applyFromArray($this->labelBold);
            $this->libro->getActiveSheet()->getStyle("A".$columnas[ $tipoDocto['id'] ]."8") ->getAlignment()->setTextRotation(90);; 
            $this->libro->getActiveSheet()->getStyle("A".$columnas[ $tipoDocto['id'] ]."8")->applyFromArray($this->setColorText('ffffff',9));
            // $this->libro->getActiveSheet()->getStyle("A".$columnas[ $tipoDocto['id'] ]."8")->getFill()->applyFromArray( $this->setColorFill("cc0000")  );        
            $ultimaColumna = chr( $letraInicio );   
            $letraInicio++;

        }

        $this->libro->getActiveSheet()->mergeCells("K7:M7");
        $this->libro->getActiveSheet()->getStyle("K7")->applyFromArray($this->centrarTexto);
        $this->libro->getActiveSheet()->getStyle("K7")->applyFromArray($this->labelBold);

        $this->libro->getActiveSheet()->setCellValue("K8","AÑOS");
        $this->libro->getActiveSheet()->setCellValue("L8","MESES");
        $this->libro->getActiveSheet()->setCellValue("M8","DIAS");

        $this->libro->getActiveSheet()->setAutoFilter("A8:A".$ultimaColumna."8");
        $this->libro->getActiveSheet()->getStyle("A8:A".$ultimaColumna."8")->applyFromArray($this->centrarTexto);

        $this->libro->getActiveSheet()->getStyle("A8:A".$ultimaColumna."8")->getFill()->applyFromArray( $this->setColorFill("cc0000")  );   
        $this->libro->getActiveSheet()->getStyle("A8:A".$ultimaColumna."8")->applyFromArray($this->labelBold);
        $this->libro->getActiveSheet()->getStyle("A8:A".$ultimaColumna."8")->getAlignment()->setWrapText(true); 
        $this->libro->getActiveSheet()->getStyle("A8:A".$ultimaColumna."8")->applyFromArray($this->setColorText('ffffff',11));

        $nHombres = 0;
        $nMujeres = 0;
        
        foreach ( $listaTrabajadores as $empleado ) {
            $fechaActual = date_create( date("Y-m-d")  );
            $fechaNacimiento = date_create( $empleado['fechanac'] );
            $edad = date_diff( $fechaNacimiento , $fechaActual );
            $ultimoCambioAscripcion = $this->modeloTrabajador->ultimoCambioAdscripcion( $empleado['nip'] );
            $puestoAnterior =  $ultimoCambioAscripcion[0]['fecha'] != ''  > 0 ? $ultimoCambioAscripcion[0]['puesto'] : "-";
            $sucursalAnterior = $ultimoCambioAscripcion[0]['fecha'] != '' ? $ultimoCambioAscripcion[0]['sucursal'] : "-";

            $this->libro->getActiveSheet()->setCellValue("A$i", $empleado['nip'] );
            $this->libro->getActiveSheet()->setCellValue("B$i", $empleado['nombre'] );
            $this->libro->getActiveSheet()->setCellValue("C$i", $empleado['departamento'] );
            $this->libro->getActiveSheet()->setCellValue("D$i", $empleado['puesto'] ); 
            $this->libro->getActiveSheet()->setCellValue("E$i", $empleado['sucursal'] );
            $this->libro->getActiveSheet()->setCellValue("F$i", mb_convert_encoding($puestoAnterior, "UTF-8" )  );
            $this->libro->getActiveSheet()->setCellValue("G$i", $sucursalAnterior );
            $this->libro->getActiveSheet()->setCellValue("H$i", $ultimoCambioAscripcion[0]['fecha'] != '' ? PHPExcel_Shared_Date::PHPToExcel( $ultimoCambioAscripcion[0]['fecha']  )   : "-" );
            
            $this->libro->getActiveSheet()->setCellValue("I$i", PHPExcel_Shared_Date::PHPToExcel( $empleado['fechanac'] ) );
            $this->libro->getActiveSheet()
                ->getStyle('H'.$i.":J$i")->getNumberFormat()
            ->setFormatCode('dd/MM/yyyy');

            $this->libro->getActiveSheet()->setCellValue("J$i", PHPExcel_Shared_Date::PHPToExcel( $empleado['fechainiciolab'] ) );
            
            $this->libro->getActiveSheet()->setCellValue("N$i", $empleado['cp'] );
            $this->libro->getActiveSheet()->setCellValue("O$i", $empleado['celular'] == '' ? '-' :  $empleado['celular'] );
            $this->libro->getActiveSheet()->setCellValue("P$i", $edad->y);
            $this->libro->getActiveSheet()->setCellValue("Q$i", $empleado['religion'] != '' ? mb_convert_encoding($empleado['religion'] , "UTF-8" ) : '-' );
            $this->libro->getActiveSheet()->setCellValue("R$i", $empleado['edocivil'] );
            $this->libro->getActiveSheet()->setCellValue("S$i", $empleado['numhijos'] == '0-0' ? "NO" : "SI" );
            //Obteniendo la cantidad de hijos que tiene
            $explodeHijos = explode("-", $empleado['numhijos'] );

            $this->libro->getActiveSheet()->setCellValue("T$i", $explodeHijos[0] + $explodeHijos[1] );
            $this->libro->getActiveSheet()->setCellValue("U$i", $empleado['asegurado'] == 'n' ? "NO" : 'Sí' );
            $this->libro->getActiveSheet()->setCellValue("V$i", $empleado['sexo'] );
            $this->libro->getActiveSheet()->setCellValue("W$i", $empleado['nivelestudios'] != '' ? $empleado['nivelestudios'] : '-' );
            $this->libro->getActiveSheet()->setCellValue("X$i", $empleado['tiposangre']  != '' ? $empleado['tiposangre'] : '-' );
            $this->libro->getActiveSheet()->setCellValue("Y$i", $empleado['alergias'] != '' ? $empleado['alergias'] : '-' );
            $this->libro->getActiveSheet()->setCellValue("Z$i", $empleado['direccion'] != '' ? $empleado['direccion'] : '-' );
            $this->libro->getActiveSheet()->setCellValue("AA$i", $empleado['nss'] != '' ? $empleado['nss'] : '-' );
            $this->libro->getActiveSheet()->setCellValue("AB$i", $empleado['curp'] != '' ? $empleado['curp'] : '-' );
            $this->libro->getActiveSheet()->setCellValue("AC$i", $empleado['rfc'] != '' ? $empleado['rfc'] : '-' );
            $this->libro->getActiveSheet()->setCellValue("AD$i", $empleado['email'] != '' ? $empleado['email'] : '-' );
            //Socioeconomico
            $rowSocioeconomico = $this->modeloTrabajador->getUltimoSocioeconomico($empleado['nip']);
            $this->libro->getActiveSheet()->setCellValue("AE$i", $rowSocioeconomico[0]['fechaRealizacion'] != '' ? PHPExcel_Shared_Date::PHPToExcel($rowSocioeconomico[0]['fechaRealizacion']) : '' );
            $this->libro->getActiveSheet()->getStyle("AE$i")->getNumberFormat()->setFormatCode('dd/MM/yyyy');
            $this->libro->getActiveSheet()->getStyle("AE$i")->applyFromArray($this->centrarTexto);

            //DOCUMENTOS DEL EMPLEADO
            $documentosTrabajador = $this->modeloTrabajador->getDocumentacionTrabajador($empleado['nip']);
            
            $letraInicio0 = 70;
            $columnas0 = [];
            $ultimaColumna0 = "F";
            foreach ( $listaTipoDoctos as $tipoDocto0) {
                $columnas0[ $tipoDocto0['id'] ] = chr( $letraInicio0 );

                $sih = 0;
                foreach($documentosTrabajador as $docTrabajador){
                    if($docTrabajador['iddoct']==$tipoDocto0['id']){
                        $sih++;
                        $this->libro->getActiveSheet()->setCellValue( "A".$columnas0[ $docTrabajador['iddoct'] ].$i,  'Si' );
                        $this->libro->getActiveSheet()->getStyle("A".$columnas0[ $docTrabajador['iddoct'] ].$i)->applyFromArray($this->centrarTexto);
                    }
                }
                if($sih==0){                  
                    $pos = strpos($empleado['puesto'], 'CHOFER');
                    if($pos === false && ($tipoDocto0['id']==8 || $tipoDocto0['id']==12)){
                        $this->libro->getActiveSheet()->setCellValue( "A".$columnas0[ $tipoDocto0['id'] ].$i,  'N/A' );
                        $this->libro->getActiveSheet()->getStyle("A".$columnas0[ $tipoDocto0['id'] ].$i)->applyFromArray($this->centrarTexto);
                    }
                }     
                $ultimaColumna0 = chr( $letraInicio0 );   
                $letraInicio0++;

            }

            
            
            

            if ( $empleado['sexo'] == 'HOMBRE') {
                $nHombres ++;
            } else {
                $nMujeres ++;
            }
            
            //Obteniendo la antiguead del trabajador
            $fechcaInicioLaborar = date_create( $empleado['fechainiciolab']  );
            $fechaActual = date_create( date("Y-m-d")  );
           
            $diferencia = date_diff($fechcaInicioLaborar, $fechaActual);
            $this->libro->getActiveSheet()->setCellValue("K$i", $diferencia->y );
            $this->libro->getActiveSheet()->setCellValue("L$i", $diferencia->m );
            $this->libro->getActiveSheet()->setCellValue("M$i", $diferencia->d );
            
           
            $this->libro->getActiveSheet()->getStyle("A$i")->applyFromArray($this->centrarTexto);
            $this->libro->getActiveSheet()->getStyle("B$i")->applyFromArray($this->centrarTexto);

            // $this->libro->getActiveSheet()->getStyle("E$i")->applyFromArray($this->labelBold);
            // $this->libro->getActiveSheet()->getStyle("E$i")->applyFromArray($this->centrarTexto);
            $this->libro->getActiveSheet()->getStyle("E$i:V$i")->applyFromArray($this->centrarTexto);
            $this->libro->getActiveSheet()->getStyle("J$i")->applyFromArray($this->labelBold);
            $this->libro->getActiveSheet()->getStyle("K$i")->applyFromArray($this->labelBold);
            $this->libro->getActiveSheet()->getStyle("L$i")->applyFromArray($this->labelBold);
            // $this->libro->getActiveSheet()->getStyle("I$i")->applyFromArray($this->labelBold);
            // $this->libro->getActiveSheet()->getStyle("I$i")->applyFromArray($this->centrarTexto);
            
            $i++;
        }
        $this->libro->getActiveSheet()->setCellValue("E3","HOMBRES: ");
        $this->libro->getActiveSheet()->setCellValue("F3", $nHombres);      
        $this->libro->getActiveSheet()->setCellValue("E4","MUJERES");
        $this->libro->getActiveSheet()->setCellValue("F4", $nMujeres);      

        $this->libro->getActiveSheet()->getStyle("A8:A".$ultimaColumna."".( $i-1)  )->applyFromArray( $this->bordes );

        $this->libro->getActiveSheet()->getColumnDimension("B")->setAutoSize(false );
        $this->libro->getActiveSheet()->getColumnDimension('B')->setWidth( 45);
        $this->libro->getActiveSheet()->getColumnDimension("C")->setAutoSize(false );
        $this->libro->getActiveSheet()->getColumnDimension('C')->setWidth( 25);
        $this->libro->getActiveSheet()->getColumnDimension("D")->setAutoSize(false );
        $this->libro->getActiveSheet()->getColumnDimension('D')->setWidth( 25);
        $this->libro->getActiveSheet()->getColumnDimension("E")->setAutoSize(false );
        $this->libro->getActiveSheet()->getColumnDimension('E')->setWidth( 20);
        $this->libro->getActiveSheet()->getColumnDimension("F")->setAutoSize(false );
        $this->libro->getActiveSheet()->getColumnDimension('F')->setWidth( 20);

        $this->libro->getActiveSheet()->getColumnDimension("G")->setAutoSize(false );
        $this->libro->getActiveSheet()->getColumnDimension('G')->setWidth( 20);
        $this->libro->getActiveSheet()->getColumnDimension("H")->setAutoSize(false );
        $this->libro->getActiveSheet()->getColumnDimension('H')->setWidth( 15);
        $this->libro->getActiveSheet()->getColumnDimension("I")->setAutoSize(false );
        $this->libro->getActiveSheet()->getColumnDimension('I')->setWidth( 15 );
        $this->libro->getActiveSheet()->getColumnDimension("J")->setAutoSize(false );
        $this->libro->getActiveSheet()->getColumnDimension('J')->setWidth( 15);

        $this->libro->getActiveSheet()->getColumnDimension("K")->setAutoSize(false );
        $this->libro->getActiveSheet()->getColumnDimension('K')->setWidth( 10);
        $this->libro->getActiveSheet()->getColumnDimension("L")->setAutoSize(false );
        $this->libro->getActiveSheet()->getColumnDimension('L')->setWidth( 10);
        $this->libro->getActiveSheet()->getColumnDimension("M")->setAutoSize(false );
        $this->libro->getActiveSheet()->getColumnDimension('M')->setWidth( 10);
        $this->libro->getActiveSheet()->getColumnDimension("N")->setAutoSize(false );
        $this->libro->getActiveSheet()->getColumnDimension('N')->setWidth( 20);
        $this->libro->getActiveSheet()->getColumnDimension("O")->setAutoSize(false );
        $this->libro->getActiveSheet()->getColumnDimension('O')->setWidth( 20);
        $this->libro->getActiveSheet()->getColumnDimension("P")->setAutoSize(false );
        $this->libro->getActiveSheet()->getColumnDimension('P')->setWidth( 20);
        $this->libro->getActiveSheet()->getColumnDimension("Q")->setAutoSize(false );
        $this->libro->getActiveSheet()->getColumnDimension('Q')->setWidth( 20);
        $this->libro->getActiveSheet()->getColumnDimension("R")->setAutoSize(false );
        $this->libro->getActiveSheet()->getColumnDimension('R')->setWidth( 20);
        $this->libro->getActiveSheet()->getColumnDimension("S")->setAutoSize(false );
        $this->libro->getActiveSheet()->getColumnDimension('S')->setWidth( 20);
        $this->libro->getActiveSheet()->getColumnDimension("T")->setAutoSize(false );
        $this->libro->getActiveSheet()->getColumnDimension('T')->setWidth( 20);
        $this->libro->getActiveSheet()->getColumnDimension('U')->setAutoSize( FALSE);
        $this->libro->getActiveSheet()->getColumnDimension('U')->setWidth( 20);
        $this->libro->getActiveSheet()->getColumnDimension('V')->setAutoSize( FALSE);
        $this->libro->getActiveSheet()->getColumnDimension('V')->setWidth( 20);
        $this->libro->getActiveSheet()->getColumnDimension('W')->setAutoSize( FALSE);        
        $this->libro->getActiveSheet()->getColumnDimension('W')->setWidth( 20);
        $this->libro->getActiveSheet()->getColumnDimension('X')->setAutoSize( FALSE); 
        $this->libro->getActiveSheet()->getColumnDimension('X')->setWidth( 20);
        $this->libro->getActiveSheet()->getColumnDimension('Y')->setAutoSize( FALSE); 
        $this->libro->getActiveSheet()->getColumnDimension('Y')->setWidth( 20);
        $this->libro->getActiveSheet()->getColumnDimension('Z')->setAutoSize( TRUE);  
        //$this->libro->getActiveSheet()->getColumnDimension('Z')->setWidth( 20); 
        $this->libro->getActiveSheet()->getColumnDimension('AA')->setAutoSize( TRUE); 
        $this->libro->getActiveSheet()->getColumnDimension('AA')->setWidth( 20);    
        $this->libro->getActiveSheet()->getColumnDimension('AB')->setAutoSize( TRUE); 
        $this->libro->getActiveSheet()->getColumnDimension('AB')->setWidth( 20);      
        $this->libro->getActiveSheet()->getColumnDimension('AC')->setAutoSize( TRUE); 
        $this->libro->getActiveSheet()->getColumnDimension('AC')->setWidth( 35);    
        $this->libro->getActiveSheet()->getColumnDimension('AD')->setAutoSize( TRUE); 
        $this->libro->getActiveSheet()->getColumnDimension('AD')->setWidth( 25);   
        $this->libro->getActiveSheet()->getColumnDimension('AE')->setAutoSize( TRUE); 
        $this->libro->getActiveSheet()->getColumnDimension('AE')->setWidth( 25);     


        $reporteTerminado = new PHPExcel_Writer_Excel2007( $this->libro);
        $reporteTerminado->save( 'TrabajadoresActivos'.(strtotime(date("Y-m-d H:i:s")) * 1000).'.xlsx');
        echo 'TrabajadoresActivos'.(strtotime(date("Y-m-d H:i:s")) * 1000).".xlsx";

    }
}


$reporte = new TrabajadoresActivos;
$reporte->generaReporte();