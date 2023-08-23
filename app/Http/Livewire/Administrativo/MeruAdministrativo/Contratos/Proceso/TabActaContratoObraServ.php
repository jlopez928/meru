<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Contratos\Proceso;

use App\Models\Administrativo\Meru_Administrativo\Tesoreria\Beneficiario;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Gerencia;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\RegistroControl;
use App\Models\Administrativo\Meru_Administrativo\Compra\EncNotaEntrega;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\OpSolservicio;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Traits\funcActas;

class TabActaContratoObraServ extends Component
{
    use funcActas;

    public $tab = 'identificacion-tab';
    public $encnotaentrega;
    public $actaencnotaentrega;
    public $accion;
    public $solservicio;

//------------------------------------------------------
// TAB ACTA DE SERVICIO
//------------------------------------------------------
    // Grupo Entrega HB
    public $fk_ano_pro;
    public $grupo;
    public $nro_ent;
    public $fec_pos;
    public $fec_ent;
    public $fec_com;


    // Orden de Servicio
    public $fk_tip_ord;
    public $ano_ord_com;
    public $xnro_ord;
    public $cont_fis;
    public $fec_ord;
    public $mto_ord;
    public $tip_ent = 'T';

    // rif proveedor
    public $fk_rif_con;
    public $fk_rif_con_desc;

    //Fondo Cuenta Contable
    public $fondos;
    public $cuenta_contable;

    //factura
    public $num_fac;

    //Solicitud
    public $jus_sol;
    public $observacion;
    public $tipo_orden;

    //Estado Status
    public $sta_ent;
    public $stat_causacion;

    //recomendación
    public $recomen;

    //HIDROBOLIVAR
    public $nom_hb;
    public $ced_hb;
    public $cargo_hb;

    //Reunidos en...
    public $lug_reunion;
    public $fecha_acta;
    public $revision;
    public $gerencia;

    //CONTRATISTA
    public $nom_con;
    public $ced_con;
//------------------------------------------------------
// TAB ACTA DE SERVICIO
//------------------------------------------------------
    public $mto_anticipo;
    public $antc_amort;
    public $mto_siniva;
    public $por_iva;
    public $mto_iva;
    public $mto_ent;
    public $base_imponible;
    public $base_exenta;
//------------------------------------------------------
// TAB DETALLE ACTA SERVICIO
//------------------------------------------------------
    public $totrecep;

    public $tip_cod;
    public $cod_pryacc;
    public $cod_obj;
    //public $gerencia;
    public $unidad;
    public $cod_par;
    public $cod_gen;
    public $cod_esp;
    public $cod_sub;
    public $mto_cau;
    public $causar;
//---------------------

    // public $mto_iva;
    // public $mto_ent;
    // public $base_imponible;

    //---------------------
   public $onfocus;
   public $detallegasto= [];
   public $detalle= [];
   public $estado;
   protected $listeners =['habilita'=>'activa'];

//    public function activa()
//     {
//         $this->emit('enableComp');

//     }

   public function cargar_emit()
    {
      $this->emit('alert', ['det' => $this->detalle]);
      $this->emit('alert', ['det' => $this->detallegasto]);

    }

    public function validarAmortizacion ($valor){

        $mto_anticipo =$this->mto_anticipo;
        $porc_ant     =$this->porc_ant;
        $antc_amort   =$this->antc_amort;
        $mto_siniva   =$this->mto_siniva;
        $antc_amort_ori = (round(($mto_siniva) * $porc_ant)) / 100;

        if ($antc_amort != 0) {
            if ($mto_anticipo == 0) {
                $this->emit('swal:alert', [
                    'tipo'    => 'warning',
                    'titulo'  => 'Error',
                    'mensaje' => 'No se puede Modificar la Amortizacion de Anticipo si no Existe Anticipo.'
                ]);

                $this->antc_amort  = 0.00;
            } else {
                if ($antc_amort > $mto_anticipo) {
                    $this->emit('swal:alert', [
                        'tipo'    => 'warning',
                        'titulo'  => 'Error',
                        'mensaje' => 'La Amortizacion no puede ser Mayor al Anticipo.'
                    ]);
                    $this->antc_amort = $antc_amort_ori;
                } else {
                    if ($antc_amort >= $mto_siniva) {
                        $this->emit('swal:alert', [
                            'tipo'    => 'warning',
                            'titulo'  => 'Error',
                            'mensaje' => 'La Amortizacion no puede ser Mayor o Igual al Monto Neto.'
                        ]);
                        $this->antc_amort = $antc_amort_ori;
                    }
                }
            }
        } else {
            if ($mto_anticipo != 0) {
                $this->emit('swal:alert', [
                    'tipo'    => 'warning',
                    'titulo'  => 'Error',
                    'mensaje' => 'Cuidado, puede que no amortice todo el Anticipo si esta Valuacion no lleva Amortizacion.'
                ]);

            }
        }

       $this->emit('alert', ['tab' => '#detalle-tab','onfocus' => $this->onfocus]);
    }


