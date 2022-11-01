<?php

namespace App\Http\Requests\Administrativo\Meru_Administrativo\Cuentasxpagar\Proceso;

use Illuminate\Foundation\Http\FormRequest;

class SolicitudPagoRequest extends FormRequest
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
            //
        ];
    }
}
