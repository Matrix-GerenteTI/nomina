<?php
ob_start();
date_default_timezone_set('America/Mexico_City');

error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();
require_once dirname(__DIR__)."/ajax/mysql.php";
include_once dirname(__DIR__).'/PHPExcel.php';
include_once  dirname(__DIR__).'/PHPExcel/Writer/Excel2007.php';
//conexion();
	
 header( "Content-type: application/vnd.ms-excel; charset=UTF-8" );
 header("Content-Disposition: attachment; filename=prenomina.xlsx");
 header("Pragma: no-cache");
 header("Expires: 0");

$departamento = "";
$nombre = "";


$diahoy = date('d');
$cantDomQuincena = 0;
$mesActual = date('m');
$anioActual = date('Y');
$numero = cal_days_in_month(CAL_GREGORIAN, $mesActual, $anioActual);
$condicionFecha = "";

if($diahoy<=15){
	$fecini = date('01/m/Y');
	for ($i=1; $i <= 15  ; $i++) { 
		if( strftime("%a", strtotime("$mesActual/$i/$anioActual")) == "Sun" ){
			$cantDomQuincena++;
		}
	}
	$condicionFecha = " AND fechaCargo BETWEEN '$anioActual-$mesActual-01' AND '$anioActual-$mesActual-15' ";
}else{
	$fecini = date('16/m/Y');
		for ($i=16; $i <= $numero  ; $i++) { 
		if( strftime("%a", strtotime("$mesActual/$i/$anioActual")) == "Sun" ){
			$cantDomQuincena++;
		}
	}
	$condicionFecha = " AND fechaCargo BETWEEN '$anioActual-$mesActual-16' AND '$anioActual-$mesActual-31'";
}


//echo $cantDomQuincena;//


$fecfin = date('d/m/Y', strtotime('+1 day'));
$puesto = "";

$dias = calculaDias($fecini,$fecfin);

$date = str_replace("/","-",$fecini);
$rangoFechas[0] = $date;
for($d=0;$d<$dias;$d++){
	$mod_date = strtotime($date."+ 1 days");
	$date = date("d-m-Y",$mod_date);
	$rangoFechas[count($rangoFechas)] = $date;
}



//CONSULTA PARA LA OBTENCIÓN DE LAS DEDICCIONES
$q1 = "SELECT 	d.id AS id,
				d.idcontrato AS idcontrato,
				d.importe AS importe,
				td.descripcion AS descripcion,
				td.id as iddeduccion
		FROM 	pdeducciones d
		INNER JOIN ctipodeduccion td ON d.idtipodeduccion=td.id
		WHERE 	d.STATUS=1 $condicionFecha
		ORDER BY d.idcontrato ASC";

$s1 = $conexion->query($q1);
$listaDeducciones = $s1->fetch_all(MYSQLI_ASSOC);

//Metemos en un arreglo los datos de la consulta para procesarlos como cabecera y datos
$arrCabDeducciones = array();
$arrDeducciones = array();
foreach($listaDeducciones as $indice => $valor){
	if(!in_array($valor['descripcion'],$arrCabDeducciones)){
		$arrCabDeducciones[$valor['iddeduccion']] = $valor['descripcion'];
	}
}



$array = array();
$query = "SELECT 	e.nip as nip,
					e.nombre as nombre,
					d.descripcion as departamento,
					p.descripcion as puesto,
					c.id as idcontrato,
					r.timecheck,
					YEAR(r.timecheck) as anio,
					MONTH(r.timecheck) as mes,
					DAY(r.timecheck) as dia,
					HOUR(r.timecheck) as hora,
					MINUTE(r.timecheck) as minuto,
					CASE WHEN TIMEDIFF(TIME(r.timecheck),pa.entrada)>'00:00:59' THEN CASE WHEN(HOUR(TIMEDIFF(TIME(r.timecheck),pa.entrada))*60)+MINUTE(TIMEDIFF(TIME(r.timecheck),pa.entrada))<6 THEN 50 ELSE IF(MINUTE(TIMEDIFF(TIME(r.timecheck),pa.entrada))<11,100,c.salariodiario) END ELSE 0 END AS RETARDO,
					pa.entrada,
					pa.entradai,
					pa.salidai,
					pa.salida,
					pa.tolerancia,
					pa.retardospfalta,
					pa.corrido,
					pa.faltaspdescuento,
					s.descripcion as sucursal,
					c.salariodiario
		  FROM 		pempleado e
		  LEFT JOIN pregistros r ON r.idempleado=e.nip 
		  INNER JOIN pcontrato c ON e.nip=c.nip 
		  INNER JOIN cpuesto p ON c.idpuesto=p.id 
		  INNER JOIN cdepartamento d ON c.iddepartamento=d.id  
		  INNER JOIN cparametrosasistencia pa ON p.id=pa.idpuesto 
		  INNER JOIN csucursal s ON e.idsucursal=s.id
		  WHERE 	e.nombre LIKE '%".$nombre."%' 
		  AND 		p.id LIKE '%".$puesto."%' ";
