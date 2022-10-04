<?php

namespace App\Traits;

trait ReportFpdf
{
	/****************************************************************
	 * Pinta el Encabezado de Reporte PDF
	 * @author
	 * @version 	1.0
	 * @param $pdf= La variable pdf
	 * @param $titulo= Titulo del Reporte
	 * @param $orientacion= H Horizontal V vertical
	 * @param $tipo_hoja= C Carta Oficio
	 * @param $gerencia= Gerencia del Reporte
	 * @param $division= Division del Reporte
	 * @param $cod_normalizacion= Codigo de Normalizacion del Reporte
	 *****************************************************************/
	function pintar_encabezado_pdf($pdf, $data) {
		if($data['tipo_hoja']=='C'){
			if ($data['orientacion']=='H') {
				$pdf->AddPage('L','Letter');
				$pdf->Image('img/logo_superior_izquierdo.png',10,10,30,12);
				$pdf->SetFont('Arial','I',5);
				$pdf->setxy(240,26);
				$pdf->Cell(30,5,'Generado: '.date("d/m/Y").' '.date("h:i:s"),0,0,'L');
				$pdf->setxy(240,28);
				$pdf->Cell(30,5,'PAG '.$pdf->PageNo().' DE {nb}',0,0,'L');
				$pdf->SetFont('Arial','B',6);
				if($data['cod_normalizacion']!=''){
					$pdf->setXY(242,9);
					$pdf->MultiCell(30,5,utf8_decode('Código: '.$data['cod_normalizacion']),0,'L',0);
				}
				$pdf->setxy(15,23);
				$pdf->Cell(20,5,utf8_decode($data['gerencia']),0,0,'C');
				$pdf->setxy(15,26);
				$pdf->Cell(20,5,utf8_decode($data['division']),0,0,'C');
				$pdf->SetFont('Arial','B',9);
				$pdf->setxy(15,20);
				$pdf->MultiCell(260,5,utf8_decode($data['titulo']),0,'C',0);
				$pdf->setxy(15,26);
				$pdf->MultiCell(260,5,utf8_decode($data['subtitulo']),0,'C',0);
			} else {
				$pdf->AddPage('P','Letter');
				$pdf->Image('img/logo_superior_izquierdo.png',10,13,30,12);
				$pdf->SetFont('Arial','I',5);
				$pdf->setxy(180,26);
				$pdf->Cell(30,5,'Generado: '.date("d/m/Y").' '.date("h:i:s"),0,0,'L');
				$pdf->setxy(180,28);
				$pdf->Cell(30,5,'PAG '.$pdf->PageNo().' DE {nb}',0,0,'L');
				$pdf->SetFont('Arial','B',6);
				if($data['cod_normalizacion']!=''){
					$pdf->setXY(180,9);
					$pdf->MultiCell(30,5,utf8_decode('Código: '.$data['cod_normalizacion']),0,'C',0);
				}
				$pdf->setxy(15,23);
				$pdf->Cell(20,5,utf8_decode($data['gerencia']),0,0,'C');
				$pdf->setxy(15,25);
				$pdf->Cell(20,5,utf8_decode($data['division']),0,0,'C');
				$pdf->SetFont('Arial','B',9);
				$pdf->setxy(15,20);
				$pdf->MultiCell(200,5,utf8_decode($data['titulo']),0,'C',0);
				$pdf->setxy(15,26);
				$pdf->MultiCell(200,5,utf8_decode($data['subtitulo']),0,'C',0);
			}
		} else { //Oficio
			if($data['tipo_hoja']=='O') {
				if ($data['orientacion']=='H') {
					$pdf->AddPage('L','Legal');
					$pdf->Image('img/logo_superior_izquierdo.png',10,13,30,12);
					$pdf->SetFont('Arial','I',5);
					$pdf->setxy(315,26);
					$pdf->Cell(30,5,'Generado: '.date("d/m/Y").' '.date("h:i:s"),0,0,'L');
					$pdf->setxy(315,28);
					$pdf->Cell(30,5,'PAG '.$pdf->PageNo().' DE {nb}',0,0,'L');
					$pdf->SetFont('Arial','B',6);
					if($data['cod_normalizacion']!=''){
						$pdf->setXY(318,9);
						$pdf->MultiCell(30,5,utf8_decode('Código: '.$data['cod_normalizacion']),0,'L',0);
					}
					$pdf->setxy(15,23);
					$pdf->Cell(20,5,utf8_decode($data['gerencia']),0,0,'C');
					$pdf->setxy(15,25);
					$pdf->Cell(20,5,utf8_decode($data['division']),0,0,'C');
					$pdf->SetFont('Arial','B',9);
					$pdf->setxy(15,20);
					$pdf->MultiCell(330,5,utf8_decode($data['titulo']),0,'C',0);
					$pdf->setxy(15,26);
					$pdf->MultiCell(330,5,utf8_decode($data['subtitulo']),0,'C',0);
				}else{
					$pdf->AddPage('P','Legal');
					$pdf->Image('img/logo_superior_izquierdo.png',10,13,30,12);
					$pdf->SetFont('Arial','I',5);
					$pdf->setxy(180,26);
					$pdf->Cell(30,5,'Generado: '.date("d/m/Y").' '.date("h:i:s"),0,0,'L');
					$pdf->setxy(180,28);
					$pdf->Cell(30,5,'PAG '.$pdf->PageNo().' DE {nb}',0,0,'L');
					$pdf->SetFont('Arial','B',6);
					if($data['cod_normalizacion']!=''){
						$pdf->setXY(180,9);
						$pdf->MultiCell(30,5,utf8_decode('Código: '.$data['cod_normalizacion']),0,'C',0);
					}
					$pdf->setxy(15,23);
					$pdf->Cell(20,5,utf8_decode($data['gerencia']),0,0,'C');
					$pdf->setxy(15,25);
					$pdf->Cell(20,5,utf8_decode($data['division']),0,0,'C');
					$pdf->SetFont('Arial','B',9);
					$pdf->setxy(15,20);
					$pdf->MultiCell(200,5,utf8_decode($data['titulo']),0,'C',0);
					$pdf->setxy(15,26);
					$pdf->MultiCell(200,5,utf8_decode($data['subtitulo']),0,'C',0);
				}
			} else {
				if ($data['orientacion']=='H') {
					$pdf->AddPage('L','A4');
					$pdf->Image('img/logo_superior_izquierdo.png',10,13,30,12);

					// Juan Durán
					$pdf->SetFont('Arial','I',5);
					$pdf->setxy(255,26);
					$pdf->Cell(30,5,'Generado: '.date("d/m/Y").' '.date("h:i:s"),0,0,'L');
					$pdf->setxy(255,28);
					$pdf->Cell(30,5,'PAG '.$pdf->PageNo().' DE {nb}',0,0,'L');
					$pdf->SetFont('Arial','B',6);
					// Juan Durán

					/*
					// Comentado Juan Durán
					$pdf->SetFont('Arial','I',5);
					$pdf->setxy(315,26);
					$pdf->Cell(30,5,'Generado: '.date("d/m/Y").' '.date("h:i:s"),0,0,'L');
					$pdf->setxy(315,28);
					$pdf->Cell(30,5,'PAG '.$pdf->PageNo().' DE {nb}',0,0,'L');
					$pdf->SetFont('Arial','B',6);
					*/

					if($data['cod_normalizacion']!=''){
						$pdf->setXY(318,9);
						$pdf->MultiCell(30,5,utf8_decode('Código: '.$data['cod_normalizacion']),0,'L',0);
					}
					$pdf->setxy(15,23);
					$pdf->Cell(20,5,utf8_decode($data['gerencia']),0,0,'C');
					$pdf->setxy(15,25);
					$pdf->Cell(20,5,utf8_decode($data['division']),0,0,'C');
					$pdf->SetFont('Arial','B',9);
					$pdf->setxy(15,20);
					//$pdf->MultiCell(330,5,utf8_decode($data['titulo']),0,'C',0);
					$pdf->MultiCell(260,5,utf8_decode($data['titulo']),0,'C',0);
					$pdf->setxy(15,26);
					//$pdf->MultiCell(330,5,utf8_decode($data['subtitulo']),0,'C',0);
					$pdf->MultiCell(260,5,utf8_decode($data['subtitulo']),0,'C',0);
				} else {
					$pdf->AddPage('P','A4');
					$pdf->Image('img/logo_superior_izquierdo.png',10,13,30,12);
					$pdf->SetFont('Arial','I',5);
					$pdf->setxy(180,26);
					$pdf->Cell(30,5,'Generado: '.date("d/m/Y").' '.date("h:i:s"),0,0,'L');
					$pdf->setxy(180,28);
					$pdf->Cell(30,5,'PAG '.$pdf->PageNo().' DE {nb}',0,0,'L');
					$pdf->SetFont('Arial','B',6);
					if($data['cod_normalizacion']!=''){
						$pdf->setXY(180,9);
						$pdf->MultiCell(30,5,utf8_decode('Código: '.$data['cod_normalizacion']),0,'C',0);
					}
					$pdf->setxy(15,23);
					$pdf->Cell(20,5,utf8_decode($data['gerencia']),0,0,'C');
					$pdf->setxy(15,25);
					$pdf->Cell(20,5,utf8_decode($data['division']),0,0,'C');
					$pdf->SetFont('Arial','B',9);
					$pdf->setxy(15,20);
					$pdf->MultiCell(200,5,utf8_decode($data['titulo']),0,'C',0);
					$pdf->setxy(15,26);
					$pdf->MultiCell(200,5,utf8_decode($data['subtitulo']),0,'C',0);
				}
			}
		}
    }

