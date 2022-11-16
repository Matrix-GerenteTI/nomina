<?php
	require_once("ajax/control.php");
	require_once("ajax/mysql.php");
	conexion();
	
	function formateaFecha($fecha){
		$pos = strpos($fecha,"/");
		if($pos>0){
			$arr = explode("/",$fecha);
			$fechaNueva = $arr[2]."-".$arr[1]."-".$arr[0];
		}
		else{
			$arr = explode("-",$fecha);
			$fechaNueva = $arr[2]."/".$arr[1]."/".$arr[0];
		}
		return $fechaNueva;	
	}
	
	
	$_GET['fi']==""?$fecIni="2015-01-01":$fecIni=formateaFecha($_GET['fi']);
	$_GET['ff']==""?$fecFin=date("Y-m-d"):$fecFin=formateaFecha($_GET['ff']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>7SM - Fundaci&oacute;n Sin Obesidad M&eacute;xico</title>
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600"
        rel="stylesheet">
<link href="css/font-awesome.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<script src="js/jquery-1.7.2.min.js"></script>
<script>
	function imprimir(){
		$(".noPrint").hide();
		window.print();	
		$(".noPrint").show();
	}
	
	function cancelar(folio){
		if(confirm("¿Desa cancelar el recibo No. "+folio+"?")){
			$.post("ajax/ajaxcaja.php?op=cancelar", "folio="+folio, function(resp){
				if(resp>0){
					alert("El recibo se canceló existosamente");
					document.location.reload();
				}
				else
					alert("Ocurrió un error, intente nuevamente. Si el problema persiste contacte a soporte");
			});
		}
	}
	
	function imprimir(folio){
		alert("Ha ocurrido un problema con la configuracion de la impresion");	
	}
	
</script>
</head>
<body>
<table width="100%" cellpadding="0" cellspacing="0" style="padding-bottom:10px; border-bottom:#000 1px solid">
	<tr>
    	<td align="left"><i class="icon-user-md"></i>&nbsp;Fundaci&oacute;n Sin Obesidad M&eacute;xico</td>
        <td align="center"></td>
        <td align="right"><div class="noPrint"><input type="button" value="Imprimir Reporte" onClick="imprimir()" /></div></td>
    </tr>
</table>
<table width="100%" cellpadding="0" cellspacing="0" style="border-bottom:#000 1px solid">
	<tr>
    	<td align="center" style="font-size:24px;padding:20px">Reporte de Donaciones<br/><span style="font-size:18px">(Del <?php echo $fecIni; ?> al <?php echo $fecFin; ?>)</span></td>
    </tr>
</table>
<table width="100%" cellpadding="0" cellspacing="0" style="border-top:#000 1px solid; margin-top:2px">
	<tr>
    	<th align="left">FOLIO</th>
        <th align="left">EXP.</th>
        <th align="left">NOMBRE</th>
        <th align="left">VIA</th>
        <th align="left">MONTO</th>
        <th align="left">FECHA</th>
        <th align="left">USUARIO</th>
        <th align="left">STATUS</th>
        <th></th>
    </tr>
<?php
	$query = "SELECT 	*,
						CASE status
							WHEN 1 THEN 'Activo'
							WHEN 99 THEN 'Cancelado'
						END as status 
			  FROM 		recibos 
			  ORDER BY 	folio";
	$sql = mysql_query($query);
	$total = 0;
	$registros = 0;
	while($row = mysql_fetch_assoc($sql)){
		$registros++;
		if($row['status']=='Activo')
			$total = $total + $row['monto'];
?>
    <tr>
    	<td style="padding: 3px 5px 3px 5px; font-size:11px"><?php echo $row['folio']; ?></td>
        <td style="padding: 3px 5px 3px 5px; font-size:11px"><?php echo $row['idexpediente']; ?></td>
        <td style="padding: 3px 5px 3px 5px; font-size:11px"><?php echo $row['paciente']; ?></td>
        <td style="padding: 3px 5px 3px 5px; font-size:11px"><?php echo $row['concepto']; ?></td>
        <td style="padding: 3px 5px 3px 5px; font-size:11px"><?php echo "$ ".number_format($row['monto'],2,'.',','); ?></td>
        <td style="padding: 3px 5px 3px 5px; font-size:11px"><?php echo formateaFecha($row['fecha']); ?></td>
        <td style="padding: 3px 5px 3px 5px; font-size:11px"><?php echo $row['idusuario']; ?></td>
        <td style="padding: 3px 5px 3px 5px; font-size:11px"><?php echo $row['status']; ?></td>
        <td style="padding: 3px 5px 3px 5px; font-size:11px"><div class="noPrint">[<a href="javascript:imprimir(<?php echo $row['folio']; ?>)">Imprimir</a>]&nbsp;[<a href="javascript:cancelar(<?php echo $row['folio']; ?>)">Cancelar</a>]</div></td>
    </tr>
<?php
	}
?>
	<tr>
    	<td colspan="9" style="padding-top:20px" align="right">
        	<table align="right" cellpadding="0" cellspacing="0" style="font-size:16px">
            	<tr>
                	<td align="left">Total de emisiones:</td><td align="right"><?php echo " ".$registros; ?></td>
               	</tr>
                <tr>
                	<td align="left">Total de cobro:</td><td align="right"><?php echo "$ ".number_format($total,2,'.',','); ?></td>
               	</tr>
          	</table>
       	</td>
    </tr>
</table>
</body>
</html>
