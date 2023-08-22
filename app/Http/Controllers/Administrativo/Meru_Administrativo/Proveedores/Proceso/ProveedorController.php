<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Proveedores\Proceso;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Administrativo\Meru_Administrativo\Proveedores\Proveedor;
use App\Models\Administrativo\Meru_Administrativo\Proveedores\CorrProveedor;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\UbicacionGeografica;
use App\Http\Requests\Administrativo\Meru_Administrativo\Proveedores\Proceso\ProveedorRequest;
use App\Models\Administrativo\Meru_Administrativo\Rr_hh\EnteForaneo;

class ProveedorController extends Controller
{
    public function index()
    {
        return view('administrativo.meru_administrativo.proveedores.proceso.proveedor.index');
    }

    public function create()
    {
        $proveedor = new Proveedor();
        $accion = 'nuevo';

        return view('administrativo.meru_administrativo.proveedores.proceso.proveedor.create', compact('proveedor', 'accion'));
    }

    public function store(ProveedorRequest $request)
    {
        try {
            // Buscar el correlativo del proveedor
            $corr_num_reg = CorrProveedor::getCorrProveedor($this->anoPro);

            $num_reg = $corr_num_reg > 0 ? $corr_num_reg : 1;

            DB::connection('pgsql')->transaction(function () use($request, $num_reg){

                // TODO cod_prov en formatear teoria deberia generar un codigo de 6 digitos, pero los genera de 5
                // TODO La function formatear deberia ser migrada a un trait (conversar con el equipo)
                $cod_prov = $request->tip_emp."-".Proveedor::formatear($num_reg, 5)."-".Proveedor::formatear(((date("Y"))-2000),2);

                Proveedor::create([
                    'tip_emp'           => $request->tip_emp,
                    'rif_prov'          => $request->rif_prov,
                    'cod_prov'          => $cod_prov,
                    'tip_reg'           => $request->tip_reg,
                    'nom_prov'          => $request->nom_prov,
                    'sig_prov'          => $request->sig_prov,
                    'dir_prov'          => $request->dir_prov,
                    'tlf_prov1'         => $request->tlf_prov1,
                    'tlf_prov2'         => $request->tlf_prov2,
                    'fax'               => $request->fax,
                    'email'             => $request->email,
                    'sta_emp'           => $request->sta_emp,
                    'capital'           => $request->capital,
                    'objetivo'          => $request->objetivo,
                    'nro_rnc'           => $request->nro_rnc,
                    'nro_sunacoop'      => $request->nro_sunacoop,
                    'fec_susp'          => $request->fec_susp,
                    'objetivo_gral'     => $request->objetivo_gral,
                    'ced_res'           => $request->ced_res,
                    'nom_res'           => $request->nom_res,
                    'car_res'           => $request->car_res,
                    'ubi_pro'           => $request->ubi_pro,
                    'cod_edo'           => $request->cod_edo,
                    'cod_mun'           => $request->cod_mun,
                    'nivel_cont'        => $request->nivel_cont,
                    'num_fem'           => $request->num_fem,
                    'num_mas'           => $request->num_mas,
                    'sol_ivss'          => $request->sol_ivss,
                    'fec_ivss'          => $request->fec_ivss,
                    'sol_ince'          => $request->sol_ince,
                    'fec_ince'          => $request->fec_ince,
                    'sol_laboral'       => $request->sol_laboral,
                    'fec_laboral'       => $request->fec_laboral,
                    'sol_agua'          => $request->sol_agua,
                    'fec_agua'          => $request->fec_agua,
                    'sta_con'           => '0',
                    'usuario'           => auth()->user()->id,
                    'fecha'             => now(),
                    'cuenta_hid'        => $request->cuenta_hid,
                    'inscrito_rnc'      => $request->inscrito_rnc,
                    'tipo'              => 'P'
                ]);

                EnteForaneo::create([
                                        'rifenteforaneo'        => $request->rif_prov,
                                        'nomenteforaneo'        => $request->nom_prov,
                                        'direnteforaneo'        => $request->dir_prov,
                                        'telfonoenteforaneo'    => $request->tlf_prov1,
                                        'emailenteforano'       => $request->email,
                                        'idstatus'              => '1',
                                        // TODO Verificar el idusuario, $idusuario = $_SESSION['IDUSUARIO'];
                                        'idusuarioregistro'     => auth()->user()->id,
                                        'fecharegistro'         => now()
                                    ]);

                // Actualizar el correlativo
                CorrProveedor::incCorrProveedor($this->anoPro, $num_reg == 1 ? 2 : $num_reg + 1);
            });

            alert()->html('',   "Se ha Creado Exitosamente el Proveedor ".$request->rif_prov,'success')
                ->showConfirmButton('Ok', '#3085d6');

            return to_route('compras.proceso.proveedor.index');
        } catch (\Exception $ex) {
            alert()->error('Error', str($ex)->limit(250));

            return redirect()->back()->withInput();
        }
    }

