<x-card>
    <x-slot name="header">
        <h3 class="card-title text-bold">Descuento</h3>
    </x-slot>

    <x-slot name="body">
        <div class="row col-12">
           <x-field class="col-1">
                <x-label for="id">{{ __('ID') }}</x-label>
                <x-input name="id" value="{{ old('id', $descuento->id) }}" readonly/>
            </x-field>

            <x-field class="col-1">
                <x-label for="cod_des">Código</x-label>
                <x-input name="cod_des" class="{{ $errors->has('cod_des') ? 'is-invalid' : 'is-valid' }}" type="text" placeholder="Ingrese código de descuento" value="{{ old('cod_des', $descuento->cod_des) }}"  />
                <div class="invalid-feedback">
                    @error('cod_des') {{ $message }} @enderror
                </div>
            </x-field>

            <x-field class="col-10">
                <x-label for="des_des">Descripción</x-label>
                <x-input name="des_des" class="{{ $errors->has('des_des') ? 'is-invalid' : 'is-valid' }}" type="text" placeholder="Ingrese descripción" value="{{ old('des_des', $descuento->des_des) }}"  />
                <div class="invalid-feedback">
                    @error('des_des') {{ $message }} @enderror
                </div>
            </x-field>
            {{--  se incluyen los combos que actualizan el código a traves de un componente  --}}
            <livewire:administrativo.meru-administrativo.configuracion.configuracion.select-input :descuento="$descuento"/>
             <x-field class="col-3">
                <x-label for="por_islr">Porc. I.S.L.R.</x-label>
                <x-input name="por_islr" class="{{ $errors->has('por_islr') ? 'is-invalid' : 'is-valid' }}" placeholder="Ingrese Porc. I.S.L.R." value="{{ old('por_islr', $descuento->por_islr) }}"   />
                <div class="invalid-feedback">
                    @error('por_islr') {{ $message }} @enderror
                </div>
            </x-field>
            <x-field class="col-2">
                <x-label for="status">Estado</x-label>
                <x-select   name="status" class="{{ $errors->has('status') ? 'is-invalid' : 'is-valid' }}">
                    <option value="{{ old('status', $descuento->status) == '0' ? '0' : '1' }}" selected>{{ old('status', $descuento->status) == '0' ? 'INACTIVO' : 'ACTIVO' }}</option>
                    <option value="{{ old('status', $descuento->status) == '0' ? '1' : '0'}}"> {{ old('status', $descuento->status) == '0' ? 'ACTIVO' : 'INACTIVO' }}</option>
             </x-select>
                <div class="invalid-feedback">
                    @error('status') {{ $message }} @enderror
                </div>
            </x-field>

            <x-field class="col-2"  style="visibility: hidden">
                <x-label for="id_des">Usuario</x-label>
                <x-input name="usuario" class="{{ $errors->has('id_des') ? 'is-invalid' : 'is-valid' }}" placeholder="Ingrese costo UT UCAU" value="{{ auth()->user()->id }}"  />
                <div class="invalid-feedback">
                    @error('usuario') {{ $message }} @enderror
                </div>
            </x-field>



            <x-field class="col-2"  style="visibility: hidden">
                <x-label for="id_des">id_des</x-label>
                <x-input name="id_des" class="{{ $errors->has('id_des') ? 'is-invalid' : 'is-valid' }}" value="{{ old('id_des', '')  }}"  />
                <div class="invalid-feedback">
                    @error('id_des') {{ $message }} @enderror
                </div>
            </x-field>
        </div>
    </x-slot>

    <x-slot name="footer">
		<button type="submit" class="btn btn-sm btn-primary text-bold float-right">Guardar</button>
	</x-slot>
</x-card>
