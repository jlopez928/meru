<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\CuentasxPagar\Reporte;

use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\Solpago;
use App\Models\Administrativo\Meru_Administrativo\General\DatosEmpresa;
use App\Http\Controllers\Controller;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Traits\ReportFpdf;
use App\Traits\PreMovimientos;

class RepComprobanteISRLController extends Controller
{
    use ReportFpdf;
    use PreMovimientos;

    public function print_generar_ISLR(Solpago $solicititudpago)
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
        $pdf->SetFont('Times', 'B', 12);
		$pdf->SetY($Y + 5);
		$pdf->SetX(40);
		$pdf->Cell(140,4,'COMPROBANTE DE RETENCION DEL IMPUESTO',0,0,'C',0);
		$pdf->Ln();
		$pdf->Cell(200,4,'SOBRE LA RENTA (I.S.L.R)',0,0,'C',0);
		$pdf->Header();


		/** Encabezado */
	    $pdf->SetY($Y + 15);
		$pdf->SetFont('Times','',9);
		$pdf->SetFillColor(235,235,235);
		$pdf->SetX(10);
		$pdf->Cell(25,5,'FECHA:' . $datos_prov[0]->comprobantesretencionISLR[0]->fecha_comprobante, 0, 0, 'C', 0);
		$pdf->SetX(120);
		$pdf->Cell(45, 5, 'COMPROBANTE NRO: ' . $datos_prov[0]->comprobantesretencionISLR[0]->nro_comprobante, 0, 0, 'C', 0);
		$pdf->Ln();

        /** Datos del agente de Retención */
		$pdf->SetFillColor(235,235,235);
		$pdf->SetFont('Times', '', 9);
		$pdf->SetY($Y + 25);
		$pdf->SetX(10);
		$pdf->Cell(200, 5, 'DATOS DEL AGENTE DE RETENCION', 1, 0, 'C', 1);

		$pdf->SetY($Y + 30);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetFont('Times', '', 7);
		$pdf->SetX(10);
		$pdf->Cell(150, 5, 'Razon Social: ' . utf8_decode($empresa->nombre), 'L,T', 0, 'L', 1);
		$pdf->SetX(180);
		$pdf->Cell(30, 5, '' . $empresa->rif, 'R,T', 0, 'L', 1); //Rif de la empresa
		$pdf->Ln();
		$pdf->SetX(10);
		$pdf->Cell(170, 5, 'Domicilio Fiscal: ' . utf8_decode($empresa->direccion), 'L,B', 0, 'L', 1);
		$pdf->SetX(180);
		$pdf->Cell(30, 5, '' . $empresa->nit, 'R,B', 0, 'L', 1); //Nit de la Empresa

		/** Datos del Contribuyente */
		if ($datos_prov[0]->beneficiario->tipo_proveedor== 'N' || $datos_prov[0]->beneficiario->tipo_proveedor == 'F'){
			$cod_ret = 'I2';

			if ($datos_prov[0]->beneficiario->residente == 'S')
	           $tipo_proveedor = 'Natural Residente';
	        else
	           $tipo_proveedor = 'Natural NO Residente';
		}else{
			$cod_ret    = 'I1';

	        if ($datos_prov[0]->beneficiario->residente == 'S')
	            $tipo_proveedor = 'Juridica Domiciliada';
	        else
	            $tipo_proveedor = 'Juridica no Domicialiada';
		}
        $pdf->SetFillColor(235, 235, 235);
		$pdf->SetFont('Times', '', 9);
		$pdf->SetY($Y + 40);
		$pdf->SetX(10);
		$pdf->Cell(200, 5, 'DATOS DEL CONTRIBUYENTE', 1, 0, 'C', 1);

		$pdf->SetY($Y  + 45);
		$pdf->SetFillColor(255,255,255);
		$pdf->SetFont('Times', '', 7);
		$pdf->SetX(10);
		$pdf->Cell(135, 5, 'Razon Social : ' . utf8_decode($datos_prov[0]->beneficiario->nom_ben), 'L,T', 0, 'L', 1);
		$pdf->SetX(145);
		$pdf->Cell(65, 5, 'Tipo de Persona : ' . $tipo_proveedor, 'R,T', 0, 'L', 1);
		$pdf->Ln();
		$pdf->SetX(10);
		$pdf->Cell(200, 5, 'RIF : ' . $datos_prov[0]->beneficiario->rif_ben, 'L,R,B', 0, 'L', 1);
		$pdf->Ln();