if($fecini!="" && $fecfin!="")
	$query.= "AND		r.timecheck>='".formateaFechaSLASH($fecini)."' AND r.timecheck<='".formateaFechaSLASH($fecfin)."' ";	
$query.= "AND		e.status=1 
		  GROUP BY  d.descripcion,p.descripcion,e.nombre,anio,mes,dia 
		  UNION ALL
			SELECT 	e.nip AS nip,
				e.nombre AS nombre,
				d.descripcion AS departamento,
				p.descripcion AS puesto,
				c.id AS idcontrato,
				'NA' AS timecheck,
				'' AS anio,
				'' AS mes,
				'' AS dia,
				'' AS hora,
				'' AS minuto,
				0 AS RETARDO,
				'' AS entrada,
				'' AS entradai,
				'' AS salidai,
				'' AS salida,
				'' AS tolerancia,
				'' AS retardospfalta,
				'' AS corrido,
				'' AS faltaspdescuento,
				s.descripcion as sucursal,
				c.salariodiario
			FROM 	pempleado e  
			INNER JOIN pcontrato c ON e.nip=c.nip 
			INNER JOIN cpuesto p ON c.idpuesto=p.id 
			INNER JOIN cdepartamento d ON c.iddepartamento=d.id 
			INNER JOIN csucursal s ON e.idsucursal=s.id 
			WHERE 	e.nombre LIKE '%%' 
			AND 	p.id LIKE '%%'
			AND	e.STATUS=1 
			AND 	p.id NOT IN (SELECT idpuesto FROM cparametrosasistencia WHERE STATUS=1)
			GROUP BY  d.descripcion,p.descripcion,e.nombre,anio,mes,dia
		  UNION ALL
			SELECT 	e.nip AS nip,
				e.nombre AS nombre,
				d.descripcion AS departamento,
				p.descripcion AS puesto,
				c.id AS idcontrato,
				'0' AS timecheck,
				'' AS anio,
				'' AS mes,
				'' AS dia,
				'' AS hora,
				'' AS minuto,
				0 AS RETARDO,
				'' AS entrada,
				'' AS entradai,
				'' AS salidai,
				'' AS salida,
				'' AS tolerancia,
				'' AS retardospfalta,
				'' AS corrido,
				'' AS faltaspdescuento,
				s.descripcion as sucursal,
				c.salariodiario
			FROM 	pempleado e  
			INNER JOIN pcontrato c ON e.nip=c.nip 
			INNER JOIN cpuesto p ON c.idpuesto=p.id 
			INNER JOIN cdepartamento d ON c.iddepartamento=d.id 
			INNER JOIN csucursal s ON e.idsucursal=s.id 
			INNER JOIN cparametrosasistencia pa ON p.id=pa.idpuesto 
			WHERE 	e.nombre LIKE '%%' 
			AND 	p.id LIKE '%%' 
			AND		e.status=1 
			AND 	e.nip NOT IN (SELECT r.idempleado FROM pregistros r WHERE r.timecheck>='".formateaFechaSLASH($fecini)."' AND r.timecheck<='".formateaFechaSLASH($fecfin)."' )
			GROUP BY  d.descripcion,p.descripcion,e.nombre,anio,mes,dia
		  ORDER BY departamento,puesto,nombre,timecheck ASC";

//die();

$exeAsistencia = $conexion->query($query);

$listaAsistencia = $exeAsistencia->fetch_all(MYSQLI_ASSOC);

