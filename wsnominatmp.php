<?php
	require_once("ajax/mysql.php");

	$fechaInicio=strtotime("13-05-2019");
	$fechaFin=strtotime("13-05-2019");
	for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
		$fecha = date("Y-m-d", $i);
		$ha1 = 8;
		$ha2 = rand(46,59);
		$ha3 = rand(1,59);
		if($ha3<10){
			$ha3 = "0".$ha3;
		}
		$ha = "0".$ha1.":".$ha2.":".$ha3;
		$dia = date("w",strtotime($fecha));
		if($dia!=0 && $dia!=6){
			$query1 = "INSERT INTO pregistros (idempleado,timecheck,idreloj) VALUES (1,'".$fecha." ".$ha."',1)";
			//$sql1 = $conexion->query($query1);
			echo $query1.";</br>";
		}
	}
	
	
?>