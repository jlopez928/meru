<?php

namespace App\Http\Requests\Administrativo\Meru_Administrativo\Compra;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\funcActas;
use App\Models\Administrativo\Meru_Administrativo\Presupuesto\RegistroControl;

class ActaRequest extends FormRequest
{
    use funcActas;
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
                // 'acta'              => 'required',
                // 'grupo'             => 'required',
                // 'nro_ent'           => 'required',
                // 'xnro_ord'          => 'required',
                // 'ano_ord'           => 'required',
                // 'fk_ano_pro'        => 'required',
                // 'jus_sol'           => 'required',
                'nom_hb'            => 'required',
                'ced_hb'            => 'required',
                'nom_con'           => 'required',
                'ced_con'           => 'required',
                // 'observacion'       => 'required',
                // 'fecha'             => 'required',
                // 'usuario'           => 'required',
                // 'fec_act'           => 'required',
                // 'gerencia'          => 'required',
                'cargo_hb'          => 'required',
                'acta'              => 'required',
                //'encnotaentrega_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            '*.required' =>'El campo :attribute es obligatorio'
        ];
    }

    protected function prepareForValidation()
    {
       //dd($this->acta);
        switch ($this->acta) {
            case "iniciar":
                $acta = 'I';
                break;
            case "terminar":
                $acta = 'T';
                break;
            case "aceptar":
                $acta = 'A';
                break;
        }
//dd($acta);

        $this->merge([
        // 'cod_uni' =>UnidadMedida::select('cod_uni')->max('cod_uni')+1,
        'ced_hb'            => strtoupper($this->ced_hb),
        'nom_hb'            => strtoupper($this->nom_hb),
        'ced_con'           => strtoupper($this->ced_con),
        'nom_con'           => strtoupper($this->nom_con),
        'usuario'           => auth()->user()->id,
        'fecha'             => $this->FechaSistema(RegistroControl::periodoActual(), "Y-m-d H:i:s"),
        'encnotaentrega_id' => $this->id,
        'acta'              => $acta
        // 'fecha' =>now()->format('Y-m-d'),
        // 'usuario' => \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email),
        ]);
    }
}
