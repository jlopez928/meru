<x-datatable :list="$centros">

    @if (count($centros))
        <div class="table-responsive">

            <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers">
                @foreach ($centros as $centroItem)     
                    <tr>
                        <td class="text-center" style="vertical-align: middle;">
                            {{ $centroItem->id }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            {{ $centroItem->ano_pro }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            <a href="{{ route('formulacion.configuracion.centro_costo.show', $centroItem->id) }}"> {{ $centroItem->cod_cencosto }} </a>
                        </td>
                        <td style="vertical-align: middle;"> {{ $centroItem->des_con }} </td>
                        <td class="text-center" style="vertical-align: middle;">
                            <span class="text-bold {{ $centroItem->cre_adi == 'SI' ? 'text-success' : 'text-danger' }}">
                                {{ $centroItem->cre_adi }}
                            </span>
                        </td>
                        <td class="text-center" style="vertical-align: middle;"> 
                            <span class="text-bold {{ $centroItem->sta_reg == 'ACTIVO' ? 'text-success' : 'text-danger' }}">
                                {{ $centroItem->sta_reg }}
                            </span>
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            <a href="{{ route('formulacion.configuracion.centro_costo.edit', $centroItem->id) }}" type="button" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Editar">
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