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
                            class="icon-user"></i> Administrador <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <!--<li><a href="javascript:;">Profile</a></li>-->
              <li><a href="javascript:;">Cerrar sesi&oacute;n</a></li>
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
        <li><a href="reportes.php"><i class="icon-list-alt"></i><span>Reportes</span> </a> </li>
        <li><a href="usuarios.php"><i class="icon-user"></i><span>Usuarios</span> </a> </li>
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
                                <a href="javascript:;" class="shortcut"><i class="shortcut-icon icon-file-alt"></i><span class="shortcut-label">Nuevo</span></a>&nbsp;
                                <a href="javascript:;" class="shortcut"><i class="shortcut-icon icon-save"></i><span class="shortcut-label">Guardar</span></a>&nbsp;
                                <a href="javascript:;" class="shortcut"><i class="shortcut-icon icon-print"></i><span class="shortcut-label">Imprimir</span></a>&nbsp;
                                <a href="javascript:;" class="shortcut"><i class="shortcut-icon icon-remove"></i><span class="shortcut-label">Eliminar</span></a>
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
                    	<td width="50%">
                        	<table style="font-size:30px; color:#F00" cellpadding="0" cellspacing="0">
                            	<tr>
                                	<td>FOLIO:&nbsp;&nbsp;</td><td><div id="folio">1</div></td>
                              	</tr>
                           	</table>
                        </td>
                        <td>
                        	Doctor responsable<br/>
                            <input type="text" style="width:92%" />
                       	</td>
                        <td>
                        	Ciruj&iacute;a<br/>
                            <select style="width:92%">
                            	<option value="1">Cirugia 1</option>
                                <option value="2">Cirugia 2</option>
                                <option value="3">Cirugia 3</option>
                            </select>
                       	</td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td>
                        	Nombre del paciente<br/>
                            <input type="text" style="width:97%" />
                       	</td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td>
                        	Fecha de nacimiento<br/>
                            <input type="text" style="width:92%" />
                       	</td>
                        <td>
                        	Edad<br/>
                            <input type="text" style="width:92%" readonly="readonly" />
                       	</td>
                        <td>
                        	Estado civil<br/>
                            <select style="width:92%">
                                <option value="SOLTERA(O)">SOLTERA(O)</option>
                            	<option value="CASADA(O)">CASADA(O)</option>
                                <option value="DIVORCIADA(O)">DIVORCIADA(O)</option>
                                <option value="VIUDA(O)">VIUDA(O)</option>
                            </select>
                       	</td>
                        <td>
                        	Sexo<br/>
                            <select style="width:92%">
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
                            <input type="text" id="domicilio" style="width:97%" />
                       	</td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td>
                        	Tel&eacute;fono particular<br/>
                            <input type="text" style="width:92%" />
                       	</td>
                        <td>
                        	Tel&eacute;fono de trabajo<br/>
                            <input type="text" style="width:92%" />
                       	</td>
                        <td>
                        	Celular<br/>
                            <input type="text" style="width:92%" />
                       	</td>
                    </tr>
                </table>                
                <table width="100%">
                    <tr>
                        <td>
                        	Email<br/>
                            <input type="text" style="width:92%" />
                       	</td>
                        <td>
                        	Facebook<br/>
                            <input type="text" style="width:92%" />
                       	</td>
                        <td>
                        	Twitter<br/>
                            <input type="text" style="width:92%" />
                       	</td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td>
                        	Nombre del responsable<br/>
                            <input type="text" style="width:92%" />
                       	</td>
                        <td>
                        	Tel&eacute;fono del responsable<br/>
                            <input type="text" style="width:92%" />
                       	</td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td>
                        	En caso de emergencia avisar a<br/>
                            <input type="text" style="width:92%" />
                       	</td>
                        <td>
                        	Tel&eacute;fonos<br/>
                            <input type="text" style="width:92%" />
                       	</td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td>
                        	Peso<br/>
                            <input type="text" style="width:92%" />
                       	</td>
                        <td>
                        	Talla<br/>
                            <input type="text" style="width:92%" />
                       	</td>
                        <td>
                        	IMC<br/>
                            <input type="text" style="width:92%" readonly="readonly" />
                       	</td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td>
                        	Operaciones y procedimiento<br/>
                            <input type="text" id="operacionesProcedimiento" style="width:97%" />
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
                            <input type="text" style="width:92%" />
                       	</td>
                        <td>
                        	¿&Uacute;ltima toma de radiograf&iacute;a de t&oacute;rax?<br/>
                            <input type="text" style="width:92%" />
                       	</td>
                        <td>
                        	¿En que fecha recibi&oacute; su &uacute;ltimo electrocardiograma?<br/>
                            <input type="text" style="width:92%" />
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
                        	<input type="radio" value="SI" name="anestesia1" />
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="NO" name="anestesia1" />
                       	</td>
                    </tr>
                    <tr>
                        <td>
                        	¿Local?
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="SI" name="anestesia2" />
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="NO" name="anestesia2" />
                       	</td>
                    </tr>
                    <tr>
                        <td>
                        	¿General?
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="SI" name="anestesia3" />
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="NO" name="anestesia3" />
                       	</td>
                    </tr>
                    <tr>
                        <td>
                        	¿A presentado reacciones anormales?
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="SI" name="anestesia4" />
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="NO" name="anestesia4" />
                       	</td>
                    </tr>
                    <tr>
                        <td>
                        	¿A presentado fiebre en operaciones previas?
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="SI" name="anestesia5" />
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="NO" name="anestesia5" />
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
                        	<input type="radio" value="SI" name="usted1" />
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="NO" name="usted1" />
                       	</td>
                    </tr>
                    <tr>
                        <td>
                        	¿Le falta dientes o tiene dientes flojos?
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="SI" name="usted2" />
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="NO" name="usted2" />
                       	</td>
                    </tr>
                    <tr>
                        <td>
                        	¿Estan cubiertos de porcelana permanente sus dientes?
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="SI" name="usted3" />
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="NO" name="usted3" />
                       	</td>
                    </tr>
                    <tr>
                        <td>
                        	¿Se le dificulta abrir la boca o moverla?
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="SI" name="usted4" />
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="NO" name="usted4" />
                       	</td>
                    </tr>
                    <tr>
                        <td>
                        	¿Usa pesta&ntilde;as postizas?
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="SI" name="usted5" />
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="NO" name="usted5" />
                       	</td>
                    </tr>
                    <tr>
                        <td>
                        	¿Usa lentes de contacto?
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="SI" name="usted6" />
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="NO" name="usted6" />
                       	</td>
                    </tr>
                    <tr>
                        <td>
                        	¿Tiene defectos f&iacute;sicos mayores o cong&eacute;nitos?
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="SI" name="usted7" />
                       	</td>
                        <td width="10%">
                        	<input type="radio" value="NO" name="usted7" />
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
                        	<input type="radio" value="SI" name="medicamento1" />
                       	</td>
                        <td valign="top" width="3%">
                        	<input type="radio" value="NO" name="medicamento1" />
                       	</td>
                        <td valign="top" width="40%">
                        	<table width="100%" cellpadding="0" cellspacing="0">
                            	<tr>
                                	<td valign="top" width="5%">¿Cu&aacute;l?</td><td>&nbsp;&nbsp;<input type="text" style="width:92%"  /></td>
                               	</tr>
                            </table>
                       	</td>
                    </tr>
                    <tr>
                        <td valign="top">
                        	Antihipertensivas
                       	</td>
                        <td valign="top" width="3%">
                        	<input type="radio" value="SI" name="medicamento2" />
                       	</td>
                        <td valign="top" width="3%">
                        	<input type="radio" value="NO" name="medicamento2" />
                       	</td>
                        <td valign="top" width="40%">
                        	<table width="100%" cellpadding="0" cellspacing="0">
                            	<tr>
                                	<td valign="top" width="5%">¿Cu&aacute;l?</td><td>&nbsp;&nbsp;<input type="text" style="width:92%"  /></td>
                               	</tr>
                            </table>
                       	</td>
                    </tr>
                    <tr>
                        <td valign="top">
                        	Anticuagulantes
                       	</td>
                        <td width="3%" valign="top">
                        	<input type="radio" value="SI" name="medicamento3" />
                       	</td>
                        <td width="3%" valign="top">
                        	<input type="radio" value="NO" name="medicamento3" />
                       	</td>
                        <td width="40%" valign="top">
                        	<table width="100%" cellpadding="0" cellspacing="0">
                            	<tr>
                                	<td valign="top" width="5%">¿Cu&aacute;l?</td><td>&nbsp;&nbsp;<input type="text" style="width:92%"  /></td>
                               	</tr>
                                <tr>
                                	<td valign="top" width="5%">Dosis:</td><td>&nbsp;&nbsp;<input type="text" style="width:92%"  /></td>
                              	</tr>
                            </table>
                       	</td>
                    </tr>
                    <tr>
                        <td valign="top">
                        	Medicamentos para la diabetes
                       	</td>
                        <td valign="top" width="3%">
                        	<input type="radio" value="SI" name="medicamento4" />
                       	</td>
                        <td valign="top" width="3%">
                        	<input type="radio" value="NO" name="medicamento4" />
                       	</td>
                        <td valign="top" width="40%">
                        	<table width="100%" cellpadding="0" cellspacing="0">
                            	<tr>
                                	<td valign="top" width="5%">¿Cu&aacute;l?</td><td>&nbsp;&nbsp;<input type="text" style="width:92%"  /></td>
                               	</tr>
                            </table>
                       	</td>
                    </tr>
                    <tr>
                        <td valign="top">
                        	¿Toma alg&uacute;n otro medicamento?
                       	</td>
                        <td width="3%" valign="top">
                        	<input type="radio" value="SI" name="medicamento5" />
                       	</td>
                        <td width="3%" valign="top">
                        	<input type="radio" value="NO" name="medicamento5" />
                       	</td>
                        <td width="40%" valign="top">
                        	<table width="100%" cellpadding="0" cellspacing="0">
                            	<tr>
                                	<td valign="top" width="5%">¿Cu&aacute;l?</td><td>&nbsp;&nbsp;<input type="text" style="width:92%"  /></td>
                               	</tr>
                                <tr>
                                	<td valign="top" width="5%">Dosis:</td><td>&nbsp;&nbsp;<input type="text" style="width:92%"  /></td>
                              	</tr>
                                <tr>
                                	<td valign="top" width="5%">¿Cu&aacute;l?</td><td>&nbsp;&nbsp;<input type="text" style="width:92%"  /></td>
                               	</tr>
                                <tr>
                                	<td valign="top" width="5%">Dosis:</td><td>&nbsp;&nbsp;<input type="text" style="width:92%"  /></td>
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
                                <a href="javascript:;" class="shortcut"><i class="shortcut-icon icon-file-alt"></i><span class="shortcut-label">Nuevo</span></a>&nbsp;
                                <a href="javascript:;" class="shortcut"><i class="shortcut-icon icon-save"></i><span class="shortcut-label">Guardar</span></a>&nbsp;
                                <a href="javascript:;" class="shortcut"><i class="shortcut-icon icon-print"></i><span class="shortcut-label">Imprimir</span></a>&nbsp;
                                <a href="javascript:;" class="shortcut"><i class="shortcut-icon icon-remove"></i><span class="shortcut-label">Eliminar</span></a>
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
<!-- /main 
<div class="extra">
  <div class="extra-inner">
    <div class="container">
      <div class="row">
                    <div class="span3">
                        <h4>
                            About Free Admin Template</h4>
                        <ul>
                            <li><a href="javascript:;">EGrappler.com</a></li>
                            <li><a href="javascript:;">Web Development Resources</a></li>
                            <li><a href="javascript:;">Responsive HTML5 Portfolio Templates</a></li>
                            <li><a href="javascript:;">Free Resources and Scripts</a></li>
                        </ul>
                    </div>
                    <!-- /span3 
                    <div class="span3">
                        <h4>
                            Support</h4>
                        <ul>
                            <li><a href="javascript:;">Frequently Asked Questions</a></li>
                            <li><a href="javascript:;">Ask a Question</a></li>
                            <li><a href="javascript:;">Video Tutorial</a></li>
                            <li><a href="javascript:;">Feedback</a></li>
                        </ul>
                    </div>
                    <!-- /span3
                    <div class="span3">
                        <h4>
                            Something Legal</h4>
                        <ul>
                            <li><a href="javascript:;">Read License</a></li>
                            <li><a href="javascript:;">Terms of Use</a></li>
                            <li><a href="javascript:;">Privacy Policy</a></li>
                        </ul>
                    </div>
                    <!-- /span3
                    <div class="span3">
                        <h4>
                            Open Source jQuery Plugins</h4>
                        <ul>
                            <li><a href="http://www.egrappler.com">Open Source jQuery Plugins</a></li>
                            <li><a href="http://www.egrappler.com;">HTML5 Responsive Tempaltes</a></li>
                            <li><a href="http://www.egrappler.com;">Free Contact Form Plugin</a></li>
                            <li><a href="http://www.egrappler.com;">Flat UI PSD</a></li>
                        </ul>
                    </div>
                    <!-- /span3
                </div>
      <!-- /row
    </div>
    <!-- /container
  </div>
  <!-- /extra-inner
