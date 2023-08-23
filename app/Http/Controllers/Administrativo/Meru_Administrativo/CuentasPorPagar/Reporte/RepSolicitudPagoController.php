<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\CuentasxPagar\Reporte;

use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\Solpago;
use App\Http\Controllers\Controller;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Traits\ReportFpdf;

class RepSolicitudPagoController extends Controller
{
    use ReportFpdf;

    public function print_generar_solicitud(Solpago $solicititudpago)
    {
        if(!empty($solicititudpago)){
            $encab[0]=$solicititudpago;
            $pdf = new Fpdf('p','mm','letter');
            $pdf->AddPage("P");

            $pdf->Image('img/logo_superior_izquierdo.png', 10,5,40,17,'PNG');
            $pdf->Image('img/logo_superior_derecho.png', 192,10,12,12,'PNG');
            $pdf->Image('img/logo_superior_centro.png', 80,5,60,8,'PNG');

            $pdf->Image('img/logo_inferior_izquierdo.png', 10,253,12,13,'PNG');
            $pdf->Image('img/logo_inferior_centro.png', 100,245,30,8,'PNG');
            $pdf->Image('img/logo_inferior_derecho.png', 195,254,10,11,'PNG');

            $pdf->SetY(10);
            $pdf->SetFont('Arial','B',5);
            $pdf->Cell(180,5,utf8_decode('Código: F-AF-035'),0,0,'R');
            $pdf->Ln(3);

            $pdf->SetFont('Arial','B',5);
            $pdf->Cell(180,5,utf8_decode('Vigencia: 24/01/2018'),0,0,'R');
            $pdf->Ln(3);

            $pdf->SetFont('Arial','B',5);
            $pdf->Cell(180,5,utf8_decode('Revisión: 2'),0,0,'R');
            $pdf->Ln(4);

            $pdf->SetFont('Arial','B',13);
            $pdf->SetY(27);
            $pdf->SetX(60);
            $pdf->Cell(100,4,'SOLICITUD DE PAGO',0,0,'C',0);
            $pdf->Header();
            $Y_Fields_Name_position = 25;
            $Posicion_Y = 94;
            $Y_Position = 156;
            $pdf->SetFillColor(235,235,235);
            $pdf->SetFont('Arial','B',9);
            $pdf->SetY(24);
            $pdf->SetX(165);
            $pdf->Cell(20,5,utf8_decode('Número:'),1,0,'L',1);
            $pdf->SetY(29);
            $pdf->SetX(165);
            $pdf->Cell(20,5,'Fecha:',1,0,'L',1);
            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',8);
            $pdf->SetY(24);
            $pdf->SetX(180);


            $pdf->Cell(30,5,$encab[0]['ord_pag'],1,0,'L',1);
            $pdf->SetY(29);
            $pdf->SetX(180);
            $pdf->Cell(30,5,$encab[0]['fecha']->format('d/m/Y') ,1,0,'L',1);
            $pdf->Header();
            $pdf->SetFillColor(205,205,205);
            $pdf->SetFont('Arial','B',10);
            $pdf->SetY($Y_Fields_Name_position + 9);
            $pdf->SetX(10);
            $pdf->Cell(200,5,utf8_decode('INFORMACIÓN GENERAL DEL PAGO'),1,0,'C',1);
            $pdf->Ln();

            $pdf->SetFillColor(235,235,235);
            $pdf->SetFont('Arial','B',9);
            $pdf->SetY($Y_Fields_Name_position + 14);
            $pdf->SetX(10);
            $pdf->Cell(115,5,'BENEFICIARIO',1,0,'C',1);
            $pdf->SetX(125);
            $pdf->Cell(45,5,'CESIONARIO DE CREDITO',1,0,'C',1);
            $pdf->SetX(170);
            $pdf->Cell(40,5,'RIF DEL BENEFICIARIO',1,0,'C',1);
            $pdf->Ln();
            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',8);
            $pdf->SetY($Y_Fields_Name_position + 19);
            $pdf->SetX(10);
            $pdf->Cell(115,5,utf8_decode($encab[0]->beneficiario->nom_ben),1,0,'L',1);
            $pdf->SetX(125);
            $pdf->Cell(45,5,$encab[0]['cesion'],1,0,'C',1);
            $pdf->SetX(170);
            $pdf->Cell(40,5,$encab[0]['ced_ben'],1,0,'L',1);
            $pdf->Ln();

            $pdf->SetFillColor(235,235,235);
            $pdf->SetFont('Arial','B',9);

            $pdf->SetY($Y_Fields_Name_position + 24);
            $pdf->SetX(10);
            $pdf->Cell(200,5,'CONCEPTO:',1,0,'L',1);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',8);
            $pdf->SetY($Y_Fields_Name_position + 29);
            $pdf->SetX(10);
            $pdf->MultiCell(200,5,utf8_decode($encab[0]['concepto']),1,'L',0);
            $pdf->Ln();

            $pdf->SetY($Y_Fields_Name_position + 39);
            $pdf->SetFont('Arial','B',9);
            $pdf->SetFillColor(235,235,235);

            $pdf->SetX(10);
            $pdf->Cell(35,10,'NRO DE CONTRATO',1,0,'C',1);
            $pdf->SetX(45);
            $pdf->Cell(35,10,utf8_decode('NRO DE VALUACIÓN'),1,0,'C',1);
            $pdf->SetX(80);
            $pdf->Cell(30,10,'NRO DE FACTURA',1,0,'C',1);
            $pdf->SetY(64);
            $pdf->SetX(110);
            $pdf->Cell(100,5,'MONTOS (BsF)',1,0,'C',1);
            $pdf->SetY(69);
            $pdf->SetX(110);
            $pdf->Cell(30,5,'TOTAL FACTURA',1,0,'C',1);
            $pdf->SetX(140);
            $pdf->Cell(30,5,'DEDUCCIONES',1,0,'C',1);
            $pdf->SetX(170);
            $pdf->Cell(40,5,'TOTAL A PAGAR',1,0,'C',1);
            $pdf->Ln();

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',8);

            $total_ded=$encab[0]['tot_des_iva']+$encab[0]['tot_des_islr']+$encab[0]['retencion_esp']+$encab[0]['mto_amortizacion']
            +$encab[0]['tot_ncr']+$encab[0]['monto_descuento'];

            $pdf->SetY($Y_Fields_Name_position + 49);
            $pdf->SetX(10);
            $pdf->Cell(35,5,$encab[0]['doc_sop'],1,0,'L',1);
            $pdf->SetX(45);
            $pdf->Cell(35,5,'',1,0,'C',1);
            $pdf->SetX(80);
            $pdf->Cell(30,5,$encab[0]['num_fac'],1,0,'L',1);
            $pdf->SetX(110);
            $pdf->Cell(30,5,number_format($encab[0]['monto'],2,',','.'),1,0,'R',1);
            $pdf->SetX(140);
            $pdf->Cell(30,5,number_format($total_ded,2,',','.'),1,0,'R',1);
            $pdf->SetX(170);
            $pdf->Cell(40,5,number_format($encab[0]['saldo'],2,',','.'),1,0,'R',1);
            $pdf->Ln();

            $pdf->SetFillColor(205,205,205);
            $pdf->SetFont('Arial','B',10);
            $pdf->SetY($Y_Fields_Name_position + 59);
            $pdf->SetX(10);
            $pdf->Cell(200,5,'CONTROL PRESUPUESTARIO',1,0,'C',1);
            $pdf->Ln();

            $pdf->SetY($Y_Fields_Name_position + 64);
            $pdf->SetFont('Arial','B',9);
            $pdf->SetFillColor(235,235,235);

            $pdf->SetX(10);
            $pdf->Cell(10,5,'TP',1,0,'C',1);
            $pdf->SetX(20);
            $pdf->Cell(10,5,'P/A',1,0,'C',1);
            $pdf->SetX(30);
            $pdf->Cell(12,5,'OBJ',1,0,'C',1);
            $pdf->SetX(42);
            $pdf->Cell(12,5,'GCIA',1,0,'C',1);
            $pdf->SetX(54);
            $pdf->Cell(12,5,'UNI',1,0,'C',1);
            $pdf->SetX(66);
            $pdf->Cell(10,5,'PA',1,0,'C',1);
            $pdf->SetX(76);
            $pdf->Cell(10,5,'GN',1,0,'C',1);
            $pdf->SetX(86);
            $pdf->Cell(12,5,'ESP',1,0,'C',1);
            $pdf->SetX(98);
            $pdf->Cell(12,5,'SUB',1,0,'C',1);
            $pdf->SetX(110);
            $pdf->Cell(40,5,'MTO COMPROMETIDO',1,0,'C',1);
            $pdf->SetX(150);
            $pdf->Cell(30,5,'MTO CAUSADO',1,0,'C',1);
            $pdf->SetX(180);
            $pdf->Cell(30,5,'TOTAL A PAGAR',1,0,'C',1);
            $pdf->Ln();
            $Y_Pie = 80;
            $pdf->SetFont('Arial','',8);
            $pdf->SetFillColor(255,255,255);
            if (!empty($solicititudpago->cxpdetgastosolpago)){
                foreach ($solicititudpago->cxpdetgastosolpago as $index => $detallegasto)
                {
                    $pdf->SetX(10);
                    $pdf->Cell(10,5,$detallegasto['tip_cod'],1,0,'C',1);
                    $pdf->SetX(20);
                    $pdf->Cell(10,5,$detallegasto['cod_pryacc'],1,0,'C',1);
                    $pdf->SetX(30);
                    $pdf->Cell(12,5,$detallegasto['cod_obj'],1,0,'C',1);
                    $pdf->SetX(42);
                    $pdf->Cell(12,5,$detallegasto['gerencia'],1,0,'C',1);
                    $pdf->SetX(54);
                    $pdf->Cell(12,5,$detallegasto['unidad'],1,0,'C',1);
                    $pdf->SetX(66);
                    $pdf->Cell(10,5,$detallegasto['cod_par'],1,0,'C',1);
                    $pdf->SetX(76);
                    $pdf->Cell(10,5,$detallegasto['cod_gen'],1,0,'C',1);
                    $pdf->SetX(86);
                    $pdf->Cell(12,5,$detallegasto['cod_esp'],1,0,'C',1);
                    $pdf->SetX(98);
                    $pdf->Cell(12,5,$detallegasto['cod_sub'],1,0,'C',1);
                    $pdf->SetX(110);
                    $pdf->Cell(40,5,number_format($detallegasto['mto_tra'],2,',','.'),1,0,'R',1);
                    $pdf->SetX(150);
                    $pdf->Cell(30,5,number_format($detallegasto['mto_sdo'],2,',','.'),1,0,'R',1);
                    $pdf->SetX(180);
                    $pdf->Cell(30,5,number_format($detallegasto['mto_sdo'],2,',','.'),1,0,'R',1);
                    $pdf->Ln();
                }
                $x=$solicititudpago->cxpdetgastosolpago->count();
            }else{$x=0;}

            for ($i = $x; $i < 12; $i++)
            {
                $Y_Pie = 92 + 5;
                $pdf->SetX(10);
                $pdf->Cell(10,5,'',1,0,'C',1);
                $pdf->SetX(20);
                $pdf->Cell(10,5,'',1,0,'C',1);
                $pdf->SetX(30);
                $pdf->Cell(12,5,'',1,0,'C',1);
                $pdf->SetX(42);
                $pdf->Cell(12,5,'',1,0,'C',1);
                $pdf->SetX(54);
                $pdf->Cell(12,5,'',1,0,'C',1);
                $pdf->SetX(66);
                $pdf->Cell(10,5,'',1,0,'C',1);
                $pdf->SetX(76);
                $pdf->Cell(10,5,'',1,0,'C',1);
                $pdf->SetX(86);
                $pdf->Cell(12,5,'',1,0,'C',1);
                $pdf->SetX(98);
                $pdf->Cell(12,5,'',1,0,'C',1);
                $pdf->SetX(110);
                $pdf->Cell(40,5,'',1,0,'C',1);
                $pdf->SetX(150);
                $pdf->Cell(30,5,'',1,0,'C',1);
                $pdf->SetX(180);
                $pdf->Cell(30,5,'',1,0,'C',1);
                $pdf->Ln();
            }
            $pdf->SetFillColor(205,205,205);
            $pdf->SetFont('Arial','B',10);
            $pdf->SetY($Y_Fields_Name_position + 131);
            $pdf->SetX(10);
            $pdf->Cell(200,5,'CONTABILIDAD',1,0,'C',1);
            $pdf->Ln();

            $pdf->SetY($Y_Fields_Name_position + 136);
            $pdf->SetFont('Arial','B',9);
            $pdf->SetFillColor(235,235,235);

            $pdf->SetX(10);
            $pdf->Cell(35,5,utf8_decode('CÓDIGO CONTABLE'),1,0,'C',1);
            $pdf->SetX(45);
            $pdf->Cell(135,5,utf8_decode('DESCRIPCIÓN'),1,0,'C',1);
            $pdf->SetX(180);
            $pdf->Cell(30,5,'TOTAL A PAGAR',1,0,'C',1);
            $pdf->Ln();
            $pdf->SetFont('Arial','',8);
            $pdf->SetFillColor(255,255,255);

            $ancho_fila = 5;
            $num_filas  = 12;

            if (!empty($solicititudpago->cxpdetcontablesolpago)){
                $Y_Pie = 140 +  1;
                $pdf->SetY($Y_Fields_Name_position + $Y_Pie );
                foreach ($solicititudpago->cxpdetcontablesolpago as $index => $comprobanteContable)
                {
                    $lineas = (int)(strlen($comprobanteContable->plancontable->nom_cta) /75);
                    if ($lineas > 0){
                        $ancho_fila = 5 * ($lineas + 1);
                        $num_filas--;
                    }else{
                        $ancho_fila = 5;
                    }
                    $pdf->SetX(10);
                    $pdf->Cell(35,$ancho_fila,$comprobanteContable['cod_cta'],1,0,'L',0);
                    $pdf->SetX(45);
                    $pdf->MultiCell(135,5,utf8_decode($comprobanteContable->plancontable->nom_cta),1,'L',0);
                    $pdf->SetY($Y_Fields_Name_position + $Y_Pie );
                    $pdf->SetX(180);

                    if ($comprobanteContable['tipo'] == 'DB'){
                        $pdf->Cell(30,$ancho_fila,number_format($comprobanteContable['monto'],2,',','.'),1,0,'R',0);
                    }else{
                        $pdf->Cell(30,$ancho_fila,'('.number_format($comprobanteContable['monto'],2,',','.').')',1,0,'R',0);
                    }
                    $Y_Pie = $Y_Pie+ $ancho_fila;
                    $pdf->SetY($Y_Fields_Name_position + $Y_Pie );
                }
                $j=$solicititudpago->cxpdetcontablesolpago->count();
            }else{
                $j=0;
            }
            for ($i = $j; $i < $num_filas; $i++)
            {
                $pdf->SetX(10);
                $pdf->Cell(35,5,'',1,0,'C',1);
                $pdf->SetX(45);
                $pdf->Cell(135,5,'',1,0,'C',1);
                $pdf->SetX(180);
                $pdf->Cell(30,5,'',1,0,'C',1);
                $pdf->Ln();
            }
            $Y_Pie = 226;
            //firmas y aprobaciones
            $pdf->SetFillColor(235,235,235);
            $pdf->SetFont('Arial','B',9);

            $pdf->SetY($Y_Pie);
            $pdf->SetX(10);
            $pdf->Cell(60,5,'CUENTAS POR PAGAR',1,0,'C',1);
            $pdf->SetX(70);
            $pdf->Cell(70,5,'CONTABILIDAD',1,0,'C',1);
            $pdf->SetX(140);
            $pdf->Cell(70,5,utf8_decode('TESORERÍA'),1,0,'C',1);
            $pdf->Ln();
            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',8);
            $Y_Pie = 231;
            $pdf->SetY($Y_Pie);
            $pdf->SetX(10);
            $pdf->Cell(60,13,'',1,0,'C',1);
            $pdf->SetX(70);
            $pdf->Cell(70,13,'',1,0,'C',1);
            $pdf->SetX(140);
            $pdf->Cell(70,13,'',1,0,'C',1);
            $pdf->SetY(254);
            $pdf->SetFont('Arial','',5);
            $pdf->SetTextColor(0,0,0);
            $pdf->setx(90);
            $pdf->Cell(80,3,utf8_decode("VISIÓN: SER LA HIDROLÓGICA DE REFERENCIA NACIONAL"),'C');
            $pdf->ln(1);
            $pdf->setxy(30,257);
            $pdf->MultiCell(160,2,utf8_decode("El logotipo de Certificación está relacionado con los Procesos de Captación, Tratamiento y Almacenamiento en los Ac. Industrial, Pto. Ordaz y Macagua San Félix de la Empresa HIDROBOLIVAR, C.A"),2,'C');
            header("Content-type: application/pdf");
            $pdf->Output();
            exit();
        }
        else
        {
            $html='<script language="javascript">alert("No se encontraron Datos para IMPRIMIR. \n Verifique la Solicitud a Imprimir o Contacte a Su ADMINISTRADOR de Sistemas"); window.close(); </script>';
            echo $html;
        }

    }
}
