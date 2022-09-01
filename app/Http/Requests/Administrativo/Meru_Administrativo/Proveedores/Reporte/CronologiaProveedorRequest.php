<?php

namespace App\Http\Requests\Administrativo\Meru_Administrativo\Proveedores\Reporte;

use Illuminate\Foundation\Http\FormRequest;

class CronologiaProveedorRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }
    public function rules()
    {   $this->redirect = url()->previous();
        return [
            'rif_prov'           => 'required',
            'fec_ini'           => '',
            'fec_fin'           => 'required_if:fec_ini,after_or_equal:fec_ini',
            ];
    }

    public function messages()
    {
        return [
            '*.required' =>'El campo :attribute es obligatorio',
            'fec_fin.after_or_equal' =>'El campo :$attribute no es válido. La fecha final, debe ser posterior a la fecha inicial'
        ];
    }
    //-----------------------------------------------------------------------
    //  Nombre de los Atributos en los mensajes de Error ::attribute
    //-----------------------------------------------------------------------
    public function attributes(){

        return[
            'rif_prov'          => 'Rif de Proveedor',
            'fec_ini'           => 'Fecha Inicio',
            'fec_fin'           => 'Fecha Fin',
        ];
    }
    public function withValidator($validator)
    {    //Validar que a menos exista un campo de filtro

        $validator->after(function ($validator) {
            if($this->request->get('rif_prov')=="" &&
               $this->request->get('fec_ini')=="" &&
               $this->request->get('fec_fin')=="" )
            {
               $validator->errors()->add('mensaje', 'Debe Seleccionar un filtro para generar el reporte');
                  return $this;
            }
            return $this;
        });
    }

}
