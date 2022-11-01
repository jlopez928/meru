<x-datatable :list="$creditosAdicionales">

    @if (count($creditosAdicionales))
        <div class="table-responsive">
            <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers" style="font-size:12px;">
                @foreach ($creditosAdicionales as $creditoItem)
                    <tr>
                        <td class="text-center" style="vertical-align: middle;">
                            {{ $creditoItem->ano_pro }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            <a href="{{ route('modificaciones.movimientos.credito_adicional.show', $creditoItem) }}" title="Consultar">
                                {{ $creditoItem->xnro_mod }}
                            </a>
                        </td>
                        <td style="vertical-align: middle;">
                            {{ $creditoItem->concepto }}
                        </td>
                        <td style="vertical-align: middle;">
                            {{ $creditoItem->justificacion }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            {{ Carbon\Carbon::parse($creditoItem->fec_pos)->format('d/m/Y') }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            {{ Str::replace('_', ' ', $creditoItem->sta_reg->name) }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            @if ( in_array($creditoItem->ano_pro, $periodos) )
                                @include('administrativo/meru_administrativo/modificaciones/movimientos/creditos_adicionales/partials/_botonera')
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