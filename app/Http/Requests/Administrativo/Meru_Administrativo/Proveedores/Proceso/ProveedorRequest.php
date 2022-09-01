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
            'dir_prov'      => 'required|max:200',
            'tlf_prov1'     => 'sometimes',
            'tlf_prov2'     => 'sometimes',
            'fax'           => 'sometimes',
            'sta_emp'       => 'required',
            'cuenta_hid'    => 'sometimes',
            'sta_con'       => 'sometimes',
            'inscrito_rnc'  => 'sometimes',
            'nro_rnc'       => 'sometimes|max:40',
            'fec_susp'      => 'sometimes',
            'nro_sunacoop'  => 'sometimes|max:30',
            'objetivo'      => 'sometimes|max:500',
            'objetivo_gral' => 'sometimes|max:300',
            'ced_res'       => 'required',
            'nom_res'       => 'required',
            'car_res'       => 'required',
            'ubi_pro'       => 'required',
            'cod_edo'       => 'required',
            'cod_mun'       => 'required',
            'capital'       => 'sometimes',
            'nivel_cont'    => 'sometimes',
            'num_fem'       => 'sometimes',
            'num_mas'       => 'sometimes',
            'sol_ivss'      => 'sometimes',
            'fec_ivss'      => 'sometimes',
            'sol_ince'      => 'sometimes',
            'fec_ince'      => 'sometimes',
            'sol_laboral'   => 'sometimes',
            'fec_laboral'   => 'sometimes',
            'sol_agua'      => 'sometimes',
            'fec_agua'      => 'sometimes',

        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'rif_prov'      => strtoupper($this->rif_prov ?? ''),
            'nom_prov'      => strtoupper($this->nom_prov ?? ''),
            'sig_prov'      => strtoupper($this->sig_prov ?? ''),
            'dir_prov'      => strtoupper($this->dir_prov ?? ''),
            'cuenta_hid'    => strtoupper($this->cuenta_hid ?? ''),
            'objetivo'      => strtoupper($this->objetivo ?? ''),
            'objetivo_gral' => strtoupper($this->objetivo_gral ?? ''),
            'nom_res'       => strtoupper($this->nom_res ?? ''),
            'car_res'       => strtoupper($this->car_res ?? ''),
            'nivel_cont'    => strtoupper($this->nivel_cont ?? ''),
            'sol_ivss'      => strtoupper($this->sol_ivss ?? ''),
            'sol_ince'      => strtoupper($this->sol_ince ?? ''),
            'sol_laboral'   => strtoupper($this->sol_laboral ?? ''),
            'sol_agua'      => strtoupper($this->sol_agua ?? ''),
        ]);
    }

}