<?php

namespace App\Http\Requests\Administrativo\Meru_Administrativo\Compras\Configuracion;

use Illuminate\Foundation\Http\FormRequest;

class SubGrupoProductoRequest extends FormRequest
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
        $rules = [
            'grupo'         => 'required',
            'des_subgrupo'  => 'required|max:150',
            'sta_reg'       => 'required',
        ];

        if ($this->getMethod() === 'POST') { // store
            $rules += ['subgrupo' => 'required|size:5|unique:' . 'pgsql.subgruposprod,subgrupo,NULL,subgrupo'];
        }

        return $rules;
    }

    public function prepareForValidation()
    {
        $this->merge([
            'subgrupo'     => strtoupper($this->subgrupo ?? ''),
            'des_subgrupo' => strtoupper($this->des_subgrupo ?? ''),
        ]);
    }
}
