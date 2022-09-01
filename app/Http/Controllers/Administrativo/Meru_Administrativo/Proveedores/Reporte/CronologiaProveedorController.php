<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Proveedores\Reporte;

use App\Traits\ReportFpdf;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Http\Controllers\Controller;
use App\Models\Administrativo\Meru_Administrativo\Proveedores\Proveedor;
use App\Http\Requests\Administrativo\Meru_Administrativo\Proveedores\Reporte\CronologiaProveedorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class CronologiaProveedorController extends Controller
{
    use ReportFpdf;
    public function index()
    {

        $beneficiario=Proveedor::query()
                                ->select('rif_prov','nom_prov')
                                ->orderBy('nom_prov', 'asc')
                                ->get();
        return view('administrativo.meru_administrativo.proveedores.reporte.cronologiaproveedor.index',compact('beneficiario'));

    }

    public function print_cronologiaproveedor(CronologiaProveedorRequest $request)
    {
        if($request->validated()){

            $data['tipo_hoja']                  = 'C'; // O Oficio
            $data['orientacion']                = 'V'; // H Horizontal
            $data['cod_normalizacion']          = '';
            $data['gerencia']                   = '';
            $data['division']                   = '';
            $data['titulo']                     = 'LISTADO CRONOLÓGICO DE INSCRIPCION DE PROVEEDORES';
            $data['subtitulo']                  = '';
            $data['alineacion_columnas']		= array('C','L','C','C'); //C centrado R derecha L izquierda
            $data['ancho_columnas']		    	= array('30','110','20','40');//Ancho de Columnas
            $data['nombre_columnas']		   	= array('Rif Proveedor','Nombre Proveedor ',utf8_decode('Código'),utf8_decode('Fecha Inscripcion'));
            $data['funciones_columnas']         = '';
            $data['fuente']		   	            = 8;
            $data['registros_mostar']           = array('rif_prov',utf8_decode('nom_prov'),'cod_prov','fecha');
            $data['nombre_documento']			= 'Cronologiaproveedor.pdf'; //Nombre de Archivo
            $data['con_imagen']			        = true;
            $data['vigencia']			        = '';
            $data['revision']			        = '';
            $data['usuario']			        = '';
            $data['cod_reporte']			    = 'MERU';
            $data['registros']                  = Proveedor::query()
                                                    ->select(
                                                            'rif_prov','nom_prov','cod_prov',
                                                            DB::raw( "TO_CHAR(fecha,'dd/mm/yyyy') as fecha"))
                                                            ->when($request->rif_prov!='',function($query) use($request){
                                                                            $query->Where('rif_prov', $request->rif_prov)
                                                                            ->orwhereBetween('fecha',[$request->fec_ini,$request->fec_fin]);
                                                                        })
                                                            ->orderby('nom_prov')->get();


           $pdf = new Fpdf;
           $pdf->setTitle('Cronologia Proveedores ');
           $this->pintar_listado_pdf($pdf,$data);
          // $pdf->Output($data['nombre_documento'],'I');

//           echo  "<script type='text/javascript'> window.open('".$pdf->Output($data['nombre_documento'],'I')."', '_blank'); </script>";
            exit;
        }else{

            return redirect()->back()->withInput();
        }

    }
}
