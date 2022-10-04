<?php

namespace App\Http\Requests\Administrativo\Meru_Administrativo\Contratos\Proceso;

use Illuminate\Foundation\Http\FormRequest;

class OpSolContratoRequest extends FormRequest
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
            'num_contrato'          => 'required',
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
            'fec_serv'              => 'Fecha de Servicio',
            'motivo'                => 'Motivo',
        ];
    }
}
