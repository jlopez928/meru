<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Compras\Configuracion;

use Livewire\Component;
use App\Enums\Administrativo\Meru_Administrativo\Estado;
use App\Models\Administrativo\Meru_Administrativo\Compras\Producto;
use App\Models\Administrativo\Meru_Administrativo\Proveedores\Ramo;
use App\Models\Administrativo\Meru_Administrativo\Compras\UnidadMedida;
use App\Models\Administrativo\Meru_Administrativo\Compras\GrupoProducto;
use App\Models\Administrativo\Meru_Administrativo\Compras\PrePartidaGasto;
use App\Models\Administrativo\Meru_Administrativo\Compras\SubGrupoProducto;

class BienMaterialServicio extends Component
{
    public $producto;
    public $tip_prod;
    public $grupos = [];
    public $grupo;
    public $subgrupos = [];
    public $subgrupo;
    public $gru_ram;
    public $cod_prod;
    public $des_prod;
    public $cod_uni;
    public $fec_act;
    public $ult_pre = '0.00';
    public $sta_reg;
    public $cod_par;
    public $partidas = [];
    public $cod_gen;
    public $genericas = [];
    public $cod_esp;
    public $especificas = [];
    public $cod_sub;
    public $subespecificas = [];
    public $accion;

    protected $listeners = ['asignarPartidas','update', 'store'];

    protected $messages = [
        'des_prod.required' => 'El campo descripción es obligatorio',
        'cod_uni.required'  => 'El campo unidad medida es obligatorio',
        'ult_pre.required'  => 'El campo ultimo precio es obligatorio',
        'cod_par.required'  => 'El campo partida es obligatorio',
        'cod_gen.required'  => 'El campo genérica es obligatorio',
        'cod_esp.required'  => 'El campo específica es obligatorio',
        'tip_prod.required' => 'El campo tipo es obligatorio',
        'grupo.required'    => 'El campo grupo es obligatorio',
        'subgrupo.required' => 'El campo subgrupo es obligatorio',
        'gru_ram.required'  => 'El campo grupo-ramo es obligatorio'
    ];

    public function mount()
    {
        if ($this->producto->cod_prod) {
            $this->tip_prod         = $this->producto->tip_prod->value;
            $this->grupo            = $this->producto->grupo;
            $this->subgrupo         = $this->producto->subgrupo;
            $this->grupos           = GrupoProducto::getGrupos($this->tip_prod);
            $this->subgrupos        = SubGrupoProducto::getSubGrupos($this->tip_prod, $this->grupo);
            $this->gru_ram          = $this->producto->gru_ram;
            $this->cod_prod         = $this->producto->cod_prod;
            $this->des_prod         = $this->producto->des_prod;
            $this->cod_uni          = $this->producto->cod_uni;
            $this->fec_act          = $this->producto->fec_act?->format('Y-m-d');
            $this->ult_pre          = $this->producto->ult_pre;
            $this->sta_reg          = $this->producto->sta_reg->value;
            $this->cod_par          = $this->producto->cod_par;
            $this->partidas         = PrePartidaGasto::getPartidas();
            $this->cod_gen          = $this->producto->cod_gen;
            $this->genericas        = PrePartidaGasto::getGenericas($this->cod_par);
            $this->cod_esp          = $this->producto->cod_esp;
            $this->especificas      = PrePartidaGasto::getEspecificas($this->cod_par, $this->cod_gen);
            $this->cod_sub          = $this->producto->cod_sub;
            $this->subespecificas   = PrePartidaGasto::getSubEspecificas($this->cod_par, $this->cod_gen, $this->cod_sub);
        }else{
            $this->fec_act      = now()->format('Y-m-d');
            $this->partidas     = PrePartidaGasto::getPartidas();
        }
    }

    public function updatedTipProd($tip_prod)
    {
        $this->reset('grupos', 'subgrupos');

        $this->grupos = GrupoProducto::getGrupos($tip_prod);
    }

    public function updatedGrupo($grupo)
    {
        $this->reset('subgrupos');

        $this->subgrupos = SubGrupoProducto::getSubGrupos($this->tip_prod, $grupo);
    }

    public function updatedSubGrupo($subgrupo)
    {
        $ultimoCodigo = Producto::query()
                                ->selectRaw("SUBSTRING(cod_prod, 6) as cod_prod")
                                ->where('cod_prod', 'like', '%'.$subgrupo.'%')
                                ->orderBy('cod_prod', 'Desc')
                                ->first();

        if ($ultimoCodigo) {
            $cp2 = intval($ultimoCodigo->cod_prod) + 1;

            $dif = 5 -  strlen($cp2);

            for ($i=1; $i < $dif; $i++) {
                $cp2 = "0".$cp2;
            }

            $cod_p = $this->subgrupo . $cp2;
        } else {
            $cod_p = $this->subgrupo . "0001";
        }

        $this->cod_prod = $cod_p;
    }

    public function confirmStore()
    {
        $this->emit('swal:confirm', [
            'tipo'      => 'warning',
            'titulo'    => 'Bien/Material/Servicio',
            'mensaje'   => 'Está seguro de Guardar el Bien/Material/Servicio?',
            'funcion'   => 'store'
        ]);
    }

