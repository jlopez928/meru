<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Proveedores\Configuracion;

use App\Traits\ReportFpdf;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Administrativo\Meru_Administrativo\Proveedores\Ramo;
use App\Http\Requests\Administrativo\Meru_Administrativo\Proveedores\Configuracion\RamoRequest;

class RamoController extends Controller
{
    use ReportFpdf;

    public function index()
    {
        return view('administrativo.meru_administrativo.proveedores.configuracion.ramo.index');
    }

    public function create()
    {
        $ramo = new Ramo();

        return view('administrativo.meru_administrativo.proveedores.configuracion.ramo.create', compact('ramo'));
    }
    public function show(Ramo $ramo)
    {

        return view('administrativo.meru_administrativo.proveedores.configuracion.ramo.show', compact('ramo'));

    }
    public function store(RamoRequest $request)
    {
        try {

            DB::connection('pgsql')->transaction(function() use($request){

                Ramo::create($request->validated() + [
                    'cod_ram' => Ramo::max('id') + 1,
                    'usuario' => auth()->id(),
                ]);

            });

            alert()->success('Éxito','Registro Guardado Exitosamente');

            return to_route('proveedores.configuracion.ramo.index');
        } catch (\Exception $ex) {
            alert()->error('Error', str($ex)->limit(250));

            return redirect()->back()->withInput();
        }
    }

    public function edit(Ramo $ramo)
    {
        return view('administrativo.meru_administrativo.proveedores.configuracion.ramo.edit', compact('ramo'));
    }

    public function update(RamoRequest $request, Ramo $ramo)
    {
        try {
            $ramo->update($request->validated());

            alert()->success('Éxito','Registro Modificado Exitosamente');

            return to_route('proveedores.configuracion.ramo.index');
        } catch (\Exception $ex) {
            alert()->error('Error', str($ex)->limit(250));

            return redirect()->back()->withInput();
        }
    }

    public function destroy(Ramo $ramo)
    {
        try {
            $ramo->delete();

            alert()->success('Éxito','Registro Eliminado Exitosamente');

            return to_route('proveedores.configuracion.ramo.index');
        } catch (\Exception $ex) {

            alert()->error('Error', str($ex)->limit(250));

            return redirect()->back();
        }
    }

    public function print_ramos()
    {
        $data['tipo_hoja']                  = 'C'; // C carta
        $data['orientacion']                = 'V'; // V Vertical
        $data['cod_normalizacion']          = '';
        $data['gerencia']                   = 'Departamento de Contrataciones';
        $data['division']                   = '';
        $data['titulo']                     = 'HIDROBOLIVAR';
        $data['subtitulo']                  = 'CODIFICACION DE RAMOS DE PROVEEDORES';
        $data['alineacion_columnas']		= array('C','L'); //C centrado R derecha L izquierda
        $data['ancho_columnas']		    	= array('20','170');//Ancho de Columnas
        $data['nombre_columnas']		   	= array(utf8_decode('Código'),utf8_decode('Descripción'));
        $data['funciones_columnas']         = '';
        $data['fuente']		   	            = 8;
        $data['registros_mostar']           = array('cod_ram', 'des_ram');
        $data['nombre_documento']			= 'Ramos.pdf'; //Nombre de Archivo
        $data['con_imagen']			        = true;
        $data['vigencia']			        = '';
        $data['revision']			        = '';
        $data['usuario']			        = auth()->user()->name;
        $data['cod_reporte']			    = '';
        $data['registros']                  = Ramo::query()->where('sta_reg', 1)->orderby('des_ram')->get();

        $pdf = new Fpdf;

        $pdf->setTitle(utf8_decode('Codificación de Ramos de Proveedores'));

        $this->pintar_listado_pdf($pdf,$data);

        exit;
    }
}