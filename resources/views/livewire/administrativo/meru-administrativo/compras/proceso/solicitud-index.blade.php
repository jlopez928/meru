<x-datatable :list="$solicitudes">

    @if (count($solicitudes))
        <div class="table-responsive">
            <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers">
                @foreach ($solicitudes as $solicitud)
                    <tr>
                        <td align="center">{{ $solicitud->ano_pro }}</td>
                        <td align="center">{{ $solicitud->grupo_numero }}</td>
                        <td align="center">{{ \Carbon\Carbon::parse($solicitud->fec_emi)->format('d/m/Y') }}</td>
                        <td align="left">{{ $solicitud->gerencia->des_ger }}</td>
                        <td align="right">{{ $solicitud->monto_tot }}</td>
                        <td align="left">{{ $solicitud->estado->descripcion }}</td>
                        <td align="center">
                            @if ($modulo == 'unidad')
                                {{--  Boton Editar Solicitud de Compra  --}}
                                @if (($solicitud->ano_pro == session('ano_pro')))
                                    <a href="{{ route('compras.proceso.solicitud.unidad.edit', [$solicitud->ano_pro, $solicitud->grupo, $solicitud->nro_req]) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Modificar Solicitud de Compra">
                                        <span class="fas fa-edit" aria-hidden="true"></span>
                                    </a>
                                @endif

                                {{--  Boton Aprobar - Precompromete Solicitud de Compra  --}}
                                @if ($solicitud->ano_pro == session('ano_pro') && $solicitud->sta_sol == '12')
                                    <a href="{{ route('compras.proceso.solicitud.unidad.precomprometer', [$solicitud->ano_pro, $solicitud->grupo, $solicitud->nro_req]) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Aprueba y Precompromete Solicitud de Compra">
                                        <span class="fas fa-thumbs-up" aria-hidden="true"></span>
                                    </a>
                                @endif

                                {{--  Boton Anular Solicitud de Compra  --}}
                                @if (($solicitud->ano_pro == session('ano_pro')) && (($solicitud->sta_sol == '0') || ($solicitud->sta_sol == '41') || ($solicitud->sta_sol == '12')))
                                    <a href="{{ route('compras.proceso.solicitud.unidad.anular', [$solicitud->ano_pro, $solicitud->grupo, $solicitud->nro_req]) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Anular Solicitud de Compra">
                                        <span class="fas fa-times" aria-hidden="true"></span>
                                    </a>
                                @endif

                                {{--  Boton Reversar - Anular Presupuesto Solicitud de Compra  --}}
                                @if (($solicitud->ano_pro == session('ano_pro')) && (($solicitud->sta_sol == '5') || ($solicitud->sta_sol == '61') || ($solicitud->sta_sol == '63')))
                                    <a href="{{ route('compras.proceso.solicitud.unidad.reversar', [$solicitud->ano_pro, $solicitud->grupo, $solicitud->nro_req]) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Anular Presupuesto Solicitud de Compra">
                                        <span class="fas fa-calendar-times" aria-hidden="true"></span>
                                    </a>
                                @endif

                                {{--  Boton Copiar Solicitud de Compra  --}}
                                @if (($solicitud->ano_pro == session('ano_pro')) && (($solicitud->sta_sol == '3') || ($solicitud->sta_sol == '51')))
                                    <a href="{{ route('compras.proceso.solicitud.unidad.copiar', [$solicitud->ano_pro, $solicitud->grupo, $solicitud->nro_req]) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Crea Copia de Solicitud de Compras Anulada">
                                        <span class="fas fa-copy" aria-hidden="true"></span>
                                    </a>
                                @endif

                                {{--  Boton Activar Solicitud de Compra  --}}
                                @if ($solicitud->ano_pro == session('ano_pro') && ($solicitud->sta_sol == '3' || $solicitud->fk_cod_cau == '15'))
                                    <a href="{{ route('compras.proceso.solicitud.unidad.activar', [$solicitud->ano_pro, $solicitud->grupo, $solicitud->nro_req]) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Activa Solicitud de Compra Anulada">
                                        <i class="fas fa-check" aria-hidden="true"></i>
                                    </a>
                                @endif

                                {{--  Boton Ver Solicitud de Compra  --}}
                                <a href="{{ route('compras.proceso.solicitud.unidad.show', [$solicitud->ano_pro, $solicitud->grupo, $solicitud->nro_req]) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Ver Solicitud de Compra">
                                    <span class="fas fa-search" aria-hidden="true"></span>
                                </a>
                            @endif

                            @if ($modulo == 'compra_recibir')
                                {{--  Boton Ver Solicitud de Compra  --}}
                                <a href="{{ route('compras.proceso.solicitud.compra_recibir.show', [$solicitud->ano_pro, $solicitud->grupo, $solicitud->nro_req]) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Ver Solicitud de Compra">
                                    <span class="fas fa-search" aria-hidden="true"></span>
                                </a>

                                {{--  Boton Recepcionar Solicitud de Compra  --}}
                                @if ($solicitud->ano_pro == session('ano_pro') && ($solicitud->sta_sol == '5' || $solicitud->sta_sol == '61'))
                                    <a href="{{ route('compras.proceso.solicitud.compra_recibir.recepcionar', [$solicitud->ano_pro, $solicitud->grupo, $solicitud->nro_req]) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Recibir Solicitud de Compra">
                                        <i class="fas fa-check" aria-hidden="true"></i>
                                    </a>
                                @endif

                                {{--  Boton Devolver Solicitud de Compra  --}}
                                @if ($solicitud->ano_pro == session('ano_pro') && ($solicitud->sta_sol == '6' || $solicitud->sta_sol == '61' || $solicitud->sta_sol == '7'))
                                    <a href="{{ route('compras.proceso.solicitud.compra_recibir.devolver', [$solicitud->ano_pro, $solicitud->grupo, $solicitud->nro_req]) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Devolver la Solicitud de Compra">
                                        <i class="fas fa-undo" aria-hidden="true"></i>
                                    </a>
                                @endif

                                {{--  Boton Imprimir Devolucion  --}}
                                @if (!is_null($solicitud->fec_dev_com) || !is_null($solicitud->fec_dev_cont))
                                    <a href="{{ route('compras.proceso.solicitud.compra_recibir.imprimir', [$solicitud->ano_pro, $solicitud->grupo, $solicitud->nro_req]) }}" target="_blank" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Imprimir Devolución">
                                        <i class="fas fa-print" aria-hidden="true"></i>
                                    </a>
                                @endif

                                {{--  Boton Asignar Comprador  --}}
                                @if ($solicitud->ano_pro == session('ano_pro') && ($solicitud->sta_sol == '6' || $solicitud->sta_sol == '7'))
                                    <a href="{{ route('compras.proceso.solicitud.compra_recibir.comprador', [$solicitud->ano_pro, $solicitud->grupo, $solicitud->nro_req]) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Asignar Comprador a la Solicitud">
                                        <i class="fas fa-user" aria-hidden="true"></i>
                                    </a>
                                @endif

                                {{--  Boton Reasignar Solicitud  --}}
                                @if ($solicitud->ano_pro == session('ano_pro') && ($solicitud->sta_sol == 'TR' ||$solicitud->sta_sol == '6' || $solicitud->sta_sol == '7'))
                                    <a href="{{ route('compras.proceso.solicitud.compra_recibir.reasignar', [$solicitud->ano_pro, $solicitud->grupo, $solicitud->nro_req]) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Reasignar Unidad Contratante de la Solicitud">
                                        <i class="fas fa-exchange-alt" aria-hidden="true"></i>
                                    </a>
                                @endif
                            @endif

                            @if ($modulo == 'contratacion_recibir')
                                {{--  Boton Ver Solicitud de Compra  --}}
                                <a href="{{ route('compras.proceso.solicitud.contratacion_recibir.show', [$solicitud->ano_pro, $solicitud->grupo, $solicitud->nro_req]) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Ver Solicitud de Compra">
                                    <span class="fas fa-search" aria-hidden="true"></span>
                                </a>

                                {{--  Boton Recepcionar Solicitud de Compra  --}}
                                @if ($solicitud->ano_pro == session('ano_pro') && ($solicitud->sta_sol == '5' || $solicitud->sta_sol == '63'))
                                    <a href="{{ route('compras.proceso.solicitud.contratacion_recibir.recepcionar', [$solicitud->ano_pro, $solicitud->grupo, $solicitud->nro_req]) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Recibir Solicitud de Compra">
                                        <i class="fas fa-check" aria-hidden="true"></i>
                                    </a>
                                @endif

                                {{--  Boton Devolver Solicitud de Compra  --}}
                                @if ($solicitud->ano_pro == session('ano_pro') && ($solicitud->sta_sol == '62' || $solicitud->sta_sol == '63' || $solicitud->sta_sol == '71'))
                                    <a href="{{ route('compras.proceso.solicitud.contratacion_recibir.devolver', [$solicitud->ano_pro, $solicitud->grupo, $solicitud->nro_req]) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Devolver la Solicitud de Compra">
                                        <i class="fas fa-undo" aria-hidden="true"></i>
                                    </a>
                                @endif

                                {{--  Boton Imprimir Devolucion  --}}
                                @if (!is_null($solicitud->fec_dev_com) || !is_null($solicitud->fec_dev_cont))
                                    <a href="{{ route('compras.proceso.solicitud.contratacion_recibir.imprimir', [$solicitud->ano_pro, $solicitud->grupo, $solicitud->nro_req]) }}" target="_blank" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Imprimir Devolución">
                                        <i class="fas fa-print" aria-hidden="true"></i>
                                    </a>
                                @endif

                                {{--  Boton Asignar Comprador  --}}
                                @if ($solicitud->ano_pro == session('ano_pro') && ($solicitud->sta_sol == '62' || $solicitud->sta_sol == '71'))
                                    <a href="{{ route('compras.proceso.solicitud.contratacion_recibir.comprador', [$solicitud->ano_pro, $solicitud->grupo, $solicitud->nro_req]) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Asignar Comprador a la Solicitud">
                                        <i class="fas fa-user" aria-hidden="true"></i>
                                    </a>
                                @endif

                                {{--  Boton Reasignar Solicitud  --}}
                                @if ($solicitud->ano_pro == session('ano_pro') && ($solicitud->sta_sol == 'TR' ||$solicitud->sta_sol == '62' || $solicitud->sta_sol == '71'))
                                    <a href="{{ route('compras.proceso.solicitud.contratacion_recibir.reasignar', [$solicitud->ano_pro, $solicitud->grupo, $solicitud->nro_req]) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Reasignar Unidad Contratante de la Solicitud">
                                        <i class="fas fa-exchange-alt" aria-hidden="true"></i>
                                    </a>
                                @endif
                            @endif

                            @if ($modulo == 'presupuesto')
                                {{--  Boton Ver Solicitud de Compra  --}}
                                <a href="{{ route('compras.proceso.solicitud.presupuesto.show', [$solicitud->ano_pro, $solicitud->grupo, $solicitud->nro_req]) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Ver Solicitud de Compra">
                                    <span class="fas fa-search" aria-hidden="true"></span>
                                </a>

                                {{--  Boton Conformacion Presupuestaria Solicitud de Compra  --}}
                                @if ($solicitud->ano_pro == session('ano_pro') && $solicitud->sta_sol == '0')
                                    <a href="{{ route('compras.proceso.solicitud.presupuesto.aprobar', [$solicitud->ano_pro, $solicitud->grupo, $solicitud->nro_req]) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Conformación Presupuestaria Solicitud de Compra">
                                        <i class="fas fa-check" aria-hidden="true"></i>
                                    </a>
                                @endif

                                {{--  Boton Anular Presupuestario Solicitud de Compra  --}}
                                @if ($solicitud->ano_pro == session('ano_pro') && ($solicitud->sta_sol == '5' || $solicitud->sta_sol == '61' || $solicitud->sta_sol == '63'))
                                    <a href="{{ route('compras.proceso.solicitud.presupuesto.reversar', [$solicitud->ano_pro, $solicitud->grupo, $solicitud->nro_req]) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Anular Presupuesto Solicitud de Compra">
                                        <i class="fas fa-calendar-times" aria-hidden="true"></i>
                                    </a>
                                @endif
                            @endif

                            @if ($modulo == 'unidad_donante')
                                {{--  Boton Ver Solicitud de Compra  --}}
                                <a href="{{ route('compras.proceso.solicitud.unidad_donante.show', [$solicitud->ano_pro, $solicitud->grupo, $solicitud->nro_req]) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Ver Solicitud de Compra">
                                    <span class="fas fa-search" aria-hidden="true"></span>
                                </a>

                                {{--  Boton Conformacion Presupuestaria Solicitud de Compra  --}}
                                {{--  @if ($solicitud->ano_pro == session('ano_pro') && $solicitud->sta_sol == '0')
                                    <a href="{{ route('compras.proceso.solicitud.presupuesto.aprobar', [$solicitud->ano_pro, $solicitud->grupo, $solicitud->nro_req]) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Conformación Presupuestaria Solicitud de Compra">
                                        <i class="fas fa-check" aria-hidden="true"></i>
                                    </a>
                                @endif  --}}

                                {{--  Boton Anular Presupuestario Solicitud de Compra  --}}
                                {{--  @if ($solicitud->ano_pro == session('ano_pro') && ($solicitud->sta_sol == '5' || $solicitud->sta_sol == '61' || $solicitud->sta_sol == '63'))
                                    <a href="{{ route('compras.proceso.solicitud.presupuesto.reversar', [$solicitud->ano_pro, $solicitud->grupo, $solicitud->nro_req]) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Anular Presupuesto Solicitud de Compra">
                                        <i class="fas fa-calendar-times" aria-hidden="true"></i>
                                    </a>
                                @endif  --}}
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
