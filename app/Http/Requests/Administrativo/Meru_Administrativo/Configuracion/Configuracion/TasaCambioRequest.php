<?php

namespace App\Http\Requests\Administrativo\Meru_Administrativo\Configuracion\Configuracion;

use Illuminate\Foundation\Http\FormRequest;

class TasaCambioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
             'fec_tasa' => 'required|unique:'.config('app.pgsql').'adm_tasacambio,fec_tasa,'.intval($this->id),
             'bs_tasa'  => 'required|numeric|between:0,9999999999',
             'usuario'  => 'required',
             'sta_reg'  => 'required',
             'fecha'    => 'required',

        ];
    }
}
