<!-- Divisor Detalle Comprobante Contable-->
    <div class="row col-12">
        <x-label for="tipo">&nbsp</x-label>
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Detalle Comprobante Contable</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12 ">
        <table  class="table table-bordered table-striped table-hover text-nowrap">
            <thead>
                <tr align="center" style="background: rgba(128, 255, 0, 0.3); border: 1px solid rgba(100, 200, 0, 0.3);">
                    <th>Renglón</th>
                    <th>Cuenta Contable</th>
                    <th>Tipo Mov.</th>
                    <th>Monto</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($encnotaentrega->comprobantesopendetne as $comprobantesopendetneItem)
                    <tr>
                        <td class="text-center"> {{ $comprobantesopendetneItem->con_com }} </td>
                        <td class="text-center"> {{ $comprobantesopendetneItem->cod_cta }} </td>
                        <td class="text-center"> {{ $comprobantesopendetneItem->tip_mto }} </td>
                        <td class="text-center"> {{ $comprobantesopendetneItem->mto_doc }} </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

