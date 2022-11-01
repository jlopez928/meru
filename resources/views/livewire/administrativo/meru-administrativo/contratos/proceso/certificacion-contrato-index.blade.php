<x-datatable :list="$certificacion">
    @if (count($certificacion))
    {{--  @dump($certificacion)  --}}
        <div class="table-responsive">
            <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers">
                @foreach ($certificacion as $certificacionItem)
                    <tr>
                        <td align="center" >
                            <a href="{{ route('contratos.proceso.certificacioncontrato.show', [ $certificacionItem->id ,'show'] ) }}" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Mostrar">
                                {{ $certificacionItem->id }}
                            </a>
                        </td>
                        <td align="center" >{{ $certificacionItem->ano_pro }}</td>
                        <td align="center" >{{ $certificacionItem->xnro_sol }}</td>
                        <td align="center" >{{ $certificacionItem->fec_emi->format("d/m/Y") }}</td>
                        <td align="left" >{{ $certificacionItem->gerencias->des_ger }} </td>
                        <td align="left" >{{ $certificacionItem->rif_prov }} - {{ $certificacionItem->beneficiario->nom_ben }} </td>
                        <td align="left" >{{ $certificacionItem->formatNumber('monto_total')}} </td>
                        <td align="left" >{{ $certificacionItem->sta_sol->name }} </td>
                        <td align="center" style="width:15%" >
                            @if($certificacionItem->sta_sol->value =='0'|| $certificacionItem->sta_sol->value =='3')
                                <a href="{{ route('contratos.proceso.certificacioncontrato.edit', $certificacionItem->id) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Editar">
                                    <span class="fas fa-edit" aria-hidden="true"></span>
                                </a>
                                <a href="{{ route('contratos.proceso.certificacioncontrato.show', [ $certificacionItem->id ,'anular'] ) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Anular">
                                    <span class="fas fa-times text-danger" aria-hidden="true"></span>
                                </a>

                                <a href="{{ route('contratos.proceso.certificacioncontrato.show', [ $certificacionItem->id ,'aprobar'] ) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Aprobar Gerente">
                                    <span class="fas fa-check  text-success" aria-hidden="true"></span>
                                </a>
                                <a href="{{ route('contratos.proceso.print_certificacionobras','xnro_sol='.$certificacionItem->xnro_sol.'&ano_pro='.$certificacionItem->ano_pro) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Imprimir Certificación">
                                    <span class="fas fa-print" aria-hidden="true"></span>
                                </a>
                            @endif
                            @if($certificacionItem->sta_sol->value =='2'|| $certificacionItem->sta_sol->value =='5')
                                <a href="{{ route('contratos.proceso.certificacioncontrato.show', [ $certificacionItem->id ,'reversar'] ) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Reverso de Gerente">
                                    <span class="fas fa-undo text-warning" aria-hidden="true"></span>
                                </a>
                                <a href="{{ route('contratos.proceso.certificacioncontrato.show', [ $certificacionItem->id ,'comprometer'] ) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Comprometer">
                                    <span class="fas fa-coins text-success" aria-hidden="true"></span>
                                </a>
                                <a href="{{ route('contratos.proceso.print_certificacionobras','xnro_sol='.$certificacionItem->xnro_sol.'&ano_pro='.$certificacionItem->ano_pro) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Imprimir Certificación">
                                    <span class="fas fa-print" aria-hidden="true"></span>
                                </a>
                            @endif

                            @if($certificacionItem->sta_sol->value =='4')
                                <a href="{{ route('contratos.proceso.certificacioncontrato.show', [ $certificacionItem->id ,'reverso'] ) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Reverso de Compromiso">
                                    <span class="fas fa-undo text-warning" aria-hidden="true"></span>
                                </a>
                                <a href="{{ route('contratos.proceso.print_certificacionobras','xnro_sol='.$certificacionItem->xnro_sol.'&ano_pro='.$certificacionItem->ano_pro) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Imprimir Certificación">
                                    <span class="fas fa-print" aria-hidden="true"></span>
                                </a>
                                @if($certificacionItem->ult_sol !='-1')
                                    <a href="{{ route('contratos.proceso.certificacioncontrato.show', [ $certificacionItem->id ,'cierre'] ) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Cierre de Compromiso">
                                        <span class="fas fa-briefcase text-danger" aria-hidden="true"></span>
                                    </a>
                                @endif
                            @endif
                            @if($certificacionItem->sta_sol->value =='C')
                                <a href="{{ route('contratos.proceso.print_certificacionobras','xnro_sol='.$certificacionItem->xnro_sol.'&ano_pro='.$certificacionItem->ano_pro) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Imprimir Certificación">
                                    <span class="fas fa-print" aria-hidden="true"></span>
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


