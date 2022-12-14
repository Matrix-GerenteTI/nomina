<?php
	require_once("ajax/control.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Matrix Nómina</title>
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
	<script src="js/vue.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.js"></script>
	<script src="assets/components/tabla-organigrama.js"></script>
</head>
<body>
<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container"> <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"><span
                    class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span> </a><a class="brand" href="index.html"><i class="icon-book"></i>&nbsp;Timbrado de Nomina 1.2 </a>
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
		<li><a href="socioeconomicos.php"><i class="icon-book"></i><span>Socioecónomicos</span></a></li>
		<li ><a href="vacaciones.php"> <i class="icon-plane"></i><span>Vacaciones</span></a></li>
		<li><a href="incidencias.php"><i class="icon-calculator"></i><span>Incidencias</span> </a> </li>
		<li class="active"><a href="empresa.php"><i class="icon-building"></i><span>Empresa</span> </a> </li>
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
            <div class="form-actions" style="text-align:right; margin-top:0px; margin-bottom:0px; padding:5px">
            	<table align="right" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td align="right">
                            <div class="shortcuts"> 
                               <!-- <a href="javascript:nuevo();" class="shortcut"><i class="shortcut-icon icon-file-alt"></i><span class="shortcut-label">Nuevo</span></a>&nbsp;-->
                                <a href="javascript:guardar();" class="shortcut"><i class="shortcut-icon icon-save"></i><span class="shortcut-label">Guardar</span></a>&nbsp;
                                <!--<a href="javascript:imprimir();" class="shortcut"><i class="shortcut-icon icon-print"></i><span class="shortcut-label">Imprimir</span></a>&nbsp;-->
                                <!--<a href="javascript:eliminar();" class="shortcut"><i class="shortcut-icon icon-remove"></i><span class="shortcut-label">Eliminar</span></a>-->
                            </div>
                      	</td>
                   	</tr>
              	</table>
            </div>
            <div class="widget-header"> <i class="icon-edit"></i>
              <h3> Datos de la empresa</h3>
            </div>
            <!-- /widget-header -->
            <div class="widget-content" style="padding:10px">
                <table width="100%">
                    <tr>
						<td width="15%">
                        	RFC<br/>
                            <input type="text" id="erfc" style="width:90%" />
                       	</td>
                        <td>
                        	Nombre<br/>
                            <input type="text" id="enombre" style="width:97%" />
                       	</td>
						<td width="15%">
                        	CURP<br/>
                            <input type="text" id="ecurp" style="width:90%" />
                       	</td>
						<td width="20%">
                        	Registro patronal<br/>
                            <input type="text" id="eregistropatronal" style="width:92%" />
                       	</td>
                    </tr>
                </table>
              	<table width="100%">
                    <tr>
                        <td>
                        	Calle<br/>
                            <input type="text" id="ecalle" style="width:95%" />
                       	</td>
						<td width="10%">
                        	Numero Ext.<br/>
                            <input type="text" id="enumext" style="width:87%" />
                       	</td>
						<td width="10%">
                        	Numero Int.<br/>
                            <input type="text" id="enumint" style="width:87%" />
                       	</td>
						<td width="30%">
                        	Colonia<br/>
                            <input type="text" id="ecolonia" style="width:95%" />
                       	</td>
						<td width="10%">
                        	C.P.<br/>
                            <input type="text" id="ecp" style="width:85%" />
                       	</td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
						<td>
                        	Estado<br/>
                            <select id="eestado" style="width:92%">
                            </select>
                       	</td>
						<td>
                        	Municipio<br/>
                            <input type="text" id="emunicipio" style="width:92%" />
                       	</td>
                        <td>
                        	Correo electr&oacute;nico<br/>
                            <input type="text" id="eemail" style="width:92%" />
                       	</td>
                        <td>
                        	Tel&eacute;fono<br/>
                            <input type="text" id="etelefono" style="width:92%" />
                       	</td>
                    </tr>
                </table><table width="100%">
                    <tr>
						<td>
                        	R&eacute;gimen fiscal<br/>
                            <select id="eregimenfiscal" style="width:92%">
                            </select>
                       	</td>
                    </tr>
                </table>
            </div>
            <div class="form-actions" style="text-align:right; margin-top:0px; margin-bottom:0px; padding:5px">
            	<table align="right" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td align="right">
                            <div class="shortcuts"> 
                               <!-- <a href="javascript:nuevo();" class="shortcut"><i class="shortcut-icon icon-file-alt"></i><span class="shortcut-label">Nuevo</span></a>&nbsp;
                                <a href="javascript:guardar();" class="shortcut"><i class="shortcut-icon icon-save"></i><span class="shortcut-label">Guardar</span></a>&nbsp;-->
                                <!--<a href="javascript:imprimir();" class="shortcut"><i class="shortcut-icon icon-print"></i><span class="shortcut-label">Imprimir</span></a>&nbsp;-->
                                <!--<a href="javascript:eliminar();" class="shortcut"><i class="shortcut-icon icon-remove"></i><span class="shortcut-label">Eliminar</span></a>-->
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
	  <div class="row">
        <div class="span6">
					<div class="row">
						<div class="span6">
							<div class="widget">
								<div class="widget-header">
									<i class="icon-sitemap"></i>
									<h3>Departamentos</h3>
								</div>
								<div class="widget-content" style="padding:10px">
									<table width="100%" cellpadding="0" cellspacing="0">
										<tr>
									    	<td>Departamento
													<input type="text" id="ddescripcion" style="width:97%" />
												</td>
												<td align="right">
													<table cellpadding="0" cellspacing="0" align="right">
														<tr>
															<td>
																<div id="botones" class="shortcuts"> 
																	<a href="javascript:guardarDepto();" class="shortcut"><i class="shortcut-icon icon-plus"></i><span class="shortcut-label">Agregar</span></a>&nbsp;
																	<a href="javascript:eliminar('d','departamento');" class="shortcut"><i class="shortcut-icon icon-remove"></i><span class="shortcut-label">Eliminar</span></a>
																</div>															
															</td>
														</tr>
													</table>
												</td>
										</tr>
									</table>
									<table class="table table-striped table-bordered">
										<thead>
										   <thead>
											 		<tr>
													 	<th width="10px"><input type="checkbox" id="chkall" name="chkall"></th>
														 <th> Departamento </th>
													</tr>
											 </thead>
											 <tbody id="tbodyDeptos">
												 
											 </tbody>
										</thead>
									</table>
									<div id="paginacion" style="text-align:right"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="row" id="puestosSuperiores">
						<div class="span6">
							<div class="widget">
								<div class="widget-header">
									<i class="icon-sitemap"></i>
									<h3>Puestos Inmediatos Superiores</h3>								
								</div>
								<div class="widget-content">
									<table width="100%" cellpadding="0" cellspacing="0">					
										<tr>
											<td>
											  Departamento p. superior<br>
											  <select class="form-control" v-model="deptoSuperior" >
												  <option value="-1">Selecciona un puesto</option>
												  <option v-for="departamento in departamentos" :value="departamento.id">{{departamento.descripcion}}</option>
											  </select>
											</td>
										   <td>
											    Puesto Superior<br>
													<select class="form-control" v-model="superior">
														<option value="-1">Selecciona un puesto</option>
														<option v-for="puesto in puestosSup" :value="puesto.id">{{puesto.descripcion}}</option>
													</select>
											 </td>
										</tr>
										<tr>
											<td>
											   Departamento p. dependiente<br>
											   <select class="form-control" v-model="deptoDependiente">
												   <option value="-1">Selecciona un puesto</option>
												   <option v-for="departamento in departamentos" :value="departamento.id">{{departamento.descripcion}}</option>
											   </select>
											</td>
											 <td>
											    Puesto dependiente<br>
													<select class="form-control" v-model="dependiente">
														<option value="-1">Selecciona un puesto</option>
														<option v-for="puesto in puestosDep" :value="puesto.id">{{puesto.descripcion}}</option>
													</select>											 
											 </td>
											 <td>
													<div id="botones" class="shortcuts"> 
														<a href="" @click.prevent="agregar" class="shortcut"><i class="shortcut-icon icon-plus"></i><span class="shortcut-label">Agregar</span></a>&nbsp;
													</div>													 
											 </td>
										</tr>				
										<tr>
											<td><b>Aplica a sucursal:</b></td>
											<td>  <input type="checkbox" v-model="aplicaSucursal"  ></td>
											<td></td>
										</tr>
										<tr v-show="aplicaSucursal" >
												<td> <b>Selecciona Sucursal</b> </td>
												<td>
														<select v-model="sucursales" >
															<option value="-1">Selecciona sucursal</option>
															<option v-for="sucursal in listaSucursales"  :value="sucursal.id">{{sucursal.descripcion}}</option>
														</select>
												</td>
										</tr>
										<tr>
											<td><b>Aplica a organigrama:</b></td>
											<td><input type="checkbox" v-model="aplicacionOrg" value="Dir">&nbsp;Dirección&ensp;
											<input type="checkbox" v-model="aplicacionOrg" value="Ger">&nbsp;Gerencial&ensp;
											<input type="checkbox" v-model="aplicacionOrg" value="Jef">&nbsp;Jefaturas&ensp;</td>
										</tr>
									</table>
									<br><br>
									<table class="table table-striped table-bordered">
										<thead>
										   <thead>
											 		<tr>
													 	<th width="10px"></th>
														 <th> Puesto </th>
														 <th>Puesto Superior</th>
														 <th></th>
													</tr>
											 </thead>
											 <tbody is="tr-organigrama" :puestos="puestosOrg">
											 </tbody>
										</thead>
									</table>									
								</div>
							</div>
						</div>
					</div>
     </div>
		<div class="span6">
          <div class="widget">			
            <div class="widget-header"> <i class="icon-suitcase"></i>
              <h3> Puestos</h3>
            </div>			
            <div class="widget-content" style="padding:10px">
			  <table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td>
							Departamento<br/>
							<select id="pdepartamento" style="width:92%">
							</select>
						</td>
						<td>
							Puesto<br/>
							<input type="text" id="pdescripcion" style="width:97%" />
						</td>
						<td align="right">
							<table cellpadding="0" cellspacing="0" align="right">
								<tr>
									<td>
											<table cellpadding="0" cellspacing="0" align="right">
												<tr>
													<td>
														<div id="botones" class="shortcuts"> 
															<a href="javascript:guardarPuesto();" class="shortcut"><i class="shortcut-icon icon-plus"></i><span class="shortcut-label">Agregar</span></a>&nbsp;
															<a href="javascript:eliminar('p','puesto');" class="shortcut"><i class="shortcut-icon icon-remove"></i><span class="shortcut-label">Eliminar</span></a>
														</div>															
													</td>
												</tr>
											</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
           	  <table class="table table-striped table-bordered">
                <thead>
                  <tr>
					<th width="10px"><input type="checkbox" id="chkall" name="chkall"></th>
                  	<th> Departamento </th>
					<th> Puesto </th>
                  </tr>
                </thead>
                <tbody id="tbodyPuestos">
                  
                </tbody>
              </table>
			  <div id="paginacion" style="text-align:right"></div>
            </div>
          </div>
          <!-- /widget -->
        </div>
        <!-- /span6 -->
      </div>
    </div>
    <!-- /container --> 
  </div>
  <!-- /main-inner --> 
</div>
<!-- /main  -->
<!-- /footer --
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
	var camposTXT = new Array('erfc_txt',
							   'enombre_txt',
							   'eregistropatronal_txt',
							   'ecalle_txt',
							   'enumext_txt',
							   'enumint',
							   'ecolonia_txt',
							   'ecp_txt',
							   'emunicipio_txt',
							   'eemail',
							   'etelefono');
	$(document).ready(function(e) {
		cargaEmpresa();
		comboCatalogo('p','departamento',1);
		lista('Deptos');
		lista('Puestos');
    });
	
	
	
	function cargaEmpresa(){
		$.post("ajax/ajaxempresa.php?op=carga", "", function(resp){
			//alert(resp);
			var row = eval('('+resp+')');
			
			reiniciaTXT(camposTXT);
			$("#erfc").val(row.rfc);
			$("#enombre").val(row.nombre_razsoc);
			$("#ecurp").val(row.curp);
			$("#eregistropatronal").val(row.registropatronal);
			$("#ecalle").val(row.calle);
			$("#enumext").val(row.numext);
			$("#enumint").val(row.numint);
			$("#ecolonia").val(row.colonia);
			$("#ecp").val(row.cp);
			comboCatalogoS('e','estado',row.estado);
			$("#emunicipio").val(row.municipio);
			$("#eemail").val(row.email);
			$("#etelefono").val(row.telefono);			
			comboCatalogoS('e','regimenfiscal',row.idregimenfiscal);
		});
	}
	
	function lista(id){
		if(id=='Deptos'){
			$.post("ajax/ajaxempresa.php?op=listaDeptos", "", function(resp){
				//alert(resp);
				var row = eval('('+resp+')');
				var echo = "";
				var n=0;
				for(i in row){
					echo+= "<tr>";
					echo+= "	<td><input type='checkbox' id='d-"+row[i].id+"' name='d-"+row[i].id+"' /></td>";
					echo+= "	<td>"+row[i].descripcion+"</td>";
					echo+= "<tr>";
					n++;
				}
				$("#tbodyDeptos").html(echo);
			});	
		}
		if(id=='Puestos'){
			$.post("ajax/ajaxempresa.php?op=listaPuestos", "", function(resp){
				//alert(resp);
				var row = eval('('+resp+')');
				var echo = "";
				var n=0;
				for(i in row){
					echo+= "<tr>";
					echo+= "	<td><input type='checkbox' id='p-"+row[i].id+"' name='p-"+row[i].id+"' /></td>";
					echo+= "	<td>"+row[i].departamento+"</td>";
					echo+= "	<td>"+row[i].puesto+"</td>";
					echo+= "<tr>";
					n++;
				}
				$("#tbodyPuestos").html(echo);
			});	
		}
	}
	
	function guardarDepto(){
		var descripcion = $("#ddescripcion").val();
		alert("A")
		if(descripcion.trim()!=""){
			var params = "descripcion=" + descripcion;
			
			$.post("ajax/ajaxempresa.php?op=guardaDepto", params, function(resp){
				//alert(resp);
				if(resp>0){
					alert("El registro se guardo exitosamente!");
					$("#ddescripcion").val('');
					lista('Deptos');
					comboCatalogo('p','departamento',1);
				}else
					alert("Ocurrió un error, intente nuevamente. Si el problema persiste contacte a soporte");
			});
		}
	}
	
	function guardarPuesto(){
		var descripcion = $("#pdescripcion").val();
		if(descripcion.trim()!=""){
			var params = "descripcion=" + descripcion;
			params+= "&iddepartamento=" + $("#pdepartamento").val();
			$.post("ajax/ajaxempresa.php?op=guardaPuesto", params, function(resp){
				//alert(resp);
				if(resp>0){
					alert("El registro se guardo exitosamente!");
					$("#pdescripcion").val('');
					comboCatalogo('p','departamento',1);
					lista('Puestos');
				}else
					alert("Ocurrió un error, intente nuevamente. Si el problema persiste contacte a soporte");
			});
		}
	}
	
	function eliminar(prefijo,tabla){
		$("input:checkbox:checked").each(function() {
			 var id = $(this).attr('name');
			 //var arreglo = new Array();
			 if(id!='chkall'){
				 arreglo = id.split("-");
				 if(arreglo[0]==prefijo){
					
					$.post("ajax/ajaxempresa.php?op=eliminaItem", "tabla="+tabla+"&id="+arreglo[1], function(resp){
						//alert(resp);
						if(resp>0){
							alert("El registro se eliminó exitosamente!");
							if(prefijo=='d')
								lista('Deptos');
							if(prefijo=='p')
								lista('Puestos');							
						}else
							alert("Ocurrió un error, intente nuevamente. Si el problema persiste contacte a soporte");
					});
				 }
			 }
		});
	}
	
	function guardar(){
		var validacion = validar(camposTXT);
						
		if(validacion == true){			
			var params = "rfc=" + $("#erfc").val();
			params+= "&nombre=" + $("#enombre").val();
			params+= "&curp=" + $("#ecurp").val();
			params+= "&registropatronal=" + $("#eregistropatronal").val();
			params+= "&telefono=" + $("#etelefono").val();
			params+= "&email=" + $("#eemail").val();
			params+= "&regimenfiscal=" + $("#eregimenfiscal").val();
			//Datos que se van a pdireccion
			params+= "&calle=" + $("#ecalle").val();
			params+= "&numext=" + $("#enumext").val();
			params+= "&numint=" + $("#enumint").val();
			params+= "&colonia=" + $("#ecolonia").val();
			params+= "&cp=" + $("#ecp").val();
			params+= "&estado=" + $("#eestado").val();
			params+= "&municipio=" + $("#emunicipio").val();
			
			$.post("ajax/ajaxempresa.php?op=guardar", params, function(resp){
				//alert(resp);
				if(resp>0){
					alert("El registro se guardo exitosamente!");
					cargaEmpresa();
				}else
					alert("Ocurrió un error, intente nuevamente. Si el problema persiste contacte a soporte");
			});
		}else{
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
	
	function comboCatalogoS(prefijo,catalogo,valor){
		var id = "";
		$.post("ajax/ajaxempresa.php?op=comboSelected", "catalogo="+catalogo+"&valor="+valor, function(resp){
			//alert(resp);
			$("#"+prefijo+""+catalogo).html(resp);
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

	//SCRIPTS CON VUE JS

	vmPuestos = new Vue({
		el:"#puestosSuperiores",
		data:{
			superior: -1,
			dependiente:-1,
			deptoSuperior:-1,
			deptoDependiente:-1,
			puestosSup: [],
			puestosDep: [],
			departamentos:[],
			puestosOrg: [],
			aplicacionOrg:[],
			aplicaSucursal: false,
			sucursales:-1,
			listaSucursales: []
		},
		watch:{
			aplicaSucursal: function () {  
				this.sucursales = -1;
			},
			deptoSuperior: function (id) {
				  axios.get('ajax/ajaxempresa.php',{params:{
					  departamento: id,
					  op:'listaPuestoDepartamento'
				  }}).then((result) => {
					  this.puestosSup = result.data
				  }).catch((err) => {
					  
				  });
				
			  },
			  deptoDependiente: function (id) {
				  axios.get('ajax/ajaxempresa.php',{params:{
					  departamento: id,
					  op:'listaPuestoDepartamento'
				  }}).then((result) => {
					  this.puestosDep = result.data
				  }).catch((err) => {
					  
				  });
			    }
		},
		methods:{
			fillSucursales: function () { 
				axios.get('ajax/ajaxempresa.php',{
					params:{
						op: "listaSucursañes"
					}
				}).then((result) => {
					this.listaSucursales = result.data;
				}).catch((err) => {
					
				});
			 },
			fillPuestos: function () {
				axios.get('ajax/ajaxempresa.php',{params:{
					op: 'listaPuestos'
				}}).then((result) => {
					this.puestos = result.data;
					console.log(this.puestos)
				}).catch((err) => {
					
				});
			  },
			  fillDepartamento: function () { 
				  axios.get('ajax/ajaxempresa.php',{params:{
					  op: "listaDeptos",
				  }}).then((result) => {
					  this.departamentos = result.data
				  }).catch((err) => {
					  
				  });
			   },
			agregar: function () { 
				axios.post('ajax/ajaxempresa.php',{
					op: "addPuestoSuperior",
					puestoDep: this.dependiente,
					dptoSup: this.deptoSuperior,
					dptoDep:this.deptoDependiente,
					puestoSup: this.superior,
					abstraccion: this.aplicacionOrg,
					sucursal: this.sucursales
				}).then((result) => {
					if ( Array.isArray( result.data) ) {
						this.puestosOrg = result.data
					} else {
						alert('No se puede realizar la operación, por favor revise la relación padre-hijo');
					}
				}).catch((err) => {
					
				});

			 },
			getOrganigrama: function(){
				axios.get("ajax/ajaxempresa.php",{ params:{
					op: 'getOrganigrama'
					}
				}).then((result) => {
						this.puestosOrg = result.data					
				}).catch((err) => {
					
				});
			}
		}
	})

	vmPuestos.fillPuestos();
	vmPuestos.fillDepartamento();
	vmPuestos.getOrganigrama();
	vmPuestos.fillSucursales();

</script>
</body>
</html>

