<x-datatable :list="$factura">
    @if (count($factura))
        <div class="table-responsive">
            <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers">
                @foreach ($factura as  $facturaItem)
                    <tr>
                         <td align="center" >
                            <a href="{{ route('cuentasxpagar.proceso.factura.show', [$facturaItem,'show'] ) }}" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Mostrar">
                                {{ $facturaItem->id }}
                            </a>
                        </td>
                        <td align="center" >{{ $facturaItem->fec_fac->format('d-m-Y') }}</td>
                        <td align="center" >
                            {{$facturaItem->num_fac}}
                        </td>
                        <td align="center" >{{ $facturaItem->ano_pro }} </td>
                        <td align="center" >{{ $facturaItem->num_ctrl}}</td>
                        <td align="center" >{{ $facturaItem->rif_prov }}</td>
                        <td align="center" >{{ $facturaItem->mto_fac }}</td>
                        <td align="center" > @switch($facturaItem->sta_fac)
                                                @case(0) {{"Con Expediente Registrado en Control del Gasto"}} @break
                                                @case(1) {{"Aprobada Presupuestariamente"}} @break
                                                @case(2) {{"Reversada Presupuestariamente"}} @break
                                                @case(3) {{"Aprobada Contablemente"}} @break
                                                @case(4) {{"Reversar Asiento Contable"}} @break
                                                @case(5) {{"Con Deducciones y Retenciones"}} @break
                                                @case(6) {{"En Cronograma de Pago"}} @break
                                                @case(8) {{"Con Cheque Impreso"}} @break
                                                @case(9) {{"Parcialmente pagada"}} @break
                                            @endswitch
                        </td>
                        <td align="center" >
                            @if ($facturaItem->sta_fac == 2 || $facturaItem->sta_fac == 0)
                                <a href="{{route('cuentasxpagar.proceso.factura.show',[$facturaItem,'cambiar']) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Cambiar Factura">
                                    <span class="fas fa-edit"></span>
                                </a>
                            @endif

                            @if ($facturaItem->sta_fac == 2 || $facturaItem->sta_fac == 0)
                                <a href="{{route('cuentasxpagar.proceso.factura.show', [$facturaItem->id,'anular']) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Anular Factura">
                                    <span class="fas fa-expand-arrows-alt" style="color: #0b7ff3" ></span>
                                </a>
                            @endif

                            @if ($facturaItem->sta_fac == 0 )
                                <a href="{{route('cuentasxpagar.proceso.factura.show', [$facturaItem->id,'aprobar']) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Aprobar Factura">
                                    <span class="fas fa-check" style="color: #f3aa0b" ></span>

                                </a>
                            @endif

                            @if ($facturaItem->sta_fac == 1 )
                                <a href="{{route('cuentasxpagar.proceso.factura.show', [$facturaItem->id,'reversar']) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Reversar Factura">
                                    <span class="fas fa-undo" style="color: #044b1d" ></span>
                                </a>
                            @endif
                            @if ($facturaItem->sta_fac == 1 || $facturaItem->sta_fac == 2)
                                <a href="{{route('cuentasxpagar.proceso.factura.show', [$facturaItem->id,'modificar']) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Modificar Asiento">
                                    <span class="fas fa-tasks" style="color: #f30b54" ></span>
                                </a>
                            @endif

                           {{-- @if ( $facturaItem->sta_fac==3 )
                                <a href="{{route('cuentasxpagar.reporte.print_devolver_fact_recibida', $facturaItem) }}" target="_blank" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Devolver Factura Recibida">
                                    <span class="fas fa-print" style="color: #f30b54" ></span>
                                </a>
                          {{--  @endif  --}}

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