$contadorFaltas = 0;
$diasTrabajados = 0;
$contadorAsistencia = 0;
$asistenciaDeducciones = array();
$arrDomingos = array();
foreach ($listaAsistencia as $i => $asistencia) {
	if ( isset($asistenciaDeducciones[ $asistencia['sucursal'] ] [ $asistencia['departamento'] ] [ $asistencia['puesto'] ] [$asistencia['idcontrato']] ) ) {
		array_push ($asistenciaDeducciones[ $asistencia['sucursal'] ] [ $asistencia['departamento'] ] [ $asistencia['puesto'] ] [$asistencia['idcontrato']] [ 'descuentos'] , $asistencia['RETARDO'] );

		$contadorAsistencia ++;
		if($asistencia['timecheck']=='NA'){
			$asistenciaDeducciones[ $asistencia['sucursal'] ] [ $asistencia['departamento'] ] [ $asistencia['puesto'] ] [$asistencia['idcontrato']] [ 'asistencia'] = 15;
		}else{
			if($asistencia['timecheck']=='0'){
				$asistenciaDeducciones[ $asistencia['sucursal'] ] [ $asistencia['departamento'] ] [ $asistencia['puesto'] ] [$asistencia['idcontrato']] [ 'asistencia'] = 0;
			}else{
				$asistenciaDeducciones[ $asistencia['sucursal'] ] [ $asistencia['departamento'] ] [ $asistencia['puesto'] ] [$asistencia['idcontrato']] [ 'asistencia'] = $contadorAsistencia;
				if(!isset($arrDomingos[$asistencia['idcontrato']])){
					$arrDomingos[$asistencia['idcontrato']] = array($asistencia['anio']."-".$asistencia['mes']."-".$asistencia['dia']);
				}else{
					array_push($arrDomingos[$asistencia['idcontrato']],$asistencia['anio']."-".$asistencia['mes']."-".$asistencia['dia']);
				}
			}
		}
	}
	else{
		$asistenciaDeducciones[ $asistencia['sucursal'] ] [ $asistencia['departamento'] ] [ $asistencia['puesto'] ] [$asistencia['idcontrato']] [ 'nombre'] = $asistencia['nombre'];
		$asistenciaDeducciones[ $asistencia['sucursal'] ] [ $asistencia['departamento'] ] [ $asistencia['puesto'] ] [$asistencia['idcontrato']] [ 'salariodiario'] = $asistencia['salariodiario'] ;
		

		$asistenciaDeducciones[ $asistencia['sucursal'] ] [ $asistencia['departamento'] ] [ $asistencia['puesto'] ] [$asistencia['idcontrato']] [ 'descuentos'] = array();
		array_push ($asistenciaDeducciones[ $asistencia['sucursal'] ] [ $asistencia['departamento'] ] [ $asistencia['puesto'] ] [$asistencia['idcontrato']] [ 'descuentos'] , $asistencia['RETARDO'] );
		
		$contadorAsistencia++;
		$asistenciaDeducciones[ $asistencia['sucursal'] ] [ $asistencia['departamento'] ] [ $asistencia['puesto'] ] [$asistencia['idcontrato']] [ 'faltas'] = $contadorFaltas;
		if($asistencia['timecheck']=='NA'){
			$asistenciaDeducciones[ $asistencia['sucursal'] ] [ $asistencia['departamento'] ] [ $asistencia['puesto'] ] [$asistencia['idcontrato']] [ 'asistencia'] = 15;
		}else{
			if($asistencia['timecheck']=='0'){
				$asistenciaDeducciones[ $asistencia['sucursal'] ] [ $asistencia['departamento'] ] [ $asistencia['puesto'] ] [$asistencia['idcontrato']] [ 'asistencia'] = 0;
			}else{
				$asistenciaDeducciones[ $asistencia['sucursal'] ] [ $asistencia['departamento'] ] [ $asistencia['puesto'] ] [$asistencia['idcontrato']] [ 'asistencia'] = $contadorAsistencia;
				if(!isset($arrDomingos[$asistencia['idcontrato']])){
					$arrDomingos[$asistencia['idcontrato']] = array($asistencia['anio']."-".$asistencia['mes']."-".$asistencia['dia']);
				}else{
					array_push($arrDomingos[$asistencia['idcontrato']],$asistencia['anio']."-".$asistencia['mes']."-".$asistencia['dia']);
				}
			}
		}
	}

	if( isset($listaAsistencia[$i+1]['idcontrato']) ){
		if ($listaAsistencia[$i]['idcontrato'] != $listaAsistencia[$i+1]['idcontrato']) {
			$contadorFaltas = 0;
			$contadorRetardo = 0;
			$contadorAsistencia = 0;
		}
	}
}
var_dump($asistenciaDeducciones);
$i = 0;
	
