<?php

namespace App\Http\Requests\Administrativo\Meru_Administrativo\Formulacion\Configuracion;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\PartidaPresupuestaria;

class PartidaPresupuestariaRequest extends FormRequest
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
            'tipo'          => 'sometimes|required|integer|digits:1',
            'partida'       => 'sometimes|required|integer|digits_between:1,2',
            'generica'      => 'sometimes|required|integer|digits_between:1,2',
            'especifica'    => 'sometimes|required|integer|digits_between:1,2',
            'subespecifica' => 'sometimes|required|integer|digits_between:1,2',
            'cod_partida'   => [
                'sometimes',
                'required',
                'string',
                'size:13',
                Rule::unique('pre_partidasgastos', 'cod_cta')->ignore($this->partida_presupuestaria),
            ],
            'descripcion'        => 'sometimes|required|string|max:500',
            'partida_asociada'   => 'sometimes|nullable|exists:pre_partidasgastos,part_asociada',
            'cta_activo'         => 'sometimes|nullable',
            'cta_gasto'          => 'sometimes|nullable',
            'cta_por_pagar'      => 'sometimes|nullable',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!$this->request->has('cta_activo')) {
                $tipo          = $this->request->get('tipo');
                $partida       = $this->request->get('partida');
                $generica      = $this->request->get('generica');
                $especifica    = $this->request->get('especifica');
                $subespecifica = $this->request->get('subespecifica');
                $cod_partida   = $this->request->get('cod_partida');
                $codPartida    = PartidaPresupuestaria::generarCodPartida(
                                    $partida,
                                    $generica,
                                    $especifica,
                                    $subespecifica
                                );

                if ($tipo . '.' . $codPartida != $cod_partida) {
                    $validator->errors()->add('cod_partida', 'El Código de la Partida no coincide con los campos individuales:' . $cod_partida);
                }

                if ((int)$partida > 0 && (int)$generica > 0) {
                    if (PartidaPresupuestaria::where('cod_par', (int)$partida)
                        ->get()->count() == 0) {
                        $validator->errors()->add('partida', 'Código de Partida no existe en la base de datos');
                        return $this;
                    }
                }

                if ((int)$partida > 0 && (int)$generica > 0) {
                    if (PartidaPresupuestaria::where('cod_par', (int)$partida)
                        ->where('cod_gen', (int)$generica)
                        ->get()->count() == 0) {
                        $validator->errors()->add('generica', 'Código de Genérica no existe en la base de datos');
                        return $this;
                    }
                }

                if ((int)$partida > 0 && (int)$generica > 0) {
                    if (PartidaPresupuestaria::where('cod_par', (int)$partida)
                        ->where('cod_gen', (int)$generica)
                        ->where('cod_esp', (int)$especifica)
                        ->get()->count() == 0) {
                        $validator->errors()->add('especifica', 'Código de Específica no existe en la base de datos');
                        return $this;
                    }
                }
            }

            return $this;
        } );
    }
}
