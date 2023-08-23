
<!-- Divisor Detalle-->

    <div class="row col-12">
        <x-label for="tipo">&nbsp</x-label>
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Detalle</h5>
        </div>
        <div class="dropdown-divider col-12"  style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12">
        <div class="form-group col-2">
            <x-label  for="mto_anticipo">Monto Antic.</x-label>
            <x-input wire:model.defer="mto_anticipo" id="mto_anticipo" name="mto_anticipo"  x-mask="99999.99" class="form-control-sm text-sm-right " value="" maxlength="2" readonly/>
        </div>

        <div class="form-group col-2">
            <x-label for="porc_ant">% Anticipo</x-label>
            <x-input wire:model.defer="porc_ant" id="porc_ant" name="porc_ant" class="form-control-sm text-sm-right "  x-mask="999.99" value=""  readonly/>
        </div>

        <div class="form-group col-2">
            <x-label for="antc_amort">Amort. Anticipo</x-label>
            <x-input wire:model.defer="antc_amort" wire:keydown.tab.prevent="validarAmortizacion('antc_amort')" id="antc_amort" name="antc_amort"    class="form-control-sm text-sm-right" value="" maxlength="8" readonly/>
        </div>

        <div class="form-group col-2">
            <x-label for="mto_siniva">Monto Neto</x-label>
            <x-input wire:model.defer="mto_siniva" id="mto_siniva" name="mto_siniva"  x-mask="99999.99" class="form-control-sm text-sm-right" type="text" value="" maxlength="2" readonly/>
        </div>


        <div class="form-group col-2">
            <x-label for="por_iva">% IVA</x-label>
            {{--  @if (!is_null($solservicio))  --}}
                {{--  <x-input name="por_iva" class="form-control-sm text-sm-right" type="text" value=""  readonly/>
            @else  --}}
                <x-input wire:model.defer="por_iva" id="por_iva" name="por_iva"  x-mask="99999.99" class="form-control-sm text-sm-right" type="text" value="0.00"  readonly/>
            {{--  @endif  --}}
        </div>


        <div class="form-group col-2">
            <x-label for="mto_iva">Monto IVA</x-label>
            <x-input wire:model.defer="mto_iva" id="mto_iva" name="mto_iva"  x-mask="99999.99" class="form-control-sm text-sm-right" type="text" value="" maxlength="2" readonly/>
        </div>

        <div class="form-group col-2">
            <x-label for="mto_ent">Monto Entrada</x-label>
            <x-input wire:model.defer="mto_ent" id="mto_ent" name="mto_ent"  x-mask="99999.99" class="form-control-sm text-sm-right" type="text" value="" maxlength="2" readonly/>
        </div>

        <div class="form-group col-2">
            <x-label for="base_imponible">Base Imponible</x-label>
            <x-input wire:model.defer="base_imponible" id="base_imponible" name="base_imponible"  x-mask="99999.99" class="form-control-sm text-sm-right" type="text" value="" maxlength="2" readonly/>
        </div>

        <div class="form-group col-2">
            <x-label for="base_exenta">Base Exenta</x-label>
            <x-input wire:model.defer="base_exenta" id="base_exenta" name="base_exenta"  x-mask="99999.99" class="form-control-sm text-sm-right" type="text" value="" maxlength="2" readonly/>
        </div>

    </div>

  <!-- Divisor Detalle Nota Entrega-->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Detalle Nota Entrega</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12 ">
        <input id="hiddenDetalle" type="hidden" wire:model.defer ="listadoDetalle" name="listadoDetalle">
        <input id="hiddengastos"  type="hidden" wire:model.defer ="listadoGastos"  name="listadoGastos">
        <table  class="table table-bordered table-striped table-hover text-nowrap table-responsive">
            <thead>
                <tr align="center" style="background: rgba(128, 255, 0, 0.3); border: 1px solid rgba(100, 200, 0, 0.3);">
                    <th>Rgl</th>
                    <th>Cpto</th>
                    <th>Descripción</th>
                    <th>Monto Sol.</th>
                    <th>Pendiente</th>
                    <th>Entrega</th>
                    <th>% IVA</th>
                    <th>Monto IVA</th>
                    <th>TP</th>
                    <th>P/A</th>
                    <th>Obj.</th>
                    <th>Gcia.</th>
                    <th>Und.</th>
                    <th>Pa</th>
                    <th>Gn</th>
                    <th>Esp</th>
                    <th>Sub</th>
                    <th>Gasto</th>
                    <th>Cuenta</th>
                    <th>Cuenta X Pagar</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->detalle as $index => $detalle )
                <tr>
                    <td class="text-center">
                        {{ $detalle['nro_ren'] }}
                    </td>
                    <td class="text-center">
                        {{ $detalle['fk_cod_prod'] }}
                    </td>
                    <td class="text-center">
                        {{  $detalle['des_con'] }}
                    </td>
                    <td class="text-center">
                        {{  $detalle['cantidad'] }}
                    </td>
                    <td class="text-center">
                        {{  $detalle['saldo'] }}
                    </td>
                    <td class="text-center">
                        <x-input wire:model.defer="totrecep" wire:keydown.tab.prevent="estGastos('totrecep')" id="totrecep" name="totrecep" style="border: 1px solid rgba(95, 96, 95, 0.3);" class="form-control-sm text-sm-right border-0 "  x-mask="999.99" value="{{ $detalle['totrecep']? $detalle['totrecep']: 0.00 }}" />
                    </td>
                    <td class="text-center">
                        {{  $detalle['por_iva'] }}
                    </td>
                    <td class="text-center">
                        {{  $detalle['mon_iva'] }}
                    </td>
                    <td class="text-center">
                        {{  $detalle['tip_cod'] }}
                    </td>
                    <td class="text-center">
                        {{  $detalle['cod_pryacc'] }}
                    </td>
                    <td class="text-center">
                        {{ $detalle['cod_obj'] }}
                    </td>
                    <td class="text-center">
                        {{  $detalle['gerencia'] }}
                    </td>
                    <td class="text-center">
                        {{  $detalle['unidad'] }}
                    </td>
                    <td class="text-center">
                        {{  $detalle['cod_par'] }}
                    </td>
                    <td class="text-center">
                        {{  $detalle['cod_gen'] }}
                    </td>
                    <td class="text-center">
                        {{  $detalle['cod_esp'] }}
                    </td>
                    <td class="text-center">
                        {{ $detalle['cod_sub'] }}
                    </td>
                    <td class="text-center">
                        {{  $detalle['gasto'] }}
                    </td>
                    <td class="text-center">
                        {{  $detalle['cta_cont'] }}
                    </td>
                    <td class="text-center">
                        {{  $detalle['cta_x_pagar'] }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Divisor Estructura de Gastos-->
    <div class="row col-12" id="tituloGastos" style="visibility:hidden">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Estructura de Gastos</h5>
        </div>

        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12" id="gridGastos" style="visibility:hidden">
        <table  class="table table-bordered table-striped table-hover text-nowrap table-responsive">
            <thead>
                <tr align="center" style="background: rgba(128, 255, 0, 0.3); border: 1px solid rgba(100, 200, 0, 0.3);">
                    <th>TP</th>
                    <th>P/A</th>
                    <th>Objetivo</th>
                    <th>Gerencia</th>
                    <th>U. Ejec</th>
                    <th>Pa</th>
                    <th>Gn</th>
                    <th>Esp</th>
                    <th>Sub-Esp</th>
                    <th>Monto</th>
                    <th>C</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->detallegasto as $index => $detgasto )
                    <tr>
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
                            {{ $detgasto['mto_cau']  }}
                        </td>
                        <td class="text-center">
                            {{ $detgasto['causar']  }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

