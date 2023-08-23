
<x-datatable :list="$unidadmedida">
    @if (count($unidadmedida))
    {{--  @dump($unidadmedida)  --}}
        <div class="table-responsive">
            <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers">
                @foreach ($unidadmedida as $unidadmedidaItem)
                    <tr>
                        <td align="center" >
                            <a href="{{ route('compras.configuracion.unidadmedida.show', $unidadmedidaItem->id ) }}" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Mostrar">
                                {{ $unidadmedidaItem->id }}
                            </a>
                        </td>
                        <td align="center" >{{ $unidadmedidaItem->cod_uni }}</td>
                        <td align="left" >{{ $unidadmedidaItem->des_uni }} </td>
                        <td align="center" >
                            <span class="text-bold {{  $unidadmedidaItem->sta_reg->value  == '1' ? 'text-success' : 'text-danger' }}" >
                                {{  $unidadmedidaItem->sta_reg->value == '1' ? 'Activo':'Inactivo' }}
                            </span>
                        </td>

                        <td align="center" >
                            <a href="{{ route('compras.configuracion.unidadmedida.edit',$unidadmedidaItem->cod_uni) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Editar">
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
>
