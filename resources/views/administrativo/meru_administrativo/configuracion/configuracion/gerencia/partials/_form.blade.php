<x-card x-data="handler()">
	<x-slot name="header">
		<h3 class="card-title text-bold">Gerencia</h3>
	</x-slot>

	<x-slot name="body">

		<div class="row col-12">
			<div class="form-group col-8 offset-1">
				<x-label for="gerencia">Nombre</x-label>
				<x-input class="form-control-sm {{ $errors->has('gerencia') ? 'is-invalid' : '' }}" name="gerencia" value="{{ old('gerencia', $gerencia->des_ger) }}" maxlength="500" required/>

				@error('gerencia')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>

			<div class="form-group col-2">
				<x-label for="nomenclatura">Nomenclatura</x-label>
				<x-input class="text-center form-control-sm {{ $errors->has('nomenclatura') ? 'is-invalid' : '' }}" name="nomenclatura" value="{{ old('nomenclatura', $gerencia->nomenclatura) }}" maxlength="3" required/>

				@error('nomenclatura')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>
		</div>

		<div class="row col-12">
			<div class="form-group col-3 offset-1">
				<x-label for="jefe">Jefe / Responsable</x-label>
				<x-input class="text-center form-control-sm {{ $errors->has('jefe') ? 'is-invalid' : '' }}" name="jefe" value="{{ old('jefe', $gerencia->nom_jefe) }}" maxlength="60"/>

				@error('jefe')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>

			<div class="form-group col-4">
				<x-label for="cargo_jefe">Cargo</x-label>
				<x-input class="text-center form-control-sm {{ $errors->has('cargo_jefe') ? 'is-invalid' : '' }}" name="cargo_jefe" value="{{ old('cargo_jefe', $gerencia->car_jefe) }}" maxlength="60"/>

				@error('cargo_jefe')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>

			<div class="form-group col-3">
				<x-label for="correo_jefe">Correo Electrónico</x-label>
				<x-input class="text-center form-control-sm {{ $errors->has('correo_jefe') ? 'is-invalid' : '' }}" name="correo_jefe" value="{{ old('correo_jefe', $gerencia->correo_jefe) }}" maxlength="60"/>

				@error('correo_jefe')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>
		</div>

		<div class="row col-12">
			<div class="form-group col-4 offset-1">
				<x-label for="centro_costo">Centro de Costo</x-label>
				<select name="centro_costo" id="centro_costo" class="form-control form-control-sm select2bs4 {{ $errors->has('centro_costo') ? 'is-invalid' : '' }}" required {{ $gerencia->id ? 'disabled' : '' }}>
					<option value="">Seleccione...</option>
					@foreach ($centrosCosto as $cecoItem)
						<option value="{{ $cecoItem->id }}" @selected(old('centro_costo', $gerencia->centro_costo_id) == $cecoItem->id)>{{ $cecoItem->cod_cencosto . ' - ' . $cecoItem->des_con }}</option>
					@endforeach
				</select>

				@error('centro_costo')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>

			<div class="form-group col-3">
				<x-label for="viaticos_nac">Estructura de Gastos Viaticos Nac.</x-label>
				<select name="viaticos_nac" id="viaticos_nac" class="form-control form-control-sm select2bs4 {{ $errors->has('viaticos_nac') ? 'is-invalid' : '' }}" {{ $gerencia->id ? 'disabled' : '' }}>
					<option value="">Seleccione...</option>
					@foreach ($partidas as $partidaItem)
						<option value="{{ $partidaItem->id }}" @selected(old('viaticos_nac', $gerencia->part_gasto_id) == $partidaItem->id)>{{ $partidaItem->cod_cta . ' - ' . $partidaItem->des_con }}</option>
					@endforeach
				</select>

				@error('viaticos_nac')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>

			<div class="form-group col-3">
				<x-label for="viaticos_internac">Estructura de Gastos Viaticos Int.</x-label>
				<select name="viaticos_internac" id="viaticos_internac" class="form-control form-control-sm select2bs4 {{ $errors->has('viaticos_internac') ? 'is-invalid' : '' }}" {{ $gerencia->id ? 'disabled' : '' }}>
					<option value="">Seleccione...</option>
					@foreach ($partidas as $partidaItem)
						<option value="{{ $partidaItem->id }}" @selected(old('viaticos_internac', $gerencia->part_gasto_vinternac_id) == $partidaItem->id)>{{ $partidaItem->cod_cta . ' - ' . $partidaItem->des_con }}</option>
					@endforeach
				</select>

				@error('viaticos_internac')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>
		</div>

		<div class="row col-12">
			<div class="form-group col-2 offset-1">
				<x-label for="centro_costo_ant">Centro de Costo Ant.</x-label>
				<x-input class="text-center form-control-sm" name="centro_costo_ant" value="{{ old('centro_costo_ant', $gerencia->centro_costo_anterior) }}" readonly/>
			</div>

			<div class="form-group col-4 offset-1 text-center">
				<div class="custom-control custom-switch">
					<x-label for="centro_costo_ant">¿Aplica Pre-compromiso?</x-label><br>
					<input type="checkbox" class="custom-control-input" id="aplica_pre" name="aplica_pre" value="Si" @checked(old('aplica_pre', $gerencia->aplica_pre) == 'SI') >
					<label class="custom-control-label" for="aplica_pre">SI</label>
				</div>
			</div>

			<div class="form-group col-3 text-center">
				<div class="custom-control custom-switch">
					<x-label for="centro_costo_ant">¿Activa?</x-label><br>
					<input type="checkbox" class="custom-control-input" id="estado" name="estado" value="Activo" @checked(old('estado', $gerencia->status) == 'ACTIVA')>
					<label class="custom-control-label" for="estado">SI</label>
				</div>
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
				this.generarEstructura();
			},
			generarEstructura() {
				let ceco       = $('#centro_costo').val();
				let partida    = $('#partida_presupuestaria').val();
				let estructura = (ceco != '' && partida != '') ? ceco + partida.substr(1) : '';
		    	$('#estructura').val(estructura);
			}
		}
	}
</script>