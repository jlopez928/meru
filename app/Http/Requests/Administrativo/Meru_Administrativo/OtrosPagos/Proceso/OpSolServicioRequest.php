<?php

namespace App\Http\Requests\Administrativo\Meru_Administrativo\OtrosPagos\Proceso;

use Illuminate\Foundation\Http\FormRequest;
class OpSolServicioRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'monto_total'           => 'required',
            'cod_ger'               => 'required',
            'rif_prov'              => 'required',
            'tip_pag'               => 'required',
            'factura'               => 'required',
            'lugar_serv'            => 'required|max:30',
            'fec_serv'              => 'required',
            'motivo'                => 'required'
        ];
    }
    public function messages()
    {
        return [
            '*.required' =>'El campo :attribute es obligatorio'
        ];
    }
    //-----------------------------------------------------------------------
    //  Nombre de los Atributos en los mensajes de Error ::attribute
    //-----------------------------------------------------------------------
    public function attributes(){

        return[
            'monto_total'           => 'Monto Total',
            'cod_ger'               => 'Gerencia',
            'rif_prov'              => 'Rif Proveedor',
            'tip_pag'               => 'Tipo de Pago',
            'factura'               => 'Factura',
            'lugar_serv'            => 'Lugar de Servicio',
            'fec_serv'              => 'Fecha de Servicio',
            'motivo'                => 'Motivo',
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
             if ($this->request->get('factura') == 'N') {
                if ($this->request->get('por_iva')!= 0 && $this->request->get('base_imponible') != 0 && $this->request->get('monto_iva')!= 0 && $this->request->get('cos_uni') != 0 && $this->request->get('por_iva_con') != 0) {
                    $validator->errors()->add('por_iva', 'Las certificaciones sin facturas no deben tener IVA ni Base Imponible.Por favor verifique ');
                    return $this;
                }
            }
            return $this;
        });
    }

}
