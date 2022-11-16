<?php
    require_once("lib/nusoap.php");
    require_once("satxmlsv.php"); 
    function getCFDI($user,$password,$XMLstring,$emisor,$serie,$folio) {
		$connect = mysql_connect("localhost","superuser","serygra300107+-");
		$selecdb = mysql_select_db("clientes",$connect);
		
		$query = "SELECT * FROM clientes WHERE usuario='".$user."' AND password='".$password."' AND status=1";
		$sql = mysql_query($query);
		$idCliente = 0;
		$it = 0;
		while($row = mysql_fetch_assoc($sql)){
			$it++;	
			$idCliente = $row['id'];
		}
		if($it>0){
			if($XMLstring != "" && $emisor!= "" && $serie != "" && $folio!="") {
				/*$invoice_path1 = "ejemplo.xml";
				$xml_file1 = fopen($invoice_path1, "rb");
				$xml_content1 = fread($xml_file1, filesize($invoice_path1));
				fclose($xml_file1);*/
				$validacion = satxmlsv_valida($XMLstring);
				if($validacion=="ok"){
				
					$xml = satxmlsv($XMLstring,$emisor,$serie,$folio);
										
					//TIMBRADO
					
					#DEMO
					//$username = 'sergio@xiontecnologias.com';
					//$password = 'P4ssw0rd+';
					
					# Username and Password, assigned by FINKOK
					$username = 'sergio@xiontecnologias.com';#'serygra@gmail.com';
					$password = 'Serygra300107+-';#'Serygra300107+';
					
					# Read the xml file and encode it on base64
					$invoice_path = "./send/".$emisor."/".$serie.$folio.".xml";
					$xml_file = fopen($invoice_path, "rb");
					$xml_content = fread($xml_file, filesize($invoice_path));
					fclose($xml_file);
					 
					# In newer PHP versions the SoapLib class automatically converts FILE parameters to base64, so the next line is not needed, otherwise uncomment it
					#$xml_content = base64_encode($xml_content);
					
					#DEMO
					//$url = 'http://demo-facturacion.finkok.com/servicios/soap/stamp.wsdl';
					
					#PRODUCCION
					$url = 'https://facturacion.finkok.com/servicios/soap/stamp.wsdl';
					
					/* Tiempo límite de espera entre la conexión de 10 segundos */
					//$timeout = stream_context_create(array('http' => array('timeout' => 50))); 
					/* Verifica si la url existe */
					//if(@file_get_contents($url, 0, $timeout)){
					# Consuming the stamp service
					//$url = "http://demo-facturacion.finkok.com/servicios/soap/stamp.wsdl";
					$client = new SoapClient($url);
					 
					$params = array(
					  "xml" => $xml_content,
					  "username" => $username,
					  "password" => $password
					);
					$response = $client->__soapCall("stamp", array($params));
					//print_r($response);
					$xmlResp = $response->stampResult->xml;
					if(trim($xmlResp)!=""){
						if(is_string($xmlResp)){
							if(strlen($xmlResp)>200){
								$response_path = "./CFDIS/".$emisor."_".$serie.$folio.".xml";
								$xml_out_file = fopen($response_path, "w");
								fwrite($xml_out_file, $xmlResp);
								fclose($xml_out_file);
								return $xmlResp; 
							}
							else
								return "INCIDENCIA 1920: Error en STRLEN, contacte a soporte o intente de nuevo";
						}else{
							return "INCIDENCIA 1900: La respuesta no es un STRING, contacte a soporte o intente de nuevo";	
						}
					}else{
						return "INCIDENCIA 1902: ".$response->stampResult->Incidencias->Incidencia->MensajeIncidencia;
					}
					
					/*$carpeta = "CFDIS/".$emisor;
					if (!file_exists($carpeta)) {
						mkdir($carpeta, 0777, true);
					}*/
					
					
				}else{
					if(strlen($validacion)>1)
						return $validacion;
					else
						return "INCIDENCIA 1900: Error desconocido, contacte a soporte o intente de nuevo";
				}
			}
			else {
				return "INCIDENCIA 1901: Los atributos XML, emisor, serie y folio no deben ir vacios";
			}
		}else{
			return "INDICENCIA 1001: Acceso denegado";	
		}
    }
	
	function addRFC($user,$password,$RFC,$razon,$calle,$noInterior,$noExterior,$colonia,$cp,$municipio,$estado,$email) {
		$connect = mysql_connect("localhost","superuser","serygra300107+-");
		$selecdb = mysql_select_db("clientes",$connect);
		
		$query = "SELECT * FROM clientes WHERE usuario='".$user."' AND password='".$password."' AND status=1";
		$sql = mysql_query($query);
		$it = 0;
		$idcliente = 0;
		while($row = mysql_fetch_assoc($sql)){
			$it++;
			$idcliente = $row['id'];
		}
		if($it>0){
			if($RFC != "") {
				$incidencia = "Error desconocido, contacte con soporte";
				$num = 0;
				$qs = "SELECT * FROM subclientes WHERE idcliente='".$idcliente."' AND rfc='".$RFC."'";
				$ss = mysql_query($qs);
				while($rs = mysql_fetch_assoc($ss)){
					$num++;	
				}
				if($num==0){
					#DEMO
					//$username = 'sergio@xiontecnologias.com';
					//$password = 'P4ssw0rd+';
					
					# Username and Password, assigned by FINKOK
					$username = 'sergio@xiontecnologias.com';#'serygra@gmail.com';
					$password = 'Serygra300107+-';#'Serygra300107+';
					 
					# In newer PHP versions the SoapLib class automatically converts FILE parameters to base64, so the next line is not needed, otherwise uncomment it
					#$xml_content = base64_encode($xml_content);
					
					#DEMO
					//$url = 'http://demo-facturacion.finkok.com/servicios/soap/registration.wsdl';
					
					#PRODUCCION
					$url = 'https://facturacion.finkok.com/servicios/soap/registration.wsdl';
					
					/* Tiempo límite de espera entre la conexión de 10 segundos */
					//$timeout = stream_context_create(array('http' => array('timeout' => 50))); 
					/* Verifica si la url existe */
					//if(@file_get_contents($url, 0, $timeout)){
					# Consuming the stamp service
					//$url = "http://demo-facturacion.finkok.com/servicios/soap/stamp.wsdl";
					$client = new SoapClient($url);
					 
					$params = array(
					  "reseller_username" => $username,
					  "reseller_password" => $password,
					  "taxpayer_id" => $RFC
					);
					$response = $client->__soapCall("Add", array($params));
					//print_r($response);
					$respuesta = $response->addResult->message;
					
					if($respuesta == "Account Created successfully" || $respuesta == "Account Already exists"){
						$query2 =  "INSERT INTO subclientes (rfc,razon_social,calle,numeroExt,numeroInt,colonia,cp,municipio,estado,email,idcliente) VALUES ('".$RFC."','".$razon."','".$calle."','".$noInterior."','".$noExterior."','".$colonia."','".$cp."','".$municipio."','".$estado."','".$email."','".$idcliente."')";
						$sql2 = mysql_query($query2);
						
						$carpeta = 'send/'.$RFC;
						if (!file_exists($carpeta)) {
							mkdir($carpeta, 0777, true);
						}
						
						$incidencia = "Cuenta creada satisfactoriamente";
					}else{
						$incidencia = $respuesta;	
					}
				}else{
					$query2 =  "UPDATE subclientes  SET 	razon_social='".$razon."',
															calle='".$calle."',
															numeroExt='".$noExterior."',
															numeroInt='".$noInterior."',
															colonia='".$colonia."',
															cp='".$cp."',
															municipio='".$municipio."',
															estado='".$estado."',
															email='".$email."'
													WHERE 	id='".$num."'";
					$sql2 = mysql_query($query2);
					$incidencia = "El RFC ya se encuentra registrado";
				}
				return $incidencia;
				/*$response_path = "CFDI/AAD990814BP7/A1.xml";
				$xml_out_file = fopen($response_path, "w");
				fwrite($xml_out_file, $xmlResp);
				fclose($xml_out_file);*/
			}else {
				return "INCIDENCIA 1901: No se ha recibido un RFC valido";
			}
		}else{
			return "INDICENCIA 1001: Acceso denegado";	
		}
    }
	
	function cancelaCFDI($user,$password,$RFC, $UUID) {
		$connect = mysql_connect("localhost","superuser","serygra300107+-");
		$selecdb = mysql_select_db("clientes",$connect);
		
		$query = "SELECT * FROM clientes WHERE usuario='".$user."' AND password='".$password."' AND status=1";
		$sql = mysql_query($query);
		$it = 0;
		$idcliente = 0;
		while($row = mysql_fetch_assoc($sql)){
			$it++;
			$idcliente = $row['id'];
		}
		if($it>0){
			if($RFC != "") {
				$incidencia = "Error desconocido, contacte con soporte";
				$num = 0;
				$qs = "SELECT * FROM subclientes WHERE idcliente='".$idcliente."' AND rfc='".$RFC."'";
				$ss = mysql_query($qs);
				while($rs = mysql_fetch_assoc($ss)){
					$num++;	
				}
				if($num>0){
					$errores = array(201=>'Folio cancelado exitosamente',
									 202=>'Folio cancelado previamente',
									 203=>'No corresponde el RFC del Emisor y de quien solicita la cancelacion',
									 205=>'Folio inexistente',
									 501=>'Autenticacion no valida',
									 702=>'No ha registrado el RFC emisor bajo la cuenta del PAC',
									 703=>'Cuenta en el PAC suspendida',
									 704=>'Error en la contrasena de la llave privada',
									 708=>'No se pudo conectar al SAT',
									 709=>'No se pudo generar la cancelacion intente mas tarde, si ya es la segunda vez que ve este mensaje contacte a Soporte Tecnico',
									 711=>'Error en el certificado al cancelar');
					#DEMO
					//$username = 'sergio@xiontecnologias.com';
					//$password = 'P4ssw0rd+';
					
					# Username and Password, assigned by FINKOK
					$username = 'sergio@xiontecnologias.com';#'serygra@gmail.com';
					$password = 'Serygra300107+-';#'Serygra300107+';
					 
					$cer_path = "certificados/".$RFC.".cer.pem";
					$cer_file = fopen($cer_path, "r");
					$cer_content = fread($cer_file, filesize($cer_path));
					fclose($cer_file);
					# In newer PHP versions the SoapLib class automatically converts FILE parameters to base64, so the next line is not needed, otherwise uncomment it
					//$cer_content = base64_encode($cer_content);
					
					# Read the Encrypted Private Key (des3) file on PEM format and encode it on base64
					$key_path = "certificados/".$RFC.".enc.key";
					$key_file = fopen($key_path, "r");
					$key_content = fread($key_file,filesize($key_path));
					fclose($key_file);
					
					#DEMO
					//$url = 'http://demo-facturacion.finkok.com/servicios/soap/registration.wsdl';
					
					#PRODUCCION
					$url = "https://facturacion.finkok.com/servicios/soap/cancel.wsdl";
					
					$taxpayer_id = $RFC; # The RFC of the Emisor
					$invoices = array("".$UUID.""); # A list of UUIDs
	
					$client = new SoapClient($url,array("soap_version" => SOAP_1_1,"trace" => 1));
					$params = array(
					  "UUIDS" => array('uuids' => $invoices),
					  "username" => $username,
					  "password" => $password,
					  "taxpayer_id" => $taxpayer_id,
					  "cer" => $cer_content,
					  "key" => $key_content
					);
					$response = $client->__soapCall("cancel", array($params));
					//print_r($response);
					if(count(get_object_vars($response->cancelResult))>1)
						$status = $response->cancelResult->Folios->Folio->EstatusUUID;
					else
						$status = 709;
					
					
					if($status == 201){
						return utf8_encode($response->cancelResult->Acuse);
					}else{
						return utf8_encode($errores[$status]);	
					}
				}else{
					return "El RFC no se encuentra registrado";
				}
			}else {
				return "INCIDENCIA 1901: No se ha recibido un RFC valido";
			}
		}else{
			return "INDICENCIA 1001: Acceso denegado";	
		}
    }
	
	function getTOTALES($user,$password,$anio,$m) {
		$connect = mysql_connect("localhost","superuser","serygra300107+-");
		$selecdb = mysql_select_db("clientes",$connect);
		$arrMes = array('ENERO'=>1,
						'FEBRERO'=>2,
						'MARZO'=>3,
						'ABRIL'=>4,
						'MAYO'=>5,
						'JUNIO'=>6,
						'JULIO'=>7,
						'AGOSTO'=>8,
						'SEPTIEMBRE'=>9,
						'OCTUBRE'=>10,
						'NOVIEMBRE'=>11,
						'DICIEMBRE'=>12);
		/*$mesAnterior = date("Y-m-d",strtotime("-1 month"));
		$mesAnterior = date("m", strtotime($mesAnterior));*/
		//$mesActual = date("m");
		//$mesAnterior = $mesAnterior * 1;
		//$mesActual = $mesActual * 1;
		//$arrMeses = array($mesActual);
		$mes = $arrMes[$m];
		$query = "SELECT * FROM clientes WHERE usuario='".$user."' AND password='".$password."' AND status=1";
		$sql = mysql_query($query);
		$it = 0;
		$idcliente = 0;
		while($row = mysql_fetch_assoc($sql)){
			$it++;
			$idcliente = $row['id'];
		}
		if($it>0){
		
			$incidencia = "Error desconocido, contacte con soporte";
			$array = array();
			$num = 0;
			$qs = "SELECT *,0 as anterior,timbres as actual FROM subclientes WHERE idcliente='".$idcliente."'";
			$ss = mysql_query($qs);
			while($rs = mysql_fetch_assoc($ss)){
				$array[$num]['id'] = $rs['id'];
				$array[$num]['rfc'] = $rs['rfc'];
				/*$array[$num]['razon_social'] = $rs['razon_social'];
				$array[$num]['calle'] = $rs['calle'];
				$array[$num]['numeroExt'] = $rs['numeroExt'];
				$array[$num]['numeroInt'] = $rs['numeroExt'];
				$array[$num]['colonia'] = $rs['colonia'];
				$array[$num]['cp'] = $rs['cp'];
				$array[$num]['municipio'] = $rs['municipio'];
				$array[$num]['estado'] = $rs['estado'];
				$array[$num]['email'] = $rs['email'];
				$array[$num]['timbres'] = $rs['timbres'];
				$array[$num]['idcliente'] = $rs['idcliente'];*/
				
				/*$n2=1;
				foreach($arrMeses as $mes){*/
					//$m1 = $mes;
					//$m2 = $mes + 1;
					$mes2 = $mes + 1;
					if($mes2 == 13){
						$mes2 = 1;
						$anio2 = $anio + 1;
					}else{
						$anio2 = $anio;
					}
					if($mes>9)
						$fecha_inicio = $anio."-".$mes."-01T00:00:00";
					else
						$fecha_inicio = $anio."-0".$mes."-01T00:00:00";
					if($mes2>9)
						$fecha_fin = $anio2."-".$mes2."-01T00:00:00";
					else
						$fecha_fin = $anio2."-0".$mes2."-01T00:00:00";
					# Username and Password, assigned by FINKOK
					$username = 'sergio@xiontecnologias.com';
					$password = 'Serygra300107+-';
					
					$taxpayer_id = $rs['rfc']; # The RFC of the Emisor
					
					$url = "http://facturacion.finkok.com/servicios/soap/utilities.wsdl";
		
					$client = new SoapClient($url,array("soap_version" => SOAP_1_1,'trace' => true, 'cache_wsdl' => WSDL_CACHE_MEMORY));
					$params = array(
					  "username" => $username,
					  "password" => $password,
					  "taxpayer_id" => $taxpayer_id,
					  "date_from" => $fecha_inicio,
					  "date_to" => $fecha_fin
					);
					//print_r($params);
					$response = $client->__soapCall("report_total", array($params));
					if(property_exists($response->report_totalResult->result->ReportTotal,'total'))
						$array[$num]['actual'] = $response->report_totalResult->result->ReportTotal->total;
					//if($n2==1)
					///	$array[$num]['anterior'] = $response->report_totalResult->result->ReportTotal->total;
					//else
					//	$array[$num]['actual'] = $response->report_totalResult->result->ReportTotal->total;
					//$n2++;
				//}
				$num++;
			}
			
			return json_encode($array);
			/*$response_path = "CFDI/AAD990814BP7/A1.xml";
			$xml_out_file = fopen($response_path, "w");
			fwrite($xml_out_file, $xmlResp);
			fclose($xml_out_file);*/
		
		}else{
			return "INDICENCIA 1001: Acceso denegado";	
		}
    }
	
	function login($user,$password) {
		$connect = mysql_connect("localhost","superuser","serygra300107+-");
		$selecdb = mysql_select_db("clientes",$connect);
		
		$query = "SELECT * FROM clientes WHERE usuario='".$user."' AND password='".$password."' AND status=1";
		$sql = mysql_query($query);
		$it = 0;
		while($row = mysql_fetch_assoc($sql)){
			$it++;
		}
		if($it>0){
	
			return "OK";
		}else{
			return "INDICENCIA 1001: Acceso denegado";	
		}
    }
      
    $server = new soap_server();
	$server->soap_defencoding = 'utf-8';
	$server->decode_utf8 = false;
    $server->configureWSDL("cfdi", "urn:cfdi");
      
    $server->register("getCFDI",
        array("user" => "xsd:string","password" => "xsd:string","XMLstring" => "xsd:string","emisor" => "xsd:string","serie" => "xsd:string","folio" => "xsd:string"),
        array("return" => "xsd:string"),
        "urn:cfdi",
        "urn:cfdi#getCFDI",
        "rpc",
        "encoded",
        "Regresa el XML timbrado");
	$server->register("addRFC",
        array("user" => "xsd:string","password" => "xsd:string","RFC" => "xsd:string","razonsocial" => "xsd:string","calle" => "xsd:string","noInterior" => "xsd:string","noExterior" => "xsd:string","colonia" => "xsd:string","cp" => "xsd:string","municipio" => "xsd:string","estado" => "xsd:string","email" => "xsd:string"),
        array("return" => "xsd:string"),
        "urn:cfdi",
        "urn:cfdi#addRFC",
        "rpc",
        "encoded",
        "Agrega un nuevo registro de emisor");
	$server->register("getTOTALES",
        array("user" => "xsd:string","password" => "xsd:string","anio" => "xsd:string","mes" => "xsd:string"),
        array("return" => "xsd:string"),
        "urn:cfdi",
        "urn:cfdi#getTOTALES",
        "rpc",
        "encoded",
        "Obtiene los totales de los RFC registrados");
	$server->register("login",
        array("user" => "xsd:string","password" => "xsd:string"),
        array("return" => "xsd:string"),
        "urn:cfdi",
        "urn:cfdi#login",
        "rpc",
        "encoded",
        "Autenticacion");
     $server->register("cancelaCFDI",
        array("user" => "xsd:string","password" => "xsd:string","rfc" => "xsd:string","uuid" => "xsd:string"),
        array("return" => "xsd:string"),
        "urn:cfdi",
        "urn:cfdi#cancelaCFDI",
        "rpc",
        "encoded",
        "Cancelacion de CFDIs");
    //$server->service($HTTP_RAW_POST_DATA);
	if ( !isset( $HTTP_RAW_POST_DATA ) ) $HTTP_RAW_POST_DATA =file_get_contents( 'php://input' );
$server->service($HTTP_RAW_POST_DATA);
?>