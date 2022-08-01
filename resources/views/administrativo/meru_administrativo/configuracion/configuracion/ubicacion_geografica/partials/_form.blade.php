<x-card>
	<x-slot name="header">
		<h3 class="card-title text-bold">Ubicación Geográfica</h3>
	</x-slot>

	<x-slot name="body">

		@if($ubicacionGeografica->id)

			<div class="row col-12">

				<div class="form-group col-3 offset-1">
					<x-label for="estado">Estado</x-label>
					<select name="estado" id="estado" class="form-control form-control-sm {{ $errors->has('centro_costo') ? 'is-invalid' : '' }}" disabled>
						<option value="">Seleccione...</option>
						@foreach ($estados as $edoItem)
							<option value="{{ $edoItem->cod_edo }}" @selected($edoItem->cod_edo == $ubicacionGeografica->cod_edo)>{{ $edoItem->des_ubi }}</option>
						@endforeach
					</select>

					@error('estado')
						<span class="invalid-feedback" role="alert">
							{{ $message }}
						</span>
					@enderror
				</div>

				<div class="form-group col-3">
					<x-label for="municipio">Municipio</x-label>
					<select name="municipio" id="municipio" class="form-control form-control-sm {{ $errors->has('municipio') ? 'is-invalid' : '' }}" disabled>
						<option value="">...</option>

						@foreach ($municipios as $municipioItem)
							<option value="{{ $municipioItem->cod_mun }}" @selected($municipioItem->cod_mun == $ubicacionGeografica->cod_mun)>{{ $municipioItem->des_ubi }}</option>
						@endforeach
					</select>

					@error('municipio')
						<span class="invalid-feedback" role="alert">
							{{ $message }}
						</span>
					@enderror
				</div>

				<div class="form-group col-3">
					<x-label for="parroquia">Parroquia</x-label>
					<select name="parroquia" id="parroquia" class="form-control form-control-sm {{ $errors->has('parroquia') ? 'is-invalid' : '' }}" disabled>
						<option value="">...</option>

						@foreach ($parroquias as $parroquiaItem)
							<option value="{{ $parroquiaItem->cod_par }}" @selected($parroquiaItem->cod_par == $ubicacionGeografica->cod_par)>{{ $parroquiaItem->des_ubi }}</option>
						@endforeach
					</select>

					@error('parroquia')
						<span class="invalid-feedback" role="alert">
							{{ $message }}
						</span>
					@enderror
				</div>

			</div>
			
		@else

			<livewire:administrativo.meru-administrativo.configuracion.configuracion.ubicacion-geografica-dependientes />

		@endif

		<div class="row col-12">
			<div class="form-group col-3 offset-1">
				<x-label for="descripcion">Descripción</x-label>
				<x-input class="form-control-sm {{ $errors->has('descripcion') ? 'is-invalid' : '' }}" name="descripcion" value="{{ old('descripcion', $ubicacionGeografica->des_ubi) }}"  maxlength="40" required/>

				@error('descripcion')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>

			<div class="form-group col-3 ">
				<x-label for="capital">Capital</x-label>
				<x-input class="form-control-sm {{ $errors->has('capital') ? 'is-invalid' : '' }}" name="capital" value="{{ old('capital', $ubicacionGeografica->capital) }}" maxlength="40"/>

				@error('capital')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>

			<div class="form-group col-1">
				<x-label for="codigo">Código</x-label>
				<x-input class="text-center form-control-sm {{ $errors->has('codigo') ? 'is-invalid' :'' }}" name="codigo" value="{{ old('codigo', $ubicacionGeografica->cod_ubi) }}" maxlength="5" required/>

				@error('codigo')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>

		</div>

	</x-slot>

	<x-slot name="footer">
		<button id="guardar" name="guardar" type="submit" class="btn btn-sm btn-primary text-bold float-right">Guardar</button>
	</x-slot>

</x-card>