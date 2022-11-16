<?php
require('fpdf/fpdf.php');
require_once("ajax/control.php");
require_once("ajax/mysql.php");
conexion();

function formateaFechaGUION($fecha){
	$arr = explode("-",$fecha);
	$fechaNueva = $arr[2]."/".$arr[1]."/".$arr[0];
	return $fechaNueva;	
}

function sino($valor){
	if($valor==1)
		return "SI";
	else
		return "NO";	
}

$mes = array(1=>"enero",
			 2=>"febrero",
			 3=>"marzo",
			 4=>"abril",
			 5=>"mayo",
			 6=>"junio",
			 7=>"julio",
			 8=>"agosto",
			 9=>"septiembre",
			 10=>"octubre",
			 11=>"noviembre",
			 12=>"diciembre");

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
        $this->MultiCell($w, 5, $data[$i], 0, $a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w, $y);
    }
    //Go to the next line
    $this->Ln($h);
	}
	
	function CheckPageBreak($h)
	{
		//If the height h would cause an overflow, add a new page immediately
		if($this->GetY()+$h>($this->PageBreakTrigger))
		{
			$this->AddPage($this->CurOrientation);
			$this->SetMargins(6,20,20);
			$this->Ln(12);
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
				
	}
	
	function Footer()
	{
		$this->SetY(-20);
		$this->SetFont('Arial','',10);
		$this->Line(13,258,205,258);
		$this->Cell(97,5,"6a Rus Poniente No. 881-A",0,0,'L');
		$this->Cell(97,5,"Teléfono y fax (01 961) 61 3 39 18",0,1,'R');
		$this->Cell(97,5,"Tuxtla Gutiérrez, Chiapas. México",0,0,'L');
		$this->Cell(97,5,"ruby-plastic@hotmail.com",0,0,'R');
		
	}
	
}
	
	$folio = $_GET['folio'];
	
	$query1 = "SELECT *,e.status as status,c.cirugia as cirugia,d.nombre as doctor FROM expedientes e,datosmedicos m,cirugias c,usuarios d WHERE e.folio=m.idexpediente AND e.cirugia=c.id AND e.doctor=d.nombre AND e.folio='".$folio."'";
	$sql1 = mysql_query($query1);
	$exp = mysql_fetch_assoc($sql1);
	
	$pdf=new PDF('P','mm','Letter');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->SetMargins(12,20,20);	
	$pdf->Image('img/logo.jpg',84,10,60);	
	$pdf->SetFont('Arial','b',14);
	$pdf->Cell(194,5,"EXPEDIENTE: ".$folio,0,1,'L');
	$pdf->Ln(40);
	$pdf->Cell(194,5,"HOJA DE INGRESO",0,1,'C');
	$pdf->SetFont('Arial','',10);
	$pdf->Ln(7);
	$pdf->Cell(30,5,"Fecha de Ingreso:",0,0,'L');
	$pdf->Cell(68,5,formateaFechaGUION($exp['fecha']),'B',0,'C');
	$pdf->Cell(35,5,"    Hora de Ingreso:",0,0,'L');
	$pdf->Cell(60,5,$exp['hora'],'B',1,'C');
	$pdf->Ln(4);
	$pdf->Cell(36,5,"Nombre del paciente:",0,0,'L');
	$pdf->Cell(157,5,utf8_decode($exp['pnombre']),'B',1,'L');
	$pdf->Ln(4);
	$pdf->Cell(37,5,"Fecha de Nacimiento:",0,0,'L');
	$pdf->Cell(27,5,formateaFechaGUION($exp['pfecnac']),'B',0,'C');
	$pdf->Cell(13,5,"  Edad:",0,0,'L');
	$pdf->Cell(10,5,$exp['pedad'],'B',0,'C');
	$pdf->Cell(20,5,"  Edo. Civil:",0,0,'L');
	$pdf->Cell(39,5,$exp['pedocivil'],'B',0,'C');
	$pdf->Cell(13,5,"  Sexo:",0,0,'L');
	$pdf->Cell(34,5,$exp['psexo'],'B',1,'C');
	$pdf->Ln(4);
	$pdf->Cell(34,5,"Domicilio particular:",0,0,'L');
	$pdf->Cell(159,5,utf8_decode($exp['pdomicilio']),'B',1,'L');
	$pdf->Ln(4);
	$pdf->Cell(33,5,"Teléfono particular:",0,0,'L');
	$pdf->Cell(33,5,$exp['ptelparticular'],'B',0,'C');
	$pdf->Cell(36,5,"  Teléfono de trabajo:",0,0,'L');
	$pdf->Cell(35,5,$exp['pteltrabajo'],'B',0,'C');
	$pdf->Cell(16,5,"  Celular:",0,0,'L');
	$pdf->Cell(40,5,$exp['pcelular'],'B',1,'C');
	$pdf->Ln(4);
	$pdf->Cell(13,5,"Email:",0,0,'L');
	$pdf->Cell(68,5,utf8_decode($exp['pemail']),'B',0,'C');
	$pdf->Cell(21,5,"  Facebook:",0,0,'L');
	$pdf->Cell(35,5,utf8_decode($exp['pfacebook']),'B',0,'C');
	$pdf->Cell(16,5,"  Twitter:",0,0,'L');
	$pdf->Cell(40,5,utf8_decode($exp['ptwitter']),'B',1,'C');
	$pdf->Ln(4);
	$pdf->Cell(42,5,"Nombre del responsable:",0,0,'L');
	$pdf->Cell(151,5,utf8_decode($exp['rnombre']),'B',1,'L');
	$pdf->Ln(4);
	$pdf->Cell(51,5,"En caso de emergencia avisar:",0,0,'L');
	$pdf->Cell(142,5,utf8_decode($exp['avisara']),'B',1,'L');
	$pdf->Ln(4);
	$pdf->Cell(19,5,"Teléfonos:",0,0,'L');
	$pdf->Cell(58,5,$exp['avisartelefonos'],'B',0,'C');
	$pdf->Cell(22,5,"  Peso (Kg):",0,0,'L');
	$pdf->Cell(10,5,$exp['peso'],'B',0,'C');
	$pdf->Cell(22,5,"  Talla (mts):",0,0,'L');
	$pdf->Cell(15,5,$exp['talla'],'B',0,'C');
	$pdf->Cell(13,5,"  IMC:",0,0,'L');
	$pdf->Cell(34,5,$exp['imc'],'B',1,'C');	
	$pdf->Ln(4);
	$pdf->Cell(51,5,"Operaciones y procedimientos:",0,0,'L');
	$pdf->Cell(142,5,utf8_decode($exp['operyproc']),'B',1,'L');
	
	$pdf->Ln(12);
	$pdf->SetFont('Arial','b',14);
	$pdf->Cell(194,5,"NOTA",0,1,'C');
	$pdf->SetFont('Arial','',10);
	$pdf->Ln(5);
	$pdf->SetWidths(array(194));
	$pdf->SetHeights(5);
	$pdf->SetAligns(array("J"));
	$pdf->Row(array("Por medio de la presente autorizo al Dr. ".utf8_decode($exp['doctor'])." y a su equipo médico que me efectúe la cirugía ".utf8_decode($exp['cirugia'])." en mi persona."));
	$pdf->Ln(8);
	$pdf->Row(array("Acepto y autorizo todos los servicios adicionales necesarios tales como radiografías, laboratorio, administración de medicamentos sugeridos por el médico tratante, al igual de interconsultas por especialistas."));
	$pdf->Ln(8);
	$pdf->Row(array("Se me ha explicado las complicaciones que pueden surgir de esta Cirugía Proyectada. Comprendo que cualquier complicación que pueda surgir requiere admisión o readmisión a un hospital o centro de cirugía, los gastos de las complicaciones tanto de hospital y honorarios médicos, no estan conectados de ninguna manera con el costo a Cirugía arriba mendionados."));
	
	//FIRMAS
	$pdf->Line(20,246,100,246);
	$pdf->Line(116,246,196,246);
	$pdf->Ln(40);
	$pdf->Cell(97,5,"Firma de Conformidad del Paciente",0,0,'C');
	$pdf->Cell(97,5,"Firma de Tutor o Responsable",0,0,'C');
	
	
	$pdf->AddPage();
	$pdf->SetMargins(12,20,20);
	$pdf->Image('img/logo.jpg',84,10,60);	
	$pdf->Ln(-9);
	$pdf->SetFont('Arial','b',14);
	$pdf->Cell(194,5,"EXPEDIENTE: 1",0,1,'L');
	$pdf->Ln(40);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(76,5,"¿En que fecha recibió su último examen físico?",0,0,'L');
	$pdf->Cell(116,5,utf8_decode($exp['ultimoexamenfisico']),'B',1,'C');
	$pdf->Ln(3);
	$pdf->Cell(63,5,"¿Última toma de radiografía de tórax?",0,0,'L');
	$pdf->Cell(129,5,utf8_decode($exp['ultimaradiografia']),'B',1,'C');
	$pdf->Ln(3);
	$pdf->Cell(85,5,"¿En que fecha recibió su último electrocardiograma?",0,0,'L');
	$pdf->Cell(107,5,utf8_decode($exp['ultimoelectrocardiograma']),'B',1,'C');
	$pdf->Ln(8);
	$pdf->SetFont('Arial','b',10);
	$pdf->SetWidths(array(97,97));
	$pdf->SetHeights(5);
	$pdf->SetAligns(array('L','L'));
	$pdf->Row(array("¿Que clase de anestesia ha recibido?","Usted"));
	$pdf->SetFont('Arial','',9);
	$pdf->SetWidths(array(75,22,85,12));
	$pdf->SetHeights(5);
	$pdf->SetAligns(array('L','L','L','L'));
	$pdf->Row(array("¿Raquia o Bloqueo Epidural?","[ ".sino($exp['anestesiaraquia'])." ]","¿Usa dientes postizos?","[ ".sino($exp['usteddientespostizos'])." ]"));
	$pdf->Row(array("¿Local?","[ ".sino($exp['anestesialocal'])." ]","¿Le faltan dientes o tiene dientes flojos?","[ ".sino($exp['usteddientesflojos'])." ]"));
	$pdf->Row(array("¿General?","[ ".sino($exp['anestesiageneral'])." ]","¿Estan cubiertos de porcelana permanente sus dientes?","[ ".sino($exp['ustedcubiertosporcelana'])." ]"));
	$pdf->Row(array("¿A presentado reacciones anormales?","[ ".sino($exp['anestesiareacciones'])." ]","¿Se le dificulta abrir la boca o moverla?","[ ".sino($exp['ustedabrirboca'])." ]"));
	$pdf->Row(array("¿A presentado fiebre en operaciones previas?","[ ".sino($exp['anestesiafiebre'])." ]","¿Usa pestañas postizas?","[ ".sino($exp['ustedpestaniaspostizas'])." ]"));
	$pdf->Row(array("","","¿Usa lentes de contacto?","[ ".sino($exp['ustedlentescontacto'])." ]"));
	$pdf->Row(array("","","¿Tiene defectos físicos mayores o congénitos?","[ ".sino($exp['usteddefectosfisicos'])." ]"));
	
	$pdf->Ln(8);
	$pdf->SetFont('Arial','b',10);
	$pdf->SetWidths(array(194));
	$pdf->SetHeights(5);
	$pdf->SetAligns(array('C'));
	$pdf->Row(array("Medicamentos que emplea usted actualmente"));
	$pdf->Ln(3);
	$pdf->SetFont('Arial','',9);
	$pdf->SetWidths(array(75,22,85,12));
	$pdf->SetHeights(5);
	$pdf->SetAligns(array('L','L','L','L'));
	$pdf->Row(array("Antidepresivos","[ ".sino($exp['medantidepresivos'])." ]","Medicamentos para la diabetes","[ ".sino($exp['meddiabetes'])." ]"));
	$pdf->Row(array("¿Cuál?: ".$exp['medantidepresivoscual'],"","¿Cuál?: ".$exp['meddiabetescual'],""));
	$pdf->Row(array("Antihipertensivos","[ ".sino($exp['medantihipertensivos'])." ]","¿Toma algún otro medicamento?","[ ".sino($exp['medotro'])." ]"));
	$pdf->Row(array("¿Cuál?: ".$exp['medantihipertensivoscual'],"","¿Cuál?: ".$exp['medotrocual1'],""));
	$pdf->Row(array("Anticuagulantes","[ ".sino($exp['medanticuagulantes'])." ]","¿Dosis?: ".$exp['medotrodosis1'],""));
	$pdf->Row(array("¿Cuál?: ".$exp['medanticuagulantescual'],"","¿Cuál?: ".$exp['medotrocual2'],""));
	$pdf->Row(array("¿Dosis?: ".$exp['medanticuagulantesdosis'],"","¿Dosis?: ".$exp['medotrodosis2'],""));
	
	$pdf->SetFont('Arial','',10);
	$pdf->Ln(8);
	$pdf->SetWidths(array(194));
	$pdf->SetHeights(5);
	$pdf->SetAligns(array("J"));
	$pdf->Row(array("Su anestesiólogo se comunicara con usted y le aconsejará sobre el tipo de anestesia y medicamentos que considera apropiado para ... Por lo general la anestesia empleada hoy en día se considera de bajo riesgo. No obstante ustede debe comprender que al igual que ... médico, la administración de la anestesia presenta cierto riesgos; que varían con los hábitos, edad y enfermedades agregadas de cada ... que es importante que usted lea y conteste detenidamente esta forma."));
	$pdf->Ln(20);
	$pdf->Row(array("Firme la pare inferior cuando este de acuerdo con lo que ha leído y completado adecuadamente."));
	//FIRMAS
	$pdf->Line(20,246,100,246);
	$pdf->Line(116,246,196,246);
	$pdf->Ln(32);
	$pdf->Cell(97,5,"Firma del Paciente",0,0,'C');
	$pdf->Cell(97,5,"Firma del anestesiólogo",0,0,'C');
	
	$pdf->AddPage();
	$pdf->SetMargins(12,20,20);
	$pdf->Image('img/logo.jpg',84,10,60);	
	$pdf->Ln(-9);
	$pdf->SetFont('Arial','b',14);
	$pdf->Cell(194,5,"EXPEDIENTE: 1",0,1,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Ln(40);
	$pdf->SetWidths(array(194));
	$pdf->SetHeights(5);
	$pdf->SetAligns(array("R"));
	$pdf->Row(array("Tuxtla Gutiérrez, Chiapas a ".date('d')." de ".$mes[(date("m")*1)]." del ".date("Y")));
	$pdf->Ln(20);
	$pdf->SetWidths(array(194));
	$pdf->SetHeights(10);
	$pdf->SetAligns(array("J"));
	$pdf->SetFont('Arial','b',10);
	$pdf->Row(array("A quien corresponda:"));
	$pdf->Ln(10);
	$pdf->SetFont('Arial','',10);
	$pdf->Row(array("Por medio de la presente, yo ".utf8_decode($exp['pnombre'])." autorizo al Dr. Roberto González Chame y al Sanatorio RC. Cirugía Cosmética, emplee el material fotográfico y/o de videográfico que documenta el procedimiento(s) a que me he sometido, para que sea empleado con fines de enseñanza y como referencia clínica de mi caso."));
	//FIRMAS
	$pdf->Line(60,206,162,206);
	$pdf->Ln(90);
	$pdf->Cell(194,5,utf8_decode($exp['pnombre']),0,1,'C');
	$pdf->Ln(2);
	$pdf->SetFont('Arial','b',10);
	$pdf->Cell(194,5,"Nombre y Firma del Paciente",0,0,'C');
	
	$pdf->AddPage();
	$pdf->SetMargins(12,20,20);
	$pdf->Image('img/logo.jpg',84,10,60);	
	$pdf->Ln(-9);
	$pdf->SetFont('Arial','b',14);
	$pdf->Cell(194,5,"EXPEDIENTE: 1",0,1,'L');
	$pdf->Image('img/body.jpg',50,67,120);	
	
	$pdf->AddPage();
	$pdf->SetMargins(12,20,20);
	$pdf->Image('img/logo.jpg',84,10,60);	
	$pdf->Ln(-9);
	$pdf->SetFont('Arial','b',14);
	$pdf->Cell(194,5,"EXPEDIENTE: 1",0,1,'L');
	$pdf->Image('img/face.jpg',30,67,160);	
	//$pdf->AutoPrint(true);
	$pdf->Output();
?>