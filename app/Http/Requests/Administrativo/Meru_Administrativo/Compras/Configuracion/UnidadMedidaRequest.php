<?php

namespace App\Http\Requests\Administrativo\Meru_Administrativo\Compras\Configuracion;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Administrativo\Meru_Administrativo\Compras\UnidadMedida;
use Illuminate\Validation\Rule;

class UnidadMedidaRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'des_uni'           => ['required',  Rule::unique('unidadmedida')->ignore($this->id)],
            'sta_reg'           => 'required'
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
            'des_uni'           => 'Descripción',
            'sta_reg'           => 'Estado',
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge([
        'cod_uni' =>UnidadMedida::select('cod_uni')->max('cod_uni')+1,
        'des_uni'  =>strtoupper($this->des_uni),
        'fecha' =>now()->format('Y-m-d'),
        'usuario' => \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email),
        ]);
    }
}
