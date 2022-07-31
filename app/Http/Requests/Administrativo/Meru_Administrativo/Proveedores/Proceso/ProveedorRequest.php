<?php

namespace App\Http\Requests\Administrativo\Meru_Administrativo\Proveedores\Proceso;

use Illuminate\Foundation\Http\FormRequest;

class ProveedorRequest extends FormRequest
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
            'tip_emp'       => 'required',
            'rif_prov'      => 'required',
            'cod_prov'      => 'sometimes',
            'tip_reg'       => 'sometimes',
            'nom_prov'      => 'required|max:90',
            'sig_prov'      => 'sometimes|max:30',
            'email'         => 'sometimes|max:100',
            'dir_prov'      => 'sometimes|max:200',
            'tlf_prov1'     => 'sometimes',
            'tlf_prov2'     => 'sometimes',
            'fax'           => 'sometimes',
            'sta_emp'       => 'sometimes',
            'cuenta_hid'    => 'sometimes',
            'sta_con'       => 'sometimes',
            'inscrito_rnc'  => 'sometimes',
            'nro_rnc'       => 'sometimes|max:40',
            'fec_susp'      => 'sometimes',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'rif_prov' => strtoupper($this->rif_prov),
            'nom_prov' => strtoupper($this->nom_prov),
            'sig_prov' => strtoupper($this->sig_prov),
            'dir_prov' => strtoupper($this->dir_prov),
        ]);
    }

}