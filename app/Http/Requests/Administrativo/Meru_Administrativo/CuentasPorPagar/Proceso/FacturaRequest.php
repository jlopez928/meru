<?php

namespace App\Http\Requests\Administrativo\Meru_Administrativo\CuentasPorPagar\Proceso;

use Illuminate\Foundation\Http\FormRequest;

class FacturaRequest extends FormRequest
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
            'ano_pro'           => 'required',
            'rif_prov'          => 'required',
            'num_fac'           => 'required',
            'recibo'            => 'required',
            'provisionada'      => 'required',
            'servicio'          => 'required',
           // 'id'                => 'required',
            // 'num_ctrl',
            // 'fec_fac',
            // 'tipo_doc',
            // 'tipo_pago',
            // 'nro_doc',
            // 'base_imponible',
            // 'base_excenta',
            // 'mto_nto',
            // 'mto_iva',
            // 'mto_fac',
            // 'por_anticipo',
            // 'mto_anticipo',
            // 'mto_amortizacion',
            // 'ncr_sn',
            // 'nro_ncr',
            // 'mto_ncr',
            // 'iva_ncr',
            // 'tot_ncr',
            // 'usuario',
            // 'fecha',
            // 'usua_apr',
            // 'fec_apr',
            // 'usua_anu',
            // 'fec_anu',
            // 'sta_fac',
            // 'fec_sta',
            // 'sol_pag',
            // 'usua_pago',
            // 'fec_pago',
            // 'monto_original',
            // 'porcentaje_iva',
            // 'num_nc',
            // 'ano_sol',

            // 'mod_fac',
            // 'descuentos',
            // 'monto_descuento',
            // 'cuenta_contable',
            // 'fondo',
            // 'pago_manual',
            // 'deposito_garantia',
            // 'deuda',
            // 'tipo_nota',
            // 'ano_nota',
            // 'base_imponible_nd',
            // 'base_exenta_nd',
            // 'observacion',
            // 'sta_rep',
            // 'referencia',

            // 'monto_contrato',
            // 'nro_reng',

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
            'ano_pro'            => 'Año de Proceso ',
            'rif_prov'           => 'Rif Proveedor',
            'num_fac'            => 'N° Factura',
            'recibo'             => 'Recibo',
            'provisionada'       => 'Provisionada',
            'servicio'           => 'Servicio',
          // 'id'                 => 'Identificador sistema',


        ];
    }
}
