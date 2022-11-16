<?php
require('fpdf/fpdf.php');
require_once('libreria.php');
conexion();
$GLOBALS['n'] = 0;
$GLOBALS['paginas'] = 1;
$GLOBALS['h'] = 0;
//GENERAR EL PDF YA QUE SI SE TIMBRÓ	
class PDF extends FPDF{
	
	var $widths;
	var $heightL;
	var $aligns;
	
	var $javascript;
	var $n_js;

	function IncludeJS($script) {
		$this->javascript=$script;
	}

	function _putjavascript() {
		$this->_newobj();
		$this->n_js=$this->n;
		$this->_out('<<');
		$this->_out('/Names [(EmbeddedJS) '.($this->n+1).' 0 R]');
		$this->_out('>>');
		$this->_out('endobj');
		$this->_newobj();
		$this->_out('<<');
		$this->_out('/S /JavaScript');
		$this->_out('/JS '.$this->_textstring($this->javascript));
		$this->_out('>>');
		$this->_out('endobj');
	}

	function _putresources() {
		parent::_putresources();
		if (!empty($this->javascript)) {
			$this->_putjavascript();
		}
	}

	function _putcatalog() {
		parent::_putcatalog();
		if (!empty($this->javascript)) {
			$this->_out('/Names <</JavaScript '.($this->n_js).' 0 R>>');
		}
	}


	function AutoPrint($dialog=false)
	{
		//Open the print dialog or start printing immediately on the standard printer
		$param=($dialog ? 'true' : 'false');
		$script="print($param);";
		$this->IncludeJS($script);
	}
	
	function AutoPrintToPrinter($server, $printer, $dialog=false)
	{
		//Print on a shared printer (requires at least Acrobat 6)
		$script = "var pp = getPrintParams();";
		if($dialog)
			$script .= "pp.interactive = pp.constants.interactionLevel.full;";
		else
			$script .= "pp.interactive = pp.constants.interactionLevel.automatic;";
		$script .= "pp.printerName = '\\\\\\\\".$server."\\\\".$printer."';";
		$script .= "print(pp);";
		$this->IncludeJS($script);
	}
	
	function truncateFloat($number, $digitos)
	{
		$raiz = 10;
		$multiplicador = pow ($raiz,$digitos);
		$resultado = ((int)($number * $multiplicador)) / $multiplicador;
		return number_format($resultado, $digitos);
	 
	}
	
