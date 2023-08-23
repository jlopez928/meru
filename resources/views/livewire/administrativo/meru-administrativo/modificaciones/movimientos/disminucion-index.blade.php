<x-datatable :list="$disminuciones">

    @if (count($disminuciones))
        <div class="table-responsive">
            <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers" style="font-size:12px;">
                @foreach ($disminuciones as $disminucionItem)
                    <tr>
                        <td class="text-center" style="vertical-align: middle;">
                            {{ $disminucionItem->ano_pro }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            <a href="{{ route('modificaciones.movimientos.disminucion.show', $disminucionItem) }}" title="Consultar">
                                {{ $disminucionItem->xnro_mod }}
                            </a>
                        </td>
                        <td style="vertical-align: middle;">
                            {{ $disminucionItem->concepto }}
                        </td>
                        <td style="vertical-align: middle;">
                            {{ $disminucionItem->justificacion }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            {{ Carbon\Carbon::parse($disminucionItem->fec_pos)->format('d/m/Y') }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            {{ Str::replace('_', ' ', $disminucionItem->sta_reg->name) }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            @if ( in_array($disminucionItem->ano_pro, $periodos) )
                                @include('administrativo/meru_administrativo/modificaciones/movimientos/disminuciones/partials/_botonera')
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