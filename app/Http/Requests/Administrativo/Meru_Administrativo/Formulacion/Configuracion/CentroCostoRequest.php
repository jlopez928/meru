<?php

namespace App\Http\Requests\Administrativo\Meru_Administrativo\Formulacion\Configuracion;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\CentroCosto;

class CentroCostoRequest extends FormRequest
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
        $rules = [
            'cod_centro'  => [
                'required', 
                'string',
                'size:14',
                Rule::unique('pre_centrocosto', 'cod_cencosto')
                    ->where('ano_pro', $this->ano_pro)
                    ->ignore($this->centro_costo)
            ],
            'tipo'        => 'required|integer|digits_between:1,2',
            'proyecto'    => 'required|integer|digits_between:1,2',
            'objetivo'    => 'required|integer|digits_between:1,2',
            'gerencia'    => 'required|integer|digits_between:1,2',
            'unidad'      => 'required|integer|digits_between:1,2',
            'descripcion' => 'required|string|max:500',
        ];

        return $rules;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $codCentro = $this->request->get('cod_centro');
            $cenCos    = CentroCosto::generarCodCentroCosto(
                            $this->request->get('tipo'),
                            $this->request->get('proyecto'),
                            $this->request->get('objetivo'),
                            $this->request->get('gerencia'),
                            $this->request->get('unidad')
                        );
            $cenCosPadre = CentroCosto::getPadre($cenCos);
            $existePadre = CentroCosto::existe($this->request->get('ano_pro'), $cenCosPadre);

            if ($cenCos != $codCentro) {
                $validator->errors()->add('cod_centro', 'El Código del Centro de Costo no coincide con los campos individuales: ' . $codCentro);
                return $this;
            }

            if (!$existePadre) {
                $validator->errors()->add('cod_centro', 'Primero debe registrar el centro de costo padre: ' . $cenCosPadre);
                return $this;
            }

            return $this;
        });
    }
}
