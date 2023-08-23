<x-datatable :list="$causaanulacion">
    @if (count($causaanulacion))
    {{--  @dump($causaanulacion)  --}}
        <div class="table-responsive">
            <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers">
                @foreach ($causaanulacion as $causaanulacionItem)
                    <tr>
                        <td align="center" >
                            <a href="{{ route('compras.configuracion.causaanulacion.show', $causaanulacionItem->id ) }}" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Mostrar">
                                {{ $causaanulacionItem->id }}
                            </a>
                        </td>
                        <td align="center" >{{ $causaanulacionItem->cod_cau }}</td>
                        <td align="left" >{{ $causaanulacionItem->des_cau }} </td>
                        <td align="center" >
                            <span class="text-bold {{ $causaanulacionItem->sta_reg == '1' ? 'text-success' : 'text-danger' }}" >
                                {{  $causaanulacionItem->sta_reg == '1' ? 'Activo':'Inactivo' }}
                            </span>
                        </td>

                        <td align="center" >
                            <a href="{{ route('compras.configuracion.causaanulacion.edit', $causaanulacionItem->id) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Editar">
                                <span class="fas fa-edit" aria-hidden="true"></span>
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


