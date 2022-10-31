@switch($creditoItem->sta_reg->value)
    @case(0)
    @case(3)
    @case(5)
        <a href="{{ route('modificaciones.movimientos.credito_adicional.edit', $creditoItem) }}" type="button" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Editar">
            <span class="fa fa-edit" aria-hidden="true"></span>
        </a>
        &nbsp;
        <a href="{{ route('modificaciones.movimientos.credito_adicional.anular.edit', $creditoItem) }}" type="button" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Anular">
            <span class="fas fa-ban text-danger" aria-hidden="true"></span>
        </a>
        &nbsp;
        <a href="{{ route('modificaciones.movimientos.credito_adicional.aprobar.edit', $creditoItem) }}" type="button" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Aprobar">
            <span class="fas fa-check text-success" aria-hidden="true"></span>
        </a>
        @break
    @case(2)
        <a href="{{ route('modificaciones.movimientos.credito_adicional.reversar_aprobacion.edit', $creditoItem) }}" type="button" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Reversar Aprobación">
            <span class="fas fa-undo text-warning" aria-hidden="true"></span>
        </a>
        @break
@endswitch