$libroPrenomina = new PHPExcel();

$libroPrenomina->getProperties()->setCreator("Matrix");
$libroPrenomina->getProperties()->setLastModifiedBy("Matrix");
$libroPrenomina->getProperties()->setTitle("Office 2007 XLSX Test Document");
$libroPrenomina->getProperties()->setSubject("Office 2007 XLSX Test Document");
$libroPrenomina->getProperties()->setDescription("Inventario actualizado");

$header_style= array('font' => array('bold' => true),'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$border_style= array('font' => array('bold' => true),'borders' => array('bottom' => array('style' =>PHPExcel_Style_Border::BORDER_THICK,'color' => array('rgb' => '000000'),)));


$libroPrenomina->createSheet();
$libroPrenomina->setActiveSheetIndex(0);
$libroPrenomina->getProperties()->setTitle('Prenomina');
$libroPrenomina->setActiveSheetIndex(0)->mergeCells('A1:N1');
$libroPrenomina->setActiveSheetIndex(0)->mergeCells('A2:N2');
$libroPrenomina->setActiveSheetIndex(0)->mergeCells('A3:N3');

$libroPrenomina->getActiveSheet()->getRowDimension(1)->setRowHeight(50);
$libroPrenomina->getActiveSheet()->SetCellValue('A2', 'REPORTE DE ASISTENCIA DEL '.date('d/m/Y', strtotime('-1 day')));
$libroPrenomina->getActiveSheet()->SetCellValue('A3', '"NO EXISTEN LOS PRETEXTOS, SI QUIERES HACER ALGO HAZLO O POR LO MENOS INTENTALO"');
$libroPrenomina->getActiveSheet()->getStyle('A1')->applyFromArray($header_style);
$libroPrenomina->getActiveSheet()->getStyle('A2')->applyFromArray($header_style);
$libroPrenomina->getActiveSheet()->getStyle('A3')->applyFromArray($header_style);
$objDrawing1 = new PHPExcel_Worksheet_Drawing();
$objDrawing1->setName('Logo');
$objDrawing1->setDescription('Logo');
$objDrawing1->setPath('./logoFWhite.png');
$objDrawing1->setCoordinates('F1');	 
$objDrawing1->setOffsetX(60);
$objDrawing1->setHeight(57);
$objDrawing1->setWidth(120);					
$objDrawing1->setWorksheet($libroPrenomina->getActiveSheet());

$libroPrenomina->getActiveSheet()->getStyle('A4')->applyFromArray($border_style);
$libroPrenomina->getActiveSheet()->getStyle('B4')->applyFromArray($border_style);
$libroPrenomina->getActiveSheet()->getStyle('C4')->applyFromArray($border_style);
$libroPrenomina->getActiveSheet()->getStyle('D4')->applyFromArray($border_style);
$libroPrenomina->getActiveSheet()->getStyle('E4')->applyFromArray($border_style);
$libroPrenomina->getActiveSheet()->getStyle('F4')->applyFromArray($border_style);
$libroPrenomina->getActiveSheet()->getStyle('G4')->applyFromArray($border_style);
$libroPrenomina->getActiveSheet()->getStyle('H4')->applyFromArray($border_style);
$libroPrenomina->getActiveSheet()->getStyle('I4')->applyFromArray($border_style);

$libroPrenomina->getActiveSheet()->setCellValue("A4","Sucursal");
$libroPrenomina->getActiveSheet()->setCellValue("B4","Departamento");
$libroPrenomina->getActiveSheet()->setCellValue("C4","Puesto");
$libroPrenomina->getActiveSheet()->setCellValue("D4","Nombre");
$libroPrenomina->getActiveSheet()->setCellValue("E4","Sueldo Diario");
//$libroPrenomina->getActiveSheet()->setCellValue("F4","Sueldo Quincenal");
$libroPrenomina->getActiveSheet()->setCellValue("F4","Dias trabajados");
$libroPrenomina->getActiveSheet()->setCellValue("G4","Faltas");
$libroPrenomina->getActiveSheet()->setCellValue("H4","Retardos");
$libroPrenomina->getActiveSheet()->setCellValue("I4","Pago");

$libroPrenomina->getActiveSheet()->getStyle('A4')->applyFromArray($header_style);
$libroPrenomina->getActiveSheet()->getStyle('B4')->applyFromArray($header_style);
$libroPrenomina->getActiveSheet()->getStyle('C4')->applyFromArray($header_style);
$libroPrenomina->getActiveSheet()->getStyle('D4')->applyFromArray($header_style);
$libroPrenomina->getActiveSheet()->getStyle('E4')->applyFromArray($header_style);
$libroPrenomina->getActiveSheet()->getStyle('F4')->applyFromArray($header_style);
$libroPrenomina->getActiveSheet()->getStyle('G4')->applyFromArray($header_style);
$libroPrenomina->getActiveSheet()->getStyle('H4')->applyFromArray($header_style);
$libroPrenomina->getActiveSheet()->getStyle('I4')->applyFromArray($header_style);
		
$it0 = 0;
$uletra = 0;
$arrLetraTitulo = array();
foreach($arrCabDeducciones as $indicetitulo => $titulo){
	$it1 = 0;
	foreach(range('I','Z') as $colID){
		if($it0==$it1){
			$libroPrenomina->getActiveSheet()->getStyle($colID."4")->applyFromArray($border_style);
			$libroPrenomina->getActiveSheet()->setCellValue($colID."4",$titulo);			
			$libroPrenomina->getActiveSheet()->getStyle($colID.'4')->applyFromArray($header_style);
			$arrLetraTitulo[$indicetitulo] = $colID;
			$uletra++;
		}
		$it1++;
	}
	$it0++;
}

$it1=0;
foreach(range('I','Z') as $colID1){
	if($it1==($uletra)){
		$libroPrenomina->getActiveSheet()->getStyle($colID1."4")->applyFromArray($border_style);
		$libroPrenomina->getActiveSheet()->setCellValue($colID1."4", "IMPORTE PAGO");
		$libroPrenomina->getActiveSheet()->getStyle($colID1.'4')->applyFromArray($header_style);
	}
	$it1++;
}


$row = 5;
foreach ($asistenciaDeducciones as $sucursal => $departamentos) {
	
	foreach ($departamentos as $departamento => $puestos) {
		foreach ($puestos as $puesto => $trabajadores) {
			foreach ($trabajadores as $trabajadorId => $infoTrabajador) {
				$libroPrenomina->getActiveSheet()->setCellValue("A$row", $sucursal);
				$libroPrenomina->getActiveSheet()->setCellValue("B$row", $departamento);
				$libroPrenomina->getActiveSheet()->setCellValue("C$row", $puesto);
				$libroPrenomina->getActiveSheet()->setCellValue("D$row", $infoTrabajador['nombre']);
				$libroPrenomina->getActiveSheet()->setCellValue("E$row", $infoTrabajador['salariodiario']);
				//$libroPrenomina->getActiveSheet()->setCellValue("F$row", round($infoTrabajador['salariodiario'] * 15,0));
				
				
				if(isset($arrDomingos[$trabajadorId])){
					$fecI = explode("-",$arrDomingos[$trabajadorId][0]);
					$fecF = explode("-",$arrDomingos[$trabajadorId][(count($arrDomingos[$trabajadorId])-1)]);
					$di = $fecI[2];
					$mi = $fecI[1];
					$yi = $fecI[0];
					$df = $fecF[2];
					$mf = $fecF[1];
					$yf = $fecF[0];
					$domingosCount = 0;
					for ($i=($di*1); $i <= ($df*1) ; $i++) { 
						if( strftime("%a", strtotime($mi."/".$i."/".$yi)) == "Sun" ){
							$domingosCount++;
						}
					}
					$diasLab = $infoTrabajador['asistencia'] + $domingosCount;
				}else{
					$diasLab = $infoTrabajador['asistencia'];
				}
				$diff = 30 - $numero;
				$diasLab = $diasLab + $diff;
				
				if($diasLab>15)
					$diasLab = 15;
				
				$libroPrenomina->getActiveSheet()->setCellValue("F$row", $diasLab );
				$libroPrenomina->getActiveSheet()->setCellValue("G$row", $infoTrabajador['faltas']);
				
				$sumaRetardos = 0;
				foreach($infoTrabajador['descuentos'] as $valor){
					$sumaRetardos+= $valor;
				}
				$libroPrenomina->getActiveSheet()->setCellValue("H$row", round($sumaRetardos,2));
				
				$sumaDeducciones = 0;
				foreach($listaDeducciones as $idx => $val){
					if($listaDeducciones[$idx]['idcontrato']==$trabajadorId){
						foreach($arrLetraTitulo as $idx2 => $val2){
							if($idx2==$listaDeducciones[$idx]['iddeduccion']){
								$libroPrenomina->getActiveSheet()->setCellValue($val2.$row, number_format($listaDeducciones[$idx]['importe'],2,'.','') );
								$sumaDeducciones = $sumaDeducciones + $listaDeducciones[$idx]['importe'];
							}
						}
					}
				}
				$pago = ($infoTrabajador['salariodiario'] * $diasLab) - ($sumaDeducciones);
				
				$it2 = 0;
				foreach(range('I','Z') as $colID2){
					if($it2==($uletra)){
						$libroPrenomina->getActiveSheet()->setCellValue($colID2.$row, round($pago,0));
					}
					$it2++;
				}
				
				$row++;
			}
		}
	}
}

foreach(range('A','X') as $columnID) {
	$libroPrenomina->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}


$prenominaReady = new PHPExcel_Writer_Excel2007($libroPrenomina);
ob_end_clean();
$prenominaReady->save("prenomina.xlsx");
 $prenominaReady->save("php://output");
//var_dump($asistenciaDeducciones['MATRIZ']['ADMINISTRACION']);
//var_dump($listaAsistencia);
$i = 0;






function CalculaEdad( $fecha ) {
    list($Y,$m,$d) = explode("-",$fecha);
    return( date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y );
}

function CalculaAntiguedadSAT( $fecha, $fechaFinal ) {
    $fecha1 = new DateTime($fecha);
	$fecha2 = new DateTime($fechaFinal);
	$fecha = $fecha1->diff($fecha2);
	$anio = $fecha->y;
	$mes = $fecha->m;
	$dia = $fecha->d;
	$return = "P";
	if($anio>0)
		$return.= $anio."Y";
	if($mes>0)
		$return.= $mes."M";
	$return.= $dia."D";
	return $return;
}

function formateaFechaSLASH($fecha){
	$arr = explode("/",$fecha);
	$fechaNueva = $arr[2]."-".$arr[1]."-".$arr[0];
	return $fechaNueva;	
}

function formateaFechaGUION($fecha){
	$arr = explode("-",$fecha);
	$fechaNueva = $arr[2]."/".$arr[1]."/".$arr[0];
	return $fechaNueva;	
}

function formateaFecha($fecha){
	$arr = explode("-",$fecha);
	$fechaNueva = $arr[2]."-".$arr[1]."-".$arr[0];
	return $fechaNueva;	
}

function calculaDias($fechaI, $fechaF)
{
    $fecha1= explode("/",$fechaI); // convierte la fecha de formato mm/dd/yyyy a marca de tiempo
    $dia1=$fecha1[0]; // d?a del mes en n?mero
    $mes1=$fecha1[1]; // n?mero del mes de 01 a 12
    $anio1=$fecha1[2];
    
    $fecha2= explode("/",$fechaF); // convierte la fecha de formato mm/dd/yyyy a marca de tiempo
    $dia2=$fecha2[0]; // d?a del mes en n?mero
    $mes2=$fecha2[1]; // n?mero del mes de 01 a 12
    $anio2=$fecha2[2];
    
    $fecha1a=mktime(0,0,0,$mes1,$dia1,$anio1);
    $fecha2a=mktime(0,0,0,$mes2,$dia2,$anio2);
 
    $diferencia = $fecha2a - $fecha1a;
    $dias=$diferencia/(60*60*24);
    $dias=floor($dias);
    
    return $dias; 
}

function calculaMinutos($hora1,$hora2){ 
    $h1=explode(':',$hora1); 
    $h2=explode(':',$hora2); 

	$h1 = ($h1[0]*60)+$h1[1]; 
	$h2 = ($h2[0]*60)+$h2[1]; 
	$total_minutos_trasncurridos = $h2 - $h1; 
	return($total_minutos_trasncurridos); 
}

function formateaDigitos($numero){
	if($numero<10)
		return '0'.$numero;
	else
		return $numero;
}

?>


