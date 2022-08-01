<x-datatable :list="$unidadtributaria">

    @if (count($unidadtributaria))
        <div class="table-responsive">

            <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers">
                @foreach ($unidadtributaria as $unidadtributariaItem)
                    <tr>
                        <td align="center" >
                            <a href="{{ route('configuracion.configuracion.unidadtributaria.show', $unidadtributariaItem->id ) }}" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Mostrar">
                                {{ $unidadtributariaItem->id }}
                            </a>
                        </td>
                        <td align="center" >{{ $unidadtributariaItem->fec_ut }}</td>
                        <td align="center" >{{ $unidadtributariaItem->bs_ut }} </td>
                        <td align="center" >{{ $unidadtributariaItem->bs_ucau }} </td>
                        <td align="center" >
                            <span class="text-bold {{ $unidadtributariaItem->vigente == '1' ? 'text-success' : 'text-danger' }}" >
                                {{  $unidadtributariaItem->vigente == '1' ? 'Activo':'Inactivo' }}
                            </span>
                        </td>
                        <td align="center" >
                            @if ( $unidadtributariaItem->vigente == '1')
                                <a href="{{ route('configuracion.configuracion.unidadtributaria.edit', $unidadtributariaItem->id) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Editar">
                                    <span class="fas fa-edit" aria-hidden="true"></span>
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