    /****************************************************************
     * Pinta la Cabecera de las Columnas del Listado PDF
     * @version 	1.0
     * @param $pdf= La variable pdf
     * @param $orientacion= H Horizontal V vertical
     * @param $alineacion_columnas= Arreglo q contiene la alineacion de las columnas
     * @param $ancho_columnas= Arreglo de contiene el ancho de las columnas
     * @param $nombre_columnas= Arreglo de Contiene el nombre de las columnas
     *****************************************************************/
    function pintar_cabecera_columnas_pdf($pdf, $data, $setX = true)
    {
		$y = $pdf->GetY();

		if ($data['fuente']!='') {
			$tamano=$data['fuente'];
		} else {
			$tamano=7;
		}

		$pdf->SetFont('Arial','B',$tamano);
		$pdf->SetTextColor(10);

		// Juan Durán
		if ($setX) {
			$x = $data['orientacion'] == 'H' ? 16 : 11;
			$pdf->SetXY($x, $y + 5);
		} else {
			$pdf->SetY($y + 5);
		}

		/*
		// Comentado Juan Durán
		if ($data['orientacion'] == 'H') {
			$pdf->SetXY(16, $y+5);
		} else {
			$pdf->SetXY(11, $y+5);
		}
		*/

		$pdf->SetFillColor(219,219,219);
		$pdf->SetAligns(array('C','C','C','C','C','C','C','C','C','C','C','C','C','C','C'));
		$pdf->SetWidths($data['ancho_columnas']);
    	//$pdf->Row($data['nombre_columnas'],4,1);
        $pdf->Row($data['nombre_columnas']); // Juan Durán
    }

