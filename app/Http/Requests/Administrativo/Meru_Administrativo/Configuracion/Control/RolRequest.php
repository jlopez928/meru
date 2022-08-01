<?php

namespace App\Http\Requests\Administrativo\Meru_Administrativo\Configuracion\Control;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RolRequest extends FormRequest
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

    public function rules()
    {
        return [
            'name'  => ['required', 'string', 'max:30',  Rule::unique('roles')->ignore($this->rol)],
            'status' => 'required'


        ];

    }
}