		/** Datos del Comprobante */
		$pdf->SetY($Y  + 55);
		$pdf->SetFillColor(235, 235, 235);
		$pdf->SetFont('Times','B',6);

		$pdf->SetX(10);
		$pdf->Cell(18,4,'Numeral Literal','L,R,T',0,'C',1);
		$pdf->SetX(28);
		$pdf->Cell(48,4,'Concepto de Retencion (Decreto Nro.1808)','L,R,T',0,'C',1);
		$pdf->SetX(76);
		$pdf->Cell(10,4,'% Ret.','L,R,T',0,'C',1);
		$pdf->SetX(86);
		$pdf->Cell(15,4,'Nro Factura','L,R,T',0,'C',1);
		$pdf->SetX(101);
		$pdf->Cell(15,4,'Nro Control','L,R,T',0,'C',1);
		$pdf->SetX(116);
		$pdf->Cell(15,4,'Nota de','L,R,T',0,'C',1);
		$pdf->SetX(131);
		$pdf->Cell(15,4,'Nro Factura','L,R,T',0,'C',1);
		$pdf->SetX(146);
		$pdf->Cell(16,4,'Base Imponible','L,R,T',0,'C',1);
		$pdf->SetX(162);
		$pdf->Cell(16,4,'Tot. Acum. Mes','L,R,T',0,'C',1);
		$pdf->SetX(178);
		$pdf->Cell(12,4,'Sustraendo','L,R,T',0,'C',1);
		$pdf->SetX(190);
		$pdf->Cell(20,4,'Impuesto Retenido','L,R,T',0,'C',1);
		$pdf->Ln();

        //ojppj revisar esto
       /* if (strpos($datos_tip_nota[0]['id_des'], 'NC') === false)
        $encabe = 'Débito';
            else
        $encabe = 'Crédito';*/
        $encabe = 'Crédito';

        $pdf->Cell(18,4,'','L,R,B',0,'C',1);
		$pdf->SetX(28);
		$pdf->Cell(48,4,'','L,R,B',0,'C',1);
		$pdf->SetX(76);
		$pdf->Cell(10,4,'','L,R,B',0,'C',1);
		$pdf->SetX(86);
		$pdf->Cell(15,4,'','L,R,B',0,'C',1);
		$pdf->SetX(101);
		$pdf->Cell(15,4,'','L,R,B',0,'C',1);
		$pdf->SetX(116);
		$pdf->Cell(15,4,utf8_decode($encabe),'L,R,B',0,'C',1);
		$pdf->SetX(131);
		$pdf->Cell(15,4,'Afectada','L,R,B',0,'C',1);
		$pdf->SetX(146);
		$pdf->Cell(16,4,'','L,R,B',0,'C',1);
		$pdf->SetX(162);
		$pdf->Cell(16,4,'','L,R,B',0,'C',1);
		$pdf->SetX(178);
		$pdf->Cell(12,4,'','L,R,B',0,'C',1);
		$pdf->SetX(190);
		$pdf->Cell(20,4,'','L,R,B',0,'C',1);
		$pdf->Ln();

		$total_base_ret   = 0;
		$total_sustraendo = 0;
		$total_retenido	  = 0;
		$total_acum_mes	  = 0;

