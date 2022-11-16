<?php
	require_once("ajax/control.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema para Timbrado de N&oacute;mina</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="assets/datetimepicker/css/datetimepicker.min.css">
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
<style>
    .interior{
        padding: 10px;
    }
    .action-bottom-buttons{
      text-align:right;
       margin-top:0px; 
       margin-bottom:0px; 
       padding:5px
    }
    .puntero:hover{
        cursor: pointer;
    }

    .buscaSocioeconomico{
      width:
    }
</style>  
<script src="js/jquery-1.7.2.min.js"></script> 
<script src="js/bootstrap.js"></script> 
<script src="js/jquery-ui.js"></script>

<script src="js/vue.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.js"></script>


<link href="https://unpkg.com/flatpickr@4/dist/flatpickr.min.css" rel="stylesheet">
<script src="https://unpkg.com/flatpickr@4/dist/flatpickr.min.js"></script>
<script src="https://unpkg.com/vue-flatpickr-component@7"></script>

	<script>
		// Vue.component('date-picker', VueBootstrapDatetimePicker.default);

 			Vue.component('flat-pickr', VueFlatpickr);
	</script>
</head>
<body>
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
					<select id="selDepartamento" style="width:92%">
					</select>
				</td>
				<td>
					Nombre<br/>
					<input type="text" id="nombreTrabajador" style="width:95%" />
				</td>
				<td align="right">
					<div class="shortcuts"> 
            <br>
						<span id="filtraTrabajador" class="shortcut puntero"><i class="shortcut-icon icon-search"></i><span class="shortcut-label"></span></span>&nbsp;
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
<!-- Termina Modal -->
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
		<li><a href="empleados.php"><i class="icon-group"></i><span>Empleados</span> </a></li>
        <li class="active"><a href="socioeconomicos.php"><i class="icon-book"></i><span>Socioecónomicos</span></a></li>
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
          <div class="widget widget-notpad">
            <div class="form-actions action-bottom-buttons">
                <table style="border-collapse:none;border-spacing:0px">
                   <tr >
                        <td>
                          <div class="shortcuts">
                            <span class="shortcut puntero" style="z-index: 99" id="btnBuscarTrabajador">
                                <i class="shortcut-icon icon-search"></i>
                                  <span>Buscar</span>
                            </span>                
                        </div>
                        </td>
                   </tr>
                </table>
            </div>          
            <div class="widget-header">
               <h3>Datos Personales</h3>
            </div>
            <div class="widget-content interior">
              <input type="hidden" id="nip">
              <input type="hidden" id="socioeconomico">
              <table style="width:100%">
                    <tr>
                      <td style="width:100%" colspan="4">
                        <label for="">Nombre completo</label>
                        <input type="text" name="" id="nombreEmpleado" style="width:99%">
                      </td>
                    </tr>
                    <tr>
                        <td colspan = "4" class="text-center"  style="width:99%"><b>Domicilio</b></td>
                    </tr>
                    <tr>
                        <td style="width:94%"> 
                            <label for="">Calle/Avenida</label>
                            <input type="text" name="" id="calle_av" style="width:98%">
                        </td>
                        <td style="width:94%">
                            <label for="">Número Int.</label>
                            <input type="text" name="" id="numInt">
                        </td>
                        <td style="width:94%">
                          <label for="">Número Ext.</label>
                          <input type="text" name="" id="numExt">
                        </td>
                        <td style="width:94%">
                          <label for="">Código postal</label>
                          <input type="text" id="cp">
                        </td>                        
                    </tr>
                    <tr>
                        <td>
                          <label for="">Colonia</label>
                          <input type="text" name="" id="colonia" style="width:98%">
                        </td>                        
                        <td style="width:94%">
                            <label>Estado</label>
                            <input type="text" name="" id="estado" style="width:94%">
                        </td>
                        <td>
                          <label for="">Municipio</label>
                          <input type="text" name="Municipio" id="municipio">
                        </td>
                    </tr>
                    <tr>
                      <td >
                        <label for="">Fecha Nacimiento</label>
                        <input type="date" name="" id="nacimiento" style="width:97%">
                      </td>
                      <td colspan="4" style="width:96%">
                        <label for="">Lugar de Nacimiento</label>
                        <input type="text" name="" id="lugarNacimiento" style="width:100%">
                      </td>
                    </tr>
                    <tr>
                      <td  style="width:100%">
                          <label for="">Estado Civil</label>
                          <select name="" id="edoCivil" style="width:96%">
                            <option value="CASADO(A)">CASADO(A)</option>
                            <option value="SOLTERO(A)">SOLTERO(A)</option>
                          </select>
                      </td>
                      <!-- <td style="width:100%" colspan="3">
                        <label for="">Escolaridad</label>
                        <input type="text" name="" id="" style="width:100%">
                      </td> -->
                    </tr>
                </table>            
            </div>
            <div class="form-actions action-bottom-buttons">
            	<table align="right" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td align="right">
                            <div class="shortcuts"> 
                                <span class="shortcut puntero" id="guardaPersonal"><i class="shortcut-icon icon-save"></i><span class="shortcut-label">Guardar</span></span>&nbsp;
                      
                            </div>
                      	</td>
                   	</tr>
              	</table>            
            </div>
          </div>                

          <div  class="widget widget-nopad oculto"  style="display:none">
            <div class="widget-header">
              <h3>Datos Económicos</h3>
            </div>
            <div class="widget-content" id="content-economico"style="padding:10px;">
              <div class="row">
                  <div class="span4">
                    <table  style="width:100%">
                      <tr>
                        <th colspan="2">Mensualmente Perciben</th>
                      </tr>
                      <tbody id="tingreso">
                        
                      </tbody>
                    </table>                  
                  </div>
                  <div class="span4">
                    <table  style="width:100%">
                      <tr>
                        <th colspan="2">Gasto mensual familiar</th>
                      </tr>
                      <tbody id="tgasto">
                        
                      </tbody>
                    </table>                  
                  </div>
                  <div class="span4">
                    <table  style="width:100%">
                      <tr>
                        <th colspan="2">Créditos</th>
                      </tr>
                      <tbody id="tcreditos">
                        
                      </tbody>
                    </table>                  
                  </div>              
              </div>

            </div>
            <div class="form-actions action-bottom-buttons">
            	<table align="right" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td align="right">
                            <div class="shortcuts"> 
                                <span class="shortcut puntero" onclick="limpiaSeccion('economico')"><i class="shortcut-icon icon-file-alt" ></i><span class="shortcut-label">Nuevo</span></span>&nbsp;
                                <span class="shortcut puntero"  onclick="guardaSeccion('economico')" id="guardaEconomico"><i class="shortcut-icon icon-save"></i><span class="shortcut-label">Guardar</span></span>&nbsp;
                      
                            </div>
                      	</td>
                   	</tr>
              	</table>            
            </div>            
          </div>

          <div class="widget widget-nopad oculto"  style="display:none">
            <div class="widget-header">
              <h3>Escolaridad</h3>
            </div>
            <div class="widget-content"  id="content-escolaridad" style="padding:10px;">
                <table>
                  <tbody id="tescolaridad">
                    
                  </tbody>
                </table>
            </div>
            <div class="form-actions action-bottom-buttons">
            	<table align="right" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td align="right">
                            <div class="shortcuts"> 
                                <span class="shortcut puntero" onclick="limpiaSeccion('escolaridad)"><i class="shortcut-icon icon-file-alt" ></i><span class="shortcut-label">Nuevo</span></span>&nbsp;
                                <span class="shortcut puntero" onclick="guardaSeccion('escolaridad')"><i class="shortcut-icon icon-save"></i><span class="shortcut-label">Guardar</span></span>&nbsp;
                      
                            </div>
                      	</td>
                   	</tr>
              	</table>            
            </div>                     
          </div>          

          <div class="widget widget-nopad oculto"  style="display:none">
            <div class="widget-header">
              <h3>Referencias Vecinales</h3>
            </div>
            <div class="widget-content"  id="content-vecinales" style="padding:10px;">
                <table>
                  <tbody id="tvecinales">
                    
                  </tbody>
                </table>            
            </div>
            <div class="form-actions action-bottom-buttons">
            	<table align="right" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td align="right">
                            <div class="shortcuts"> 
                                <span class="shortcut puntero" onclick="limpiaSeccion('vecinales')"><i class="shortcut-icon icon-file-alt" ></i><span class="shortcut-label">Nuevo</span></span>&nbsp;
                                <span class="shortcut puntero" onclick="guardaSeccion('vecinales')"><i class="shortcut-icon icon-save"></i><span class="shortcut-label">Guardar</span></span>&nbsp;
                      
                            </div>
                      	</td>
                   	</tr>
              	</table>            
            </div>            
          </div>          

          <div class="widget widget-nopad oculto"  style="display:none">
            <div class="widget-header">
              <h3>Colonia y Vivienda </h3>
            </div>
            <div class="widget-content" id="content-colonia_vivienda" style="padding:10px;">
                <table>
                  <tbody id="tcolonia_vivienda">
                    
                  </tbody>
                </table>            
            </div>
            <div class="form-actions action-bottom-buttons">
            	<table align="right" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td align="right">
                            <div class="shortcuts"> 
                                <span class="shortcut puntero" onclick="limpiaSeccion('colonia_vivienda')"><i class="shortcut-icon icon-file-alt" ></i><span class="shortcut-label">Nuevo</span></span>&nbsp;
                                <span class="shortcut puntero" onclick="guardaSeccion('colonia_vivienda')"><i class="shortcut-icon icon-save"></i><span class="shortcut-label">Guardar</span></span>&nbsp;
                      
                            </div>
                      	</td>
                   	</tr>
              	</table>            
            </div>                     
          </div>          

          <div class="widget widget-nopad oculto"  style="display:none">
            <div class="widget-header">
              <h3>Servicios Médicos</h3>
            </div>
            <div class="widget-content" id="content-serv_medicos" style="padding:10px;">
                <table>
                  <tbody id="tserv_medicos">
                    
                  </tbody>
                </table>                       
            </div>
            <div class="form-actions action-bottom-buttons">
            	<table align="right" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td align="right">
                            <div class="shortcuts"> 
                                <span class="shortcut puntero" onclick="limpiaSeccion('serv_medicos')"><i class="shortcut-icon icon-file-alt" ></i><span class="shortcut-label">Nuevo</span></span>&nbsp;
                                <span class="shortcut puntero" onclick="guardaSeccion('serv_medicos')"><i class="shortcut-icon icon-save"></i><span class="shortcut-label">Guardar</span></span>&nbsp;
                      
                            </div>
                      	</td>
                   	</tr>
              	</table>            
            </div>            
          </div>          

          <div  class="widget widget-nopad oculto"  style="display:none"  style="display:none">
            <div class="widget-header">
              <h3>Datos Familiares</h3>
            </div>
            <div class="widget-content" id="content-familia"style="padding:10px;">
                <table>
                  <tbody id="tfamilia">
                    
                  </tbody>
                </table>  
                <table style="width:100%">
                  <tr>
                    <th>Nombre</th>
                    <th>Parentesco</th>
                    <th>Edad</th>
                    <th>Escolaridad</th>
                    <th>Ocupación</th>
                  </tr>
                  <tbody id="elementFamiliar">
                    
                  </tbody>
                </table>               
            </div>
            <div class="form-actions action-bottom-buttons">
            	<table align="right" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td align="right">
                            <div class="shortcuts"> 
                                <span class="shortcut puntero" onclick="limpiaSeccion('familia')"><i class="shortcut-icon icon-file-alt" ></i><span class="shortcut-label">Nuevo</span></span>&nbsp;
                                <span class="shortcut puntero" onclick="guardaSeccion('familia')"><i class="shortcut-icon icon-save"></i><span class="shortcut-label">Guardar</span></span>&nbsp;
                      
                            </div>
                      	</td>
                   	</tr>
              	</table>            
            </div>            
          </div>     

          <div class="widget widget-nopad oculto"  style="display:none">
            <div class="widget-header">
              <h3>Datos Médicos</h3>
            </div>
            <div class="widget-content" id="content-medicos" style="padding:10px;">
                <table>
                  <tbody id="tmedicos">
                    
                  </tbody>
                </table>                 
            </div>
            <div class="form-actions action-bottom-buttons">
            	<table align="right" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td align="right">
                            <div class="shortcuts"> 
                                <span class="shortcut puntero" onclick="limpiaSeccion('medicos')"><i class="shortcut-icon icon-file-alt" ></i><span class="shortcut-label">Nuevo</span></span>&nbsp;
                                <span class="shortcut puntero" onclick="guardaSeccion('medicos')"><i class="shortcut-icon icon-save"></i><span class="shortcut-label">Guardar</span></span>&nbsp;
                      
                            </div>
                      	</td>
                   	</tr>
              	</table>            
            </div>            
          </div>     

          <div  class="widget widget-nopad oculto"  style="display:none"  style="display:none">
            <div class="widget-header">
              <h3>Sociocultural</h3>
            </div>
            <div class="widget-content" id="content-sociocultural"style="padding:10px;">
                <table>
                  <tbody id="tsociocultural">
                    
                  </tbody>
                </table>                 
            </div>
            <div class="form-actions action-bottom-buttons">
            	<table align="right" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td align="right">
                            <div class="shortcuts"> 
                                <span class="shortcut puntero" onclick="limpiaSeccion('sociocultural')"><i class="shortcut-icon icon-file-alt" ></i><span class="shortcut-label">Nuevo</span></span>&nbsp;
                                <span class="shortcut puntero" onclick="guardaSeccion('sociocultural')"><i class="shortcut-icon icon-save"></i><span class="shortcut-label">Guardar</span></span>&nbsp;
                      
                            </div>
                      	</td>
                   	</tr>
              	</table>            
            </div>            
          </div>     

          <div  class="widget widget-nopad oculto"  style="display:none"  style="display:none">
            <div class="widget-header">
              <h3>Comentarios Finales</h3>
            </div>
            <div class="widget-content" id="content-final"style="padding:10px;">
                <table>
                  <tr>
                      <td style="width:20%"><label for="">Nombre del evaluador:</label></td>
                      <td style="width:80%"><input type="text" id="nombreEvaluador" style="width:100%"></td>
                  </tr>
                  <tr>
                      <td style="width:20%"><label for="">Fecha de realización:</label></td>
                      <td style="width:80%"><input type="date" id="fechaEvaluacion" style="width:100%"></td>
                  </tr>                  
                  <tr>
                      <td style="width:20%"><label for="">Comentarios sobre la entrevista:</label></td>
                      <td style="width:80%"><textarea id="comentarios" style="width:100%;resize:none" rows="7"></textarea></td>
                  </tr>   
                  <tr>
                      <td style="width:20%"><label for="">Agregar comprobantes:</label></td>
                      <td style="width:80%"><input type="file" accept="image/*" id="fotoSocioeconomico"></td>
                  </tr>                                       
                </table>                 
            </div>
            <div class="form-actions action-bottom-buttons">
            	<table align="right" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td align="right">
                            <div class="shortcuts"> 
                                <span class="shortcut puntero" id="guardaFinales"><i class="shortcut-icon icon-save"></i><span class="shortcut-label">Guardar</span></span>&nbsp;
                      
                            </div>
                      	</td>
                   	</tr>
              	</table>            
            </div>            
          </div>     

      </div>
      <!-- /row --> 
    </div>
    <!-- /container --> 
  </div>
  <!-- /main-inner --> 
</div>
<!-- /footer --
<div class="footer">
  <div class="footer-inner">
    <div class="container">
      <div class="row">
        <div class="span12"> &copy; 2017 Timbrado de N&oacute;mina. <a href="http://www.xiontecnologias.com/">Creado por XION Tecnologias</a></div>
        <!-- /span12 - 
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

<script src="/nomina/js/socioeconomicos.js"></script>
</body>
</html>