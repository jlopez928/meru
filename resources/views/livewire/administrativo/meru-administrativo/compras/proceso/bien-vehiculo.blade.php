<div>
    @if( $accion == 'nuevo' || $accion == 'editar')
        <x-datatable :list="$vehiculosList">

            @if (count($vehiculosList))
                <div class="table-responsive">
                    <x-table-headers class="py-2 table-sm" :sortby="$sort" :order="$direction" :headers="$headers">
                        @foreach ($vehiculosList as $vehiculo)
                            <tr>
                                <td>{{ $vehiculo->cod_corr }}</td>
                                <td>{{ $vehiculo->placa }}</td>
                                <td>{{ $vehiculo->modelo }}</td>
                                <td>{{ $vehiculo->marca }}</td>
                                <td class="text-center">
                                    {{--  <button class="btn-primary btn-sm" wire:click.prevent="agregarVehiculo({{ $vehiculo->cod_corr }})" title="Agregar Vehiculo"><i class="fas fa-plus"></i></button>  --}}
                                    <a wire:click.prevent="agregarVehiculo({{ $vehiculo->cod_corr }})" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Agregar Vehiculo">
                                        <span class="fas fa-plus" aria-hidden="true"></span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </x-table-headers>
                </div>
            @else
                <div class="px-6 py-2">
                        <span>No se encontraron registros.</span>
                </div>
            @endif

        </x-datatable>
    @endif

    <input type="hidden" name="vehiculos" id="vehiculos" />

    {{--  Detalle Vehiculos Seleccionados  --}}
    <div class="mt-4 table-responsive">
        <table class="table table-bordered table-striped table-sm text-center">
            <thead>
                <tr class="table-success">
                    <th>Itém</th>
                    <th>Cód. Correlativo</th>
                    <th>Placa</th>
                    <th>Modelo</th>
                    <th>Marca</th>
                    @if( $accion == 'nuevo' || $accion == 'editar')
                        <th>Acción</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($vehiculos as $index => $vehiculo)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $vehiculo['cod_corr'] }}</td>
                        <td>{{ $vehiculo['placa'] }}</td>
                        <td>{{ $vehiculo['modelo'] }}</td>
                        <td>{{ $vehiculo['marca'] }}</td>
                        @if( $accion == 'nuevo' || $accion == 'editar')
                            <td align="center">
                                <a wire:click.prevent="eliminarVehiculo({{ $index }})" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Eliminar Vehiculo">
                                    <span class="fas fa-trash" aria-hidden="true"></span>
                                </a>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No hay vehiculos Seleccionados</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
    <script>
        Livewire.on('cargarVehiculo', param => {
			$("#vehiculos").val(JSON.stringify(param['vehiculos']));
        });

        Livewire.on('swal:alert', param => {
            Swal.fire({
                title: param['titulo'],
                text: param['mensaje'],
                icon: param['tipo'],
            })
        })
    </script>
@endpush
