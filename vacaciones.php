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
<link rel="stylesheet" href="/nomina/css/datepicker.min.css">
<link href="css/font-awesome.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/pages/dashboard.css" rel="stylesheet">
<style>



.wrapper {
  margin: 0 auto;
  padding: 40px;
  max-width: 800px;
}

.table {
  margin: 0 0 40px 0;
  width: 100%;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
  display: table;
}
@media screen and (max-width: 580px) {
  .table {
    display: block;
  }
}

.row {
  display: table-row;
  background: #f6f6f6;
}
.row:nth-of-type(odd) {
  background: #e9e9e9;
}
.row.header {
  font-weight: 900;
  color: #ffffff;
  background: #ea6153;
}
@media screen and (max-width: 580px) {
  .row {
    padding: 14px 0 7px;
    display: block;
  }
  .row.header {
    padding: 0;
    height: 6px;
  }
  .row.header .cell {
    display: none;
  }
  .row .cell {
    margin-bottom: 10px;
  }
  .row .cell:before {
    margin-bottom: 3px;
    content: attr(data-title);
    min-width: 98px;
    font-size: 10px;
    line-height: 10px;
    font-weight: bold;
    text-transform: uppercase;
    color: #969696;
    display: block;
  }
}

.cell {
  padding: 6px 12px;
  display: table-cell;
}
@media screen and (max-width: 580px) {
  .cell {
    padding: 2px 16px;
    display: block;
  }
}

    
    .grid-1 {
        display: grid;
        
        width: 100%;
        max-width: 2048px;
        margin: 0 auto;
        
        grid-template-columns: repeat(auto-fill, minmax(30em, 1fr));
        
        grid-gap: 20px;
        
    }
    .form-grid{
        display: grid;
        margin: 0 auto;
        grid-template-columns: repeat(auto-fill, minmax(20em, 1fr));
    }



    .container-lista{
        display: grid;
        margin: 0 auto;
        grid-template-columns: repeat(auto-fill, minmax(50em, 1fr));
    }

    .grid-1 .items{
        padding: 10px;
    }
    .-selected- .dp-note {
    bottom: 2px;
    background: #fff;
    opacity: .5;
}

.dp-note {
    background: #DF013A;
    width: 4px;
    height: 4px;
    border-radius: 50%;
    left: 50%;
    bottom: 1px;
    -webkit-transform: translateX(-50%);
    transform: translateX(-50%);
}
.dp-note, .nav {
    position: absolute;
}

</style>
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

    <?php require_once $_SERVER['DOCUMENT_ROOT']."/nomina/menu.php" ?>

