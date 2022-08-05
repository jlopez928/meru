
<x-card>
    <x-slot name="header">
        <h3 class="card-title text-bold">Rol</h3>
    </x-slot>
    <x-slot name="body">
        <div class="row col-12">
            <x-field class="form-group col-2 offset-1">
                <x-label for="id">{{ __('ID') }}</x-label>
                <x-input id="id" name="id" class="text-center form-control-sm " value="{{ old('id', $rol->id) }}" readonly/>
            </x-field>

            <x-field class="form-group col-4 ">
                <x-label for="name">Descripción</x-label>
                <x-input name="name" class="form-control-sm  {{ $errors->has('name') ? 'is-invalid' : '' }}" placeholder="Ingrese Descripción" value="{{ old('name', $rol->name) }}"  />
                <div class="invalid-feedback">
                    @error('name') {{ $message }} @enderror
                </div>
            </x-field>
            <x-field class="form-group col-2 ">
                <x-label for="Estado">Status</x-label>
                <x-select name="status" class="form-control-sm  {{ $errors->has('status') ? 'is-invalid' : '' }}">
                        <option value="{{ old('status', $rol->status) == '0' ? '0' : '1' }}" selected>{{ old('status', $rol->status) == '0' ? 'INACTIVO' : 'ACTIVO' }}</option>
                        <option value="{{ old('status', $rol->status) == '0' ? '1' : '0'}}"> {{ old('status', $rol->status) == '0' ? 'ACTIVO' : 'INACTIVO' }}</option>
                 </x-select>
                <div class="invalid-feedback">
                    @error('status') {{ $message }} @enderror
                </div>
            </x-field>
        </div>
    </x-slot>
    <x-slot name="footer">
		<button type="submit" class="btn btn-sm btn-primary text-bold float-right">Guardar</button>
	</x-slot>
</x-card>
