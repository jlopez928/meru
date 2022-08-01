<x-card>
    <x-slot name="header">
        <h3 class="card-title text-bold">Tasa Cambio</h3>
    </x-slot>
    <x-slot name="body">
        <div class="row col-12">
            <x-field class="col-1">
                <x-label for="id">{{ __('ID') }}</x-label>
                <x-input name="id" value="{{ old('id', $tasacambio->id) }}" readonly/>
            </x-field>

            <x-field class="col-2">
                <x-label for="fec_tasa">Fecha Vigencia</x-label>
                <x-input name="fec_tasa" class="{{ $errors->has('fec_tasa') ? 'is-invalid' : 'is-valid' }}" type="date" placeholder="Ingrese fecha de la tasa" value="{{ old('fec_tasa', now()) }}"  />
                <div class="invalid-feedback">
                    @error('fec_tasa') {{ $message }} @enderror
                </div>
            </x-field>

            <x-field class="col-2">
                <x-label for="bs_tasa">Monto Tasa</x-label>
                <x-input name="bs_tasa" class="{{ $errors->has('bs_tasa') ? 'is-invalid' : 'is-valid' }}" placeholder="Ingrese monto tasa de Cambio" value="{{ old('bs_tasa', $tasacambio->bs_tasa) }}"  />
                <div class="invalid-feedback">
                    @error('bs_tasa') {{ $message }} @enderror
                </div>
            </x-field>

            <x-field class="col-2" >
                <x-label for="fecha">Fecha</x-label>
                <x-input name="fecha" class="{{ $errors->has('fecha') ? 'is-invalid' : 'is-valid' }}" type="datetime" placeholder="Ingrese fecha" value="{{ old('fecha', $tasacambio->fecha) }}"  readonly/>
                <div class="invalid-feedback">
                    @error('fecha') {{ $message }} @enderror
                </div>
            </x-field>
            <x-field class="col-2">
                <x-label for="sta_reg">Estado</x-label>
                <x-select name="sta_reg" class="{{ $errors->has('sta_reg') ? 'is-invalid' : 'is-valid' }}">
                        <option value="{{ old('sta_reg', $tasacambio->sta_reg) == '0' ? '0' : '1' }}" selected>{{ old('sta_reg', $tasacambio->sta_reg) == '0' ? 'INACTIVO' : 'ACTIVO' }}</option>
                        <option value="{{ old('sta_reg', $tasacambio->sta_reg) == '0' ? '1' : '0' }}"> {{ old('sta_reg', $tasacambio->sta_reg) == '0' ? 'ACTIVO' : 'INACTIVO' }}</option>
                 </x-select>
                <div class="invalid-feedback">
                    @error('sta_reg') {{ $message }} @enderror
                </div>
            </x-field>

            <x-field class="col-2" style="visibility: hidden">
                <x-label for="usuario">Usuario</x-label>
                <x-input name="usuario" class="{{ $errors->has('usuario') ? 'is-invalid' : 'is-valid' }}" placeholder="Ingrese usuario" value="{{ auth()->user()->id }}"  />
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
