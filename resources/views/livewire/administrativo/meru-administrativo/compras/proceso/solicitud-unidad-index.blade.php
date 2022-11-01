<x-datatable :list="$solicitudesUnidad">

    @if (count($solicitudesUnidad))
        <div class="table-responsive">
            <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers">
                @foreach ($solicitudesUnidad as $solicitud)
                    <tr>
                        <td align="left">{{ $solicitud->ano_pro }}</td>
                        <td align="left">{{ $solicitud->nro_req }}</td>
                        <td align="left">{{ $solicitud->grupo }}</td>
                        <td align="left">{{ $solicitud->fec_emi }}</td>
                        <td align="left">{{ $solicitud->gerencia->des_ger }}</td>
                        <td align="left">{{ $solicitud->monto_tot }}</td>
                        <td align="left">{{ $solicitud->estado->descripcion }}</td>
                        <td align="center">
                            {{--  Boton Ver Solicitud de Compra  --}}
                            <a href="{{ route('compras.proceso.solicitud_unidad.show', [$solicitud->ano_pro, $solicitud->grupo, $solicitud->nro_req]) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Ver Solicitud de Compra">
                                <span class="fas fa-search" aria-hidden="true"></span>
                            </a>

                            {{--  Boton Editar Solicitud de Compra  --}}
                            {{--  @if (($solicitud->ano_pro == session('ano_pro')))
                                <a href="{{ route('compras.proceso.solicitud_unidad.edit', [$solicitud->ano_pro, $solicitud->grupo, $solicitud->nro_req]) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Modificar Solicitud de Compra">
                                    <span class="fas fa-edit" aria-hidden="true"></span>
                                </a>
                            @endif  --}}

                            {{--  Boton Anular Solicitud de Compra  --}}
                            @if (($solicitud->ano_pro == session('ano_pro')) && (($solicitud->sta_sol == '0') || ($solicitud->sta_sol == '41') || ($solicitud->sta_sol == '12')))
                                <a href="{{ route('compras.proceso.solicitud_unidad.anular', [$solicitud->ano_pro, $solicitud->grupo, $solicitud->nro_req]) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Anular Solicitud de Compra">
                                    <span class="fas fa-times" aria-hidden="true"></span>
                                </a>
                            @endif

                            {{--  Boton Copiar Solicitud de Compra  --}}
                            @if (($solicitud->ano_pro == session('ano_pro')) && (($solicitud->sta_sol == '3') || ($solicitud->sta_sol == '51')))
                                <a href="{{ route('compras.proceso.solicitud_unidad.copiar', [$solicitud->ano_pro, $solicitud->grupo, $solicitud->nro_req]) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Crea Copia de Solicitud de Compras Anulada">
                                    <span class="fas fa-copy" aria-hidden="true"></span>
                                </a>
                            @endif

                            {{--  Boton Reversar - Anular Presupuesto Solicitud de Compra  --}}
                            @if (($solicitud->ano_pro == session('ano_pro')) && (($solicitud->sta_sol == '5') || ($solicitud->sta_sol == '61') || ($solicitud->sta_sol == '63')))
                                <a href="{{ route('compras.proceso.solicitud_unidad.reversar', [$solicitud->ano_pro, $solicitud->grupo, $solicitud->nro_req]) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Anular Presupuesto Solicitud de Compra">
                                    <span class="fas fa-calendar-times" aria-hidden="true"></span>
                                </a>
                            @endif

                            {{--  Boton Activar Solicitud de Compra  --}}
                            @if ($solicitud->ano_pro == session('ano_pro') && ($solicitud->sta_sol == '3' || $solicitud->fk_cod_cau == '15'))
                                <a class="btn-sm" type="button" wire:click="confirmActivarSolicitud({{ $solicitud->ano_pro }}, '{{ $solicitud->grupo }}', {{ $solicitud->nro_req }})" title="Activa Solicitud de Compra Anulada">
                                    <i class="fas fa-check" aria-hidden="true"></i>
                                </a>
                            @endif

                            {{--  Boton Aprobar - Precompromete Solicitud de Compra  --}}
                            @if ($solicitud->ano_pro == session('ano_pro') && $solicitud->sta_sol == '12')
                                {{--  <a class="btn-sm" type="button" wire:click="confirmAprobarSolicitud({{ $solicitud->ano_pro }}, '{{ $solicitud->grupo }}', {{ $solicitud->nro_req }})" title="Aprueba y Precompromete Solicitud de Compra">
                                    <i class="fas fa-thumbs-up"></i>
                                </a>  --}}
                                <a href="{{ route('compras.proceso.solicitud_unidad.precomprometer', [$solicitud->ano_pro, $solicitud->grupo, $solicitud->nro_req]) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Aprueba y Precompromete Solicitud de Compra">
                                    <span class="fas fa-thumbs-up" aria-hidden="true"></span>
                                </a>
                            @endif

                            {{--  Prueba sweetalert to route  --}}
                            {{--  <a href ="{{ route('year_setups.review_recall', ['id' => $goalmanager->employee_id])}}" class="btn btn-info" onclick="confirmation(event)">  --}}
                            {{--  <a href ="{{ route('compras.proceso.solicitud_unidad.anular', [$solicitud->ano_pro, $solicitud->grupo, $solicitud->nro_req]) }}" class="btn btn-info" onclick="confirmation(event)">
                                <i class="fas fa-minus-circle"></i> Recall
                            </a>  --}}
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

@push('scripts')
    <script>
        Livewire.on('swal:confirm', param => {
            Swal.fire({
                title: param['titulo'],
                text: param['mensaje'],
                icon: param['tipo'],
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                confirmButtonText: '¡Sí!',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if(result.isConfirmed){
                    Livewire.emitTo('administrativo.meru-administrativo.compras.proceso.solicitud-unidad-index', param['funcion'], param['ano_pro'], param['grupo'], param['nro_req'])
                }
            })
        })
    </script>
@endpush
