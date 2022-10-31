<?php
/*
namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Modificaciones\Movimientos;

use Livewire\Component;

class CreditoAdicionalForm extends Component
{
    public function render()
    {
        return view('livewire.administrativo.meru-administrativo.modificaciones.movimientos.credito-adicional-form');
    }
}
*/

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Modificaciones\Movimientos;

use App\Enums\Administrativo\Meru_Administrativo\Modificaciones\EstadoSolicitudTraspaso;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\MaestroLey;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\PartidaPresupuestaria;
use App\Models\Administrativo\Meru_Administrativo\Modificaciones\SolicitudTraspaso;
use Livewire\Component;

class CreditoAdicionalForm extends Component
{
    public $creditoAdicional;
    public $anoPro;
    public $xnroMod;
    public $nroMod;
    public $numDoc;
    public $fecTra;
    public $fecSta;
    public $concepto;
    public $justificacion;
    public $estructurasReceptoras;
    public $receptora;
    public $montoRec;
    public $totRec;

    public function mount($creditoAdicional)
    {
        $this->creditoAdicional = $creditoAdicional;
        $this->receptora = [
            'tip_cod'    => '',
            'cod_pryacc' => '',
            'cod_obj'    => '',
            'gerencia'   => '',
            'unidad'     => '',
            'cod_par'    => '',
            'cod_gen'    => '',
            'cod_esp'    => '',
            'cod_sub'    => ''
        ];
        $this->montoRec = null;

        if ($this->creditoAdicional->id) {
            $this->anoPro  = $creditoAdicional->ano_pro;
            $this->xnroMod = $creditoAdicional->xnro_mod;
            $this->nroMod  = $creditoAdicional->nro_mod;
            $this->numDoc  = $creditoAdicional->num_doc;
            $this->fecTra  = \Carbon\Carbon::parse($creditoAdicional->fec_tra)->format('d/m/Y');
            $this->fecSta  = \Carbon\Carbon::parse($creditoAdicional->fec_sta)->format('d/m/Y');
            $this->concepto      = $creditoAdicional->concepto;
            $this->justificacion = $creditoAdicional->justificacion;
            $this->estructurasReceptoras = $creditoAdicional->estructurasReceptoras(); // Revisar modelo
            $this->totRec = number_format($creditoAdicional->totalReceptoras(), 2, ',', '.');
        } else {
            $this->anoPro = session('ano_pro');
            $this->xnroMod;
            $this->nroMod;
            $this->numDoc;
            $this->fecTra = now()->format('d/m/Y');
            $this->fecSta = now()->format('d/m/Y');
            $this->concepto;
            $this->justificacion;
            $this->estructurasReceptoras = [];
            $this->totRec = '0,00';
        }

        $valor = json_encode(session()->getOldInput());
        $valor = json_decode($valor);

        if ($valor) {
            foreach($valor as $key => $value) {
                $this->$key = $value;
            }

            $this->estructurasReceptoras = json_decode($this->estructurasReceptoras, true);
        }
    }

    public function inicializar()
    {
        $this->emit('estructura:act', [
            'estructuras' => $this->estructurasReceptoras
        ]);
    }

    /**
     * Agregar estructura al arreglo correspondiente
     * 
     * @param string $tipo Tipo de estructura a agregar c:cedente, r:receptora
     * 
     * @return [type]
     */
    public function agregarEstructura()
    {
        $desTipo    = 'Receptora';
        $estructura = $this->receptora;
        $monto  = \Str::replace(',', '.', \Str::replace('.','',  $this->montoRec));
        $totRec = \Str::replace(',', '.', \Str::replace('.','', $this->totRec));

        $arrFilter  = array_filter($estructura, fn($val) => is_numeric($val));

        // Validar que se haya registrado le estructura completa
        if (count($arrFilter) != count($estructura)) {
            $this->_alert('Debe ingresar la estructura ' . $desTipo . ' completa.<br>Por favor verifique.');

        // Validar el monto de la estructura
        } else if (!is_numeric($monto)) {
            $this->_alert('Monto de la estructura ' . $desTipo . ' errado.<br>Por favor verifique.');

        // Válido
        } else {
            $arrCodCom = array_map(fn($val) => \Str::padLeft($val, 2, '0'), $estructura);
            $codCom    = \Arr::join($arrCodCom, '.');

            $maestroLey = MaestroLey::where('ano_pro', $this->anoPro)
                            ->where('cod_com', $codCom)
                            ->first();

            // Validar que la estructura exista en pre_maestroley para el año
            if (is_null($maestroLey)) {
                $this->_alert('La estructura presupuestaria ' . $desTipo . ' no existe.<br>Por favor verifique.');
            } else {
                $codPar = '4.' . \Arr::join(array_slice($arrCodCom, 5), '.');
                $desPartida = PartidaPresupuestaria::where('cod_cta', $codPar)->pluck('des_con')->first();

                $arrayEst = \Arr::collapse([
                    ['cod_com' => $codCom],
                    $estructura,
                    [
                        'descrip' => $desPartida,
                        'mto_dis' => $maestroLey->mto_dis,
                        'mto_tra' => $monto
                    ]
                ]);

                if (array_key_exists($codCom, $this->estructurasReceptoras) ) {
                    $this->totRec -= $this->estructurasReceptoras[$codCom]['mto_tra'];
                }

                $this->estructurasReceptoras[$codCom] = $arrayEst;
                $this->receptora = array_map(fn($val) => '', $this->receptora);
                $this->montoRec = '';
                $totRec += (float)$monto;
                $this->totRec = number_format($totRec, 2, ',', '.');

                $this->emit('estructura:act', [
                    'estructuras' => $this->estructurasReceptoras
                ]);
            }
        }
    }

    /**
     * Eliminar estructura del arreglo correspondiente
     * 
     * @param string $tipo Tipo de estructura a eliminar c:cedente, r:receptora
     * @param string $key
     * 
     * @return [type]
     */
    public function eliminarEstructura(string $key)
    {
        $total = \Str::replace(',', '.', \Str::replace('.', '', $this->totRec));
        $total -= $this->estructurasReceptoras[$key]['mto_tra'];
        $this->totRec = number_format($total, 2, ',', '.');
        unset($this->estructurasReceptoras[$key]);

        $this->emit('estructura:act', [
            'estructuras' => $this->estructurasReceptoras
        ]);
    }

    public function eliminarTodasEstructuras()
    {
        $this->totRec = 0;
        $this->estructurasReceptoras = [];

        $this->emit('estructura:act', [
            'estructuras' => $this->estructurasReceptoras
        ]);
    }

    /**
     * Disparar emit de Sweet Alert
     * 
     * @param string $msj
     * @param string $tipo
     * @param string $titulo
     * 
     * @return [type]
     */
    private function _alert(string $msj, string $tipo = 'w', string $titulo = 'Error')
    {
        switch($tipo) {
            case 's':
            case 'S':
                $t = 'success';
                break;
            case 'e':
            case 'E':
                $t = 'error';
                break;
            case 'i':
            case 'I':
                $t = 'info';
                break;
            default:
                $t = 'warning';
                break;
        }

        $this->emit('swal:alert', [
            'tipo'    => $t,
            'titulo'  => $titulo,
            'mensaje' => $msj
        ]);
    }

    public function render()
    {
        return view('livewire.administrativo.meru-administrativo.modificaciones.movimientos.credito-adicional-form');
    }
}