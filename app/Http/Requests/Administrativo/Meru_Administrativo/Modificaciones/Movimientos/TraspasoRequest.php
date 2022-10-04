<?php

namespace App\Http\Requests\Administrativo\Meru_Administrativo\Modificaciones\Movimientos;

use Illuminate\Foundation\Http\FormRequest;

class TraspasoRequest extends FormRequest
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
            'estructurasCedentes'   => 'required',
            'estructurasReceptoras' => 'required',
            'total_ced'             => 'required|numeric|gt:0',
            'total_rec'             => 'required|numeric|gt:0',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'total_ced' => \Str::replace(',', '.', \Str::replace('.', '', $this->total_ced)),
            'total_rec' => \Str::replace(',', '.', \Str::replace('.', '', $this->total_rec)),
            'estructurasCedentes' => $this->estructurasCedentes == '[]' ? null : $this->estructurasCedentes,
            'estructurasReceptoras' => $this->estructurasReceptoras == '[]' ? null : $this->estructurasReceptoras,
        ]);
    }

    public function attributes()
    {
        return[
            'num_doc'               => 'Solicitud',
            'concepto'              => 'Concepto',
            'justificacion'         => 'Justificación',
            'estructurasCedentes'   => 'Estructuras Cedentes',
            'estructurasReceptoras' => 'Estructuras Receptoras',
            'total_ced'             => 'Total Cedentes',
            'total_rec'             => 'Total Receptoras',
        ];
    }

    public function messages()
    {
        return [
            'estructurasCedentes.required'   => 'Debe existir al menos una Estructura Cedente',
            'estructurasReceptoras.required' => 'Debe existir al menos una Estructura Receptora',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $estCed   = json_decode($this->request->get('estructurasCedentes'), true);
            $estRec   = json_decode($this->request->get('estructurasReceptoras'), true);
            $totalCed = $this->request->get('total_ced');
            $totalRec = $this->request->get('total_rec');

            if (is_array($estCed) && is_array($estRec)) {
                $totCedCalc = array_sum(array_column($estCed, 'mto_tra'));
                $totRecCalc = array_sum(array_column($estRec, 'mto_tra'));
                
                if ($totalCed != $totCedCalc) {
                    $validator->errors()->add('total_ced', 'El monto Total Cedentes no coincide con el total de estructuras del listado.');
                    return $this;
                }

                if ($totalRec != $totRecCalc) {
                    $validator->errors()->add('total_rec', 'El monto Total Receptoras no coincide con el total de estructuras del listado.');
                    return $this;
                }
            }

            return $this;
        });
    }
}
