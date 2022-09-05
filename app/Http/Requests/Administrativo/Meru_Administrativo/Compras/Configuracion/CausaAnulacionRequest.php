<?php

namespace App\Http\Requests\Administrativo\Meru_Administrativo\Compras\Configuracion;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Administrativo\Meru_Administrativo\Compras\CausaAnulacion;
use Illuminate\Validation\Rule;

class CausaAnulacionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'des_cau'           => ['required',  Rule::unique('com_causasanulacion')->ignore($this->id)],
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
            'des_cau'           => 'Descripción',
            'sta_reg'           => 'Estado',
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge([
        'cod_cau' =>CausaAnulacion::select('cod_cau')->max('cod_cau')+1,
        'des_cau'  =>strtoupper($this->des_cau),
        'fecha' =>now()->format('Y-m-d'),
        'usuario' => \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email),
        ]);
    }
}
