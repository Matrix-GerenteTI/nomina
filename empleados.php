<?php
	require_once("ajax/control.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Sistema para Timbrado de N&oacute;mina</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600"
        rel="stylesheet">
<link href="css/font-awesome.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/pages/dashboard.css" rel="stylesheet">
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
<!--  Modal para baja con registro de fecha del empleado -->
<div id="modBajaPersonal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Bajas de empleados</h4>
      </div>
      <div class="modal-body">
	  	<div style="width:50%; margin-left:25%;">
			<div class="form-group">
			  	<label for="">Fecha de Baja</label>
				<input type="text" id="dateBajaPersonal">
				<label for="">Observaciones</label>
				<textarea id="txtBajaPersonal"></textarea>
			</div>
		</div>
	  </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
		<button type="button" class="btn btn-default" id="btnBajaPersonal">Confirmar</button>
      </div>
    </div>
  </div>
</div>

<!--  Fin modal de baja con registo de fecha del empleado -->


<!--  Modal para el cambio de adscripcion del personal     -->
<div id="modCambioAdscripcion" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Cambio de Adscripción</h4>
      </div>
      <div class="modal-body">
	  	<div style="width:50%; margin-left:25%;">
			<div class="form-group">
			  	<label for="">Fecha de cambio</label>
				  <input type="text" id="dateCambioAds">
			  </div>
		  </div>
	  </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
		<button type="button" class="btn btn-default" id="btnCambiaAds">Confirmar</button>
      </div>
    </div>
  </div>
</div>

<!-- Fin modal para cambio de adscripción del personal -->

<!-- Modal -->
<div id="busquedaEmpleados" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">B&uacute;squeda de Empleado</h4>
      </div>
      <div class="modal-body">
        
		<table width="100%">
			<tr>
				<td>
					Departamento<br/>
					<select id="busdepartamento" style="width:92%">
					</select>
				</td>
				<td>
					Nombre<br/>
					<input type="text" id="busnombre" style="width:95%" />
				</td>
				<td align="right">
					<div class="shortcuts"> 
						<a href="javascript:buslista();" class="shortcut"><i class="shortcut-icon icon-search"></i><span class="shortcut-label"></span></a>&nbsp;
					</div>
				</td>
			</tr>
		</table>
		<br/>
		<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th> Nombre </th>
				<th> Departamento </th>
			</tr>
		</thead>
		<tbody id="bustbody">
		</tbody>
		</table>
		
		
      </div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
      </div>
    </div>

  </div>
</div>

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
        <li><a href="empleados.php"><i class="icon-flag"></i><span>Empleados</span> </a> </li>
		<li><a href="socioeconomicos.php"><i class="icon-book"></i><span>Socioecónomicos</span></a></li>
		<li><a href="reportes.php"><i class="icon-archive"></i><span>Reportes</span> </a> </li>
	<?php
        }else{
    ?>
		<li><a href="index.php"><i class="icon-ok"></i><span>Timbrar</span> </a> </li>
		<li class="active"><a href="empleados.php"><i class="icon-group"></i><span>Empleados</span> </a></li>
		<li ><a href="socioeconomicos.php"><i class="icon-book"></i><span>Socioecónomicos</span></a></li>
		<li ><a href="vacaciones.php"> <i class="icon-plane"></i><span>Vacaciones</span></a></li>
    	<li><a href="incidencias.php"><i class="icon-calculator"></i><span>Incidencias</span> </a> </li>
		<li><a href="empresa.php"><i class="icon-building"></i><span>Empresa</span> </a> </li>
        <li><a href="cfdis.php"><i class="icon-list-alt"></i><span>CFDIs</span> </a> </li>
		<li><a href="asistencia.php"><i class="icon-file-clock-o"></i><span>Asistencias</span> </a> </li>
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
	
          <div class="widget widget-nopad">
            <div class="form-actions" style="margin-top:0px; margin-bottom:0px; padding:5px">
            	<table width="100%" cellpadding="0" cellspacing="0">
                	<tr>
						<td width="75px">
							<div class="shortcuts"> 
								<a href="javascript:buscar();" class="shortcut"><i class="shortcut-icon icon-search"></i><span class="shortcut-label">Buscar</span></a>&nbsp;
							</div>
						</td>
                    	<td>
							<table align="right" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<div class="shortcuts"> 
											<a href="javascript:nuevo();" class="shortcut"><i class="shortcut-icon icon-file-alt"></i><span class="shortcut-label">Nuevo</span></a>&nbsp;
											<a href="javascript:guardar();" class="shortcut"><i class="shortcut-icon icon-save btn-guardar"></i><span class="shortcut-label">Guardar</span></a>&nbsp;
											<!--<a href="javascript:imprimir();" class="shortcut"><i class="shortcut-icon icon-print"></i><span class="shortcut-label">Imprimir</span></a>&nbsp;-->
											<a href="javascript:eliminar();" class="shortcut"><i class="shortcut-icon icon-remove"></i><span class="shortcut-label">Eliminar</span></a>
										</div>
									</td>
								</tr>
							</table>
                      	</td>
                   	</tr>
              	</table>
			</div>			
            <div class="widget-header"> <i class="icon-edit"></i>
              <h3> Datos Personales</h3>
            </div>
            <!-- /widget-header -->
            <div class="widget-content" style="padding:10px">
				<div style="text-align:center">
					
				</div>
				<table width="100%" style="border-collapse: collapse">
					<tr>
						<td style="width:30%" rowspan="3">
							<img src="/intranet/assets/images/person.png"  style="width: 240px; height:240px;margin-left:5%" id="previewfoto"/>
							<input type="file" style="display: none" id="takePhoto" accept="image/*;capture=camera" />
						</td>
						<td width="70%">
							<table width="100%">
								<tr>
									<td width="15%">
										<b>NIP</b><br/>
										<input type="text" id="pnip" style="width:90%; text-align:center" readonly />
									</td>
									<td>
										
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						
						<td style="width:70%">
							<table width="100%">
								<tr>
									<td width="15%">
										RFC<br/>
										<input type="text" id="prfc" style="width:90%" />
									</td>
									<td>
										Nombre<br/>
										<input type="text" id="pnombre" style="width:97%" />
									</td>
									<td width="20%">
										CURP<br/>
										<input type="text" id="pcurp" style="width:92%" />
									</td>
									<td width="20%">
										NSS<br/>
										<input type="text" id="pnss" style="width:92%" />
									</td>
								</tr>
							</table>

						</td>
					</tr>
					<tr>
						<td>
							<table width="100%">
								<tr>
									<td>
										Fecha de nacimiento<br/>
										<input type="text" id="pfecnac" style="width:92%" onChange="calculaTiempo('pfecnac','pedad','edad')" />
									</td>
									<td>
										Edad<br/>
										<input type="text" id="pedad" style="width:92%" readonly />
									</td>
									<td>
										Estado civil<br/>
										<select id="pedocivil" style="width:92%">
											<option value="SOLTERA(O)">SOLTERA(O)</option>
											<option value="CASADA(O)">CASADA(O)</option>
											<option value="DIVORCIADA(O)">DIVORCIADA(O)</option>
											<option value="VIUDA(O)">VIUDA(O)</option>
										</select>
									</td>
									<td>
										Sexo<br/>
										<select id="psexo" style="width:92%">
											<option value="MUJER">MUJER</option>
											<option value="HOMBRE">HOMBRE</option>
										</select>
									</td>
								</tr>
							</table>

						</td>
					</tr>
				</table>
              	<table width="100%">
                    <tr>
                        <td>
                        	Calle<br/>
                            <input type="text" id="pcalle" style="width:95%" />
                       	</td>
						<td width="10%">
                        	Numero Ext.<br/>
                            <input type="text" id="pnumext" style="width:87%" />
                       	</td>
						<td width="10%">
                        	Numero Int.<br/>
                            <input type="text" id="pnumint" style="width:87%" />
                       	</td>
						<td width="30%">
                        	Colonia<br/>
                            <input type="text" id="pcolonia" style="width:95%" />
                       	</td>
						<td width="10%">
                        	C.P.<br/>
                            <input type="text" id="pcp" style="width:85%" />
                       	</td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
						<td>
                        	Estado<br/>
                            <select id="pestado" style="width:92%">
                            </select>
                       	</td>
						<td>
                        	Municipio<br/>
                            <input type="text" id="pmunicipio" style="width:92%" />
                       	</td>
                        <td>
                        	Correo electr&oacute;nico<br/>
                            <input type="text" id="pemail" style="width:92%" />
                       	</td>
                        <td>
                        	Tel&eacute;fono particular<br/>
                            <input type="text" id="ptelefono" style="width:92%" />
                       	</td>
                        <td>
                        	Celular<br/>
                            <input type="text" id="pcelular" style="width:92%" />
                       	</td>
					</tr>
					<tr>
						<td>
							Religión
							<input type="text" id="religion">
						</td>
						<td>
							¿Tiene hijos?<br>
							No<input type="radio" name="hasChild" class="hasChild" value="0" checked>
							Sí<input type="radio" name="hasChild" class="hasChild" value="1">
						</td>
						<td  id="formHijos" style="display:none">
								<table>
										<tr>
												<td >
														# Hijas<br>
														<input type="number"  id="nHijas"  style="width:50%">
												</td>
												
												<td>
														# Hijos<br>
														<input type="number"  id="nHijos" style="width:50%">
												</td>
										</tr>
								</table>
						</td>
						<td>
							Escolaridad<br>
							<select  id="selEscolaridad">
								<option value="NINGUNO">NINGUNO</option>
								<option value="PRIMARIA">PRIMARIA</option>
								<option value="SECUNDARIA">SECUNDARIA</option>
								<option value="PREPARATORIA">PREPARATORIA</option>
								<option value="LICENCIATURA">LICENCIATURA</option>
								<option value="MAESTRIA">MAESTRÍA</option>
								<option value="DOCTORADO">DOCTORADO</option>
								<option value="POSGRADO">POSGRADO</option>
								<option value="OTRO">OTRO</option>
							</select>
						</td>
						<td id="otrosEstudios" style="display:none">
							Especifique<br>
							<input type="text" name="" id="txtescolaridad">
						</td>
					</tr>
					<tr>
						<td>
								¿Asegurado?<br>
								No <input type="radio" name="hasSeguro"  class="hasSeguro" value="0" checked>
								Sí <input type="radio" name="hasSeguro"  class="hasSeguro" value="1">
							</td>
						<td>
								Tipo de Sangre<br>
								<input type="text"  id="tipoSangre" placeholder="Ej. A POSITIVO">
						</td>
						<td>
							Alergias Medicas
							<input type="text" id="alergiaMedica">
						</td>
					</tr>
                </table>     
            </div>
          </div>
          <div class="widget widget-nopad">
			<div class="widget-header"> <i class="icon-edit"></i>
              <h3> Datos de Acceso</h3>
            </div>
            <!-- /widget-header -->
            <div class="widget-content" style="padding:10px">
				<div style="text-align:center">
					
				</div>
                <table width="100%">
                    <tr>
						<td width="20%">
                        	Usuario<br/>
                            <input type="text" id="pusuario" style="width:90%" />
                       	</td>
                        <td width="20%">
                        	Contrase&ntilde;a<br/>
                            <input type="password" id="ppassword" style="width:90%" />
                       	</td>
						<td width="20%">
                        	Repetir Contrase&ntilde;a<br/>
                            <input type="password" id="ppasswordr" style="width:90%" />
                       	</td>
						<td width="20%">
                        	Tipo<br/>
                            <select id="ptipo" style="width:92%">
								<option value="3">USUARIO</option>
								<option value="1">ADMINISTRADOR</option>
                            </select>
                       	</td>
						<td></td>
                    </tr>
                </table>   
            </div>
          </div>
          <div class="widget widget-nopad">
            <div class="widget-header"> <i class="icon-file"></i>
              <h3> Datos del contrato</h3>
            </div>
            <div class="widget-content" style="padding:10px">
                <table width="100%">
                    <tr>
						<td>
                        	Sucursal<br/>
                            <select id="psucursal" style="width:92%">
                            </select>
                       	</td>
						<td>
                        	Departamento<br/>
                            <select id="pdepartamento" style="width:92%" onChange="comboCatalogo('p','departamento',2,'puesto')">
                            </select>
                       	</td>
						<td>
                        	Puesto<br/>
                            <select id="ppuesto" style="width:92%">
                            </select>
                       	</td>
						<td>
							Cambiar adscripción
							<button class="btn btn-danger" id="actAdscripcion">Cambiar</button>
						</td>
						<td>
                        	Tipo de contrato<br/>
                            <select id="ptipocontrato" style="width:92%">
                            </select>
                       	</td>
						<td>
                        	Tiempo de contrato<br/>
                            <select id="ptiempocontrato" style="width:200px">
                            </select>
                       	</td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
						<td>
                        	Tipo de jornada<br/>
                            <select id="ptipojornada" style="width:92%">
                            </select>
                       	</td>					
                        <td>
                        	Fecha de Inicio Laboral<br/>
                            <input type="text" id="piniciolaboral" style="width:92%" onChange="calculaTiempo('piniciolaboral','pantiguedad','antiguedadSAT')" />
                       	</td>
                        <td>
                        	Antigüedad<br/>
                            <input type="text" id="pantiguedad" style="width:92%" readonly/>
                       	</td>
						<td>
                        	Sindicalizado<br/>
                            <select id="psindicalizado" style="width:92%">
								<option value='No'>No</option>
								<option value='Sí'>Si</option>
                            </select>
                       	</td>
                        <td>
                        	Tipo de r&eacute;gimen<br/>
                            <select id="ptiporegimen" style="width:92%">
                            </select>
                       	</td>
                    </tr>
                </table>   
                <table width="100%">
                    <tr>
                        <td>
                        	Riesgo del puesto<br/>
                            <select id="priesgopuesto" style="width:92%">
                            </select>
                       	</td>					
                        <td>
                        	Periodicidad de pago<br/>
                            <select id="pperiodicidadpago" style="width:92%">
                            </select>
                       	</td>
                        <td>
                        	Salario base<br/>
                            <input type="number" id="psalariobase" style="width:92%"  />
                       	</td>
						<td>
                        	Salario diario integrado<br/>
                            <input type="number" id="psalariodiario" style="width:92%"  />
                       	</td>
                        <td>
                        	Banco<br/>
                            <select id="pbanco" style="width:92%">
                            </select>
                       	</td>
                    </tr>
                </table>
				<table>
					<td>
                        	Cuenta bancaria<br/>
                            <input type="number" id="pcuentabancaria" style="width:92%"  />
                       	</td>				
                    <tr>
                        <td>
                        	RFC (Subcontrataci&oacute;n)<br/>
                            <input type="text" id="psubrfc" style="width:92%"  />
                       	</td>
                        <td>
                        	% de tiempo (Subcontrataci&oacute;n)<br/>
                            <input type="number" id="psubporcentaje" style="width:92%"  />
                       	</td>
                    </tr>
                </table>
            </div>
          </div>
          <!-- /widget -->
		  <div class="widget widget-nopad">
            <div class="widget-header"> <i class="icon-file"></i>
              <h3> Documentaci&oacute;n digital</h3>
            </div>
            <div class="widget-content" style="padding:10px">
                <table>
                    <tr>
						<td>
                        	Archivo<br/>
                            <input type="file" id="parchivo" name="parchivo" style="width:92%" />
                       	</td>
						<td>
                        	Tipo de documento<br/>
                            <select id="ptipodoc" style="width:92%"></select>
                       	</td>						
						<td>
							&nbsp;
							<br/>
                        	<input type="button" value="Agregar" onClick="subirDoc()" />
                       	</td>
                    </tr>
                </table>
				<div id="documentos" style="padding:10px"></div>
            </div>
            
            <div class="form-actions" style="text-align:right; margin-top:0px; margin-bottom:0px; padding:5px">
            	<table align="right" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td align="right">
                            <div class="shortcuts"> 
                                <a href="javascript:nuevo();" class="shortcut"><i class="shortcut-icon icon-file-alt"></i><span class="shortcut-label">Nuevo</span></a>&nbsp;
                                <a href="javascript:guardar();" class="shortcut"><i class="shortcut-icon icon-save btn-guardar"></i><span class="shortcut-label">Guardar</span></a>&nbsp;
                                <!--<a href="javascript:imprimir();" class="shortcut"><i class="shortcut-icon icon-print"></i><span class="shortcut-label">Imprimir</span></a>&nbsp;-->
                                <a href="javascript:eliminar();" class="shortcut"><i class="shortcut-icon icon-remove"></i><span class="shortcut-label">Eliminar</span></a>
                            </div>
                      	</td>
                   	</tr>
              	</table>
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
<!-- /main  --
<div class="footer">
  <div class="footer-inner">
    <div class="container">
      <div class="row">
        <div class="span12"> &copy; 2017 Timbrado de N&oacute;mina. <a href="http://www.xiontecnologias.com/">Creado por XION Tecnologias</a></div>
        <!-- /span12 --
      </div>
      <!-- /row --
    </div>
    <!-- /container --
  </div>
  <!-- /footer-inner --
</div>
<!-- /footer --> 
<!-- Le javascript
================================================== --> 
<!-- Placed at the end of the document so the pages load faster --> 
<script src="js/jquery-1.7.2.min.js"></script> 
<script src="js/bootstrap.js"></script> 
<script src="js/jquery-ui.js"></script>
<script type="text/javascript">
	var camposTXT = new Array('pnip',
							   'prfc_txt',
							   'pnombre_txt',
							   'pcurp_txt',
							   'pnss',
							   'pfecnac_txt',
							   'pedad_txt',
							   'pcalle_txt',
							   'pnumext_txt',
							   'pnumint',
							   'pcolonia_txt',
							   'pcp_txt',
							   'pmunicipio_txt',
							   'ptelefono',
							   'pcelular',
							   'pemail',
							   'piniciolaboral_txt',
							   'pantiguedad_txt',
							   'psalariobase_txt',
							   'psalariodiario_txt',
							   'pcuentabancaria',
							   'psubrfc',
							   'psubporcentaje',
							   'pusuario_txt',
							   'ppassword_txt',
							   'ppasswordr_txt',
							   'nHijas',
							   'nHijos',
							   'txtescolaridad',
							   'tipoSangre',
							   'alergiaMedica'
							   );
							   
	
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
		$("#pfecnac").datepicker({
			changeMonth: true,
            changeYear: true,
			showButtonPanel: true,
			yearRange: '1930:+0'
		});
		$("#piniciolaboral").datepicker({
			changeMonth: true,
            changeYear: true,
			showButtonPanel: true,
			yearRange: '1930:+0'
		});
		$("#dateBajaPersonal").datepicker({
			changeMonth: true,
            changeYear: true,
			showButtonPanel: true,
			yearRange: '1930:+0'
		});
		$("#dateCambioAds").datepicker({
			changeMonth: true,
            changeYear: true,
			showButtonPanel: true,
			yearRange: '1930:+0'
		});		
		
        nuevo();

	
	function $_GET(param){
		url = document.URL;
		url = String(url.match(/\?+.+/));
		url = url.replace("?", "");
		url = url.split("&");
		x = 0;
		while (x < url.length){
			p = url[x].split("=");
			if (p[0] == param){
				return decodeURIComponent(p[1]);
			}
			x++;
		}
	}
	
	function checaGET(){
		var v = $_GET("v");
		if(v=="outview"){
			cargarExpediente();
		}
	}
	
	function formatearFecha(fecha){
		fec = fecha.split("-");
		return fec[2]+"/"+fec[1]+"/"+fec[0];	
	}
	
	function obtenerFolio(){
		$.post("ajax/ajaxempleado.php?op=obtenerFolio", "", function(resp){
			//alert(resp);
			$("#pnip").val(resp);
		});
	}
	
	function calculaTiempo(origen,destino,tipo){
		var fecha = $("#"+origen).val();
		$.post("ajax/ajaxempleado.php?op=calculaTiempo", "fecha="+fecha+"&tipo="+tipo, function(resp){
			//alert(resp);
			$("#"+destino).val(resp);
		});
	}
	
	function comboCatalogo(prefijo,catalogo,tipo,scatalogo,sscatalogo,ssscatalogo){
		var id = "";
		if(tipo==1){
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
	
	function nuevo(){
		//obtenerFolio();		
		$("#previewfoto").attr('src', '/intranet/assets/images/person.png');//intranet/assets/images/person.png
		reiniciaTXT(camposTXT);
		comboCatalogo('p','estado',1);
		comboCatalogo('p','banco',1);
		comboCatalogo('p','periodicidadpago',1);
		comboCatalogo('p','tiporegimen',1);
		comboCatalogo('p','riesgopuesto',1);
		comboCatalogo('p','tipocontrato',1);
		comboCatalogo('p','tiempocontrato',1);
		comboCatalogo('p','tipojornada',1);
		comboCatalogo('p','sucursal',1);
		comboCatalogo('p','tipodoc',1);
		comboCatalogo('p','departamento',1,'puesto');		
		comboCatalogo('bus','departamento',3);
		$("#documentos").html('');
	}
	
	function buscar(){
		$("#busquedaEmpleados").modal('toggle');
		comboCatalogo('bus','departamento',3);
		buslista();
	}
	
	function buslista(){
		var departamento = $("#busdepartamento").val();
		var nombre = $("#busnombre").val();
		$.post("ajax/ajaxempleado.php?op=buslista", "departamento="+departamento+"&nombre="+nombre, function(resp){
			//alert(resp);
	  		var row = eval('('+resp+')');
			var echo = "";
			for(i in row){
				echo+= "<tr style='cursor:pointer' onClick='loadmodal(\""+row[i].nip+"\")'>";
				echo+= "	<td>"+row[i].departamento+"</td><td>"+row[i].nombre+"</td>";
				echo+= "<tr>";
			}
			$("#bustbody").html(echo);
		});
	}
	
	function actualizar() {
			let empleado = $("#pnip").val();
			let rfc = $("#prfc").val();
			let nombre = $("#pnombre").val();
			let curp = $("#pcurp").val();			
			let nss = $("#pnss").val();
			let nacimiento = $("#pfecnac").val(formatearFecha());
			let edoCivil =$("#pedocivil").val();
			let sexo = $("#psexo").val();
			let email = $("#pemail").val();
			let telefono = $("#ptelefono").val();
			let celular = $("#pcelular").val();
			//Datos de pdireccion
			let calle = $("#pcalle").val();
			let numCasa = $("#pnumext").val();
			let interior = $("#pnumint").val();
			let colonia = $("#pcolonia").val();
			let codPostal = $("#pcp").val();
			let municipio = $("#pmunicipio").val();
			let estado = $("#pestado").val();
			
			let username = $("#pusuario").val();			
			let password = $("#ppassword").val();			
			let tipo = $("#ptipo").val();
			
			let salarioBase =$("#psalariobase").val();
			let salarioDiario = $("#psalariodiario").val();
			let cuentaBancaria =$("#pcuentabancaria").val();
			let subRfc =$("#psubrfc").val();
			let subPorcen = $("#psubporcentaje").val();			
			let sucursal = $("#psucursal").val();
			let puesto = $("#ppuesto").val();
			let contrato =$("#ptipocontrato").val();
			let tiempocontrato =$("#ptiempocontrato").val();
			let inicioLab = $("#piniciolaboral").val();
			let sindicalizado = $("#psindicalizado").val();
			let regimen = $("#ptiporegimen").val();
			let riesgo = $("#priesgopuesto").val();
			let periodoPago = $("#pperiodicidadpago").val();
			let banco = $("#pbanco").val();
			let religion = $("#religion").val()
			let tieneHijos = $(".hasChild:checked").val() == 0 ? false : true;
			let nHijo = 0;
			let nHija = 0;
			if ( tieneHijos ) {
				nHijo = $("#nHijos").val();
				nHija = $("#nHijas").val();
			}
			let escolaridad  = $("#selEscolaridad").val();
			if ( escolaridad == 'OTRO') {
				escolaridad = $("#txtescolaridad").val();
			}

			let tieneSeguro = $(".hasSeguro:cheked").val();
			let tipoSangre = $("#tipoSangre").val();
			let alergias = $("#alergiaMedica").val();

			$.post("ajax/ajaxempleado.php", {
				op:  'actualizar',
				empleado: empleado,
				rfc: rfc,
				nombre:nombre,
				curp:curp,
				nss:nss,
				nacimiento:nacimiento,
				religion:religion,
				nHijo: nHijo,
				nHija: nHija,
				escolaridad: escolaridad,
				seguro: tieneSeguro,
				tipoSangre: tipoSangre,
				alergias: alergias,
				edoCivil: edoCivil,
				sexo:sexo,
				email:email,
				telefono:telefono,
				celular:celular,
				calle:calle,
				numCasa:numCasa,
				interior:interior,
				colonia:colonia,
				codPostal:codPostal,
				municipio: municipio,
				estado:estado,
				username:username,
				password:password,
				tipo:tipo,
				inicioLabores: inicioLab,
				salarioBase:salarioBase,
				salarioDiario:salarioDiario,
				cuenta:cuentaBancaria,
				subRfc:subRfc,
				subPorcen: subPorcen,
				sucursal: sucursal,
				puesto:puesto,
				contrato:contrato,
				tiempocontrato:tiempocontrato,
				sindicalizado:sindicalizado,
				regimen:regimen,
				riesgo:riesgo,
				periodoPago:periodoPago,
				banco: banco
			},
				function (data, textStatus, jqXHR) {
					
				},
				"text"
			);

	}
	
//para cuando cambie el select de nivel de estudios y seleccione otro, muestra el input
$("#selEscolaridad").change(function (e) { 
	if ( $( this).val() == 'OTRO' ){
		$("#otrosEstudios").fadeIn();
	}else{
		$("#otrosEstudios").fadeOut();
		$("#txtescolaridad").val("");
	}
	
});

//Para cuando cambian el radio de tiene hijos muestra el desgloce para  indicar el numeo de hijos en caso de que elija SI
$(".hasChild").change(function (e) { 
	if ( $(this).val() == 0 ) {
		$("#formHijos").fadeOut();
		$("#nHijas").val('');
		$("#nHijos").val('');
	} else {
		$("#formHijos").fadeIn();
	}
	
});

	function loadmodal(nip){
		$("#busquedaEmpleados").modal('toggle');
		cargar(nip);
		
	}

	//cuando den clic en la imagen abrirá el buscador de archivos para actualizarla en el servidor
	$("#previewfoto").click(function (e) { 
		e.preventDefault();
		const idEmpleado = $("#pnip").val();
		if ( idEmpleado != '') {
			$("#takePhoto").click();
		} else {
			alert("Debes seleccionar un empleado primero");
		}
		
	});

	$("#takePhoto").change(function() {
			const idEmpleado = $("#pnip").val();
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("takePhoto").files[0]);

            oFReader.onload = function (oFREvent) {
                
                document.getElementById("previewfoto").src = oFREvent.target.result;
				//Guardando en  el servidor la imagen del empleado
				let fotoIn = document.getElementById("takePhoto");
				let foto = fotoIn.files[0];
					let data = new FormData();
					data.append("foto", foto);
					data.append('nip', idEmpleado);
					$.ajax({
					url:"/intranet/trabajadores/fotografiar/save",
					type:'POST',
					contentType:false,
					data:data,
					processData:false,
					cache:false}).done(function(resp1){		
						if ( resp1 == 1) {
							alert("Fotografía almacenada correctamente");
						} else {
							alert("No se pudo guardar la imagen");
						}
					});

            };

        });


	function cargar(nip){
		$.post("ajax/ajaxempleado.php?op=cargar", "nip="+nip, function(resp){
			console.log(resp);
			$(".btn-guarda").attr('href','javascript:actualizar();')
			var row = eval('('+resp+')');
			//Datos de pempleado
			$("#pnip").val(row.nip);
			$("#prfc").val(row.rfc);
			$("#pnombre").val(row.nombre);
			$("#pcurp").val(row.curp);			
			$("#pnss").val(row.nss);
			$("#pfecnac").val(formatearFecha(row.fechanac));
			calculaTiempo('pfecnac','pedad','edad');
			$("#pedocivil").val(row.edocivil);
			$("#psexo").val(row.sexo);
			$("#pemail").val(row.email);
			$("#ptelefono").val(row.telefono);
			$("#pcelular").val(row.celular);
			$("#previewfoto").attr('src', row.foto);//intranet/assets/images/person.png
			//Datos  de sangre, escolaridad y familia
				$("#religion").val( row.religion);
				let hijos = (row.numhijos).split("-");
				if ( hijos[0] != 0 || hijos[1] != 0) {
					$(".hasChild[value=1]").attr( 'checked' , true );
					$("#formHijos").fadeIn();
					$("#nHijas").val( hijos[1] );
					$("#nHijos").val( hijos[0] );
				}else{
					$(".hasChild[value=0]").attr( 'checked' , true );
					$("#formHijos").fadeOut();
					$("#nHijas").val( '0' );
					$("#nHijos").val('0');
				}
				const escolaridades = ['NINGUNO','PRIMARIA','SECUNDARIA','PREPARATORIA','LICENCIATURA','MAESTRIA','DOCTORADO','´POSGRADO'];
				if ( escolaridades.includes( row.nivelestudios) ) {
					$(`#selEscolaridad option[value="${row.nivelestudios}"]`).prop('selected', true);
					$("#otrosEstudios").fadeOut();
				}else{
					$(`#selEscolaridad option[value="OTRO"]`).prop('selected', true);
					$("#otrosEstudios").fadeIn();
					$("#txtescolaridad").val( row.nivelestudios);
				}
				if ( row.asegurado == 's') {
					$(".hasSeguro[value=1]").attr( 'checked' , true );
				} else {
					$(".hasSeguro[value=0]").attr( 'checked' , true );
				}

				$("#tipoSangre").val( row.tiposangre);
				$("#alergiaMedica").val( row.alergias);

			//Datos de pdireccion
			$("#pcalle").val(row.calle);
			$("#pnumext").val(row.numext);
			$("#pnumint").val(row.numint);
			$("#pcolonia").val(row.colonia);
			$("#pcp").val(row.cp);
			comboCatalogoS('p','estado',row.estado);
			$("#pmunicipio").val(row.municipio);
			//Datos de pcontrato
			comboCatalogoS('p','sucursal',row.idsucursal);
			comboCatalogoS('p','departamento',row.iddepartamento);
			comboCatalogoS('p','puesto',row.idpuesto,'departamento',row.iddepartamento);
			comboCatalogoS('p','tipocontrato',row.idtipocontrato);
			comboCatalogoS('p','tiempocontrato',row.idtiempocontrato);
			comboCatalogoS('p','tipojornada',row.idtipojornada);
			comboCatalogoS('p','tiporegimen',row.idtiporegimen);
			comboCatalogoS('p','riesgopuesto',row.idriesgopuesto);
			comboCatalogoS('p','periodicidadpago',row.idperiodicidadpago);
			comboCatalogoS('p','banco',row.banco);
			$("#piniciolaboral").val(formatearFecha(row.fechainiciolab));
			calculaTiempo('piniciolaboral','pantiguedad','antiguedadSAT');
			$("#psalariobase").val(row.salariobase);
			$("#psalariodiario").val(row.salariodiario);
			$("#pcuentabancaria").val(row.cuentabancaria);
			$("#psubrfc").val(row.subrfc);
			$("#psubporcentaje").val(row.subporcentaje);
			$("#pusuario").val(row.username);
			$("#ppassword").val(row.password);
			$("#ppasswordr").val(row.password);			
			$("#ptipo").val(row.tipo);
			getDoctos();
		});
	}
	
	function eliminar(){
		var folio = $("#pnip").val();
		let confirmarBaja = confirm("¿Desea dar de baja al empleado? ");
		
		if ( confirmarBaja ) {
				$("#modBajaPersonal").modal('show');

		}else{
			if(confirm("¿Desea eliminar el empleado No."+folio+"?")){
				var params = "nip="+folio;
				bajasPersonal( params );
			}
		}
	}
	
	$("#btnBajaPersonal").click(function (e) { 
		let fecha = $("#dateBajaPersonal").val();
		let folio = $("#pnip").val();
		let txtobservaciones = $("#txtBajaPersonal").val();
		
		if ( fecha != '') {
			fecha = formatDBDate( fecha );
			$.post("/nomina/ajax/ajaxempleado.php", {
				op: 'baja',
				empleado: folio,
				fecha: fecha,
				tipo: 'baja',
				observaciones: txtobservaciones
			},
				function (data, textStatus, jqXHR) {
					if ( data == 1) {
						alert("Personal dado de baja correctamente.");
						nuevo();
					} else {
						alert("No se pudo realizar la baja del trabajador.");
					}
					$("#modBajaPersonal").modal('hide');
					$("#dateBajaPersonal").val('');
				},
				"text"
			);				
		}
		
	});
	
	function bajasPersonal(params) {
		$.post("ajax/ajaxempleado.php?op=eliminar", params, function(resp){
	
				if(resp==1){
					alert("El empleado se desactivo correctamente!");
					nuevo();					
				}
				if(resp==0){
					alert("Ocurrió un error, intente nuevamente. Si el problema persiste contacte a soporte");
				}
			});	
	}
	
	function existuser(){
		$.post("ajax/ajaxempleado.php?op=existuser", "username="+$("#pusuario").val()+"&nip="+$("#pnip").val(), function(resp){
			//alert(resp);
			if(resp==1)
				return true;
			else
				return false;
		});
	}

	function guardar(){
		$.post("ajax/ajaxempleado.php?op=existuser", "username="+$("#pusuario").val()+"&nip="+$("#pnip").val(), function(resp){
			//alert(resp);
			if(resp!=1){
				alert("El nombre de usuario que intenta registrar ya existe!");
				return false;
			}
		});
		
		var validacion = validar(camposTXT);
		var pass1 = $("#ppassword").val();
		var pass2 = $("#ppasswordr").val();
		if(validacion == true && pass1==pass2){			
			var params = "nip=" + $("#pnip").val();
			//Datos que se van a pempleado
			params+= "&rfc=" + $("#prfc").val();
			params+= "&nombre=" + $("#pnombre").val();			
			params+= "&curp=" + $("#pcurp").val();
			params+= "&nss=" + $("#pnss").val();
			params+= "&fecnac=" + $("#pfecnac").val();
			params+= "&edocivil=" + $("#pedocivil").val();
			params+= "&sexo=" + $("#psexo").val();
			params+= "&telefono=" + $("#ptelefono").val();
			params+= "&celular=" + $("#pcelular").val();
			params+= "&email=" + $("#pemail").val();
			//Datos que se van a pdireccion
			params+= "&calle=" + $("#pcalle").val();
			params+= "&numext=" + $("#pnumext").val();
			params+= "&numint=" + $("#pnumint").val();
			params+= "&colonia=" + $("#pcolonia").val();
			params+= "&cp=" + $("#pcp").val();
			params+= "&estado=" + $("#pestado").val();
			params+= "&municipio=" + $("#pmunicipio").val();
			//Datos de usuario
			params+= "&username=" + $("#pusuario").val();
			params+= "&password=" + $("#ppassword").val();
			params+= "&tipo=" + $("#ptipo").val();
			//Datos que se van a pcontrato
			params+= "&sucursal=" + $("#psucursal").val();
			params+= "&departamento=" + $("#pdepartamento").val();
			params+= "&puesto=" + $("#ppuesto").val();
			params+= "&tipocontrato=" + $("#ptipocontrato").val();
			params+= "&tiempocontrato=" + $("#ptiempocontrato").val();
			params+= "&tipojornada=" + $("#ptipojornada").val();
			params+= "&iniciolaboral=" + $("#piniciolaboral").val();
			params+= "&sindicalizado=" + $("#psindicalizado").val();
			params+= "&tiporegimen=" + $("#ptiporegimen").val();
			params+= "&riesgopuesto=" + $("#priesgopuesto").val();
			params+= "&periodicidadpago=" + $("#pperiodicidadpago").val();
			params+= "&salariobase=" + $("#psalariobase").val();
			params+= "&salariodiario=" + $("#psalariodiario").val();
			params+= "&banco=" + $("#pbanco").val();
			params+= "&cuentabancaria=" + $("#pcuentabancaria").val();
			params+= "&subrfc=" + $("#psubrfc").val();
			params+= "&subporcentaje=" + $("#psubporcentaje").val();
			let religion = $("#religion").val()
			let tieneHijos = $(".hasChild:checked").val() == 0 ? false : true;
			let nHijo = 0;
			let nHija = 0;
			if ( tieneHijos ) {
				nHijo = $("#nHijos").val();
				nHija = $("#nHijas").val();
			}
			let escolaridad  = $("#selEscolaridad").val();
			if ( escolaridad == 'OTRO') {
				escolaridad = $("#txtescolaridad").val();
			}

			let tieneSeguro = $(".hasSeguro:checked").val();
			let tipoSangre = $("#tipoSangre").val();
			let alergias = $("#alergiaMedica").val();
			params += `&religion=${religion}&nHijo=${nHijo}&nHija=${nHija}&escolaridad=${escolaridad}&seguro=${tieneSeguro}&tipoSangre=${tipoSangre}&alergias=${alergias}`;

			
			$.post("ajax/ajaxempleado.php?op=guardar", params, function(resp){
				//alert(resp);
				if(resp>0){
					alert("El registro se guardo exitosamente!");
					//imprimir();
					cargar(resp);
					//$("#busquedaEmpleados").modal('toggle');
				}else
					alert("Ocurrió un error, intente nuevamente. Si el problema persiste contacte a soporte");
			});
		}else{
			if(pass1!=pass2)
				alert("Las contraseñas de usuario no coinciden. Vuelva a escribirlas.");
			else
				alert("Llene los campos requeridos");
		}
	}
	
	function validar(campos){
		var res = true;
		for(a in campos){
			var arr = campos[a].split("_");
			//alert(arr[1]);
			if(arr[1] == "txt"){
				var campo = $("#"+arr[0]).val();
				if(campo==""){
					 $("#"+arr[0]).addClass('bordeRojo');
					 res = false;
				}else{
					 $("#"+arr[0]).removeClass('bordeRojo');
				}
			}
		}
		return res;
	}
	
	function reiniciaTXT(campos){
		var res = true;
		for(a in campos){
			var arr = campos[a].split("_");
			//alert(arr[1]);
			if(arr[1] == "txt"){
				var campo = $("#"+arr[0]).val();
				$("#"+arr[0]).removeClass('bordeRojo');
				$("#"+arr[0]).val('');				
			}else{
				$("#"+arr[0]).val('');
			}
		}
		return res;
	}
	
	function imprimir(){
		var folio = $("#folio").html();
		window.open("impIngreso.php?folio="+folio,"_blank");
		//alert("Ha ocurrido un problema con la configuracion de la impresion");	
	}
	
	function comboCatalogoS(prefijo,catalogo,valor,padre,valorpadre){
		var id = "";
		if(typeof padre != 'undefined'){
			$.post("ajax/ajaxempresa.php?op=comboSelected", "catalogo="+catalogo+"&valor="+valor+"&padre="+padre+"&valorpadre="+valorpadre, function(resp){
				//alert(resp);
				$("#"+prefijo+""+catalogo).html(resp);
			});
		}else{
			$.post("ajax/ajaxempresa.php?op=comboSelected", "catalogo="+catalogo+"&valor="+valor, function(resp){
				//alert(resp);
				$("#"+prefijo+""+catalogo).html(resp);
			});
		}
	}
	
	// Scripts extras agregados 07.01.19
	$("#actAdscripcion").click(function (e) { 

		$("#modCambioAdscripcion").modal('show');
	});

	$("#btnCambiaAds").click(function (e) { 
		let sucursal = $("#psucursal").val();
		let departamento = $("#pdepartamento").val();
		let puesto = $("#ppuesto").val();
		let nip = $("#pnip").val();
		let fecha =  $("#dateCambioAds").val();

		$.post("/nomina/ajax/ajaxempleado.php", {
			op: 'updateAdscripcion',
			sucursal: sucursal,
			departamento:  departamento,
			puesto: puesto,
			empleado: nip,
			fecha: fecha,
			tipo:'cambioAdscrip'
		},
			function (data, textStatus, jqXHR) {
				if ( data == 1) {
					alert("Cambio de adscripción realizada");
				} else if( data == 0 ) {
					alert("No se pudo efectuar el cambio, por favor intente nuevamete.")
				}else if( data == -1){
					alert("Cambio realizado correctamente, pero no se guardó el cambio de adscripción en el historial");
				}else{
					alert("Ocurrió un error interno en el servido, por favor contacte al personal encargado");
				}
				$("#modCambioAdscripcion").modal('hide');
			},
			"text"
		);
		
	});
	// Fin scripts 07.01.19
	
	function getDoctos(){
		//alert("Entra");
		var nip = $("#pnip").val();
		//alert("marca="+marca+"&linea="+modelo+"&modelo="+anio+"&sucursal="+sucursal);
		$.post("ajax/ajaxempleado.php?op=getDoctos", "nip="+nip, function(resp){
			//alert(resp);
			$("#documentos").html(resp);
		});
	}

	function delImg(id){
		if(confirm("¿Esta seguro de eliminar la imagen?")){
			$.post("ajax/ajaxempleado.php?op=deldocto", "id="+id, function(resp){
				//alert(resp);
				getDoctos()
			});
		}
	}
	
	function subirDoc(){
		var tipo = $("#ptipodoc").val();
		var nip = $("#pnip").val();
		if(nip>0){
			var inputFileImage = document.getElementById("parchivo");
			var file = inputFileImage.files[0];
			var data = new FormData();
			data.append('archivo',file);
			data.append('tipo',tipo);
			data.append('nip',nip);
			var url = "ajax/uploadDocto.php";
			$.ajax({
				url:url,
				type:'POST',
				contentType:false,
				data:data,
				processData:false,
				cache:false}).done(function(resp1){		
					//alert(resp1);
					getDoctos();
				});
		}else{
			alert("Debe seleccionar primero un empleado");
		}
	}

	function formatearFecha(fecha){
		fec = fecha.split("-");
		return fec[2]+"/"+fec[1]+"/"+fec[0];	
	}

	function formatDBDate(fecha) {
		fec = fecha.split("/");
		return fec[2]+"-"+fec[1]+"-"+fec[0];	
	}
	
</script>
</body>
</html>
