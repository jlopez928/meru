<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Modificaciones\Movimientos;

use App\Enums\Administrativo\Meru_Administrativo\Modificaciones\EstadoSolicitudTraspaso;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\MaestroLey;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\PartidaPresupuestaria;
use App\Models\Administrativo\Meru_Administrativo\Modificaciones\SolicitudTraspaso;
use Livewire\Component;

class TraspasoForm extends Component
{
    public $traspaso;
    public $anoPro;
    public $xnroMod;
    public $nroMod;
    public $numDoc;
    public $fecTra;
    public $fecSta;
    public $concepto;
    public $justificacion;
    public $estructurasCedentes;
    public $estructurasReceptoras;
    public $cedente;
    public $receptora;
    public $montoCed;
    public $montoRec;
    public $totCed;
    public $totRec;

    public function mount($traspaso)
    {
        $this->traspaso = $traspaso;
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
        $this->montoCed = null;
        $this->montoRec = null;

        if ($this->traspaso->id) {
            $this->anoPro  = $traspaso->ano_pro;
            $this->xnroMod = $traspaso->xnro_mod;
            $this->nroMod  = $traspaso->nro_mod;
            $this->numDoc  = $traspaso->num_doc;
            $this->fecTra  = \Carbon\Carbon::parse($traspaso->fec_tra)->format('d/m/Y');
            $this->fecSta  = \Carbon\Carbon::parse($traspaso->fec_sta)->format('d/m/Y');
            $this->concepto      = $traspaso->concepto;
            $this->justificacion = $traspaso->justificacion;
            $this->estructurasCedentes   = $traspaso->estructurasCedentes(); // Revisar modelo
            $this->estructurasReceptoras = $traspaso->estructurasReceptoras(); // Revisar modelo
            $this->totCed = number_format($traspaso->totalCedentes(), 2, ',', '.');
            $this->totRec = number_format($traspaso->totalReceptoras(), 2, ',', '.');
        } else {
            $this->anoPro = session('ano_pro');
            $this->xnroMod;
            $this->nroMod;
            $this->numDoc;
            $this->fecTra = now()->format('d/m/Y');
            $this->fecSta = now()->format('d/m/Y');
            $this->concepto;
            $this->justificacion;
            $this->estructurasCedentes   = [];
            $this->estructurasReceptoras = [];
            $this->totCed = '0,00';
            $this->totRec = '0,00';
        }

        $valor = json_encode(session()->getOldInput());
        $valor = json_decode($valor);

        if ($valor) {
            foreach($valor as $key => $value) {
                $this->$key = $value;
            }

            $this->estructurasCedentes   = json_decode($this->estructurasCedentes, true);
            $this->estructurasReceptoras = json_decode($this->estructurasReceptoras, true);
        }
    }

