<x-form wire:submit.prevent="update">
    <x-card>

        <x-slot:header>
            <h3 class="card-title text-bold">Proveedor</h3>
        </x-slot>

        <x-slot:body>
            <div class="row col-12">
                    <x-field class="col-2">
                        <x-label for="rif_prov">Rif</x-label>
                        <x-input wire:model="rif_prov" readonly/>
                    </x-field>

                    <x-field class="col-6">
                        <x-label for="nom_prov">Nombre</x-label>
                            <x-input wire:model="nom_prov" readonly />
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
                                    <x-select wire:model="selectedRamoId"  class="{{ $errors->has('selectedRamoId') ? 'is-invalid' : 'is-valid' }}">
                                        <option value="">-- Seleccione Ramo --</option>
                                        @foreach ($this->ramo as $index => $ramo)
                                            <option value="{{ $index }}">
                                                {{ $index }}-{{  ($ramo) }}
                                            </option>
                                        @endforeach
                                    </x-select>
                                    <div class="invalid-feedback">
                                        @error('selectedRamoId') {{ $message }} @enderror
                                    </div>
                                </x-field>
                                <x-field class="col-1">
                                    <a class=" text-dark" wire:click.prevent="addRamo" title="Agregar Ramo">
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
                                                        <div x-data>
                                                            <a style="cursor:pointer" x-on:click="confirm('Seguro que desea eliminar este Registro?') ? @this.deleteRamo({{ $ramo['cod_ram'] }}) : false" class="text-dark" title="Eliminar Ramo">
                                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                                            </a>
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
                        </div>

                    </x-slot>
                </x-card>
            </div>
        </x-slot>

    </x-card>
</x-form>