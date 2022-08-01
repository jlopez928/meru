<?php

namespace App\Http\Requests\Administrativo\Meru_Administrativo\Configuracion\Control;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ModuloRequest extends FormRequest
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
                'name'  => ['required', 'string',  'max:100', Rule::unique('modulos')->ignore($this->modulo)],
                'status' => 'required',


            ];

    }
}
