<?php

namespace App\Http\Requests\Administrativo\Meru_Administrativo\Contratos\Configuracion;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\ConceptoContrato;
use Illuminate\Support\Str;

class ConceptosContratosRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {

            return [
                'des_con'           => ['required',  Rule::unique('op_conceptos_contrato','des_con')->ignore($this->id)],
                'sta_reg'           => 'required',

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
            'des_con'           => 'Descripción',
            'sta_reg'           => 'Estado',
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge([
        'cod_con' =>ConceptoContrato::select('cod_con')->max('cod_con')+1,
        'des_con'  =>strtoupper($this->des_con),
        'fecha' =>now()->format('Y-m-d'),
        'usuario' => \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email),
        ]);
    }
    public function withValidator($validator)
    {    //Validar que exista partida presupuestaria
        $validator->after(function ($validator) {
            if($this->request->get('estructuras')=="[]") {
                  $validator->errors()->add('partida', 'Debe Asociar Partida Presupuestaria.Por favor verifique ');
                  return $this;
            }
            return $this;
        });
    }
}
