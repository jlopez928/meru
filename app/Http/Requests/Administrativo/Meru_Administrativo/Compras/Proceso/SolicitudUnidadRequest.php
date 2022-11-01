<?php

namespace App\Http\Requests\Administrativo\Meru_Administrativo\Compras\Proceso;

use Illuminate\Foundation\Http\FormRequest;

class SolicitudUnidadRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return  [
                    'ano_pro'       => 'required',
                    'grupo'         => 'required',
                    'cla_sol'       => 'required',
                    'jus_sol'       => 'required',
                    'fec_emi'       => 'required',
                    'gru_ram'       => 'required',
                    'fk_cod_ger'    => 'required',
                    'pri_sol'       => 'required',
                    'fec_anu'       => 'required',
                    'fk_cod_cau'    => 'required',
                    'monto_tot'     => [
                                            'required',
                                            function($attribute,$value,$fail){
                                                if($value == '0' || $value == '0,00'){
                                                    $fail('El monto total de la Solicitud no puede ser cero');
                                                }
                                            }

                                        ],
                    'anexos'       => 'required'
                ];
    }

    public function attributes(){

        return[
            'ano_pro'           => 'año',
            'cla_sol'           => 'clase',
            'jus_sol'           => 'justificación',
            'fec_emi'           => 'fecha emisión',
            'gru_ram'           => 'grupo ramo',
            'fk_cod_ger'        => 'gerencia solicitante',
            'pri_sol'           => 'prioridad',
            'fec_anu'           => 'fecha anulación',
            'fk_cod_cau'        => 'causa anulación',
            'monto_tot'         => 'monto total',
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge([
                        'jus_sol'  => strtoupper($this->jus_sol),
                        'anexos'   => strtoupper($this->anexos),
                    ]);
    }
}
