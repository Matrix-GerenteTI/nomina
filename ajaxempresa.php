<?php
	session_start();
	require_once("mysql.php");
	//conexion();
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

		case 'addPuestoSuperior':
			$puesto = $incomingData['puesto'];
			$puestoSuperior = $incomingData['superior'];
			
			if ( esPuestoHijoDe($conexion, $puesto, $puestoSuperior) === 0 ) {
				$queryInsertaPuestoHijo = "INSERT INTO porganigrama VALUES($puesto, $puestoSuperior, 1)";
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

 function esPuestoHijoDe($conexion, $puesto, $superior = "")
{
	//comprueba si el puesto que se quiere jerarquizar no sea padre del puesto hijo
	$queryHijo = "SELECT idhijo,idpadre FROM porganigrama WHERE idhijo=$superior AND idpadre=$puesto";
	if ( $superior == NULL) {
		$queryHijo ="SELECT idhijo,idpadre FROM porganigrama WHERE idhijo is null  AND idpadre=$puesto";
	}
	// echo $queryHijo;
	$exeHijo = $conexion->query( $queryHijo );
	$puestoPadre = $exeHijo->fetch_all(MYSQLI_ASSOC);

	if ( sizeof($puestoPadre) == 0) {
		
		$queryGetPadre = "SELECT idpadre,id FROM porganigrama  WHERE idhijo = $superior";
		if ( $superior == null) {
			$queryGetPadre = "SELECT idpadre,id FROM porganigrama  WHERE idhijo is  null";
		}
		// echo "$queryGetPadre <br>";
		$exePadre = $conexion->query( $queryGetPadre );
		$padre = $exePadre->fetch_all( MYSQLI_ASSOC);


		if ( sizeof($padre) == 0) {
			return 0;
			// esPuestoHijoDe($conexion, $puesto, $padre[0]['idpadre']);
		}else{
			if ($padre[0]['idpadre'] == $puesto) {
				
				return 1;
			}
			// if ( $padre[0]['idpadre'] === NULL) {
			// 	echo "AAAAAAAAAAAAA";
			// 	return -1;
			// }
			// if ( $padre[0]['idpadre'] == null) {
			// 	echo "ENTREEEEE";

			// 	return "NO MMMM";
			// }			
			return esPuestoHijoDe($conexion, $puesto, $padre[0]['idpadre']);
		}
	}else{
		
		return 1;
	}
}

 function estructuraOrganigrama($conexion)
{
	$queryOrganigrama = "SELECT cpuesto.descripcion as puestoHijo,porganigrama.idhijo,puesto.descripcion as puestoPadre,porganigrama.idpadre 
												FROM porganigrama
												INNER JOIN cpuesto ON porganigrama.idhijo = cpuesto.id
												LEFT JOIN cpuesto AS puesto On puesto.id = porganigrama.idpadre";
	$exeOrganigrama = $conexion->query( $queryOrganigrama);
	return $exeOrganigrama->fetch_all(MYSQLI_ASSOC);
}
?>