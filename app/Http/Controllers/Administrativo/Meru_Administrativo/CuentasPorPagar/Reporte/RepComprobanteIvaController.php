<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\CuentasxPagar\Reporte;

use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\Solpago;
use App\Models\Administrativo\Meru_Administrativo\General\DatosEmpresa;
use App\Http\Controllers\Controller;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Traits\ReportFpdf;
use App\Traits\PreMovimientos;

class RepComprobanteIvaController extends Controller
{
    use ReportFpdf;
    use PreMovimientos;

    public function print_generar_iva(Solpago $solicititudpago)
    {
        $empresa = DatosEmpresa::where('cod_empresa','01')->first();
        $pdf = new Fpdf('p','mm','letter');
        $pdf->AddPage("P");
        $this->doReporte( $pdf ,$empresa, $solicititudpago,  5, 75);
        $pdf->line(10, 130, 210, 130);
        $this->doReporte( $pdf ,$empresa, $solicititudpago,  138, 208);
        header('Content-type: application/pdf');
        $pdf->Output();
        exit();
    }

    public function doReporte( $pdf ,$empresa, $solicititudpago,$Y, $Y_Pie)
    {   $PUNTODECIMAL=',';
        $datos_prov[0]=$solicititudpago;
        $pdf->Image('img/hidrobolivar.jpg', 10,$Y,40,15,'JPG');
		$pdf->Image('img/fondonorma.jpg', 190,$Y,18,18,'JPG');
		$pdf->Image('img/titulohidrobolivar.png', 95,$Y-4.5,30,8,'PNG');
		$pdf->Image('img/rangel.png', 10,$Y_Pie+30,25,15,'PNG');
		$pdf->Image('img/logo_zamora.jpg', 100,$Y_Pie+32,20,9,'JPG');
		$pdf->Image('img/iso.png', 200,$Y_Pie+30,10,15,'PNG');


		$pdf->SetFont('Times','B',12);
		$pdf->SetY($Y + 5);
		$pdf->SetX(40);
		$pdf->Cell(140,4,'COMPROBANTE DE RETENCION DEL IMPUESTO',0,0,'C',0);
		$pdf->Ln();
		$pdf->Cell(200,4,'AL VALOR AGREGADO (I.V.A)',0,0,'C',0);
		$pdf->Header();
        /** Encabezado */
	    $pdf->SetY($Y + 20);
		$pdf->SetFont('Times','',9);
		$pdf->SetFillColor(235,235,235);
		$pdf->SetX(10);
		$pdf->Cell(25,5,'FECHA: ' . $datos_prov[0]->comprobantesretencion[0]->fecha_comprobante,0,0,'C',0);
		$pdf->SetX(120);
		$pdf->Cell(45,5,'COMPROBANTE NRO: ' . $datos_prov[0]->comprobantesretencion[0]->nro_comprobante,0,0,'C',0);
		$pdf->Ln();
        /** Datos del Agente de Retención */
        $pdf->SetFillColor(235,235,235);
        $pdf->SetFont('Times','B',9);
        $pdf->SetY($Y + 30);
        $pdf->SetX(10);
        $pdf->Cell(201,5,'DATOS DEL AGENTE DE RETENCION',1,0,'C',1);
        $pdf->SetY($Y + 35);
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Times','',8);
        $pdf->SetX(10);
        $pdf->Cell(151,5,'Razon Social: ' . $empresa->nombre,'L,T',0,'L',1);
        $pdf->SetX(180);
        $pdf->Cell(31,5,'' . $empresa->rif,'R,T',0,'L',1);  //Rif de la empresa
        $pdf->Ln();
        $pdf->SetX(10);
        $pdf->Cell(201,5,'Domicilio Fiscal: ' . $empresa->direccion,'R,L',0,'L',1);
        /** Datos del Proveedor */
        $pdf->SetFillColor(235,235,235);
        $pdf->SetFont('Times','B',9);
        $pdf->SetY($Y + 45);
        $pdf->SetX(10);
        $pdf->Cell(201,5,'DATOS DEL PROVEEDOR',1,0,'C',1);
        $pdf->SetY($Y + 50);
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Times','',9);
        $pdf->SetX(10);
        $pdf->Cell(201,5,'Nombre o Razon Social: ' . utf8_decode($datos_prov[0]->beneficiario->nom_ben),'L,T,R',0,'L',1);
        $pdf->Ln();
        $pdf->SetX(10);
        $pdf->Cell(201,5,'RIF: ' . $datos_prov[0]->beneficiario->rif_ben,'L,R,B',0,'L',1);
        $pdf->Ln();
        /** Datos del Comprobante */
        $pdf->SetY($Y + 60);
        $Ycopia = $Y + 60;
        $pdf->SetFillColor(235,235,235);
        $pdf->SetFont('Times','B',6);
        $pdf->SetX(10);
        $pdf->Cell(8,4,'Nro.','L,T',0,'C',1);
        $pdf->SetX(18);
        $pdf->Cell(13,4,'Fecha de','L,R,T',0,'C',1);
        $pdf->SetX(31);
        $pdf->Cell(28,4,utf8_decode('Nro Factura o Nota Débito '),'L,R,T',0,'C',1);
        $pdf->SetX(58);
        $pdf->Cell(26,4,'Nro Control','L,R,T',0,'C',1);
        $pdf->SetX(84);
        $pdf->Cell(14,4,'Nota de','L,R,T',0,'C',1);
        $pdf->SetX(98);
        $pdf->Cell(14,4,'Nro Factura','L,R,T',0,'C',1);
        $pdf->SetX(112);
        $pdf->Cell(25,4,'Total Compras','L,R,T',0,'C',1);
        $pdf->SetX(137);
        $pdf->Cell(15,4,'Base','L,R,T',0,'C',1);
        $pdf->SetX(152);
        $pdf->Cell(15,4,'Compras','L,R,T',0,'C',1);
        $pdf->SetX(167);
        $pdf->Cell(11,4,'%Alicuota','L,R,T',0,'C',1);
        $pdf->SetX(178);
        $pdf->Cell(18,4,'Impuesto IVA','L,R,T',0,'C',1);
        $pdf->SetX(196);
        $pdf->Cell(15,4,'IVA','L,R,T',0,'C',1);
        $pdf->Ln();
        $pdf->SetX(10);
        $pdf->Cell(8,4,'Oper.','L,R,B',0,'C',1);
        $pdf->SetX(18);
        $pdf->Cell(13,4,'Factura','L,R,B',0,'C',1);
        $pdf->SetX(31);
        $pdf->Cell(27,4,'','L,R,B',0,'C',1);
        $pdf->SetX(58);
        $pdf->Cell(26,4,'','L,R,B',0,'C',1);
       // dd(count($datos_prov[0]->comprobantesretencion[0]->comprobantesdetretencionND));
        if ($datos_prov[0]->comprobantesretencion[0]->comprobantesdetretencionND->count() != 0)
        $encabe = 'Débito';
        else
        $encabe = 'Crédito';

        $pdf->SetX(84);
		$pdf->Cell(14,4,utf8_decode($encabe),'L,R,B',0,'C',1);
		$pdf->SetX(98);
		$pdf->Cell(14,4,'Afectada','L,R,B',0,'C',1);
		$pdf->SetX(112);
		$pdf->Cell(25,4,'Incluyendo IVA','L,R,B',0,'C',1);
		$pdf->SetX(137);
		$pdf->Cell(15,4,'Imponible','L,R,B',0,'C',1);
		$pdf->SetX(152);
		$pdf->Cell(15,4,'Exentas','L,R,B',0,'C',1);
		$pdf->SetX(167);
		$pdf->Cell(11,4,'','L,R,B',0,'C',1);
		$pdf->SetX(178);
		$pdf->Cell(18,4,'','L,R,B',0,'C',1);
		$pdf->SetX(196);
		$pdf->Cell(15,4,'Retenido','L,R,B',0,'C',1);
		$pdf->Ln();

		$monto_factura 		 = 0;
		$monto_baseimponible = 0;
		$monto_basexcenta	 = 0;
		$monto_iva			 = 0;
		$monto_iva_retenido	 = 0;
        $datos_comprob=$datos_prov[0]->comprobantesretencion[0]->comprobantesdetretencion;

        $Y_Pie = $Y_Pie + 5;
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Times','B',7);
        $pdf->SetX(10);
        $pdf->Cell(8,5,$datos_comprob->nro_renglon,1,0,'C',1);
        $pdf->SetX(18);
        $fec_vec = explode('-',$datos_prov[0]->fec_fac);

        $fec_factura = substr($fec_vec[2],0,2) . '/' . $fec_vec[1] . '/' . $fec_vec[0];
        $pdf->Cell(13,5,   $fec_factura, 1, 0, 'C', 1);
        $pdf->SetX(31);
        $pdf->Cell(27,5,$datos_comprob->nro_factura,1,0,'C',1);
        $pdf->SetX(58);
        $pdf->Cell(26,5,$datos_comprob->nro_control,1,0,'C',1);
        $pdf->SetX(84);
        $pdf->Cell(14,5,$datos_comprob->nro_notacredito,1,0,'C',1);
        $pdf->SetX(98);
        $pdf->Cell(14,5,$datos_comprob->nro_fac_afec,1,0,'C',1);
        $pdf->SetX(112);

        if($datos_comprob->mto_fac != '')
                    $valor_mto_fac = $datos_comprob->mto_fac;
            else
                $valor_mto_fac = '';

        $pdf->Cell(25,5,$this->formatNumber($valor_mto_fac,2,",",$PUNTODECIMAL,"("),1,0,'C',1);
        $monto_factura = $monto_factura + $datos_comprob->mto_fac;
        $pdf->SetX(137);
        if($datos_comprob->mto_base_imp != '')
            $valor_mto_base_imp = $datos_comprob->mto_base_imp;
        else
            $valor_mto_base_imp = '';
        $pdf->Cell(15,5,$this->formatNumber($valor_mto_base_imp,2,",",$PUNTODECIMAL,"("),1,0,'C',1);
        $monto_baseimponible = $monto_baseimponible + $datos_comprob->mto_base_imp;
        $pdf->SetX(152);

        if($datos_comprob->mto_base_exc != '')
            $valor_mto_base_exc = $datos_comprob->mto_base_exc;
        else
                $valor_mto_base_exc = '';

        $pdf->Cell(15,5,$this->formatNumber($valor_mto_base_exc,2,",",$PUNTODECIMAL,"("),1,0,'C',1);
        $monto_basexcenta = $monto_basexcenta + $datos_comprob->mto_base_exc;
        $pdf->SetX(167);

        if($datos_comprob->por_retencion != '')
            $valor_por_iva = $datos_comprob->por_retencion;
        else
            $valor_por_iva = '';

        $pdf->Cell(11,5,$this->formatNumber($valor_por_iva,2,",",$PUNTODECIMAL,"("),1,0,'C',1);
        $pdf->SetX(178);

        if($datos_comprob->mto_iva != '')
            $valor_mto_iva = $datos_comprob->mto_iva;
        else
            $valor_mto_iva = '';

        $pdf->Cell(18,5,$this->formatNumber($valor_mto_iva,2,",",$PUNTODECIMAL,"("),1,0,'C',1);
        $monto_iva = $monto_iva + $datos_comprob->mto_iva;
        $pdf->SetX(196);
        if($datos_comprob != '')
            $valor_iva_retenido = $datos_comprob->mto_retencion;
        else
            $valor_iva_retenido = '';

        $pdf->Cell(15,5,$this->formatNumber($valor_iva_retenido,2,",",$PUNTODECIMAL,"("),1,0,'C',1);
        $monto_iva_retenido = $monto_iva_retenido + $datos_comprob->mto_retencion;
        $pdf->Ln();
        $pdf->SetFillColor(255,255,255);
		$pdf->SetFont('Times','B',8);
		$pdf->SetX(10);
		$pdf->Cell(102,5,'TOTAL',1,0,'C',1);
		$pdf->SetX(112);
		$monto_factura = $monto_factura;
		$pdf->Cell(25,5,$this->formatNumber($monto_factura,2,",",$PUNTODECIMAL,"("),1,0,'C',1);
		$pdf->SetX(137);
        $monto_baseimponible = $monto_baseimponible;
		$pdf->Cell(15,5,$this->formatNumber($monto_baseimponible,2,",",$PUNTODECIMAL,"("),1,0,'C',1);
		$pdf->SetX(152);
        $monto_basexcenta = $monto_basexcenta;
		$pdf->Cell(15,5,$this->formatNumber($monto_basexcenta,2,",",$PUNTODECIMAL,"("),1,0,'C',1);
		$pdf->SetX(167);
		$pdf->Cell(11,5,'',1,0,'C',1);
		$pdf->SetX(178);
        $monto_iva = $monto_iva;
		$pdf->Cell(18,5,$this->formatNumber($monto_iva,2,",",$PUNTODECIMAL,"("),1,0,'C',1);
		$pdf->SetX(196);
        $monto_iva_retenido = $monto_iva_retenido;
		$pdf->Cell(15,5,$this->formatNumber($monto_iva_retenido,2,",",$PUNTODECIMAL,"("),1,0,'C',1);
		$pdf->Ln();

		/** Firma y Sello */
		$pdf->SetFillColor(235,235,235);
		$pdf->SetY($Y_Pie + 10);
		$pdf->SetX(12);
		$pdf->Cell(90,5,'______________________________',0,0,'C',0);
		$pdf->SetX(102);
		$pdf->Cell(98,5,'______________________________',0,0,'C',0);
		$pdf->SetY($Y_Pie + 15);
		$pdf->Cell(90,5,'Fecha',0,0,'C',0);
		$pdf->SetX(102);
		$pdf->Cell(98,5,'Administracion',0,0,'C',0);
		$pdf->SetY($Y_Pie + 20);
		$pdf->SetFont('Times','',8);
		$pdf->SetX(12);
		$pdf->Cell(90,5,'Firma y Sello Proveedor',0,0,'C',0);
		$pdf->SetX(102);
		$pdf->Cell(98,5,'Firma y Sello Agente Retencion',0,0,'C',0);
        if ($datos_prov[0]->status == '4'){
			$pdf->SetX(10);
			$pdf->SetY($Y_Pie + 40);
			$pdf->SetFillColor(235,235,235);
			$pdf->SetFont('Times','B',10);
			$pdf->Cell(13,5,'NOTA: ',0,0,'L',0);
			$pdf->SetX(23);
			$pdf->SetFont('Times','',10);
			$pdf->Cell(50,5,'Copia fiel y exacta del original.',0,0,'L',0);
		}
    }

}
