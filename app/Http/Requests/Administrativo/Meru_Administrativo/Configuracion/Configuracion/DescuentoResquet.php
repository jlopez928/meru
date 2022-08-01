<?php

namespace App\Http\Requests\Administrativo\Meru_Administrativo\Configuracion\Configuracion;

use Illuminate\Foundation\Http\FormRequest;

class DescuentoResquet extends FormRequest
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
             'cod_des'            => 'required',
             'des_des'            => 'required',
             'tip_mto'            => 'required',
             'cla_desc'           => 'required',
             'residente'          => 'required',
               'id_des'             => 'required',
             'por_islr'           => 'required|numeric|between:0,9999999999',
             'usuario'            => 'required',
            //  'fecha'              => 'required',
             'status'             => 'required',
             'tipo_montos_id'     => 'required',
             'adm_retencion_id'   => 'required',
             'adm_residencia_id'  => 'required',

        ];
    }

    // Para modificar $request antes bde validad
    protected function prepareForValidation()
    {
        $this->merge([
        'id_des' =>$this->cod_des .  $this->cla_desc .  $this->residente
        ]);
    }
}
