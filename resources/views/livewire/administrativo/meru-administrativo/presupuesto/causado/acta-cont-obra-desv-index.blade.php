<x-datatable :list="$encnotaentrega">

    @if (count($encnotaentrega))
        <div class="table-responsive">
            <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers">
                @foreach ($encnotaentrega as $encnotaentregaItem)
                    <tr>
                        <td align="center" >
                            <a href="{{ route('presupuesto.causado.actacontobraserv.show', $encnotaentregaItem->id ) }}" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Mostrar">
                                {{ $encnotaentregaItem->id }}
                            </a>
                        </td>
                        <td align="center" >{{ $encnotaentregaItem->fk_ano_pro }}</td>
                        <td align="center" >{{ $encnotaentregaItem->grupo }} </td>
                        <td align="center" >{{ $encnotaentregaItem->nro_ent }} </td>
                        <td align="center" >{{ $encnotaentregaItem->fk_nro_ord }}</td>
                        <td align="center" >{{ $encnotaentregaItem->fec_pos }}</td>
                        <td align="center" @if ($encnotaentregaItem->sta_ent==7) class="text-primary" @endif> @switch($encnotaentregaItem->sta_ent)
                                                @case(0) {{"Solo Transcrita."}} @break
                                                @case(1) {{"Con Acta de Inicio."}} @break
                                                @case(2) {{"Con Acta de Terminación."}} @break
                                                @case(3) {{"Con Acta de Aceptación."}} @break
                                                @case (4) {{"Con Factura Registrada."}}  @break
                                                @case (5) {{"Reversada."}}  @break
                                                @case (7) {{"Causada."}} @break
                                                @case (7) {{"Causada."}} @break
                                                @case (8) {{"Anulada."}}  @break
                                                @break
                                            @endswitch
                        </td>
                        <td align="center" >
                             @if ($encnotaentregaItem->sta_ent =='3')
                                <a href="{{ route('presupuesto.causado.actacontobraserv.causar', $encnotaentregaItem->id) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Causar">
                                    <span class="fas fa-calendar" ></span>
                                </a>
                            @endif
                            @if ($encnotaentregaItem->stat_causacion =='7')
                                <a href="{{ route('presupuesto.causado.actacontobraserv.aprobar', $encnotaentregaItem->id) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Aprobar Comprobante">
                                    <span class="fas fa-check on fa-ban" ></span>
                                </a>
                            @endif
                            @if ($encnotaentregaItem->sta_ent =='7')
                                <a href="{{ route('presupuesto.causado.actacontobraserv.reversar', $encnotaentregaItem->id) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Reversar">
                                    <span class="fas fa-reply" ></span>
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