    public function inicializar()
    {
        $this->emit('estructura:act', [
            'tipo' => 'c', 
            'estructuras' => $this->estructurasCedentes
        ]);

        $this->emit('estructura:act', [
            'tipo' => 'r', 
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
    private function _agregarEstructura(string $tipo = 'c')
    {
        $desTipo    = $tipo == 'c' ? 'Cedente' : 'Receptora';
        $estructura = $tipo == 'c' ? $this->cedente : $this->receptora;
        // $monto      = $tipo == 'c' ? $this->montoCed : $this->montoRec;
        $monto  = \Str::replace(',', '.', \Str::replace('.','',  $tipo == 'c' ? $this->montoCed : $this->montoRec));
        $totCed = \Str::replace(',', '.', \Str::replace('.','', $this->totCed));
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

            // Validar que exista suficiente disponible en la estructura cedente
            } else if ($tipo == 'c' && $maestroLey->mto_dis < $monto) {
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

                if ($tipo == 'c') {
                    if (array_key_exists($codCom, $this->estructurasCedentes) ) {
                        $this->totCed -= $this->estructurasCedentes[$codCom]['mto_tra'];
                    }

                    $this->estructurasCedentes[$codCom] = $arrayEst;
                    $this->cedente = array_map(fn($val) => '', $this->cedente);
                    $this->montoCed = '';
                    $totCed += (float)$monto;
                    $this->totCed = number_format($totCed, 2, ',', '.');

                } else {
                    if (array_key_exists($codCom, $this->estructurasReceptoras) ) {
                        $this->totRec -= $this->estructurasReceptoras[$codCom]['mto_tra'];
                    }

                    $this->estructurasReceptoras[$codCom] = $arrayEst;
                    $this->receptora = array_map(fn($val) => '', $this->receptora);
                    $this->montoRec = '';
                    $totRec += (float)$monto;
                    $this->totRec = number_format($totRec, 2, ',', '.');
                }

                $this->emit('estructura:act', [
                    'tipo' => $tipo, 
                    'estructuras' => $tipo == 'c' ? $this->estructurasCedentes : $this->estructurasReceptoras
                ]);
            }
        }
    }

    public function agregarCedente()
    {
        $this->_agregarEstructura();
    }

    public function agregarReceptora()
    {
        $this->_agregarEstructura('r');
    }

    /**
     * Eliminar estructura del arreglo correspondiente
     * 
     * @param string $tipo Tipo de estructura a eliminar c:cedente, r:receptora
     * @param string $key
     * 
     * @return [type]
     */
    private function _eliminarEstructura(string $tipo, string $key)
    {
        $total = \Str::replace(',', '.', \Str::replace('.', '', $tipo == 'c' ? $this->totCed : $this->totRec));

        if ($tipo == 'c') {
            $total -= $this->estructurasCedentes[$key]['mto_tra'];
            $this->totCed = number_format($total, 2, ',', '.');
            unset($this->estructurasCedentes[$key]);
        } else {
            $total -= $this->estructurasReceptoras[$key]['mto_tra'];
            $this->totRec = number_format($total, 2, ',', '.');
            unset($this->estructurasReceptoras[$key]);
        }

        $this->emit('estructura:act', [
            'tipo' => $tipo, 
            'estructuras' => $tipo == 'c' ? $this->estructurasCedentes : $this->estructurasReceptoras
        ]);
    }

    public function eliminarCedente($key)
    {
        $this->_eliminarEstructura('c', $key);
    }

    public function eliminarReceptora($key)
    {
        $this->_eliminarEstructura('r', $key);
    }

    private function _eliminarTodasEstructuras(string $tipo = 'c')
    {
        if ($tipo == 'c') {
            $this->totCed = 0;
            $this->estructurasCedentes = [];
        } else {
            $this->totRec = 0;
            $this->estructurasReceptoras = [];
        }

        $this->emit('estructura:act', [
            'tipo' => $tipo, 
            'estructuras' => $tipo == 'c' ? $this->estructurasCedentes : $this->estructurasReceptoras
        ]);
    }

    public function eliminarTodasCedentes()
    {
        $this->_eliminarTodasEstructuras('c');
    }

    public function eliminarTodasReceptoras()
    {
        $this->_eliminarTodasEstructuras('r');
    }

    /**
     * Busca las estructuras de una solicitud de traspaso y las coloca como receptoras
     * 
     * @return [type]
     */
    public function buscarSolicitud()
    {
        $numDoc = $this->numDoc;

        if (!empty($numDoc) && is_numeric($numDoc)) {
            $solicitud = SolicitudTraspaso::where('nro_sol', $numDoc)
                ->where('ano_pro', $this->anoPro)
                ->first();

            if (!is_null($solicitud)) {
                if ($solicitud->sta_reg->value != EstadoSolicitudTraspaso::Aprobada->value) {
                    $this->_alert('Solicitud de Traspaso con status inválido para agregar: <b>' . $solicitud->sta_reg->name . '</b><br>Por favor verifique.');
                } else {
                    $this->estructurasReceptoras = [];
                    $this->totRec = 0;

                    foreach ($solicitud->obtenerEstructuras() as $row) {
                        $this->estructurasReceptoras[$row['cod_com']] = $row;
                        $this->totRec += $row['mto_tra'];
                    }

                    $this->concepto = $solicitud->concepto;
                    $this->justificacion = $solicitud->justificacion;

                    $this->inicializar();
                }
            } else {
                $this->numDoc = '';
                $this->concepto = '';
                $this->justificacion = '';
                $this->_alert('La Solicitud de Traspaso <b>' . $numDoc . '</b> no existe para el año <b>' . $this->anoPro . '</b>.<br>Por favor verifique.');
            }
        }
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
        return view('livewire.administrativo.meru-administrativo.modificaciones.movimientos.traspaso-form');
    }
}