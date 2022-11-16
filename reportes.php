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
<style>
  .modulos li{
    list-style-type: none;
    background: #E10000 ;
  }
  .modulos li p{
    font-family: "Century Gothic";
    font-weight: bold;
    font-size: 1.4em;
    color: #fff;
    padding: .3em;
  }
  .moduloReportes li a{
    font-size: 1em;
    font-weight: bold;
    text-decoration: none;
    color: #000
  }
  .moduloReportes li{
    background: #fff ;
  }
</style>
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
<!--  Modal para baja con registro de fecha del empleado -->
<div id="modalAccionCorrectiva" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Acciones correctivas</h4>
      </div>
      <div class="modal-body">
	  	<div style="width:50%; margin-left:25%;">
        <div class="form-group">
            <label for="">Fecha de inicio del periodo</label>
            <input type="text" id="dateInicio">
        </div>
        <div class="form-group">
            <label for="">Fecha de finalización del periodo</label>
            <input type="text" id="dateFin">
        </div>        
		  </div>
	  </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
		<button type="button" class="btn btn-default" id="btnDwldAccionCorr">Confirmar</button>
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
    <li><a href="usuarios.php"><i class="icon-archive"></i><span>Reportes</span> </a> </li>
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
		<li><a href="asistencia.php"><i class="icon-file-clock-o"></i><span>Asistencias</span> </a> </li>
		<li><a href="parametros.php"><i class="icon-fa-cogs"></i><span>Parametros</span> </a> </li>
        <li><a href="usuarios.php"><i class="icon-user"></i><span>Usuarios</span> </a> </li>
        <li class="active"><a href="reportes.php"><i class="icon-archive"></i><span>Reportes</span> </a> </li>
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
      <div class="widget-header"> <i class="icon-edit"></i>
            <h3> Trabajadores</h3>
          </div>
          <!-- /widget-header -->
          <div class="widget-content" style="padding:10px">
             <ul class="moduloReportes">
                <!--<li>
                  <img src="/nomina/assets/images/descarga.png" style="width:3%; height:3%; padding-right:12px;" />
                  <a id="descargaReporteDocumentacion" style="cursor:pointer">Documentación</a>
                </li> -->
                <li>
                  <img src="/nomina/assets/images/descarga.png" style="width:3%; height:3%; padding-right:12px;margin-top:1%" />
                  <a id="descargaListaTrabajadores" style="cursor:pointer">Lista de Trabajadores</a>
                </li>                
              </ul>        
          </div>  
<!-- 
        <div class="widget-header"> <i class="icon-edit"></i>
          <h3> Socioecónomicos</h3>
        </div>
     
        <div class="widget-content" style="padding:10px">
           <ul class="moduloReportes">
              <li>
                <img src="/nomina/assets/images/descarga.png" style="width:3%; height:3%; padding-right:12px;" />
                <a id="descargaSocioeconomicos" style="cursor:pointer">Informe de estudios</a>
              </li>
            </ul>        
        </div> -->

        <div class="widget-header"> <i class="icon-edit"></i>
          <h3> Incidencias</h3>
        </div>
        <!-- /widget-header -->
        <div class="widget-content" style="padding:10px">
           <ul class="moduloReportes">
              <li>
                <img src="/nomina/assets/images/descarga.png" style="width:3%; height:3%; padding-right:12px;" />
                <a id="showModalAccionCorrectiva" style="cursor:pointer">Acciones correctivas</a>
              </li>
            </ul>      
        </div>

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
<script>
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
    
    
  $("#dateInicio").datepicker({
    changeMonth: true,
          changeYear: true,
    showButtonPanel: true,
    yearRange: '1930:+0'
  });

  $("#dateFin").datepicker({
    changeMonth: true,
          changeYear: true,
    showButtonPanel: true,
    yearRange: '1930:+0'
  });


  $("#btnDwldAccionCorr").click(function (e) { 
    e.preventDefault();
    const inicio = $("#dateInicio").val();
    const fin = $("#dateFin").val();
    $.get("/intranet/controladores/Reportes/nomina/accionescorrectivas.php", {
      fechaInicio: inicio,
      fechaFin: fin
    },
      function (data, textStatus, jqXHR) {
        window.open( `${data}`,'_blank',"width=200,height=100" )
      },
      "text"
    );
  });
  
  $("#showModalAccionCorrectiva").click(function (e) { 
    e.preventDefault();
    $("#modalAccionCorrectiva").modal("show");
  });

  $("#descargaSocioeconomicos").click(function (e) { 
    e.preventDefault(); 
    $.get("/nomina/ajax/reportes/empleados/socioeconomicos.php", {
      opc: "generar"
    },
      function (data, textStatus, jqXHR) {
        
        window.open( `/nomina/ajax/reportes/empleados/${data}`,'_blank',"width=200,height=100" )
      },
      "text"
    );
    
  });

  $("#descargaReporteDocumentacion").click(function (e) { 
    e.preventDefault(); 
    $.get("/nomina/ajax/reportes/empleados/documentacion.php", {
      opc: "generar"
    },
      function (data, textStatus, jqXHR) {
        
        window.open( `/nomina/ajax/reportes/empleados/${data}`,'_blank',"width=200,height=100" )
      },
      "text"
    );
    
  });

  $("#descargaListaTrabajadores").click(function (e) { 
    e.preventDefault(); 
    $.get("/nomina/ajax/reportes/empleados/TrabajadoresActivos.php", {
      opc: "generar"
    },
      function (data, textStatus, jqXHR) {
        
        window.open( `/nomina/ajax/reportes/empleados/${data}`,'_blank',"width=200,height=100" )
      },
      "text"
    );
    
  });

  
</script>
</body>
</html>
