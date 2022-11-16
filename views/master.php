<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema para Timbrado de N&oacute;mina</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <!-- <link href="/nomina/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="/nomina/css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600"
            rel="stylesheet">
    <link href="/nomina/css/font-awesome.css" rel="stylesheet">
    <link href="/nomina/css/style.css" rel="stylesheet">
    <link href="/nomina/css/pages/dashboard.css" rel="stylesheet">    
    <?= $this->section("styles")  ?>
</head>
<body>
<div class="navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container-fluid"> <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"><span
                    class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span> </a><a class="brand" href="index.html"><i class="icon-book"></i>&nbsp;Nomina Matrix </a>
      <div class="nav-collapse">
        <ul class="nav pull-right">
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

<div class="subnavbar">
  <div class="subnavbar-inner">
    <div class="container-fñi">
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
      </ul>
    </div>
    <!-- /container --> 
  </div>
  <!-- /subnavbar-inner --> 
</div>

<div class="main">
    <div class="main-inner">
        <div class="container">
                <?= $this->section("maincontent") ?>
        </div>
    </div>
</div>
<script src="/nomina/js/vue.min.js"></script>
<script src="/nomina/js/axios.js"></script>
<?= $this->section("scripts") ?>
</body>
</html>