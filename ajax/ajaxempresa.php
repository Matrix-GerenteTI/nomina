<?php
	ini_set('max_execution_time', 0);
	session_start();
	require_once("mysql.php");
	// conexion();
	$incomingData = isset($_GET['op']) ? $_GET['op'] : ( json_decode(file_get_contents('php://input'), true) );
	$op = isset($_GET['op']) ? $_GET['op'] : $incomingData['op'];
	switch($op){
		
		case "comboSelected":{
			$catalogo = $_POST['catalogo'];
			//$scatalogo = $_POST['scatalogo'];
			$echo = "";
			if(isset($_POST['padre']))
				$query = "SELECT * FROM c".$catalogo." WHERE id".$_POST['padre']."=".$_POST['valorpadre']." AND status=1";
			else
				$query = "SELECT * FROM c".$catalogo." WHERE status=1";
			$sql = $conexion->query($query);
			while($row = $sql->fetch_assoc())
				if($_POST['valor']==$row['id'])
					$echo.= '<option value="'.$row['id'].'" selected>'.strtoupper($row['descripcion']).'</option>';
				else
					$echo.= '<option value="'.$row['id'].'">'.strtoupper($row['descripcion']).'</option>';
			echo $echo;
			break;
		}
		
		case "listaDeptos":{
			$query = "SELECT 	*								
					  FROM 		cdepartamento
					  WHERE 		status=1";
			$sql = $conexion->query($query);
			while($row = $sql->fetch_assoc()){
				$array[] = $row;
			}
			echo json_encode($array);
			break;
		}
		
		case 'listaSucursañes':
			 $querySucursales = "SELECT * FROM csucursal WHERE status = 1 ";
			 $exeSucursales = $conexion->query( $querySucursales );
			 
			 echo json_encode( $exeSucursales->fetch_all(MYSQLI_ASSOC) );

			break;

		case "listaPuestos":{
			$query = "SELECT 	p.id as id,
								p.descripcion as puesto,
								d.descripcion as departamento
					  FROM 		cpuesto p
					  INNER JOIN cdepartamento d ON p.iddepartamento=d.id
					  AND 		p.status=1";
			$sql = $conexion->query($query);
			while($row = $sql->fetch_assoc()){
				$array[] = $row;
			}
			echo json_encode($array);
			break;
		}
		
		case "guardaDepto":{
			$departamento = $_POST['descripcion'];
			$query = "INSERT INTO cdepartamento (idpatron,descripcion,status,idpadre) VALUES (1,'".$departamento."',1,0)";
			$sql = $conexion->query($query);
			if(!$sql)
				echo 0;
			else
				echo 1;
			break;
		}
		
		case "guardaPuesto":{
			$departamento = $_POST['iddepartamento'];
			$puesto = $_POST['descripcion'];
			$query = "INSERT INTO cpuesto (iddepartamento,descripcion,status,idpadre) VALUES ('".$departamento."','".$puesto."',1,0)";
			$sql = $conexion->query($query);
			if(!$sql)
				echo 0;
			else
				echo 1;
			break;
		}
		
		case "eliminaItem":{
			$tabla = $_POST['tabla'];
			$id = $_POST['id'];
			$query = "UPDATE c".$tabla." SET status=99 WHERE id='".$id."'";
			$sql = $conexion->query($query);
			if(!$sql)
				echo 0;
			else
				echo 1;
			break;
		}
		
		case "carga":{
			$query = "SELECT 	*, s.id as estado
					  FROM 		ppatron e
					  INNER JOIN pdireccion d ON d.id=e.iddireccion
					  INNER JOIN cestado s ON s.id=d.idestado
					  WHERE 	e.status=1";
			$sql = $conexion->query($query);
			$row = $sql->fetch_assoc();
			echo json_encode($row);
			break;
		}
		
		case "guardar":{			
			$query1 = "UPDATE ppatron SET 	rfc='".$_POST['rfc']."',
											nombre_razsoc='".$_POST['nombre']."',
											curp='".$_POST['curp']."',
											registropatronal='".$_POST['registropatronal']."',
											idregimenfiscal='".$_POST['regimenfiscal']."',
											telefono='".$_POST['telefono']."',
											email='".$_POST['email']."'
									  WHERE id='1'";
			$sql1 = $conexion->query($query1);
			if(!$sql1){
				echo 0;
			}else{
				$query2 = "UPDATE pdireccion SET 	calle='".$_POST['calle']."',
													numext='".$_POST['numext']."',
													numint='".$_POST['numint']."',
													colonia='".$_POST['colonia']."',
													municipio='".$_POST['municipio']."',
													cp='".$_POST['cp']."',
													idestado='".$_POST['estado']."'
											  WHERE id='1'";
				$sql2 = $conexion->query($query2);
				if(!$sql2){
					echo 0;
				}else{
					echo 1;
				}
			}
			break;
		}

		case 'listaPuestoDepartamento':{
			$departamento = $_GET['departamento'];
			$queryPuestosDepa = "select * from cpuesto where cpuesto.iddepartamento = ".$departamento." and status=1 ";
			$exePuesto = $conexion->query($queryPuestosDepa);
			echo json_encode ($exePuesto->fetch_all(MYSQLI_ASSOC)) ;
			break;
		}

		case 'addPuestoSuperior':
		
			$puesto['dptoSup'] = $incomingData['dptoSup'];
			$puesto['puestoSup'] = $incomingData['puestoSup'];
			$puesto['dptoDep'] = $incomingData['dptoDep'];
			$puesto['puestoDep'] = $incomingData['puestoDep'];
			$puesto['sucursal'] = $incomingData['sucursal'] == -1 ?  "NULL" : $incomingData['sucursal'];

			$abstraccion = implode(',', $incomingData['abstraccion']);
			
			if ( esPuestoHijoDe($conexion, $puesto, $incomingData['sucursal']) === 0 ) {
				$queryInsertaPuestoHijo = "INSERT INTO porganigrama VALUES('',".$puesto['puestoDep'] .",".$puesto['dptoDep'].",".$puesto['sucursal'].",".$puesto['puestoSup'].",".$puesto['dptoSup'].",'$abstraccion', 1)";
				
				$conexion->query( $queryInsertaPuestoHijo);
				if( $conexion->affected_rows ){
					echo json_encode( estructuraOrganigrama($conexion) );
				}
				else{
					echo json_encode( array());
				}
			}else{
				echo "Somos familia :(";
			}
			break;
		
			case 'getOrganigrama':
				echo json_encode( estructuraOrganigrama($conexion) );
			break;
		
			case 'eliminarNodoOrganigrama':
				$idNodo = $_GET['nodo'];
				$queryEliminarNodo = "DELETE FROM porganigrama where id=$idNodo";
				
				$exeEliminanodo = $conexion->query( $queryEliminarNodo);

				echo  $conexion->affected_rows;
				break;
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

 function esPuestoHijoDe($conexion, $puesto, $sucursal = -1)
{
	extract( $puesto );
	$condicionSucursal = "";
	if ( $sucursal != -1 ) {
		$condicionSucursal = " AND idsucursal = $sucursal";
	}
	//comprueba si el puesto que se quiere jerarquizar no sea padre del puesto hijo
	$queryHijo = "SELECT idhijo,idpadre FROM porganigrama WHERE (idhijo=$puestoSup AND iddepa_hijo=$dptoSup )AND (idpadre=$puestoDep AND iddepa_padre=$dptoDep) $condicionSucursal";
	if ( $puestoSup == NULL || $puestoSup == '') {
		$queryHijo ="SELECT idhijo,idpadre FROM porganigrama WHERE idhijo is null  AND idpadre=$puestoDep AND iddepa_padre=$dptoDep";
	}
	// echo $queryHijo."<br>";
	$exeHijo = $conexion->query( $queryHijo );
	$puestoPadre = $exeHijo->fetch_all(MYSQLI_ASSOC);
	// var_dump( $puestoPadre);
	// echo "<br>";
	if ( sizeof($puestoPadre) == 0) {
		
		$queryGetPadre = "SELECT idpadre,id,iddepa_padre FROM porganigrama  WHERE idhijo=$puestoDep AND iddepa_hijo=$dptoDep $condicionSucursal";
		if ( $puestoSup == null || $puestoSup == '') {
			$queryGetPadre = "SELECT idpadre,id,iddepa_padre FROM porganigrama  WHERE idhijo is  null ";
		}
		// echo "$queryGetPadre <br>";
		$exePadre = $conexion->query( $queryGetPadre );
		$padre = $exePadre->fetch_all( MYSQLI_ASSOC);


		if ( sizeof($padre) == 0) {
			return 0;
			// esPuestoHijoDe($conexion, $puesto, $padre[0]['idpadre']);
		}else{
			
			if ( ($padre[0]['idpadre'] == $puestoSup) && ($padre[0]['iddepa_padre'] == $puestoSup) ) {
				// echo "nta? $puestoDep  $dptoDep  ---- ".$padre[0]['idpadre']."  ".$padre[0]['iddepa_padre'];
				return 1;
			}
			// if ( $padre[0]['idpadre'] === NULL) {
			// 	echo "AAAAAAAAAAAAA";
			// 	return -1;
			// }
			// if ( $padre[0]['idpadre'] == null) {
			// 	echo "ENTREEEEE";

			// 	return "NO";
			// }		
			$puesto['puestoSup'] = $padre[0]['idpadre'] ;
			$puesto['dptoSup'] = $padre[0]['iddepa_padre'];
			return esPuestoHijoDe($conexion, $puesto, $sucursal);
		}
	}else{
		
		return 1;
	}
}

 function estructuraOrganigrama($conexion)
{
	$queryOrganigrama = "SELECT porganigrama.id,cpuesto.descripcion as puestoHijo,porganigrama.idhijo,puesto.descripcion as puestoPadre,porganigrama.idpadre 
												FROM porganigrama
												INNER JOIN cpuesto ON porganigrama.idhijo = cpuesto.id
												LEFT JOIN cpuesto AS puesto On puesto.id = porganigrama.idpadre";
	$exeOrganigrama = $conexion->query( $queryOrganigrama);
	return $exeOrganigrama->fetch_all(MYSQLI_ASSOC);
}
?>