    public function store()
    {
        $this->validate([
            'tip_prod'  => 'required',
            'grupo'     => 'required',
            'subgrupo'  => 'required',
            'gru_ram'  => 'required',
            'des_prod'  => 'required',
            'cod_uni'   => 'required',
            'ult_pre'   => 'required'
        ]);

        try {
            Producto::create([
                'cod_prod' => $this->cod_prod,
                'des_prod' => strtoupper($this->des_prod),
                'cod_uni'  => $this->cod_uni,
                'fec_act'  => now(),
                'tip_prod' => $this->tip_prod,
                'grupo'    => $this->grupo,
                'subgrupo' => $this->subgrupo,
                'prod'     => substr($this->cod_prod, 5),
                'sta_reg'  => Estado::Activo->value,
                'usuario'  => auth()->id(),
                'fecha'    => now(),
                'gru_ram'  => $this->gru_ram,
                'ult_pre'  => $this->ult_pre
            ]);

            $this->emit('swal:alert', [
                'tipo'      => 'success',
                'titulo'    => 'Éxito',
                'mensaje'   => 'Registro Agregado Exitosamente'
            ]);

            return to_route('compras.configuracion.bien_material_servicio.index');

        } catch (\Exception $ex) {
            $this->emit('swal:alert', [
                'tipo'      => 'error',
                'titulo'    => 'Error',
                'mensaje'   => str($ex)->limit(250)
            ]);

            return redirect()->back()->withInput();
        }
    }

    public function confirmUpdate()
    {
        $this->emit('swal:confirm', [
            'tipo'      => 'warning',
            'titulo'    => 'Bien/Material/Servicio',
            'mensaje'   => 'Está seguro de Modificar el Bien/Material/Servicio?',
            'funcion'   => 'update'
        ]);
    }

    public function update()
    {
        $this->validate([
            'des_prod'  => 'required',
            'cod_uni'   => 'required',
            'ult_pre'   => 'required'
        ]);

        try {
            Producto::query()
                ->where('cod_prod', $this->cod_prod)
                ->update([
                            'des_prod' => strtoupper($this->des_prod),
                            'cod_uni'  => $this->cod_uni,
                            'fec_act'  => now(),
                            'ult_pre'  => $this->ult_pre,
                            'sta_reg'  => $this->sta_reg
                        ]);

            $this->emit('swal:alert', [
                'tipo'      => 'success',
                'titulo'    => 'Éxito',
                'mensaje'   => 'Registro Modificado Exitosamente'
            ]);

            return to_route('compras.configuracion.bien_material_servicio.index');

        } catch (\Exception $ex) {
            $this->emit('swal:alert', [
                'tipo'      => 'error',
                'titulo'    => 'Error',
                'mensaje'   => str($ex)->limit(250)
            ]);

            return redirect()->back()->withInput();
        }
    }

    public function confirmAsignarPartida()
    {
        $this->emit('swal:confirm', [
            'tipo'      => 'warning',
            'titulo'    => 'Asignar Partidas',
            'mensaje'   => 'Está seguro de Asignar esta Partida?',
            'funcion'   => 'asignarPartidas'
        ]);
    }

    public function asignarPartidas()
    {
        $this->validate([
            'cod_par'  => 'required',
            'cod_gen'  => 'required',
            'cod_esp'  => 'required',
        ]);

        try {
            Producto::query()
                ->where('cod_prod', $this->cod_prod)
                ->update([
                            'cod_par'  => $this->cod_par,
                            'cod_gen'  => $this->cod_gen,
                            'cod_esp'  => $this->cod_esp,
                            'cod_sub'  => $this->cod_sub
                        ]);

            $this->emit('swal:alert', [
                'tipo'      => 'success',
                'titulo'    => 'Éxito',
                'mensaje'   => 'Partidas Asignadas Exitosamente'
            ]);

            return to_route('compras.configuracion.bien_material_servicio.index');

        } catch (\Exception $ex) {
            $this->emit('swal:alert', [
                'tipo'      => 'error',
                'titulo'    => 'Error',
                'mensaje'   => str($ex)->limit(250)
            ]);

            return redirect()->back()->withInput();
        }
    }

    public function getRamoProperty()
    {
        return Ramo::query()
                        ->orderby('des_ram')
                        ->pluck('des_ram', 'cod_ram');
    }

    public function getUnidadMedidaProperty()
    {
        return UnidadMedida::query()
                        ->orderby('des_uni')
                        ->pluck('des_uni', 'cod_uni');
    }

    public function updatedCodPar($cod_par)
    {
        $this->reset('cod_gen','genericas','cod_esp','especificas','cod_sub','subespecificas');

        $this->genericas = PrePartidaGasto::getGenericas($cod_par);
    }

    public function updatedCodGen($cod_gen)
    {
        $this->reset('cod_esp','especificas','cod_sub','subespecificas');

        $this->especificas = PrePartidaGasto::getEspecificas($this->cod_par, $cod_gen);
    }

    public function updatedCodEsp($cod_sub)
    {
        $this->reset('cod_sub','subespecificas');

        $this->subespecificas = PrePartidaGasto::getSubEspecificas($this->cod_par, $this->cod_gen, $cod_sub);
    }

    public function render()
    {
        return view('livewire.administrativo.meru-administrativo.compras.configuracion.bien-material-servicio');
    }
}
