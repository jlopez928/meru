<x-datatable :list="$estructuras">

    @if (count($estructuras))
        <div class="table-responsive">

            <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers">

                @foreach ($estructuras as $estructuraItem)

                    <tr>
                        <td class="text-center" style="vertical-align: middle;">
                            {{ $estructuraItem->id }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            {{ $estructuraItem->ano_pro }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            <a href="{{ route('formulacion.configuracion.maestro_ley.show', $estructuraItem->id) }}"> {{ $estructuraItem->cod_com }} </a>
                        </td>
                        <td style="vertical-align: middle;">
                            <b>{{ $estructuraItem->centroCosto->cod_cencosto }}</b> - {{ $estructuraItem->centroCosto->des_con }}
                        </td>
                        <td style="vertical-align: middle;">
                            <b>{{ $estructuraItem->partidaPresupuestaria->cod_cta }}</b> - {{ $estructuraItem->partidaPresupuestaria->des_con }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            <a href="{{ route('formulacion.configuracion.maestro_ley.edit', $estructuraItem->id) }}" type="button" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Editar">
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