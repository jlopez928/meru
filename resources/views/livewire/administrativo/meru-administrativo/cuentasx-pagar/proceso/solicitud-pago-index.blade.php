<x-datatable :list="$solpago">
    @if (count($solpago))
    {{--  @dump($solpago)  --}}
        <div class="table-responsive">
            <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers">
                @foreach ($solpago as $solpagoItem)
                    <tr>
                        <td align="center" >
                            <a href="{{ route('cuentasxpagar.proceso.solicititudpago.show', [ $solpagoItem->id]) }}" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Mostrar">
                                {{ $solpagoItem->id }}
                            </a>
                        </td>
                        <td align="center" >{{ $solpagoItem->ano_pro }}</td>
                        <td align="center" >{{ $solpagoItem->ord_pag }}</td>
                        <td align="center" >{{ $solpagoItem->num_fac}}</td>
                        <td align="center" >{{ $solpagoItem->fecha->format('d/m/Y') }}</td>
                        <td align="left" >{{ $solpagoItem->ced_ben }} - {{ $solpagoItem->benefi }} </td>
                        <td align="left" >{{ $solpagoItem->formatNumber('monto')}} </td>
                        <td align="left" >{{ $solpagoItem->sta_sol->name }} </td>
                        <td align="center" >
                            <a href="{{ route('cuentasxpagar.reporte.print_generar_solicitud', $solpagoItem->id) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Imprimir Solicitud">
                                <span class="fas fa-edit" aria-hidden="true"></span>
                            </a>
                            <a href="{{ route('cuentasxpagar.reporte.print_generar_iva', $solpagoItem->id) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="CR. IVA">
                                <span class="fas fa-edit" aria-hidden="true"></span>
                            </a>
                            <a href="{{ route('cuentasxpagar.reporte.print_generar_ISLR', $solpagoItem->id) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="ISLR">
                                <span class="fas fa-edit" aria-hidden="true"></span>
                            </a>
                            <a href="{{ route('cuentasxpagar.reporte.print_generar_unoxcien', $solpagoItem->id) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="1*100">
                                <span class="fas fa-edit" aria-hidden="true"></span>
                            </a>
                            <a href="{{ route('cuentasxpagar.reporte.print_generar_CSOC', $solpagoItem->id) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="R.Social">
                                <span class="fas fa-edit" aria-hidden="true"></span>
                            </a>
                            <a href="{{ route('cuentasxpagar.reporte.print_generar_UNOXMIL', $solpagoItem->id) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="1*1000">
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


