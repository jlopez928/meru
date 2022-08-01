<?php

namespace App\Http\Requests\Administrativo\Meru_Administrativo\Formulacion\Configuracion;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;

class MaestroLeyRequest extends FormRequest
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
			'centro_costo' =>  [
				'sometimes',
				'required',
				Rule::exists('pre_centrocosto', 'cod_cencosto')->where('ano_pro', $this->ano_pro)
			],
			'partida_presupuestaria' => 'sometimes|required|exists:pre_partidasgastos,cod_cta',
			'estructura' => [
				'required',
				Rule::unique('pre_maestroley', 'cod_com')
					->where('ano_pro', $this->ano_pro)
					->ignore($this->maestro_ley),
			],
		];
	}
}
