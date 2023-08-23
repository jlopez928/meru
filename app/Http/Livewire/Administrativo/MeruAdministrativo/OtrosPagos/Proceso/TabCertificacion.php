<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\OtrosPagos\Proceso;
use Illuminate\Support\Arr;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\OpDetsolservicio;
use App\Models\Administrativo\Meru_Administrativo\Tesoreria\Beneficiario;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Gerencia;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\OpConceptosDet;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\OpConceptos;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\RegistroControl;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Traits\Presupuesto;
class TabCertificacion extends Component
{   use Presupuesto;
    public $tab = 'identificacion-tab';
    public $opdetsolservicio;
    public $certificacionservicio;
    public $selectedcheck = [];
    public $showDropdown = [];
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
    public $comprobante= [];
    protected $listeners =['changeSelect','registrar','inactivar'];
    public $EstructuraPresupuestaria ;
    public $accion;
    public $actprovision;

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
            if ( $this->provision=='N'){
                $this->actprovision='nuevo';
            }else{
                $this->actprovision='provision';
            }
            $this->fec_emi = $this->certificacionservicio->fec_emi->format('Y-m-d');
            $this->fec_serv = $this->certificacionservicio->fec_serv->format('Y-m-d');
            if (!is_null($this->certificacionservicio->fec_pto)){
                $this->fec_pto = $this->certificacionservicio->fec_pto->format('Y-m-d');
            }
            $this->por_anticipo    = number_format($this->por_anticipo, 2, ',', '.');
            $this->mto_ant         = number_format($this->mto_ant, 2, ',', '.');
            $this->base_imponible  = number_format($this->base_imponible, 2, ',', '.');
            $this->base_exenta     = number_format($this->base_exenta, 2, ',', '.');
            $this->monto_neto      = number_format($this->monto_neto, 2, ',', '.');
            $this->monto_iva       = number_format($this->monto_iva, 2, ',', '.');
            $this->monto_total       = number_format($this->monto_total, 2, ',', '.');
            $this->por_iva         = number_format($this->por_iva, 2, ',', '.');
            //Modificar el detalle
           foreach($this->certificacionservicio->opdetsolservicio  as $index => $detalle) {
                $this->codigo       = $detalle['cod_prod'];
                $this->des_con      = $detalle->opconceptos->des_con;
                $this->por_iva_con  = number_format($detalle['por_iva'], 2, ',', '.');
                $this->cantidad     = number_format($detalle['cantidad'], 2, ',', '.');
                $this->cos_uni      = number_format($detalle['cos_uni'], 2, ',', '.');
                $this->cos_excenta  = number_format($detalle['base_excenta'], 2, ',', '.');
                $this->cos_tot      = number_format($detalle['cos_tot'], 2, ',', '.');
            }
            foreach($this->certificacionservicio->opdetgastossolservicio as $index => $estructura){
                $this->detallegasto[] = [
                    'gasto'         => $estructura['gasto'],
                    'tip_cod'       => $estructura['tip_cod'],
                    'cod_pryacc'    => $estructura['cod_pryacc'],
                    'cod_obj'       => $estructura['cod_obj'],
                    'gerencia'      => $estructura['gerencia'],
                    'unidad'        => $estructura['unidad'],
                    'cod_par'       => $estructura['cod_par'],
                    'cod_gen'       => $estructura['cod_gen'],
                    'cod_esp'       => $estructura['cod_esp'],
                    'cod_sub'       => $estructura['cod_sub'],
                    'descrip'       => $estructura->partidapresupuestaria->des_con,
                    'mto_tra'       =>  number_format( $estructura['mto_tra'], 2, ',', '.'),
                    'partida'       => $this->varmarpartida($estructura->cod_par,$estructura->cod_gen,$estructura->cod_esp,$estructura->cod_sub),
                    'cod_cta'        => $estructura['cod_cta'],
                ];
            }
          }else{
            $this->opdetsolservicio = new OpDetsolservicio();
            $this->certificacionservicio    = $this->certificacionservicio;
            $this->fec_emi= now()->format('Y-m-d');
            $this->ano_pro=RegistroControl::periodoActual();
            $this->provision=0;
            $this->sta_sol=0;
            $this->grupo='PD';
            $this->tip_pag='T';
            $this->factura='S';
            $this->tip_contrat='N';
            $this->fec_serv= now()->format('Y-m-d');
            $this->estructuras = [];
            $this->por_anticipo    = number_format($this->por_anticipo, 2, ',', '.');
            $this->mto_ant         = number_format($this->mto_ant, 2, ',', '.');
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
                    'mto_tra'       =>$estructura->mto_tra,
                    'partida'       => $this->varmarpartida($estructura->cod_par,$estructura->cod_gen,$estructura->cod_esp,$estructura->cod_sub),
                    'cod_cta'        => $estructura->cod_cta,
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
public function calcularAnticipo($tabfocus){
    $this->mto_ant='';
    if (!empty($this->por_anticipo)){
        if (!empty($this->monto_neto)){
            if (!empty($this->factura) &&  $this->factura!='N'){
                $monto_neto=floatval(\Str::replace(',', '.', \Str::replace('.','', ($this->monto_neto))));
                $por_anticipo=floatval(\Str::replace(',', '.', \Str::replace('.','', ($this->por_anticipo))));
                $mto_ant=$monto_neto * ($por_anticipo / 100);
                $this->mto_ant=number_format($mto_ant, 2, ',', '.');
            }else{
                $this->por_anticipo=0;
            }
        }else{
            $this->por_anticipo=0;
        }
    }
    $this->onfocus='#tip_pag';
    $this->emit('alert', ['tab' => $tabfocus,'onfocus' => $this->onfocus]);
}
public function calculaCostoTotal ($valor){
    $this->selectedcheck=[];
    $this->showDropdown=[];
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
                                                                    'ppg.des_con',
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
                            'partida'       => $this->varmarpartida($estructura->cod_par,$estructura->cod_gen,$estructura->cod_esp,$estructura->cod_sub),
                            'cod_cta'       => $estructura->cta_gasto,
                        ];
                        $this->selectedcheck[]=$estructura->gasto ?: false;
                        $this->showDropdown[] =false;
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
                            'partida'       => $this->varmarpartida($row_iva[0]->cod_pari,$row_iva[0]->cod_geni,$row_iva[0]->cod_espi,$row_iva[0]->cod_subi),
                            'cod_cta'       => '',
                        ];
                        $this->selectedcheck[]=$row_iva[0]->gasto?:false;
                        $this->showDropdown[] =false;
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
                    'mensaje' => 'Servicio no existe.Favor verifique'
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
            $this->onfocus='#cod_ger';
            $this->emit('alert', ['tab' => '#identificacion-tab','onfocus' => $this->onfocus]);
        }
    }else{
        $this->reset(['des_con','por_iva_con','cantidad','cos_uni','base_imponible','cos_tot','cos_excenta','detallegasto',
                        'base_imponible','base_exenta','monto_neto','monto_iva','monto_total','por_iva']);
        $this->emit('alert', ['tab' => '#detalle-tab']);
    }

    $this->calcularAnticipo('#detalle-tab');
}
//-------------------------------------------------------------------------
//-------------------------------------------------------------------------
public function validar_monto_Iva(){

    $variabilidad = 0.10;
    $iva = 0;
    if ($this->por_iva_con== '' || $this->por_iva_con== ''){
        $this->emit('swal:alert', [
            'tipo'    => 'warning',
            'titulo'  => 'Error',
            'mensaje' => 'No se puede modificar el Monto si no ha especificado un Porcentaje de Iva.'
        ]);
    }else{
        $cos_uni = floatval(\Str::replace(',', '.', \Str::replace('.','', $this->cos_uni)));
        $cantidad =floatval( \Str::replace(',', '.', \Str::replace('.','', $this->cantidad)));
        $cos_excenta = floatval(\Str::replace(',', '.', \Str::replace('.','', $this->cos_excenta)));
        $por_iva_con = floatval(\Str::replace(',', '.', \Str::replace('.','', $this->por_iva_con)));
        $cos_tot=($cos_uni*$cantidad )+$cos_excenta;
        $iva=($cos_uni*($por_iva_con/100));
        $valor=floatval(\Str::replace(',', '.', \Str::replace('.','', $this->monto_iva)));
        $minimo = (($iva - $variabilidad) * 100) / 100;
        $maximo = (($iva + $variabilidad) * 100) / 100;
        if (($valor < $minimo) || ($valor > $maximo)) {
            $this->emit('swal:alert', [
                'tipo'    => 'warning',
                'titulo'  => 'Error',
                'mensaje' => 'El Monto de Iva esta Fuera del Rango de Tolerancia. ('.$minimo.'<= Monto Iva <='.$maximo.')'
            ]);
            $this->monto_iva=number_format($iva, 2, ',', '.');
        } else {
            $monto_total=$cos_tot+ $valor;
            $this->monto_iva=number_format($valor, 2, ',', '.');
            $this->monto_total=number_format($monto_total, 2, ',', '.');
            $this->emit('alert', ['tab' => '#detalle-tab']);
            //Validar si existe la partida de Iva en el arreglo
            $pos = array_search('03.18.01.00', array_column($this->detallegasto, 'partida'));
            if($pos>=0){
                // Actualiza el monto de Iva en e arreglo del gasto
                Arr::set($this->detallegasto, $pos.'.mto_tra', $valor);
            };
            $this->emit('alert', ['tab' => '#detalle-tab','det' => $this->detallegasto]);
        }
      }
      $this->emit('alert', ['tab' => '#detalle-tab']);

}

