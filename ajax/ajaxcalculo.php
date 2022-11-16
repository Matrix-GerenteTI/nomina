<?php

	if(!isset($_SESSION))
		session_start();
	require_once("mysql.php");
	//conexion();
	$op = $_GET['op'];
	switch($op){
		case "lista":{
			$udn = $_POST['udn'];
			$departamento = $_POST['departamento'];
			$puesto = $_POST['puesto'];
			$empleado = $_POST['empleado'];
			$_POST['fecIni']!=''?$fecini = $_POST['fecIni']:$fecini = date("d/m/Y");
			$_POST['fecFin']!=''?$fecfin = $_POST['fecFin']:$fecfin = date("d/m/Y");
            $diasLab = calculaDias($fecini,$fecfin);
			$nomina = array();
			$n = 0;

            $idcontrato = 0;
            $nip = 0;
            //:::::::::::::::::::::::::::::>> SACAMOS TODAS LAS PERCEPCIONES Y DEDUCCIONES
            $empleadosArr = array();
			//Datos del Receptor
			$q1 = "SELECT   e.*,
                            s.id as idudn,
                            s.descripcion as udn, 
                            d.id as iddepartamento,
                            d.descripcion as departamento,
                            p.id as idpuesto,
                            p.descripcion as puesto,
                            e.nombre as empleado,
                            c.id as idcontrato,
                            c.sueldobruto as sueldo,
                            c.idbanco as idbanco,
                            IFNULL((SELECT descripcion FROM cbanco WHERE id=c.idbanco),'') as banco,
                            c.cuentabancaria as cuenta,
                            fechainiciolab as fechainiciolab
                   FROM     pempleado e
                   INNER JOIN csucursal s ON e.idsucursal=s.id
                   INNER JOIN pcontrato c ON e.nip=c.nip
                   INNER JOIN cdepartamento d ON c.iddepartamento=d.id
                   INNER JOIN cpuesto p ON c.idpuesto=p.id
                   WHERE    e.status=1 
                   AND      s.id LIKE '".$udn."'
                   AND      d.id LIKE '".$departamento."'
                   AND      p.id LIKE '".$puesto."'
                   AND      e.nip LIKE '".$empleado."'
                   ORDER BY e.nombre";
			$s1 = $conexion->query($q1);
			while($r1 = $s1->fetch_assoc()){
                $idcontrato = $r1['idcontrato'];
                $nip = $r1['nip'];
                $fecinilab = date('d/m/Y', strtotime($r1['fechainiciolab']));
                $difDias = calculaDias($fecinilab,$fecini);
                $difDias2 = calculaDias($fecinilab,$fecfin);
                //echo $r1['empleado'].'_'.$fecinilab.'_'.$difDias.'\n';
                if($difDias<0){
                    $sueldo = abs($difDias2) * $r1['sueldo'];
                }else{
                    $sueldo = $diasLab * $r1['sueldo'];
                }

                if(!in_array($r1['nip'],$empleadosArr)){
                    $nomina[$r1['nip']] = array('nip'=>$r1['nip'],
                                                'idudn'=>$r1['idudn'],
                                                'udn'=>$r1['udn'],
                                                'iddepartamento'=>$r1['iddepartamento'],
                                                'departamento'=>$r1['departamento'],
                                                'idpuesto'=>$r1['idpuesto'],
                                                'puesto'=>$r1['puesto'],
                                                'empleado'=>$r1['empleado'],
                                                'idbanco'=>$r1['idbanco'],
                                                'banco'=>$r1['banco'],
                                                'cuenta'=>$r1['cuenta'],
                                                'fechainiciolab'=>$r1['fechainiciolab'],
                                                'percepciones'=>array(),
                                                'deducciones'=>array(),
                                                'bancos'=>0,
                                                'efectivo'=>0,
                                                'vales'=>0,
                                                'otros'=>0,
                                                'totalpercepciones'=>0,
                                                'totaldeducciones'=>0,
                                                'total'=>0);

                    $qLastNomina = "SELECT * FROM pnomina WHERE status=1 AND idempleado='".$r1['nip']."' ORDER BY id DESC LIMIT 1";
                    $sLastNomina = $conexion->query($qLastNomina);
                    while($rLastNomina = $sLastNomina->fetch_assoc()){
                        $nomina[$r1['nip']]['bancos'] = $rLastNomina['pagobancos'];
                        $nomina[$r1['nip']]['efectivo'] = $rLastNomina['pagoefectivo'];
                        $nomina[$r1['nip']]['vales'] = $rLastNomina['pagovales'];
                        $nomina[$r1['nip']]['otros'] = $rLastNomina['pagootros'];
                    }
                }

                $NominaReceptorNumEmpleado = $r1['nip'];	

                //Datos de las Percepciones sin contar Horas Extra
                //Actualizamos la percepcion de sueldos
                $conexion->query("DELETE FROM ppercepciones WHERE idtipopercepcion='001' AND idcontrato='".$idcontrato."'");
                $conexion->query("INSERT INTO ppercepciones (idtipopercepcion,gravado,excento,valormercado,preciootorgarse,status,idcontrato) VALUES ('001','".number_format($sueldo,2,'.','')."',0,0,0,1,".$idcontrato.")"); 
                $TotalImporteGravado = 0;
                $TotalImporteExento = 0;
                $TotalSeparacionIndemnizacion = 0;
                $TotalJubilacionPensionRetiro = 0;
                $TotalSueldos = 0;
                $arrPercepcion = array();
                $q9 = "SELECT 	p.idtipopercepcion as TipoPercepcion,
                                p.idtipopercepcion as Clave,
                                tp.descripcion as Concepto,
                                p.gravado as ImporteGravado,
                                p.excento as ImporteExento,
                                p.valormercado as ValorMercado,
                                p.preciootorgarse as PrecioAlOtorgarse,
                                'percepcion' as tipo,
                                tp.descripcion as incidencia,
                                '' as fecha,
                                (p.gravado + p.excento) as monto,
                                ".$_SESSION['permisos'][10]['guardar']." as permiso
                    FROM 	ppercepciones p
                    INNER JOIN ctipopercepcion tp ON p.idtipopercepcion=tp.id
                    WHERE 	p.idcontrato=".$idcontrato." 
                    AND 		p.status=1";
                $s9 = $conexion->query($q9);
                while($r9 = $s9->fetch_assoc()){
                    $nomina[$r1['nip']]['percepciones'][] = $r9;
                    $arrPercepcion[] = $r9;
                    if($r9['TipoPercepcion']!='022' && $r9['TipoPercepcion']!='023' && $r9['TipoPercepcion']!='025' && $r9['TipoPercepcion']!='039' && $r9['TipoPercepcion']!='044'){
                        $TotalSueldos = $TotalSueldos + $r9['ImporteGravado'] + $r9['ImporteExento'];
                    }
                    if($r9['TipoPercepcion']=='022' && $r9['TipoPercepcion']=='023' && $r9['TipoPercepcion']=='025'){
                        $TotalSeparacionIndemnizacion = $TotalSeparacionIndemnizacion + $r9['ImporteGravado'] + $r9['ImporteExento'];
                    }
                    if($r9['TipoPercepcion']=='039' && $r9['TipoPercepcion']=='044'){
                        $TotalJubilacionPensionRetiro = $TotalJubilacionPensionRetiro + $r9['ImporteGravado'] + $r9['ImporteExento'];
                    }
                    $TotalImporteGravado = $TotalImporteGravado + $r9['ImporteGravado'];
                    $TotalImporteExento = $TotalImporteExento + $r9['ImporteExento'];
                }
                
                $sumImporteGravado = 0;
                $sumImporteExento = 0;
                //Datos de las horas extra sin contar Horas Extra
                $arrHorasExtra = array();
                $q2 = "SELECT 	idtipohoras as TipoHoras,
                                dias as Dias,
                                horasextra as HorasExtra,
                                importepagado as ImportePagado,
                                exento as ImporteExento,
                                gravado as ImporteGravado,
                                'percepcion' as tipo,
                                'Horas Extras' as incidencia,
                                '' as fecha,
                                (gravado + exento) as monto,
                                ".$_SESSION['permisos'][10]['guardar']." as permiso
                    FROM 	phorasextra 
                    WHERE 	idcontrato=".$idcontrato." 
                    AND 		status=1";
                $s2 = $conexion->query($q2);
                while($r2 = $s2->fetch_assoc()){
                    $nomina[$r1['nip']]['percepciones'][] = $r2;
                    $arrHorasExtra[] = $r2;
                    $sumImporteGravado = $sumImporteGravado + $r2['ImporteGravado'];
                    $sumImporteExento =  $sumImporteExento + $r2['ImporteExento'];
                }
                
                $TotalImporteExento = $TotalImporteExento + $sumImporteExento;
                $TotalImporteGravado = $TotalImporteGravado + $sumImporteGravado;
                $TotalSueldos = $TotalSueldos + $sumImporteExento + $sumImporteGravado;
                
                //DEDUCCIONES
                $TotalOtrasDeducciones = 0;
                $TotalImpuestosRetenidos = 0;
                $arrDeducciones = array();
                $q3 = "SELECT 	d.idtipodeduccion as TipoDeduccion,
                                d.idtipodeduccion as Clave,
                                td.descripcion as Concepto,
                                d.importe as Importe,
                                'deduccion' as tipo,
                                td.descripcion as incidencia,
                                d.fechaCargo as fecha,
                                d.importe as monto,
                                ".$_SESSION['permisos'][10]['guardar']." as permiso
                    FROM 	pdeducciones d
                    INNER JOIN ctipodeduccion td ON d.idtipodeduccion=td.id
                    WHERE 	d.idcontrato=".$idcontrato." 
                    AND 		d.status=1 
                    AND     d.fechaCargo>='".formateaFechaSLASH($fecini)."' AND d.fechaCargo<='".formateaFechaSLASH($fecfin)."'";
                $s3 = $conexion->query($q3);
                while($r3 = $s3->fetch_assoc()){
                    $nomina[$r1['nip']]['deducciones'][] = $r3;
                    $arrDeducciones[] = $r3;
                    if($r3['TipoDeduccion']=='002'){
                        $TotalImpuestosRetenidos = $TotalImpuestosRetenidos + $r3['Importe'];
                    }else{
                        $TotalOtrasDeducciones = $TotalOtrasDeducciones + $r3['Importe'];
                    }
                }
                
                //OTROS PAGOS
                $TotalOtrosPagos = 0;
                $arrOtrosPagos = array();
                $q5 = "SELECT 	d.idtipootropago as TipoOtroPago,
                                d.idtipootropago as Clave,
                                td.descripcion as Concepto,
                                d.importe as Importe,
                                d.saldofavor as SaldoAFavor,
                                d.anio as Anio,
                                d.remanente as RemanenteSalFav,
                                d.subsidiocausado as SubsidioCausado,
                                'percepcion' as tipo,
                                td.descripcion as incidencia,
                                '' as fecha,
                                d.importe as monto,
                                ".$_SESSION['permisos'][10]['guardar']." as permiso
                    FROM 	potrospagos d
                    INNER JOIN ctipootropago td ON d.idtipootropago=td.id
                    WHERE 	d.idcontrato=".$idcontrato." 
                    AND 		d.status=1";
                $s5 = $conexion->query($q5);
                while($r5 = $s5->fetch_assoc()){
                    $nomina[$r1['nip']]['percepciones'][] = $r5;
                    $arrOtrosPagos[] = $r5;
                    $TotalOtrosPagos = $TotalOtrosPagos + $r5['Importe'];
                }
                
                //JUBILACION
                $arrJubilacion = array();
                $q6 = "SELECT 	*,
                                p.descripcion as Concepto,
                                j.idtipopercepcion as Clave,
                                j.idtipopercepcion as TipoPercepcion,
                                j.gravado as ImporteGravado,
                                j.exento as ImporteExento,
                                'percepcion' as tipo,
                                p.descripcion as incidencia,
                                '' as fecha,
                                (j.gravado + j.exento) as monto,
                                ".$_SESSION['permisos'][10]['guardar']." as permiso
                    FROM 	pjubilaciones j
                    INNER JOIN ctipopercepcion p ON j.idtipopercepcion=p.id
                    WHERE 	j.idcontrato=".$idcontrato." 
                    AND 		j.status=1";
                $s6 = $conexion->query($q6);
                while($r6 = $s6->fetch_assoc()){
                    $nomina[$r1['nip']]['percepciones'][] = $r6;
                    $arrJubilacion[] = $r6;
                }
                
                //SEPARACION
                $arrSeparacion = array();
                $q7 = "SELECT 	*,
                                p.descripcion as Concepto,
                                s.idtipopercepcion as TipoPercepcion,
                                s.idtipopercepcion as Clave,
                                s.gravado as ImporteGravado,
                                s.exento as ImporteExento,
                                'percepcion' as tipo,
                                p.descripcion as incidencia,
                                '' as fecha,
                                (s.gravado + s.exento) as monto,
                                ".$_SESSION['permisos'][10]['guardar']." as permiso
                    FROM 	pseparaciones s
                    INNER JOIN ctipopercepcion p ON s.idtipopercepcion=p.id
                    WHERE 	s.idcontrato=".$idcontrato." 
                    AND 		s.status=1";
                $s7 = $conexion->query($q7);
                while($r7 = $s7->fetch_assoc()){
                    $nomina[$r1['nip']]['percepciones'][] = $r7;
                    $arrSeparacion[] = $r7;
                }
                
                //INCAPACIDADES
                $arrIncapacidades = array();
                $q8 = "SELECT 	d.idtipoincapacidad as TipoIncapacidad,
                                d.dias as Dias,
                                d.importe as Importe,
                                'percepcion' as tipo,
                                td.descripcion as incidencia,
                                '' as fecha,
                                d.importe as monto,
                                ".$_SESSION['permisos'][10]['guardar']." as permiso
                    FROM 	pincapacidades d
                    INNER JOIN ctipoincapacidad td ON d.idtipoincapacidad=td.id
                    WHERE 	d.idcontrato=".$idcontrato." 
                    AND 		d.status=1";
                $s8 = $conexion->query($q8);
                while($r8 = $s8->fetch_assoc()){
                    $nomina[$r1['nip']]['percepciones'][] = $r8;
                    $arrIncapacidades[] = $r8;
                }
                
                $TPercepciones = $TotalSueldos + $TotalJubilacionPensionRetiro + $TotalSeparacionIndemnizacion;
                $subtotal = $TPercepciones + $TotalOtrosPagos;
                $descuento = $TotalImpuestosRetenidos + $TotalOtrasDeducciones + $TotalAsistencia;
                $total = $subtotal - $descuento;
                $NominaTotalDeducciones = $descuento;
                $NominaTotalPercepciones = $TPercepciones + $TotalOtrosPagos;
                //$NominaTotalOtrosPagos = $TotalOtrosPagos;
                
                $NominaTotal = $NominaTotalPercepciones - number_format($NominaTotalDeducciones,2,'.','');
                $nomina[$r1['nip']]['totalpercepciones'] = number_format($NominaTotalPercepciones,2,'.','');
                $nomina[$r1['nip']]['totaldeducciones'] = number_format($NominaTotalDeducciones,2,'.','');
                $nomina[$r1['nip']]['total'] = number_format($NominaTotal,2,'.','');
            }
            //:::::::::::::::::::::::::::::<<< FINALIZA LAS PERCEPCIONES Y DEDUCCIONES
			
			echo json_encode($nomina);
			break;
		}

        case 'siExiste':{    
			$_POST['fecIni']!=''?$fecini = $_POST['fecIni']:$fecini = date("d/m/Y");
			$_POST['fecFin']!=''?$fecfin = $_POST['fecFin']:$fecfin = date("d/m/Y");        
            $n=0;
            $qchk = "SELECT * FROM pnomina WHERE fechainicial='".formateaFechaSLASH($fecini)."' AND fechafinal='".formateaFechaSLASH($fecfin)."'";
            $schk = $conexion->query($qchk);
            while($rchk = $schk->fetch_assoc()){   
                $n++;
            }
            echo $n;
            break;
        }

        case 'guardaNomina':{
			$_POST['fecIni']!=''?$fecini = $_POST['fecIni']:$fecini = date("d/m/Y");
			$_POST['fecFin']!=''?$fecfin = $_POST['fecFin']:$fecfin = date("d/m/Y");
            
            $qchk = "SELECT * FROM pnomina WHERE fechainicial='".formateaFechaSLASH($fecini)."' AND fechafinal='".formateaFechaSLASH($fecfin)."'";
            $schk = $conexion->query($qchk);
            while($rchk = $schk->fetch_assoc()){ 
                $qdel2 = "DELETE FROM pnomina_incidencias WHERE idpnomina='".$rchk['id']."'";
                $sdel2 = $conexion->query($qdel2);
            }
            
            $qdel = "DELETE FROM pnomina WHERE fechainicial='".formateaFechaSLASH($fecini)."' AND fechafinal='".formateaFechaSLASH($fecfin)."'";
            $sdel = $conexion->query($qdel);

            $listadoNomina = json_decode($_POST['data'],true);
            foreach($listadoNomina as $row){
                $q1 = "INSERT INTO pnomina (idempleado,
                                            totalpercepciones,
                                            totaldeducciones,
                                            pagobancos,
                                            pagoefectivo,
                                            pagovales,
                                            pagootros,
                                            total,
                                            fechainicial,
                                            fechafinal,
                                            usuario) 
                                VALUES     (".$row['nip'].",
                                            ".$row['totalpercepciones'].",
                                            ".$row['totaldeducciones'].",
                                            ".$row['bancos'].",
                                            ".$row['efectivo'].",
                                            ".$row['vales'].",
                                            ".$row['otros'].",
                                            ".$row['total'].",
                                            '".formateaFechaSLASH($fecini)."',
                                            '".formateaFechaSLASH($fecfin)."',
                                            '".$_SESSION['userid']."')";
                $s1 = $conexion->query($q1);
                $idpnomina = $conexion->insert_id;
                if($s1){
                    foreach($row['percepciones'] as $empPer){
                        $q2 = "INSERT INTO pnomina_incidencias (idpnomina,idincidencia,tipoincidencia,monto) VALUES (".$idpnomina.",'".$empPer['Clave']."','".$empPer['tipo']."',".$empPer['monto'].")";
                        $s2 = $conexion->query($q2);
                    }
                    foreach($row['deducciones'] as $empDed){
                        $q3 = "INSERT INTO pnomina_incidencias (idpnomina,idincidencia,tipoincidencia,monto) VALUES (".$idpnomina.",'".$empDed['Clave']."','".$empDed['tipo']."',".$empDed['monto'].")";
                        $s3 = $conexion->query($q3);
                    }
                }
            }
            echo $idpnomina;
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