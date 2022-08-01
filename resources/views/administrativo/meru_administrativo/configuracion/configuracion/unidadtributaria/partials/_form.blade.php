<x-card>
    <x-slot name="header">
        <h3 class="card-title text-bold">Unidad Tributaria</h3>
    </x-slot>
    <x-slot name="body">
        <div class="row col-12">
            <x-field class="col-1">
                <x-label for="id">{{ __('ID') }}</x-label>
                <x-input name="id" value="{{ old('id', $unidadtributarium->id) }}" readonly/>
            </x-field>

            <x-field class="col-3">
                <x-label for="fec_ut">Fecha Vigencia</x-label>
                <x-input name="fec_ut" class="{{ $errors->has('fec_ut') ? 'is-invalid' : 'is-valid' }}" type="date" placeholder="Ingrese fecha vigencia" value="{{ old('fec_ut', $unidadtributarium->fec_ut) }}"  />
                <div class="invalid-feedback">
                    @error('fec_ut') {{ $message }} @enderror
                </div>
            </x-field>

            <x-field class="col-3">
                <x-label for="bs_ut">Monto UT</x-label>
                <x-input name="bs_ut" class="{{ $errors->has('bs_ut') ? 'is-invalid' : 'is-valid' }}" placeholder="Ingrese monto UT" value="{{ old('bs_ut', $unidadtributarium->bs_ut) }}"  />
                <div class="invalid-feedback">
                    @error('bs_ut') {{ $message }} @enderror
                </div>
            </x-field>

            <x-field class="col-3">
                <x-label for="bs_ucau">Monto UCAU</x-label>
                <x-input name="bs_ucau" class="{{ $errors->has('bs_ucau') ? 'is-invalid' : 'is-valid' }}" placeholder="Ingrese costo UT UCAU" value="{{ old('bs_ucau', $unidadtributarium->bs_ucau) }}"  />
                <div class="invalid-feedback">
                    @error('bs_ucau') {{ $message }} @enderror
                </div>
            </x-field>
            <x-field class="col-2">
                <x-label for="vigente">Estado</x-label>
                <x-select name="vigente" class="{{ $errors->has('vigente') ? 'is-invalid' : 'is-valid' }}">
                    <option value="{{ old('vigente', $unidadtributarium->vigente) == '0' ? '0' : '1' }}" selected>{{ old('vigente', $unidadtributarium->vigente) == '0' ? 'INACTIVO' : 'ACTIVO' }}</option>
                    <option value="{{ old('vigente', $unidadtributarium->vigente) == '0' ? '1' : '0'}}"> {{ old('vigente', $unidadtributarium->vigente) == '0' ? 'ACTIVO' : 'INACTIVO' }}</option>
                  </x-select>
                <div class="invalid-feedback">
                    @error('vigente') {{ $message }} @enderror
                </div>
            </x-field>
        </div>
            <div class="row col-12">
                <x-field class="col-2" style="visibility: hidden">
                    <x-label for="usuario" style="visibility: none">Usuario</x-label>
                    <x-input name="usuario" class="{{ $errors->has('usuario') ? 'is-invalid' : 'is-valid' }}" placeholder="Ingrese usuario" value="{{ auth()->user()->id }}"  Readonly />
                    <div class="invalid-feedback">
                        @error('usuario') {{ $message }} @enderror
                    </div>
                </x-field>
            </div>
    </x-slot>

    <x-slot name="footer">
		<button type="submit" class="btn btn-sm btn-primary text-bold float-right">Guardar</button>
	</x-slot>
</x-card>
