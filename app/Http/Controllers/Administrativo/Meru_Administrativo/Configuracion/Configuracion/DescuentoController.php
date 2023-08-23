<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Configuracion\Configuracion;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Descuento;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Retencion;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Residencia;
use App\Http\Requests\Administrativo\Meru_Administrativo\Configuracion\Configuracion\DescuentoResquet;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\TipoMonto;
use Illuminate\Support\Str;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Traits\ReportFpdf;
class DescuentoController extends Controller
{ use ReportFpdf;

    public function index()
    {
        return view('administrativo.meru_administrativo.configuracion.configuracion.descuento.index');

    }


    public function create()
    {
        $descuento= new Descuento();
        $descuento->fecha = Carbon::now()->format('Y-m-d');
        return view('administrativo.meru_administrativo.configuracion.configuracion.descuento.create', compact('descuento'));
    }

    public function store(DescuentoResquet $request)
    {
        try {
             Descuento::create($request->validated());
             alert()->success('¡Éxito!','Registro Guardado Exitosamente');
             return redirect()->route('configuracion.configuracion.descuento.index');
         } catch(\Illuminate\Database\QueryException $e){
             alert()->error('Transacci&oacute;n Fallida: ',Str::limit($e->getMessage(), 120));
             return redirect()->back()->withInput();
         }
    }
    public function show(Descuento $descuento)
    {
        $descuento->fecha = Carbon::parse($descuento->fecha)->format('Y-m-d');
         return view('administrativo.meru_administrativo.configuracion.configuracion.descuento.show',compact('descuento'));

    }

    public function edit(Descuento $descuento)
    {
        $tipo_monto =  TipoMonto::query()->get();
        $retencion =  Retencion::query()->get();
        $residencia =  Residencia::query()->get();
        $descuento->fecha = Carbon::parse($descuento->fecha)->format('Y-m-d');
        return view('administrativo.meru_administrativo.configuracion.configuracion.descuento.edit', compact('descuento','tipo_monto','retencion','residencia'));

    }
     public function update(DescuentoResquet $request, Descuento $descuento)
    {//
        try {
            if ($descuento->status == '0' && $request->status=='0'){
                alert()->info('Registro Inactivo NO puede ser Modificado. Favor verifique.');
                return redirect()->back()->withInput();
            }
            $descuento->update($request->validated());
            alert()->success('¡Éxito!','Registro Modificado Exitosamente');
            return redirect()->route('configuracion.configuracion.descuento.index');
        } catch(\Illuminate\Database\QueryException $e){
            alert()->error('Transacci&oacute;n Fallida: ',Str::limit($e->getMessage(), 120));
            return redirect()->back()->withInput();
        }
    }
    public function print_descuento()
    {

        $data['tipo_hoja']                  = 'C'; // C carta
        $data['orientacion']                = 'V'; // V Vertical
        $data['cod_normalizacion']          = '';
        $data['gerencia']                   = '';
        $data['division']                   = '';
        $data['titulo']                     = 'HIDROBOLIVAR';
        $data['subtitulo']                  = 'LISTADO DESCUENTOS Y RETENCIONES';
        $data['alineacion_columnas']		= array('C','C','C','C'); //C centrado R derecha L izquierda
        $data['ancho_columnas']		    	= array('20','80','20','80');//Ancho de Columnas
        $data['nombre_columnas']		   	= array(utf8_decode('Código'),utf8_decode('Descripción'),utf8_decode('Tipo Monto'),utf8_decode('Clase'));
        $data['funciones_columnas']         = '';
        $data['fuente']		   	            = 8;
        $data['registros_mostar']           = array('cod_des', 'des_des','tip_mto','cla_desc');
        $data['nombre_documento']			= 'listado_tasacambio.pdf'; //Nombre de Archivo
        $data['con_imagen']			        = true;
        $data['vigencia']			        = '';
        $data['revision']			        = '';
        $data['usuario']			        = auth()->user()->name;
        $data['cod_reporte']			    = '';
        $data['registros']                  = Descuento::query()->where('status', '1')->orderby('cod_des')->get();

        $pdf = new Fpdf;
        $pdf->setTitle(utf8_decode('Listado de Descuento'));
        $this->pintar_listado_pdf($pdf,$data);

        exit;
    }


}
