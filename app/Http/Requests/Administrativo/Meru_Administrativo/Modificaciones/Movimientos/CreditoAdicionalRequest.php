<?php

namespace App\Http\Requests\Administrativo\Meru_Administrativo\Modificaciones\Movimientos;

use Illuminate\Foundation\Http\FormRequest;

class CreditoAdicionalRequest extends FormRequest
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
            'num_doc'               => 'nullable|integer|digits_between:1,5',
            'concepto'              => 'required|string|between:1,500',
            'justificacion'         => 'nullable|string|between:1,500',
            'estructurasReceptoras' => 'required',
            'total_rec'             => 'required|numeric|gt:0',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'total_rec' => \Str::replace(',', '.', \Str::replace('.', '', $this->total_rec)),
            'estructurasReceptoras' => $this->estructurasReceptoras == '[]' ? null : $this->estructurasReceptoras,
        ]);
    }

    public function attributes()
    {
        return[
            'num_doc'               => 'Solicitud',
            'concepto'              => 'Concepto',
            'justificacion'         => 'Justificación',
            'estructurasReceptoras' => 'Estructuras Receptoras',
            'total_rec'             => 'Total Receptoras',
        ];
    }

    public function messages()
    {
        return [
            'estructurasReceptoras.required' => 'Debe existir al menos una Estructura Receptora',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $estRec   = json_decode($this->request->get('estructurasReceptoras'), true);
            $totalRec = $this->request->get('total_rec');

            if (is_array($estRec)) {
                $totRecCalc = array_sum(array_column($estRec, 'mto_tra'));

                if ($totalRec != $totRecCalc) {
                    $validator->errors()->add('total_rec', 'El monto Total Receptoras no coincide con el total de estructuras del listado.');
                    return $this;
                }
            }

            return $this;
        });
    }
}