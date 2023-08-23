<x-datatable :list="$usuarios">

    @if (count($usuarios))
        <div class="table-responsive">
            <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers">
                @foreach ($usuarios as $usuario)
                    <tr>
                        <td align="left" >{{ $usuario->ficha }}</td>
                        <td align="left">{{ $usuario->usuario }}</td>
                        <td align="left">{{ $usuario->cedula }}</td>
                        <td align="left">{{ $usuario->nombre }}</td>
                        <td align="left">{{ $usuario->correo }}</td>
                        <td align="center">{{ $usuario->status->name }}</td>
                        <td align="center">
                            <a wire:click="confirmRegistrar({{ $usuario }})" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Registrar">
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
                    Livewire.emitTo('administrativo.meru-administrativo.compras.configuracion.comprador-create', param['funcion'], param['usuario'])

                }
            })
        })
    </script>
@endpush
