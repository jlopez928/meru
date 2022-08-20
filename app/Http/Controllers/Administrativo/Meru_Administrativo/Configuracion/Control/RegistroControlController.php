<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Configuracion\Control;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\RegistroControl;
use App\Http\Controllers\Controller;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Traits\ReportFpdf;
use Illuminate\Http\Request;

class RegistroControlController extends Controller
{  use ReportFpdf;

     public function index()
    {
        return view('administrativo.meru_administrativo.configuracion.control.registrocontrol.index');

    }
    public function show($id)
    {
        $registrocontrol= RegistroControl::where('id',$id)->first();
        return view('administrativo.meru_administrativo.configuracion.control.registrocontrol.show', compact('registrocontrol'));
   }

   public function print_registrocontrol()
   {

       $data['tipo_hoja']                  = 'C'; // C carta
       $data['orientacion']                = 'H'; // V Vertical
       $data['cod_normalizacion']          = '';
       $data['gerencia']                   = '';
       $data['division']                   = '';
       $data['titulo']                     = 'HIDROBOLIVAR';
       $data['subtitulo']                  = 'LISTADO DE REGISTRO CONTROL';
       $data['alineacion_columnas']		   = array('C','C','C','C','C','C','C','C'); //C centrado R derecha L izquierda
       $data['ancho_columnas']		       = array('20','20','10','40','40','40','40','40');//Ancho de Columnas
       $data['nombre_columnas']		   	   = array(utf8_decode('Ano Proceso'),utf8_decode('Estado'),utf8_decode('Mes'),utf8_decode('Comprobantes abiertos'),
                                                utf8_decode('Cuenta Resultado'),utf8_decode('Cuenta Comision'),utf8_decode('Cuenta Transferencia'),utf8_decode('Cuenta Reintegro'));
       $data['funciones_columnas']         = '';
       $data['fuente']		   	            = 8;
       $data['registros_mostar']           = array('ano_pro', 'sta_con','ult_mes','con_con','ctaresultado','cod_cta_comisiones','con_cta_transferencia','cod_cta_reintegro');
       $data['nombre_documento']			= 'listado_modulo.pdf'; //Nombre de Archivo
       $data['con_imagen']			        = true;
       $data['vigencia']			        = '';
       $data['revision']			        = '';
       $data['usuario']			        = auth()->user()->name;
       $data['cod_reporte']			    = '';
       $data['registros']                  = Registrocontrol::query()->orderby('ano_pro')->get();

       $pdf = new Fpdf;

       $pdf->setTitle(utf8_decode('Listado de Modulos'));

       $this->pintar_listado_pdf($pdf,$data);

       exit;
   }

    public function periodoActual(Request $request)
    {
        if (!empty($request->get('ano_pro'))) {
            session(['ano_pro' => $request->get('ano_pro')]);
        }

        return redirect()->back();
    }
}