public function registrar($pos)
{
    Arr::set($this->detallegasto, $pos.'.gasto', 1);
    $this->emit('alert', ['tab' => '#detalle-tab','det' => $this->detallegasto]);
}
public function inactivar($pos)
{
    $this->selectedcheck[$pos]=0;
    $this->emit('alert', ['tab' => '#detalle-tab']);
}
public function habilitar_checkbox($posicion){
    if($posicion!='N'){
        $valor=$this->selectedcheck[$posicion]==false?0:1;
        if ($valor==1){
            if (($this->detallegasto[$posicion]['cod_par'] == 4) ||
                ($this->detallegasto[$posicion]['cod_par'] == 3 &&
                 $this->detallegasto[$posicion]['cod_gen'] == 1 &&
                 $this->detallegasto[$posicion]['cod_esp'] == 1 &&
                 $this->detallegasto[$posicion]['cod_sub'] == 0)) {
                 $bandera = 3;
                 $this->emit('swal:confirm', [
                        'tipo'      => 'warning',
                        'titulo'    => 'Estructura de Gasto',
                        'mensaje'   => 'Esta Seguro de Asignar una Cuenta de Gasto a la Partida Presupuestaria[' . $this->detallegasto[$posicion]['partida'] . '] ?',
                        'funcion'   => 'registrar',
                        'funcion2'  => 'inactivar',
                        'posicion'  => $posicion
                ]);
            }else {
                if( $this->detallegasto[$posicion]['cod_par'] == 3 &&
                    $this->detallegasto[$posicion]['cod_gen'] == 18 &&
                    $this->detallegasto[$posicion]['cod_esp'] == 1 &&
                    $this->detallegasto[$posicion]['cod_sub'] == 0){
                        $bandera = 0;
                        $this->emit('swal:alert', [
                            'tipo'    => 'warning',
                            'titulo'  => 'Error',
                            'mensaje' => 'La Partida Presupuestaria [' . $this->detallegasto[$posicion]['partida'] . '] no se puede imputar por la Cuenta de Gasto'
                        ]);
                   } else  {
                        $bandera = 1;
                  }
            }
        }else{
            if ($this->detallegasto[$posicion]['cod_par'] != 4) {
                if( $this->detallegasto[$posicion]['cod_par'] == 3 &&
                    $this->detallegasto[$posicion]['cod_gen'] == 1 &&
                    $this->detallegasto[$posicion]['cod_esp'] == 1 &&
                    $this->detallegasto[$posicion]['cod_sub'] == 0){
                        $bandera = 0;
                    } else
                    {
                        if( $this->detallegasto[$posicion]['cod_par'] == 3 &&
                            $this->detallegasto[$posicion]['cod_gen'] == 18 &&
                            $this->detallegasto[$posicion]['cod_esp'] == 1 &&
                            $this->detallegasto[$posicion]['cod_sub'] == 0){
                                $bandera = 0;
                            } else  {
                                $bandera = 1;
                                $this->emit('swal:alert', [
                                    'tipo'    => 'warning',
                                    'titulo'  => 'Error',
                                    'mensaje' => 'La Partida Presupuestaria [' . $this->detallegasto[$posicion]['partida'] . '] no se puede imputar por la Cuenta de Activo'
                                ]);
                        }
                    }
            }else {
                $bandera = 0;
            }
        }
        if ( $bandera !=3){
            $this->selectedcheck[$posicion]=$bandera;
            Arr::set($this->detallegasto, $posicion.'.gasto', $bandera);
            $this->emit('alert', ['tab' => '#detalle-tab','det' => $this->detallegasto]);
        }else{
            $this->emit('alert', ['tab' => '#detalle-tab']);
        }

    }else{
        $this->emit('alert', ['tab' => '#detalle-tab']);
    }
}
//-------------------------------------------------------------------------
//-------------------------------------------------------------------------
public function render()
{
    return view('livewire.administrativo.meru-administrativo.otros-pagos.proceso.tab-certificacion');
}
}