<div class="main">
  <div class="main-inner">
    <div class="container">
      <div class="row">
        <div class="span12">
	
        <div class="form-actions" style="margin-top:0px; margin-bottom:0px; padding:5px;align:left">
                    <table width="10%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="75px">
                                <div class="shortcuts"> 
                                    <a href="javascript:buscar();" class="shortcut"><i class="shortcut-icon icon-search"></i><span class="shortcut-label">Buscar</span></a>&nbsp;
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>		

          <div class="widget widget-nopad">   
			<div class="widget-header"> <i class="icon-edit"></i>
              <h3> Vacaciones</h3>
            </div>
            <!-- /widget-header -->
            <div class="widget-content" style="padding:10px;">
                <section class="grid-1">
                    <div class="items" >
                        <div>
                            <input type="hidden" id="nip" >
                            <label for="">Empleado</label>
                            <input type="text" name="" id="nombre" style="width:100%">
                        </div>
                        <div class="form-grid">
                        <div>
                                <label for="">Fecha de Inicio Laboral</label>
                                <input type="text" name="" id="fechaInicioLab" disabled >      
                            </div>                            
                            <div>
                                <label for="">Antiguedad Laboral</label>
                                <input type="text" name="" id="antiguedad" disabled >                                
                            </div>

                        </div>
                        <div class="form-grid">
                            <div>
                                <label for="">Derecho a vacaciones </label>
                                <input type="text" name="" id="tieneVacaciones" disabled >
                            </div>
                            <div>
                                    <label for=""># Días programados</label>
                                    <input type="text" name="" id="diasProgramados" disabled>
                                </div>
                        </div>
                        <div class="form-grid">
                                <div >
                                        <label for="">Periodo Vacacional Act. </label>
                                        <table style=" border-collapse: collapse;">
                                            <tr>
                                                <td>
                                                    <input type="text"
                                                    class="datepicker-here "
                                                    placeholder="Establecer periodo"
                                                    data-language='es'
                                                    data-min-view="months"
                                                    autocomplete="off" 
                                                    data-view="months"
                                                    data-date-format="MM yyyy"
                                                    id="inicioPeriodo"
                                                    />                                                    
                                                </td>
                                                <!-- <td style="width:50%">
                                                    <input type="text"
                                                    id="finPeriodo"
                                                    style="width:100%"
                                                    class="datepicker-here "
                                                    placeholder="Finalización"
                                                    data-language='es'
                                                    data-min-view="months"
                                                    data-view="months"
                                                    data-date-format="MM yyyy" />                                                        
                                                </td> -->
                                            </tr>
                                        </table>
                                                                             
                                </div>

                        </div>
                    </div>
                    <div class="items">
                        <div class="form-grid">
                            <div id="fechasProgramada" class="datepicker-here" ></div>
                            <div>
                                    <div id="contentConfirmaVacaciones" style="display:none">
                                        <h3>Confirmar los siguientes Día(s) para Vacaciones: <span id="txtFechasConfirmar"></span></h3>
                                        <button id="confirmaVacaciones" class="shortcut">Confirmar</button>
                                    </div>
                                    
                                    <div style="display:none" id="containerCancelaVacaciones">
                                        <h3>El trabajador tiene vacaciones programadas en los dias: <span id="txtFechasCancelar"></span>  , ¿deseas cancelarla? </h3>   
                                        <button id="cancelarDiaVacacion">Confirmar</button>
                                        <button>Cancelar</button>                                        
                                    </div>                        
                            </div>
                        </div>

                    </div>
                </section>
                
          </div>

          <br><br>
          <div class="widget widget-nopad">   
			<div class="widget-header"> <i class="icon-edit"></i>
              <h3> Trabajadoes Programados </h3>
            </div>
            <!-- /widget-header -->
            <div class="widget-content" style="padding:10px;">
                <section class="container-lista">
                    <div class="items" >
                        <div class="wrapper">
    
                            <div class="table"  id="trabajadoresProgramados">                                
                            </div>  
                        </div>

                    </div>
                </section>
                
          </div>

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
<script src="/nomina/js/datepicker.min.js"></script>
<script src="/nomina/js/datepicker.es.js"></script>
<script type="text/javascript">
    let fechasProgramadas = [];
    let fechasTime = [];
    let fechasSeleccionadas = '';
    let fechasACancelar = [];
    let diasVacaciones = 0;
    let nipEmpleado = -1;
    let anioProgramar = new Date();
    anioProgramar = anioProgramar.getFullYear();

    let inicioPeriodo = undefined;
    let finPeriodo = undefined;

let pickerVacaciones = $("#fechasProgramada");
let minDate = new Date();
let maxDate = new Date();



$('#inicioPeriodo').datepicker({
    onSelect: function (fd , date) {
        minDate = new Date(date.getFullYear() , date.getMonth(), date.getDate() );

        maxDate = new Date(date.getFullYear() +1  , date.getMonth(), 0 );
        pickerVacaciones.datepicker({
            minDate: minDate,
            maxDate: maxDate
        })

    }
})



