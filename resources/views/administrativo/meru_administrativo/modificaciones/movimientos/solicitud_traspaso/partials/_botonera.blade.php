@switch($solicitudItem->sta_reg->value)
    @case(0)
    @case(2)
        <a href="{{ route('modificaciones.movimientos.solicitud_traspaso.edit', $solicitudItem) }}" type="button" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Editar">
            <span class="fa fa-edit" aria-hidden="true"></span>
        </a>
        &nbsp;
        <a href="{{ route('modificaciones.movimientos.solicitud_traspaso.aprobar.edit', $solicitudItem) }}" type="button" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Aprobar">
            <span class="fas fa-check text-success" aria-hidden="true"></span>
        </a>
        &nbsp;
        <a href="{{ route('modificaciones.movimientos.solicitud_traspaso.anular.edit', $solicitudItem) }}" type="button" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Anular">
            <span class="fas fa-times text-danger" aria-hidden="true"></span>
        </a>
        @break
    @case(1)
        <x-form id="print-form-{{ $solicitudItem->id }}" method="get" action="{{ route('modificaciones.movimientos.solicitud_traspaso.imprimir', $solicitudItem) }} " target="_blank">
            <a href="{{ route('modificaciones.movimientos.solicitud_traspaso.rechazar.edit', $solicitudItem) }}" type="button" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Rechazar">
                <span class="fas fa-ban text-danger" aria-hidden="true"></span>
            </a>
            &nbsp;
            <a href="#" id="{{ $solicitudItem->id }}" class="print-sol" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Imprimir" {{-- onclick="event.preventDefault();$('#print-form-{{ $solicitudItem->id }}').submit();" --}}>
                <span class="fas fa-print" aria-hidden="true"></span>
            </a>
        </x-form>
        @break
@endswitch