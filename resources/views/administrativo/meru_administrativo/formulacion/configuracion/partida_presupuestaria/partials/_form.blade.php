<x-card x-data="handler()">
	<x-slot name="header">
		<h3 class="card-title text-bold">Partida Presupuestaria</h3>
	</x-slot>

	<x-slot name="body">

		<!-- Divisor -->
		<div class="row col-12">
				<div class="col-12">
				<h5 class="card-title text-secondary text-bold">Datos partida</h5>
			</div>

				<div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
			</div>

		<div class="row col-12">
			<div class="form-group col-1 offset-2">
				<x-label for="tipo">Tipo</x-label>
				<input type="text" id="tipo" name="tipo" x-mask="9" class="form-control form-control-sm text-center {{ $errors->has('tipo') ? 'is-invalid' : '' }}" value="{{ old('tipo', $partidaPresupuestaria->tip_cod) }}" maxlength="1" required {{ $partidaPresupuestaria->id ? 'readonly' : '' }} @keyup="generarCodPartida()"/>

				@error('tipo')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>

			<div class="form-group col-1">
				<x-label for="partida">Partida</x-label>
				<input type="text" id="partida" name="partida" x-mask="99" class="form-control form-control-sm text-center {{ $errors->has('partida') ? 'is-invalid' : '' }}" value="{{ old('partida', $partidaPresupuestaria->cod_par) }}" maxlength="2" required {{ $partidaPresupuestaria->id ? 'readonly' : '' }} @keyup="generarCodPartida()"/>

				@error('partida')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>

			<div class="form-group col-1">
				<x-label for="generica">Genérica</x-label>
				<input type="text" id="generica" name="generica" x-mask="99" class="form-control form-control-sm text-center {{ $errors->has('generica') ? 'is-invalid' : '' }}" value="{{ old('generica', $partidaPresupuestaria->cod_gen) }}" maxlength="2" required {{ $partidaPresupuestaria->id ? 'readonly' : '' }} @keyup="generarCodPartida()"/>

				@error('generica')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>

			<div class="form-group col-1">
				<x-label for="especifica">Específica</x-label>
				<input type="text" id="especifica" name="especifica" x-mask="99" class="form-control form-control-sm text-center {{ $errors->has('especifica') ? 'is-invalid' : '' }}" value="{{ old('especifica', $partidaPresupuestaria->cod_esp) }}" maxlength="2" required {{ $partidaPresupuestaria->id ? 'readonly' : '' }} @keyup="generarCodPartida()"/>

				@error('especifica')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>

			<div class="form-group col-1">
				<x-label for="subespecifica">Sub-específica</x-label>
				<input type="text" id="subespecifica" name="subespecifica" x-mask="99" class="form-control form-control-sm text-center {{ $errors->has('subespecifica') ? 'is-invalid' : '' }}" value="{{ old('subespecifica', $partidaPresupuestaria->cod_sub) }}" maxlength="2" required {{ $partidaPresupuestaria->id ? 'readonly' : '' }} @keyup="generarCodPartida()"/>

				@error('subespecifica')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>

			<div class="form-group col-3">
				<x-label for="cod_partida">Código Partida</x-label>
				<x-input class="text-center form-control-sm {{ $errors->has('cod_partida') ? 'is-invalid' : '' }}" name="cod_partida" value="{{ old('cod_partida', $partidaPresupuestaria->cod_cta) }}" readonly/>

				@error('cod_partida')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>
		</div>

		<div class="row col-12">
			<div class="form-group col-8 offset-2">
				<x-label for="descripcion">Descripción</x-label>
				<x-input name="descripcion" class="form-control-sm {{ $errors->has('descripcion') ? 'is-invalid' : '' }}" placeholder="Ingrese descripción" value="{{ old('descripcion', $partidaPresupuestaria->des_con) }}" maxlength="500" required/>

				@error('descripcion')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>
		</div>

		<!-- Divisor -->
		<div class="row col-12">
			<div class="col-12">
				<h5 class="card-title text-secondary text-bold">Partida asociada - Efectos por pagar</h5>
			</div>

			<div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
		</div>

		<div class="row col-12">
			<div class="form-group col-4 offset-4">
				<x-label for="partida_asociada">Código Partida</x-label>
				<x-select name="partida_asociada" class="form-control-sm select2bs4 {{ $errors->has('partida_asociada') ? 'is-invalid' : '' }} required">
					<option value="">Seleccione...</option>
					@foreach ($partidas411 as $partidaItem)
						<option value="{{ $partidaItem->cod_cta }}" @selected(old('partida_asociada', $partidaPresupuestaria->part_asociada) == $partidaItem->cod_cta)>{{ $partidaItem->cod_cta . ' - ' . $partidaItem->des_con }}</option>
					@endforeach
				</x-select>

				@error('partida_asociada')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>
		</div>

	</x-slot>

	<x-slot name="footer">
		<button type="submit" class="btn btn-sm btn-primary text-bold float-right">Guardar</button>
	</x-slot>

</x-card>

<script type="text/javascript">
	function handler() {
		return {
			init() {
				this.generarCodPartida();
			},
			generarCodPartida() {
				$('#cod_partida').val(
					$('#tipo').val().padStart('1', '0') + '.' +
					$('#partida').val().padStart('2', '0') + '.' +
					$('#generica').val().padStart('2', '0') + '.' +
					$('#especifica').val().padStart('2', '0') + '.' +
					$('#subespecifica').val().padStart('2', '0')
				);
			}
		}
	}
</script>