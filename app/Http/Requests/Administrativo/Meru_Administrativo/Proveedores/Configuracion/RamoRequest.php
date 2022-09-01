<?php

namespace App\Http\Requests\Administrativo\Meru_Administrativo\Proveedores\Configuracion;

use Illuminate\Foundation\Http\FormRequest;

class RamoRequest extends FormRequest
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
            'des_ram'   => 'required|unique:'.'pgsql.pro_ramos,des_ram,'.$this->cod_ram.',cod_ram',
            'sta_reg'   => 'required',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'des_ram' => strtoupper($this->des_ram ?? '')
        ]);
    }
}