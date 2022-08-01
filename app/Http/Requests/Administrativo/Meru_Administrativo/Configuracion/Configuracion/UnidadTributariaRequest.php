<?php

namespace App\Http\Requests\Administrativo\Meru_Administrativo\Configuracion\Configuracion;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UnidadTributariaRequest extends FormRequest
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
            'fec_ut'   => 'required|unique:'.config('app.pgsql').'.adm_unitributaria,fec_ut,'.intval($this->id),
            'bs_ut'    => 'required|numeric|between:1,9999999999',  'bs_ut'    => 'required|numeric|between:1,9999999999',
            'bs_ucau'  => 'required|numeric|between:1,9999999999',
            'vigente'  => 'required',
            'usuario'  => 'required'

        ];
    }
}
