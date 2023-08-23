<?php

namespace App\Http\Requests\Administrativo\Meru_Administrativo\CuentasPorPagar\Proceso;

use Illuminate\Foundation\Http\FormRequest;

class FacRecepFacturaRequest extends FormRequest
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
              'rif_prov'           => 'required',
            // // 'nro_reng'           => 'required',
             'concepto'           => 'required',
             'num_fac'            => 'required',
             'mto_fac'            => 'required',
             'fec_fac'            => 'required',
             'fec_rec'            => 'required',
             'tipo_doc'           => 'required',
             'nro_doc'            => 'required',
             'ano_pro'            => 'required'
        ];
    }

    //-----------------------------------------------------------------------
    //  Nombre de los Atributos en los mensajes de Error ::attribute
    //-----------------------------------------------------------------------
     public function attributes(){

        return[
            'rif_prov'           => 'Rif Proveedor',
            'concepto'           => 'Concepto',
            'num_fac'            => 'N° Factura',
            'nro_reng'           => 'N° de Renglón',
            'mto_fac'            => 'Monto Factura',
            'fec_fac'            => 'Fecha de Factura',
            'fec_rec'            => 'Fecha de Recepción',
            'tipo_doc'           => 'Tipo de Documento',
            'nro_doc'            => 'N° Documento',
            'ano_pro'            => 'Año de Proceso '
        ];
    }

    public function messages()
    {
        return [
            '*.required' =>'El campo :attribute es obligatorio'
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'rif_prov'      => strtoupper($this->rif_prov ?? ''),
            'concepto'      => strtoupper($this->concepto ?? ''),
            'num_fac'       => strtoupper($this->num_fac ?? ''),
            'tipo_doc'      => strtoupper($this->tipo_doc ?? ''),
            'nro_doc'       => strtoupper($this->nro_doc ?? ''),
        ]);
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
                if ($this->request->get('fec_rec') <  $this->request->get('fec_fac') ) {
                    $validator->errors()->add('fec_rec', 'La fecha de recepcción no puedeser menor que la fecha de facturación.Por favor verifique ');
                    return $this;
                }

            return $this;
        });
    }
}