</div>
<!-- /extra -->
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
<script src="js/excanvas.min.js"></script> 
<script src="js/chart.min.js" type="text/javascript"></script> 
<script src="js/bootstrap.js"></script>
<script language="javascript" type="text/javascript" src="js/full-calendar/fullcalendar.min.js"></script>
 
<script src="js/base.js"></script> 
<script>     

        var lineChartData = {
            labels: ["January", "February", "March", "April", "May", "June", "July"],
            datasets: [
				{
				    fillColor: "rgba(220,220,220,0.5)",
				    strokeColor: "rgba(220,220,220,1)",
				    pointColor: "rgba(220,220,220,1)",
				    pointStrokeColor: "#fff",
				    data: [65, 59, 90, 81, 56, 55, 40]
				},
				{
				    fillColor: "rgba(151,187,205,0.5)",
				    strokeColor: "rgba(151,187,205,1)",
				    pointColor: "rgba(151,187,205,1)",
				    pointStrokeColor: "#fff",
				    data: [28, 48, 40, 19, 96, 27, 100]
				}
			]

        }

        var myLine = new Chart(document.getElementById("area-chart").getContext("2d")).Line(lineChartData);


        var barChartData = {
            labels: ["January", "February", "March", "April", "May", "June", "July"],
            datasets: [
				{
				    fillColor: "rgba(220,220,220,0.5)",
				    strokeColor: "rgba(220,220,220,1)",
				    data: [65, 59, 90, 81, 56, 55, 40]
				},
				{
				    fillColor: "rgba(151,187,205,0.5)",
				    strokeColor: "rgba(151,187,205,1)",
				    data: [28, 48, 40, 19, 96, 27, 100]
				}
			]

        }    

        $(document).ready(function() {
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();
        var calendar = $('#calendar').fullCalendar({
          header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
          },
          selectable: true,
          selectHelper: true,
          select: function(start, end, allDay) {
            var title = prompt('Event Title:');
            if (title) {
              calendar.fullCalendar('renderEvent',
                {
                  title: title,
                  start: start,
                  end: end,
                  allDay: allDay
                },
                true // make the event "stick"
              );
            }
            calendar.fullCalendar('unselect');
          },
          editable: true,
          events: [
            {
              title: 'All Day Event',
              start: new Date(y, m, 1)
            },
            {
              title: 'Long Event',
              start: new Date(y, m, d+5),
              end: new Date(y, m, d+7)
            },
            {
              id: 999,
              title: 'Repeating Event',
              start: new Date(y, m, d-3, 16, 0),
              allDay: false
            },
            {
              id: 999,
              title: 'Repeating Event',
              start: new Date(y, m, d+4, 16, 0),
              allDay: false
            },
            {
              title: 'Meeting',
              start: new Date(y, m, d, 10, 30),
              allDay: false
            },
            {
              title: 'Lunch',
              start: new Date(y, m, d, 12, 0),
              end: new Date(y, m, d, 14, 0),
              allDay: false
            },
            {
              title: 'Birthday Party',
              start: new Date(y, m, d+1, 19, 0),
              end: new Date(y, m, d+1, 22, 30),
              allDay: false
            },
            {
              title: 'EGrappler.com',
              start: new Date(y, m, 28),
              end: new Date(y, m, 29),
              url: 'http://EGrappler.com/'
            }
          ]
        });
      });
    </script><!-- /Calendar -->
</body>
</html>
