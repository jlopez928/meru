<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Contratos\Configuracion;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\PartidaPresupuestaria;
use Livewire\Component;

class ConceptosContratos extends Component
{  public  $conceptoscontratos;
    public $des_con;
    public $cod_con;
    public $sta_reg;
    public $partidapresupuestaria;
    public $estructuras;

    public function cargar_emit(){
        $this->emit('agregar', ['estructuras' => $this->estructuras]);
    }
    public function mount()

    {
            if( $this->conceptoscontratos->id){
                $this->cod_con=$this->conceptoscontratos->cod_con;
                $this->des_con=$this->conceptoscontratos->des_con;
                $this->sta_reg=$this->conceptoscontratos->sta_reg;
                foreach($this->conceptoscontratos->conceptoscontratodet as $key => $value) {
                    $this->estructuras[$value->partida_presupuestaria_id] = [
                        'estructura' => $value->partida_presupuestaria_id,
                        'cod_par'    => $value->cod_par,
                        'cod_gen'    => $value->cod_gen,
                        'cod_esp'    => $value->cod_esp,
                        'cod_sub'    => $value->cod_sub,
                        'des_con'    => $value->partidapresupuestaria->des_con,
                    ];
                }
              }else{
                $this->cod_con;
                $this->des_con;
                $this->sta_reg;
                $this->estructuras = [];
            }
            $valor = json_encode(session()->getOldInput());
            $valor = json_decode($valor);
            if ($valor) {
                foreach($valor as $key => $value) {
                    $this->$key = $value;
            }
                $this->estructuras=  json_decode($this->estructuras,true);
            }

    }

    public function getPartidaPresupuestariaProperty()
    {
        return PartidaPresupuestaria::orderBy('cod_cta')->where('sta_reg','=','1')->get();

    }
    public function changeSelect($selectid)
    {
       $this->partidapresupuestaria= $selectid;
    }
    public function eliminarEstructura($key)
    {
        unset($this->estructuras[$key]);
        $this->emit('agregar', ['estructuras' => $this->estructuras]);

    }
    public function agregarPartida()
    {
        if($this->partidapresupuestaria) {
            //Validar que la partida no ha sido seleccionada
            $estructura = PartidaPresupuestaria::find($this->partidapresupuestaria);
            if (array_key_exists($estructura->id, $this->estructuras)) {
                $this->emit('swal:alert', [
                    'tipo'    => 'warning',
                    'titulo'  => 'Error',
                    'mensaje' => 'La estructura [' . $estructura->des_con . '], ya fue registrada. Favor Verifique'
                ]);
            } else {
                $this->estructuras[$estructura->id] = [
                    'estructura' => $estructura->id,
                    'cod_par'    => $estructura->cod_par,
                    'cod_gen'    => $estructura->cod_gen,
                    'cod_esp'    => $estructura->cod_esp,
                    'cod_sub'    => $estructura->cod_sub,
                    'des_con'    => $estructura->des_con,
                ];
                $this->emit('agregar', ['estructuras' => $this->estructuras]);
                $this->partidapresupuestaria= "";
            }
        }else{
            $this->emit('swal:alert', [
                'tipo'    => 'warning',
                'titulo'  => 'Error',
                'mensaje' => 'Debe seleccionar Patida Presupuestaria.Favor Verifique.'
            ]);
        }
    }
    public function render()
    {
        return view('livewire.administrativo.meru-administrativo.contratos.configuracion.conceptos-contratos');
    }
}
