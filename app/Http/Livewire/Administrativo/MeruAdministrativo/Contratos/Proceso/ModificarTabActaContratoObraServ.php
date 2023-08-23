<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Contratos\Proceso;

use App\Models\Administrativo\Meru_Administrativo\Tesoreria\Beneficiario;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Gerencia;
use App\Models\Administrativo\Meru_Administrativo\Compra\Acta;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\RegistroControl;
use App\Models\Administrativo\Meru_Administrativo\Compra\EncNotaEntrega;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\OpSolservicio;
use App\Models\Administrativo\Meru_Administrativo\General\Trabajadores;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Traits\funcActas;

class ModificarTabActaContratoObraServ extends Component
{

    public $accion='iniciar';
    public  $encnotaentrega;
    public  $beneficiarios;
    public  $statusent;
    public $statcomprob;
    public $actacontratobraserv;
    public $enableInicio;
    public $valor;
    public $ced_hb;
    public $nom_hb;
    public $cargo_hb;
    public $lug_reunion;
    public $fec_act;
    public $revision;
    public $gerencia;
    public $ced_con;
    public $nom_con;
    public $selectedCedHb;
    public $selectedActa;
    public $trabaj;
    public $enc_id;
    public $fk_ano_pro;
    public $grupo;
    public $nro_ent;
    public $cont_fis;
    public $observacion;
    public $jus_sol;





    public function cargar_emit()
    {
        if ($this->valor =='terminar'){
            $this->emit('enableTerminar');
        }elseif ($this->valor =='iniciar'){
            $this->emit('enableInicio');
        }elseif ($this->valor =='aceptar'){
            $this->emit('enableAceptar');
        }elseif ($this->valor =='anular'){
            $this->emit('enableAnular');
        }elseif ($this->valor =='modificar'){
            $this->emit('enableModificar');
        }elseif ($this->valor =='reimprimir'){
            $this->emit('enableReimprimir');
        }
    }


    public function mount()
    {

    }

    public function updatedSelectedCedHb($id){
         $this->selectedCedHb = $this->selectedCedHb;
         $trabaj =Trabajadores::where('rif_ben',$this->selectedCedHb)->get();
         $this->nom_hb   = $trabaj[0]->nom_ben;
         $this->cargo_hb = $trabaj[0]->cargos[0]->nomcar;

    }


    public function updatedSelectedActa($id){

        if ($this->valor =='modificar')
            $this->emit('enableModificar');
        else
            $this->emit('enableReimprimir');

        $actas = Acta::where('acta',$id)
                     ->Where('encnotaentrega_id',$this->encnotaentrega->id)
                     ->first();
        if ($actas){
            $this->cont_fis         = $actas->xnro_ord;
            $this->jus_sol          = $actas->jus_sol;
            $this->observacion      = $actas->observacion;
            $this->gerencia         = $actas->gerencia;
            $this->ced_con          = $actas->ced_con;
            $this->nom_con          = $actas->nom_con;
            $this->selectedCedHb    = $actas->ced_hb;
            $this->nom_hb           = $actas->nom_hb;
            $this->cargo_hb         = $actas->cargo_hb;
            $this->fec_act          = $actas->fec_act;
            $this->revision         = $actas->revision;
        }


        //    $this->emit('enableBoton');
    }

    public function render()
    {
        return view('livewire.administrativo.meru-administrativo.contratos.proceso.modificar-tab-acta-contrato-obra-serv',[
            'trabajador' =>  Trabajadores::query()->get(),
            'gerencias'  =>  Gerencia::query()->get(),
       ]);
    }
}
