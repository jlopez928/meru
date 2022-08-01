<x-datatable :list="$ubicaciones">

    @if (count($ubicaciones))
        <div class="table-responsive">

            <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers">
                @foreach ($ubicaciones as $ubicacionItem)     
                    <tr>
                        <td class="text-center" style="vertical-align: middle;">
                            {{ $ubicacionItem->id }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            {{ $ubicacionItem->cod_edo }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            {{ $ubicacionItem->cod_mun }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            {{ $ubicacionItem->cod_par }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            <a href="{{ route('configuracion.configuracion.ubicacion_geografica.show', $ubicacionItem->id) }}"> {{ $ubicacionItem->des_ubi }} </a>
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            {{ $ubicacionItem->capital }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            {{ $ubicacionItem->cod_ubi }}                        
                        <td class="text-center" style="vertical-align: middle;">
                            <a href="{{ route('configuracion.configuracion.ubicacion_geografica.edit', $ubicacionItem->id) }}" type="button" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Editar">
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