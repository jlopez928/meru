<x-datatable :list="$insubsistencias">

    @if (count($insubsistencias))
        <div class="table-responsive">
            <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers" style="font-size:12px;">
                @foreach ($insubsistencias as $insubsistenciaItem)
                    <tr>
                        <td class="text-center" style="vertical-align: middle;">
                            {{ $insubsistenciaItem->ano_pro }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            <a href="{{ route('modificaciones.movimientos.insubsistencia.show', $insubsistenciaItem) }}" title="Consultar">
                                {{ $insubsistenciaItem->xnro_mod }}
                            </a>
                        </td>
                        <td style="vertical-align: middle;">
                            {{ $insubsistenciaItem->concepto }}
                        </td>
                        <td style="vertical-align: middle;">
                            {{ $insubsistenciaItem->justificacion }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            {{ Carbon\Carbon::parse($insubsistenciaItem->fec_pos)->format('d/m/Y') }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            {{ Str::replace('_', ' ', $insubsistenciaItem->sta_reg->name) }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            @if ( in_array($insubsistenciaItem->ano_pro, $periodos) )
                                @include('administrativo/meru_administrativo/modificaciones/movimientos/insubsistencias/partials/_botonera')
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