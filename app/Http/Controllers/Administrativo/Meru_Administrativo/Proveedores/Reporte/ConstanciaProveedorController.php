<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Proveedores\Reporte;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Administrativo\Meru_Administrativo\Proveedores\Proveedor;
use App\Models\Administrativo\Meru_Administrativo\General\DatosEmpresa;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Traits\ReportFpdf;
use PhpParser\Node\Expr\Isset_;

class ConstanciaProveedorController extends Controller
{
    use ReportFpdf;
    public function index()
    {
        return view('administrativo.meru_administrativo.proveedores.reporte.constanciaproveedor.index');

    }
    public function edit(Proveedor $constanciaproveedore)
    {
        return view('administrativo.meru_administrativo.proveedores.reporte.constanciaproveedor.edit', compact('constanciaproveedore'));

    }
    public function print_consproveedor(Request $request )
    {
        $proveedor=Proveedor::where('id',$request->id)->first();
        $datos_empresa=DatosEmpresa::where('cod_empresa','01')->first();
       // dd($c);
        $pdf = new Fpdf('p','mm','letter','true');
        $pdf->SetLeftMargin(5);
        $pdf->SetRightMargin(5);
        $pdf->AddPage();

        //Pintar encabezado
        /*imagenes para reportes verticales*/
        /*Imagenes cabecera*/
        $pdf->Image('img/logo_superior_izquierdo.png',10,13,30,12);
        $pdf->Image('img/logo_superior_derecho.png', 185,5,18,18,'PNG');
      //  $pdf->Image('images/logo_superior_centro.png', 80,8,60,8,'PNG');
        /*Imagenes pie*/
        $pdf->Image('img/logo_inferior_izquierdo.png', 5,262,15,15,'PNG');
        $pdf->Image('img/logo_inferior_centro.png', 100,247.5,30,8,'PNG');
        $pdf->Image('img/logo_inferior_derecho.png', 200,262,10,15,'PNG');
        $pdf->SetFont('Arial','B',9);
        $pdf->SetY(25);
        $pdf->Setx(20);
        $pdf->Cell(21,4,'GERENCIA DE LOGISTICA',0,0,'C',0);
        $pdf->Setx(180);
        $pdf->Cell(5,4,'Codigo: F-AF-061',0,0,'C',0);
        $pdf->Ln();
        $pdf->Setx(180);
        $pdf->Cell(5,4,'Vigencia: 24/01/2018',0,0,'C',0);
        $pdf->SetY(29);
        $pdf->Setx(18);
        $pdf->Cell(28,4,'COORDINACION DE PROVEEDORES',0,0,'C',0);
        /*****************************************TITULO******************************************/
        $pdf->SetFont('Arial','B',11);
        $PosicionY = 37;
        $pdf->SetY($PosicionY);
        $pdf->Cell(195,4,'REGISTRO DE PROVEEDORES DE BIENES Y SERVICIOS',0,0,'C',0);
        $pdf->SetY($PosicionY+5);
        $pdf->Cell(195,4,'CONSTANCIA DE INSCRIPCION',0,0,'C',0);
        /*************************************ENCABEZADO******************************************/
        $pdf->SetFont('Arial','',9);
        $pdf->SetX(30);
        $pdf->SetY($PosicionY+20);
        $pdf->Cell('90',5,'POR MEDIO DEL PRESENTE SE HACE CONSTAR QUE LA EMPRESA',0,0,'L',0);
        $pdf->SetY($PosicionY+30);

        $pdf->SetFont('Arial','B',9);
        $pdf->SetY($PosicionY+25);
        $pdf->SetX(20);
        $pdf->Cell(10,5,'NOMBRE: ',0,0,'C',0);

        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',9);
        $pdf->SetX(33);
        $pdf->Cell(80,5,$proveedor->nom_prov,0,0,'L',0);

        $pdf->SetFont('Arial','B',9);
        $pdf->SetY($PosicionY+29);
        $pdf->SetX(18);
        $pdf->Cell(8,5,'R.I.F: ',0,0,'C',0);

        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',9);
        $pdf->SetX(27);
        $pdf->Cell(40,5,$proveedor->rif_prov,0,0,'L',1);
        $pdf->Ln();

        //$pdf->SetX(5);
        $pdf->Cell(65,5,'HA QUEDADO INCRITA EN EL REGISTRO DE PROVEEDORES DE HIDROBOLIVAR BAJO EL NUMERO: ',0,0,'L',0);
        $pdf->SetX(167);
        $pdf->SetFillColor(255,255,255);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(40,5,$proveedor->cod_prov,1,0,'C',1);

        /***************DATOS DEL PROVEEDOR**************************/
        $PosicionY = 77;
        $alto=6;

            $pdf->SetFillColor(205,205,205);
            $pdf->SetFont('Arial','B',11);

            $pdf->SetY($PosicionY);

            $pdf->Cell(205,7,'DATOS DE PROVEEDOR',1,0,'C',1);
            $pdf->Ln();

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(43,$alto,'FECHA DE INSCRIPCION:',1,0,'L',1);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(20,$alto,$proveedor->fecha,1,0,'C',1);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(43,$alto,'TELEFONO FIJO O MOVIL:',1,0,'L',1);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(48,$alto,$proveedor->tlf_prov1,1,0,'L',1);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(25,$alto,'FAX/TELEFAX:',1,0,'L',1);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(26,$alto,$proveedor->fax,1,1,'L',1);

            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(43,$alto,'DOMICILIO PRINCIPAL: ',1,0,'L',1);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(162,$alto,$proveedor->dir_prov,1,1,'L',1);

            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(63,$alto,'OBJETIVO PRINCIPAL DE LA EMPRESA ',1,0,'L',1);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(142,$alto,$proveedor->objetivo_gral,1,1,'L',1);

            /***************DATOS DEL REPPRESENTANTE LEGAL********************/
            $pdf->SetFillColor(205,205,205);
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(205,7,'DATOS REPRESENTANTE LEGAL',1,1,'C',1);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(43,$alto,'REPRESENTANTE LEGAL:',1,0,'L',1);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(111,$alto,$proveedor->nom_res,1,0,'L',1);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(25,$alto,'C.I:',1,0,'L',1);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(26,$alto,$proveedor->ced_res,1,1,'L',1);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(23,$alto,'CARGO:',1,0,'L',1);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(55,$alto,$proveedor->car_res,1,0,'L',1);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(48,$alto,'CORREO ELECTRONICO:',1,0,'L',1);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(79,$alto,$proveedor->email,1,1,'L',1);


            /***************SITUACION FINANCIERA********************/
            $pdf->SetFillColor(205,205,205);
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(205,7,'SITUACION FINANCIERA',1,1,'C',1);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(43,$alto,'CAPITAL SOCIAL:',1,0,'L',1);
            $pdf->SetFont('Arial','',10);
            $pdf->Cell(63,$alto,$proveedor->capital,1,0,'L',1);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(48,$alto,'NIVEL DE CONTRATACION:',1,0,'L',1);
            $pdf->SetFont('Arial','',10);
            $pdf->Cell(51,$alto,$proveedor->nivel_cont,1,1,'L',1);

            /***************SOLVENCIA DE HIDROBOLIVAR********************/
            $pdf->SetFillColor(205,205,205);
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(205,7,'SOLVENCIA DE HIDROBOLIVAR',1,1,'C',1);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(43,$alto,'NUMERO DEL CLIENTE:',1,0,'L',1);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(63,$alto,$proveedor->cuenta_hid,1,0,'L',1);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(48,$alto,'FECHA:',1,0,'L',1);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(51,$alto,$proveedor->fec_agua,1,1,'L',1);

            /***************DOCUMENTOS CONSIGNADOS********************/
            $pdf->SetFillColor(205,205,205);
            $pdf->SetFont('Arial','B',11);

            $pdf->Cell(205,7,'DOCUMENTOS CONSIGNADOS',1,0,'C',1);
            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','B',10);
            $pdf->SetY(157);
            $pdf->Cell(205,55,' ',1,1);
            $pdf->SetY(157);
            //dd($request->uno);
            if (isset($request->Checkbox1)){
                $pdf->Cell(205,5,'* REGISTRO MERCANTIL',0,1);
            }
            if (isset($request->Checkbox2)){
                $pdf->Cell(205,5,'* COPIA DE CEDULA DEL REPRESENTANTE LEGAL',0,1);
            }
            if (isset($request->Checkbox3)){
                $pdf->Cell(205,5,'* COPIA DE CEDULA Y CARTA DE AUTORZACION DEL AUTORIZADO A COBRAR',0,1);
            }
            if (isset($request->Checkbox4)){
                $pdf->Cell(205,5,'* COPIA DE RIF',0,1);
            }
            if (isset($request->Checkbox5)){
                $pdf->Cell(205,5,'* COPIA DE LA SOLVENCIA LABORAL (GOBERNACION DEL ESTADO BOLIVAR)',0,1);
            }
            if (isset($request->Checkbox6)){
                $pdf->Cell(205,5,'* COPIA DE LA SOLVENCIA EMITIDA POR HIDROBOLIVAR',0,1);
            }
            if (isset($request->Checkbox7)){
                $pdf->Cell(205,5,'* LISTADO DE BIENES O SERVICIOS A OFRECER',0,1);
            }
            if (isset($request->Checkbox8)){
                $pdf->Cell(205,5,'* INSCRIPCION DEL SERVICIO NACIONAL DE CONTRATISTAS',0,1);
            }
            if (isset($request->Checkbox9)){
                $pdf->Cell(205,5,'* INSCRIPCION DEL SUNACOOP (DE SER COOPERATIVA)',0,1);
            }
            if (isset($request->Checkbox10)){
                $pdf->Cell(205,5,'* SOLVENCIA INCE',0,1);
            }
            if (isset($request->Checkbox11)){
                $pdf->Cell(205,5,'* SOLVENCIA DEL SEGURO SOCIAL',0,1);
            }

            /*************************************PIE********************************************/
            $pdf->SetY(212);
            $pdf->Cell(205,35,' ',1,1);


            $pdf->SetY(213);
            $pdf->Cell(30,5,"PUERTO ORDAZ, {$proveedor->fecha_hoy}",'L',0,'L',1);
            $pdf->SetY(235);
            $pdf->SetFont('Arial','B',6);
           // $pdf->Cell(0,3,$proveedor->jefe_logistica,0,1,'C');
            $pdf->Cell(0,3,'GERENCIA DE LOGISTICA',0,0,'C');

            $pdf->SetY(255);
            $pdf->SetFillColor(235,235,235);
            $pdf->SetFont('Arial','I',5);
            $direccion = $datos_empresa->direccion."-".$datos_empresa->telefono."-".$datos_empresa->fax."-".$datos_empresa->rif."-".$datos_empresa->nit;
            $pdf->Cell(0,4,$direccion,0,0,'C',0);


        header("Content-type: application/pdf");
        $pdf->Output();

        exit;
    }
}
