<x-datatable :list="$encnotaentrega">

    @if (count($encnotaentrega))
        <div class="table-responsive">
            <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers">
                @foreach ($encnotaentrega as $encnotaentregaItem)
                    <tr>
                        <td align="center" >
                            <a href="{{ route('contratos.proceso.actacontratobraserv.show', [$encnotaentregaItem->id,'show'] ) }}" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Mostrar">
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

                            @if ($encnotaentregaItem->stat_causacion!=6 && $encnotaentregaItem->cont_fis=='' && $encnotaentregaItem->sta_ent!=8 && $encnotaentregaItem->sta_ent!=5) {{--  modificar --}}
                                <a href="{{route('contratos.proceso.actacontratobraserv.show',[$encnotaentregaItem->id,'modificar']) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Editar">
                                    <span class="fas fa-edit"></span>
                                </a>
                            @endif

                            @if ($encnotaentregaItem->sta_ent==0) {{--  transcrita --}}
                                <a href="{{route('contratos.proceso.actacontratobraserv.show', [$encnotaentregaItem->id,'iniciar']) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Iniciar Entrega">
                                    <span class="fas fa-tasks" style="color: #f3aa0b" ></span>
                                </a>
                            @endif
                            @if ($encnotaentregaItem->sta_ent==1) {{--  con acta de entrega  --}}
                                <a href="{{route('contratos.proceso.actacontratobraserv.show', [$encnotaentregaItem->id,'terminar']) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Terminar Entrega">
                                    <span class="fa fa-flag" style="color: #6c0bf3" ></span>
                                </a>
                            @endif
                            @if ($encnotaentregaItem->sta_ent==2) {{--  con acta de terminación  --}}
                                <a href="{{route('contratos.proceso.actacontratobraserv.show', [$encnotaentregaItem->id,'aceptar'])  }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Aceptar Entrega">
                                    <span class="fas fa-thumbs-up" ></span>
                                </a>
                            @endif
                            @if ($encnotaentregaItem->sta_ent==3) {{--  con acta de aceptacion  --}}
                                <a href="{{route('contratos.proceso.actacontratobraserv.show', [$encnotaentregaItem->id,'anular'])  }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Anular Entrega">
                                    <span class="fas fa-undo" style="color: rgb(23, 238, 23)" ></span>
                                </a>
                            @endif

                            <a href="{{route('contratos.proceso.actacontratobraserv.show', [$encnotaentregaItem->id,'reimprimir'])  }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Reimprimir Entrega">
                                <span class="fas fa-print" ></span>
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

