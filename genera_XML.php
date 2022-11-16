<?php
session_start();
require_once("satxmlsv22.php");
require_once("config.inc.php");
$conexion=mysql_connect(SERVER,USER,PASS);
$db=mysql_select_db(DB,$conexion);

//OBTENEMOS LOS DATOS QUE VAMOS A METER EN LA FACTURA PARA LUEGO GENERAR EL XML
$q = "SELECT * FROM cfdi";
$s = mysql_query($q);
$r1 = mysql_fetch_array($s);

$q = "SELECT AUTO_INCREMENT as folio FROM information_schema.TABLES WHERE TABLE_SCHEMA='".DB."' AND TABLE_NAME='facturas'";
$s = mysql_query($q);
$r3 = mysql_fetch_array($s);

$subtotal = $_POST['subtotal'];
$descuento = $_POST['descuento'];
$total = $_POST['total'];

$serie = $_POST['serieFact'];
$folio = $r3['folio'];
$noCertificado = $r1['noCertificado'];
if($_POST['tipoVenta']=="CONTADO"){
	$condicionesDePago = "CONTADO";
	$formaDePago = 'PAGO EN UNA SOLA EXHIBICION';
}
else{
	$condicionesDePago = "CREDITO";
	$formaDePago = 'PAGO EN PARCIALIDADES';
}
$subtotalCFDI = $subtotal;
$descuentoCFDI = $_POST['descuentos'];
$totalCFDI = $total;
$tipoDeComprobante = 'ingreso';
$metodoDePago = $_POST['tipoPago'];
$noCuenta = $_POST['noCuenta'];
$LugarExpedicion = 'TUXTLA GUTIERREZ, CHIAPAS. MEXICO';
$TipoCambio = 1;
$Moneda = 'PESOS';			
$cliente = $_POST['nip'];
$impuesto = 'IVA';
$tasa = 16;
$importe = $descuento;

$query = "SELECT * FROM empresa";
$sql = mysql_query($query);
$rowEmisor = mysql_fetch_array($sql);

$query = "SELECT * FROM socios WHERE clave='".$cliente."'";
$sql = mysql_query($query);
$rowReceptor = mysql_fetch_array($sql);

$query = "SELECT * FROM unidades";
$sql = mysql_query($query);
$arrUnidades = array();
while($rowUnidades = mysql_fetch_array($sql)){
	$arrUnidades[$rowUnidades['id']] = $rowUnidades['unidadMedida'];	
}


#$array = "SIN MARCA@1@SERVICIO PRUEBA@0@200@0000000000020@4@1@200|herra@0@PRODUCTO PRUEBA@0@100@0000000000010@4@1@100";
$array = $_POST['detalles'];
$rows = explode('|',$array);
$conceptosArray = array('');
$n = 1;
foreach($rows as $row)
{
	$dato = explode('@',$row);
	$conceptosArray[$n] = array((string)$dato[7],(string)$dato[9],(string)$dato[2],(string)$dato[4],(string)$dato[8]);
	$n++;
}

$array2 = $_POST['impuestos'];
$rows2 = explode('|',$array2);
$impuestosArray = array('');
$n2 = 1;
foreach($rows2 as $row2)
{
	$dato2 = explode('@',$row2);
	$impuestosArray[$n2] = array($dato2[0],(string)$dato2[1],$dato2[2],$dato2[3],$dato2[4]);
	$n2++;
}

/*$query = "SELECT COUNT(*) as cantidad, CASE modelo WHEN 0 THEN 'PIEZA' WHEN 1 THEN 'NO APLICA' END as unidad, color_material as descripcion, importe as precioUnitario, (COUNT(*)*importe) as importeTotal FROM pedidos_det WHERE idventa='".$row['idventa']."' AND status=2 GROUP BY clave";
$sql = mysql_query($query);

$n = 1;
while($rwConcepto = mysql_fetch_array($sql)){
	$conceptosArray[$n] = array((string)$dato[7],(string)$rwConcepto['unidad'],(string)$rwConcepto['descripcion'],(string)$rwConcepto['precioUnitario'],(string)$dato[8]);
	$n++;
}*/

//ARREGLO DE EJEMPLO DE CONCEPTOS... El indice 0 (cero) no lleva datos
//$conceptos = array($conceptosArray);

date_default_timezone_set('America/Mexico_City');

