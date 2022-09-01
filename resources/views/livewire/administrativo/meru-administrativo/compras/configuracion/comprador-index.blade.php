<x-datatable :list="$compradores">

    @if (count($compradores))
        <div class="table-responsive">
            <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers">
                @foreach ($compradores as $comprador)
                    <tr>
                        <td align="center" >
                            <a href="{{ route('compras.configuracion.comprador.show', $comprador) }}" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Mostrar">
                                {{ $comprador->cod_com }}
                            </a>

                        </td>
                        <td align="left">{{ $comprador->usu_com }}</td>
                        <td align="left">{{ $comprador->usuariot->nombre }}</td>
                        <td align="left">{{ $comprador->usuariot->cedula }}</td>
                        <td align="left">{{ $comprador->usuariot->correo }}</td>
                        <td align="center">{{ $comprador->sta_reg->name }}</td>
                        <td align="center">

                            @if ($comprador->sta_reg->value === \App\Enums\Administrativo\Meru_Administrativo\Estado::Inactivo->value)
                                <a wire:click="confirmActivar({{ $comprador->cod_com }})" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Activar">
                                    <span class="fas fa-check" aria-hidden="true"></span>
                                </a>
                            @else
                                <a wire:click="confirmInactivar({{ $comprador->cod_com }})" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Inactivar">
                                    <span class="fas fa-times" aria-hidden="true"></span>
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
                    Livewire.emitTo('administrativo.meru-administrativo.compras.configuracion.comprador-index', param['funcion'], param['cod_com'])
                }
            })
        })
    </script>
@endpush