    /****************************************************************
     * Pinta Los registros de un Listado PDF
     * @version 	1.0
     * @param $pdf= La variable pdf
     * @param $orientacion= H Horizontal V vertical
     * @param $alineacion_columnas= Arreglo q contiene la alineacion de las columnas
     * @param $ancho_columnas= Arreglo de contiene el ancho de las columnas
     * @param $campos= Arreglo con los datos a colocar en las columnas
     *****************************************************************/
	function pintar_registros_pdf($pdf,$data,$campos,$setX = true)
	{
		$y1 = $pdf->GetY();
		if($data['fuente']!=''){
			$data['tamano']=$data['fuente'];
		}else{
			$data['tamano']=7;
		}
		$pdf->SetFont('Arial','',$data['tamano']);
		$pdf->SetTextColor(10);

		// Juan Durán
		if ($setX) {
			$x = $data['orientacion'] == 'H' ? 16 : 11;
			$pdf->SetXY($x, $y1);
		} else {
			$pdf->SetY($y1);
		}

		/*
		if ($data['orientacion']=='H'){
			$pdf->SetXY(16, $y1);
		}else{
			$pdf->SetXY(11, $y1);
		}
		*/

		$pdf->SetFillColor(255,255,255);
		$pdf->SetAligns($data['alineacion_columnas']);
		$pdf->SetWidths($data['ancho_columnas']);
		//$pdf->Row($campos,4,1);
		$pdf->Row($campos); // Juan Durán
	}