//************  DATOS GENERALES DE LA FACTURA ***********
$arreglo = array("xmlns:cfdi"=>"http://www.sat.gob.mx/cfd/3",
                 "xmlns:xsi"=>"http://www.w3.org/2001/XMLSchema-instance",
                 "xsi:schemaLocation"=>"http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv32.xsd",
				 "version"=>"3.2",
                 "serie"=>$serie,
                 "folio"=>$folio,
                 "fecha"=>date("Y-m-d")."T".date("h:i:s"),
              	 #"fecha"=>"2013-01-12T10:45:12",
				 "sello"=>"@",
				 "formaDePago"=>$formaDePago,
				 "condicionesDePago"=>$condicionesDePago,
				 "noCertificado"=>$noCertificado,
				 "certificado"=>"@",
				 "subTotal"=>$subtotalCFDI,
				 "descuento"=>$descuentoCFDI,
				 "total"=>$totalCFDI,
				 "tipoDeComprobante"=>$tipoDeComprobante,
				 "metodoDePago"=>$metodoDePago,
				 "NumCtaPago"=>$noCuenta,
				 "LugarExpedicion"=>$LugarExpedicion,
				 "tipoDeComprobante"=>$tipoDeComprobante,
				 "TipoCambio"=>$TipoCambio,
				 "Moneda"=>$Moneda);

#************  DATOS DEL EMISOR ***********
$arreglo['Emisor'] = array("rfc"=>utf8_decode($rowEmisor['rfc']),
                           "nombre"=>utf8_decode($rowEmisor['nombre']));

$arreglo['Emisor']['ExpedidoEn'] = array("calle"=>utf8_decode($rowEmisor['calle']),
                        				 "noExterior"=>utf8_decode($rowEmisor['noExterior']),
                       					 "noInterior"=>utf8_decode($rowEmisor['noInterior']),
										 "colonia"=>utf8_decode($rowEmisor['colonia']),
										 "municipio"=>utf8_decode($rowEmisor['municipio']),
										 "estado"=>utf8_decode($rowEmisor['estado']),
										 "pais"=>utf8_decode($rowEmisor['pais']),
										 "codigoPostal"=>utf8_decode($rowEmisor['codigoPostal']));

#************  DATOS DEL RECEPTOR ***********
$arreglo['Regimen'] = array("Regimen"=>$rowEmisor['regimen']);

#************  DATOS DEL RECEPTOR ***********
$arreglo['Receptor'] = array("rfc"=>utf8_decode($rowReceptor['ife']),
                             "nombre"=>trim(utf8_decode($rowReceptor['nombre']).' '.utf8_decode($rowReceptor['paterno']).' '.utf8_decode($rowReceptor['materno'])));

$arreglo['Receptor']['Domicilio'] = array("calle"=>utf8_decode($rowReceptor['calle']),
										  "noExterior"=>utf8_decode($rowReceptor['noExterior']),
										  "noInterior"=>utf8_decode($rowReceptor['noInterior']),
										  "colonia"=>utf8_decode($rowReceptor['colonia']),
										  "municipio"=>utf8_decode($rowReceptor['ciudad']),
										  "estado"=>utf8_decode($rowReceptor['estado']),
										  "pais"=>utf8_decode($rowReceptor['pais']),
										  "codigoPostal"=>utf8_decode($rowReceptor['codigoPostal']));

#************  CONCEPTOS DE LA FACTURA ***********
$arreglo['Conceptos'] = array();

for($i=1;$i<count($conceptosArray);$i++){
	//if($conceptosArray[$i][0]>0){
		$arreglo['Conceptos'][$i]['cantidad'] = $conceptosArray[$i][0];
		$arreglo['Conceptos'][$i]['unidad'] = $conceptosArray[$i][1];
		$arreglo['Conceptos'][$i]['descripcion'] = utf8_decode($conceptosArray[$i][2]);	
		$arreglo['Conceptos'][$i]['valorUnitario'] = $conceptosArray[$i][3];
		$arreglo['Conceptos'][$i]['importe'] = $conceptosArray[$i][4];
	//}
}

$arreglo['Traslados'] = array();
$arreglo['Retenciones'] = array();
$totalTraslados = 0;

$h=1;
for($i=1;$i<count($impuestosArray);$i++){
	if($impuestosArray[$i][3]==1){
		if($impuestosArray[$i][2]>0){
			$arreglo['Traslados'][$h]['impuesto'] = $impuestosArray[$i][1];
			$arreglo['Traslados'][$h]['tasa'] = $impuestosArray[$i][2];
			$arreglo['Traslados'][$h]['importe'] = number_format($impuestosArray[$i][4],2,".","");
			$totalTraslados = $totalTraslados + $impuestosArray[$i][4];	
			$h++;
		}
	}
}

