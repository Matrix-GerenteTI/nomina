<?php
	session_start();
	require_once("mysql.php");
	//conexion();

	
	$op = isset($_GET['op'])  ? $_GET['op'] : json_decode(file_get_contents('php://input'), true);
	
	$postContent = array();
	if ( is_array($op) ) {
		$postContent = $op;
		$op = $op['op'];
	}

	switch($op){
		case "lista":{
			$array = array();
			$tabla = 'p'.$_POST['id'];
		
			$nip = $_POST['nip'];
			//Obtenemos el id del contrato activo
			$query1 = "SELECT * FROM pcontrato WHERE nip='".$nip."' AND status=1";
			$sql1 = $conexion->query($query1);
			$row1 =$sql1->fetch_assoc();
			$contrato = $row1['id'];
			$inner = "";
			$select = "";
			//Sacamos los campos de la tabla a consultar para poder hacer las relaciones
			$query2 = "SHOW COLUMNS FROM ".$tabla;
			
			
			$sql2 = $conexion->query($query2);
			while($row2 =$sql2->fetch_assoc()){
				if($row2['Field']!='status' && $row2['Field']!='idcontrato'){
					if(strlen($row2['Field'])>2){
						if(strpos($row2['Field'],'id') === false){
							$select.= $tabla.".".$row2['Field']." as ".$row2['Field'].",";
						}else{
							if(strpos($row2['Field'],'id')>1){
								$select.= $tabla.".".$row2['Field']." as ".$row2['Field'].",";
							}else{
								$tablainner = substr($row2['Field'],2,strlen($row2['Field']));
								$select.= "c".substr($row2['Field'],2,strlen($row2['Field'])).".descripcion as ".substr($row2['Field'],2,strlen($row2['Field'])).", ";
								if($tablainner!='accion_correctiva'){
									$inner.= " INNER JOIN c".$tablainner." ON ".$tabla.".".$row2['Field']."=c".$tablainner.".id";
								}
							}
						}
					}else{
						$select.= $tabla.".".$row2['Field']." as ".$row2['Field'].",";
					}
				}
			}
			//$select.= "pacciones_correctivas.motivo as motivo,";
			$select = substr(trim($select),0,-1);
			
			$query = "SELECT 	".str_replace("caccion_correctiva","ctipodeduccion",$select)." 
					  FROM 		".$tabla." 
					  ".str_replace("caccion_correctiva","ctipodeduccion",$inner)."
					  WHERE 	".$tabla.".idcontrato='".$contrato."' AND ".$tabla.".status=1";
					  
			$sql = $conexion->query($query);
			
			//die();
			while($row =$sql->fetch_assoc()){
				if( isset( $row['descripcion']) ){
					$row['descripcion'] = mb_convert_encoding( $row['descripcion'] ,"UTF-8");
				}
				$array[] = $row;
			}
			echo json_encode($array);
			break;
		}
		
		case "add":{
			$tabla = "p".$_POST['id'];
			$nip = $_POST['nip'];
			//Obtenemos el id del contrato activo
			$query1 = "SELECT * FROM pcontrato WHERE nip='".$nip."' AND status=1";
			$sql1 = $conexion->query($query1);
			$row1 =$sql1->fetch_assoc();
			$contrato = $row1['id'];
			//creamos el string para el insert de los campos enviados
			$campos = "";
			$valores = "";
			foreach($_POST as $clave => $valor){
				if($clave!='id' && $clave!='nip'){
					$campos.= $clave.",";
					$valores.= "'".$valor."',";
				}
			}
			//Hacemos el insert
			$campos = substr($campos,0,-1);
			$valores = substr($valores,0,-1);
			$query = "INSERT INTO ".$tabla." (status,idcontrato,".$campos.") VALUES (1,".$contrato.",".$valores.")";
			
			$sql = $conexion->query($query);
			if(!$sql){
				echo 0;
			}else{
				echo 1;
			}
			break;
		}
		
		case "delete":{
			$tabla = "p".$_POST['id'];
			$id = $_POST['item'];
			$query = "UPDATE ".$tabla." SET status=0 WHERE id='".$id."'";
			$sql = $conexion->query($query);
			if(!$sql)
				echo 0;
			else
				echo 1;
			break;
		}

		case 'addDeduccion':{
			if ( !$postContent['deduccionAuto']) {
				$postContent['tipoProgramacion'] = -1;
				$postContent['vencimientoCargo'] = $postContent['fechaCargo'];
			}
			$contratoId = getContratoActivo( $postContent['trabajador'], $conexion);
			extract( $postContent );
			$queryInsertaDeduccion = "INSERT INTO pdeducciones VALUES('','$tipoDeduccion','$importe','1','$contratoId','$fechaCargo','$tipoProgramacion','$vencimientoCargo',NULL,NULL)";
			$exeInsertaDeduccion = $conexion->query( $queryInsertaDeduccion);

			
			if ( $exeInsertaDeduccion) {
				echo 1;
			} else {
				echo 0;
			}
			
			break;
		}

		case 'actualizaAutomatico':{
			$queryAutomaticos = "SELECT * 
						FROM pdeducciones
						 WHERE vencimiento IS NOT NULL AND pdeducciones.status != 0 ";
			$exeAutomaticos = $conexion->query( $queryAutomaticos );
			$listadoDeduccionesAuto = $exeAutomaticos->fetch_all(MYSQLI_ASSOC);

			foreach ($listadoDeduccionesAuto as $i => $deduccion) {
				$paramsToInsert['tipoDeduccion'] = $deduccion['idtipodeduccion'];
				$paramsToInsert['importe'] = $deduccion['importe'];
				$paramsToInsert['contrato'] = $deduccion['idcontrato'];
				$paramsToInsert['idDeducionPadre'] = $deduccion['idtipodeduccion'];
				$paramsToInsert['vencimiento'] = $deduccion['vencimiento'];
				$frecuencia = $paramsToInsert['frecuencia'] = $deduccion['fecuencia'];
				$fecha = "";
				
				$queryUltimaDeduccion= "SELECT max(fechaCargo) AS ultimo 
										FROM pdeducciones 
										WHERE pdeducciones.idtipodeduccion = ".$paramsToInsert['tipoDeduccion']."  AND  importe = ".$paramsToInsert['importe']." AND idcontrato=".$paramsToInsert['contrato'] ;
				$exeUltimaDeduccion = $conexion->query( $queryUltimaDeduccion );
				$ultimaDeduccion = $exeUltimaDeduccion->fetch_all( MYSQLI_ASSOC );
				$paramsToInsert['ultimaDeduccion'] = $ultimaDeduccion[0]['ultimo'];
				$paramsToInsert['conexion'] = $conexion;
				
				switch ($frecuencia) {
					case '1':
							$paramsToInsert['plus'] = "+15 day";
							registraDeduccionAutomatica( $paramsToInsert);
						break;
					case '2':
							$paramsToInsert['plus'] = "next month";
							registraDeduccionAutomatica( $paramsToInsert);
						break;
					case '3':
							$paramsToInsert['plus'] = "+1 year";
							registraDeduccionAutomatica( $paramsToInsert);
						break;
				}

			}
		}
	}

 function registraDeduccionAutomatica( $data )
{
	extract( $data );
	
	$fechaProgramada = date('Y-m-d', strtotime($plus, strtotime($ultimaDeduccion) ) );
	$fechaActual = date('Y-m-d');
	//Se convierte las fechas a time para que pueda evaluarlos en una condición if
	if ( strtotime($fechaActual) >= strtotime($fechaProgramada) ){
		$insertDeduccionAuto = "INSERT INTO pdeducciones(idtipodeduccion,importe,status,idcontrato,fechaCargo,fecuencia)
				VALUES('$tipoDeduccion','$importe',1,$contrato,'$fechaProgramada',-1)";

				$exeUltimaDeduccion = $conexion->query( $insertDeduccionAuto );		

				if ( strtotime($fechaProgramada) >= strtotime($vencimiento) ) {
					$saldarDeduccion = "UPDATE pdeducciones set pdeducciones.status = 0 where idtipodeduccion=$idDeducionPadre";
					$conexion->query( $saldarDeduccion );
				}
	}

		
}

function getContratoActivo( $trabajadorId, $conexion)
{
	$query1 = "SELECT * FROM pcontrato WHERE nip='".$trabajadorId."' AND status=1";
	$sql1 = $conexion->query($query1);
	$contato =$sql1->fetch_assoc();
	$contratoId = $contato['id'];	

	return $contratoId;
}

function CalculaEdad( $fecha ) {
    list($Y,$m,$d) = explode("-",$fecha);
    return( date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y );
}

function CalculaAntiguedadSAT( $fecha ) {
    $fecha1 = new DateTime($fecha);
	$fecha2 = new DateTime(date('Y-m-d'));
	$fecha = $fecha1->diff($fecha2);
	return "P".$fecha->y."Y".$fecha->m."M".$fecha->d."D";
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

function calculaIMC($peso,$estatura){
	if($estatura>0 && $peso>0)
		$imcnum = $peso/($estatura*$estatura);
	else
		return "";
	if($imcnum<18)
		return "PESO BAJO";
	if($imcnum>=18 && $imcnum<25)
		return "NORMAL";
	if($imcnum>=25 && $imcnum<27)
		return "SOBREPESO";
	if($imcnum>=27)
		return "OBESIDAD";
}
?>