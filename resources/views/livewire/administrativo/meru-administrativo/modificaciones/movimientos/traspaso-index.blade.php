<x-datatable :list="$traspasos">

    @if (count($traspasos))
        <div class="table-responsive">
            <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers" style="font-size:12px;">
                @foreach ($traspasos as $traspasoItem)
                    <tr>
                        <td class="text-center" style="vertical-align: middle;">
                            {{ $traspasoItem->ano_pro }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            <a href="{{ route('modificaciones.movimientos.traspaso_presupuestario.show', $traspasoItem) }}" title="Consultar">
                                {{ $traspasoItem->xnro_mod }}
                            </a>
                        </td>
                        <td style="vertical-align: middle;">
                            {{ $traspasoItem->concepto }}
                        </td>
                        <td style="vertical-align: middle;">
                            {{ $traspasoItem->justificacion }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            {{ Carbon\Carbon::parse($traspasoItem->fec_pos)->format('d/m/Y') }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            {{ Str::replace('_', ' ', $traspasoItem->sta_reg->name) }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            @if ( in_array($traspasoItem->ano_pro, $periodos) )
                                @include('administrativo/meru_administrativo/modificaciones/movimientos/traspaso/partials/_botonera')
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