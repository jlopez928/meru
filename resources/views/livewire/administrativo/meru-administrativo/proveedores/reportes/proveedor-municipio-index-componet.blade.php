<div class="row col-12">
	<div class="form-group col-3 offset-1">
		<x-label for="estado">Estado</x-label>
		<select name="estado" id="estado" class="form-control form-control-sm {{ $errors->has('centro_costo') ? 'is-invalid' : '' }}"
              wire:model="estado">
			<option value="">Seleccione...</option>
			@foreach ($estados as $edoItem)
				<option value="{{ $edoItem->cod_edo }}" >{{ $edoItem->des_ubi }}</option>
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
		<select name="municipio" id="municipio" class="form-control form-control-sm {{ $errors->has('municipio') ? 'is-invalid' : '' }}">

			@if($municipios->count() == 0)
				<option value="">Primero debe seleccionar un Estado</option>
			@else
				<option value="">Seleccione...</option>
			@endif

			@foreach ($municipios as $municipioItem)
				<option value="{{ $municipioItem->cod_mun }}" >{{ $municipioItem->des_ubi }}</option>
			@endforeach
		</select>

		@error('municipio')
			<span class="invalid-feedback" role="alert">
				{{ $message }}
			</span>
		@enderror
	</div>
    <div class="form-group col-3">
		<x-label for="tip_emp">Tipo de Empresa</x-label>
		<select name="tip_emp" id="tip_emp" class="form-control form-control-sm {{ $errors->has('tip_emp') ? 'is-invalid' : '' }}" >
            <option value="">Seleccione...</option>
			@foreach (\App\Enums\Administrativo\Meru_Administrativo\Proveedores\TipoEmpresa::cases() as $tipoEmpresa)
                <option
                    value="{{ $tipoEmpresa->value }}"
                >
                    {{ $tipoEmpresa->name }}
                </option>
            @endforeach
		    </select>
		  @error('tip_emp')
			<span class="invalid-feedback" role="alert">
				{{ $message }}
			</span>
		@enderror
	</div>
</div>
<div class="row col-12">
    <div class="form-group col-9 offset-1">
        <x-label for="objetivo">Objetivo</x-label>
        <x-input class="text-left" name="objetivo" value=""/>
    </div>
</div>
