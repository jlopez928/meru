<x-card>
    <x-slot name="header">
        <h3 class="card-title text-bold">Unidad Tributaria</h3>
    </x-slot>
    <x-slot name="body">
        <div class="row col-12">
            <x-field class="form-group col-3 offset-1">
                <x-label for="id">{{ __('ID') }}</x-label>
                <x-input readonly  class="text-center form--control-sm "  name="id" value="{{ old('id', $unidadtributarium->id) }}" />
            </x-field>
            <x-field class="form-group col-3 ">
                <x-label for="bs_ut">Monto UT</x-label>
                <x-input name="bs_ut" class="form-control-sm {{ $errors->has('bs_ut') ? 'is-invalid' : '' }}" placeholder="Ingrese monto UT" value="{{ old('bs_ut', $unidadtributarium->bs_ut) }}"   />
                <div class="invalid-feedback">
                    @error('bs_ut') {{ $message }} @enderror
                </div>
            </x-field>
            <x-field class="form-group col-3 ">
                <x-label for="bs_ucau">Monto UCAU</x-label>
                <x-input name="bs_ucau" class="form-control-sm {{ $errors->has('bs_ucau') ? 'is-invalid' : '' }}" placeholder="Ingrese costo UT UCAU" value="{{ old('bs_ucau', $unidadtributarium->bs_ucau) }}"  />
                <div class="invalid-feedback">
                    @error('bs_ucau') {{ $message }} @enderror
                </div>
            </x-field>
            <x-field class="form-group col-3 offset-1">
                <x-label for="usuario">Usuario</x-label>
                <x-input name="usuario" readonly  class="form-control-sm" value="{{  $unidadtributarium->usuario  ? $unidadtributarium->usuario : auth()->user()->name}}"  />
                <div class="invalid-feedback">
                    @error('usuario') {{ $message }} @enderror
                </div>
            </x-field>
            <x-field class="form-group col-3 ">
                <x-label for="fec_ut">Fecha Vigencia</x-label>
                <x-input name="fec_ut" class="form-control-sm {{ $errors->has('fec_ut') ? 'is-invalid' : '' }}" type="date" placeholder="Ingrese fecha vigencia" value="{{ old('fec_ut', $unidadtributarium->fec_ut) }}"  />
                <div class="invalid-feedback">
                    @error('fec_ut') {{ $message }} @enderror
                </div>
            </x-field>
            <x-field class="form-group col-3 ">
                <x-label for="vigente">Estado</x-label>
                <x-select  name="vigente" class="form-control-sm {{ $errors->has('vigente') ? 'is-invalid' : '' }}">
                    <option value="{{ old('vigente', $unidadtributarium->vigente) == '0' ? '0' : '1' }}" selected>{{ old('vigente', $unidadtributarium->vigente) == '0' ? 'INACTIVO' : 'ACTIVO' }}</option>
                    <option value="{{ old('vigente', $unidadtributarium->vigente) == '0' ? '1' : '0'}}"> {{ old('vigente', $unidadtributarium->vigente) == '0' ? 'ACTIVO' : 'INACTIVO' }}</option>
                  </x-select>
                <div class="invalid-feedback">
                    @error('vigente') {{ $message }} @enderror
                </div>
            </x-field>
        </div>
    </x-slot>

    <x-slot name="footer">
		<button type="submit" class="btn btn-sm btn-primary text-bold float-right">Guardar</button>
	</x-slot>
</x-card>