$totalRetenciones = 0;

$h = 1;
for($i=1;$i<count($impuestosArray);$i++){
	if($impuestosArray[$i][3]==2){
		if($impuestosArray[$i][4]>0){
			$arreglo['Retenciones'][$h]['impuesto'] = $impuestosArray[$i][1];
			$arreglo['Retenciones'][$h]['importe'] = number_format($impuestosArray[$i][4],2,".","");
			$totalRetenciones = $totalRetenciones + $impuestosArray[$i][4];	
			$h++;
		}
	}
}
		

$arreglo['totalesImpuestos'] = array('totalTraslado'=>number_format($totalTraslados,2,".",""),
									 'totalRetencion'=>number_format($totalRetenciones,2,".",""));


/*$arreglo['Impuestos'] = array("impuesto"=>$impuesto,
							  "tasa"=>$tasa,
							  "importe"=>$importe);*/

$xml = satxmlsv22($arreglo,NULL,'./send/');    // Genera cadena XML en base a arreglo asociativo y cadena EDI
//print $xml;         // Muestra el XML



//TIMBRADO

#DEMO
//$username = 'serygra@gmail.com';
//$password = 'Serygra300107+';

# Username and Password, assigned by FINKOK
$username = 'sergio@xiontecnologias.com';#'serygra@gmail.com';
$password = 'Serygra300107+-';#'Serygra300107+';

# Read the xml file and encode it on base64
$invoice_path = "./send/".$serie."".$folio.".xml";
$xml_file = fopen($invoice_path, "rb");
$xml_content = fread($xml_file, filesize($invoice_path));
fclose($xml_file);
 
# In newer PHP versions the SoapLib class automatically converts FILE parameters to base64, so the next line is not needed, otherwise uncomment it
#$xml_content = base64_encode($xml_content);

$arrResp = array('status'=>3,
				 'mensaje'=>'',
				 'UUID'=>0,
				 'NoCertificadoSAT'=>0,
				 'Fecha'=>0,
				 'SatSeal'=>0,
				 'selloCFD'=>0);
#DEMO
//$url = 'http://demo-facturacion.finkok.com/servicios/soap/stamp.wsdl';

#PRODUCCION
$url = 'https://facturacion.finkok.com/servicios/soap/stamp.wsdl';

/* Tiempo límite de espera entre la conexión de 10 segundos */
$timeout = stream_context_create(array('http' => array('timeout' => 10))); 
/* Verifica si la url existe */
if(@file_get_contents($url, 0, $timeout)){
	# Consuming the stamp service
	//$url = "http://demo-facturacion.finkok.com/servicios/soap/stamp.wsdl";
	$client = new SoapClient($url);
	 
	$params = array(
	  "xml" => $xml_content,
	  "username" => $username,
	  "password" => $password
	);
	$response = $client->__soapCall("stamp", array($params));
	$xmlResp = $response->stampResult->xml;
	
	if($xmlResp!=""){
		$arrResp['status'] = 0;
		$arrResp['UUID'] = $response->stampResult->UUID;
		$arrResp['NoCertificadoSAT'] = $response->stampResult->NoCertificadoSAT;
		$arrResp['Fecha'] = $response->stampResult->Fecha;
		$arrResp['SatSeal'] = $response->stampResult->SatSeal;
		$arrResp['selloCFD'] = '';
		
		$sxe = new SimpleXMLElement($xmlResp);
		$ns = $sxe->getNamespaces(true);
		$sxe->registerXPathNamespace('c', $ns['cfdi']);
		$sxe->registerXPathNamespace('t', $ns['tfd']);
		$selloCFD = "";
		foreach ($sxe->xpath('//t:TimbreFiscalDigital') as $tfd) {
			$selloCFD = $tfd['selloCFD'];
		}
		
		$arrResp['selloCFD'] = "".$selloCFD;
		
		# Guardar el XML timbrado
		$response_path = "CFDI/".$rowEmisor['rfc']."/".$serie."".$folio.".xml";
		$xml_out_file = fopen($response_path, "w");
		fwrite($xml_out_file, $xmlResp);
		fclose($xml_out_file);
	}
	else{
		$arrResp['status'] = 1;//RESPUESTA DE ERROR DEL PACMensajeIncidencia
		$arrResp['mensaje'] = $response->stampResult->Incidencias->Incidencia->MensajeIncidencia;
	}
}
else{
	$arrResp['status'] = 2; //SERVIDOR DE PAC FUERA DE LINEA
}
echo json_encode($arrResp);
?>