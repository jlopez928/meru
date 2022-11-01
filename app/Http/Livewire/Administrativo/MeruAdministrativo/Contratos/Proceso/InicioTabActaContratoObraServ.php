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

class InicioTabActaContratoObraServ extends Component
{

    public $accion='iniciar';
    public  $encnotaentrega;
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
        }
        //$this->emit('enableBoton');
    }
    public function mount()
    {
        if ($this->valor =='terminar' || $this->valor =='aceptar'){
            $this->fec_act = now()->format('Y-m-d');
            $contratista  =  Acta::where('encnotaentrega_id',$this->encnotaentrega->id)
                                ->where('acta','I')
                                ->first();
          //  $this->emit('enableBoton');
            if ( $contratista){
                $this->ced_con = $contratista->ced_con;
                $this->nom_con =$contratista->nom_con;
            }
        }
        if ($this->valor =='aceptar'){
            $this->revision    = 1;
            $this->lug_reunion ='SEDE HIDROBOLIVAR';
            $this->gerencia    = 13;
        }

    }
    public function updatedSelectedCedHb($id){

        if ($this->valor =='terminar'){
            $this->emit('enableTerminar');
        }elseif ($this->valor =='iniciar'){
            $this->emit('enableInicio');
        }elseif ($this->valor =='aceptar'){
            $this->emit('enableAceptar');
        }

        //$this->emit('activateBoton');

         $this->selectedCedHb = $this->selectedCedHb;
         if ($id != 0){
            $trabaj =Trabajadores::where('rif_ben',$this->selectedCedHb)->get();
            $this->nom_hb   = $trabaj[0]->nom_ben;
            $this->cargo_hb = $trabaj[0]->cargos[0]->nomcar;
         }

    }
    public function updatedSelectedActa($id){

        $actas = Acta::where('acta',$id)
                     ->Where('encnotaentrega_id',$this->encnotaentrega->id)
                     ->first();

        $this->cont_fis = $actas->xnro_ord;
        $this->jus_sol = $actas->jus_sol;
        $this->observacion = $actas->observacion;
        $this->gerencia = $actas->gerencia;
        $this->ced_con = $actas->ced_con;
        $this->nom_con = $actas->nom_con;
        $this->ced_hb = $actas->selectedCedHb.'--'.$actas->nom_hb;
    }

    public function render()
    {
        return view('livewire.administrativo.meru-administrativo.contratos.proceso.inicio-tab-acta-contrato-obra-serv',[
            'trabajador' =>  Trabajadores::query()->get(),
            'gerencias'  =>  Gerencia::query()->get(),

       ]);
    }
}
