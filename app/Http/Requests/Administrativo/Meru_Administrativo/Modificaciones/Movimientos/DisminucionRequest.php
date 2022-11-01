<?php

namespace App\Http\Requests\Administrativo\Meru_Administrativo\Modificaciones\Movimientos;

use Illuminate\Foundation\Http\FormRequest;

class DisminucionRequest extends FormRequest
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
            'total_ced'             => 'required|numeric|gt:0',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'total_ced' => \Str::replace(',', '.', \Str::replace('.', '', $this->total_ced)),
            'estructurasCedentes' => $this->estructurasCedentes == '[]' ? null : $this->estructurasCedentes,
        ]);
    }

    public function attributes()
    {
        return[
            'num_doc'               => 'Solicitud',
            'concepto'              => 'Concepto',
            'justificacion'         => 'Justificación',
            'estructurasCedentes'   => 'Estructuras Cedentes',
            'total_ced'             => 'Total Cedentes',
        ];
    }

    public function messages()
    {
        return [
            'estructurasCedentes.required'   => 'Debe existir al menos una Estructura Cedente',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $estCed   = json_decode($this->request->get('estructurasCedentes'), true);
            $totalCed = $this->request->get('total_ced');

            if (is_array($estCed)) {
                $totCedCalc = array_sum(array_column($estCed, 'mto_tra'));
                
                if ($totalCed != $totCedCalc) {
                    $validator->errors()->add('total_ced', 'El monto Total Cedentes no coincide con el total de estructuras del listado.');
                    return $this;
                }
            }

            return $this;
        });
    }
}