<x-card x-data="handler()">
	<x-slot name="header">
		<h3 class="card-title text-bold">Permiso de Traspasos</h3>
	</x-slot>

	<x-slot name="body">

		<div class="row col-12">

			<div class="form-group col-4">
				<x-label for="usuario_id">Usuario</x-label>
				<select name="usuario_id" id="usuario_id" class="form-control form-control-sm select2bs4 {{ $errors->has('usuario_id') ? 'is-invalid' : '' }}" required {{ $permisoTraspaso->usuario_id ? 'disabled' : '' }}>
					<option value="">Seleccione...</option>
					@foreach ($usuarios as $usuarioItem)
						<option value="{{ $usuarioItem->id }}" @selected(old('usuario_id', $permisoTraspaso->usuario_id) == $usuarioItem->id)>{{ $usuarioItem->name }}</option>
					@endforeach
				</select>

				{{-- Si es editar crear un input hidden para guardar el usuario_id --}}
				@if (!is_null($permisoTraspaso->usuario_id))
					<x-input type="hidden" name="usuario_id" value="{{ $permisoTraspaso->usuario_id }}"/>
				@endif

				@error('usuario_id')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>

			<div class="form-group col-4">
				<x-label for="maxut">Max. Unidad Tributaria</x-label>
				<x-input class="text-center {{ $errors->has('maxut') ? 'is-invalid' : '' }}" name="maxut" value="{{ old('maxut', $permisoTraspaso->maxut) }}" x-mask="99999999999999"/>

				@error('maxut')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>

            <div class="form-group col-4 text-center">
                <div class="custom-control custom-switch">
                    <x-label for="exceder_pago">¿Traspasos entre diferentes Centros?</x-label><br>
                    <input type="checkbox" class="custom-control-input" id="multicentro" name="multicentro" value="true" @checked(old('multicentro', $permisoTraspaso->multicentro) == 'true')>
                    <label class="custom-control-label" for="multicentro">Permitir</label>
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
				//
			},
		}
	}
</script>