    public function estGastos ($valor){


        $encnotaent = EncNotaEntrega::query()
                    ->where('xnro_ord',$this->xnro_ord)
                    ->where('fk_ano_pro',$this->ano_ord_com)
                    ->get();



        $this->antc_amort       = 0;
        $this->sin_iva          = 0;
        $this->mto_iva          = 0 ;
        $this->mto_tot          = 0;
        $this->iva_acum         = 0;

        $this->base_exenta      = 0;
        $this->base_imponible   = 0;
        $iva                    = 0;
        $this->mto_siniva       = $this->totrecep;
        $this->mto_iva          = $this->totrecep * ($this->por_iva / 100);
        $this->mto_iva          = (round(($this->mto_iva) * 100)) / 100;
        $this->mto_ent          = $this->totrecep + $this->mto_iva;


        if ($this->por_iva == 0) {
            $this->base_exenta = (round(($this->base_exenta + $this->mto_siniva) * 100)) / 100;
        } else {
            $this->base_imponible = (round(($this->base_imponible + $this->mto_siniva) * 100)) / 100;
        }

            $solservicio = OpSolservicio::where('ano_pro',$this->ano_ord_com)
                                        ->where('xnro_sol',$this->xnro_ord)
                                        // ->select('tip_contrat', 'grupo','fec_emi','rif_prov','monto_neto', 'monto_iva',
                                        //          'monto_total', 'sta_sol', 'motivo','por_anticipo', 'mto_ant', 'por_iva', 'cont_fis','ano_pro' )
                                         ->first();



        if ( $solservicio->opdetgastossolservicio){

            foreach( $solservicio->opdetgastossolservicio as $gastos){

                $this->detallegasto[] = [
                    'tip_cod'        => $gastos->tip_cod,
                    'cod_pryacc'     => $gastos->cod_pryacc,
                    'cod_obj'        => $gastos->cod_obj,
                    'gerencia'       => $gastos->gerencia,
                    'unidad'         => $gastos->unidad,
                    'cod_par'        => $gastos->cod_par,
                    'cod_gen'        => $gastos->cod_gen,
                    'cod_esp'        => $gastos->cod_esp,
                    'cod_sub'        => $gastos->cod_sub,
                    'mto_cau'        => $gastos->mto_tra,
                    'causar'         =>  $gastos->gasto == '1' ? 'Si':'No',
               ];

            }
        }
        $this->emit('enableGasto');
        $this->emit('alert', ['tab' => '#detalle-tab','onfocus' => $this->onfocus]);
    }

