
    <x-card>
        <x-slot:header>
            <h3 class="card-title text-bold">Asignar Permiso a Rol</h3>
        </x-slot>

        <x-slot name="body">
            <div class="row col-12">
                <x-field class="form-group col-2 offset-1">
                    <x-label for="id">Id</x-label>
                    <x-input  class="form-control-sm " wire:model="iden" readonly/>
                </x-field>

                <x-field class="form-group col-6">
                    <x-label for="name">Descripción</x-label>
                    <x-input class="form-control-sm " wire:model="name" readonly/>
                </x-field>
            </div>
            <div class="row mb-2">
                <x-field class="form-group col-5 offset-1">
                    <x-label for="name">Modulo</x-label>
                    <x-select  wire:change="addPermiso" wire:model="selectedModuloId"  class="form-control-sm {{ $errors->has('selectedModuloId') ? 'is-invalid' : '' }}">
                        <option value="">-- Seleccione Modulo --</option>
                        @foreach ($this->Modulo as $index => $Modulo)
                            <option value="{{ $index }}">
                                {{ ($Modulo) }}
                            </option>
                        @endforeach
                    </x-select>
                    <div class="invalid-feedback">
                        @error('selectedModuloId') {{ $message }} @enderror
                    </div>
                </x-field>
            </div>
            <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
            <div class="mb-2">
                <table  class="table table-bordered table-sm text-center " >
                    <thead class="">
                        <tr class="table-success">
                            <th>Código</th>
                            <th>Descripción del Permiso</th>
                            <th>Accion</th>
                        </tr>
                    </thead >
                    <tbody>
                         @forelse ($permiso as $index => $permiso)
                            <tr>
                                <td class="text-center">
                                    {{ $permiso['id'] }}
                                </td>

                                <td class="text-center">
                                    {{ $permiso['name'] }}   {{  $rol->hasPermissionTo($permiso->id)}}
                                </td>

                                <td>
                                    <div class="form-check">
                                        <input wire:model="selectedRoles" wire:click="update" type="checkbox" value="{{ $permiso['id'] }}">
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No se encontraron registros.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-slot>
</x-card>

