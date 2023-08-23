<x-datatable :list="$recepfactura">

    @if (count($recepfactura))
        <div class="table-responsive">
            <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers">
                @foreach ($recepfactura as $recepfacturaItem)
                    <tr>
                        <td align="center" >
                            <a href="{{ route('cuentasxpagar.proceso.recepfactura.show', [$recepfacturaItem->id,'show'] ) }}" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Mostrar">
                                {{ $recepfacturaItem->id }}
                            </a>
                        </td>
                        <td align="center" >{{ $recepfacturaItem->fec_fac->format('d-m-Y') }}</td>
                        <td align="center" >{{ $recepfacturaItem->num_fac }} </td>
                        <td align="center" >{{ $recepfacturaItem->ano_pro }} </td>
                        <td align="center" >{{ ($recepfacturaItem->recibo =='R')? 'Recibo' : 'Factura' }}</td>
                        <td align="center" >{{ $recepfacturaItem->rif_prov }}</td>
                        <td align="center" >{{ $recepfacturaItem->mto_fac }}</td>
                        <td align="center" > @switch($recepfacturaItem->sta_fac)
                                                @case(0) {{"Recepcionada."}} @break
                                                @case(1) {{"Expediente Registrado en Control del Gasto."}} @break
                                                @case(2) {{"Expediente Devuelto."}} @break
                                                @case(3) {{"Expediente Entregado."}} @break
                                            @endswitch
                        </td>
                        <td align="center" >
                            @if ($recepfacturaItem->sta_fac!=2 && $recepfacturaItem->sta_fac!=3)
                                <a href="{{route('cuentasxpagar.proceso.recepfactura.show',[$recepfacturaItem,'devolver']) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Devolver Factura">
                                    <span class="fas fa-undo"></span>

                                </a>
                            @endif

                            @if ($recepfacturaItem->sta_fac==2 )
                                <a href="{{route('cuentasxpagar.proceso.recepfactura.show', [$recepfacturaItem->id,'entregar']) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Entregar Factura">
                                    <span class="fas fa-table" style="color: #0b7ff3" ></span>
                                </a>
                            @endif

                            @if ($recepfacturaItem->sta_fac==3 )
                                <a href="{{route('cuentasxpagar.proceso.recepfactura.show', [$recepfacturaItem->id,'reactivar']) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Reactivar Factura">
                                    <span class="fas fa-sort-amount-up" style="color: #f3aa0b" ></span>
                                </a>
                            @endif

                            @if ( $recepfacturaItem->sta_fac==0 || $recepfacturaItem->sta_fac==2 )
                                <a href="{{route('cuentasxpagar.proceso.recepfactura.show', [$recepfacturaItem->id,'modificar']) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Modificar Factura">
                                    <span class="fas fa-retweet" style="color: #044b1d" ></span>

                                </a>
                             @endif

                             @if ( $recepfacturaItem->sta_fac==0 )
                                <a href="{{route('cuentasxpagar.proceso.recepfactura.show', [$recepfacturaItem->id,'eliminar']) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Eliminar Factura">
                                    <span class="fas fa-trash" style="color: #f30b54" ></span>
                                </a>
                            @endif

                            @if ( $recepfacturaItem->sta_fac==3 )
                                <a href="{{route('cuentasxpagar.reporte.print_devolver_fact_recibida', $recepfacturaItem) }}" target="_blank" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Devolver Factura Recibida">
                                    <span class="fas fa-print" style="color: #f30b54" ></span>
                                </a>
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

