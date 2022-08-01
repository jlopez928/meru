<?php

namespace App\Http\Requests\Administrativo\Meru_Administrativo\Configuracion\Configuracion;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\RegistroControl;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\CentroCosto;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\PartidaPresupuestaria;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\MaestroLey;

class GerenciaRequest extends FormRequest
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
            'gerencia'     => 'required|string|between:1,500',
            'nomenclatura' => 'required|string|between:1,3',
            'jefe'         => 'nullable|string|between:1,60',
            'cargo_jefe'   => 'nullable|string|between:1,60',
            'correo_jefe'  => 'nullable|string|between:1,60|email',
            'centro_costo' => [
                'required',
                Rule::exists('pre_centrocosto', 'id')->where('ano_pro', RegistroControl::periodoActual())
            ],
            'viaticos_nac' => [
                'nullable',
                Rule::exists('pre_partidasgastos', 'id')
            ],
            'viaticos_internac' => [
                'nullable',
                Rule::exists('pre_partidasgastos', 'id')
            ]
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!$this->request->has('cta_activo')) {
                $cenCos       = CentroCosto::find($this->request->get('centro_costo'));
                $partidaGasto = $this->request->get('viaticos_nac');
                $partidaGastoVinternac= $this->request->get('viaticos_internac');

                if (!is_null($partidaGasto)) {
                    $partida    = PartidaPresupuestaria::find($partidaGasto);
                    $estructura = $cenCos->cod_cencosto . \Str::substr($partida->cod_cta, 1);

                    $maestroLey = MaestroLey::where('ano_pro', RegistroControl::periodoActual())->where('cod_com', $estructura)->first();

                    if (is_null($maestroLey)) {
                        $validator->errors()->add('viaticos_nac', 'No existe la estructura de gasto ' . $estructura . ' para Viaticos Nacionales');
                        return $this;
                    }
                }

                if (!is_null($partidaGastoVinternac)) {
                    $partida    = PartidaPresupuestaria::find($partidaGastoVinternac);
                    $estructura = $cenCos->cod_cencosto . \Str::substr($partida->cod_cta, 1);
                    $maestroLey = MaestroLey::where('ano_pro', RegistroControl::periodoActual())->where('cod_com', $estructura)->first();

                    if (is_null($maestroLey)) {
                        $validator->errors()->add('viaticos_internac', 'No existe la estructura de gasto ' . $estructura . ' para Viaticos Internacionales');
                        return $this;
                    }
                }
            }

            return $this;
        } );
    }
}
