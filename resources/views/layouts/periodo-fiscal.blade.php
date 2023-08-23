<div class="text-center">
    <form id="periodo-form" action="{{ route('configuracion.control.registrocontrol.periodo_actual') }}" method="POST"
    style="font-size:12px !important;">
        @csrf
        <label for="ano_pro" class="text-white">Periodo Fiscal:&nbsp;</label>
        <select name="ano_pro" id="ano_pro" style="height:24px; line-height:14px; border-radius:0.25rem; color:#495057; background-color:#fff;" onchange="$('#periodo-form').submit();" {{ $periodos->count() == 1 ? 'disabled' : '' }}>
            <option value="" @selected(empty(session('ano_pro')) && $periodos->count() > 1)>Seleccione...</option>

            @foreach($periodos as $periodo)
                <option value="{{ $periodo }}" @selected($periodo == session('ano_pro'))>{{ $periodo }}</option>
            @endforeach
        </select>
    </form>
</div>
