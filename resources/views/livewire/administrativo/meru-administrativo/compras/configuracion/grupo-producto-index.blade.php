<x-datatable :list="$grupos">

    @if (count($grupos))
        <div class="table-responsive">
            <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers">
                @foreach ($grupos as $grupo)
                    <tr>
                        <td align="center" >
                            <a href="{{ route('compras.configuracion.grupo_producto.show', $grupo) }}" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Mostrar">
                                {{ $grupo->grupo }}
                            </a>
                        </td>
                        <td align="left">{{ $grupo->des_grupo }}</td>
                        <td align="center">{{ $grupo->sta_reg->name }}</td>
                        <td align="center">
                            <a href="{{ route('compras.configuracion.grupo_producto.edit', $grupo) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Editar">
                                <span class="fas fa-edit" aria-hidden="true"></span>
                            </a>
                            @if ($grupo->subgrupoproductos_count === 0)
                                <a class="btn-sm confirm-delete" type="button" form-target="delete-form-{{ $grupo->grupo }}"
                                    onclick="event.preventDefault();" title="Eliminar">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </a>
                                <form 
                                    id="delete-form-{{ $grupo->grupo }}" 
                                    action="{{ route('compras.configuracion.grupo_producto.destroy', $grupo->grupo) }}"
                                    method="POST" 
                                    style="display: none;"
                                >
                                    @csrf
                                    @method('DELETE')
                                </form>
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
    {!! Helper::swalConfirm('.confirm-delete') !!}
@endpush