	/****************************************************************
	 * Pinta la Cabecera de las Columnas del Listado PDF
	 * @version 	1.0
	 * @param $pdf= La variable pdf
	 * @param $orientacion= H Horizontal V vertical
	 * @param $alineacion_columnas= Arreglo q contiene la alineacion de las columnas
	 * @param $ancho_columnas= Arreglo de contiene el ancho de las columnas
	 * @param $nombre_columnas= Arreglo de Contiene el nombre de las columnas
	 *****************************************************************/
	function pintar_listado_pdf($pdf,$data,$setX = true)
	{
		$pdf->AliasNbPages();
		$this->pintar_encabezado_pdf($pdf,$data);
		$this->pintar_cabecera_columnas_pdf($pdf,$data,$setX);
		$y=0;
		foreach ($data['registros'] as $linea)
		{
			for ($i=0; $i< count($data['registros_mostar']); $i++){
				if($data['funciones_columnas'] != ''){
					$nombre_funcion='';
					foreach ($data['funciones_columnas'] as $campo => $funcion) {
						if($campo==$data['registros_mostar'][$i]){
							$nombre_funcion=$funcion;
						}
					}
					if($nombre_funcion!=''){
						$reg= $nombre_funcion($linea[$data['registros_mostar'][$i]]);
					}else{
						$reg=$linea[$data['registros_mostar'][$i]];
					}
				}else{
					$reg=$linea[$data['registros_mostar'][$i]];
				}
				$campos[$i]=$reg;
			}
			$yy = $pdf->GetY();

			if( $data['tipo_hoja']	=='C'){
				$largo_hoja=245;
			}else{
				$largo_hoja=320;
			}

			if($data['orientacion']=='H'){
				if ($yy>=170 ){
					$y=0;
					$this->pintar_footer_pdf($pdf,$data);
					$this->pintar_encabezado_pdf($pdf,$data);
					$this->pintar_cabecera_columnas_pdf($pdf,$data,$setX);

				}
			}else{
				if ($yy>=$largo_hoja ){
					$y=0;
					$this->pintar_footer_pdf($pdf,$data);
					$this->pintar_encabezado_pdf($pdf,$data);
					$this->pintar_cabecera_columnas_pdf($pdf,$data,$setX);
				}
			}

			$this->pintar_registros_pdf($pdf,$data,$campos,$setX);
		}

		$pdf->Output($data['nombre_documento'],'D');
	}

