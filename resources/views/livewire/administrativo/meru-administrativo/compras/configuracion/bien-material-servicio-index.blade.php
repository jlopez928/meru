<x-datatable :list="$productos">

    @if (count($productos))
        <div class="table-responsive">
            <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers">
                @foreach ($productos as $producto)
                    <tr>
                        <td align="center" >
                            <a href="{{ route('compras.configuracion.bien_material_servicio.show', $producto) }}" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Mostrar">
                                {{ $producto->cod_prod }}
                            </a>
                        </td>
                        <td align="center">{{ $producto->des_prod }}</td>
                        <td align="left">{{ $producto->ult_pre }}</td>
                        <td align="center">{{ $producto->sta_reg->name }}</td>
                        <td align="center">
                            @if ($producto->sta_reg->value === \App\Enums\Administrativo\Meru_Administrativo\Estado::Activo->value)
                                <a href="{{ route('compras.configuracion.bien_material_servicio.edit', $producto) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Editar">
                                    <span class="fas fa-edit" aria-hidden="true"></span>
                                </a>
                                <a href="{{ route('compras.configuracion.bien_material_servicio.asignar', $producto) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Asignar Partida">
                                    <span class="fas fa-clipboard-check" aria-hidden="true"></span>
                                </a>
                                <a wire:click="confirmInactivar({{$producto}})" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Inactivar">
                                    <span class="fas fa-trash" aria-hidden="true"></span>
                                </a>
                            @endif
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
                    Livewire.emitTo('administrativo.meru-administrativo.compras.configuracion.bien-material-servicio-index', param['funcion'], param['cod_prod'])
                }
            })
        })
    </script>
@endpush
