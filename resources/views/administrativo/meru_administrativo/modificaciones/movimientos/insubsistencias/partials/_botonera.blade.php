@switch($insubsistenciaItem->sta_reg->value)
    @case(0)
    @case(4)
    @case(5)
        <a href="{{ route('modificaciones.movimientos.insubsistencia.edit', $insubsistenciaItem) }}" type="button" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Editar">
            <span class="fa fa-edit" aria-hidden="true"></span>
        </a>
        &nbsp;
        <a href="{{ route('modificaciones.movimientos.insubsistencia.apartar.edit', $insubsistenciaItem) }}" type="button" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Apartar">
            <span class="fa fa-check text-success" aria-hidden="true"></span>
        </a>
        &nbsp;
        <a href="{{ route('modificaciones.movimientos.insubsistencia.anular.edit', $insubsistenciaItem) }}" type="button" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Anular">
            <span class="fas fa-ban text-danger" aria-hidden="true"></span>
        </a>
        @break
    @case(1)
        <a href="{{ route('modificaciones.movimientos.insubsistencia.reversar_apartado.edit', $insubsistenciaItem) }}" type="button" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Reversar Apartado">
            <span class="fas fa-undo text-warning" aria-hidden="true"></span>
        </a>
        &nbsp;
        <a href="{{ route('modificaciones.movimientos.insubsistencia.aprobar.edit', $insubsistenciaItem) }}" type="button" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Aprobar">
            <span class="fas fa-check text-success" aria-hidden="true"></span>
        </a>
        @break
    @case(2)
        <a href="{{ route('modificaciones.movimientos.insubsistencia.reversar_aprobacion.edit', $insubsistenciaItem) }}" type="button" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Reversar Aprobación">
            <span class="fas fa-undo text-warning" aria-hidden="true"></span>
        </a>
        @break
@endswitch