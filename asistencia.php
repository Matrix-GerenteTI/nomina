<?php
	require_once("ajax/control.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Nómina Matrix</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<link href="css/bootstrapdos.min.css" rel="stylesheet">
<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600"
        rel="stylesheet">
<link href="css/font-awesome.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/pages/dashboard.css" rel="stylesheet">
<link href="css/bootstrapdos.css" rel="stylesheet">
<link href="css/bootstrap-clockpicker.min.css" rel="stylesheet">

<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>


	<div class="modal" tabindex="-1" role="dialog" id="modalControlAsistencia">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="tituloControl"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
							<table class="table table-striped table-bordered" style="width:50%; margin: 0 auto;">
									<tr>
										<th>Fecha</th>
										<th>Hora</th>
										<th style="display:none" id="headerMontoIncidencia" >Descuento</th>							
										<th style="display:none" id="headerEliminaIncidencia">Quitar</th>							
									</tr>
									<tbody id="logAsistencia">
										
									</tbody>
							</table>
							<div class="container-fluid" style="display:none" id="displaySetIngresoXFalta">
								<div class="row">
									<div class="col-sm-10 col-offset-1">
										<b>*Nota: </b>
										<p>Se requiere de una hora de ingreso estimada para proceder a cancelar la falta del empleado, si se elige una hora superior a la hora de ingreso del trabajador se le aplicará la sanción correspondiente</p>
										<div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true">
											<input type="text" class="form-control"  placeholder="Ingresa una hora" id="horaIngresoxFalta">
											<span class="input-group-addon">
												<span class="icon-clock"></span>
											</span>
										</div>			
										<input type="hidden"  id="nipIngresoXFalta">					
									</div>
								</div>		
								<div class="row">
									<div class="col-sm-10 col-offset-1">
										<button class="btn btn-success" id="btnSetAsistenciaDeFalta">Confirmar</button>
									</div>
								</div>					
							</div>

						</div>
					</div>					
				</div>
			</div>
			<div class="modal-footer">
					
					<button class="btn btn-primary" id="btnAplicaCambiosMontos" style="float: right">Actualizar Deducciones</button>
					<button class="btn btn-default"  id="btnCerrarControl" data-dismiss="modal" style="float: right">Cerrar</button>
			</div>
			</div>
		</div>
	</div>

	<!-- <div id="modalControlAsistencia" style="display:none;width:100%;height:100%; background:rgba(0,0,0,0.4);z-index:1;position:absolute;background-size: cover;">
			<div class="widget">
				<div class="widget-content span8" style="margin-left:25%; margin-right:25%;margin-top:10%;">
						<div style="width:50%; margin: 0 auto;">
									<h4 id="tituloControl"></h4>
									<br>
						</div>

						<button class="btn btn-primary" id="btnAplicaRetardos" style="float: right">Aplicar Cargos por Retardos</button>
						<button class="btn btn-primary" style="float: right" id="btnAplicaFaltas">Aplicar Cargos por Falras</button>
						<button class="btn btn-primary" id="btnCerrarControl" style="float:right">Cerrar</button>
				</div>

			</div>
	</div> -->
<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container"> <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"><span
                    class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span> </a><a class="brand" href="index.html"><i class="icon-book"></i>&nbsp;Nomina Matrix </a>
      <div class="nav-collapse">
        <ul class="nav pull-right">
          <!--<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i
                            class="icon-cog"></i> Account <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="javascript:;">Settings</a></li>
              <li><a href="javascript:;">Help</a></li>
            </ul>
          </li>-->
          <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i
                            class="icon-user"></i> <?php echo $_SESSION['username']; ?> <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <!--<li><a href="javascript:;">Profile</a></li>-->
              <li><a href="ajax/control.php?closeSesion=y">Cerrar sesi&oacute;n</a></li>
            </ul>
          </li>
        </ul>
        <!--<form class="navbar-search pull-right">
          <input type="text" class="search-query" placeholder="Search">
        </form>-->
      </div>
      <!--/.nav-collapse --> 
    </div>
    <!-- /container --> 
  </div>
  <!-- /navbar-inner --> 
</div>
<!-- /navbar -->
<div class="subnavbar">
  <div class="subnavbar-inner">
    <div class="container">
      <ul class="mainnav">
   	<?php
   		if($_SESSION['usertype']!='ADMINISTRADOR'){
   	?>
        <li><a href="index.php"><i class="icon-flag"></i><span>Reto 7SM</span> </a> </li>
		<li><a href="suscripcion.php"><i class="icon-money"></i><span>Donaci&oacute;n</span> </a> </li>
		<li><a href="reportes.php"><i class="icon-archive"></i><span>Reportes</span> </a> </li>
	<?php
        }else{
    ?>
		<li><a href="index.php"><i class="icon-ok"></i><span>Timbrar</span> </a> </li>
		<li><a href="empleados.php"><i class="icon-group"></i><span>Empleados</span> </a></li>
		<li ><a href="socioeconomicos.php"><i class="icon-book"></i><span>Socioecónomicos</span></a></li>
		<li ><a href="vacaciones.php"> <i class="icon-plane"></i><span>Vacaciones</span></a></li>
    	<li><a href="incidencias.php"><i class="icon-calculator"></i><span>Incidencias</span> </a> </li>
		<li><a href="empresa.php"><i class="icon-building"></i><span>Empresa</span> </a> </li>
        <li><a href="cfdis.php"><i class="icon-list-alt"></i><span>CFDIs</span> </a> </li>
		<li class="active"><a href="asistencia.php"><i class="icon-file-clock-o"></i><span>Asistencias</span> </a> </li>
		<li><a href="parametros.php"><i class="icon-fa-cogs"></i><span>Parametros</span> </a> </li>
		<li><a href="usuarios.php"><i class="icon-user"></i><span>Usuarios</span> </a> </li>
		<li><a href="reportes.php"><i class="icon-archive"></i><span>Reportes</span> </a> </li>
    <?php
		}
	?>
        <!--<li class="dropdown"><a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-long-arrow-down"></i><span>Drops</span> <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="icons.html">Icons</a></li>
            <li><a href="faq.html">FAQ</a></li>
            <li><a href="pricing.html">Pricing Plans</a></li>
            <li><a href="login.html">Login</a></li>
            <li><a href="signup.html">Signup</a></li>
            <li><a href="error.html">404</a></li>
          </ul>
        </li>-->
      </ul>
    </div>
    <!-- /container --> 
  </div>
  <!-- /subnavbar-inner --> 
</div>
<!-- /subnavbar -->
<div class="main">
  <div class="main-inner">
    <div class="container">
      <div class="row">
        <div class="span12">
          <div class="widget">
          	<div class="form-actions" style="margin-top:0px; margin-bottom:0px; padding:5px">
            	<table width="100%" cellpadding="0" cellspacing="0">
                	<tr>
						<td>
							Sucursal<br/>
							<select id="bussucursal" style="width:92%">
							</select>
						</td>
						<td>
							Departamento<br/>
							<select id="busdepartamento" style="width:92%">
							</select>
						</td>
						<td>
							Puesto<br/>
							<select id="buspuesto" style="width:92%">
							</select>
						</td>
                    	<td align="right">
							<table cellpadding="0" cellspacing="0" align="right">
								<tr>
									<td>
										<div id="botones" class="shortcuts"> 
											<a href="javascript:lista(0,15);" class="shortcut"><i class="shortcut-icon icon-search"></i><span class="shortcut-label">Buscar</span></a>&nbsp;
											<a href="javascript:imprimir();" class="shortcut"><i class="shortcut-icon icon-print"></i><span class="shortcut-label">Imprimir</span></a>&nbsp;
											<a href="javascript:descargar();" class="shortcut"><i class="shortcut-icon icon-download"></i><span class="shortcut-label">Exportar</span></a>
											<a href="javascript:prenomina();" class="shortcut"><i class="shortcut-icon icon-upload"></i><span class="shortcut-label">Prenomina</span></a>
										</div>
									</td>
								</tr>
							</table>
                      	</td>
                   	</tr>
              	</table>
				<table width="100%" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td style="padding-left:5px">
                        	Empleado<br/>
							<input type="text" id="busempleado" style="width:97%" />
                        </td>
                        <td>
                        	<table cellpadding="0" cellspacing="0">
                            	<tr>
                                	<td>
                                        De<br/>
										<input type="text" id="fecIni" style="width:80%"  autocomplete="off" /><i class="btn-icon-only icon-calendar" style="font-size:16px"> </i>
                                    </td>
                                    <td>
                                        Al<br/>
										<input type="text" id="fecFin" style="width:80%" autocomplete="off" /><i class="btn-icon-only icon-calendar" style="font-size:16px"> </i>
                                    </td>
                                </tr>
                            </table>
                        </td>
                   	</tr>
              	</table>
            </div>
            <div class="widget-content" style="padding:10px" id="tablaExport">
           	  <table class="table table-striped table-bordered">
                <thead>
                  <tr>
                  	<th> Sucursal </th>
                  	<th> Depto. </th>
					<th> Puesto. </th>
                   	<th> Empleado </th>
                    <th> Asistencias </th>
                    <th> Retardos </th>
                    <th> faltas </th>
                  </tr>
				</thead>
				<tr id="cargandoSVG" style="display: none">
					<td colspan="7">
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: transparent; display: block;" width="200px" height="200px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
						<defs>
						<pattern id="ldio-2e5zc5j0tys-pattern" patternUnits="userSpaceOnUse" x="0" y="0" width="100" height="100">
							<g transform="translate(1.15649 0)">
							<g transform="rotate(20 50 50) scale(1.2)">
								<rect x="-20" y="-10" width="10" height="120" fill="#1d0e0b"></rect>
								<rect x="-10" y="-10" width="10" height="120" fill="rgba(255, 255, 255, 0.7138709677419355)"></rect>
								<rect x="0" y="-10" width="10" height="120" fill="#1d0e0b"></rect>
								<rect x="10" y="-10" width="10" height="120" fill="rgba(255, 255, 255, 0.7138709677419355)"></rect>
								<rect x="20" y="-10" width="10" height="120" fill="#1d0e0b"></rect>
								<rect x="30" y="-10" width="10" height="120" fill="rgba(255, 255, 255, 0.7138709677419355)"></rect>
								<rect x="40" y="-10" width="10" height="120" fill="#1d0e0b"></rect>
								<rect x="50" y="-10" width="10" height="120" fill="rgba(255, 255, 255, 0.7138709677419355)"></rect>
								<rect x="60" y="-10" width="10" height="120" fill="#1d0e0b"></rect>
								<rect x="70" y="-10" width="10" height="120" fill="rgba(255, 255, 255, 0.7138709677419355)"></rect>
								<rect x="80" y="-10" width="10" height="120" fill="#1d0e0b"></rect>
								<rect x="90" y="-10" width="10" height="120" fill="rgba(255, 255, 255, 0.7138709677419355)"></rect>
								<rect x="100" y="-10" width="10" height="120" fill="#1d0e0b"></rect>
								<rect x="110" y="-10" width="10" height="120" fill="#1d0e0b"></rect>
							</g>
							<animateTransform attributeName="transform" type="translate" values="0 0;26 0" keyTimes="0;1" dur="0.5847953216374269s" repeatCount="indefinite"></animateTransform>
							</g>
						</pattern>
						</defs>
						<rect rx="8" ry="8" x="10" y="41.5" stroke="#000000" stroke-width="2.5" width="80" height="17" fill="url(#ldio-2e5zc5j0tys-pattern)"></rect>
						</svg>						
					</td>
				  </tr>				
                <tbody id="tbody">
                </tbody>
              </table>
			  <div id="paginacion" style="text-align:right"></div>
            </div>
          </div>
          <!-- /widget -->
        </div>
        <!-- /span6 -->
      </div>
      <!-- /row --> 
    </div>
    <!-- /container --> 
  </div>
  <!-- /main-inner --> 
</div>
<!-- /main 

<!-- Placed at the end of the document so the pages load faster --> 
<script src="js/jquery-1.7.2.min.js"></script> 
<script src="js/bootstrap.js"></script> 
<script type="text/javascript" src="js/bootstrap-clockpicker.min.js"></script>
<script src="js/jquery-ui.js"></script>
<script type="text/javascript">	
	$('.clockpicker').clockpicker({
		placement: 'top',
		align: 'left',
		donetext: 'Done'
	});
	$(document).ready(function(e) {
		$.datepicker.regional['es'] = {
			closeText: 'Cerrar',
			currentText: 'Hoy',
			monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
			monthNamesShort: ['Enero','Febrero','Marzo','Abril', 'Mayo','Junio','Julio','Agosto','Septiembre', 'Octubre','Noviembre','Diciembre'],
			dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
			dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
			dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
			weekHeader: 'Sm',
			dateFormat: 'dd/mm/yy',
			firstDay: 1,
			isRTL: false,
			showMonthAfterYear: false,
			yearSuffix: ''};
		$.datepicker.setDefaults($.datepicker.regional['es']);
		$("#fecIni").datepicker({
			changeMonth: true,
            changeYear: true,
			showButtonPanel: true,
			yearRange: '1930:+0'
		});
		$("#fecFin").datepicker({
			changeMonth: true,
            changeYear: true,
			showButtonPanel: true,
			yearRange: '1930:+0'
		});
		
		comboCatalogo('bus','departamento',1,'puesto');
		comboCatalogo('bus','sucursal',1);
		
	});
	
	let trabajadorSeleccionado = [];

	function getCurrentDate(){
		var today = new Date();
		var dd = today.getDate();

		var mm = today.getMonth()+1; 
		var yyyy = today.getFullYear();
		if(dd<10) 
		{
			dd='0'+dd;
		} 

		if(mm<10) 
		{
			mm='0'+mm;
		} 
		
		
		return `${dd}/${mm}/${yyyy}`
	}
	
	$("#btnCerrarControl").click(function (e) { 
		e.preventDefault();
		$("#modalControlAsistencia").hide();
				$('html, body').css({
				overflow: 'auto',
			});
	});
	let asistencias;
	function controlAsistencia(opc, empleadoId) {
	// 	$("html, body").animate({scrollTop:"0px"});
	// 	$('html, body').css({
    // overflow: 'hidden',
    // height: '100%'
	// });
		$("#logAsistencia").html('');
		$("#modalControlAsistencia").fadeIn('slow');
		//accediendo a las asistencias del  empleado
		let empleado = asistencias[empleadoId];
		trabajadorSeleccionado = empleado;
		let templateItem = '';
		$("#displaySetIngresoXFalta").hide();
		$("#horaIngresoxFalta").val('');
		$("#nipIngesoXFalta").val('');

		switch (opc) {
			case 'asistencia':
				$("#headerEliminaIncidencia").hide();
				$("#headerMontoIncidencia").hide();
				$("#displaySetIngresoXFalta").hide();

							$("#tituloControl").html("Registro de Asistencia");
								$.each(empleado.asistencia, function (i, item) { 
						templateItem += `
							<tr>
									<td>${i}</td>
										<td>${item}</td>
								</tr>
						`;
				});
			break;
			case 'retardos':
				$("#tituloControl").html('Registro de Rertardos');	
					
					$.each(empleado.retardos, function (i, item) { 
						$("#headerEliminaIncidencia").show();
						$("#headerMontoIncidencia").show();
						$("#displaySetIngresoXFalta").hide();
						 templateItem += `
						 		<tr>
								 		<td>${i}</td>
										 <td>${item}</td>
										 <td> <input type="number" id="sancion_${empleadoId}" value="0"> </td>
										 <td><button onclick="removeIncidenciaAsistencia( {
												 tipo: 'retardo',
												 monto: 0,
												 empleado: ${empleadoId},
												 incidencia: '',
												 checado: '${i} ${item}',
												 index: ${i}
										 })"><i class="icon-minus"></i></button></td>
								 </tr>
						 `;
					});
			break;
			case 'faltas':
					$("#tituloControl").html("Registro de Faltas");
					$("#headerEliminaIncidencia").show();
					$("#headerMontoIncidencia").show();

					$.each(empleado.faltas, function (i, item) { 
						 templateItem += `
						 		<tr>
								 		<td>${i}</td>
										 <td>${item}</td>
										 <td> <input type="number" id="sancion_${empleadoId}" value="0"> </td>
										 <td><button onclick="removeIncidenciaAsistencia( {
												 tipo: 'falta',
												 monto:0,
												 empleado: ${empleadoId},
												 incidencia: '',
												 checado: '${i} ',
												 index: ${i}
										 })"><i class="icon-minus"></i></button>
										 </td>
								 </tr>
						 `;
					});			
			break;
			default:
				break;
		}
		$("#logAsistencia").html(templateItem );
	} 
	
	function removeIncidenciaAsistencia( params ) {
		$("#horaIngresoxFalta").val('');
		$("#nipIngesoXFalta").val( params.empleado );
		$("#btnSetAsistenciaDeFalta").attr('onClick', `confirmRemoveFalta(${JSON.stringify( params ) })`);
		$("#displaySetIngresoXFalta").show();
					$("#horaIngresoxFalta").val('');
					$("#nipIngesoXFalta").val('');
	}

	function confirmRemoveFalta( params ) {
		//Variable para asignarle asistencia en caso de que se le de ingreso a raíz de una falta
		nuevoIngreso = '';
		if( params.tipo == 'falta'){
			nuevoIngreso = $("#horaIngresoxFalta").val();
			if( nuevoIngreso.trim()  == '' ){
				alert("Debes elegir una hora de ingreso aproximada");
				return 0;
			}
		}

		$.ajax({
				url: "/intranet/nomina/asistencia/"+params.tipo,
				type: 'POST',
				data: {
					empleado: params.empleado,
					checado: params.checado,
					incidencia: params.incidencia,
					horaNvoIngreso: nuevoIngreso
				},
				dataType: "text",
				success: function(result) {
					if ( result == 1) {
						alert(`Se ha desaplicado ${params.tipo} al trabajador ${trabajadorSeleccionado.nombre}`);
						if ( params.tipo == 'retardo') {
							(asistencias[ trabajadorSeleccionado.id ].diasRetardo).splice( params.index ,1);

							if ( (asistencias[ trabajadorSeleccionado.id ].diasRetardo).length > 0 ) {
								controlAsistencia(  "retardos", trabajadorSeleccionado.id )
							}else{
								$("#modalControlAsistencia").hide();
									$('html, body').css({
									overflow: 'auto',
								});
								lista(0 , 15);
							}
							renderListaAsistencia( asistencias );
						}else if( params.tipo == 'falta'){
							(asistencias[ trabajadorSeleccionado.id ].diasFaltas).splice( params.index ,1);

							if ( (asistencias[ trabajadorSeleccionado.id ].diasFaltas).length > 0 ) {
								controlAsistencia( "faltas" , trabajadorSeleccionado.id )
							}else{
								$("#modalControlAsistencia").hide();
									$('html, body').css({
									overflow: 'auto',
								});
								lista(0 , 15);
							}							
							$("#displaySetIngresoXFalta").hide();
							$("#horaIngresoxFalta").val('');
							$("#nipIngesoXFalta").val('');
							renderListaAsistencia( asistencias );
						}

						
					}else{
						alert("No se pudo realizar la operación");
					}
				}
			});
	}


	$("#btnAplicaCambiosMontos").click(function (e) { 
		
		let relacionActualizacion = [];
		//obteniendo todas  los retardos del trabajador
		$.each( trabajadorSeleccionado.diasRetardo , function (i, item) { 
			relacionActualizacion.push({
				idIncidencia : item.aplicaIncidencia,
				monto: $(`#sancion_${trabajadorSeleccionado.id}_${item.aplicaIncidencia}`).val()
			})
						
		});
		//Obteniendo las faltas del trabajador cuyos montos serán actualizados
		$.each( trabajadorSeleccionado.diasFaltas , function (i, item) { 
			relacionActualizacion.push({
				idIncidencia : item.aplicaIncidencia,
				monto: $(`#sancion_${trabajadorSeleccionado.id}_${item.aplicaIncidencia}`).val()
			})
						
		});

		//Haciendo el envío para la actualización de los montos
		$.post(`/intranet/nomina/incidencia/actualizar`, {
			incidencias: JSON.stringify( relacionActualizacion )
		},
			function (data, textStatus, jqXHR) {
				if ( data == 1) {
					alert("Se han aplicado los cambios correctamente");
				}
			},
			"text"
		);
	});


	function renderListaAsistencia( lista ){
		console.log( lista );
		
		let echo = '';
		for(i in lista){
				echo+= "<tr class='text-center'>";
				echo+= "	<td>"+lista[i].sucursal+"</td>";
				echo+= "	<td>"+lista[i].depto+"</td>";
				echo+= "	<td>"+lista[i].puesto+"</td>";
				echo+= "	<td>"+lista[i].nombre+"</td>";
				echo+= `	<td style='text-align:center;cursor:pointer' onclick='controlAsistencia("asistencia",${i})'>${lista[i].asistenciaTotal}</td>`;
				echo+= `	<td style='text-align:center;cursor:pointer' onclick='controlAsistencia("retardos",${i})'>${lista[i].retardosTotal}</td>`;
				echo+= `	<td style='text-align:center;cursor:pointer' onclick='controlAsistencia("faltas",${i})'>${lista[i].faltasTotal}</td>`;
				echo+=`		<td style='text-align:center;cursor:pointer;' onclick='descargaAsistencia(${ JSON.stringify( lista[i] )  })'> <img src="/nomina/assets/images/descarga.png" style="width:30px;height:auto"></td>`
				echo+= "<tr>";
				
			}
			// for(j=n;j<cantidad;j++){
			// 	echo+= "<tr>";
			// 	echo+= "	<td>&nbsp;</td>";
			// 	echo+= "	<td>&nbsp;</td>";
			// 	echo+= "	<td>&nbsp;</td>";
			// 	echo+= "	<td>&nbsp;</td>";
			// 	echo+= "	<td>&nbsp;</td>";
			// 	echo+= "	<td>&nbsp;</td>";
			// 	echo+= "	<td>&nbsp;</td>";
			// 	echo+= "<tr>";
			// }
			$("#tbody").html(echo);
	}

	function lista(inicio,cantidad){
		$("#cargandoSVG").fadeIn();
		var fecIni = $("#fecIni").val();
		var fecFin = $("#fecFin").val();
		var nombre = $("#busempleado").val();
		var sucursal = $("#bussucursal").val();
		var depto = $("#busdepartamento").val();		
		var puesto = $("#buspuesto").val();
		var params = "fecIni="+fecIni;
		params+= "&fecFin="+fecFin;
		params+= "&nombre="+nombre;
		params+= "&sucursal="+sucursal;
		params+= "&departamento="+depto;
		params+= "&puesto="+puesto;
		params+= "&inicio="+inicio;
		params+= "&cantidad="+cantidad;
		$.post("ajax/ajaxasistencia.php?op=lista", params, function(resp){
			console.log(resp);
			//alert(resp);
			//$("#paginacion").html(resp);
	  		var row = eval('('+resp+')');
				asistencias = row;
			var echo = "";
			var n=0;
			$("#cargandoSVG").fadeOut();
			renderListaAsistencia( row );
		});
		
		/*$.post("ajax/ajaxasistencia.php?op=listaP", params, function(resp){
			//alert(resp);
			var paginas = Math.ceil(resp/cantidad);
			var echo = '';
			var n2 = 0;
			for(i=1;i<=paginas;i++){
				var inicio = (n2 * cantidad);
				echo+= '<input type="button" value="'+i+'" onclick="lista('+inicio+','+cantidad+')" />';
				n2++;
			}
	  		
			$("#paginacion").html(echo);
		});*/
		
	}

	function descargaAsistencia (asistencia) {
		$.post("/intranet/asistencia/recibos", {
			asistencia: asistencia
		},
			function (data, textStatus, jqXHR) {
				window.open( data );
			},
			"text"
		);
		
	}

	function getMovimientosTrabajadores() {
		                                                           
	}

	function listaIncidenciasAsistencia() {
		inicio = 0;
		cantidad = 0;

		$.post("ajax/ajaxasistencia.php?op=lista", {
			fecIni: $("#fecIni").val(),
			fecFin: $("#fecFin").val(),
			nombre: $("#busempleado").val(),
			sucursal: $("#bussucursal").val(),
			departamento: $("#busdepartamento").val(),
			puesto: $("#buspuesto").val(),
			inicio: inicio,
			cantidad: cantidad
		}, function(resp){
			//alert(resp);
			//$("#paginacion").html(resp);
	  		var row = eval('('+resp+')');
				asistencias = row;
			var echo = "";
			var n=0;
			for(i in row){
				echo+= "<tr class='text-center'>";
				echo+= "	<td>"+row[i].sucursal+"</td>";
				echo+= "	<td>"+row[i].depto+"</td>";
				echo+= "	<td>"+row[i].puesto+"</td>";
				echo+= "	<td>"+row[i].nombre+"</td>";
				echo+= `	<td style='text-align:center;cursor:pointer' onclick='controlAsistencia("asistencia",${row[i].id})'>${row[i].historialAsistencia.length}</td>`;
				echo+= `	<td style='text-align:center;cursor:pointer' onclick='controlAsistencia("retardos",${row[i].id})'>${row[i].nRetardos}</td>`;
				echo+= `	<td style='text-align:center;cursor:pointer' onclick='controlAsistencia("faltas",${row[i].id})'>${row[i].faltas}</td>`;
				echo+= `	 <td>
										<select class="sancion_empleado" id="emp_${row[i].id}">
											<option>Aplica</option>
											<option>No Aplica</option>
											<option>Aplica Sancion Diferente</option>
										</select>
									</td>`;
				echo+= `	<td>
										<label>Concepto</label>
										<input type="number" placeholder="Ingresa un monto"
										<label>Observaciones:</label>
										<textarea></textarea>
									</td>`;
				echo+= "<tr>";
				n++;
			}
			for(j=n;j<cantidad;j++){
				echo+= "<tr>";
				echo+= "	<td>&nbsp;</td>";
				echo+= "	<td>&nbsp;</td>";
				echo+= "	<td>&nbsp;</td>";
				echo+= "	<td>&nbsp;</td>";
				echo+= "	<td>&nbsp;</td>";
				echo+= "	<td>&nbsp;</td>";
				echo+= "	<td>&nbsp;</td>";
				echo+= "<tr>";
			}
			$("#tbody").html(echo);
		});
	}
	
	function imprimir(){
		var ficha = document.getElementById('tablaExport');
		var ventimp = window.open(' ', 'popimpr');
		ventimp.document.write( ficha.innerHTML );
		ventimp.document.close();
		ventimp.print( );
		ventimp.close();
	}
	
	function cancelar(){
		if(confirm("Se procederá a cancelar los recibos de nómina seleccionados, ¿Esta seguro de relizar esta acción?")){
			$("input:checkbox:checked").each(function() {
				 var id = $(this).attr('name');
				 var arreglo = new Array();
				 if(id!='chkall'){
					 arreglo = id.split("-");
					 $("#ico"+id).html('<img src="loading.gif" width="17px" heigth="17px" />');
					 $.post("ajax/ajaxtimbrado.php?op=cancelar", "serie="+arreglo[0]+"&folio="+arreglo[1], function(resp){
						//alert(resp);						
						var row = eval('('+resp+')');
						if(row.status==201){
							$("#ico"+id).html("<span class='shortcut-icon icon-ok'></span>");
							$("#divchk"+id).html("");
						}else{
							alert(row.mensaje);
							$("#ico"+id).html("<span class='shortcut-icon icon-exclamation-triangle'></span>");
						}
						
					});
				 }
			});
		}
	}
	
	function descargar(){
		window.open('data:application/vnd.ms-excel,' + encodeURIComponent($('#tablaExport').html()));
		e.preventDefault();
	}
	
	function prenomina(){
		$.ajax('/nomina/ajax/reportes/asistencias_faltas.php',function(data){
		});
		$.ajax('wsasistencia.php',function(data){
			console.log(data);
			window.open(data, "", "width=200,height=100");
		});
		window.open("/nomina/ajax/prenomina.php", "", "width=200,height=100");
	}
	function comboCatalogo(prefijo,catalogo,tipo,scatalogo,sscatalogo,ssscatalogo){
		var id = "";
		if(tipo==1){
			$.post("ajax/ajaxempleado.php?op=comboCatalogo", "catalogo="+catalogo+"&tipo="+tipo, function(resp){
				//alert(resp);
				$("#"+prefijo+""+catalogo).html("<option value='%'>TODOS...</option>"+resp);
				
				//Cargamos el subcombo
				if(typeof scatalogo != 'undefined'){
					id = $("#"+prefijo+""+catalogo).val();
					$.post("ajax/ajaxempleado.php?op=comboCatalogo", "catalogo="+catalogo+"&scatalogo="+scatalogo+"&id="+id+"&tipo="+tipo, function(sresp){
						$("#"+prefijo+""+scatalogo).html("<option value='%'>TODOS...</option>"+sresp);
						
						//Cargamos el subcombo
						if(typeof sscatalogo != 'undefined'){
							id = $("#"+prefijo+""+scatalogo).val();
							$.post("ajax/ajaxempleado.php?op=comboCatalogo", "catalogo="+scatalogo+"&scatalogo="+sscatalogo+"&id="+id+"&tipo="+tipo, function(ssresp){
								$("#"+prefijo+""+sscatalogo).html(ssresp);
								
								//Cargamos el subcombo
								if(typeof ssscatalogo != 'undefined'){
									id = $("#"+prefijo+""+sscatalogo).val();
									$.post("ajax/ajaxempleado.php?op=comboCatalogo", "catalogo="+sscatalogo+"&scatalogo="+ssscatalogo+"&id="+id+"&tipo="+tipo, function(sssresp){
										$("#"+prefijo+""+ssscatalogo).html(sssresp);
									});
								}
							});
						}
					});
				}
			});
		}
		if(tipo==2){
			if(typeof scatalogo != 'undefined'){
				id = $("#"+prefijo+""+catalogo).val();
				$.post("ajax/ajaxempleado.php?op=comboCatalogo", "catalogo="+catalogo+"&scatalogo="+scatalogo+"&id="+id+"&tipo="+tipo, function(sresp){
					$("#"+prefijo+""+scatalogo).html(sresp);
					
					//Cargamos el subcombo
					if(typeof sscatalogo != 'undefined'){
						id = $("#"+prefijo+""+scatalogo).val();
						$.post("ajax/ajaxempleado.php?op=comboCatalogo", "catalogo="+scatalogo+"&scatalogo="+sscatalogo+"&id="+id+"&tipo="+tipo, function(ssresp){
							$("#"+prefijo+""+sscatalogo).html(ssresp);
							
							//Cargamos el subcombo
							if(typeof ssscatalogo != 'undefined'){
								id = $("#"+prefijo+""+sscatalogo).val();
								$.post("ajax/ajaxempleado.php?op=comboCatalogo", "catalogo="+sscatalogo+"&scatalogo="+ssscatalogo+"&id="+id+"&tipo="+tipo, function(sssresp){
									$("#"+prefijo+""+ssscatalogo).html(sssresp);
								});
							}
						});
					}
				});
			}
		}
		if(tipo==3){
			$.post("ajax/ajaxempleado.php?op=comboCatalogo", "catalogo="+catalogo+"&tipo="+tipo, function(resp){
				//alert(resp);
				$("#"+prefijo+""+catalogo).html(resp);
				
				//Cargamos el subcombo
				if(typeof scatalogo != 'undefined'){
					id = $("#"+prefijo+""+catalogo).val();
					$.post("ajax/ajaxempleado.php?op=comboCatalogo", "catalogo="+catalogo+"&scatalogo="+scatalogo+"&id="+id+"&tipo="+tipo, function(sresp){
						$("#"+prefijo+""+scatalogo).html(sresp);
						
						//Cargamos el subcombo
						if(typeof sscatalogo != 'undefined'){
							id = $("#"+prefijo+""+scatalogo).val();
							$.post("ajax/ajaxempleado.php?op=comboCatalogo", "catalogo="+scatalogo+"&scatalogo="+sscatalogo+"&id="+id+"&tipo="+tipo, function(ssresp){
								$("#"+prefijo+""+sscatalogo).html(ssresp);
								
								//Cargamos el subcombo
								if(typeof ssscatalogo != 'undefined'){
									id = $("#"+prefijo+""+sscatalogo).val();
									$.post("ajax/ajaxempleado.php?op=comboCatalogo", "catalogo="+sscatalogo+"&scatalogo="+ssscatalogo+"&id="+id+"&tipo="+tipo, function(sssresp){
										$("#"+prefijo+""+ssscatalogo).html(sssresp);
									});
								}
							});
						}
					});
				}
			});
		}
	}
</script>
</body>
</html>
