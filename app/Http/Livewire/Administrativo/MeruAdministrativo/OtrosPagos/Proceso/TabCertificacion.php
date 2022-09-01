<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\OtrosPagos\Proceso;


use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\OpDetsolservicio;
use App\Models\Administrativo\Meru_Administrativo\Tesoreria\Beneficiario;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Gerencia;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\OpConceptosDet;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\OpConceptos;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\RegistroControl;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TabCertificacion extends Component
{
    public $tab = 'identificacion-tab';
    public $opdetsolservicio;
    public $certificacionservicio;
    // Variables Primer Tab
    public $ano_pro;
    public $xnro_sol;
    public $fec_emi;
    public $provision;
    public $sta_sol;
    //---------------------
    //---------------------
    public $rif_prov;
    public $cod_ger;
    //---------------------
    //---------------------
    public $pto_cta;
    public $fec_pto;
     //---------------------
    //---------------------
    public $por_anticipo;
    public $mto_ant;
   //---------------------
   //---------------------
    public $grupo;
    public $num_contrato;
    public $tip_pag;
    public $factura;
   //---------------------
   //---------------------
   public $tip_contrat;
   public $lugar_serv;
   public $fec_serv;
    // Variables Segundo Tab
    public $motivo;
    public $observaciones;
    //---------------------
   //---------------------
    public $codigo;
    public $des_con;
    public $por_iva_con;
    public $cantidad;
    public $cos_uni;
    public $cos_excenta;
    public $base_excenta;
    public $cos_tot;
    public $base_imponible;
    public $base_exenta;
    public $monto_neto;
    public $monto_iva;
    public $monto_total;
    public $por_iva;
    public $deposito_garantia;
   //---------------------
   //---------------------
   public $tiempo_entrega;
   public $certificados;
   public $lugar_entrega;
   public $forma_pago;
   public $flete;
    //---------------------
   //---------------------
    public $onfocus;
    public $detallegasto= [];
    public $accion;
    protected $listeners =['changeSelect'];
    public $EstructuraPresupuestaria ;

    public function changeSelect($valor,$id)
    {   ($id == 'rif_prov') ?  $this->rif_prov= $valor : $this->cod_ger= $valor;    }

    public function mount()
    {
        if( $this->certificacionservicio->id){
            //----------------------------------------------------
            //------------    Opcion Modificar---------------------
            //----------------------------------------------------
            //Modificar la cabecera
            $valor = json_decode($this->certificacionservicio);
            foreach($valor as $key => $value) {
                $this->$key = $value;
            }
            // $this->fec_emi = Carbon::createFromFormat('Y-m-d', $or->fec_emi)->format('Y-m-d');
            $this->fec_emi = $this->certificacionservicio->fec_emi->format('Y-m-d');
            $this->fec_serv = $this->certificacionservicio->fec_serv->format('Y-m-d');
            if (!is_null($this->certificacionservicio->fec_pto)){
                $this->fec_pto = $this->certificacionservicio->fec_pto->format('Y-m-d');
            }
          //Modificar el detalle
           foreach($this->certificacionservicio->opdetsolservicio  as $index => $detalle) {
                $this->codigo       = $detalle['cod_prod'];
                $this->des_con      = $detalle->opconceptos->des_con ;
                $this->por_iva_con  = $detalle['por_iva'];
                $this->cantidad     = $detalle['cantidad'];
                $this->cos_uni      = $detalle['cos_uni'];
                $this->cos_excenta  = $detalle['base_excenta'];
                $this->cos_tot      = $detalle['cos_tot'];
            }

          }else{
            $this->opdetsolservicio = new OpDetsolservicio();
            $this->certificacionservicio    = $this->certificacionservicio;
            $this->ano_pro=2021;
            $this->fec_emi= now()->format('Y-m-d');
            $this->provision=0;
            $this->sta_sol=0;
            $this->grupo='PD';
            $this->tip_pag='T';
            $this->factura='S';
            $this->tip_contrat='N';
            $this->fec_serv= now()->format('Y-m-d');
            $this->estructuras = [];
            $this->limpiar();
        }
        $valor = json_encode(session()->getOldInput());
        $valor = json_decode($valor);
        if ($valor) {
            foreach($valor as $key => $value) {
                $this->$key = $value;
            }
           foreach(json_decode($this->listadoDetalle) as $estructura){
                $this->detallegasto[] = [
                    'gasto'         => $estructura->gasto,
                    'tip_cod'       => $estructura->tip_cod,
                    'cod_pryacc'    => $estructura->cod_pryacc,
                    'cod_obj'       => $estructura->cod_obj,
                    'gerencia'      => $estructura->gerencia,
                    'unidad'        => $estructura->unidad,
                    'cod_par'       => $estructura->cod_par,
                    'cod_gen'       => $estructura->cod_gen,
                    'cod_esp'       => $estructura->cod_esp,
                    'cod_sub'       => $estructura->cod_sub,
                    'descrip'       => $estructura->descrip,
                    'mto_tra'       => $estructura->mto_tra,
                   'cod_cta'       => $estructura->cod_cta,
                ];
            }
        }
 }
 public function cargar_emit()
  {
    $this->emit('alert', ['det' => $this->detallegasto]);

  }
public function limpiar()
  {
    $this->reset(['codigo','des_con','por_iva_con','cantidad','cos_uni','cos_excenta',
                   'base_exenta','base_imponible','monto_neto','cos_tot',
                   'monto_iva','monto_iva','monto_total','por_iva','detallegasto']);

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
    ->pluck('ano_pro','ano_pro')->keys();
}
public function getGerenciaProperty()
{
    return Gerencia::query()
    ->where('status', '1')
    ->orderBy('des_ger')
    ->pluck('des_ger','cod_ger');

}
public function calculaCostoTotal ($valor){
    if (!empty($this->codigo)){
        if (!empty($this->cod_ger)) {
            // LLenar el campo Descripción validando que el concepto exista
            $this->des_con=OpConceptos::query()
            ->where('sta_reg', '1')
            ->where('cod_con',$this->codigo)
            ->first()->des_con?? '';
            // Validr que el Servicio exista
            if (!empty($this->des_con)){
                //Buscar Estructura Presupuestaria
                $EstructuraPresupuestaria=OpConceptosDet::select(DB::raw("'1' AS gasto"),
                                                                    'ppg.cod_par','ppg.cod_gen', 'ppg.cod_esp', 'ppg.cod_sub',
                                                                    'ppg.des_con', 'oc.des_con',
                                                                    'pml.cod_com', 'ppg.cta_gasto',
                                                                    DB::raw("CASE WHEN op_conceptos_det.cod_cta IN ('07.01.02.01') THEN 2 ELSE CAST(substr(g.centro_costo, 1, 2) AS INTEGER) END AS tip_cod"),
                                                                    DB::raw("CASE WHEN op_conceptos_det.cod_cta IN ('07.01.02.01') THEN 1 ELSE CAST(substr(g.centro_costo, 4, 2) AS INTEGER) END AS cod_pryacc"),
                                                                    DB::raw("CASE WHEN op_conceptos_det.cod_cta IN ('07.01.02.01') THEN 0 ELSE CAST(substr(g.centro_costo, 7, 2) AS INTEGER) END AS cod_obj"),
                                                                    DB::raw("CASE WHEN op_conceptos_det.cod_cta IN ('07.01.02.01') THEN 1 ELSE CAST(substr(g.centro_costo, 10, 2) AS INTEGER) END AS gerencia"),
                                                                    DB::raw("CASE WHEN op_conceptos_det.cod_cta IN ('07.01.02.01') THEN 0 ELSE CAST(substr(g.centro_costo, 13, 2) AS INTEGER) END AS unidad"),
                                                                    )
                                                        ->join('op_conceptos AS oc', 'oc.cod_con', '=', 'op_conceptos_det.cod_con')
                                                        ->join('gerencias AS g', 'g.cod_ger', '=',  DB::raw($this->cod_ger))
                                                        ->join('pre_partidasgastos AS ppg',
                                                            function($join) {
                                                                $join->on('ppg.cod_par', '=', 'op_conceptos_det.cod_par')
                                                                ->on('ppg.cod_gen', '=', 'op_conceptos_det.cod_gen')
                                                                ->on('ppg.cod_esp', '=', 'op_conceptos_det.cod_esp')
                                                                ->on('ppg.cod_sub', '=', 'op_conceptos_det.cod_sub');
                                                            })
                                                            ->join('pre_maestroley AS pml', function($join){
                                                                $join->on( DB::raw("(CASE WHEN op_conceptos_det.cod_cta IN ('07.01.02.01') THEN '02.01.00.01.00.'||op_conceptos_det.cod_cta  ELSE  g.centro_costo||'.'||op_conceptos_det.cod_cta END) "), '=', 'pml.cod_com')
                                                                    ->on('pml.ano_pro', '=', DB::raw($this->ano_pro));
                                                            })
                                                            ->where('op_conceptos_det.cod_con','=',$this->codigo)
                                                            ->where('oc.sta_reg','=','1')
                                                            ->where('g.cod_ger','=',$this->cod_ger)
                                                            ->get();
                $cos_uni = floatval(\Str::replace(',', '.', \Str::replace('.','', $this->cos_uni)));
                $cantidad =floatval( \Str::replace(',', '.', \Str::replace('.','', $this->cantidad)));
                $cos_excenta = floatval(\Str::replace(',', '.', \Str::replace('.','', $this->cos_excenta)));
                $por_iva_con = floatval(\Str::replace(',', '.', \Str::replace('.','', $this->por_iva_con)));
                // $this->cos_tot=($this->c*$this->cantidad)+$this->cos_excenta;
                $cos_tot=($cos_uni*$cantidad )+$cos_excenta;
                if(!$EstructuraPresupuestaria->isEmpty()){
                    $this->reset(['detallegasto']);
                    foreach($EstructuraPresupuestaria as $estructura){

                        $this->detallegasto[] = [
                            'gasto'         => $estructura->gasto,
                            'tip_cod'       => $estructura->tip_cod,
                            'cod_pryacc'    => $estructura->cod_pryacc,
                            'cod_obj'       => $estructura->cod_obj,
                            'gerencia'      => $estructura->gerencia,
                            'unidad'        => $estructura->unidad,
                            'cod_par'       => $estructura->cod_par,
                            'cod_gen'       => $estructura->cod_gen,
                            'cod_esp'       => $estructura->cod_esp,
                            'cod_sub'       => $estructura->cod_sub,
                            'descrip'       => $estructura->des_con,
                            'mto_tra'       => number_format((($cos_uni*$cantidad+$cos_excenta)), 2, ',', '.'),
                            'cod_cta'       => $estructura->cta_gasto,
                        ];
                    }
                    //Recorrer la Grilla
                    for ($i = 1; $i < 5; $i++){
                        switch($i)
                        {
                            case 1:{    $this->por_iva=$this->por_iva_con;
                                        if ($this->por_iva_con== '' || $this->factura== 'N' || $this->por_iva == 0)
                                        {   $this->reset(['por_iva_con','cos_uni','base_imponible','por_iva' ]);
                                        }
                                        break;
                                        }
                            case 2:{    if ($this->cantidad == '' || $this->cantidad == 0)
                                        {
                                            $this->cantidad=1;
                                        }
                                        break;
                                    }
                            case 3:{    $this->base_imponible=$this->cos_uni;
                                        if ($this->cos_uni == '' || $this->cos_uni == 0)
                                        {
                                            if( $this->factura == 'N'){
                                                $this->reset(['cos_uni','base_imponible']);
                                            }else{
                                                if ($this->por_iva_con != 0  && ($this->cos_uni == '' || $this->cos_uni== 0 ))
                                                {   $this->reset(['cos_uni','base_imponible']);
                                                 /*   $this->emit('swal:alert', [
                                                        'tipo'    => 'warning',
                                                        'titulo'  => 'Error',
                                                        'mensaje' => 'Existe porcentaje de IVA, debe indicar Base Imponible o quitar el porcentaje de IVA existente.'
                                                    ]);*/
                                                }
                                            }
                                        }
                                        break;
                                    }
                            case 4:{    $this->base_exenta=$this->cos_excenta;
                                        break;
                                    }

                        }

                    }
                    switch($valor)
                    {
                        case 'codigo':       {$this->onfocus='#por_iva_con'; break;}
                        case 'por_iva_con':  {$this->onfocus='#cantidad';break;}
                        case 'cantidad':     {$this->onfocus='#cos_uni';break;}
                        case 'cos_uni':      {$this->onfocus='#cos_excenta';break;}
                        case 'cos_excenta':  {$this->onfocus='#monto_iva';break;}
                    }
                    $this->emit('alert', ['tab' => '#detalle-tab','onfocus' => $this->onfocus]);
                    $monto_neto=$cos_tot;
                    $monto_iva=($cos_uni*($por_iva_con/100));
                    $monto_total=$cos_tot+ $monto_iva;
                    $this->monto_neto=number_format($monto_neto, 2, ',', '.');
                    $this->monto_iva=number_format($monto_iva, 2, ',', '.');
                    $this->monto_total=number_format($monto_total, 2, ',', '.');
                    $this->cos_tot=number_format(($cos_uni*$cantidad )+$cos_excenta, 2, ',', '.');
                    //Validar la Partida de IVA
                    if ($this->factura=='S' && $this->cos_uni!='' && $this->cos_uni!=0){
                        $row_iva=RegistroControl::select(DB::raw("'0' AS gasto"),
                        'tip_codi','cod_pryacci', 'cod_obji', 'gerenciai',
                        'unidadi', 'cod_pari','cod_geni', 'cod_espi','cod_subi','des_con',
                        )
                        ->join('pre_partidasgastos AS ppg',
                        function($join) {
                            $join->on('ppg.cod_par', '=', 'registrocontrol.cod_pari')
                            ->on('ppg.cod_gen', '=', 'registrocontrol.cod_geni')
                            ->on('ppg.cod_esp', '=', 'registrocontrol.cod_espi')
                            ->on('ppg.cod_sub', '=', 'registrocontrol.cod_subi');
                        })
                        ->where('registrocontrol.ano_pro','=',DB::raw($this->ano_pro))
                        ->get();
                        $this->detallegasto[] = [
                            'gasto'         => $row_iva[0]->gasto,
                            'tip_cod'       => $row_iva[0]->tip_codi,
                            'cod_pryacc'    => $row_iva[0]->cod_pryacci,
                            'cod_obj'       => $row_iva[0]->cod_obji,
                            'gerencia'      => $row_iva[0]->gerenciai,
                            'unidad'        => $row_iva[0]->unidadi,
                            'cod_par'       => $row_iva[0]->cod_pari,
                            'cod_gen'       => $row_iva[0]->cod_geni,
                            'cod_esp'       => $row_iva[0]->cod_espi,
                            'cod_sub'       => $row_iva[0]->cod_subi,
                            'descrip'       => $row_iva[0]->des_con,
                            'mto_tra'       => $this->monto_iva,
                            'cod_cta'       => '',
                        ];
                    }
                    $this->emit('alert', ['tab' => '#detalle-tab','det' => $this->detallegasto]);
                }else{
                    $this->reset(['des_con','por_iva_con','cantidad','cos_uni','base_imponible','cos_tot','cos_excenta','detallegasto',
                                    'base_imponible','base_exenta','monto_neto','monto_iva','monto_total','por_iva']);
                    $this->emit('swal:alert', [
                        'tipo'    => 'warning',
                        'titulo'  => 'Error',
                        'mensaje' => 'Alguna estructura presupuestaria no existe en el Maestro de Ley'
                    ]);
                    $this->emit('alert', ['tab' => '#detalle-tab','det' => $this->detallegasto]);
                }
            }else{
                $this->reset(['des_con','por_iva_con','cantidad','cos_uni','base_imponible','cos_tot','cos_excenta','detallegasto',
                                'base_imponible','base_exenta','monto_neto','monto_iva','monto_total','por_iva']);
            $this->emit('swal:alert', [
                'tipo'    => 'warning',
                'titulo'  => 'Error',
                'mensaje' => 'Servcio no existe.Favor verifique'
            ]);
            $this->emit('alert', ['tab' => '#detalle-tab']);
            }
        }else{
            $this->reset(['des_con','por_iva_con','cantidad','cos_uni','base_imponible','cos_tot','cos_excenta','detallegasto',
                            'base_imponible','base_exenta','monto_neto','monto_iva','monto_total','por_iva']);
            $this->emit('swal:alert', [
                'tipo'    => 'warning',
                'titulo'  => 'Error',
                'mensaje' => 'Favor seleccione una gerencia.'
            ]);
            $this->emit('alert', ['tab' => '#detalle-tab']);
        }
    }else{
        $this->reset(['des_con','por_iva_con','cantidad','cos_uni','base_imponible','cos_tot','cos_excenta','detallegasto',
                        'base_imponible','base_exenta','monto_neto','monto_iva','monto_total','por_iva']);
        $this->emit('alert', ['tab' => '#detalle-tab']);
    }
}
public function render()
{
    return view('livewire.administrativo.meru-administrativo.otros-pagos.proceso.tab-certificacion');
}
}
