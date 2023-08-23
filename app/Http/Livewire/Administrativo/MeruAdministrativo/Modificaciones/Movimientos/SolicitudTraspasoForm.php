<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Modificaciones\Movimientos;

use App\Models\Administrativo\Meru_Administrativo\Configuracion\Gerencia;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\CentroCosto;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\PartidaPresupuestaria;
use Livewire\Component;

class SolicitudTraspasoForm extends Component
{
    public $solicitudTraspaso;
    public $ano_pro;
    public $nro_sol;
    public $fec_sol;
    public $num_sop;
    public $cod_ger;
    public $nro_ext;
    public $concepto;
    public $justificacion;
    public $total;
    public $partida;
    public $monto;
    //public $gerencias;
    //public $partidas;
    public $estructuras;

    public function mount($solicitudTraspaso)
    {
        $this->solicitudTraspaso = $solicitudTraspaso;

        if ($this->solicitudTraspaso->id) {
            $this->ano_pro = $solicitudTraspaso->ano_pro;
            $this->nro_sol = $solicitudTraspaso->nro_sol;
            $this->fec_sol = \Carbon\Carbon::parse($solicitudTraspaso->fec_sol)->format('d/m/Y');
            $this->num_sop = $solicitudTraspaso->num_sop;
            $this->cod_ger = $solicitudTraspaso->cod_ger;
            $this->nro_ext = $solicitudTraspaso->nro_ext;
            $this->concepto = $solicitudTraspaso->concepto;
            $this->justificacion = $solicitudTraspaso->justificacion;
            $this->total = number_format($solicitudTraspaso->total, 2, ',', '.');
            $this->monto = '';
            $this->estructuras = $solicitudTraspaso->obtenerEstructuras();
        } else {
            $this->ano_pro = session('ano_pro');
            $this->nro_sol;
            $this->fec_sol = now()->format('d/m/Y');
            $this->num_sop = 'SOLICITUD';
            $this->cod_ger;
            $this->nro_ext;
            $this->concepto;
            $this->justificacion;
            $this->total = 0;
            $this->monto = '';
            //$this->gerencias = Gerencia::orderBy('des_ger')->get();
            //$this->partidas  = PartidaPresupuestaria::orderBy('cod_cta')->get();
            $this->estructuras = [];
        }

        $valor = json_encode(session()->getOldInput());
        $valor = json_decode($valor);

        if ($valor) {
            foreach($valor as $key => $value) {
                $this->$key = $value;
            }

            $this->estructuras = json_decode($this->estructuras, true);
        }
    }

    public function inicializar()
    {
        $this->emit('estructura:act', ['estructuras' => $this->estructuras]);
    }

    protected $listeners = ['changeSelect'];

    public function changeSelect($valor, $id)
    {
        switch ($id) {
            case 'cod_ger':
                $this->cod_ger = $valor;
                /*
                $ger  = Gerencia::find($valor);
                $ceco = CentroCosto::where('ano_pro', $this->ano_pro)->where('cod_cencosto', $ger->centro_costo)->first();
                */
                break;
            default:
                $this->partida = $valor;
                break;
        }
    }

    public function getGerenciaProperty()
    {
        return Gerencia::orderBy('des_ger')->get();
    } 

    public function getPartidaPresupuestariaProperty()
    {
        return PartidaPresupuestaria::orderBy('cod_cta')->get();
    }

    public function agregarEstructura()
    {
        $monto = \Str::replace(',', '.', \Str::replace('.','', $this->monto));
        $total = \Str::replace(',', '.', \Str::replace('.','', $this->total));

        if ($this->cod_ger && $this->partida && $this->monto > 0) {
            $gerencia   = Gerencia::find($this->cod_ger);
            $codCeco    = explode('.', $gerencia->centro_costo);
            $partida    = PartidaPresupuestaria::find($this->partida);
            $arrPartida = explode('.', \Str::substr($partida->cod_cta, 2));
            $estructura = $gerencia->centro_costo . '.' . \Str::substr($partida->cod_cta, 2);

            if (array_key_exists($estructura, $this->estructuras)) {
                $this->emit('swal:alert', [
                    'tipo'    => 'warning',
                    'titulo'  => 'Error',
                    'mensaje' => 'La estructura ' . $estructura . ' ya fue registrada'
                ]);
            } else {
                $this->estructuras[$estructura] = [
                    'cod_com'    => $estructura,
                    'tip_cod'    => (int)$codCeco[0],
                    'cod_pryacc' => (int)$codCeco[1],
                    'cod_obj'    => (int)$codCeco[2],
                    'gerencia'   => (int)$codCeco[3],
                    'unidad'     => (int)$codCeco[4],
                    'cod_par'    => (int)$arrPartida[0],
                    'cod_gen'    => (int)$arrPartida[1],
                    'cod_esp'    => (int)$arrPartida[2],
                    'cod_sub'    => (int)$arrPartida[3],
                    'descrip'    => $partida->des_con,
                    'mto_tra'    => $monto,
                ];

                $total += (float)$monto;

                $this->total = number_format($total, 2, ',', '.');

                $this->partida = '';
                $this->monto   = '';
                $this->emit('estructura:act', ['estructuras' => $this->estructuras]);
                $this->emit('partida', ['valor' => $this->partida]);
            }
        } else {
            $this->emit('swal:alert', [
                'tipo'    => 'warning',
                'titulo'  => 'Error',
                'mensaje' => 'Debe seleccionar la Gerencia y una partida para poder agregar la Estructura Presupuestaria.'
            ]);
        }
    }

    public function eliminarEstructura($key)
    {
        $total = \Str::replace(',', '.', \Str::replace('.','', $this->total));
        $total -= (float)$this->estructuras[$key]['mto_tra'];
        $this->total = number_format($total, 2, ',', '.');
        unset($this->estructuras[$key]);
        $this->emit('estructura:act', ['estructuras' => $this->estructuras]);
    }

    public function render()
    {
        return view('livewire.administrativo.meru-administrativo.modificaciones.movimientos.solicitud-traspaso-form');
    }
}