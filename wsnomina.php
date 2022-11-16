<?php
	require_once("ajax/mysql.php");
	for($ireloj=1;$ireloj<=11;$ireloj++){	
		/*if($ireloj!=5)
			$namearchivo = "archivo".$ireloj.".txt";
		else{
			$namearchivo = date("Ymd").".txt";

		}*/
		$namearchivo = date("Ymd")."_".$ireloj.".txt";
		
		$archivo = file($namearchivo);
		$arrT = array();
		$string = "";
		$bandera = 0;
		foreach($archivo as $valor){
			echo $valor."<br/>";
			//$string.= $valor.chr(13).chr(10).		
			/*if($ireloj!=5)
				$datosEx = explode("|",$valor);
			else*/
				$datosEx = explode("_",$valor);
				$datosEx = sizeof( $datosEx ) > 1 ? $datosEx : explode(",",$valor);
			$idEmpleado = $datosEx[0];
			$horaChecado = $datosEx[1];		
			$idDispositivo = trim(str_replace("_","",$datosEx[2]));
			$exp = explode(" ",$horaChecado);
			$hr =  explode(":" , $exp[1]);
			$fecha = $exp[0];
			$ha1 = 8;
			if($ha1==8){
				$ha2 = rand(51,59);
			}else{
				$ha2 = rand(0,4);
				$ha2 = "0".$ha2;
			}
			$ha3 = rand(1,59);
			if($ha3<10){
				$ha3 = "0".$ha3;
			}
			$ha = "0".$ha1.":".$ha2.":".$ha3;
			$he = "07:".$ha2.":".$ha3;
			
			$hb1 = rand(17,18);
			$hb2 = rand(15,59);
			$hb3 = rand(1,59);
			if($hb3<10){
				$hb3 = "0".$hb3;
			}
			$hb = $hb1.":".$hb2.":".$hb3;
			
			$dia = date("w",strtotime($fecha));
			if(!in_array($fecha,$arrT)){
				$arrT[] = $fecha;
				if($dia!=0 && $dia!=6 && $idDispositivo==1){
					$query1 = "INSERT INTO pregistros (idempleado,timecheck,idreloj) VALUES (1,'".$fecha." ".$ha."',".$idDispositivo.")";
					//$sql1 = $conexion->query($query1);
					//echo "QUERY1 :: ".$query1."</br>";
					
					//$query2 = "INSERT INTO pregistros (idempleado,timecheck,idreloj) VALUES (1,'".$fecha." ".$hb."',".$idDispositivo.")";
					//$sql2 = $conexion->query($query2);
					//echo $query2."</br>";
				}
			}
			//INSERTAMOS EN LA BD


				$query = "INSERT INTO pregistros (idempleado,timecheck,idreloj) VALUES (".$idEmpleado.",'".$horaChecado."',".$idDispositivo.")";
			
			
			$sql = $conexion->query($query);
			if(!$sql)
				$bandera++;
			echo "QUERY2 :: ".$query."</br>";
			//echo "[".$bandera."]";
		}
		if($bandera==0)
			unlink($namearchivo);
	}
	
?>