        $Y_Pie = $Y_Pie + 5;
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Times','B',6);
        $comprobante=$datos_prov[0]->comprobantesretencionISLR[0]->comprobantesdetretencion;
        $pdf->SetX(10);
        $pdf->Cell(18,5,$comprobante->nro_literal,1,0,'C',1);
        $pdf->SetX(28);
        $pdf->Cell(48,5,$comprobante->concepto,1,0,'C',1);
        $pdf->SetX(76);
        $pdf->Cell(10,5,$comprobante->por_retencion,1,0,'C',1);
        $pdf->SetX(86);
        $pdf->Cell(15,5,$comprobante->nro_factura,1,0,'C',1);
        $pdf->SetX(101);
        $pdf->Cell(15,5,$comprobante->nro_control,1,0,'C',1);
        $pdf->SetX(116);
        $pdf->Cell(15,5,$comprobante->nro_notacredito,1,0,'C',1);
        $pdf->SetX(131);
        $pdf->Cell(15,5,$comprobante->nro_fac_afec,1,0,'C',1);

		/** Base Imponible */

        if ($cod_ret == 'I1')
				$base_imp = $comprobante->mto_base_ret;
			else
				$base_imp = $comprobante->base_imp + $comprobante->base_exe;

			$total_base_ret += $base_imp;

			$pdf->SetX(146);
			$pdf->Cell(16, 5, $this->formatNumber($base_imp,2,",",$PUNTODECIMAL,"(") , 1, 0, 'C', 1);


            /** Tot. Acum. Mes */
			$acum_mes = $comprobante->mto_base_ret;

			if($comprobante->cod_ret == 'I1')
				$total_acum_mes += $acum_mes;
			else
				$total_acum_mes = $acum_mes;

			$pdf->SetX(162);
			$pdf->Cell(16,5,  $this->formatNumber($acum_mes,2,",",$PUNTODECIMAL,"(") ,1,0,'C',1);


			/** Sustraendo */
			$total_sustraendo += $comprobante->mto_sustraendo;

			$pdf->SetX(178);
			$pdf->Cell(12,5, $this->formatNumber($comprobante->mto_sustraendo,2,",",$PUNTODECIMAL,"(") ,1,0,'C',1);

			/** Impuesto Retenido */
			$total_retenido += $comprobante->mto_retencion;

			$pdf->SetX(190);
			$pdf->Cell(20,5,  $this->formatNumber($comprobante->mto_retencion,2,",",$PUNTODECIMAL,"(") ,1,0,'C',1);
			$pdf->Ln();

            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetFont('Times', 'B', 7);
            $pdf->SetX(10);
            $pdf->Cell(136, 5, 'TOTALES', 1, 0, 'C', 1);
            $pdf->SetX(146);
            $pdf->SetFont('Times', 'B', 6);
            $pdf->Cell(16, 5, $this->formatNumber($total_base_ret,2,",",$PUNTODECIMAL,"(") , 1, 0, 'C', 1);
            $pdf->SetX(162);
            $pdf->Cell(16, 5, $this->formatNumber($total_acum_mes,2,",",$PUNTODECIMAL,"(") , 1, 0, 'C', 1);
            $pdf->SetX(178);
            $pdf->Cell(12, 5, $this->formatNumber($total_sustraendo,2,",",$PUNTODECIMAL,"(") , 1, 0, 'C', 1);
            $pdf->SetX(190);
            $pdf->Cell(20, 5, $this->formatNumber($total_retenido,2,",",$PUNTODECIMAL,"(") , 1, 0, 'C', 1);
            $pdf->Ln();

            /** Firma y Sello */
            $pdf->SetY($Y_Pie);
            $pdf->SetFillColor(235, 235, 235);
            $pdf->SetFont('Times', '', 7);
            $pdf->SetX(12);
            $pdf->Cell(90, 5, 'Firma y Sello Proveedor', 0, 0, 'C', 0);
            $pdf->SetX(102);
            $pdf->Cell(98, 5, 'Firma y Sello Agente Retencion', 0, 0, 'C', 0);
            $pdf->SetY($Y_Pie+10);
            $pdf->SetX(12);
            $pdf->Cell(90, 5, '______________________________', 0, 0, 'C', 0);
            $pdf->SetX(102);
            $pdf->Cell(98, 5, '______________________________', 0, 0, 'C', 0);
            $pdf->SetY($Y_Pie + 15);
            $pdf->Cell(90, 5, 'Fecha', 0, 0, 'C', 0);
            $pdf->SetX(102);
            $pdf->Cell(98, 5, 'Administracion', 0, 0, 'C', 0);

    }
}
