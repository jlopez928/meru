<x-form wire:submit.prevent="update">
    <x-card>

        <x-slot:header>
            <h3 class="card-title text-bold">Proveedor</h3>
        </x-slot>

        <x-slot:body>
            <div class="row col-12">
                    <x-field class="col-2">
                        <x-label for="rif_prov">Rif</x-label>
                        <x-input class="form-control-sm" wire:model="rif_prov" readonly/>
                    </x-field>

                    <x-field class="col-6">
                        <x-label for="nom_prov">Nombre</x-label>
                            <x-input class="form-control-sm" wire:model="nom_prov" readonly />
                    </x-field>
            </div>

            <div class="row col-12 mt-3">
                <x-card class="card-secondary col-12">
                    <x-slot name="header" >
                        <h3 class="card-title text-bold" >Ramos</h3>
                    </x-slot>

                    <x-slot name="body">
                        <div>
                            <div class="row mb-2">
                                <x-field class="col-5">
                                    <x-select wire:model="selectedRamoId"  class="form-control-sm {{ $errors->has('selectedRamoId') ? 'is-invalid' : 'is-valid' }}">
                                        <option value="">-- Seleccione Ramo --</option>
                                        @foreach ($this->ramo as $index => $ramo)
                                            <option value="{{ $index }}">
                                                ({{ $index }}) {{  ($ramo) }}
                                            </option>
                                        @endforeach
                                    </x-select>
                                    <div class="invalid-feedback">
                                        @error('selectedRamoId') {{ $message }} @enderror
                                    </div>
                                </x-field>
                                <x-field class="col-1">
                                    <a class="btn-sm" type="button" wire:click.prevent="addRamo" title="Agregar Ramo">
                                        <i class="fa fa-plus-circle fa-lg" aria-hidden="true"></i>
                                    </a>
                                </x-field>
                            </div>

                            <div class="mb-2">
                                <table  class="table table-bordered table-sm text-center " >
                                    <thead class="">
                                        <tr class="table-success">
                                            <th>Código</th>
                                            <th>Descripción</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead >
                                        <tbody>
                                            @forelse ($ramos as $index => $ramo)
                                                <tr>
                                                    <td class="text-center">
                                                        {{ $ramo['cod_ram'] }}
                                                    </td>

                                                    <td class="text-center">
                                                        {{ $ramo['des_ram'] }}
                                                    </td>

                                                    <td>
                                                        <a class="btn-sm" type="button" wire:click="confirmDeleteRamo({{ $ramo['cod_ram'] }})" title="Eliminar Ramo">
                                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                                        </a>
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
                        </div>

                    </x-slot>
                </x-card>
            </div>
        </x-slot>

    </x-card>
</x-form>

@push('scripts')
    <script>
        Livewire.on('swal:alert', param => {
            Swal.fire({
                title: param['titulo'],
                text: param['mensaje'],
                icon: param['tipo'],
            })
        })

        Livewire.on('swal:confirm', param => {
            Swal.fire({
                title: param['titulo'],
                text: param['mensaje'],
                icon: param['tipo'],
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                confirmButtonText: '¡Sí!',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if(result.isConfirmed){
                    Livewire.emitTo('administrativo.meru-administrativo.proveedores.proceso.ramo-proveedor-edit', param['funcion'], param['cod_ram'])
                }
            })
        })
    </script>
@endpush