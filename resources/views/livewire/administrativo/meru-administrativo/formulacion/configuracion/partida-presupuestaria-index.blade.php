<x-datatable :list="$partidas">

    @if (count($partidas))
        <div class="table-responsive">

            <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers">
                @foreach ($partidas as $partidaItem)     
                    <tr>
                        <td class="text-center" style="vertical-align: middle;"> 
                                {{ $partidaItem->id }}
                            </span>
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            <a href="{{ route('formulacion.configuracion.partida_presupuestaria.show', $partidaItem->id) }}"> {{ $partidaItem->cod_cta }} </a>
                            
                        </td>
                        <td style="vertical-align: middle;">
                            {{ $partidaItem->des_con }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            {{ $partidaItem->part_asociada }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            <a href="{{ route('formulacion.configuracion.partida_presupuestaria.edit', $partidaItem->id) }}" type="button" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Editar">
                                <span class="fas fa-edit" aria-hidden="true"></span>
                            </a>
                            &nbsp;
                            <a href="{{ route('formulacion.configuracion.partida_presupuestaria.asociar_cuenta.edit', $partidaItem->id) }}" type="button" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Asociar Cuentas">
                                <span class="fas fa-exchange-alt text-success" aria-hidden="true"></span>
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