    public function datosContrato ($valor){
        $estado='';
        if (!empty($this->xnro_ord)){

            $this->solservicio = OpSolservicio::where('ano_pro',$this->ano_ord_com)
                                        ->where('xnro_sol',$this->xnro_ord)
                                        ->where('ult_sol','>',-1)
                                        ->select('tip_contrat', 'grupo','fec_emi','rif_prov','monto_neto', 'monto_iva',
                                                 'monto_total', 'sta_sol', 'motivo','por_anticipo', 'mto_ant', 'por_iva', 'cont_fis','ano_pro' )
                                        ->first();

            // $encnotaent = EncNotaEntrega::query()
            //                             ->where('xnro_ord',$this->xnro_ord)
            //                             ->where('fk_ano_pro',$this->ano_ord_com)
            //                             ->get();

            if (!$this->solservicio) {
                $this->emit('swal:alert', [
                    'tipo'    => 'warning',
                    'titulo'  => 'Error',
                    'mensaje' => 'Nro. de Contrato de Obras/Servicios No Existe. Por Favor Verifique'
                ]);
            }else{
                if ($this->solservicio->sta_sol->value != 4){

                    $this->emit('swal:alert', [
                        'tipo'    => 'warning',
                        'titulo'  => 'Error',
                        'mensaje' => 'Nro. de Contrato de Obras/Servicios tiene estatus: *'.$this->solservicio->sta_sol->name.'*
                        Por Favor Verifique que tenga estatus: *Comprometida Presupuestariamente*'
                    ]);
                }
                //acta
                $this->grupo            = $this->solservicio->grupo;
                // $this->tip_ent          = $encnotaent[0]->tip_ent;
                // $this->fk_tip_ord       = $encnotaent[0]->fk_tip_ord;
                $this->lfk_tip_ord      = $this->solservicio->tip_contrat;
                $this->grupo            = $this->solservicio->grupo;
                $this->lgrupo           = $this->solservicio->grupo;
                $this->fk_rif_con       = $this->solservicio->rif_prov;
                $this->lfk_rif_con      = $this->solservicio->rif_prov;
                $this->jus_sol          = $this->solservicio->motivo;
                $this->fec_ord          = $this->solservicio->fec_emi->format('Y-m-d');
                $this->mto_ord          = $this->solservicio->monto_total;
                //$this->porc_ant         = $this->solservicio->por_anticipo;
                //$this->mto_ant     = $this->solservicio->mto_ant;
                $this->fondos           = 'I';
                $this->por_iva          = $this->solservicio->por_iva;
                $this->cont_fis         = $this->solservicio->cont_fis;
                $this->fk_rif_con_desc  = $this->solservicio->beneficiario->nom_ben;
                //Detalle
                $this->mto_anticipo    = 0.0; //$encnotaent[0]->mto_anticipo;
                $this->porc_ant        = 0.0;
                $this->antc_amort      = 0.0; //$encnotaent[0]->antc_amort;
                $this->mto_siniva      = 0.0; //$encnotaent[0]->mto_siniva;
                $this->por_iva         = $this->solservicio->por_iva;
                $this->mto_iva         = 0.0; //0.0; //$encnotaent[0]->mto_iva;
                $this->mto_ent         = 0.0; //$encnotaent[0]->mto_ent;
                $this->base_imponible  = 0.0; //$encnotaent[0]->base_imponible;
                $this->base_exenta     = 0.0; //$encnotaent[0]->base_exenta;


                $detnotaentrega = DB::select("SELECT a.nro_ren, b.cod_prod, h.des_con, a.mto_tra, a.saldo, 0.00 as entrega,
                                                b.por_iva, 0.00 as mto_iva,
                                                e.tip_cod, e.cod_pryacc, e.cod_obj, e.gerencia, e.unidad,
                                                e.cod_par, e.cod_gen, e.cod_esp, e.cod_sub,
                                                'Si' as gasto,
                                                cta_gasto,
                                                c.cta_x_pagar
                                        FROM op_detgastossolservicio a
                                        INNER JOIN op_detsolservicio b ON
                                                    a.ano_pro = b.ano_pro AND a.xnro_sol = b.xnro_sol
                                        INNER JOIN pre_partidasgastos c ON
                                                    c.cod_par = a.cod_par AND c.cod_gen = a.cod_gen AND c.cod_esp = a.cod_esp AND c.cod_sub = a.cod_sub
                                        INNER JOIN pre_centrocosto d ON
                                                    d.ano_pro = a.ano_pro and d.cod_cencosto = substring(a.cod_com from 0 for 15)
                                        INNER JOIN pre_maestroley e ON
                                                    e.ano_pro =   $this->ano_ord_com   and e.cod_com = (d.ajust_ctrocosto || substring(a.cod_com from 15 for 26))
                                        INNER JOIN registrocontrol f ON f.ano_pro = e.ano_pro AND e.cod_com != f.cod_comi
                                        INNER JOIN op_conceptos_contrato h on h.cod_con=b.cod_prod
                                        WHERE a.ano_pro =   $this->ano_ord_com   and a.xnro_sol = '$this->xnro_ord'
                                                and a.saldo > 0 and a.gasto='1'
                                        UNION
                                    SELECT a.nro_ren, b.cod_prod, h.des_con, a.mto_tra, a.saldo, 0.00 as entrega,
                                                b.por_iva, 0.00 as mto_iva,
                                                e.tip_cod, e.cod_pryacc, e.cod_obj, e.gerencia, e.unidad,
                                                e.cod_par, e.cod_gen, e.cod_esp, e.cod_sub,
                                                'Si' as gasto,
                                                c.cta_gasto,
                                                c.cta_x_pagar
                                        FROM op_detgastossolservicio a
                                        INNER JOIN op_detsolservicio b ON
                                                    a.ano_pro = b.ano_pro AND a.xnro_sol = b.xnro_sol
                                        INNER JOIN pre_partidasgastos c ON
                                                    c.cod_par = a.cod_par AND c.cod_gen = a.cod_gen AND c.cod_esp = a.cod_esp AND c.cod_sub = a.cod_sub
                                        INNER JOIN pre_centrocosto d ON
                                                    d.ano_pro = a.ano_pro and d.cod_cencosto = substring(a.cod_com from 0 for 15)
                                        INNER JOIN pre_maestroley e ON
                                                    e.ano_pro =   $this->ano_ord_com   and e.cod_com = (d.ajust_ctrocosto || substring(a.cod_com from 15 for 26))
                                        INNER JOIN registrocontrol f ON f.ano_pro = e.ano_pro AND e.cod_com != f.cod_comi
                                        INNER JOIN op_solservicio g ON a.ano_pro = g.ano_pro AND a.xnro_sol = g.xnro_sol AND
                                                    g.sta_sol IN ('4', '6') AND g.mod = '0'
                                        INNER JOIN op_conceptos_contrato h on h.cod_con=b.cod_prod
                                        WHERE a.ano_pro =   $this->ano_ord_com   and a.xnro_sol LIKE '$this->xnro_ord'
                                                and a.saldo > 0 and a.gasto='1'
                                        ORDER BY mto_tra DESC;");


                foreach($detnotaentrega as $estructura){

                    $this->detalle[] = [
                        'nro_ren'        => $estructura->nro_ren,
                        'fk_cod_prod'    => $estructura->cod_prod,
                        'des_con'        => $estructura->des_con,
                        'cantidad'       => $estructura->mto_tra,
                        'saldo'          => $estructura->saldo,
                        'totrecep'       => $estructura->entrega,
                        'por_iva'        => $estructura->por_iva,
                        'mon_iva'        => $estructura->mto_iva,
                        'tip_cod'        => $estructura->tip_cod,
                        'cod_pryacc'     => $estructura->cod_pryacc,
                        'cod_obj'        => $estructura->cod_obj,
                        'gerencia'       => $estructura->gerencia,
                        'unidad'         => $estructura->unidad,
                        'cod_par'        => $estructura->cod_par,
                        'cod_gen'        => $estructura->cod_gen,
                        'cod_esp'        => $estructura->cod_esp,
                        'cod_sub'        => $estructura->cod_sub,
                        'gasto'          => $estructura->gasto,
                        'cta_cont'       => $estructura->cta_gasto,
                        'cta_x_pagar'    => $estructura->cta_x_pagar,
                    ];
                }

            }
        }
        //$this->emit('habilita');
        $this->emit('enableComp');
    }

    public function mount()
    {
       // dd($this->accion);
        $this->fk_ano_pro=2021;
        $this->fec_pos = now()->format('Y-m-d');
        $this->fec_ent = now()->format('Y-m-d');
        $this->tip_ent = 'P';
        //$this->encnotaentrega = new EncNotaEntrega();

    }

    public function getBeneficiarioProperty()
    {
        return Beneficiario::query()
        ->where('sta_reg', '1')
        ->whereIn('tipo', ['P','E','O'])
        ->orderBy('nom_ben')
        ->pluck('nom_ben','rif_ben');

    }
    public function getRegistroControlProperty()
    {
        return  RegistroControl::query()
        ->select('ano_pro','ano_pro')
        ->orderBy('ano_pro', 'desc')
        ->pluck('ano_pro','ano_pro');
    }
    public function getGerenciaProperty()
    {
        return Gerencia::query()
        ->where('status', '1')
        ->orderBy('des_ger')
        ->pluck('des_ger','cod_ger');

    }

    public function render()
    {
        return view('livewire.administrativo.meru-administrativo.contratos.proceso.tab-acta-contrato-obra-serv');
    }
}
