<x-card>
    <x-slot name="header">
        <h3 class="card-title text-bold">Datos del Usuario</h3>
    </x-slot>
<x-slot name="body">
    <div class="row col-12">
        <x-field class=" form-group col-1">
            <x-label for="id">{{ __('ID') }}</x-label>
            <x-input class="form-control-sm" name="id" value="{{ old('id', $userrol->id) }}" readonly/>
        </x-field>

        <x-field class="form-group col-4">
            <x-label for="id">{{ __('Nombre') }}</x-label>
            <x-input name="id" class="form-control-sm" value="{{ old('id', $userrol->name) }}" readonly/>
        </x-field>
        <x-field class="form-group col-2">
            <x-label for="id">{{ __('Cédula') }}</x-label>
            <x-input name="id" class="form-control-sm" value="{{ old('id', $userrol->cedula) }}" readonly/>
        </x-field>
        <x-field class="form-group col-5">
            <x-label for="id">{{ __('Email') }}</x-label>
            <x-input name="id" class="form-control-sm" value="{{ old('id', $userrol->email) }}" readonly/>
        </x-field>
    </div>
    <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
      <div class="mb-2">
            <table  class="table table-bordered table-sm text-center " >
                <thead class="">
                    <tr class="table-success">
                        <th>Código</th>
                        <th>Descripción del Rol</th>
                        <th>Accion</th>
                    </tr>
                </thead >
                <tbody>
                     @forelse ($rol as $index => $rol)
                        <tr>
                            <td class="text-center">
                                {{ $rol['id'] }}
                            </td>

                            <td class="text-center">
                                {{ $rol['name'] }}
                            </td>

                            <td>
                                <div class="form-check">
                                    <input wire:model="selectedUser" wire:click="update" type="checkbox" value="{{ $rol['id'] }}">
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



