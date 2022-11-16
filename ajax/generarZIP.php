<?php
session_start();
 
if(isset($_SESSION['BIS-IT_CFACTWEB'])){
	if(isset($_POST['ids'])){
		
		$nombresXML = "";
		$nombresPDF = "";
		$rutaPDF = "";

		include_once("php/classes/DB_PDO.class.php");
		include_once("php/classes/Documento.class.php");

		try{
			$dbOpciones = 	array(
					'DBMS' => 'firebird',
					'dbHost'=>'localhost',
					'dbName'=>$_SESSION['BIS-IT_CFACTWEB']['DBNAME'],
					'dbUser'=>$_SESSION['BIS-IT_CFACTWEB']['DBUSER'],
					'dbPassword'=>$_SESSION['BIS-IT_CFACTWEB']['DBPASSWORD']
					);

			//Validamos variables
			/*if(!filter_input(INPUT_GET,'ID',FILTER_VALIDATE_FLOAT,FILTER_FLAG_ALLOW_THOUSAND)){
					throw new Exception("El ID no es correcto.");
			}*/
			$dbPDO = new DB_PDO($dbOpciones);
			
			if($dbPDO->getError()){
				// Si queremos obtener el error exacto usar: $dbPDO->getErrorMensaje();
				throw new Exception("No se pudo conectar con la Base de Datos");					
			}

			$documento = new Documento($dbPDO);
			
			
			$array = $_POST['ids'];
			$rows = explode('@',$array);
			foreach($rows as $id)
			{
				//Si hemos llegado a este punto procedeos a obtener el XML
	
				$xml = $documento->obtenerXML($id);
	
				if(isset($xml['error'])){
					header ('Content-type: text/html; charset=utf-8');
					echo $xml['error'];
				}else{
					if($xml['data']['NOMBRE_ARCHIVO']!=""){
						$nombreXML = $xml['data']['NOMBRE_ARCHIVO'].'.xml';					
						$fileXML = fopen($nombreXML, "w"); 
						fwrite($fileXML,$xml['data']['CONTENIDO']); 
						fclose($fileXML);
						$nombresXML.= $xml['data']['NOMBRE_ARCHIVO'].'.xml,';
					}else{
						throw new Exception("El archivo XML no existe para este documento.");
					}
				}
			}
			foreach($rows as $id)
			{
				$pdf = $documento->obtenerPDF($id);
	
				if(isset($pdf['error'])){
					header ('Content-type: text/html; charset=utf-8');
					echo $pdf['error'];
				}else{
					if($pdf['NOMBRE_ARCHIVO']!=""){
						
						$archivo = "";
						if($arrayComprobar['copia']){
							$pdf['NOMBRE_ARCHIVO']  = $pdf['NOMBRE_ARCHIVO']."_Copy";
						}
	
						$nombrePDF = $pdf['RUTA']."\\".$pdf['NOMBRE_ARCHIVO'].".pdf";
						
						$nombrePDF = str_replace("Archivos de programa", "Program Files", $nombrePDF);
						$rutaPDF = str_replace("Archivos de programa", "Program Files", $pdf['RUTA']);
						
						$rutaPDF = $rutaPDF;
						$nombresPDF.= $nombrePDF.",";
	
						
					}else{
						throw new Exception("El archivo PDF no existe para este documento.");
					}
				}
			}
								
			$_SESSION['filesXML'] = substr($nombresXML,0,-1);
			$_SESSION['filesPDF'] = substr($nombresPDF,0,-1);
			$_SESSION['rutaPDFS'] = $rutaPDF;
			
			
						
		}catch(Exception $e){
			header ('Content-type: text/html; charset=utf-8');
			echo $e->getMessage();
		}

	}
}

?>