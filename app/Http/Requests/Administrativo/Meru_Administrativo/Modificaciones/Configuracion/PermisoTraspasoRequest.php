<?php

namespace App\Http\Requests\Administrativo\Meru_Administrativo\Modificaciones\Configuracion;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PermisoTraspasoRequest extends FormRequest
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
            'usuario_id' => [
                'required',
                Rule::unique('mod_aprmov', 'usuario_id')->ignore($this->permiso_traspaso),
                'exists:users,id'
            ],
            'maxut' => 'required|integer|digits_between:1,14',
            'multicentro' => 'required|boolean'
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'multicentro' => $this->has('multicentro')
        ]);
    }
}
