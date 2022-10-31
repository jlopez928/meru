<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Modificaciones\Movimientos;

use App\Enums\Administrativo\Meru_Administrativo\Modificaciones\EstadoSolicitudTraspaso;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\MaestroLey;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\PartidaPresupuestaria;
use App\Models\Administrativo\Meru_Administrativo\Modificaciones\SolicitudTraspaso;
use Livewire\Component;

class InsubsistenciaForm extends Component
{
    public $insubsistencia;
    public $anoPro;
    public $xnroMod;
    public $nroMod;
    public $numDoc;
    public $fecTra;
    public $fecSta;
    public $concepto;
    public $justificacion;
    public $estructurasCedentes;
    public $cedente;
    public $montoCed;
    public $totCed;

    public function mount($insubsistencia)
    {
        $this->traspaso = $insubsistencia;
        $this->cedente  = [
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
        $this->montoCed = null;

        if ($this->traspaso->id) {
            $this->anoPro  = $insubsistencia->ano_pro;
            $this->xnroMod = $insubsistencia->xnro_mod;
            $this->nroMod  = $insubsistencia->nro_mod;
            $this->numDoc  = $insubsistencia->num_doc;
            $this->fecTra  = \Carbon\Carbon::parse($insubsistencia->fec_tra)->format('d/m/Y');
            $this->fecSta  = \Carbon\Carbon::parse($insubsistencia->fec_sta)->format('d/m/Y');
            $this->concepto      = $insubsistencia->concepto;
            $this->justificacion = $insubsistencia->justificacion;
            $this->estructurasCedentes = $insubsistencia->estructurasCedentes(); // Revisar modelo
            $this->totCed = number_format($insubsistencia->totalCedentes(), 2, ',', '.');
        } else {
            $this->anoPro = session('ano_pro');
            $this->xnroMod;
            $this->nroMod;
            $this->numDoc;
            $this->fecTra = now()->format('d/m/Y');
            $this->fecSta = now()->format('d/m/Y');
            $this->concepto;
            $this->justificacion;
            $this->estructurasCedentes = [];
            $this->totCed = '0,00';
        }

        $valor = json_encode(session()->getOldInput());
        $valor = json_decode($valor);

        if ($valor) {
            foreach($valor as $key => $value) {
                $this->$key = $value;
            }

            $this->estructurasCedentes = json_decode($this->estructurasCedentes, true);
        }
    }

    public function inicializar()
    {
        $this->emit('estructura:act', [
            'estructuras' => $this->estructurasCedentes
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
        $desTipo    = 'Cedente';
        $estructura = $this->cedente;
        $monto      = \Str::replace(',', '.', \Str::replace('.','',  $this->montoCed));
        $totCed     = \Str::replace(',', '.', \Str::replace('.','', $this->totCed));
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

            // Validar que exista suficiente disponible en la estructura cedente
            } else if ($maestroLey->mto_dis < $monto) {
                $this->_alert('No existe suficiente disponible en la estructura ' . $desTipo . 
                            '<br><b>' . $codCom . '</b><br><b>Monto Traspaso:</b> ' . $monto . 
                            '<br><b>Disponible:</b> ' . $maestroLey->mto_dis . 
                            '<br>Por favor verifique.');

            // Válido
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

                if (array_key_exists($codCom, $this->estructurasCedentes) ) {
                    $this->totCed -= $this->estructurasCedentes[$codCom]['mto_tra'];
                }

                $this->estructurasCedentes[$codCom] = $arrayEst;
                $this->cedente = array_map(fn($val) => '', $this->cedente);
                $this->montoCed = '';
                $totCed += (float)$monto;
                $this->totCed = number_format($totCed, 2, ',', '.');

                $this->emit('estructura:act', [
                    'estructuras' => $this->estructurasCedentes
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
        $total = \Str::replace(',', '.', \Str::replace('.', '',$this->totCed));
        $total -= $this->estructurasCedentes[$key]['mto_tra'];
        $this->totCed = number_format($total, 2, ',', '.');
        unset($this->estructurasCedentes[$key]);

        $this->emit('estructura:act', [
            'estructuras' => $this->estructurasCedentes
        ]);
    }

    public function eliminarTodasEstructuras()
    {
        $this->totCed = 0;
        $this->estructurasCedentes = [];

        $this->emit('estructura:act', [
            'estructuras' => $this->estructurasCedentes
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
        return view('livewire.administrativo.meru-administrativo.modificaciones.movimientos.insubsistencia-form');
    }
}