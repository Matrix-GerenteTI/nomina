<?php

require_once $_SERVER['DOCUMENT_ROOT']."/nomina/ajax/reportes/prepareExcel.php";
require_once $_SERVER['DOCUMENT_ROOT']."/nomina/ajax/modelo/socioeconomico.php";

class ProgresionSocioeconomico  extends PrepareExcel
{
    public function __construct(){
        parent::__construct();
        $this->creaEmptySheet('Por realizar',1);
        $this->creaEmptySheet("Realizados",0);
    }

    public function prepararDatos()
    {
        $socioeconomico = new Socioeconomico;
        //obteniendo los trabajadores que ya tienen un socioecomomico realizado
        $conSocioeconomicoList = $socioeconomico->getEmpleadosConSocioeconomico();
        $sinSocioeconomicoList = $socioeconomico->getEmpleadosSinSocioeconomico();

        return ['conSocioeconomico' => $conSocioeconomicoList, 'sinSocioeconomico' => $sinSocioeconomicoList ];
    }

    public function generaReporte()
    {
        $this->libro->setActiveSheetIndex(0);
        $i = 9;
        extract( $this->prepararDatos() );
        $this->setHeaderHojas(['titulo' => 'Empleados con estudios socioecónomico',
                                'columnas' =>["NOMBRE" => "A","ESTATUS" => 'F',"FECHA REALIZADO" => 'G'],
                                'widthCol' =>[ 'FECHA REALIZADO' => 20]  ]);
        foreach ( $conSocioeconomico as $j => $empleado) {
            $this->libro->getActiveSheet()->setCellValue("A$i",$empleado['nombre']);
            $this->libro->getActiveSheet()->mergeCells("A$i:E$i");
            $this->libro->getActiveSheet()->setCellValue("F$i","OK");
            $this->libro->getActiveSheet()->setCellValue("G$i", str_replace("-","/",$empleado['fechaRealizacion'] ) );
            $this->libro->getActiveSheet()->getStyle("F$i")->applyFromArray($this->centrarTexto);
            if( ($i % 2) == 0 )
                $this->libro->getActiveSheet()->getStyle("A$i:G$i")->getFill()->applyFromArray( $this->setColorFill("f0f5f5")  );
            $i++;
        }

        $this->libro->setActiveSheetIndex(1);
        $this->setHeaderHojas(['titulo' => 'Empleados sin estudios socioecónomico',
                                'columnas' =>["NOMBRE" => "A","ESTATUS" => 'F'] ]);
        $i = 9;
        
        foreach ($sinSocioeconomico as $j => $empleado) {
            if( $empleado['id'] != null)
                continue;
            $this->libro->getActiveSheet()->setCellValue("A$i",$empleado['nombre']);
            $this->libro->getActiveSheet()->mergeCells("A$i:E$i");
            $this->libro->getActiveSheet()->setCellValue("F$i","SN");
            $this->libro->getActiveSheet()->getStyle("F$i")->applyFromArray($this->centrarTexto);
            if( ($i % 2) == 0 )
                $this->libro->getActiveSheet()->getStyle("A$i:F$i")->getFill()->applyFromArray( $this->setColorFill("f0f5f5")  );
            $i++;
        }

        $reporteTerminado = new PHPExcel_Writer_Excel2007( $this->libro);
        $reporteTerminado->save( 'Informe_Socioeconomico'.(strtotime(date("Y-m-d H:i:s")) * 1000).'.xlsx');
        echo 'Informe_Socioeconomico'.(strtotime(date("Y-m-d H:i:s")) * 1000).'.xlsx';
    }

    public function setHeaderHojas( $params )
    {
        extract( $params );

        $this->putLogo("B1", 300,200);
        $this->libro->getActiveSheet()->setCellValue("A6", $titulo );
        $this->libro->getActiveSheet()->getStyle("A6")->applyFromArray($this->centrarTexto);
        $this->libro->getActiveSheet()->getStyle("A6")->applyFromArray($this->labelBold);

        $this->libro->getActiveSheet()->mergeCells("A6:H6");
        $this->libro->getActiveSheet()->mergeCells("A8:E8");
        foreach ($columnas as $name => $columna) {
            $this->libro->getActiveSheet()->setCellValue("$columna"."8", $name);
            $this->libro->getActiveSheet()->getStyle($columna."8")->applyFromArray($this->centrarTexto);
            $this->libro->getActiveSheet()->getStyle($columna."8")->applyFromArray( $this->labelBold );
            $this->libro->getActiveSheet()->getStyle($columna."8")->applyFromArray($this->setColorText('ffffff',11));
            $this->libro->getActiveSheet()->getStyle($columna."8")->getFill()->applyFromArray( $this->setColorFill("cc0000")  );
        }
        $this->libro->getActiveSheet()->getColumnDimension("F")->setAutoSize(false );
        $this->libro->getActiveSheet()->getColumnDimension('F')->setWidth( 25);
        if( isset($widthCol) ){
            $this->libro->getActiveSheet()->getColumnDimension("G")->setAutoSize(false );
            $this->libro->getActiveSheet()->getColumnDimension('G')->setWidth( $widthCol['FECHA REALIZADO']);
        }
    }
}


$reporte = new ProgresionSocioeconomico;
$reporte->generaReporte();