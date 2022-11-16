<?php
	session_start();
	require_once("mysql.php");
	//conexion();
	$op = $_GET['op'];
	switch($op){
		
		case "buslistaTimbrado":{
			$departamento = $_POST['departamento'];
			$nombre = $_POST['nombre'];
			$array = array();
			$n=0;
			$query = "SELECT 	e.nip as nip,
								e.nombre as nombre,
								d.descripcion as departamento,
								c.id as idcontrato
					  FROM 		pempleado e 
					  INNER JOIN pcontrato c ON c.nip=e.nip
					  INNER JOIN cdepartamento d ON c.iddepartamento=d.id  
					  WHERE 	e.nombre LIKE '%".$nombre."%' 
					  AND 		d.id LIKE '%".$departamento."%'
					  AND		e.status>0";
			$sql = $conexion->query($query);
			while($row = $sql->fetch_assoc()){
				$array[$n] = $row;
				$q1 = "SELECT SUM(gravado) as gravado, SUM(excento) as excento FROM ppercepciones WHERE idcontrato=".$row['idcontrato']." AND status=1 GROUP BY idcontrato";
				$s1 = $conexion->query($q1);
				$r1 = $s1->fetch_assoc();
				$percepcion = $r1['gravado'] + $r1['excento'];
				if($percepcion>0)
					$array[$n]['estado'] = 'listo';
				else
					$array[$n]['estado'] = 'noinfo';
				$n++;
			}
			echo json_encode($array);
			break;
		}
		
		case "timbrar":{
			$idcontrato = $_POST['idcontrato'];
			
			//DATOS DE LA Nomina
			$NominaTipoNomina = $_POST['tiponomina'];
			$NominaFechaInicialPago = $_POST['fechainicial'];
			$NominaFechaFinalPago = $_POST['fechafinal'];
			$NominaDiasPagados = $_POST['diaspagados'];
			$NominaFechaPago = $_POST['fechapago'];
			
			//DATOS DEL CONTRATO y se aprovecha para sacar el nip
			$q1 = "SELECT 	*,
							e.descripcion as departamento,
							u.descripcion as puesto
				   FROM 	pcontrato c
				   INNER JOIN pdireccion d ON c.iddireccion=d.id
				   INNER JOIN cdepartamento e ON c.iddepartamento=e.id
				   INNER JOIN cpuesto u ON c.idpuesto=u.id
				   WHERE 	c.id=".$idcontrato;
			$s1 = $conexion->query($q1);
			$r1 = $s1->fetch_assoc();
			$nip = $r1['nip'];
			$NominaReceptorAntiguedad = $r1['rfc'];
			$NominaReceptorBanco = $r1['idbanco'];
			$NominaReceptorCuenta = $r1['cuentabancaria'];
			$NominaReceptorDepartamento = $r1['departamento'];
			$NominaReceptorEstado = $r1['idestado'];
			$NominaReceptorInicioLab = formateaFecha($r1['fechainiciolab']);
			$NominaReceptorAntiguedad = CalculaAntiguedadSAT($NominaReceptorInicioLab);
			$NominaReceptorPeriodicidad = $r1['idperiodicidadpago'];
			$NominaReceptorPuesto = $r1['puesto'];
			$NominaReceptorRiesgo = $r1['idriesgopuesto'];
			$NominaReceptorSalarioIntegrado = $r1['salariodiario'];
			$NominaReceptorSalariobase = $r1['salariobase'];
			$NominaReceptorSindicalizado = $r1['sindicalizado'];
			$NominaReceptorTipoContrato = $r1['idtipocontrato'];
			$NominaReceptorTipoJornada = $r1['idtipojornada'];
			$NominaReceptorTipoRegimen = $r1['idtiporegimen'];
			
			//Obtenemos Serie y NoCertificado
			$q1 = "SELECT * FROM cgeneral WHERE id='noCertificado'";
			$s1 = $conexion->query($q1);
			$r1 = $s1->fetch_assoc();
			$noCertificado = $r1['valor'];
			
			$q1 = "SELECT * FROM cgeneral WHERE id='serie'";
			$s1 = $conexion->query($q1);
			$r1 = $s1->fetch_assoc();
			$serie = $r1['valor'];
			
			//Moneda
			$Moneda = 'MXN';
			
			//UUID Relacionado
			$RelacionadoUUID = '';
			
			//Datos de Emisor
			$q1 = "SELECT * FROM ppatron WHERE id=1";
			$s1 = $conexion->query($q1);
			$r1 = $s1->fetch_assoc();
			$EmisorRFC = $r1['rfc'];
			$EmisorNombre = $r1['nombre_razsoc'];
			$EmisorCP = $r1['cp'];
			$EmisorRegimen = $r1['idregimenfiscal'];
			$NominaEmisorCurp = $r1['curp'];
			$NominaEmisorRegistro = $r1['registropatronal'];
			
			//Datos del Receptor
			$q1 = "SELECT * FROM pempleado WHERE id=".$nip;
			$s1 = $conexion->query($q1);
			$r1 = $s1->fetch_assoc();
			$ReceptorRFC = $r1['rfc'];
			$ReceptorNombre = $r1['nombre'];
			$NominaReceptorCurp = $r1['curp'];
			$NominaReceptorNSS = $r1['nss'];
			$NominaReceptorNumEmpleado = $nip;	
			
			//Datos de las Percepciones sin contar Horas Extra
			$arrPercepcion = array();
			$q1 = "SELECT 	p.idtipopercepcion as TipoPercepcion,
							p.idtipopercepcion as Clave,
							tp.descripcion as Concepto,
							p.gravado as ImporteGravado,
							p.excento as ImporteExento,
							p.valormercado as ValorMercado,
							p.preciootorgarse as PrecioAlOtorgarse
				   FROM 	ppercepciones p
				   INNER JOIN ctipopercepcion tp ON c.idtipopercepcion=tp.id
				   WHERE 	p.idcontrato=".$idcontrato." 
				   AND 		p.status=1";
			$s1 = $conexion->query($q1);
			while($r1 = $s1->fetch_assoc()){
				$arrPercepcion[] = $r1;
			}
			
			//Datos de las horas extra sin contar Horas Extra
			$arrHorasExtra = array();
			$q1 = "SELECT 	idtipohoras as TipoHoras,
							dias as Clave,
							importepagado as ImportePagado,
							exento as ImporteExento,
							gravado as ImporteGravado
				   FROM 	phorasextra 
				   WHERE 	idcontrato=".$idcontrato." 
				   AND 		status=1";
			$s1 = $conexion->query($q1);
			while($r1 = $s1->fetch_assoc()){
				$arrHorasExtra[] = $r1;
			}
			
			
			//INSERTAMOS EL REGISTRO EN LA TABLA DE TIMBRADO
			$q2 = "INSERT INTO ptimbrado (folio,
										  idcontrato,
										  iddeducciones,
										  idhorasextra,
										  idincapacidades,
										  idjubilaciones,
										  idotrospagos,
										  idpercepciones,
										  idseparaciones,
										  fecha,
										  hora,
										  status)
								VALUES	 (NULL,
										  ".$idcontrato.",
										  ".$iddeducciones.",
										  ".$idhorasextra.",
										  ".$idincapacidades.",
										  ".$idjubilaciones.",
										  ".$idotrospagos.",
										  ".$idpercepciones.",
										  ".$idseparaciones.",
										  NOW(),
										  NOW(),
										  0)";
			$s2 = $conexion->query($q2);
			
			
			$arrayCFDI = array();
			date_default_timezone_set('America/Mexico_City');
			//************  DATOS GENERALES DE LA FACTURA ***********
			$arreglo = array("xmlns:cfdi"=>"http://www.sat.gob.mx/cfd/3",
							 "xmlns:xsi"=>"http://www.w3.org/2001/XMLSchema-instance",
							 "xmlns:nomina12"=>"http://www.sat.gob.mx/nomina12", 
							 "xsi:schemaLocation"=>"http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv32.xsd http://www.sat.gob.mx/nomina12 http://www.sat.gob.mx/informacion_fiscal/factura_electronica/Documents/Complementoscfdi/nomina12.xsd",
							 "Version"=>"3.3",
							 "Serie"=>$serie,
							 "Folio"=>$folio,
							 "Fecha"=>date("Y-m-d")."T".date("h:i:s"),
							 "Sello"=>"",
							 "FormaPago"=>"99",
							 "NoCertificado"=>$noCertificado,
							 "Certificado"=>"",
							 "SubTotal"=>$subtotalCFDI,
							 "Descuento"=>$descuentoCFDI,
							 "Total"=>$totalCFDI,
							 "MetodoPago"=>"PUE",
							 "LugarExpedicion"=>$EmisorCP,
							 "TipoDeComprobante"=>"N",
							 "Moneda"=>$Moneda);
			#************  REEMPLAZO DE CFDI ***********
			if($_POST['chksustituto']==1){
				$arreglo['cfdi:CfdiRelacionados'] = array("TipoRelacion"=>"04");
				$arreglo['cfdi:CfdiRelacionados']['CfdiRelacionado'] = array("UUID"=>$RelacionadoUUID));
			}
			#************  DATOS DEL EMISOR ***********
			$arreglo['cfdi:Emisor'] = array("Rfc"=>$EmisorRFC,
											"Nombre"=>$EmisorNombre,
											"RegimenFiscal"=>$EmisorRegimen);

			#************  DATOS DEL RECEPTOR ***********
			$arreglo['cfdi:Receptor'] = array("Rfc"=>$ReceptorRFC,
											  "Nombre"=>$ReceptorNombre,
											  "UsoCFDI"=>"P01");
			#************  CONCEPTOS DE LA FACTURA ***********
			$arreglo['cfdi:Conceptos'] = array();

			$arreglo['cfdi:Conceptos']['cfdi:Concepto'] = array("ClaveProdServ"=>"84111505",
																"Cantidad"=>"1",
																"ClaveUnidad"=>"ACT",
																"Descripcion"=>"Pago de nómina",
																"ValorUnitario"=>$Subtotal,
																"Importe"=>$Subtotal,
																"Descuento"=>$Descuento);
			#************  IMPUESTOS DE LA FACTURA ***********
			$arreglo['cfdi:Impuestos'] = array();
			
			#************  COMPLEMENTOS ***********
			$arreglo['cfdi:Complemento'] = array();
			
			#************  COMPLEMENTO NOMINA 1.2 ***********
			$arreglo['cfdi:Complemento']['nomina12:Nomina'] = array("Version"=>"1.2",
																	"TipoNomina"=>$NominaTipoNomina,
																	"FechaPago"=>$NominaFechaPago,
																	"FechaInicialPago"=>$NominaFechaInicialPago,
																	"FechaFinalPago"=>$NominaFechaFinalPago,
																	"NumDiasPagados "=>$NominaDiasPagados,
																	"TotalPercepciones "=>$NominaTotalPercepciones,
																	"TotalDeducciones "=>$NominaTotalDeducciones,
																	"TotalOtrosPagos "=>$NominaTotalOtrosPagos);
			
			#************  COMPLEMENTO NOMINA 1.2 [EMISOR] **********
			$arreglo['cfdi:Complemento']['nomina12:Nomina']['nomina12:Emisor'] = array("Curp"=>$NominaEmisorCurp,
																					   "RegistroPatronal"=>$NominaEmisorRegistro,
																					   "RfcPatronOrigen"=>$NominaEmisorRfcOrigen);
			
			/* SOLO APLICA PARA DEPENDENCIAS DE GOBIERNO
			$arreglo['cfdi:Complemento']['nomina12:Nomina']['nomina12:Emisor']['nomina12:EntidadSNCF'] = array("OrigenRecurso"=>$NominaEmisorCurp,
																											   "RegistroPatronal"=>$NominaEmisorRegistro);
			*/
			
			#************  COMPLEMENTO NOMINA 1.2 [RECEPTOR] **********
			$arreglo['cfdi:Complemento']['nomina12:Nomina']['nomina12:Receptor'] = array("Curp"=>$NominaReceptorCurp,
																						 "NumSeguridadSocial"=>$NominaReceptorNSS,
																						 "FechaInicioRelLaboral"=>$NominaReceptorInicioLab,
																						 "Antigüedad"=>$NominaReceptorAntiguedad,
																						 "TipoContrato"=>$NominaReceptorTipoContrato,
																						 "Sindicalizado"=>$NominaReceptorSindicalizado,
																						 "TipoJornada"=>$NominaReceptorTipoJornada,
																						 "TipoRegimen"=>$NominaReceptorTipoRegimen,
																						 "NumEmpleado"=>$NominaReceptorNumEmpleado,
																						 "Departamento"=>$NominaReceptorDepartamento,
																						 "Puesto"=>$NominaReceptorPuesto,
																						 "RiesgoPuesto"=>$NominaReceptorRiesgo,
																						 "PeriodicidadPago"=>$NominaReceptorPeriodicidad,
																						 "Banco"=>$NominaReceptorBanco,
																						 "CuentaBancaria"=>$NominaReceptorCuenta,
																						 "SalarioBaseCotApor"=>$NominaReceptorSalariobase,
																						 "SalarioDiarioIntegrado"=>$NominaReceptorSalarioIntegrado,
																						 "ClaveEntFed"=>$NominaReceptorEstado);
			//QUEDA PENDIENTE LO DE SUBCONTRATACION
			
			#************  COMPLEMENTO NOMINA 1.2 [PERCEPCIONES] **********
			$arreglo['cfdi:Complemento']['nomina12:Nomina']['nomina12:Percepciones'] = array("TotalSueldos"=>$PercepcionesTotalSueldos,
																						     "TotalSeparacionIndemnizacion"=>$PercepcionesTotalSeparacion,
																						     "TotalJubilacionPensionRetiro"=>$PercepcionTotalJubilacion,
																						     "TotalGravado"=>$PercepcionTotalGravado,
																						     "TotalExento"=>$PercepcionTotalExento);
			
			
			$n=0;
			foreach($arrPercepcion as $arrow){
				$arreglo['ARRAY:Percepcion'][$n] = array("TipoPercepcion"=>$arrow['TipoPercepcion'],
														 "Clave"=>$arrow['Clave'],
														 "Concepto"=>$arrow['Concepto'],
														 "ImporteGravado"=>$arrow['ImporteGravado'],
														 "ImporteExento"=>$arrow['ImporteExento']);
														 
				if($arrow['ValorMercado']>0 || $arrow['PrecioAlOtorgarse']>0){
					$arreglo['ARRAY:Percepcion'][$n]['nomina12:AccionesOTitulos'] = array("ValorMercado"=>$arrow['ValorMercado'],
																						  "PrecioAlOtorgarse"=>$arrow['PrecioAlOtorgarse']);
				}
				$n++;
			}
			
			//HORAS EXTRA	
			$n2 = 0;
			$sumImporteGravado = 0;
			$sumImporteExento = 0;
			foreach($arrHorasExtra as $arrow2){
				$arreglo['ARRAY:HorasExtra'][$n2] = array("Dias"=>$arrow2['Dias'],
														  "TipoHoras"=>$arrow2['TipoHoras'],
														  "HorasExtra"=>$arrow2['HorasExtra'],
														  "ImportePagado"=>$arrow2['ImportePagado']);
				$sumImporteGravado = $sumImporteGravado + $arrow2['ImportePagado'];
				$sumImporteExento = $sumImporteExento + $arrow2['ImporteExento'];
				$n2++;
			}
			if($n2>0){
				$proximoItem = count($arreglo['ARRAY:Percepcion']);
				$arreglo['ARRAY:Percepcion'][$proximoItem] = array("TipoPercepcion"=>"019",
																   "Clave"=>"019",
																   "Concepto"=>"Horas extra",
																   "ImporteGravado"=>$sumImporteGravado,
																   "ImporteExento"=>$sumImporteExento);
			}
			
			$n2 = 0;
			$sumImporteGravado = 0;
			$sumImporteExento = 0;
			foreach($arrJubilacion as $arrow){
				$arreglo['ARRAY:JubilacionPensionRetiro'] = array("TotalUnaExhibicion"=>$arrow['TotalUnaExhibicion'],
																  "TotalParcialidad"=>$arrow['TotalParcialidad'],
																  "MontoDiario"=>$arrow['MontoDiario'],
																  "IngresoAcumulable"=>$arrow['IngresoAcumulable'],
																  "IngresoNoAcumulable"=>$arrow['IngresoNoAcumulable']);
			}
			if($n2>0){
				$proximoItem = count($arreglo['ARRAY:Percepcion']);
				$arreglo['ARRAY:Percepcion'][$proximoItem] = array("TipoPercepcion"=>"019",
																   "Clave"=>"019",
																   "Concepto"=>"Horas extra",
																   "ImporteGravado"=>$sumImporteGravado,
																   "ImporteExento"=>$sumImporteExento);
			}
			
			$n2 = 0;
			$sumImporteGravado = 0;
			$sumImporteExento = 0;
			foreach($arrSeparacion as $arrow){
				$arreglo['ARRAY:SeparacionIndemnizacion'] = array("TotalPagado"=>$arrow['TotalPagado'],
																  "NumAñosServicio"=>$arrow['NumAñosServicio'],
																  "UltimoSueldoMensOrd"=>$arrow['UltimoSueldoMensOrd'],
																  "IngresoAcumulable"=>$arrow['IngresoAcumulable'],
																  "IngresoNoAcumulable"=>$arrow['IngresoNoAcumulable']);
			}
			if($n2>0){
				$proximoItem = count($arreglo['ARRAY:Percepcion']);
				$arreglo['ARRAY:Percepcion'][$proximoItem] = array("TipoPercepcion"=>"019",
																   "Clave"=>"019",
																   "Concepto"=>"Horas extra",
																   "ImporteGravado"=>$sumImporteGravado,
																   "ImporteExento"=>$sumImporteExento);
			}
			
			#************  COMPLEMENTO NOMINA 1.2 [DEDUCCIONES] **********
			$arreglo['cfdi:Complemento']['nomina12:Nomina']['nomina12:Deducciones'] = array("TotalOtrasDeducciones"=>$DeduccionesTotalOtrasDeducciones,
																						     "TotalImpuestosRetenidos"=>$DeduccionesTotalImpuestosRetenidos);
			$n=0;
			foreach($arrDeduccion as $arrow){
				$arreglo['ARRAY:Deduccion'][$n] = array("TipoDeduccion"=>$arrow['TipoDeduccion'],
														"Clave"=>$arrow['Clave'],
														"Concepto"=>$arrow['Concepto'],
														"Importe"=>$arrow['Importe']);
				$n++;
			}
			
			#************  COMPLEMENTO NOMINA 1.2 [OTROS PAGOS] **********
			$arreglo['cfdi:Complemento']['nomina12:Nomina']['nomina12:OtrosPagos'] = array();
			$n=0;
			foreach($arrOtroPago as $arrow){
				if($arrow['TipoOtroPago']=='002'){
					$arreglo['ARRAY:OtroPago'][$n] = array("TipoOtroPago"=>$arrow['TipoOtroPago'],
														   "Clave"=>$arrow['Clave'],
														   "Concepto"=>$arrow['Concepto'],
														   "Importe"=>$arrow['Importe']);
															
					if($arrow['SubsidioCausado']>0){
						$arreglo['ARRAY:OtroPago'][$n]['nomina12:SubsidioAlEmpleo'] = array("SubsidioCausado"=>$arrow['SubsidioCausado']);
					}
					$n++;
				}else{
					if($arrow['TipoOtroPago']=='004'){
						$arreglo['ARRAY:OtroPago'][$n] = array("TipoOtroPago"=>$arrow['TipoOtroPago'],
															   "Clave"=>$arrow['Clave'],
															   "Concepto"=>$arrow['Concepto'],
															   "Importe"=>$arrow['Importe']);
																
						if($arrow['SaldoAFavor']>0){
							$arreglo['ARRAY:OtroPago'][$n]['nomina12:CompensacionSaldosAFavor'] = array("SaldoAFavor"=>$arrow['SaldoAFavor'],
																										"Año"=>$arrow['Anio'],
																										"RemanenteSalFav"=>$arrow['RemanenteSalFav']);
						}
						$n++;
					}else{
						$arreglo['ARRAY:OtroPago'][$n] = array("TipoOtroPago"=>$arrow['TipoOtroPago'],
															   "Clave"=>$arrow['Clave'],
															   "Concepto"=>$arrow['Concepto'],
															   "Importe"=>$arrow['Importe']);
						$n++;
					}
				}
			}
			
			#************  COMPLEMENTO NOMINA 1.2 [INCAPACIDADES] **********
			$arreglo['cfdi:Complemento']['nomina12:Nomina']['nomina12:Incapacidades'] = array();
			$n=0;
			foreach($arrIncapacidad as $arrow){
				$arreglo['ARRAY:Incapacidad'][$n] = array("DiasIncapacidad"=>$arrow['TipoDeduccion'],
														  "TipoIncapacidad"=>$arrow['Clave'],
														  "ImporteMonetario"=>$arrow['Concepto']);
				$n++;
			}
			
			$xml = '<?xml version="1.0" encoding="utf-8"?>';
			$xml.= '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:nomina12="http://www.sat.gob.mx/nomina12" xsi:schemaLocation="http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv32.xsd http://www.sat.gob.mx/nomina12 http://www.sat.gob.mx/informacion_fiscal/factura_electronica/Documents/Complementoscfdi/nomina12.xsd" version="3.2" serie="NOM" ';
			$xml.= '	folio="'.$folio.'" fecha="'.$fecha.'" sello="" formaDePago="En una sola exhibición" noCertificado="'.$noCertificado.'" certificado="" subTotal="'.$subtotal.'" descuento="'.$descuento.'" Moneda="MXN" total="'.$total.'" tipoDeComprobante="egreso" metodoDePago="NA" LugarExpedicion="'.$LugarExpedicion.'">';
			$xml.= '	<cfdi:Emisor nombre="'.$EmisorNombre.'" rfc="'.$EmisorRFC.'">';
			$xml.= '		<cfdi:RegimenFiscal Regimen="'.$EmisorRegimen.'"/>';
			$xml.= '	</cfdi:Emisor>';
			$xml.= '	<cfdi:Receptor rfc="'.$ReceptorRFC.'" nombre="'.$ReceptorNombre.'">';
			$xml.= '	</cfdi:Receptor>';
			$xml.= '	<cfdi:Conceptos>';
			$xml.= '		<cfdi:Concepto cantidad="1" unidad="ACT" descripcion="Pago de nómina" valorUnitario="'.$subtotal.'" importe="'.$subtotal.'"/>';
			$xml.= '	</cfdi:Conceptos>';
			$xml.= '	<cfdi:Impuestos />';
			$xml.= '	<cfdi:Complemento>';
			if($NominaTotalDeducciones>0 && $NominaTotalOtrosPagos>0){
				$xml.= '		<nomina12:Nomina Version="1.2" TipoNomina="'.$NominaTipoNomina.'" FechaPago="'.$NominaFechaPago.'" FechaInicialPago="'.$NominaFechaInicialPago.'" FechaFinalPago="'.$NominaFechaFinalPago.'" NumDiasPagados="'.$NominaDiasPagados.'" TotalPercepciones="'.$NominaTotalPercepciones.'" TotalDeducciones="'.$NominaTotalDeducciones.'" TotalOtrosPagos="'.$NominaTotalOtrosPagos.'">';
			}else{
				if($NominaTotalDeducciones>0){
					$xml.= '		<nomina12:Nomina Version="1.2" TipoNomina="'.$NominaTipoNomina.'" FechaPago="'.$NominaFechaPago.'" FechaInicialPago="'.$NominaFechaInicialPago.'" FechaFinalPago="'.$NominaFechaFinalPago.'" NumDiasPagados="'.$NominaDiasPagados.'" TotalPercepciones="'.$NominaTotalPercepciones.'" TotalDeducciones="'.$NominaTotalDeducciones.'">';
				}else{
					if($NominaTotalOtrosPagos>0){
						$xml.= '		<nomina12:Nomina Version="1.2" TipoNomina="'.$NominaTipoNomina.'" FechaPago="'.$NominaFechaPago.'" FechaInicialPago="'.$NominaFechaInicialPago.'" FechaFinalPago="'.$NominaFechaFinalPago.'" NumDiasPagados="'.$NominaDiasPagados.'" TotalPercepciones="'.$NominaTotalPercepciones.'" TotalOtrosPagos="'.$NominaTotalOtrosPagos.'">';
					}else{
						$xml.= '		<nomina12:Nomina Version="1.2" TipoNomina="'.$NominaTipoNomina.'" FechaPago="'.$NominaFechaPago.'" FechaInicialPago="'.$NominaFechaInicialPago.'" FechaFinalPago="'.$NominaFechaFinalPago.'" NumDiasPagados="'.$NominaDiasPagados.'" TotalPercepciones="'.$NominaTotalPercepciones.'">';
					}
				}
			}
			if($NominaEmisorRfcOrigen!='')
				$xml.= '			<nomina12:Emisor Curp="'.$NominaEmisorCurp.'" RegistroPatronal="'.$NominaEmisorRegistro.'" RfcPatronOrigen="'.$NominaEmisorRfcOrigen.'">';
			else
				$xml.= '			<nomina12:Emisor Curp="'.$NominaEmisorCurp.'" RegistroPatronal="'.$NominaEmisorRegistro.'">';
			//$xml.= '				<nomina12:EntidadSNCF OrigenRecurso="" MontoRecursoPropio=""/>';
			$xml.= '			</nomina12:Emisor>';
			if($NominaReceptorBanco!='' && $$NominaReceptorCuenta!=''){
				$xml.= '			<nomina12:Receptor Curp="'.$NominaReceptorCurp.'" NumSeguridadSocial="'.$NominaReceptorNSS.'" FechaInicioRelLaboral="'.$NominaReceptorInicioLab.'" Antigüedad="'.$NominaReceptorAntiguedad.'" TipoContrato="'.$NominaReceptorTipoContrato.'" Sindicalizado="'.$NominaReceptorSindicalizado.'" TipoJornada="'.$NominaReceptorTipoJornada.'" TipoRegimen="'.$NominaReceptorTipoRegimen.'" NumEmpleado="'.$NominaReceptorNumEmpleado.'" Departamento="'.$NominaReceptorDepartamento.'" Puesto="'.$NominaReceptorPuesto.'" RiesgoPuesto="'.$NominaReceptorRiesgo.'" PeriodicidadPago="'.$NominaReceptorPeriodicidad.'" Banco="'.$NominaReceptorBanco.'" CuentaBancaria="'.$NominaReceptorCuenta.'" SalarioBaseCotApor="'.$NominaReceptorSalariobase.'" SalarioDiarioIntegrado="'.$NominaReceptorSalarioIntegrado.'" ClaveEntFed="'.$NominaReceptorEstado.'">';
			}else{
				$xml.= '			<nomina12:Receptor Curp="'.$NominaReceptorCurp.'" NumSeguridadSocial="'.$NominaReceptorCurp.'" FechaInicioRelLaboral="'.$NominaReceptorCurp.'" Antigüedad="'.$NominaReceptorCurp.'" TipoContrato="'.$NominaReceptorCurp.'" Sindicalizado="'.$NominaReceptorCurp.'" TipoJornada="'.$NominaReceptorCurp.'" TipoRegimen="'.$NominaReceptorCurp.'" NumEmpleado="'.$NominaReceptorCurp.'" Departamento="'.$NominaReceptorCurp.'" Puesto="'.$NominaReceptorCurp.'" RiesgoPuesto="'.$NominaReceptorCurp.'" PeriodicidadPago="'.$NominaReceptorCurp.'" SalarioBaseCotApor="'.$NominaReceptorCurp.'" SalarioDiarioIntegrado="'.$NominaReceptorCurp.'" ClaveEntFed="'.$NominaReceptorCurp.'">';
			}
				
			//$xml.= '			<nomina12:SubContratacion RfcLabora="" PorcentajeTiempo=""/>';
			$xml.= '			</nomina12:Receptor>';
			$xml.= '			<nomina12:Percepciones TotalSueldos="'.$TotalSueldos.'" TotalSeparacionIndemnizacion="'.$TotalSeparacionIndemnizacion.'" TotalJubilacionPensionRetiro="'.$TotalJubilacionPensionRetiro.'" TotalGravado="'.$TotalGravado.'" TotalExento="'.$TotalGravado.'">';
			
			foreach($arrPercepcion as $row){
				$xml.= '				<nomina12:Percepcion TipoPercepcion="'.$row['TipoPercepcion'].'" Clave="'.$row['Clave'].'" Concepto="'.$row['Concepto'].'" ImporteGravado="'.$row['ImporteGravado'].'" ImporteExento="'.$row['ImporteExento'].'">';
				if($row['ValorMercado']>0 && $row['PrecioAlOtorgarse']>0)
					$xml.= '					<nomina12:AccionesOTitulos ValorMercado="" PrecioAlOtorgarse=""/>';
				
				$xml.= '					<nomina12:HorasExtra Dias="" TipoHoras="" HorasExtra="" ImportePagado=""/>';
				$xml.= '				</nomina12:Percepcion>';
			}
			
			if(count($arrHorasExtra)>0){
				$xml.= '				<nomina12:Percepcion TipoPercepcion="019" Clave="019" Concepto="Horas Extra" ImporteGravado="'.$sumImporteGravado.'" ImporteExento="'.$sumImporteExento.'">';
				$xml.= '					<nomina12:HorasExtra Dias="" TipoHoras="" HorasExtra="" ImportePagado=""/>';
				$xml.= '				</nomina12:Percepcion>';
			}
			
			foreach($arrJubilacion as $row){
				$xml.= '				<nomina12:JubilacionPensionRetiro TotalUnaExhibicion="'.$row['unaexhibicion'].'" TotalParcialidad="'.$row['parcialidades'].'" MontoDiario="'.$row['diario'].'" IngresoAcumulable="'.$row['acumulable'].'" IngresoNoAcumulable="'.$row['noacumulable'].'"/>';
			}
			foreach($arrSeparacion as $row){
				$xml.= '				<nomina12:SeparacionIndemnizacion TotalPagado="'.$row['pagado'].'" NumAñosServicio="'.$row['anio'].'" UltimoSueldoMensOrd="'.$row['sueldo'].'" IngresoAcumulable="'.$row['acumulable'].'" IngresoNoAcumulable="'.$row['noacumulable'].'"/>';
			}
			$xml.= '			</nomina12:Percepciones>';
			$xml.= '			<nomina12:Deducciones TotalOtrasDeducciones="" TotalImpuestosRetenidos="">';
			$xml.= '				<nomina12:Deduccion TipoDeduccion="" Clave="" Concepto="" Importe=""/>';
			$xml.= '			</nomina12:Deducciones>';
			$xml.= '			<nomina12:OtrosPagos>';
			$xml.= '				<nomina12:OtroPago TipoOtroPago="" Clave="" Concepto="" Importe="">';
			$xml.= '					<nomina12:SubsidioAlEmpleo SubsidioCausado=""/>';
			$xml.= '					<nomina12:CompensacionSaldosAFavor SaldoAFavor="" Año="" RemanenteSalFav=""/>';
			$xml.= '				</nomina12:OtroPago>';
			$xml.= '			</nomina12:OtrosPagos>';
			$xml.= '			<nomina12:Incapacidades>';
			$xml.= '				<nomina12:Incapacidad DiasIncapacidad="" TipoIncapacidad="" ImporteMonetario=""/>';
			$xml.= '			</nomina12:Incapacidades>';
			$xml.= '		</nomina12:Nomina>';
			$xml.= '	</cfdi:Complemento>';
			$xml.= '</cfdi:Comprobante>';
			break;
		}
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

function formateaFecha($fecha){
	$arr = explode("-",$fecha);
	$fechaNueva = $arr[2]."-".$arr[1]."-".$arr[0];
	return $fechaNueva;	
}

?>