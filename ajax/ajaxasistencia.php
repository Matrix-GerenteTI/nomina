<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/intranet/modelos/nomina/trabajadores.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/intranet/modelos/nomina/incidencias.php";

	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	session_start();
	require_once("mysql.php");
	//conexion();
	$op = $_GET['op'];
	switch($op){

		case 'quitaIncidenciasViejas':
			$diasDelMes = $número = cal_days_in_month(CAL_GREGORIAN, date("m"), date("Y") );
			$mes = date('m')+1;
			$quincena = date('d') < 16  ? date("Y-m-01") : date("Y-$mes-01");
				$updateInicidencia  = "UPDATE pdeducciones set status = '0' where fechaCargo < '$quincena' and vencimiento is  null  ";

				echo $updateInicidencia;

			$sql = $conexion->query($updateInicidencia);	

		break;
		
		case "lista":{
			$modeloTrabajador = new Trabajador;
			$sucursal = $_POST['sucursal'];
			$departamento = $_POST['departamento'];
			$puesto = $_POST['puesto'];
			$nombre = $_POST['nombre'];
			$_POST['fecIni']!=''?$fecini = $_POST['fecIni']:$fecini = date("d/m/Y");
			$_POST['fecFin']!=''?$fecfin = $_POST['fecFin']:$fecfin = date("d/m/Y");
			

			$inicio = $_POST['inicio'];
			$cantidad = $_POST['cantidad'];
			$arrDays = array();
			$dias = calculaDias($fecini,$fecfin);
			for($i=0;$i<$dias;$i++){
				$fechaCalculada = date("Y-m-d",strtotime(formateaFechaSLASH($fecini)."+ ".$i." days"));
				date('w',strtotime($fechaCalculada))==0?$txt='Domingo':$txt='--';
				$arrDays[$fechaCalculada] = $txt;
			}
			//echo $dias."<br>".json_encode($arrDays);
			//die();
			$trabajadores = array();
			$n = 0;
			//Sacamos los empleados activos
			$arr = $modeloTrabajador->getEmpleadosActivos($sucursal,$departamento,$puesto,$nombre); 
			foreach($arr as $row){
				$trabajadores[$n]['nip'] = $row['nip'];
				$trabajadores[$n]['sucursal'] = utf8_decode($row['sucursal']);
				$trabajadores[$n]['depto'] = $row['departamento'];
				$trabajadores[$n]['puesto'] = $row['puesto'];
				$trabajadores[$n]['nombre'] = $row['nombre'];
				$trabajadores[$n]['asistencia'] = $arrDays;
				$trabajadores[$n]['retardos'] = $arrDays;
				$trabajadores[$n]['faltas'] = $arrDays;
				$trabajadores[$n]['asistenciaTotal'] = 0;
				$trabajadores[$n]['retardosTotal'] = 0;
				$trabajadores[$n]['faltasTotal'] = 0;

				$txtWhere = '';
				$qryParamAsist = "SELECT 	* 
							      FROM 		cparametrosasistencia 
								  WHERE 	idempleado='".$row['nip']."'
								  OR 		idsucursal='".$row['idsucursal']."' 
								  OR 		idpuesto='".$row['idpuesto']."'";
				$sqlParamAsist = $conexion->query($qryParamAsist);
				while($rwParamAsist = $sqlParamAsist->fetch_assoc()){
					if($rwParamAsist['idsucursal']>0)
						$txtWhere = ' AND p.idsucursal='.$rwParamAsist['idsucursal'];
					if($rwParamAsist['idpuesto']>0)
						$txtWhere = ' AND p.idpuesto='.$rwParamAsist['idpuesto'];
					if($rwParamAsist['idempleado']>0)
						$txtWhere = ' AND p.idempleado='.$rwParamAsist['nip'];
				}
				

				//Procesamos las asistencias
				$arrAsis = array();
				$na = 0;
				$query1 = "SELECT 	a.fecha as fecha, 
									a.hora as hora,
									p.entrada as entrada,
									p.entradai as entradai,
									p.salidai as salidai,
									p.salida as salida,
									p.tolerancia as tolerancia,
									p.toleranciafalta as toleranciafalta
							FROM 	asistencia a
							INNER JOIN pempleado e ON a.idempleado=e.nip
							INNER JOIN csucursal s ON e.idsucursal=s.id
							INNER JOIN pcontrato c ON c.nip=e.nip
							INNER JOIN cparametrosasistencia p ON p.idpuesto=c.idpuesto
							WHERE 	a.fecha>='".formateaFechaSLASH($fecini)."' AND a.fecha<='".formateaFechaSLASH($fecfin)."'";
				$query1.= $txtWhere." AND 	a.status=1";
				
            	$sql1 = $conexion->query($query1);

				
				while($rww = $sql1->fetch_assoc()){
					//Sacamos diferencia de minutos con hora de entrada
					$entrada= "1990/01/01 ".$rww['entrada'];
					$checado= "1990/01/01 ".$rww['hora'];
					$minutos = (strtotime($checado)-strtotime($entrada))/60;
					//$minutos = abs($minutos); 
					$minutos = floor($minutos);
					if($minutos>$rww['tolerancia']){
						if($minutos>$rww['toleranciafalta']){
							if($trabajadores[$n]['faltas'][$rww['fecha']] == '--' || $trabajadores[$n]['faltas'][$rww['fecha']] == 'Domingo'){
								$trabajadores[$n]['faltas'][$rww['fecha']] = $rww['hora'];
								$trabajadores[$n]['faltasTotal']++;
							}
						}else{
							if($trabajadores[$n]['retardos'][$rww['fecha']] == '--' || $trabajadores[$n]['retardos'][$rww['fecha']] == 'Domingo'){
								$trabajadores[$n]['retardos'][$rww['fecha']] = $rww['hora'];
								$trabajadores[$n]['retardosTotal']++;
							}
						}
					}else{
						if($minutos<=$rww['tolerancia']){
							if($trabajadores[$n]['asistencia'][$rww['fecha']] == '--' || $trabajadores[$n]['asistencia'][$rww['fecha']] == 'Domingo'){
								$trabajadores[$n]['asistencia'][$rww['fecha']] = $rww['hora'];
								$trabajadores[$n]['asistenciaTotal']++;
							}
						}
					}
				}
				$sinregistro = $trabajadores[$n]['asistenciaTotal'] + $trabajadores[$n]['retardosTotal'] + $trabajadores[$n]['faltasTotal'];
				if($sinregistro==0){
					$trabajadores[$n]['asistenciaTotal'] = 'N/R';
					$trabajadores[$n]['retardosTotal'] = 'N/R';
					$trabajadores[$n]['faltasTotal'] = 'N/R';
				}

				$n++;
			}

			//Sacamos asistencias

			
			echo json_encode($trabajadores);
			break;
		}
		
		case "listaP":{
			$departamento = $_POST['depto'];
			$nombre = $_POST['nombre'];
			$fecini = $_POST['fecIni'];
			$fecfin = $_POST['fecFin'];
			$array = array();
			$query = "SELECT 	COUNT(*) as cantidad								
					  FROM 		pempleado e 
					  INNER JOIN pcontrato c ON c.nip=e.nip
					  INNER JOIN cdepartamento d ON c.iddepartamento=d.id  
					  INNER JOIN ptimbrado t ON c.id=t.idcontrato
					  WHERE 	e.nombre LIKE '%".$nombre."%' 
					  AND 		d.id LIKE '%".$departamento."%'";
			if($fecini!="" && $fecfin!="")
				$query.= "AND		t.fechaPago>='".formateaFechaSLASH($fecini)."' AND t.fechaPago<='".formateaFechaSLASH($fecfin)."'";	
			$query.= "AND		e.status>0
					  AND		t.status<>0";
			$sql = $conexion->query($query);
			$row = $sql->fetch_assoc();
			echo $row['cantidad'];
			break;
		}
		
	}


 function calculaFaltas( $fechaInicio , $fechaFin , $itemTrabajador, $detalleTrabajador = null  )
{
	$modeloTrabajador = new Trabajador;
	$modeloIncidencia = new Incidencias;

	$incioExplode = explode('/', $fechaInicio);
	$finExplode = explode('/', $fechaFin);
	$mesInicio = $incioExplode[1]/1;
	$mesFin = $finExplode[1]/1;
	$diaInicio = $incioExplode[0] /1;
	$diaFin = $finExplode[0] / 1;

			for ($i= $mesInicio; $i <= $mesFin ; $i++) { 
				$mes = $i < 10 ? '0'.$i : $i;
				if ( $mesInicio !=  $i) {
					$diaInicio = 1;
				}
				if ( $mesFin != $i) {
					$diaFin = cal_days_in_month(CAL_GREGORIAN, $i, $incioExplode[2]);
				}
										
				for ($j= $diaInicio; $j <= $diaFin ; $j++) { 
					$dia =  $j < 10 ? '0'.$j : $j;
					 
					if (strftime("%a", strtotime($mes."/".$dia."/".$incioExplode[2])) != "Sun" ) {
						
						if ( ! in_array($dia.'/'.$mes.'/'.$incioExplode[2], $itemTrabajador['historialAsistencia']) ) {
							$itemTrabajador['faltas'] += 1;
							
									//Se le hace el registro en base de datos de la fecha con la hora en: 00:00:00
									$modeloTrabajador->setEntradaSalidaReloj( [
										'idempleado' => $itemTrabajador['id'],
										'timecheck' => $incioExplode[2]."-$mes"."-$dia 00:00:00",
										'idreloj' => -1
								]);
									//Obteniendo la fecha y buscar la falta en pregistros que no tenga una incidencia aplicada
									$faltaObtenida = $modeloTrabajador->getFaltaSinIncidenciaAplicada( $itemTrabajador['id'] , $incioExplode[2]."-$mes"."-$dia 00:00:00" );
									$montoFalta = 0;
									$idIncidencia = -1;

									if ( $faltaObtenida[0]['aplicaIncidencia'] == -1 ) { //entonces se le hace el cargo por el total de su salario diario x 3 días 
										
										$idIncidencia = $modeloIncidencia->setIncidencia([
											'tipoDeduccion' => '0005',
											'monto'  => ( $detalleTrabajador['salariobase'] * 2  ),
											'contratoId' =>  $detalleTrabajador['contratoId'],
											'fechaAplicacion' => $incioExplode[2]."-$mes"."-$dia",
											'observaciones' => "Aplicación automatica de incidencia por falta"
										]);
										$detalleTrabajador['aplicaIncidencia'] = $idIncidencia;
										$montoFalta = ( $detalleTrabajador['salariobase'] * 2 );

										//Asignando  al registro de la falta
										$modeloTrabajador->updateAplicacionIncidencia( $itemTrabajador['id'] , $idIncidencia , $incioExplode[2]."-$mes"."-$dia 00:00:00" );
										
									}else{
										$idIncidencia = $faltaObtenida[0]['aplicaIncidencia'] ;
										$montoFalta = $modeloIncidencia->getDetalleIncidencia( $idIncidencia )[0]['importe'];
									}
									
									array_push( $itemTrabajador['diasFaltas'] , array( 'fecha' =>$dia.'/'.$mes.'/'.$incioExplode[2] , 'aplicaIncidencia' => $idIncidencia, 'monto' => $montoFalta ) );
																								
						}							
					}else{
						if($j<=(date("d")*1))
							array_push( $itemTrabajador['historialAsistencia'] , array( $dia.'/'.$mes.'/'.$incioExplode[2]) );
					}

				}
		}
		return $itemTrabajador;
}

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
    $dia1=$fecha1[0]; // día del mes en número
    $mes1=$fecha1[1]; // número del mes de 01 a 12
    $anio1=$fecha1[2];
    
    $fecha2= explode("/",$fechaF); // convierte la fecha de formato mm/dd/yyyy a marca de tiempo
    $dia2=$fecha2[0]; // día del mes en número
    $mes2=$fecha2[1]; // número del mes de 01 a 12
    $anio2=$fecha2[2];
    
    $fecha1a=mktime(0,0,0,$mes1,$dia1,$anio1);
    $fecha2a=mktime(0,0,0,$mes2,$dia2,$anio2);
 
    $diferencia = $fecha2a - $fecha1a;
    $dias=$diferencia/(60*60*24);
    $dias=floor($dias)+1;
    
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