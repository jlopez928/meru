<!-- Divisor Asientos-->
    <div class="row col-12" id="tituloGastos" style="visibility:hidden">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Estructura de Gastos</h5>
        </div>

        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12 offset-2" id="gridasientos" >
        <table  class="table table-bordered table-striped table-hover text-nowrap table-responsive">
            <thead>
                <tr align="center" style="background: rgba(128, 255, 0, 0.3); border: 1px solid rgba(100, 200, 0, 0.3);">
                    <th>Nro.</th>
                    <th>Cuenta</th>
                    <th>Tipo</th>
                    <th>Monto</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($factura->cxpdetcomprofacturas as $index => $asiento )
                    <tr>
                        <td class="text-center">
                            {{ $asiento['nro_ren']}}
                        </td>
                        <td class="text-center">
                            {{  $asiento['cod_cta']  }}
                        </td>
                        <td class="text-center">
                            {{  $asiento['tipo']  }}
                        </td>
                        <td class="text-center">
                            {{  $asiento['monto']  }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