    public function edit(Proveedor $proveedor)
    {
        $accion = 'editar';

        return view('administrativo.meru_administrativo.proveedores.proceso.proveedor.edit', compact('proveedor','accion'));
    }

    public function update(ProveedorRequest $request, Proveedor $proveedor)
    {
        try {
            DB::connection('pgsql')->transaction(function () use($request, $proveedor){

                $proveedor->update([
                    'nom_prov'          => $request->nom_prov,
                    'inscrito_rnc'      => $request->inscrito_rnc,
                    'tip_reg'           => $request->tip_reg,
                    'sig_prov'          => $request->sig_prov,
                    'dir_prov'          => $request->dir_prov,
                    'tlf_prov1'         => $request->tlf_prov1,
                    'tlf_prov2'         => $request->tlf_prov2,
                    'fax'               => $request->fax,
                    'email'             => $request->email,
                    'sta_emp'           => $request->sta_emp,
                    'capital'           => $request->capital,
                    'objetivo'          => $request->objetivo,
                    'cuenta_hid'        => $request->cuenta_hid,
                    'nro_rnc'           => $request->nro_rnc,
                    'nro_sunacoop'      => $request->nro_sunacoop,
                    'fec_susp'          => $request->fec_susp,
                    'ced_res'           => $request->ced_res,
                    'objetivo_gral'     => $request->objetivo_gral,
                    'nom_res'           => $request->nom_res,
                    'car_res'           => $request->car_res,
                    'ubi_pro'           => $request->ubi_pro,
                    'cod_edo'           => $request->cod_edo,
                    'cod_mun'           => $request->cod_mun,
                    'nivel_cont'        => $request->nivel_cont,
                    'sol_ivss'          => $request->sol_ivss,
                    'fec_ivss'          => $request->fec_ivss,
                    'sol_ince'          => $request->sol_ince,
                    'fec_ince'          => $request->fec_ince,
                    'sol_laboral'       => $request->sol_laboral,
                    'fec_laboral'       => $request->fec_laboral,
                    'sol_agua'          => $request->sol_agua,
                    'fec_agua'          => $request->fec_agua,
                    'usu_mod'           => auth()->user()->id,
                    'fec_mod'           => now()
                ]);

                EnteForaneo::where('rifenteforaneo', $request->rif_prov)
                                    ->update([
                                        'nomenteforaneo'        => $request->nom_prov,
                                        'direnteforaneo'        => $request->dir_prov,
                                        'telfonoenteforaneo'    => $request->tlf_prov1,
                                        'emailenteforano'       => $request->email,
                                        // TODO Verificar el idusuario, $idusuario = $_SESSION['IDUSUARIO'];
                                        'idusuarioeditado'     => auth()->user()->id,
                                        'fechaeditado'         => now()
                                    ]);
            });

            alert()->html('',   "Se ha Modificado Exitosamente el Proveedor ".$request->rif_prov,'success')
                ->showConfirmButton('Ok', '#3085d6');

            return to_route('compras.proceso.proveedor.index');
        } catch (\Exception $ex) {
            alert()->error('Error', str($ex)->limit(250));

            return redirect()->back()->withInput();
        }
    }

