<!-- Divisor Estructura de Gastos-->
    <div class="row col-12" id="tituloGastos" style="visibility:hidden">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Estructura de Gastos</h5>
        </div>

        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12 offset-2" id="gridGastos" >
        <table  class="table table-bordered table-striped table-hover text-nowrap table-responsive">
            <thead>
                <tr align="center" style="background: rgba(128, 255, 0, 0.3); border: 1px solid rgba(100, 200, 0, 0.3);">
                    <th>Gastos</th>
                    <th>TP</th>
                    <th>P/A</th>
                    <th>Obj.</th>
                    <th>Gcia.</th>
                    <th>U. Ejec</th>
                    <th>Pa</th>
                    <th>Gn</th>
                    <th>Esp</th>
                    <th>Sub-Esp</th>
                    <th>Monto</th>
                    <th>Monto a NC</th>
                    <th>Monto a Causar</th>
                    <th>Pagado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($factura->cxpdetgastosfactura as $index => $detgasto )
                    <tr>
                        <td class="text-center">
                            {{ $detgasto['gasto']? 'Si' : 'No' }}
                        </td>
                        <td class="text-center">
                            {{  $detgasto['tip_cod']  }}
                        </td>
                        <td class="text-center">
                            {{  $detgasto['cod_pryacc']  }}
                        </td>
                        <td class="text-center">
                            {{  $detgasto['cod_obj']  }}
                        </td>
                        <td class="text-center">
                            {{  $detgasto['gerencia']  }}
                        </td>
                        <td class="text-center">
                            {{  $detgasto['unidad']  }}
                        </td>
                        <td class="text-center">
                            {{  $detgasto['cod_par']  }}
                        </td>
                        <td class="text-center">
                            {{  $detgasto['cod_gen']  }}
                        </td>
                        <td class="text-center">
                            {{  $detgasto['cod_esp']  }}
                        </td>
                        <td class="text-center">
                            {{  $detgasto['cod_sub']  }}
                        </td>
                        <td class="text-center">
                            {{ $detgasto['mto_tra']  }}
                        </td>
                        <td class="text-center">
                            {{ $detgasto['mto_nc']  }}
                        </td>
                        <td class="text-center">
                            {{ $detgasto['sal_cau']  }}
                        </td>
                        <td class="text-center">
                            {{'Original' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
