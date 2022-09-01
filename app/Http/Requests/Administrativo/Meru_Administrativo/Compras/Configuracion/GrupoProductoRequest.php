<?php

namespace App\Http\Requests\Administrativo\Meru_Administrativo\Compras\Configuracion;

use Illuminate\Foundation\Http\FormRequest;

class GrupoProductoRequest extends FormRequest
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
                    'des_grupo' => 'required|max:180|unique:'.'pgsql.gruposprod,des_grupo,'.$this->grupo.',grupo,deleted_at,NULL',
                    'sta_reg'   => 'required',
                ];

        if ($this->getMethod() === 'POST') { // store
            $rules += ['grupo' => 'required|size:3|string|unique:'.'pgsql.gruposprod,grupo,NULL,grupo'];
        }

        return $rules;
    }

    public function prepareForValidation()
    {
        $this->merge([
            'grupo'     => strtoupper($this->grupo ?? ''),
            'des_grupo' => strtoupper($this->des_grupo ?? ''),
        ]);
    }
}
