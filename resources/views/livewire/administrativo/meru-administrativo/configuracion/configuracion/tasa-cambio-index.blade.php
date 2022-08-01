<x-datatable :list="$tasacambio">

    @if (count($tasacambio))
        <div class="table-responsive">
            <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers">
                @foreach ($tasacambio as $tasacambioItem)
                    <tr>
                        <td align="center" > {{-- {{ $tasacambioItem->id }} --}}
                            <a href="{{ route('configuracion.configuracion.tasacambio.show', $tasacambioItem->id ) }}" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Mostrar">
                                {{ $tasacambioItem->id }}
                            </a>
                        </td>
                        <td align="center" >{{ $tasacambioItem->fec_tasa }}</td>
                        <td align="center" >{{ $tasacambioItem->bs_tasa }} </td>
                        <td align="center" >
                            {{--  {{ $tasacambioItem->sta_reg }}   --}}
                            <span class="text-bold {{ $tasacambioItem->sta_reg == '1' ? 'text-success' : 'text-danger' }}" >
                                {{  $tasacambioItem->sta_reg == '1' ? 'Activo':'Inactivo' }}
                            </span>
                        </td>
                        <td align="center" >{{ $tasacambioItem->fecha }} </td>
                        {{--  <td align="center" >{{ $tasacambioItem->usuario }} </td>  --}}

                        <td align="center" >
                            @if ($tasacambioItem->sta_reg =='1')
                                <a href="{{ route('configuracion.configuracion.tasacambio.edit', $tasacambioItem->id) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Editar">
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

