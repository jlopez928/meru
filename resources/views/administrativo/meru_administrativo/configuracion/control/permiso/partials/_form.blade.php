
<x-card>
    <x-slot name="header">
        <h3 class="card-title text-bold">Permiso</h3>
    </x-slot>
<x-slot name="body">
    <div class="row col-12">
        <x-field class="form-group col-2 offset-1">
            <x-label for="id">{{ __('ID') }}</x-label>
            <x-input id="id" name="id" class="text-center form-control-sm " value="{{ old('id', $permiso->id) }}" readonly/>
        </x-field>
        <x-field class="form-group  col-5">
            <x-label for="modulo">Modulo</x-label>
            <x-select name="modulo_id" style="{{ $accion!='nuevo' ? 'pointer-events: none' : '' }}" class="form-control-sm  {{ $errors->has('modulo_id') ? 'is-invalid' : '' }}">
                @if($accion == 'nuevo')
                    <option value="" selected>Seleccione...</option>
                @endif
                @foreach($modulo as $moduloItem)
                    <option value="{{ $moduloItem->id  }}" {{ old('modulo_id',$permiso->modulo_id) == $moduloItem->id  ? 'selected' : ''}}>
                        {{ $moduloItem->nombre }}
                    </option>
                @endforeach
            </x-select>
            <div class="invalid-feedback">
                @error('modulo_id') {{ $message }} @enderror
            </div>
        </x-field>
        <x-field class="form-group col-2">
            <x-label for="status">Estado</x-label>
            <x-select name="status" class=" form-control-sm {{ $errors->has('status') ? 'is-invalid' : '' }}">
                    <option value="{{ old('status', $permiso->status) == '0' ? '0' : '1' }}" selected>{{ old('status', $permiso->status) == '0' ? 'INACTIVO' : 'ACTIVO' }}</option>
                    <option value="{{ old('status', $permiso->status) == '0' ? '1' : '0'}}"> {{ old('status', $permiso->status) == '0' ? 'ACTIVO' : 'INACTIVO' }}</option>
             </x-select>
            <div class="invalid-feedback">
                @error('status') {{ $message }} @enderror
            </div>
        </x-field>
    </div>
        <div class="row col-12">
        <x-field class="form-group  col-8 offset-1">
            <x-label for="name">Nombre</x-label>
            <x-input name="name" class="form-control-sm  {{ $errors->has('name') ? 'is-invalid' : '' }}" placeholder="Ingrese Descripción" value="{{ old('name', $permiso->name) }}"  />
            <div class="invalid-feedback">
                @error('name') {{ $message }} @enderror
            </div>
        </x-field>
    </div>
    <div class="row col-12">
        <x-field class="form-group  col-8 offset-1">
            <x-label for="route_name">Nombre de la Ruta</x-label>
            <x-input name="route_name" class="form-control-sm  {{ $errors->has('route_name') ? 'is-invalid' : '' }}" placeholder="Ingrese Nombre de la Ruta" value="{{ old('name', $permiso->route_name) }}"  />
            <div class="invalid-feedback">
                @error('route_name') {{ $message }} @enderror
            </div>
        </x-field>
    </div>
    <div class="row col-12">
        <x-field class="form-group  col-8 offset-1">
            <x-label for="guard_name">Nombre del Guard Name</x-label>
            <x-input name="guard_name" class="form-control-sm  {{ $errors->has('guard_name') ? 'is-invalid' : '' }}" placeholder="Ingrese Nombre del Guard Name" value="{{ $permiso->route_name ? $permiso->guard_name : 'web' }} "  />
            <div class="invalid-feedback">
                @error('guard_name') {{ $message }} @enderror
            </div>
        </x-field>
    </div>

</x-slot>

<x-slot name="footer">
    <button type="submit" class="btn btn-sm btn-primary text-bold float-right">Guardar</button>
</x-slot>

</x-card>
