<x-card x-data="handler()">
	<x-slot name="header">
		<h3 class="card-title text-bold">{{ $nombre }}</h3>
	</x-slot>

	<x-slot name="body">

		<!-- Divisor -->
		<div class="row col-12">
			<div class="col-12">
				<h5 class="card-title text-secondary text-bold">Periodo</h5>
			</div>

			<div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
		</div>

		<div class="row col-12">
			<div class="form-group col-2 offset-4">
				<x-label for="ano_pro">Año</x-label>
				<x-select name="ano_pro" class="form-control-sm {{ $errors->has('ano_pro') ? 'is-invalid' : '' }} " required>
					<option value="">Seleccione...</option>
					@foreach ($periodosRep as $periodoItem)
						<option value="{{ $periodoItem }}" @selected(old('ano_pro') == $periodoItem)>
							{{ $periodoItem }}
						</option>
					@endforeach
				</x-select>
			</div>

			<div class="form-group col-2">
				<x-label for="mes">Mes</x-label>
				<x-select name="mes" class="form-control-sm {{ $errors->has('mes') ? 'is-invalid' : '' }}" required>
					<option value="">Seleccione...</option>
					@foreach ($meses as $key => $mes)
						<option value="{{ $key }}" @selected(old('mes') == $key)>
							{{ $mes }}
						</option>
					@endforeach
				</x-select>
			</div>
		</div>

		<div class="row col-12">
			<div class="col-12">
				<h5 class="card-title text-secondary text-bold">Centro de Costo</h5>
			</div>

			<div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
		</div>

		<div class="row col-12">
			<div class="form-group col-2 offset-1">
				<x-label for="tip_cod">Tipo</x-label>
				<x-input name="tip_cod" x-mask="99" class="text-center form-control-sm {{ $errors->has('tip_cod') ? 'is-invalid' : '' }}" placeholder="Ingrese tipo" value="{{ old('tip_cod') }}" maxlength="2" @keyup="generarCodCenCos()"/>

				@error('tip_cod')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>

			<div class="form-group col-2">
				<x-label for="cod_pryacc">Proyecto / Acción</x-label>
				<x-input name="cod_pryacc" x-mask="99" class="text-center {{ $errors->has('cod_pryacc') ? 'is-invalid' : '' }}" placeholder="Ingrese proyecto" value="{{ old('cod_pryacc') }}" maxlength="2" @keyup="generarCodCenCos()"/>

				@error('cod_pryacc')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>

			<div class="form-group col-2">
				<x-label for="cod_obj">Objetivo específico</x-label>
				<x-input name="cod_obj" x-mask="99" class="text-center {{ $errors->has('cod_obj') ? 'is-invalid' : '' }}" placeholder="Ingrese objetivo" value="{{ old('cod_obj') }}" maxlength="2" @keyup="generarCodCenCos()"/>

				@error('cod_obj')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>

			<div class="form-group col-2">
				<x-label for="gerencia">Gerencia</x-label>
				<x-input name="gerencia" x-mask="99" class="text-center {{ $errors->has('gerencia') ? 'is-invalid' : '' }}" placeholder="Ingrese gerencia" value="{{ old('gerencia') }}" maxlength="2" @keyup="generarCodCenCos()"/>

				@error('gerencia')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>

			<div class="form-group col-2">
				<x-label for="unidad">Unidad Ejecutora</x-label>
				<x-input name="unidad" x-mask="99" class="text-center {{ $errors->has('unidad') ? 'is-invalid' : '' }}" placeholder="Ingrese unidad" value="{{ old('unidad') }}" maxlength="2" @keyup="generarCodCenCos()"/>

				@error('unidad')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>
		</div>

		<div class="ow col-12">
			<div class="form-group col-6 offset-3">
				<x-label for="cod_centro">Centro de Costo</x-label>
				<select name="cod_centro" id="cod_centro" class="form-control form-control-sm" readonly>
					<option value="">...</option>
					@foreach ($centrosCosto as $cecoItem)
						<option value="{{ $cecoItem->cod_cencosto }}" @selected(old('cod_centro') == $cecoItem->cod_cencosto)>{{ $cecoItem->cod_cencosto . ' - ' . $cecoItem->des_con }}</option>
					@endforeach
				</select>

				@error('cod_centro')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>
		</div>

		<div class="row col-12">
			<div class="col-12">
				<h5 class="card-title text-secondary text-bold">Partida de Gastos</h5>
			</div>

			<div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
		</div>

		<div class="row col-12">
			<div class="form-group col-1 offset-2">
				<x-label for="cod_par">Partida</x-label>
				<x-input name="cod_par" x-mask="99" class="text-center {{ $errors->has('cod_par') ? 'is-invalid' : '' }}" value="{{ old('cod_par') }}" maxlength="2" @keyup="generarCodPartida()"/>

				@error('cod_par')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>

			<div class="form-group col-1">
				<x-label for="cod_gen">Genérica</x-label>
				<x-input name="cod_gen" x-mask="99" class="text-center {{ $errors->has('cod_gen') ? 'is-invalid' : '' }}" value="{{ old('cod_gen') }}" maxlength="2" @keyup="generarCodPartida()"/>

				@error('cod_gen')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>

			<div class="form-group col-1">
				<x-label for="cod_esp">Específica</x-label>
				<x-input name="cod_esp" x-mask="99" class="text-center {{ $errors->has('cod_esp') ? 'is-invalid' : '' }}" value="{{ old('cod_esp') }}" maxlength="2" @keyup="generarCodPartida()"/>

				@error('cod_esp')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>

			<div class="form-group col-1">
				<x-label for="cod_sub">Sub-esp</x-label>
				<x-input name="cod_sub" x-mask="99" class="text-center {{ $errors->has('cod_sub') ? 'is-invalid' : '' }}" value="{{ old('cod_sub') }}" maxlength="2" @keyup="generarCodPartida()"/>

				@error('cod_sub')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>

			<div class="form-group col-4">
				<x-label for="cod_partida">Partida de Gastos</x-label>
				<select name="cod_partida" id="cod_partida" class="form-control form-control-sm" readonly>
					<option value="">...</option>
					@foreach ($partidas as $partidaItem)cod_partida
						<option value="{{ $partidaItem->cod_cta }}" @selected(old('cod_partida') == $partidaItem->cod_cta)>{{ $partidaItem->cod_cta . ' - ' . $partidaItem->des_con }}</option>
					@endforeach
				</select>

				@error('cod_partida')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>
		</div>

		<div class="row col-12">
			<div class="form-group col-2 offset-5">
				<x-label for="tipo_reporte">Tipo Reporte</x-label>
				<x-select name="tipo_reporte" class="form-control-sm" required>
					<option value="P">PDF</option>
					<option value="E">EXCEL</option>
				</x-select>
			</div>
		</div>

	</x-slot>

	<x-slot name="footer">
		<button type="submit" class="btn btn-sm btn-primary text-bold float-right">Generar</button>
	</x-slot>

</x-card>

<script type="text/javascript">
	function handler() {
		return {
			init() {
				this.generarCodCenCos();
				this.generarCodPartida();
			},
			generarCodCenCos() {
				$('#cod_centro').val(
					$('#tip_cod').val().padStart('2', '0') + '.' +
					$('#cod_pryacc').val().padStart('2', '0') + '.' +
					$('#cod_obj').val().padStart('2', '0') + '.' +
					$('#gerencia').val().padStart('2', '0') + '.' +
					$('#unidad').val().padStart('2', '0')
				);
			},
			generarCodPartida() {
				$('#cod_partida').val(
					'4.' +
					$('#cod_par').val().padStart('2', '0') + '.' +
					$('#cod_gen').val().padStart('2', '0') + '.' +
					$('#cod_esp').val().padStart('2', '0') + '.' +
					$('#cod_sub').val().padStart('2', '0')
				);
			}
		}
	}
</script>