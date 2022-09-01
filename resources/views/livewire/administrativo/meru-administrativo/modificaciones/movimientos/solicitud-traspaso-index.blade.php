<x-datatable :list="$solicitudes">

    @if (count($solicitudes))
        <div class="table-responsive">
            <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers" style="font-size:12px;">
                @foreach ($solicitudes as $solicitudItem)
                    <tr>
                        <td class="text-center" style="vertical-align: middle;">
                            {{ $solicitudItem->ano_pro }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            <a href="{{ route('modificaciones.movimientos.solicitud_traspaso.show', $solicitudItem) }}" title="Consultar">
                                {{ $solicitudItem->nro_sol }}
                            </a>
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            {{ $solicitudItem->gerencia->des_ger }}
                        </td>
                        <td style="vertical-align: middle;">
                            {{ $solicitudItem->justificacion }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            {{ $solicitudItem->num_sop }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            {{ Carbon\Carbon::parse($solicitudItem->fec_sol)->format('d/m/Y') }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            {{ $solicitudItem->sta_reg->name }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            @if ( in_array($solicitudItem->ano_pro, $periodos) )
                                @include('administrativo/meru_administrativo/modificaciones/movimientos/solicitud_traspaso/partials/_botonera')
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