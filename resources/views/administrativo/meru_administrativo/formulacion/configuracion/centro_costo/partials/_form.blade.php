<x-card x-data="handler()">
	<x-slot name="header">
		<h3 class="card-title text-bold">Centro de Costo</h3>
	</x-slot>

	<x-slot name="body">

		<div class="row col-12">

			<div class="form-group col-2 offset-1">
				<x-label for="ano_pro">Año</x-label>
				<x-input class="text-center" name="ano_pro" value="{{ old('ano_pro', $centroCosto->ano_pro) }}" readonly/>
			</div>

			<div class="form-group col-4">
				<x-label for="cod_centro">Código Centro</x-label>
				<x-input class="text-center {{ $errors->has('cod_centro') ? 'is-invalid' : '' }}" name="cod_centro" value="{{ old('cod_centro', $centroCosto->cod_cencosto) }}" readonly/>

				@error('cod_centro')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>
		</div>

		<div class="row col-12">
			<div class="form-group col-2 offset-1">
				<x-label for="tipo">Tipo</x-label>
				<input type="text" id="tipo" name="tipo" x-mask="99" class="form-control form-control-sm text-center {{ $errors->has('tipo') ? 'is-invalid' : '' }}" placeholder="Ingrese tipo" value="{{ old('tipo', $centroCosto->tip_cod) }}" maxlength="2" required {{ $centroCosto->id ? 'readonly' : '' }} @keyup="generarCodCenCos()"/>

				@error('tipo')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>

			<div class="form-group col-2">
				<x-label for="proyecto">Proyecto / Acción</x-label>
				<input type="text" id="proyecto" name="proyecto" x-mask="99" class="form-control form-control-sm text-center {{ $errors->has('proyecto') ? 'is-invalid' : '' }}" placeholder="Ingrese proyecto" value="{{ old('proyecto', $centroCosto->cod_pryacc) }}" maxlength="2" required {{ $centroCosto->id ? 'readonly' : '' }} @keyup="generarCodCenCos()"/>

				@error('proyecto')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>

			<div class="form-group col-2">
				<x-label for="objetivo">Objetivo específico</x-label>
				<input type="text" id="objetivo" name="objetivo" x-mask="99" class="form-control form-control-sm text-center {{ $errors->has('objetivo') ? 'is-invalid' : '' }}" placeholder="Ingrese objetivo" value="{{ old('objetivo', $centroCosto->cod_obj) }}" maxlength="2" required {{ $centroCosto->id ? 'readonly' : '' }} @keyup="generarCodCenCos()"/>

				@error('objetivo')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>

			<div class="form-group col-2">
				<x-label for="gerencia">Gerencia</x-label>
				<input type="text" id="gerencia" name="gerencia" x-mask="99" class="form-control form-control-sm text-center {{ $errors->has('gerencia') ? 'is-invalid' : '' }}" placeholder="Ingrese gerencia" value="{{ old('gerencia', $centroCosto->gerencia) }}" maxlength="2" required {{ $centroCosto->id ? 'readonly' : '' }} @keyup="generarCodCenCos()"/>

				@error('gerencia')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>

			<div class="form-group col-2">
				<x-label for="unidad">Unidad Ejecutora</x-label>
				<input type="text" id="unidad" name="unidad" x-mask="99" class="form-control form-control-sm text-center {{ $errors->has('unidad') ? 'is-invalid' : '' }}" placeholder="Ingrese unidad" value="{{ old('unidad', $centroCosto->unidad) }}" maxlength="2" required {{ $centroCosto->id ? 'readonly' : '' }} @keyup="generarCodCenCos()"/>

				@error('unidad')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>
		</div>

		<div class="row col-12">
			<div class="form-group col-10 offset-1">
				<x-label for="descripcion">Descripción</x-label>
				<x-input name="descripcion" class="form-control-sm {{ $errors->has('descripcion') ? 'is-invalid' : '' }}" placeholder="Ingrese descripción" value="{{ old('descripcion', $centroCosto->des_con) }}" maxlength="500" required/>

				@error('descripcion')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>
		</div>

		@if (empty($centroCosto->id))

			<div class="row col-12">
				<div class="form-group col-3 offset-2 text-center">
					<div class="custom-control custom-switch">
						<input type="checkbox" class="custom-control-input" id="estado" name="estado" value="Activo" @checked(old('estado', $centroCosto->sta_reg) == 'ACTIVO')>
						<label class="custom-control-label" for="estado">¿Activo?</label>
					</div>
				</div>

				<div class="form-group col-3 text-center">
					<div class="custom-control custom-switch">
						<input type="checkbox" class="custom-control-input" id="credito_adicional" name="credito_adicional" value="SI" @checked(old('credito_adicional', $centroCosto->cre_adi) == 'SI') @click="siglasReadonly = !siglasReadonly">
						<label class="custom-control-label" for="credito_adicional">¿Crédito Adicional?</label>
					</div>
				</div>

				<div class="form-group col-2 text-center">
					<x-label for="siglas">Siglas</x-label>
					<input type="text" id="siglas" name="siglas" class="form-control form-control-sm text-center {{ $errors->has('siglas') ? 'is-invalid' : '' }}" placeholder="Ingrese siglas" value="{{ old('siglas', $centroCosto->unidad) }}" maxlength="3" required  x-bind:readonly="siglasReadonly"/>
				</div>
				
			</div>

		@else

			<div class="row col-12">
				<div class="form-group col-3 offset-2 text-center">
					<x-label for="estado">Estado</x-label><br>
					<b>
						<span name="estado" class="{{ $centroCosto->sta_reg == 'ACTIVO' ? 'text-success' : 'text-danger' }}">
							{{ $centroCosto->sta_reg }}
						</span>
					</b>
				</div>

				<div class="form-group col-3 text-center">
					<x-label for="credito_adicional">¿Crédito Adicional?</x-label><br>
					<b>
						<span name="credito_adicional" class="{{ $centroCosto->cre_adi == 'Si' ? 'text-success' : 'text-danger' }}">
							{{ $centroCosto->cre_adi }}
						</span>
					</b>
				</div>

				<div class="form-group col-2 text-center">
					<x-label for="siglas">Siglas</x-label>
					<input type="text" id="siglas" name="siglas" class="form-control form-control-sm text-center placeholder="Ingrese siglas" value="{{ $centroCosto->cre_adi == 'Si' ? $centroCosto->gerencias()->first()->nomenclatura : '' }}" maxlength="3" readonly />
				</div>
			</div>

		@endif

	</x-slot>

	<x-slot name="footer">
		<button type="submit" class="btn btn-sm btn-primary text-bold float-right">Guardar</button>
	</x-slot>

</x-card>

<script type="text/javascript">
	function handler() {
		return {
			'siglasReadonly' : true,
			init() {
				this.generarCodCenCos();
				this.siglasReadonly = !$('#credito_adicional').is(':checked');
			},
			generarCodCenCos() {
				$('#cod_centro').val(
					$('#tipo').val().padStart('2', '0') + '.' +
					$('#proyecto').val().padStart('2', '0') + '.' +
					$('#objetivo').val().padStart('2', '0') + '.' +
					$('#gerencia').val().padStart('2', '0') + '.' +
					$('#unidad').val().padStart('2', '0')
				);
			}
		}
	}
</script>