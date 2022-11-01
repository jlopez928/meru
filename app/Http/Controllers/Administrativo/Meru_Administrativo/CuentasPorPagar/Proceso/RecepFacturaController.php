<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\CuentasPorPagar\Proceso;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\FacRecepFactura;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\CxPTipoDocumento;
use App\Models\Administrativo\Meru_Administrativo\Proveedores\Proveedor;
use App\Models\Administrativo\Meru_Administrativo\Presupuesto\RegistroControl;
use App\Http\Requests\Administrativo\Meru_Administrativo\CuentasPorPagar\Proceso\FacRecepFacturaRequest;
use App\Models\Administrativo\Meru_Administrativo\TesCorrelativo;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Gerencia;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\FacCausaDevolucion;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\HistFacCausasxFactura;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\FacCausaxFactura;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\HistFacRecepFacturas;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\FacRecepFacturaBorrada;
use App\Models\Administrativo\Meru_Administrativo\General\DatosEmpresa;
use App\Models\Administrativo\Meru_Administrativo\General\Usuario;

use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\DB;
use App\Traits\ReportFpdf;
use App\Http\Controllers\Controller;
use App\Traits\funcActas;
use App\Models\User;
use Illuminate\Http\Request;

class RecepFacturaController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use ReportFpdf;
    use funcActas;

    public function index()
    {
        return view('administrativo.meru_administrativo.cuentasxpagar.proceso.recep_factura.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $recepfactura= new FacRecepFactura();

        $proveedores = Proveedor::where('cod_edo','1')
                                ->select('rif_prov','nom_prov')
                                ->orderBy('nom_prov')
                                ->get();

        $cxptipodocumento = CxPTipoDocumento::query()
                                            ->where('status','1')
                                            ->where('recp_factura','1')
                                            ->get();

        $gerencias = Gerencia::where('cod_ger','<>','17')
                    ->select( 'cod_ger', 'des_ger')
                    ->orderBy('des_ger')
                    ->get();

        $ano_pro = RegistroControl::periodoActual();

       // $faccausadevolucion =FacCausaDevolucion::query()->get();
       $accion = 'crear';

        return view('administrativo.meru_administrativo.cuentasxpagar.proceso.recep_factura.create', compact('recepfactura','proveedores','cxptipodocumento','ano_pro','gerencias','accion'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FacRecepFacturaRequest $recepfactura)
    {
        $usuario = Usuario::where('cedula', auth()->user()->cedula)->select('usuario')->first();
        //---------------------------------------------------------------------------------------------------------------
        //  Obtener el último correlativo de la tabla tes_corrtransferencias, esto se debe actualizar por cada año fiscal
        //---------------------------------------------------------------------------------------------------------------
        $nro_reng = TesCorrelativo::query()->where('ano_pro', $recepfactura->ano_pro)->where('flujo', 'RF')->first()->correlativo?? 1;
        //return $recepfactura;
        try {
            DB::connection('pgsql')->beginTransaction();

            $msj = "Error al Ingresar la Recepcion de Factura.";

           FacRecepFactura::create([
                'ano_pro'            => $recepfactura->ano_pro,
                'nro_reng'           => $nro_reng,
                'rif_prov'           => $recepfactura->rif_prov,
                'num_fac'            => $recepfactura->num_fac,
                'fec_fac'            => $recepfactura->fec_fac,
                'fec_rec'            => $recepfactura->fec_rec,
                'hor_rec'            => $recepfactura->hor_rec,
                'mto_fac'            => $recepfactura->mto_fac,
                'concepto'           => $recepfactura->concepto,
                'sta_fac'            => '0',
                'fec_sta'            => now()->format('Y-m-d'),
                'usuario'            => $usuario->usuario,
                'tipo_doc'           => $recepfactura->tipo_doc,
                'nro_doc'            => $recepfactura->nro_doc,
                'ano_sol'            => $recepfactura->ano_sol,
                'recibo'             => $recepfactura->recibo,
            ]);


            //----------------------------------------------------------------------------------
            // Actualizar el correlativo de la Recepcion de factura
            //-----------------------------------------------------------------------------------
            if($nro_reng==1){

                $msj = "Error Actualizando Correlativo de la Recepcion de Factura.\\nComuniquese con su Administrador de Sistema";

                $correlativo=TesCorrelativo::create([
                    'ano_pro'        => $recepfactura->ano_pro,
                    'correlativo'    => 2,
                    'flujo'          => 'RF',
                ]);

            }else{
                $nro_reng=$nro_reng+1;

                $msj .= "Error Actualizando Correlativo de la Recepcion de Factura.\\nComuniquese con su Administrador de Sistema";

                TesCorrelativo::where('ano_pro',$recepfactura->ano_pro)
                              ->where('flujo','RF')
                              ->update(['correlativo' => $nro_reng ]);

            }

            alert()->success('¡Éxito!', ' Factura Registrado Sastifactoriamente');

            DB::connection('pgsql')->commit();

            return redirect()->route('cuentasxpagar.proceso.recepfactura.index');

        }
            catch(\Illuminate\Database\QueryException $e){
                //dd($e->getMessage().' '.$msj);
                DB::connection('pgsql')->rollBack();
                alert()->error('¡Transacción Fallida!'.$msj.' '. $e->getMessage());
                return redirect()->back()->withInput();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(FacRecepFactura $recepfactura,$valor)
    {

        $proveedores = Proveedor::where('cod_edo','1')
        ->select('rif_prov','nom_prov')
        ->orderBy('nom_prov')
        ->get();

        $cxptipodocumento = CxPTipoDocumento::query()
                    ->where('status','1')
                    ->where('recp_factura','1')
                    ->get();
        $ano_pro = RegistroControl::periodoActual();

         $gerencias = Gerencia::where('cod_ger','<>','17')
                              ->select( 'cod_ger', 'des_ger')
                              ->orderBy('des_ger')
                              ->get();

        $faccausadevolucion =FacCausaDevolucion::query()->get();


        //   $gerencia2 = Gerencia::selectRaw("17 as cod_ger,'PROVEEDOR' as des_ger")
        //                        ->orderBy('des_ger');

        //  $gerencias->union($gerencia2)

        //  dd($gerencias);

        switch ($valor) {
            case "show":
                $ruta='administrativo.meru_administrativo.cuentasxpagar.proceso.recep_factura.show';
                break;
            case "devolver":
                 $ruta='administrativo.meru_administrativo.cuentasxpagar.proceso.recep_factura.devolver';
                 break;
             case "entregar":
                 $ruta='administrativo.meru_administrativo.cuentasxpagar.proceso.recep_factura.entregar';
                 break;
            case "reactivar":
                $ruta='administrativo.meru_administrativo.cuentasxpagar.proceso.recep_factura.reactivar';
                break;
            case "modificar":
                $ruta='administrativo.meru_administrativo.cuentasxpagar.proceso.recep_factura.modificar';
                break;
            case "eliminar":
                $ruta='administrativo.meru_administrativo.cuentasxpagar.proceso.recep_factura.eliminar';
                break;
        }

        return view($ruta, compact('recepfactura','proveedores','cxptipodocumento','ano_pro','valor','gerencias','faccausadevolucion'));
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $recepfactura, $id)
    {
        //-----------------------------------------------------------------------
        // Proceso de devolución
        //------------------------------------------------------------------------
        $fecha =now()->format('Y-m-d');
        $usuario = Usuario::where('cedula', auth()->user()->cedula)->select('usuario')->first();

        //----------------------------------------------------------------------------------
        // Se realizan validaciones previas a la devolución
        //-----------------------------------------------------------------------------------

       // dd($fecha.'|'.$recepfactura->fec_dev.'|'.$recepfactura->fec_rec);
        if(!Empty($recepfactura->resp_dev)) {
            if(!Empty($recepfactura->fec_dev)) {
                if($fecha >= $recepfactura->fec_dev){
                    if(($recepfactura->fec_dev >= $recepfactura->fec_rec)){
                        if (!$recepfactura->has('marcar')){
                            alert()->error('Debe Seleccionar Causa de Anulación. Favor Verifique...');
                            return redirect()->back()->withInput();
                        }
                    }else{
                          alert()->error('La Fecha de Devolución no puede ser Menor a la Fecha de Recepción. Favor Verifique');
                          return redirect()->back()->withInput();
                    }
                }else{
                      alert()->error('La Fecha de Devolución no puede ser Mayor a la Fecha Actual. Favor Verifique');
                      return redirect()->back()->withInput();
                }
            }else{
                alert()->error('Debe Ingresar Fecha de Devolución');
                return redirect()->back()->withInput();
            }
        }else{
             alert()->error('Debe Ingresar Devolver a:');
             return redirect()->back()->withInput();
         }

        try {
            DB::connection('pgsql')->beginTransaction();

            $msj = "Error Actualizando Status en Recepción de Factura";

            $facrecepfactura = FacRecepFactura::where('ano_pro', $recepfactura->ano_pro)
                                              ->where('rif_prov', $recepfactura->rif_prov)
                                              ->where('num_fac', $recepfactura->num_fac)
                                              ->where('recibo', $recepfactura->recibo)
                                              ->update(['sta_fac'       => '2',
                                                        'fec_sta'       => now()->format('Y-m-d'),
                                                        'fec_dev'       => $recepfactura->fec_dev,
                                                        'observaciones' => $recepfactura->observaciones,
                                                        'usuario_dev'   => $usuario->usuario,
                                                        'resp_dev'      => $recepfactura->resp_dev
        ]);

            if ($recepfactura->has('marcar') ){

                for ($i=0; $i < count($recepfactura->marcar); $i++) {

                    $msj = "Error Actualizando Correlativo de Factura";
                        FacCausaxFactura::create([
                            'ano_pro'      => $recepfactura->ano_pro,
                            'nro_reng'     => $recepfactura->nro_reng,
                            'num_fac'      => $recepfactura->num_fac,
                            'cod_dev'      => $recepfactura->marcar[$i],
                            'rif_prov'     => $recepfactura->rif_prov,
                            'usuario'      => 'fpalma',
                            'fecha'        => now()->format('Y-m-d'),
                            'ano_sol'      => $recepfactura->ano_sol,
                            ]);

                }
            }
            alert()->success('¡Éxito!', ' Factura Devuelta Sastifactoriamente');

            DB::connection('pgsql')->commit();

            return redirect()->route('cuentasxpagar.proceso.recepfactura.index');

        }   catch(\Illuminate\Database\QueryException $e){
           //dd($e->getMessage().' '.$msj);
            DB::connection('pgsql')->rollBack();
            alert()->error('¡Transacción Fallida!'.$msj.' '. $e->getMessage());
            return redirect()->back()->withInput();
    }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function entregar(Request $recepfactura, $id)
    {
        $usuario = Usuario::where('cedula', auth()->user()->cedula)->select('usuario')->first();
        $fecha = $this->FechaSistema($this->anoPro, "Y/m/d H:i:s");

        //----------------------------------------------------------------------------------
        // Se realizan validaciones previas a la devolución
        //-----------------------------------------------------------------------------------
        if(!Empty($recepfactura->fec_entrega)) {

            if($recepfactura->fec_entrega <= $fecha ){

                if($recepfactura->fec_entrega < $recepfactura->fec_dev){
                    alert()->error('Error. La Fecha de Entrega no puede ser Menor a la Fecha de Devolución. Favor Verifique...');
                }
            }else{
                alert()->error('Error. La Fecha de Entrega no puede ser Superior a la Fecha Actual. Favor Verifique...');

            }
        }else{
            alert()->error('Debe Ingresar Fecha de Entrega. Favor Verifique...');

        }

        try {
            DB::connection('pgsql')->beginTransaction();

            $msj = "Error realizando entrega de Factura";

            $facrecepfactura = FacRecepFactura::where('ano_pro', $recepfactura->ano_pro)
                                              ->where('rif_prov', $recepfactura->rif_prov)
                                              ->where('num_fac', $recepfactura->num_fac)
                                              ->where('recibo', $recepfactura->recibo)
                                              ->update(['sta_fac'          => '3',
                                                        'fec_entrega'      => $recepfactura->fec_entrega,
                                                        'usuario_entrega'  => $usuario->usuario
                                                    ]);

            alert()->success('¡Éxito!', 'La Factura Fue Registrada como Entregada Sastifactoriamente');

            DB::connection('pgsql')->commit();

            return redirect()->route('cuentasxpagar.proceso.recepfactura.index');

        }   catch(\Illuminate\Database\QueryException $e){
            //dd($e->getMessage().' '.$msj);
            DB::connection('pgsql')->rollBack();
            alert()->error('¡Transacción Fallida!'.$msj.' '. $e->getMessage());
            return redirect()->back()->withInput();

        }
    }

    public function reactivar(Request $recepfactura, $id)
    {
        $usuario = Usuario::where('cedula', auth()->user()->cedula)->select('usuario')->first();
    //return $usuario->usuario;
        $fecha = $this->FechaSistema($this->anoPro, "Y/m/d H:i:s");

        try {
            DB::connection('pgsql')->beginTransaction();

            //----------------------------------------------------------------------
            //               Buscar el numero de factura a Generar
            //----------------------------------------------------------------------
            $correlativo = TesCorrelativo::query()->where('ano_pro', $this->anoPro)
                                                ->where('flujo', 'RF')
                                                ->first()
                                                ->correlativo?? 1;

            if(!Empty( $correlativo))
                $nro_reng = $correlativo;

            //-----------------------------------------------------------------------------------------
            //Script que busca los datos del registros para ser almacenado en la Tabla de historico
            //------------------------------------------------------------------------------------------

            $facrecepfactura = FacRecepFactura::where('ano_pro', $recepfactura->ano_pro)
                                            ->where('nro_reng', $recepfactura->nro_reng)
                                            ->where('rif_prov', $recepfactura->rif_prov)
                                            ->where('num_fac', $recepfactura->num_fac)
                                            ->where('recibo', $recepfactura->recibo)
                                            ->first();

            if ($facrecepfactura->num_fac != ''){
                if ($facrecepfactura->fec_mod == ''){
                    $facrecepfactura->fec_mod = '19000101';
                }

            }
            //----------------------------------------------------------------------
            //       Script que almacena en la tabla de historico
            //----------------------------------------------------------------------

            $msj = "Error Insertando el Historico de Factura";


            HistFacRecepFacturas::create([
                'ano_pro'            => $facrecepfactura->ano_pro,
                'nro_reng'           => $facrecepfactura->nro_reng,
                'rif_prov'           => $facrecepfactura->rif_prov,
                'num_fac'            => $facrecepfactura->num_fac,
                'fec_fac'            => $facrecepfactura->fec_fac,
                'fec_rec'            => $facrecepfactura->fec_rec,
                'fec_dev'            => $facrecepfactura->fec_dev,
                'resp_dev'           => $facrecepfactura->resp_dev,
                'hor_rec'            => $facrecepfactura->hor_rec,
                'mto_fac'            => $facrecepfactura->mto_fac,
                'concepto'           => $facrecepfactura->concepto,
                'observaciones'      => $facrecepfactura->observaciones,
                'sta_fac'            => '4',
                'fec_sta'            => $facrecepfactura->fec_sta,
                'usuario'            => $facrecepfactura->usuario,
                'usuario_dev'        => $facrecepfactura->usuario_dev,
                'usuario_reac'       => $facrecepfactura->usuario_reac,
                'usuario_mod'        => $facrecepfactura->usuario_mod,
                'usuario_entrega'    => $facrecepfactura->usuario_entrega,
                'fec_mod'            => $facrecepfactura->fec_mod,
                'fec_reac'           => $facrecepfactura->fec_reac,
                'fec_entrega'        => $facrecepfactura->fec_entrega,
                'tipo_doc'           => $facrecepfactura->tipo_doc,
                'nro_doc'            => $facrecepfactura->nro_doc,
                'ano_sol'            => $facrecepfactura->ano_sol,
                'recibo'             => $facrecepfactura->recibo
            ]);

            //----------------------------------------------------------------------------------
            //Script que guarda el detalle de las causas de devolucion en la tabla de historico
            //----------------------------------------------------------------------------------
            foreach( $facrecepfactura->faccausaxfactura as $causas) {

                $msj = "Error Insertando Detalle en Historico de Factura";

                HistFacCausasxFactura::create([
                    'ano_pro'       => $causas->ano_pro,
                    'nro_reng'      => $causas->nro_reng,
                    'num_fac'       => $causas->num_fac,
                    'cod_dev'       => $causas->cod_dev,
                    'rif_prov'      => $causas->rif_prov,
                    'usuario'       => $causas->usuario,
                    'fecha'         => $causas->fecha,
                ]);

            }
            //----------------------------------------------------------------------
            //              Script que elimina las causas de devolucion
            //----------------------------------------------------------------------
            $msj = "Error Borrando Causas de Devolucion";

            FacCausaxFactura::where('ano_pro', $recepfactura->ano_pro)
                            ->where('nro_reng', $recepfactura->nro_reng)
                            ->where('rif_prov', $recepfactura->rif_prov)
                            ->where('num_fac', $recepfactura->num_fac)
                            ->delete();


            //----------------------------------------------------------------------
            // Script que inicializa nuevamente los campos del registro
            //----------------------------------------------------------------------
            $msj = "Error Eliminando Registro Viejo de Factura";
            FacRecepFactura::where('ano_pro', $recepfactura->ano_pro)
                                            ->where('nro_reng', $recepfactura->nro_reng)
                                            ->where('rif_prov', $recepfactura->rif_prov)
                                            ->where('num_fac', $recepfactura->num_fac)
                                            ->delete();
            $msj = "Error Insertando en fac_recepfacturas";

            FacRecepFactura::create([
                'ano_pro'            => $facrecepfactura->ano_pro,
                'nro_reng'           => $nro_reng,
                'rif_prov'           => $facrecepfactura->rif_prov,
                'num_fac'            => $facrecepfactura->num_fac,
                'fec_fac'            => $facrecepfactura->fec_fac,
                'fec_rec'            => now()->format('Y-m-d'),
                'hor_rec'            => now()->format('G:i:s'),
                'mto_fac'            => $facrecepfactura->mto_fac,
                'concepto'           => $facrecepfactura->concepto,
                'sta_fac'            => '0',
                'fec_sta'            => now()->format('Y-m-d'),
                'usuario'            => $usuario->usuario,
                'tipo_doc'           => $facrecepfactura->tipo_doc,
                'nro_doc'            => $facrecepfactura->nro_doc,
                'ano_sol'            => $facrecepfactura->ano_sol,
                'recibo'             => $facrecepfactura->recibo,
            ]);

            //----------------------------------------------------------------------
            //Update que actualiza el correlativo incrementandolo en 1
            //----------------------------------------------------------------------
            //----------------------------------------------------------------------------------
            // Actualizar el correlativo de la Recepcion de factura
            //-----------------------------------------------------------------------------------
            if($nro_reng==1){

                $msj .= "Error Actualizando Correlativo de la Recepcion de Factura.\\nComuniquese con su Administrador de Sistema";

                $correlativo=TesCorrelativo::create([
                    'ano_pro'        => $facrecepfactura->ano_pro,
                    'correlativo'    => 2,
                    'flujo'          => 'RF',
                ]);

            }else{
                $nro_reng = $nro_reng + 1;

                $msj = "Error Actualizando Correlativo de la Recepcion de Factura.\\nComuniquese con su Administrador de Sistema";

                TesCorrelativo::where('ano_pro', $this->anoPro)
                            ->where('flujo', 'RF')
                            ->update(['correlativo' => $nro_reng]);


            }

            alert()->success('¡Éxito!', 'Factura Reactivada  Exitosamente');

            DB::connection('pgsql')->commit();

            return redirect()->route('cuentasxpagar.proceso.recepfactura.index');

        }   catch(\Illuminate\Database\QueryException $e){
            dd($e->getMessage().' '.$msj);
            DB::connection('pgsql')->rollBack();
            alert()->error('¡Transacción Fallida!'.$msj.' '. $e->getMessage());
            return redirect()->back()->withInput();

        }

    }


    public function modificar(Request $recepfactura, $id)
    {

        $usuario = Usuario::where('cedula', auth()->user()->cedula)->select('usuario')->first();

        $fecha = $this->FechaSistema($this->anoPro, "Y/m/d H:i:s");

        try {
            DB::connection('pgsql')->beginTransaction();

            //------------------------------------------------------------------
            //------------------------------------------------------------------
            $facrecepfactura = FacRecepFactura::where('ano_pro',  $recepfactura->ano_pro)
                                            ->where('nro_reng', $recepfactura->nro_reng)
                                            ->where('rif_prov', $recepfactura->rif_prov)
                                            ->where('num_fac',  $recepfactura->num_fac)
                                            ->where('recibo',   $recepfactura->recibo)
                                            ->first();



            if ( $facrecepfactura->sta_fac == '2'){

                $msj = "Error al Actualizar los Datos de la Recepcion.";

                FacRecepFactura::where('ano_pro',   $facrecepfactura->ano_pro)
                            ->where('rif_prov',  $facrecepfactura->rif_prov)
                            ->where('num_fac',   $facrecepfactura->num_fac)
                            ->where('recibo',    $facrecepfactura->recibo)
                            ->update(['observaciones' => $recepfactura->observaciones,
                                        'resp_dev'     => $recepfactura->resp_dev,
                                        'fec_dev'      => $recepfactura->fec_dev,
                                        'fec_mod'      => $recepfactura->fec_mod,
                                        'usuario_mod'  => $usuario->usuario
                                        ]);

                //----------------------------------------------------------------------
                //              Script que elimina las causas de devolucion
                //----------------------------------------------------------------------
                $msj = "Error Modificando Causas de Anulacion.";

                FacCausaxFactura::where('ano_pro',  $facrecepfactura->ano_sol)
                                ->where('nro_reng', $facrecepfactura->nro_reng)
                                ->where('rif_prov', $facrecepfactura->rif_prov)
                                ->where('num_fac',  $facrecepfactura->num_fac)
                                ->delete();


                if ($recepfactura->has('marcar') ){
                    foreach( $recepfactura->marcar as $index => $recfactura){
                        // dd( $recfactura);
                        $msj = "Error Actualizando Correlativo de Factura";
                            FacCausaxFactura::create([
                                'ano_pro'      => $recepfactura->ano_pro,
                                'nro_reng'     => $recepfactura->nro_reng,
                                'num_fac'      => $recepfactura->num_fac,
                                'cod_dev'      => $recepfactura->cod_dev[$recfactura-1],
                                'rif_prov'     => $recepfactura->rif_prov,
                                'usuario'      => 'fpalma',
                                'fecha'        => now()->format('Y-m-d'),
                                'ano_sol'      => $recepfactura->ano_sol,
                                ]);
                    }
                }
            }else{
                //--------------------------------------------------------------------
                //actualiza registro de factura
                //--------------------------------------------------------------------
                $msj = "Error al Actualizar los Datos de la Recepcion.";

                FacRecepFactura::where('ano_pro',   $facrecepfactura->ano_pro)
                            ->where('rif_prov',  $facrecepfactura->rif_prov)
                            ->where('num_fac',   $facrecepfactura->num_fac)
                            ->where('recibo',    $facrecepfactura->recibo)
                            ->update(['fec_fac' => $recepfactura->fec_fac,
                                        'mto_fac'     => $recepfactura->mto_fac,
                                        'concepto'      => $recepfactura->concepto,
                                        'fec_mod'      => $recepfactura->fec_mod,
                                        'usuario_mod'  => $usuario->usuario,
                                        'tipo_doc'  => $recepfactura->tipo_doc,
                                        'nro_doc'  => $recepfactura->nro_doc,
                                        'ano_sol'  => $recepfactura->ano_sol
                                        ]);


            }


            alert()->success('¡Éxito!', 'Factura Modificada  Exitosamente');

            DB::connection('pgsql')->commit();

            return redirect()->route('cuentasxpagar.proceso.recepfactura.index');

        }   catch(\Illuminate\Database\QueryException $e){
            dd($e->getMessage().' '.$msj);
            DB::connection('pgsql')->rollBack();
            alert()->error('¡Transacción Fallida!'.$msj.' '. $e->getMessage());
            return redirect()->back()->withInput();

        }

    }
    public function eliminar(Request $recepfactura, $id)
    {

        $usuario = Usuario::where('cedula', auth()->user()->cedula)->select('usuario')->first();



        try {
            DB::connection('pgsql')->beginTransaction();

            //----------------------------------------------------------------------
            //Busca los datos del registros para ser almacenado en la
            //                        Tabla de historico
            //----------------------------------------------------------------------
            $facrecepfactura = FacRecepFactura::where('ano_pro',  $recepfactura->ano_pro)
                                            ->where('nro_reng', $recepfactura->nro_reng)
                                            ->where('rif_prov', $recepfactura->rif_prov)
                                            ->where('num_fac',  $recepfactura->num_fac)
                                            ->where('recibo',   $recepfactura->recibo)
                                            ->first();
                                           // return $facrecepfactura ;

            if ( $facrecepfactura->num_fac != '')
            {
                if ($facrecepfactura->fec_mod == '')        {$facrecepfactura->fec_mod      = '19000101';}
                if ($facrecepfactura->fec_dev == '')        {$facrecepfactura->fec_dev      = '19000101';}
                if ($facrecepfactura->fec_reac == '')       {$facrecepfactura->fec_reac     = '19000101';}
                if ($facrecepfactura->fec_entrega == '')    {$facrecepfactura->fec_entrega  = '19000101';}
                //----------------------------------------------------------------------
                //       Script que almacena en la tabla de historico
                //----------------------------------------------------------------------
                $msj = "Error Insertando el Historico de Factura";

                FacRecepFacturaBorrada::create([
                                    'ano_pro'            => $facrecepfactura->ano_pro,
                                    'nro_reng'           => $facrecepfactura->nro_reng,
                                    'rif_prov'           => $facrecepfactura->rif_prov,
                                    'num_fac'            => $facrecepfactura->num_fac,
                                    'fec_fac'            => $facrecepfactura->fec_fac,
                                    'fec_rec'            => $facrecepfactura->fec_rec,
                                    'fec_dev'            => $facrecepfactura->fec_dev,
                                    'mto_fac'            => $facrecepfactura->mto_fac,
                                    'concepto'           => $facrecepfactura->concepto,
                                    'observaciones'      => $facrecepfactura->observaciones,
                                    'sta_fac'            => $facrecepfactura->sta_fac,
                                    'fec_sta'            => $facrecepfactura->fec_sta,
                                    'usuario'            => $usuario->usuario,
                                    'usuario_dev'        => $facrecepfactura->usuario_dev,
                                    'usuario_reac'       => $facrecepfactura->usuario_reac,
                                    'usuario_mod'        => $facrecepfactura->usuario_mod,
                                    'usuario_entrega'    => $facrecepfactura->usuario_entrega,
                                    'fec_mod'            => $facrecepfactura->fec_mod,
                                    'fec_reac'           => $facrecepfactura->fec_reac,
                                    'fec_entrega'        => $facrecepfactura->fec_entrega,
                                    'tipo_doc'           => $facrecepfactura->tipo_doc,
                                    'nro_doc'            => $facrecepfactura->nro_doc,
                                    'ano_sol'            => $facrecepfactura->ano_sol,
                                    'usuario_anu'        => $facrecepfactura->usuario_anu,
                                    'fecha_anu'          => $facrecepfactura->fecha_anu,
                                    'recibo'             => $facrecepfactura->recibo,
                ]);


                //----------------------------------------------------------------------
                // Script que inicializa nuevamente los campos del registro
                //----------------------------------------------------------------------
                $msj .= "Error  Eliminando Recepcion de Factura.\n";

                FacRecepFactura::where('ano_pro',   $facrecepfactura->ano_pro)
                            ->where('nro_reng',  $facrecepfactura->nro_reng)
                            ->where('rif_prov',  $facrecepfactura->rif_prov)
                            ->where('num_fac',   $facrecepfactura->num_fac)
                            ->where('recibo',    $facrecepfactura->recibo)
                            ->delete();
            }
            alert()->success('¡Éxito!', 'Factura Eliminada  Exitosamente');

            DB::connection('pgsql')->commit();

            return redirect()->route('cuentasxpagar.proceso.recepfactura.index');

        }   catch(\Illuminate\Database\QueryException $e){
            dd($e->getMessage().' '.$msj);
            DB::connection('pgsql')->rollBack();
            alert()->error('¡Transacción Fallida!'.$msj.' '. $e->getMessage());
            return redirect()->back()->withInput();

        }

    }


    public function print_fact_pend_devolver()
    {

        //-------------------------------------------------------------
        //----------- Buscar datos en la Cabecera ---------------------
        //-------------------------------------------------------------
        $detalle_dev = FacRecepFactura::where('sta_fac',  '2')
                                    ->orderBy('nro_reng')
                                    ->select('nro_reng', 'rif_prov', 'num_fac')
                                    ->get();

        // $query_dev = "SELECT nro_reng, rif_prov,nom_ben, num_fac
        //             FROM fac_recepfacturas inner join tes_beneficiarios on rif_prov=rif_ben
        //             where sta_fac='2' ORDER BY nro_reng";
        // $detalle_dev = $db->execSelect($conn,$query_dev );
        $num_regis_det_dev = count($detalle_dev);//Total de Registros que arrojo la consulta
        //-------------------------------------------------------------
        //------------- Datos de le Empresa ---------------------------
        //-------------------------------------------------------------

        $datos_empresa = DatosEmpresa::where('cod_empresa','01')
                                     ->selectRaw("nombre,direccion,telefono,rif,nit,fax,TO_CHAR(LOCALTIMESTAMP,'dd/mm/yyyy')as fecha")
                                    ->first();


        // $pdf = new FPDF('P','mm','A4');
        // $pdf->Open();
        // $pdf->AddPage("");

        $pdf = new Fpdf('p','mm','letter','true');
        $pdf->SetLeftMargin(2);
        $pdf->SetRightMargin(2);
        $pdf->AddPage("P");


        if ($num_regis_det_dev >0)
        {
            // Imprime  Datos de la Empresa
            $pdf->Image('img/hidrobolivar.jpg',10,10,40,13,'JPG');
            $pdf->Image('img/fondonorma.png',180,10,15,13,'PNG');

            // $pdf->Image('img/hidrobolivar.jpg', 10,15,40,15,'JPG');
            // $pdf->Image('img/fondonorma.png', 185,15,18,18,'PNG');

            //Imprime Fceha de Solicitud
            $pdf->SetFillColor(235,235,235);
            $pdf->SetFont('Arial','B',9);
            $pdf->SetY(10);
            $pdf->SetX(120);
            $pdf->Cell(30,4,'No.Reporte:',1,0,'C',1);
            $pdf->SetY(14);
            $pdf->SetX(120);
            $pdf->Cell(30,4,'Fecha Impresion:',1,0,'C',1);

            $ancho=25;
            $eje_X=150;
            $eje_Y=10;
            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','B',8);
            $pdf->SetY($eje_Y);
            $pdf->SetX($eje_X);
            $pdf->Cell($ancho,4,'RPT-MER-F02',1,0,'C',1);
            $pdf->SetY($eje_Y+4);
            $pdf->SetX($eje_X);
            $pdf->Cell($ancho,4,$datos_empresa->fecha,1,0,'C',1);
            $pdf->SetFont('Arial','B',12);
            $pdf->SetY(40);
            $pdf->SetX(85);
            $pdf->Cell(43,4,'LISTADO DE FACTURAS PENDIENTES POR ENTREGAR',0,0,'C',0);


            $pdf->SetFillColor(235,235,235);
            $pdf->SetFont('Arial','B',10);
            $pdf->SetY(50);
            $pdf->SetX(20);
            $pdf->Cell(50,7,'FACTURA',1,0,'C',1);
            $pdf->SetX(70);
            $pdf->Cell(40,7,'RIF ',1,0,'C',1);
            $pdf->SetX(110);
            $pdf->Cell(80,7,'NOMBRE PROVEEDOR',1,0,'C',1);


            // Fields Name position
            $Y_Fields_Name_position =55;
            $j = 1;

        if ( $detalle_dev){
                $total_registros = $num_regis_det_dev;
                for($i=0; $i<$total_registros; $i++)
                {	//Salto de pagina
                    if ($i==($j * 27) ){
                    $pdf->AddPage("");
                    //Fields Name position
                    $Y_Fields_Name_position =25;
                    //Table position, under Fields Name
                    $j++;
                    }
                    $pdf->SetFillColor(255,255,255);
                    $pdf->SetFont('Arial','B',7);

                $pdf->SetY($Y_Fields_Name_position);
                $pdf->SetX(20);
                $pdf->Cell(50,5,$detalle_dev[$i]->num_fac,1,0,'L',1);
                $pdf->SetX(70);
                $pdf->Cell(40,5,$detalle_dev[$i]->rif_prov,1,0,'L',1);
                $pdf->SetX(110);
                $pdf->Cell(80,5,$detalle_dev[$i]->beneficiario->nom_ben,1,0,'L',1);
                $Y_Fields_Name_position=$Y_Fields_Name_position+5;

                }


            }
            header("Content-type: application/pdf");
            $pdf->Output();
            exit();
        }

    }

    public function print_devolver_fact_recibida(FacRecepFactura $recepfactura)
    {


        // -------------------------------------------------------------
        // ----------- Buscar datos en la Cabecera ---------------------
        // -------------------------------------------------------------

        $detalle_dev = $recepfactura;


        // $detalle_dev =FacRecepFactura::find()
        //                              ->selectRaw("DISTINCT  TO_CHAR(LOCALTIMESTAMP,'dd/mm/yyyy')as fecha,
        //                                           num_fac, concepto,tipo_doc,nro_doc,observaciones")
        //                              ->first();
        // return $detalle_dev;
                                    //    where('ano_pro',  $recepfactura->ano_pro)
                                    //  ->where('nro_reng', $recepfactura->nro_reng)
                                    //  ->where('rif_prov', $recepfactura->rif_prov)
                                    //  ->where('num_fac',  $recepfactura->num_fac)
                                    //  ->where('recibo',   $recepfactura->recibo)
                                    //  ->first();

        // $query_dev = " SELECT DISTINCT  TO_CHAR(LOCALTIMESTAMP,'dd/mm/yyyy')as fecha,
        //                       tes_beneficiarios.nom_ben,fac_recepfacturas.num_fac, fac_recepfacturas.concepto,
        //                fac_recepfacturas.tipo_doc,fac_recepfacturas.nro_doc,fac_recepfacturas.observaciones,
        //                CASE  WHEN  gerencias.cod_ger= 17 THEN 'PROVEEDOR'
        //                ELSE gerencias.des_ger
        //                END AS des_ger
        //                FROM   fac_recepfacturas
        //                INNER JOIN tes_beneficiarios ON   fac_recepfacturas.rif_prov=tes_beneficiarios.rif_ben
        //                INNER JOIN gerencias ON gerencias.cod_ger=fac_recepfacturas.resp_dev
        //                WHERE fac_recepfacturas.ano_pro=".$_GET["ano_sol"]."
        //                AND fac_recepfacturas.nro_reng=".$_GET["nro_reng"]."
        //                AND fac_recepfacturas.rif_prov='".$_GET["rif_prov"]."' AND fac_recepfacturas.num_fac='".$_GET["num_fac"]."'";
        // $detalle_dev = $db->execSelect($conn,$query_dev );

       $num_regis_det_dev = 1;//count($detalle_dev);//Total de Registros que arrojo la consulta
        //-------------------------------------------------------------
        //------------- Datos del detalle   ---------------------------
        //-------------------------------------------------------------
        $datos_detalle = $recepfactura->faccausaxfactura;
       //return $query_detalle;


        // $query_detalle="SELECT DISTINCT
        // fac_causasdevolucion.cod_dev,fac_causasdevolucion.descrip_dev
        // FROM fac_recepfacturas
        // INNER JOIN fac_causas_x_factura ON   fac_causas_x_factura.ano_pro=fac_recepfacturas.ano_pro AND
        //                                                                  fac_recepfacturas.nro_reng=fac_causas_x_factura.nro_reng AND
        //                                                                   fac_recepfacturas.num_fac=fac_causas_x_factura.num_fac AND
        //                                                                   fac_recepfacturas.rif_prov=fac_causas_x_factura.rif_prov
        //                          INNER JOIN fac_causasdevolucion  ON fac_causas_x_factura.cod_dev=fac_causasdevolucion.cod_dev
        // WHERE fac_recepfacturas.ano_pro=".$_GET["ano_sol"]." AND fac_recepfacturas.nro_reng=".$_GET["nro_reng"]." AND fac_recepfacturas.rif_prov='".$_GET["rif_prov"]."' AND
        // fac_recepfacturas.num_fac='".$_GET["num_fac"]."'";
        // $datos_detalle = $db->execSelect($conn,$query_detalle);
         $num_regis_detalle = count($datos_detalle);//Total de Registros que arrojo la consulta
        //-------------------------------------------------------------
        //-------------------------------------------------------------
        //-------------------------------------------------------------
            // $pdf = new FPDF('P','mm','A4');
            // $pdf->Open();
            // $pdf->AddPage("");


            $pdf = new Fpdf('p','mm','letter','true');
            $pdf->SetLeftMargin(2);
            $pdf->SetRightMargin(2);
            $pdf->AddPage("P");


            $pdf->SetFont('Arial','B',12);
            $pdf->SetY(30);
            $pdf->SetX(90);
            $pdf->Cell(43,4,'ORDEN DE DEVOLUCION',0,0,'C',0);
            $pdf->Header();

            if (!Empty($detalle_dev->tipo_doc)){
                switch ($detalle_dev->tipo_doc)
                  {

                    case '1':
                     $estado='O/S:';
                     break;
                    case '2':
                     $estado='O/S:';
                     break;
                     case '3':
                     $estado='O/S:';
                     break;
                    case '5':
                     $estado='Contrato:';
                     break;
                    case '4':
                     $estado='Pago Directo:';
                     break;
                  }
            }
            // Imprime  Datos de la Empresa
            $pdf->Image('img/hidrobolivar.jpg',10,10,40,13,'JPG');
            //Imprime Cabecera
            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','B',12);
            $pdf->SetY(15);
            $pdf->SetX(140);
            $pdf->Cell(30,4,'Fecha:',0,0,'R',0);
            $pdf->SetX(170);
            $pdf->Cell(90,5,now()->format('d/m/Y'),0,0,'L',0);
            $pdf->SetY(40);
            $pdf->SetX(20);
            $pdf->Cell(30,4,'Proveedor:',0,0,'R',0);
            $pdf->SetX(55);
            $pdf->Cell(90,5,utf8_decode($detalle_dev->beneficiario->nom_ben),0,0,'L',0);
            $pdf->SetY(46);
            $pdf->SetX(20);
            $pdf->Cell(30,4,'Factura:',0,0,'R',0);
            $pdf->SetX(55);
            $pdf->Cell(90,5,$detalle_dev->num_fac,0,0,'L',0);
            $pdf->SetY(52);
            $pdf->SetX(20);
            $pdf->Cell(30,4,$estado,0,0,'R',0);
            $pdf->SetX(55);
            $pdf->Cell(90,5,$detalle_dev->nro_doc,0,0,'L',0);
            $pdf->SetY(58);
            $pdf->SetX(20);
            $pdf->Cell(30,4,'Gerencia:',0,0,'R',0);
            $pdf->SetX(55);
            $pdf->Cell(90,5,utf8_decode($detalle_dev->gerencia->des_ger),0,0,'L',0);
            $pdf->SetY(70);
            $pdf->SetX(125);
            $pdf->Cell(30,4,'MOTIVO DE DEVOLUCION DE SOLICITUD',0,0,'R',0);

            $pdf->SetFillColor(235,235,235);
            $pdf->SetFont('Arial','B',12);
            $pdf->SetY(80);
            $pdf->SetX(30);
            $pdf->Cell(20,5,'CODIGO',1,0,'C',1);
            $pdf->SetX(50);
            $pdf->Cell(143,5,'DESCRIPCION',1,0,'C',1);

            $pdf->Ln();
            // Fields Name position
            $Y_Fields_Name_position =85;
            $j = 1;

           if ($datos_detalle){
                $total_registros = $num_regis_detalle;
                for($i=0; $i<$total_registros; $i++)
                 {	//Salto de pagina
                    if ($i==($j * 27) ){
                      $pdf->AddPage("");
                      //Fields Name position
                      $Y_Fields_Name_position =25;
                       //Table position, under Fields Name
                       $j++;
                    }
                   $pdf->SetFillColor(255,255,255);
                   $pdf->SetFont('Arial','B',7);
                   $pdf->SetY($Y_Fields_Name_position);
                   $pdf->SetX(30);
                   $pdf->Cell(20,5,$datos_detalle[$i]->cod_dev,1,0,'C',1);
                   $pdf->SetX(50);
                   $pdf->Cell(143,5,utf8_decode($datos_detalle[$i]->faccausadevolucion->descrip_dev),1,0,'L',1);
                   $Y_Fields_Name_position=$Y_Fields_Name_position+5;
                   if ($i+1==$total_registros){
                     $pdf->SetY($Y_Fields_Name_position);
                        if($detalle_dev->observaciones<>''){
                       $pdf->SetX(30);
                        $pdf->MultiCell(163,5,'OBSERVACIONES :'.utf8_decode($detalle_dev->observaciones),1,'L',0);
                        }
                   }

                }


                    }
            //DEVUELTO POR
            $pdf->SetFont('Arial','B',11);
            $pdf->SetY(230);
            $pdf->SetX(60);
            $pdf->Cell(20,5,'DEVUELTO POR:',0,0,'C',0);
            $pdf->SetY(250);
            $pdf->SetX(100);
            $pdf->Cell(20,5,'GERENCIA DE ADMON Y FINANZAS',0,0,'C',0);

            header("Content-type: application/pdf");
            $pdf->Output();
            exit();

    }


}