$('#fechasProgramada').datepicker({
    language: 'es',
})
// Access instance of plugin
$('#fechasProgramada').data('datepicker')				   
listarTrabajadoresConVacaciones();
	
	function formatearFecha(fecha){
		fec = fecha.split("-");
		return fec[2]+"/"+fec[1]+"/"+fec[0];	
	}
	
	
	function calculaTiempo(origen,destino,tipo){
		var fecha = $("#"+origen).val();
		$.post("ajax/ajaxempleado.php?op=calculaTiempo", "fecha="+fecha+"&tipo="+tipo, function(resp){
			//alert(resp);
			$("#"+destino).val(resp);
		});
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
	
	
	function loadmodal(nip){
		$("#busquedaEmpleados").modal('toggle');
		cargar(nip);
		
	}

	function cargar(nip){
		$.post("ajax/ajaxempleado.php?op=cargar", "nip="+nip, function(resp){
			//alert(resp);
			$(".btn-guarda").attr('href','javascript:actualizar();')
			var row = eval('('+resp+')');
			//Datos de pempleado
            $("#nip").val(row.nip);
            nipEmpleado = row.nip;
			
			$("#nombre").val(row.nombre);
            $("#antiguedad").val( row.antiguedad);
            $("#tieneVacaciones").val( row.diasVacaciones > 0  ? `${row.diasVacaciones} Días` : 'No');
            $("#fechaInicioLab").val( (row.fechainiciolab).replace(/-/g, "/") );
            diasVacaciones = row.diasVacaciones - (row.listaVacaciones).length;

			$("#diasProgramados").val( (row.listaVacaciones).length );

            recargaCalendario( row.listaVacaciones );
		});
    }
    
    function recargaCalendario( listaVacaciones ) {
            fechasProgramadas = [];
             fechasTime = [];
            let fechaRegistrada;
            let fechasMensaje = [];

            $.each(listaVacaciones, function (i, item) { 
                 splitedFecha = item.fecha.split("-");
                 fechaRegistrada = new Date(splitedFecha[0] , splitedFecha[1] -1 , splitedFecha[2] );

                 fechasProgramadas.push( fechaRegistrada  );
                 fechasTime.push( `${fechaRegistrada.getDate()}${fechaRegistrada.getMonth()}${fechaRegistrada.getFullYear()}` );
            });
            $('#fechasProgramada').datepicker().data('datepicker').clear();
            $('#fechasProgramada').datepicker({
                    language: 'es',
                    multipleDates: diasVacaciones ,
                    multipleDatesSeparator: ',',
                    maxDate: maxDate,
                    minDate: minDate,
                    onChangeMonthr: function (month, year) {
                         anioProgramar = year;
                         
                    },
                    onRenderCell: function (date, cellType) {
                        
                        // Add extra element, if `eventDates` contains `currentDate`                            
                            if (cellType == 'day' && fechasTime.indexOf( `${date.getDate()}${date.getMonth()}${date.getFullYear()}` ) != -1 ) {                                                                
                                return {
                                    html: date.getDate() + '<span class="dp-note"></span>'
                                }
                            }                             
                    },
                    onSelect: function onSelect(fd, date , picker ) {
                        let nSeleccionados = date.length ;
                        let yaProgramadas = [];                        
                        if ( nSeleccionados > 0 ) {
                            date.forEach( function ( item , i ) {                             
                                if( fechasTime.indexOf( `${item.getDate()}${item.getMonth()}${item.getFullYear()}` ) != -1 ){
                                    yaProgramadas.push( `${i}` );

                                    //Acá eliminamos las fechas que fueron seleccionadas para cancelar para luego volverl
                                    fechasACancelar.forEach( function ( value , j ) {
                                        if ( `${item.getDate() <10 ? '0' : ''}${item.getDate()}/${item.getMonth()+1 <10 ? '0' : ''}${item.getMonth()+1}/${item.getFullYear()}` == fechasACancelar[j]) {
                                            fechasACancelar.splice( j , 1 );
                                        }
                                        
                                    });

                                    fechasACancelar.push( `${item.getDate() <10 ? '0' : ''}${item.getDate()}/${item.getMonth() +1 <10 ? '0' : ''}${item.getMonth()+1}/${item.getFullYear()}` )
                                }else{

                                    if ( fechasMensaje.indexOf(`${item.getDate()}/${item.getMonth()+1}/${item.getFullYear()}` ) == -1  ) {
                                        fechasMensaje.push( `${item.getDate()}/${item.getMonth()+1}/${item.getFullYear()}`  );

                                    }
                                }
                            
                            });
                        }else{
                            fechasACancelar = [];
                            $("#contentConfirmaVacaciones").fadeOut();
                            $("#containerCancelaVacaciones").fadeOut();
                        }
                                       
                        
                        //Reocorriendo sobre todos los registos para obtener cuales fechas  ya fueron deseleccionadas
                        fechasMensaje.forEach( function ( value , n) {
                            
                            if ( fd.search( value) == -1  && fd.length > 0) {
                                console.log( fd + "               "+ value );
                               fechasMensaje.splice( n , 1 );
                                
                            }else if( fd.length == 0 && fechasMensaje.length == 0 ){
                                fechasMensaje = [];
                            }
                        })
                       
                        
                        //verificando que las fechas para cancelar coincidadn con las que hay seleccionadas

                        fechasACancelar.forEach( function ( value , j ) {                               
                                if ( fd.search( value )  == -1 ) {
                                    fechasACancelar.splice( j , 1 );
                                }        
                        });
                        
                         if ( yaProgramadas.indexOf( `${(nSeleccionados -1 )}`  ) != -1  && nSeleccionados > 0 ) {
                            
                            $("#contentConfirmaVacaciones").fadeOut();
                            $("#containerCancelaVacaciones").fadeIn();
                            $("#txtFechasCancelar").html( fechasACancelar.join(", ") );
                            
                         }else if(  nSeleccionados > 0 ) {
                            $("#contentConfirmaVacaciones").fadeIn();
                            
                            $("#txtFechasConfirmar").html( fechasMensaje.join(", ") );
                            $("#containerCancelaVacaciones").fadeOut();
                            fechasSeleccionadas = fd;
                         }
                         
                    }
                })
            
            // pickerVacaciones.data('datepicker').selectDate( fechasProgramadas  );
            $('#fechasProgramada').data('datepicker');
    }
    
    function listarTrabajadoresConVacaciones() {
        $.get("/intranet/controladores/nomina/trabajadores.php", {
            opc: 'listarVacaciones',

        },
            function (data, textStatus, jqXHR) {
                let template = `                         
                           <div class="row header">
                                    <div class="cell">
                                        Nombre
                                    </div>
                                    <div class="cell">
                                        Puesto
                                    </div>
                                    <div class="cell">
                                        Sucursal
                                    </div>
                                    <div class="cell">
                                        # Días vacaciones
                                    </div>
                                    <div class="cell">
                                       Constancia
                                    </div>                                    
                                </div>`;
                $.each(data, function (i, item) { 
                     template += `
                                        <div class="row">
                                                <div class="cell" data-title="Nombre">
                                                ${item.empleado}
                                                </div>
                                                <div class="cell" data-title="Puesto">
                                                ${item.puesto}
                                                </div>
                                                <div class="cell" data-title="Sucursal">
                                                ${item.sucursal}
                                                </div>
                                                <div class="cell" data-title="# Días programados">
                                                   -
                                                </div>
                                                <div class="cell" data-title="Constancia" style="cursor:pointer" onclick="printConstancia(${item.nip})">
                                                <i class="icon-print"></i><span>Imprimir</span> </a> </li>
                                                </div>
                                            </div>`;
                });
                $("#trabajadoresProgramados").html( template );
            },
            "json"
        );
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
    
    function printConstancia( idempleado ) {
        $.get("/nomina/ajax/controladores/formateria/vacaciones.php", {
            empleado : idempleado
        },
            function (data, textStatus, jqXHR) {
                window.open(data, "_blank");
            },
            "text"
        );
    }

    $("#confirmaVacaciones").click(function (e) { 
        e.preventDefault();
        //Buscando que la fecha ya haya sido seleccionada
        let fechas = fechasSeleccionadas.split(",");
        let index = [];
        fechas.forEach( function (item , i ) {      
             if( fechasTime.indexOf( `${ item.replace(/\//g, '' ) }` ) != -1 ){
                index.push( i );
             }
         })
         //Eliminando los indices que ya habían sido programados con anterioridad
         index.forEach( function ( value ) {
                fechas.splice( value , 1 );
           });
        let fechasAProgramar = fechas.join(",");
        $.post("/intranet/controladores/nomina/trabajadores.php?opc=agendarVacaciones", {
            trabajador: $("#nip").val(),
            fechas: fechasAProgramar,
            periodo: `${minDate.getMonth()}/${minDate.getFullYear()} A ${maxDate.getMonth()}/${maxDate.getFullYear()}`
        },
            function (data, textStatus, jqXHR) {
                listarTrabajadoresConVacaciones();
                cargar( nipEmpleado );
                if ( data.length > 0 ) {
                    alert("Se le han programado vacaciones al empleado")
                }else{
                    alert("Es posible que el trabajador no cuente con días disponibles para vacacionar");
                }
            },
            "json"
        );
    });

    $("#cancelarDiaVacacion").click(function (e) { 
        e.preventDefault();

        let fechasCancelar = fechasACancelar.join(",");
        $.post("/intranet/controladores/nomina/trabajadores.php?opc=actualizarVacaciones", {
            trabajador: $("#nip").val(),
            fechas: fechasCancelar,
            estado: "pendiente"
        },
            function (data, textStatus, jqXHR) {
                listarTrabajadoresConVacaciones();
                cargar( nipEmpleado );
                if ( data.length > 0 ) {
                    alert("Se ha realizado la operación seleccionada")
                }else{
                    alert("Ocurrió el siguiente error: "+data );
                }
            },
            "json"
        );

    });

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
