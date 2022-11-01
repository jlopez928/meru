<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\CuentasxPagar\Reporte;

use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\Solpago;
use App\Models\Administrativo\Meru_Administrativo\General\DatosEmpresa;
use App\Http\Controllers\Controller;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Traits\ReportFpdf;
use App\Traits\PreMovimientos;

class RepComprobanteunoxmilController extends Controller
{
    use ReportFpdf;
    use PreMovimientos;
    public function print_generar_UNOXMIL(Solpago $solicititudpago)
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
		$pdf->Cell(140,4,'COMPROBANTE DE RETENCION DEL IMPUESTO ',0,0,'C',0);
		$pdf->Ln();
		$pdf->Cell(200,4,'UNO POR MIL',0,0,'C',0);
		$pdf->Header();

    }


}