	function num2letras($num, $fem = false, $dec = true) { 
	   $matuni[2]  = "dos"; 
	   $matuni[3]  = "tres"; 
	   $matuni[4]  = "cuatro"; 
	   $matuni[5]  = "cinco"; 
	   $matuni[6]  = "seis"; 
	   $matuni[7]  = "siete"; 
	   $matuni[8]  = "ocho"; 
	   $matuni[9]  = "nueve"; 
	   $matuni[10] = "diez"; 
	   $matuni[11] = "once"; 
	   $matuni[12] = "doce"; 
	   $matuni[13] = "trece"; 
	   $matuni[14] = "catorce"; 
	   $matuni[15] = "quince"; 
	   $matuni[16] = "dieciseis"; 
	   $matuni[17] = "diecisiete"; 
	   $matuni[18] = "dieciocho"; 
	   $matuni[19] = "diecinueve"; 
	   $matuni[20] = "veinte"; 
	   $matunisub[2] = "dos"; 
	   $matunisub[3] = "tres"; 
	   $matunisub[4] = "cuatro"; 
	   $matunisub[5] = "quin"; 
	   $matunisub[6] = "seis"; 
	   $matunisub[7] = "sete"; 
	   $matunisub[8] = "ocho"; 
	   $matunisub[9] = "nove"; 
	
	   $matdec[2] = "veint"; 
	   $matdec[3] = "treinta"; 
	   $matdec[4] = "cuarenta"; 
	   $matdec[5] = "cincuenta"; 
	   $matdec[6] = "sesenta"; 
	   $matdec[7] = "setenta"; 
	   $matdec[8] = "ochenta"; 
	   $matdec[9] = "noventa"; 
	   $matsub[3]  = 'mill'; 
	   $matsub[5]  = 'bill'; 
	   $matsub[7]  = 'mill'; 
	   $matsub[9]  = 'trill'; 
	   $matsub[11] = 'mill'; 
	   $matsub[13] = 'bill'; 
	   $matsub[15] = 'mill'; 
	   $matmil[4]  = 'millones'; 
	   $matmil[6]  = 'billones'; 
	   $matmil[7]  = 'de billones'; 
	   $matmil[8]  = 'millones de billones'; 
	   $matmil[10] = 'trillones'; 
	   $matmil[11] = 'de trillones'; 
	   $matmil[12] = 'millones de trillones'; 
	   $matmil[13] = 'de trillones'; 
	   $matmil[14] = 'billones de trillones'; 
	   $matmil[15] = 'de billones de trillones'; 
	   $matmil[16] = 'millones de billones de trillones'; 
	   
	   //Zi hack
	   $float=explode('.',$num);
	   $num=$float[0];
	
	   $num = trim((string)@$num); 
	   if ($num[0] == '-') { 
		  $neg = 'menos '; 
		  $num = substr($num, 1); 
	   }else 
		  $neg = ''; 
	   while ($num[0] == '0') $num = substr($num, 1); 
	   if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num; 
	   $zeros = true; 
	   $punt = false; 
	   $ent = ''; 
	   $fra = ''; 
	   for ($c = 0; $c < strlen($num); $c++) { 
		  $n = $num[$c]; 
		  if (! (strpos(".,'''", $n) === false)) { 
			 if ($punt) break; 
			 else{ 
				$punt = true; 
				continue; 
			 } 
	
		  }elseif (! (strpos('0123456789', $n) === false)) { 
			 if ($punt) { 
				if ($n != '0') $zeros = false; 
				$fra .= $n; 
			 }else 
	
				$ent .= $n; 
		  }else 
	
			 break; 
	
	   } 
	   $ent = '     ' . $ent; 
	   if ($dec and $fra and ! $zeros) { 
		  $fin = ' coma'; 
		  for ($n = 0; $n < strlen($fra); $n++) { 
			 if (($s = $fra[$n]) == '0') 
				$fin .= ' cero'; 
			 elseif ($s == '1') 
				$fin .= $fem ? ' una' : ' un'; 
			 else 
				$fin .= ' ' . $matuni[$s]; 
		  } 
	   }else 
		  $fin = ''; 
	   if ((int)$ent === 0) return 'Cero ' . $fin; 
	   $tex = ''; 
	   $sub = 0; 
	   $mils = 0; 
	   $neutro = false; 
	   while ( ($num = substr($ent, -3)) != '   ') { 
		  $ent = substr($ent, 0, -3); 
		  if (++$sub < 3 and $fem) { 
			 $matuni[1] = 'una'; 
			 $subcent = 'as'; 
		  }else{ 
			 $matuni[1] = $neutro ? 'un' : 'uno'; 
			 $subcent = 'os'; 
		  } 
		  $t = ''; 
		  $n2 = substr($num, 1); 
		  if ($n2 == '00') { 
		  }elseif ($n2 < 21) 
			 $t = ' ' . $matuni[(int)$n2]; 
		  elseif ($n2 < 30) { 
			 $n3 = $num[2]; 
			 if ($n3 != 0) $t = 'i' . $matuni[$n3]; 
			 $n2 = $num[1]; 
			 $t = ' ' . $matdec[$n2] . $t; 
		  }else{ 
			 $n3 = $num[2]; 
			 if ($n3 != 0) $t = ' y ' . $matuni[$n3]; 
			 $n2 = $num[1]; 
			 $t = ' ' . $matdec[$n2] . $t; 
		  } 
		  $n = $num[0]; 
		  if ($n == 1) { 
			 $t = ' ciento' . $t; 
		  }elseif ($n == 5){ 
			 $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t; 
		  }elseif ($n != 0){ 
			 $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t; 
		  } 
		  if ($sub == 1) { 
		  }elseif (! isset($matsub[$sub])) { 
			 if ($num == 1) { 
				$t = ' mil'; 
			 }elseif ($num > 1){ 
				$t .= ' mil'; 
			 } 
		  }elseif ($num == 1) { 
			 $t .= ' ' . $matsub[$sub] . '?n'; 
		  }elseif ($num > 1){ 
			 $t .= ' ' . $matsub[$sub] . 'ones'; 
		  }   
		  if ($num == '000') $mils ++; 
		  elseif ($mils != 0) { 
			 if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub]; 
			 $mils = 0; 
		  } 
		  $neutro = true; 
		  $tex = $t . $tex; 
	   } 
	   $tex = $neg . substr($tex, 1) . $fin; 
	   //Zi hack --> return ucfirst($tex);
	   $end_num=ucfirst($tex).' pesos '.$float[1].'/100 M.N.';
	   return $end_num; 
	}
	
	
	function SetWidths($w)
	{
		//Set the array of column widths
		$this->widths=$w;
	}
	
	function SetHeights($h)
	{
		//Set the array of column heights
		$this->heightL=$h;
	}
	
	function SetAligns($a)
	{
		//Set the array of column alignments
		$this->aligns=$a;
	}
	
	function Row($data)
	{
		//Calculate the height of the row
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		$h=5*$nb;
		//Issue a page break first if needed
		$this->CheckPageBreak($h);
		//Draw the cells of the row
		for($i=0;$i<count($data);$i++)
		{
			$w=$this->widths[$i];
			$h=$this->heightL;
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
			//Save the current position
			$x=$this->GetX();
			$y=$this->GetY();
			//Draw the border
			
			//$this->Rect($x,$y,$w,$h);
			$this->MultiCell($w,$h,$data[$i],0,$a,false);
			//Put the position to the right of the cell
			$this->SetXY($x+$w,$y);
		}
		//Go to the next line
		$this->Ln($h);
	}
	
	function Row2($data)
	{
		//Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb, $this->NbLines($this->widths[$i], $data[$i]));
    $h=5*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $this->Rect($x, $y, $w, $h);
        //Print the text
		$this->SetFillColor(238,236,236); 
        $this->MultiCell($w, 5, $data[$i], 0, $a, true);
        //Put the position to the right of the cell
        $this->SetXY($x+$w, $y);
    }
    //Go to the next line
    $this->Ln($h);
	}
	
	function CheckPageBreak($h)
	{
		//If the height h would cause an overflow, add a new page immediately
		if($this->GetY()+$h>($this->PageBreakTrigger-83))
		{
			$this->AddPage($this->CurOrientation);
			$this->SetMargins(6,20,20);
			$this->Ln(12);
			$GLOBALS['paginas']++;
		}
	}
	
	function NbLines($w, $txt)
	{
		//Computes the number of lines a MultiCell of width w will take
		$cw=&$this->CurrentFont['cw'];
		if($w==0)
			$w=$this->w-$this->rMargin-$this->x;
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s=str_replace("\r", '', $txt);
		$nb=strlen($s);
		if($nb>0 and $s[$nb-1]=="\n")
			$nb--;
		$sep=-1;
		$i=0;
		$j=0;
		$l=0;
		$nl=1;
		while($i<$nb)
		{
			$c=$s[$i];
			if($c=="\n")
			{
				$i++;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
				continue;
			}
			if($c==' ')
				$sep=$i;
			$l+=$cw[$c];
			if($l>$wmax)
			{
				if($sep==-1)
				{
					if($i==$j)
						$i++;
				}
				else
					$i=$sep+1;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
			}
			else
				$i++;
		}
		return $nl;
	}
	
	function mes($m){
		$m = $m*1;
		$mesLetras = array('','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic');
		return $mesLetras[$m];
	}
	
	function Header()
	{
		require_once("phpqrcode/qrlib.php");
		
		//$this->Image('logo.png',0,0,45); //LOGO
		
		
		//$this->Image('factura.jpg',0,0,214);
		
		
		$this->SetMargins(15,20,20);
		$this->SetFont('Arial','',10);
		//$this->Text(20,14,'',0,'C', 0);
		$query = "SELECT * FROM empresa";
		$sql = mysql_query($query);
		$rowEmisor = mysql_fetch_array($sql);
		$query = "SELECT * FROM facturas WHERE idventa='".$_GET['folio']."'";
		$sql = mysql_query($query);
		$row = mysql_fetch_array($sql);
		$query = "SELECT * FROM socios WHERE clave='".$row['cliente']."'";
		$sql = mysql_query($query);
		$rowReceptor = mysql_fetch_array($sql);
		
		$query = "SELECT * FROM ventas WHERE idventa=".$row['idventa'];
		$sql = mysql_query($query);
		$rowLogo = mysql_fetch_array($sql);
		$this->Image('logos/'.$rowLogo['logo'].'.jpg',14,12,43); //LOGO
		
		$totalTMP = number_format($row['total'],6,".","");
		$totalTMP = explode(".",$totalTMP);
		$enteros = "";
		$decimales = $totalTMP[1];
		
		$tamanioStr = 10 - strlen($totalTMP[0]);
		for($i=1;$i<=$tamanioStr;$i++){
			$enteros.= "0";
		}
		
		$enteros.= $totalTMP[0];
		$totalCBB = $enteros.".".$decimales;
		
		$cadenaCodigoBarras = "?re=".trim(str_replace("-","",$rowEmisor['rfc']))."&rr=".trim(str_replace("-","",$rowReceptor['ife']))."&tt=".$totalCBB."&id=".$row['UUID'];
		$tmpImg = QRcode::png($cadenaCodigoBarras, 'CBB.png', 'L', 4, 2);
		$this->Image("CBB.png",167,217,41);
		
		if($GLOBALS['n']==0){
			$this->SetY(10);
			
		}
		else
			$this->SetY(9);
		
		#DATOS DEL CFDI							
		$this->SetWidths(array(21,20, 77, 70));
		$this->SetAligns(array('C','C','L','R'));
		$this->Row(array('','', '', 'FACTURA: '.$row['serie'].$row['folio']));
		$this->Ln(2);
		$this->SetAligns(array('C','L','L'));
		$this->SetHeights(3);
		$this->SetFont('Arial','b',8);
		$this->Row(array('','', 'Folio Fiscal: ', 'No. de Certificado SAT:   '));
		$this->SetFont('Arial','',8);
		$this->Row(array('','', strtoupper($row['UUID']), $row['NoCertificadoSAT']));
		$this->Ln(3);
		$this->SetHeights(3);
		$this->SetFont('Arial','b',8);
		$this->Row(array('','', 'No de Serie del Certificado CSD:   ', 'Fecha y Hora de Certificacion:   '));
		$this->SetFont('Arial','',8);
		$this->Row(array('','', $row['noCertificado'], $row['FechaSAT']));
		$this->Ln(3);
		$this->SetHeights(3);
		$this->SetFont('Arial','b',8);
		$this->Row(array('','', 'REGIMEN:   ', 'Lugar y Fecha de Expedicion:'));
		$this->SetFont('Arial','',8);
		$this->Row(array('','', strtoupper(utf8_decode($rowEmisor['regimen'])), strtoupper(utf8_decode($row['LugarExpedicion'])).', '.date("d-m-Y h:i:s")));
		$this->Line(14,40,203,40);
		
		$this->Ln(6);
		$this->SetWidths(array(93,3,93));
		$this->SetAligns(array('L','C','L'));
		$this->SetFont('Arial','b',10);
		$this->Row(array(strtoupper('DATOS DEL EMISOR'), '',strtoupper('DATOS DEL CLIENTE')));
		$this->SetFont('Arial','',8);
		$this->Ln(3);
		$this->Row(array(strtoupper(utf8_decode($rowEmisor['nombre'])),'',trim(strtoupper(utf8_decode($rowReceptor['nombre']).' '.utf8_decode($rowReceptor['paterno']).' '.utf8_decode($rowReceptor['materno'])))));
		$this->SetFont('Arial','',8);
		$this->Row(array(strtoupper(utf8_decode($rowEmisor['rfc'])),'', trim(strtoupper(utf8_decode($rowReceptor['ife'])))));
		$this->SetHeights(3);
		$this->Row(array(strtoupper(utf8_decode($rowEmisor['calle']).' '.utf8_decode($rowEmisor['noExterior']).', '.utf8_decode($rowEmisor['colonia']).' C.P. '.utf8_decode($rowEmisor['codigoPostal']).'. '.utf8_decode($rowEmisor['municipio']).', '.utf8_decode($rowEmisor['estado'])),'', strtoupper(utf8_decode($rowReceptor['calle']).' '.utf8_decode($rowReceptor['noExterior']).' '.utf8_decode($rowReceptor['noInterior']).', '.utf8_decode($rowReceptor['colonia']).' C.P. '.utf8_decode($rowReceptor['codigoPostal']).'. '.utf8_decode($rowReceptor['ciudad']).', '.utf8_decode($rowReceptor['estado']))));	
		$this->Ln(4);			
		$this->Row(array('Tel. '.strtoupper(utf8_decode($rowEmisor['telefono'])),'',strtoupper(utf8_decode($rowReceptor['telefono']))));
		$this->Line(14,65,203,65);
		$this->Ln(4);
		$this->SetWidths(array(187));
		$this->SetFont('Arial','',9);
		$this->SetAligns(array('C'));
		$this->Row2(array('DESCRIPCION'));
		if($GLOBALS['n']==0){
			$this->Ln(2);
			$GLOBALS['n']++;
		}
		/*else
			$this->Ln(3);*/
		
		#Expedido en
		/*$this->Ln(5);
		$this->SetTextColor(255);
		$this->SetWidths(array(56,7, 130, 40));
		$this->SetHeights(1);
		$this->SetTextColor(0);
		$this->SetAligns(array('C','C','L','C'));
		$this->Row(array(strtoupper(utf8_decode($row['leyenda'])),'', '', ''));*/
		
		
		
		
	}
	
	function Footer()
	{
		
		$query = "SELECT * FROM facturas WHERE idventa='".$_GET['folio']."'";
		$sql = mysql_query($query);
		$row = mysql_fetch_array($sql);
		
		$strConsulta = "SELECT 	SUM(d.cantidad) AS cantidad, 
								p.presentacion AS unidad, 
								d.color_material AS descripcion, 
								d.detalles AS detalles, 
								SUM(d.precio_unitario) AS precioUnitario, 
								SUM(d.importe) AS importeTotal 
						FROM 	pedidos_det d, producto p
						WHERE 	p.clave=d.clave
						AND		d.idventa='".$row['idventa']."' 
						AND 	d.status=2 
						GROUP BY d.clave";
		
		$historial = mysql_query($strConsulta);
		$lineas = 0;
		$rw = array();
		while($fila = mysql_fetch_array($historial)){
			$rw[] = $fila;
		}
		
		foreach($rw as $ro){
			$nb=0;
			#for($k=0;$k<25;$k++){
				$nb = $this->NbLines(187, strtoupper($ro['detalles']));			
				//$pdf->Cell(18,5,$nb,0,0,'C');
				$lineas = $lineas + $nb;
			#}
		}
		$paginas = ceil($lineas/17);
		
		$this->SetY(-77);
		$this->SetMargins(15,20,20);
		/*$this->SetFont('Arial','B',11);
		$this->SetWidths(array(36,26, 106,30));
		$this->SetAligns(array('C','C','R','R'));
		$this->Row(array('','', 'Subtotal:',number_format($row['subtotal'],2,'.',',')));
		$this->Row(array('','', 'Subtotal:',number_format($row['subtotal'],2,'.',',')));
		$this->Row(array('','', 'Subtotal:',number_format($row['subtotal'],2,'.',',')));
		*/
		$this->SetFont('Arial','b',9);
		$this->Cell(131,6,strtoupper($this->num2letras(number_format($row['total'],2,".",""))));//Importe con letra
		$this->SetFont('Arial','b',10);
		$this->Cell(27,6,'TOTAL:',0,0,'R');
		$this->Cell(29,6,number_format($row['total'],2,".",","),0,1,'R');//TOTAL
		$this->Line(14,213,203,213);
		$this->SetFont('Arial','b',8);
		$this->Ln(11);
		$this->Cell(74,5,$row['formaDePago'],1,0,'C');
		if($row['metodoDePago']!="EFECTIVO" && $row['metodoDePago']!="NO IDENTIFICADO"){
			$this->Cell(74,5,$row['metodoDePago'].' ['.$row['no_cuenta'].']',1,0,'C');
		}
		else{
			$this->Cell(74,5,$row['metodoDePago'],1,0,'C');	
		}
		
		#Sello SAT, Sello CSD y Cadena Original
		$this->Ln(7);
		$this->Cell(74,5,'Sello Digital del CFDI:',0,0,'L');
		$this->Ln(3);
		$cadena = $row['SelloCFD'];
		$tamanio = strlen($cadena);
		$parte1 = substr($cadena,0,97);
		$parte2 = substr($cadena,97,$tamanio);
		$this->SetFont('Arial','',8);
		$this->Cell(74,5,$parte1,0,0,'L');
		$this->Ln(3);
		$this->Cell(74,5,$parte2,0,0,'L');
		
		$this->Ln(4);
		$this->SetFont('Arial','B',8);
		$this->Cell(74,5,'Sello del SAT:',0,0,'L');
		$this->Ln(3);
		$cadena = $row['SelloSAT'];
		$tamanio = strlen($cadena);
		$parte1 = substr($cadena,0,92);
		$parte2 = substr($cadena,92,$tamanio);
		$this->SetFont('Arial','',8);
		$this->Cell(74,5,$parte1,0,0,'L');
		$this->Ln(3);
		$this->Cell(74,5,$parte2,0,0,'L');
		
		$this->Ln(4);
		$this->SetFont('Arial','B',8);
		$this->Cell(74,5,'Cadena original del complemento de certificacion digital del SAT:',0,0,'L');
		$this->Ln(3);
		$cadena = '||1.0|'.$row['UUID'].'|'.$row['FechaSAT'].'|'.$row['SelloCFD'].'|'.$row['noCertificado'].'|| ';
		$tamanio = strlen($cadena);
		$parte1 = substr($cadena,0,97);
		$parte2 = substr($cadena,97,90);
		$parte3 = substr($cadena,189,$tamanio);
		$this->SetFont('Arial','',8);
		$this->Cell(74,5,$parte1,0,0,'L');
		$this->Ln(3);
		$this->Cell(74,5,$parte2,0,0,'L');
		$this->Ln(3);
		$this->Cell(74,5,$parte3,0,0,'L');
		
		$this->SetFont('Arial','B',8);
		$this->Ln(3);
		
		$this->Ln(4);
		$this->Cell(100,5,'Pag. '.$GLOBALS['paginas'].'/'.$paginas,0,0,'L');
		$this->Cell(90,5,"Este documento es una representacion impresa de un CFDI",0,0,'R');
	}
	
	}
	
		$pdf=new PDF('P','mm','Letter');
		$pdf->Open();
		$pdf->AddPage();
		$pdf->SetMargins(15,20,20);	
		$pdf->SetAutoPageBreak(true);	
		
		$pdf->Ln(2);
		$query = "SELECT * FROM facturas WHERE idventa='".$_GET['folio']."'";
		$sql = mysql_query($query);
		$row = mysql_fetch_array($sql);
		
		$strConsulta = "SELECT 	SUM(d.cantidad) AS cantidad, 
								p.presentacion AS unidad, 
								d.color_material AS descripcion, 
								d.detalles AS detalles, 
								SUM(d.precio_unitario) AS precioUnitario, 
								SUM(d.importe) AS importeTotal 
						FROM 	pedidos_det d, producto p
						WHERE 	p.clave=d.clave
						AND		d.idventa='".$row['idventa']."' 
						AND 	d.status=2 
						GROUP BY d.clave ORDER BY importeTotal DESC";
		
		$historial = mysql_query($strConsulta);
		$numfilas = mysql_num_rows($historial);
		
		$pdf->SetFont('Arial','',7);
		//$pdf->SetFillColor(255);
		$pdf->SetTextColor(0);
		$pdf->SetWidths(array(18, 29, 94, 30, 30));
		$pdf->SetAligns(array('C','C','L','R','R'));
		$arr = array();
		while($rowt = mysql_fetch_array($historial)){		
			$arr[] = $rowt;
		}
		
		foreach($arr as $fila){
			$nb=0;	
			#for($k=0;$k<10;$k++){
				//$pdf->Row2(array($fila['cantidad'], $fila['unidad'], utf8_decode($fila['descripcion']).'LOREM IPSUM DORUM HUFJAM REFRASI LOMPRARUM KARUM', number_format($fila['precioUnitario'],2,'.',','), number_format($fila['importeTotal'],2,'.',',')));
				
				//$pdf->Cell(94,5,utf8_decode($fila['descripcion']),1,0,'L');
				//Calculate the height of the row
				$texto = strtoupper(utf8_encode($fila['detalles']));
				
				//$hR = strlen($
				
				$nb = $pdf->NbLines(187, $texto);
				$h=5*$nb;
				//Issue a page break first if needed
				$pdf->CheckPageBreak($h);
				
				/*$pdf->Cell(18,5,$fila['cantidad'],0,0,'C');
				$pdf->Cell(29,5,$fila['unidad'],0,0,'C');*/
				//Draw the cells of the row
				for($i=0;$i<1;$i++)
				{
					$w=187;
					$a='L';
					//Save the current position
					$x=$pdf->GetX();
					$y=$pdf->GetY();
					//Draw the border
					//$pdf->Rect($x, $y, $w, $h);
					//Print the text
					$pdf->MultiCell($w, 5, strtoupper(utf8_decode($texto)), 0, $a);
					//Put the position to the right of the cell
					$pdf->SetXY($x+$w, $y);
				}
				/*$pdf->Cell(30,5,number_format($fila['precioUnitario'],2,'.',','),0,0,'R');
				$pdf->Cell(30,5,number_format($fila['importeTotal'],2,'.',','),0,0,'R');*/
				
				//Go to the next line
				$pdf->Ln($nb*5);
				
				
			#}
		}
		$pdf->Ln(12);
		foreach($arr as $fila){
			$nb=0;	
			#for($k=0;$k<10;$k++){
				//$pdf->Row2(array($fila['cantidad'], $fila['unidad'], utf8_decode($fila['descripcion']).'LOREM IPSUM DORUM HUFJAM REFRASI LOMPRARUM KARUM', number_format($fila['precioUnitario'],2,'.',','), number_format($fila['importeTotal'],2,'.',',')));
				
				//$pdf->Cell(94,5,utf8_decode($fila['descripcion']),1,0,'L');
				//Calculate the height of the row
				$texto = strtoupper(utf8_encode($fila['descripcion']));
				
				//$hR = strlen($
				
				$nb = $pdf->NbLines(117, $texto);
				$h=5*$nb;
				//Issue a page break first if needed
				$pdf->CheckPageBreak($h);
				//Draw the cells of the row
				for($i=0;$i<1;$i++)
				{
					$w=117;
					$a='L';
					//Save the current position
					$x=$pdf->GetX();
					$y=$pdf->GetY();
					//Draw the border
					//$pdf->Rect($x, $y, $w, $h);
					//Print the text
					$pdf->SetFont('Arial','b',7);
					$pdf->MultiCell($w, 5, strtoupper(utf8_decode($texto)), 0, $a);
					//Put the position to the right of the cell
					$pdf->SetXY($x+$w, $y);
				}
				//$pdf->Cell(30,5,number_format($fila['precioUnitario'],2,'.',','),0,0,'R');
				$pdf->SetFont('Arial','',7);
				if($fila['importeTotal']<0){
					$pdf->Cell(40,5,$pdf->truncateFloat(($fila['importeTotal']*-1),2),0,1,'R');
					$pdf->Cell(117,5,'IVA 16%',0,0,'L');
					$pdf->Cell(40,5,$pdf->truncateFloat((($fila['importeTotal']*.16)*-1),2),0,0,'R');
				}else{
					$pdf->Cell(70,5,$pdf->truncateFloat($fila['importeTotal'],2),0,1,'R');
					$pdf->Cell(117,5,'IVA 16%',0,0,'L');
					$pdf->Cell(70,5,$pdf->truncateFloat(($fila['importeTotal']*.16),2),0,0,'R');
				}
				
				//Go to the next line
				$pdf->Ln($nb*5);				
			#}
		}
		
		$arrImp = array();
		$queryImpuestos = "SELECT i.impuesto as impuesto,vi.monto as monto,i.tasa as tasa FROM ventaimpuesto vi, impuestos i WHERE i.id=vi.impuestoid AND vi.ventaid='".$row['idventa']."' AND i.id<>1";
		$sqlImpuestos = mysql_query($queryImpuestos);
		while($rowImpuestos = mysql_fetch_array($sqlImpuestos)){
			$arrImp[] = $rowImpuestos;		
		}
		if(count($arrImp)>0){
			$pdf->SetFont('Arial','b',7);
			$pdf->Cell(117,5,"RETENCIONES",0,1,'L');
		}
		
		foreach($arrImp as $rw){
			$pdf->SetFont('Arial','',7);
			$pdf->Cell(117,5,number_format($rw['tasa'],2).'% '.$rw['impuesto'].":",0,0,'L');
			$pdf->Cell(40,5,$pdf->truncateFloat($rw['monto'],2),0,1,'R');//IVA	
		}
		
	$pdf->AutoPrint(true);
	$pdf->Output();
	
	
?>