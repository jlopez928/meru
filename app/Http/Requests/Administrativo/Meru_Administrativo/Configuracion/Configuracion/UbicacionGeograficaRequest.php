<?php

namespace App\Http\Requests\Administrativo\Meru_Administrativo\Configuracion\Configuracion;

use Illuminate\Foundation\Http\FormRequest;

class UbicacionGeograficaRequest extends FormRequest
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
            'estado'      => 'nullable',
            'municipio'   => 'nullable',
            'paroquia'    => 'nullable',
            'descripcion' => 'required|string|between:1,80',
            'capital'     => 'required|string|between:1,40',
            'codigo'      => 'required|string|between:2,5',
        ];
    }
}