	/****************************************************************
	 * Pinta Footer del Listado PDF
	 * @version 	1.0
	 * @copyright 14/06/2013
	 * @param $pdf= La variable pdf
	 * @param $orientacion= H Horizontal V vertical+
	 * @param $tipo_hoja= C Carta Oficio
	 * @param $usuario= Usuario q Genera el Reporte
	 * @param $cod_reporte= Codigo del Reporte
	 *****************************************************************/
	function pintar_footer_pdf($pdf,$data){
		if($data['tipo_hoja']=='C'){
			if ($data['orientacion']=='H'){//CARTA HORIZONTAL
				$pdf->SetFont('Arial','',5);
				if($data['con_imagen']) {
					// $pdf->Image('img/log_cvg.png',15,189,11,11);
					// $pdf->Image('img/logo_venalum.jpg',250,187,10,10);
				}
				$pdf->setxy(15,185);
				$pdf->Cell(30,2,utf8_decode($data['usuario']),0,0,'L');
				$pdf->setxy(15,187);
				$pdf->Cell(30,2,utf8_decode($data['cod_reporte']),0,0,'L');
				$pdf->SetFont('Arial','B',5);
				if($data['vigencia']!=''){
					$pdf->setxy(35,193);
					$pdf->Cell(30,1,utf8_decode('Vigencia: '.$data['vigencia']),0,0,'L');
				}
				if($data['revision']!=''){
					$pdf->setxy(235,193);
					$pdf->Cell(30,1,utf8_decode('Revisión: '.$data['revision']),0,0,'L');
				}
			}else{// CARTA VERTICAL
				$pdf->SetFont('Arial','',5);
				if($data['con_imagen']){
					// $pdf->Image('img/log_cvg.png',15,254,11,11);
					// $pdf->Image('img/logo_venalum.jpg',195,252,10,10);
				}
				$pdf->setxy(15,250);
				$pdf->Cell(30,2,utf8_decode($data['usuario']),0,0,'L');
				$pdf->setxy(15,252);
				$pdf->Cell(30,2,utf8_decode($data['cod_reporte']),0,0,'L');
				$pdf->SetFont('Arial','B',5);
				if($data['vigencia']!=''){
					$pdf->setxy(35,258);
					$pdf->Cell(30,1,utf8_decode('Vigencia: '.$data['vigencia']),0,0,'L');
				}
				if($data['revision']!=''){
					$pdf->setxy(180,258);
					$pdf->Cell(30,1,utf8_decode('Revisión: '.$data['revision']),0,0,'L');
				}
			}
		}else{
			if ($data['orientacion']=='H'){//OFICIO HORIZONTAL
				$pdf->SetFont('Arial','',5);
				if($data['con_imagen']){
					// $pdf->Image('img/log_cvg.png',15,189,11,11);
					// $pdf->Image('img/logo_venalum.jpg',325,187,10,10);
				}
				$pdf->setxy(15,185);
				$pdf->Cell(30,2,utf8_decode($data['usuario']),0,0,'L');
				$pdf->setxy(15,187);
				$pdf->Cell(30,2,utf8_decode($data['cod_reporte']),0,0,'L');
				$pdf->SetFont('Arial','B',5);
				if($data['vigencia']!=''){
					$pdf->setxy(35,193);
					$pdf->Cell(30,1,utf8_decode('Vigencia: '.$data['vigencia']),0,0,'L');
				}
				if($data['revision']!=''){
					$pdf->setxy(310,193);
					$pdf->Cell(30,1,utf8_decode('Revisión: '.$data['revision']),0,0,'L');
				}
			}else{//OFICIO VERTICAL
				$pdf->SetFont('Arial','',5);
				if($data['con_imagen']){
					// $pdf->Image('img/log_cvg.png',15,330,11,11);
					// $pdf->Image('img/logo_venalum.jpg',195,330,10,10);
				}
				$pdf->setxy(5,271);
				$pdf->Cell(30,2,utf8_decode($data['usuario']),0,0,'L');
				$pdf->setxy(5,274);
				$pdf->Cell(30,2,utf8_decode($data['cod_reporte']),0,0,'L');
				$pdf->SetFont('Arial','B',5);
				if($data['vigencia']!=''){
					$pdf->setxy(35,332);
					$pdf->Cell(30,1,utf8_decode('Vigencia: '.$data['vigencia']),0,0,'L');
				}
				if($data['revision']!=''){
					$pdf->setxy(180,332);
					$pdf->Cell(30,1,utf8_decode('Revisión: '.$data['revision']),0,0,'L');
				}
			}
		}
	}
}
