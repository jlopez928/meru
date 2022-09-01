<x-datatable :list="$subgrupos">

    @if (count($subgrupos))
        <div class="table-responsive">
            <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers">
                @foreach ($subgrupos as $subgrupo)
                    <tr>
                        <td align="center">{{ $subgrupo->grupo }}</td>
                        <td align="center" >
                            <a href="{{ route('compras.configuracion.subgrupo_producto.show', $subgrupo) }}" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Mostrar">
                                {{ $subgrupo->subgrupo }}
                            </a>
                        </td>
                        <td align="left">{{ $subgrupo->des_subgrupo }}</td>
                        <td align="center">{{ $subgrupo->sta_reg->name }}</td>
                        <td align="center">
                            <a href="{{ route('compras.configuracion.subgrupo_producto.edit', $subgrupo) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Editar">
                                <span class="fas fa-edit" aria-hidden="true"></span>
                            </a>
                            @if ($subgrupo->productos_count === 0)
                                <a class="btn-sm confirm-delete" type="button" form-target="delete-form-{{ $subgrupo->subgrupo }}"
                                    onclick="event.preventDefault();" title="Eliminar">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </a>
                                <form 
                                    id="delete-form-{{ $subgrupo->subgrupo }}" 
                                    action="{{ route('compras.configuracion.subgrupo_producto.destroy', $subgrupo->subgrupo) }}"
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