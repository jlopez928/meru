<br>    <br>
<div class="col-12">
    <h5 class="card-title text-secondary text-bold">COMPROBANTE CONTABLE DE NOTA DE CRÉDITO/DÉBITO</h5>
</div>
<div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>

<div class="mb-2">
    <input id="listadoDetalle" type="hidden" wire:model.defer ="listadoDetalle"   name="listadoDetalle">
    <table  class="table table-bordered table-sm text-center " >
        <thead class="">
            <tr class="table-primary">
                <th style="width:50px">Nro</th>
                <th style="width:50px">Cuenta</th>
                <th style="width:50px">Tipo</th>
                <th style="width:50px">Monto</th>
            </tr>
        </thead >
        <tbody>
            @forelse ($solicititudpago->cxpdetcomprofacturasnc as $index => $detallegasto)
            <tr>
                <td class="text-center">
                    {{ $detallegasto['nro_ren'] }}
                </td>
                <td class="text-left">
                    {{  $detallegasto['cod_cta'] }}-{{ $detallegasto->plancontable->nom_cta }}
                </td>
                <td class="text-center">
                    {{  $detallegasto['tipo'] }}
                </td>
                <td class="text-center">
                    {{  $detallegasto['monto'] }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="13" class="text-center"></td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
