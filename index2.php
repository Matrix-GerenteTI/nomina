<?php
	require_once("ajax/control.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Expediente - Control de Pacientes de Medicina Est&eacute;tica</title>
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
<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container"> <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"><span
                    class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span> </a><a class="brand" href="index.html"><i class="icon-user-md"></i>&nbsp;Control de Pacientes de Medicina Est&eacute;tica </a>
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
        <li class="active"><a href="index.php"><i class="icon-female"></i><span>Expediente</span> </a> </li>
        <li><a href="historial.php"><i class="icon-folder-open-alt"></i><span>Historial</span> </a></li>
        <li><a href="caja.php"><i class="icon-money"></i><span>Caja</span> </a> </li>
	<?php
        if($_SESSION['usertype']=='ADMINISTRADOR'){
    ?>
        <li><a href="reportes.php"><i class="icon-list-alt"></i><span>Reportes</span> </a> </li>
        <li><a href="usuarios.php"><i class="icon-user"></i><span>Usuarios</span> </a> </li>
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
                                <a href="javascript:nuevo();" class="shortcut"><i class="shortcut-icon icon-file-alt"></i><span class="shortcut-label">Nuevo</span></a>&nbsp;
                                <a href="javascript:guardar();" class="shortcut"><i class="shortcut-icon icon-save"></i><span class="shortcut-label">Guardar</span></a>&nbsp;
                                <a href="javascript:imprimir();" class="shortcut"><i class="shortcut-icon icon-print"></i><span class="shortcut-label">Imprimir</span></a>&nbsp;
                                <a href="javascript:eliminar();" class="shortcut"><i class="shortcut-icon icon-remove"></i><span class="shortcut-label">Eliminar</span></a>
                            </div>
                      	</td>
                   	</tr>
              	</table>
            </div>
            <div class="widget-header"> <i class="icon-edit"></i>
              <h3> Datos Personales</h3>
            </div>
            <!-- /widget-header -->
            <div class="widget-content" style="padding:10px">
            	<table width="100%" align="right">
                    <tr>
                    	<td width="40%">
                        	<table style="font-size:30px; color:#F00" cellpadding="0" cellspacing="0">
                            	<tr>
                                	<td>FOLIO:&nbsp;&nbsp;</td><td><div id="folio"></div></td>
                              	</tr>
                           	</table>
                        </td>
                         <td>
                        	Fecha de ingreso<br/>
                            <input type="text" id="fecIngreso" style="width:92%"/>
                       	</td>
                        <td>
                        	Doctor responsable<br/>
                            <select id="doctor" style="width:92%"></select>
                       	</td>
                        <td>
                        	Ciruj&iacute;a<br/>
                            <select id="cirugia" style="width:92%"></select>
                       	</td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td>
                        	Nombre del paciente<br/>
                            <input type="text" id="pnombre" style="width:97%" />
                       	</td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td>
                        	Fecha de nacimiento<br/>
                            <input type="text" id="pfecnac" style="width:92%" onChange="calculaEdad()" />
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
              	<table width="100%">
                    <tr>
                        <td>
                        	Domicilio particular<br/>
                            <input type="text" id="pdomicilio" style="width:97%" />
                       	</td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td>
                        	Tel&eacute;fono particular<br/>
                            <input type="text" id="ptelparticular" style="width:92%" />
                       	</td>
                        <td>
                        	Tel&eacute;fono de trabajo<br/>
                            <input type="text" id="pteltrabajo" style="width:92%" />
                       	</td>
                        <td>
                        	Celular<br/>
                            <input type="text" id="pcelular" style="width:92%" />
                       	</td>
                    </tr>
                </table>                
                <table width="100%">
                    <tr>
                        <td>
                        	Email<br/>
                            <input type="text" id="pemail" style="width:92%" />
                       	</td>
                        <td>
                        	Facebook<br/>
                            <input type="text" id="pfacebook" style="width:92%" />
                       	</td>
                        <td>
                        	Twitter<br/>
                            <input type="text" id="ptwitter" style="width:92%" />
                       	</td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td>
                        	Nombre del responsable<br/>
                            <input type="text" id="rnombre" style="width:92%" />
                       	</td>
                        <td>
                        	Tel&eacute;fono del responsable<br/>
                            <input type="text" id="rtelefono" style="width:92%" />
                       	</td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td>
                        	En caso de emergencia avisar a<br/>
                            <input type="text" id="avisara" style="width:92%" />
                       	</td>
                        <td>
                        	Tel&eacute;fonos<br/>
                            <input type="text" id="avisartelefonos" style="width:92%" />
                       	</td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td>
                        	Peso<br/>
                            <input type="text" id="peso" style="width:92%" onKeyUp="calculaIMC()" />
                       	</td>
                        <td>
                        	Talla<br/>
                            <input type="text" id="talla" style="width:92%" onKeyUp="calculaIMC()" />
                       	</td>
                        <td>
                        	IMC<br/>
                            <input type="text" id="imc" style="width:92%" readonly />
                       	</td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td>
                        	Operaciones y procedimiento<br/>
                            <input type="text" id="operyproc" style="width:97%" />
                       	</td>
                    </tr>
                </table>
            </div>
          </div>
          <div class="widget widget-nopad">
            <div class="widget-header"> <i class="icon-stethoscope"></i>
              <h3> Datos M&eacute;dicos</h3>
            </div>
            <!-- /widget-header -->
            <div class="widget-content" style="padding:10px">
             	<table width="100%">
                    <tr>
                        <td>
                        	¿En que fecha recibi&oacute; su &uacute;ltimo examen f&iacute;sico?<br/>
                            <input type="text" id="ultimoexamenfisico" style="width:92%" />
                       	</td>
                        <td>
                        	¿&Uacute;ltima toma de radiograf&iacute;a de t&oacute;rax?<br/>
                            <input type="text" id="ultimaradiografia" style="width:92%" />
                       	</td>
                        <td>
                        	¿En que fecha recibi&oacute; su &uacute;ltimo electrocardiograma?<br/>
                            <input type="text" id="ultimoelectrocardiograma" style="width:92%" />
                       	</td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td>
                        	<b>¿Qu&eacute; clase de anestesia ha recibido?</b>
                       	</td>
                        <td width="10%">
                        	<b>Si</b>
                       	</td>
                        <td width="10%">
                        	<b>No</b>
                       	</td>
                    </tr>
                    <tr>
                        <td>
                        	¿Raquia o bloqueo epidural?
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="0" name="anestesiaraquia" />
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="1" name="anestesiaraquia" />
                       	</td>
                    </tr>
                    <tr>
                        <td>
                        	¿Local?
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="0" name="anestesialocal" />
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="1" name="anestesialocal" />
                       	</td>
                    </tr>
                    <tr>
                        <td>
                        	¿General?
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="0" name="anestesiageneral" />
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="1" name="anestesiageneral" />
                       	</td>
                    </tr>
                    <tr>
                        <td>
                        	¿A presentado reacciones anormales?
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="0" name="anestesiareacciones" />
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="1" name="anestesiareacciones" />
                       	</td>
                    </tr>
                    <tr>
                        <td>
                        	¿A presentado fiebre en operaciones previas?
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="0" name="anestesiafiebre" />
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="1" name="anestesiafiebre" />
                       	</td>
                    </tr>
                </table>
                <br/>
                <table width="100%">
                    <tr>
                        <td>
                        	<b>Usted</b>
                       	</td>
                        <td width="10%">
                        	<b>Si</b>
                       	</td>
                        <td width="10%">
                        	<b>No</b>
                       	</td>
                    </tr>
                    <tr>
                        <td>
                        	¿Usa dientes postizos?
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="0" name="usteddientespostizos" />
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="1" name="usteddientespostizos" />
                       	</td>
                    </tr>
                    <tr>
                        <td>
                        	¿Le falta dientes o tiene dientes flojos?
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="0" name="usteddientesflojos" />
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="1" name="usteddientesflojos" />
                       	</td>
                    </tr>
                    <tr>
                        <td>
                        	¿Estan cubiertos de porcelana permanente sus dientes?
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="0" name="ustedcubiertosporcelana" />
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="1" name="ustedcubiertosporcelana" />
                       	</td>
                    </tr>
                    <tr>
                        <td>
                        	¿Se le dificulta abrir la boca o moverla?
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="0" name="ustedabrirboca" />
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="1" name="ustedabrirboca" />
                       	</td>
                    </tr>
                    <tr>
                        <td>
                        	¿Usa pesta&ntilde;as postizas?
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="0" name="ustedpestaniaspostizas" />
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="1" name="ustedpestaniaspostizas" />
                       	</td>
                    </tr>
                    <tr>
                        <td>
                        	¿Usa lentes de contacto?
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="0" name="ustedlentescontacto" />
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="1" name="ustedlentescontacto" />
                       	</td>
                    </tr>
                    <tr>
                        <td>
                        	¿Tiene defectos f&iacute;sicos mayores o cong&eacute;nitos?
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="0" name="usteddefectosfisicos" />
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="1" name="usteddefectosfisicos" />
                       	</td>
                    </tr>
                </table>
                <br/>
                <table width="100%">
                    <tr>
                        <td>
                        	<b>Medicamentos que emplea usted actualmente</b>
                       	</td>
                        <td width="3%">
                        	<b>Si</b>
                       	</td>
                        <td width="3%">
                        	<b>No</b>
                       	</td>
                        <td width="40%">
                        	
                       	</td>
                    </tr>
                    <tr>
                        <td valign="top">
                        	Antidepresivos
                       	</td>
                        <td valign="top" width="3%">
                        	<input type="radio" value="0" name="medantidepresivos" />
                       	</td>
                        <td valign="top" width="3%">
                        	<input type="radio" value="1" name="medantidepresivos" />
                       	</td>
                        <td valign="top" width="40%">
                        	<table width="100%" cellpadding="0" cellspacing="0">
                            	<tr>
                                	<td valign="top" width="5%">¿Cu&aacute;l?</td><td>&nbsp;&nbsp;<input type="text" style="width:92%" id="medantidepresivoscual"  /></td>
                               	</tr>
                            </table>
                       	</td>
                    </tr>
                    <tr>
                        <td valign="top">
                        	Antihipertensivas
                       	</td>
                        <td valign="top" width="3%">
                        	<input type="radio" value="0" name="medantihipertensivos" />
                       	</td>
                        <td valign="top" width="3%">
                        	<input type="radio" value="1" name="medantihipertensivos" />
                       	</td>
                        <td valign="top" width="40%">
                        	<table width="100%" cellpadding="0" cellspacing="0">
                            	<tr>
                                	<td valign="top" width="5%">¿Cu&aacute;l?</td><td>&nbsp;&nbsp;<input type="text" style="width:92%" id="medantihipertensivoscual"  /></td>
                               	</tr>
                            </table>
                       	</td>
                    </tr>
                    <tr>
                        <td valign="top">
                        	Anticuagulantes
                       	</td>
                        <td width="3%" valign="top">
                        	<input type="radio" value="0" name="medanticuagulantes" />
                       	</td>
                        <td width="3%" valign="top">
                        	<input type="radio" value="1" name="medanticuagulantes" />
                       	</td>
                        <td width="40%" valign="top">
                        	<table width="100%" cellpadding="0" cellspacing="0">
                            	<tr>
                                	<td valign="top" width="5%">¿Cu&aacute;l?</td><td>&nbsp;&nbsp;<input type="text" id="medanticuagulantescual" style="width:92%"  /></td>
                               	</tr>
                                <tr>
                                	<td valign="top" width="5%">Dosis:</td><td>&nbsp;&nbsp;<input type="text" id="medanticuagulantesdosis" style="width:92%"  /></td>
                              	</tr>
                            </table>
                       	</td>
                    </tr>
                    <tr>
                        <td valign="top">
                        	Medicamentos para la diabetes
                       	</td>
                        <td valign="top" width="3%">
                        	<input type="radio" value="0" name="meddiabetes" />
                       	</td>
                        <td valign="top" width="3%">
                        	<input type="radio" value="1" name="meddiabetes" />
                       	</td>
                        <td valign="top" width="40%">
                        	<table width="100%" cellpadding="0" cellspacing="0">
                            	<tr>
                                	<td valign="top" width="5%">¿Cu&aacute;l?</td><td>&nbsp;&nbsp;<input type="text" id="meddiabetescual" style="width:92%"  /></td>
                               	</tr>
                            </table>
                       	</td>
                    </tr>
                    <tr>
                        <td valign="top">
                        	¿Toma alg&uacute;n otro medicamento?
                       	</td>
                        <td width="3%" valign="top">
                        	<input type="radio" value="0" name="medotro" />
                       	</td>
                        <td width="3%" valign="top">
                        	<input type="radio" value="1" name="medotro" />
                       	</td>
                        <td width="40%" valign="top">
                        	<table width="100%" cellpadding="0" cellspacing="0">
                            	<tr>
                                	<td valign="top" width="5%">¿Cu&aacute;l?</td><td>&nbsp;&nbsp;<input type="text" id="medotrocual1" style="width:92%"  /></td>
                               	</tr>
                                <tr>
                                	<td valign="top" width="5%">Dosis:</td><td>&nbsp;&nbsp;<input type="text" id="medotrodosis1" style="width:92%"  /></td>
                              	</tr>
                                <tr>
                                	<td valign="top" width="5%">¿Cu&aacute;l?</td><td>&nbsp;&nbsp;<input type="text" id="medotrocual2" style="width:92%"  /></td>
                               	</tr>
                                <tr>
                                	<td valign="top" width="5%">Dosis:</td><td>&nbsp;&nbsp;<input type="text" id="medotrodosis2" style="width:92%"  /></td>
                              	</tr>
                            </table>
                       	</td>
                    </tr>
                </table>
            </div>
            <div class="form-actions" style="text-align:right; margin-top:0px; margin-bottom:0px; padding:5px">
            	<table align="right" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td align="right">
                            <div class="shortcuts"> 
                                <a href="javascript:nuevo();" class="shortcut"><i class="shortcut-icon icon-file-alt"></i><span class="shortcut-label">Nuevo</span></a>&nbsp;
                                <a href="javascript:guardar();" class="shortcut"><i class="shortcut-icon icon-save"></i><span class="shortcut-label">Guardar</span></a>&nbsp;
                                <a href="javascript:imprimir();" class="shortcut"><i class="shortcut-icon icon-print"></i><span class="shortcut-label">Imprimir</span></a>&nbsp;
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
<!-- /main  -->
<div class="footer">
  <div class="footer-inner">
    <div class="container">
      <div class="row">
        <div class="span12"> &copy; 2015 Sistema de Control de Medicina Est&eacute;tica. <a href="http://www.xiontecnologias.com/">Creado por XION Tecnologias</a></div>
        <!-- /span12 --> 
      </div>
      <!-- /row --> 
    </div>
    <!-- /container --> 
  </div>
  <!-- /footer-inner --> 
</div>
<!-- /footer --> 
<!-- Le javascript
================================================== --> 
<!-- Placed at the end of the document so the pages load faster --> 
<script src="js/jquery-1.7.2.min.js"></script> 
<script src="js/bootstrap.js"></script> 
<script src="js/jquery-ui.js"></script>
<script type="text/javascript">
	
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
		$("#fecIngreso").datepicker({
			changeMonth: true,
            changeYear: true,
			showButtonPanel: true,
			yearRange: '1930:+0'
		});
		$("#pfecnac").datepicker({
			changeMonth: true,
            changeYear: true,
			showButtonPanel: true,
			yearRange: '1930:+0'
		});
		/*$("#ultimoexamenfisico").datepicker({
			changeMonth: true,
            changeYear: true,
			showButtonPanel: true,
			yearRange: '1930:+0'
		});
		$("#ultimaradiografia").datepicker({
			changeMonth: true,
            changeYear: true,
			showButtonPanel: true,
			yearRange: '1930:+0'
		});
		$("#ultimoelectrocardiograma").datepicker({
			changeMonth: true,
            changeYear: true,
			showButtonPanel: true,
			yearRange: '1930:+0'
		});*/
        nuevo();
		checaGET();
    });
	
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
		$.post("ajax/ajaxingreso.php?op=obtenerFolio", "", function(resp){
			$("#folio").html(resp);
		});
	}
	
	function calculaEdad(){
		var pfecnac = $("#pfecnac").val();
		$.post("ajax/ajaxingreso.php?op=calculaEdad", "pfecnac="+pfecnac, function(resp){
			$("#pedad").val(resp);
		});
	}
	
	function calculaIMC(){
		var peso = $("#peso").val();
		var talla = $("#talla").val();
		$.post("ajax/ajaxingreso.php?op=calculaIMC", "peso="+peso+"&talla="+talla, function(resp){
			$("#imc").val(resp);
		});
	}
	
	function comboDoctor(){
		$.post("ajax/ajaxingreso.php?op=comboDoctor", "", function(resp){
			$("#doctor").html(resp);
		});
	}
	
	function comboCirugia(){
		$.post("ajax/ajaxingreso.php?op=comboCirugia", "", function(resp){
			$("#cirugia").html(resp);
		});
	}
	
	function nuevo(){
		obtenerFolio();
		comboDoctor();
		comboCirugia();
		$("#fecIngreso").val('<?php echo date("d/m/Y"); ?>');
		$("#doctor").val('');
		$("#cirugia").val('');
		$("#pnombre").val('');
		$("#pfecnac").val('');
		$("#pedad").val('');
		$("#pedocivil").val('SOLTERA(O)');
		$("#psexo").val('MUJER');
		$("#pdomicilio").val('');
		$("#ptelparticular").val('');
		$("#pteltrabajo").val('');
		$("#pcelular").val('');
		$("#pemail").val('');
		$("#pfacebook").val('');
		$("#ptwitter").val('');
		$("#rnombre").val('');
		$("#rtelefono").val('');
		$("#avisara").val('');
		$("#avisartelefonos").val('');
		$("#peso").val('');
		$("#talla").val('');
		$("#imc").val('');
		$("#operyproc").val('');
		$("#ultimoexamenfisico").val('');
		$("#ultimaradiografia").val('');
		$("#ultimoelectrocardiograma").val('');
		$("input[name='anestesiaraquia']")[1].checked = true;
		$("input[name='anestesialocal']")[1].checked = true;
		$("input[name='anestesiageneral']")[1].checked = true;
		$("input[name='anestesiareacciones']")[1].checked = true;
		$("input[name='anestesiafiebre']")[1].checked = true;
		$("input[name='usteddientespostizos']")[1].checked = true;
		$("input[name='usteddientesflojos']")[1].checked = true;
		$("input[name='ustedcubiertosporcelana']")[1].checked = true;
		$("input[name='ustedabrirboca']")[1].checked = true;
		$("input[name='ustedpestaniaspostizas']")[1].checked = true;
		$("input[name='ustedlentescontacto']")[1].checked = true;
		$("input[name='usteddefectosfisicos']")[1].checked = true;
		$("input[name='medantidepresivos']")[1].checked = true;
		$("#medantidepresivoscual").val('');
		$("input[name='medantihipertensivos']")[1].checked = true;
		$("#medantihipertensivoscual").val('');
		$("input[name='medanticuagulantes']")[1].checked = true;
		$("#medanticuagulantescual").val('');
		$("#medanticuagulantesdosis").val('');
		$("input[name='meddiabetes']")[1].checked = true;
		$("#meddiabetescual").val('');
		$("input[name='medotro']")[1].checked = true;
		$("#medotrocual1").val('');
		$("#medotrodosis1").val('');
		$("#medotrocual2").val('');
		$("#medotrodosis2").val('');
	}
	
	function cargarExpediente(){
		$.post("ajax/ajaxingreso.php?op=cargar", "", function(resp){
			//alert(resp);
			var row = eval('('+resp+')');
			$("#fecIngreso").val(formatearFecha(row.fecha));
			$("#folio").html(row.folio);
			$("#doctor").val(row.nombre);
			$("#cirugia").val(row.cirugia);
			$("#pnombre").val(row.pnombre);
			$("#pfecnac").val(formatearFecha(row.pfecnac));
			$("#pedad").val(row.pedad);
			//calculaEdad();
			$("#pedocivil").val(row.pedocivil);
			$("#psexo").val(row.psexo);
			$("#pdomicilio").val(row.pdomicilio);
			$("#ptelparticular").val(row.ptelparticular);
			$("#pteltrabajo").val(row.pteltrabajo);
			$("#pcelular").val(row.pcelular);
			$("#pemail").val(row.pemail);
			$("#pfacebook").val(row.pfacebook);
			$("#ptwitter").val(row.ptwitter);
			$("#rnombre").val(row.rnombre);
			$("#rtelefono").val(row.rtelefono);
			$("#avisara").val(row.avisara);
			$("#avisartelefonos").val(row.avisartelefonos);
			$("#peso").val(row.peso);
			$("#talla").val(row.talla);
			$("#imc").val(row.imc);
			$("#operyproc").val(row.operyproc);
			$("#ultimoexamenfisico").val(formatearFecha(row.ultimoexamenfisico));
			$("#ultimaradiografia").val(formatearFecha(row.ultimaradiografia));
			$("#ultimoelectrocardiograma").val(formatearFecha(row.ultimoelectrocardiograma));
			$("input[name='anestesiaraquia']")[row.anestesiaraquia].checked = true;
			$("input[name='anestesialocal']")[row.anestesialocal].checked = true;
			$("input[name='anestesiageneral']")[row.anestesiageneral].checked = true;
			$("input[name='anestesiareacciones']")[row.anestesiareacciones].checked = true;
			$("input[name='anestesiafiebre']")[row.anestesiafiebre].checked = true;
			$("input[name='usteddientespostizos']")[row.usteddientespostizos].checked = true;
			$("input[name='usteddientesflojos']")[row.usteddientesflojos].checked = true;
			$("input[name='ustedcubiertosporcelana']")[row.ustedcubiertosporcelana].checked = true;
			$("input[name='ustedabrirboca']")[row.ustedabrirboca].checked = true;
			$("input[name='ustedpestaniaspostizas']")[row.ustedpestaniaspostizas].checked = true;
			$("input[name='ustedlentescontacto']")[row.ustedlentescontacto].checked = true;
			$("input[name='usteddefectosfisicos']")[row.usteddefectosfisicos].checked = true;
			$("input[name='medantidepresivos']")[row.medantidepresivos].checked = true;
			$("#medantidepresivoscual").val(row.medantidepresivoscual);
			$("input[name='medantihipertensivos']")[row.medantihipertensivos].checked = true;
			$("#medantihipertensivoscual").val(row.medantihipertensivoscual);
			$("input[name='medanticuagulantes']")[row.medanticuagulantes].checked = true;
			$("#medanticuagulantescual").val(row.medanticuagulantescual);
			$("#medanticuagulantesdosis").val(row.medanticuagulantesdosis);
			$("input[name='meddiabetes']")[row.meddiabetes].checked = true;
			$("#meddiabetescual").val(row.meddiabetescual);
			$("input[name='medotro']")[row.medotro].checked = true;
			$("#medotrocual1").val(row.medotrocual1);
			$("#medotrodosis1").val(row.medotrodosis1);
			$("#medotrocual2").val(row.medotrocual2);
			$("#medotrodosis2").val(row.medotrodosis2);
		});
	}
	
	function eliminar(){
		var folio = $("#folio").html();
		if(confirm("¿Desea eliminar el expediente No."+folio+"?")){
			var params = "folio="+folio;
			$.post("ajax/ajaxingreso.php?op=eliminar", params, function(resp){
				if(resp==1){
					alert("El expediente se elimino exitosamente!");
					nuevo();					
				}
				if(resp==0){
					alert("Ocurrió un error, intente nuevamente. Si el problema persiste contacte a soporte");
				}
			});	
		}
	}
	
	function guardar(){
		var campos = new Array('pnombre_txt');
		var validacion = validar(campos);
						
		if(validacion == true){			
			var params = "folio=" + $("#folio").html();
			params+= "&fecha=" + $("#fecIngreso").val();
			params+= "&doctor=" + $("#doctor").val();
			params+= "&cirugia=" + $("#cirugia").val();
			params+= "&pnombre=" + $("#pnombre").val();
			params+= "&pfecnac=" + $("#pfecnac").val();
			params+= "&pedad=" + $("#pedad").val();
			params+= "&pedocivil=" + $("#pedocivil").val();
			params+= "&psexo=" + $("#psexo").val();
			params+= "&pdomicilio=" + $("#pdomicilio").val();
			params+= "&ptelparticular=" + $("#ptelparticular").val();
			params+= "&pteltrabajo=" + $("#pteltrabajo").val();
			params+= "&pcelular=" + $("#pcelular").val();
			params+= "&pemail=" + $("#pemail").val();
			params+= "&pfacebook=" + $("#pfacebook").val();
			params+= "&ptwitter=" + $("#ptwitter").val();
			params+= "&rnombre=" + $("#rnombre").val();
			params+= "&rtelefono=" + $("#rtelefono").val();
			params+= "&avisara=" + $("#avisara").val();
			params+= "&avisartelefonos=" + $("#avisartelefonos").val();
			params+= "&peso=" + $("#peso").val();
			params+= "&talla=" + $("#talla").val();
			params+= "&imc=" + $("#imc").val();
			params+= "&operyproc=" + $("#operyproc").val();
			params+= "&ultimoexamenfisico=" + $("#ultimoexamenfisico").val();
			params+= "&ultimaradiografia=" + $("#ultimaradiografia").val();
			params+= "&ultimoelectrocardiograma=" + $("#ultimoelectrocardiograma").val();
			params+= "&anestesiaraquia=" + $("input[name='anestesiaraquia']:checked").val();
			params+= "&anestesialocal=" + $("input[name='anestesialocal']:checked").val();
			params+= "&anestesiageneral=" + $("input[name='anestesiageneral']:checked").val();
			params+= "&anestesiareacciones=" + $("input[name='anestesiareacciones']:checked").val();
			params+= "&anestesiafiebre=" + $("input[name='anestesiafiebre']:checked").val();
			params+= "&usteddientespostizos=" + $("input[name='usteddientespostizos']:checked").val();
			params+= "&usteddientesflojos=" + $("input[name='usteddientesflojos']:checked").val();
			params+= "&ustedcubiertosporcelana=" + $("input[name='ustedcubiertosporcelana']:checked").val();
			params+= "&ustedabrirboca=" + $("input[name='ustedabrirboca']:checked").val();
			params+= "&ustedpestaniaspostizas=" + $("input[name='ustedpestaniaspostizas']:checked").val();
			params+= "&ustedlentescontacto=" + $("input[name='ustedlentescontacto']:checked").val();
			params+= "&usteddefectosfisicos=" + $("input[name='usteddefectosfisicos']:checked").val();
			params+= "&medantidepresivos=" + $("input[name='medantidepresivos']:checked").val();
			params+= "&medantidepresivoscual=" + $("#medantidepresivoscual").val();
			params+= "&medantihipertensivos=" + $("input[name='medantihipertensivos']:checked").val();
			params+= "&medantihipertensivoscual=" + $("#medantihipertensivoscual").val();
			params+= "&medanticuagulantes=" + $("input[name='medanticuagulantes']:checked").val();
			params+= "&medanticuagulantescual=" + $("#medanticuagulantescual").val();
			params+= "&medanticuagulantesdosis=" + $("#medanticuagulantesdosis").val();
			params+= "&meddiabetes=" + $("input[name='meddiabetes']:checked").val();
			params+= "&meddiabetescual=" + $("#meddiabetescual").val();
			params+= "&medotro=" + $("input[name='medotro']:checked").val();
			params+= "&medotrocual1=" + $("#medotrocual1").val();
			params+= "&medotrodosis1=" + $("#medotrodosis1").val();
			params+= "&medotrocual2=" + $("#medotrocual2").val();
			params+= "&medotrodosis2=" + $("#medotrodosis2").val();
			
			$.post("ajax/ajaxingreso.php?op=guardar", params, function(resp){
				//alert(resp);
				if(resp>0){
					alert("El registro se guardo exitosamente!");
					imprimir();
					nuevo();
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
	
	function imprimir(){
		var folio = $("#folio").html();
		window.open("impIngreso.php?folio="+folio,"_blank");
		//alert("Ha ocurrido un problema con la configuracion de la impresion");	
	}
	
</script>
</body>
</html>