    public function show(Proveedor $proveedor)
    {
        $accion = 'show';

        return view('administrativo.meru_administrativo.proveedores.proceso.proveedor.show', compact('proveedor', 'accion'));
    }

    public function suspender(Proveedor $proveedor)
    {
        $accion = 'suspender';

        return view('administrativo.meru_administrativo.proveedores.proceso.proveedor.suspender', compact('proveedor', 'accion'));
    }

    //* TODO Revisar los valores de usu_sus y idusuarioeditado
    public function suspender_proveedor(Request $request, Proveedor $proveedor)
    {
        $request->validate(
                [
                    'cau_sus' => 'required'
                ],
                [
                    'cau_sus.required' => 'El campo causa suspensión es obligatorio.'
                ]);

        try {
            DB::connection('pgsql')->transaction(function () use($request){

                Proveedor::query()
                            ->where('rif_prov', $request->rif_prov)
                            ->update([
                                'sta_con' => '2',
                                'usu_sus' => auth()->user()->id,
                                'cau_sus' => strtoupper($request->cau_sus),
                                'fec_sus' => now()
                            ]);

                EnteForaneo::query()
                                ->where('rifenteforaneo', $request->rif_prov)
                                ->update([
                                    'idstatus'          => '0',
                                    'idusuarioeditado'  => auth()->user()->id,
                                    'fechaeditado'      => now()
                                ]);
            });

            alert()->html('',"Se ha Suspendido Exitosamente el Proveedor ".$request->rif_prov,'success')
            ->showConfirmButton('Ok', '#3085d6');

            return to_route('proveedores.proceso.proveedor.index');
        } catch (\Exception $ex) {
            alert()->error('Error', str($ex)->limit(250));

            return redirect()->back()->withInput();
        }
    }

    public function reactivar(Proveedor $proveedor)
    {
        $accion = 'reactivar';

        return view('administrativo.meru_administrativo.proveedores.proceso.proveedor.reactivar', compact('proveedor', 'accion'));
    }

    //* TODO Revisar los valores de usu_act y idusuarioeditado
    public function reactivar_proveedor(Proveedor $proveedor)
    {
        try {
            DB::connection('pgsql')->transaction(function () use($proveedor){

                $proveedor->update([
                                'cau_sus' => null,
                                'sta_con' => '0',
                                'usu_act' => auth()->user()->id,
                                'fec_act' => now()
                            ]);

                EnteForaneo::query()
                                ->where('rifenteforaneo', $proveedor->rif_prov)
                                ->update([
                                    'idstatus'          => '1',
                                    'idusuarioeditado'  => auth()->user()->id,
                                    'fechaeditado'      => now()
                                ]);
            });

            alert()->html('',"Se ha Activado Exitosamente el Proveedor ".$proveedor->rif_prov,'success')
            ->showConfirmButton('Ok', '#3085d6');

            return to_route('proveedores.proceso.proveedor.index');
        } catch (\Exception $ex) {
            alert()->error('Error', str($ex)->limit(250));

            return redirect()->back()->withInput();
        }
    }

    public function destroy(Proveedor $proveedor)
    {
        try {
            $proveedor->delete();

            alert()->success('Éxito','Proveedor Eliminado Exitosamente');

            return to_route('proveedores.proceso.proveedor.index');
        } catch (\Exception $ex) {

            alert()->error('Error', str($ex)->limit(250));

            return redirect()->back();
        }
    }

    public function getEstados($ubicacion = null)
    {
        return UbicacionGeografica::query()
                                    ->when($ubicacion === 'E',
                                        fn($q) => $q->where('cod_edo', 50)
                                    )
                                    ->where('cod_mun', '0')
                                    ->where('cod_par', '0')
                                    ->orderBy('des_ubi')
                                    ->get(['cod_edo', 'des_ubi']);
    }

    public function getMunicipios($estado = null)
    {
        return UbicacionGeografica::query()
                                    ->where('cod_edo', $estado)
                                    ->where('cod_mun', '!=', 0)
                                    ->where('cod_par', 0)
                                    ->orderBy('des_ubi')
                                    ->get(['cod_mun', 'des_ubi']);
    }
}
