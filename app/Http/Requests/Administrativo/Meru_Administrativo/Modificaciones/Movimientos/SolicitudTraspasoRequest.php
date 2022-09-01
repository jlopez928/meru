<?php

namespace App\Http\Requests\Administrativo\Meru_Administrativo\Modificaciones\Movimientos;

use App\Models\Administrativo\Meru_Administrativo\Formulacion\MaestroLey;
use Illuminate\Foundation\Http\FormRequest;

class SolicitudTraspasoRequest extends FormRequest
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
            'ano_pro'       => 'required',
            'fec_sol'       => 'required',
            'num_sop'       => 'required|string|between:1,20',
            'cod_ger'       => 'required',
            'nro_ext'       => 'nullable|integer|digits_between:1,5',
            'concepto'      => 'required|string|between:1,500',
            'justificacion' => 'required|string|between:1,800',
            'total'         => 'required|numeric',
            'estructuras'   => 'required'
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'total' => \Str::replace(',', '.', \Str::replace('.', '', $this->total)),
            'estructuras' => $this->estructuras == '[]' ? null : $this->estructuras,
        ]);
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $estructuras = json_decode($this->request->get('estructuras'), true);

            $ceco  = '';
            $total = 0.00;

            if (is_null($estructuras)) {
                $validator->errors()->add('partida', 'Debe agregar al menos una estructura.');
                return $this;
            }

            foreach($estructuras as $key => $row) {
                if (empty($ceco)) {
                    $ceco = \Str::substr($key, 0, 14);
                }

                if ($ceco != \Str::substr($key, 0, 14)) {
                    $validator->errors()->add('partida', 'Todas las estructuras deben pertenecer a un mismo Centro de Costo, Por favor verifique.');
                    return $this;
                }

                $existe = MaestroLey::where('ano_pro', session('ano_pro'))->where('cod_com', $row['cod_com'])->first();

                if (is_null($existe)) {
                    $validator->errors()->add('partida', 'La partida 4.' . \Str::substr($key, 15) . ' no existe asociada al centro de costo ' . \Str::substr($key, 0, 14) . ', Por favor verifique.');
                    return $this;
                }

                $total += (float)$row['mto_tra'];
            }

            //if ($total != (float)$this->request->get('total')) {
            if ((string)$total != $this->request->get('total')) {
                $validator->errors()->add('total', 'El monto total no puede ser distinto de la suma de las estructuras.');
                return $this;
            }

            return $this;
        });
    }
}
