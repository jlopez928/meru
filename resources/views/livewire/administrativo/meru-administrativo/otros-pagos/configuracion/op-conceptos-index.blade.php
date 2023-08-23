<div>
    <x-datatable :list="$opconceptos">
        @if (count($opconceptos))
        {{--  @dump($opconceptos)  --}}
            <div class="table-responsive">
                <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers">
                    @foreach ($opconceptos as $opconceptosItem)
                        <tr>
                            <td align="center" >
                                <a href="{{ route('otrospagos.configuracion.conceptoservicio.show', $opconceptosItem->id ) }}" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Mostrar">
                                    {{ $opconceptosItem->id }}
                                </a>
                            </td>
                            <td align="center" >{{ $opconceptosItem->cod_con }}</td>
                            <td align="left" >{{ $opconceptosItem->des_con }}</td>
                            <td  class="text-bold {{ $opconceptosItem->sta_reg == '1' ? 'text-success' : 'text-danger' }}"  align="center" >
                                 {{  $opconceptosItem->sta_reg == '1' ? 'Activo':'Inactivo' }} </td>
                            <td align="center" >
                                <a href="{{ route('otrospagos.configuracion.conceptoservicio.edit', $opconceptosItem->id) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Editar">
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
</div>

