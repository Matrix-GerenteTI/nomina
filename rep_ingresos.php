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
	$paciente=$_GET['paciente'];
	$_GET['doctor']==""?$doctor="%":$doctor=$_GET['doctor'];
	$_GET['cirugia']==""?$cirugia="%":$cirugia = $_GET['cirugia'];
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
</script>
</head>
<body>
<table width="100%" cellpadding="0" cellspacing="0" style="padding-bottom:10px; border-bottom:#000 1px solid">
	<tr>
    	<td align="left"><i class="icon-user-md"></i>&nbsp;Fundaci&oacute;n Sin Obesidad M&eacute;xico</td>
        <td align="right"><div class="noPrint"><input type="button" value="Imprimir Reporte" onClick="imprimir()" /></div></td>
    </tr>
</table>
<table width="100%" cellpadding="0" cellspacing="0" style="border-bottom:#000 1px solid">
	<tr>
    	<td align="center" style="font-size:24px;padding:20px">Reporte de Registros de Usuarios<br/><span style="font-size:18px">(Del <?php echo $fecIni; ?> al <?php echo $fecFin; ?>)</span></td>
    </tr>
</table>
<table width="100%" cellpadding="0" cellspacing="0" style="border-top:#000 1px solid; margin-top:2px">
	<tr>
    	<th align="left">EQUIPO</th>
        <th align="left">NOMBRE</th>
        <th align="left">INICIAL</th>
        <th align="left">BAJADO</th>
        <th align="left">FECHA</th>
        <th align="left">EMAIL</th>
    </tr>
<?php
	$query = "SELECT 	*,
						c.cirugia as cirugia
			  FROM 		expedientes e,
			  			usuarios m,
						cirugias c 
			  WHERE 	e.idusuario=m.username 
			  AND 		e.cirugia=c.id 
			  AND		e.fecha>='".$fecIni."' AND e.fecha<='".$fecFin."'
			  AND		e.pnombre LIKE '%".$paciente."%'
			  AND		e.idusuario LIKE '".$doctor."'
			  AND		e.cirugia LIKE '".$cirugia."'
			  ORDER BY 	pnombre";
	$sql = mysql_query($query);
	$registros = 0;
	while($row = mysql_fetch_assoc($sql)){
		$registros++;
?>
    <tr>
    	<td style="padding: 3px 5px 3px 5px; font-size:11px"><?php echo $row['folio']; ?></td>
        <td style="padding: 3px 5px 3px 5px; font-size:11px"><?php echo strtoupper($row['pnombre']); ?></td>
        <td style="padding: 3px 5px 3px 5px; font-size:11px"><?php echo strtoupper($row['doctor']); ?></td>
        <td style="padding: 3px 5px 3px 5px; font-size:11px"><?php echo strtoupper($row['cirugia']); ?></td>
        <td style="padding: 3px 5px 3px 5px; font-size:11px"><?php echo formateaFecha($row['fecha']); ?></td>
        <td style="padding: 3px 5px 3px 5px; font-size:11px"><?php echo $row['idusuario']; ?></td>
    </tr>
<?php
	}
?>
	<tr>
    	<td colspan="6" style="padding-top:20px" align="right">Total de ingresos: <?php echo " ".$registros; ?></td>
    </tr>
</table>
</body>
</html>
