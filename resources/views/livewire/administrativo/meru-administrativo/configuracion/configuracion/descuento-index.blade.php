
<x-datatable :list="$descuento">
    @if (count($descuento))
    {{--  @dump($descuento)  --}}
        <div class="table-responsive">
            <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers">
                @foreach ($descuento as $descuentoItem)
                    <tr>
                        <td align="center" >
                            <a href="{{ route('configuracion.configuracion.descuento.show', $descuentoItem->id ) }}" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Mostrar">
                                {{ $descuentoItem->id }}
                            </a>
                        </td>
                        <td align="center" >{{ $descuentoItem->cod_des }}</td>
                        <td align="left" >{{ $descuentoItem->des_des }} </td>
                        @if ($descuentoItem->tipomontos)
                            <td align="center" >{{ $descuentoItem->tipomontos->descripcion  }} </td>
                        @else
                            <td align="center" >{{ '--' }} </td>
                        @endif
                        @if ($descuentoItem->tipomontos)
                            <td align="left" >{{ $descuentoItem->adm_retencions->des_ret }} </td>
                         @else
                             <td align="center" >{{ '--' }} </td>
                        @endif
                            <td align="center" >
                            {{--  {{ $descuentoItem->status }}   --}}
                            <span class="text-bold {{ $descuentoItem->status == '1' ? 'text-success' : 'text-danger' }}" >
                                {{  $descuentoItem->status == '1' ? 'Activo':'Inactivo' }}
                            </span>
                        </td>

                        <td align="center" >
                            <a href="{{ route('configuracion.configuracion.descuento.edit', $descuentoItem->id) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Editar">
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

