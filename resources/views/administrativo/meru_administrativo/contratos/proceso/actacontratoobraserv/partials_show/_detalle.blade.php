<!-- Divisor Detalle-->
    <div class="row col-12">
        <x-label for="tipo">&nbsp</x-label>
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Detalle</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12">
        <div class="form-group col-2">
            <x-label for="mto_anticipo">Monto Anticipo</x-label>
            <x-input name="mto_anticipo" class="form-control-sm text-sm-right " value="{{ $encnotaentrega->mto_anticipo }}" maxlength="2" disabled/>
        </div>

        <div class="form-group col-1">
            <x-label for="porc_ant">% Anticipo</x-label>
            <x-input name="porc_ant" class="form-control-sm text-sm-right " value="{{ $encnotaentrega->porc_ant }}" maxlength="2" disabled/>
        </div>

        <div class="form-group col-2">
            <x-label for="antc_amort">Amortización Anticipo</x-label>
            <x-input name="antc_amort" class="form-control-sm text-sm-right" value="{{ $encnotaentrega->antc_amort }}" maxlength="2" disabled/>
        </div>

        <div class="form-group col-2">
            <x-label for="mto_siniva">Monto Neto</x-label>
            <x-input name="mto_siniva" class="form-control-sm text-sm-right" type="text" value="{{ $encnotaentrega->mto_siniva }}" maxlength="2" disabled/>
        </div>



            <div class="form-group col-2">
                <x-label for="por_iva">% IVA</x-label>
                @if (!is_null($solservicio))
                    <x-input name="por_iva" class="form-control-sm text-sm-right" type="text" value="{{$solservicio->por_iva}}"  disabled/>
                @else
                    <x-input name="por_iva" class="form-control-sm text-sm-right" type="text" value="0.00"  disabled/>
                @endif
            </div>


        <div class="form-group col-2">
            <x-label for="mto_iva">Monto IVA</x-label>
            <x-input name="mto_iva" class="form-control-sm text-sm-right" type="text" value="{{ $encnotaentrega->mto_iva }}" maxlength="2" disabled/>
        </div>

        <div class="form-group col-2">
            <x-label for="mto_ent">Monto Entrada</x-label>
            <x-input name="mto_ent" class="form-control-sm text-sm-right" type="text" value="{{$encnotaentrega->mto_ent  }}" maxlength="2" disabled/>
        </div>

        <div class="form-group col-2">
            <x-label for="base_imponible">Base Imponible</x-label>
            <x-input name="base_imponible" class="form-control-sm text-sm-right" type="text" value="{{ $encnotaentrega->base_imponible }}" maxlength="2" disabled/>
        </div>

        <div class="form-group col-2">
            <x-label for="base_exenta">Base Exenta</x-label>
            <x-input name="base_exenta" class="form-control-sm text-sm-right" type="text" value="{{ $encnotaentrega->base_exenta }}" maxlength="2" disabled/>
        </div>

    </div>

    <!-- Divisor Detalle Nota Entrega-->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Detalle Nota Entrega</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12 " offset-2>
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
                  @if(count($encnotaentrega->detnotaentrega)>0 )
                    @foreach ($encnotaentrega->detnotaentrega as $detnotaentregaItem)
                        <tr>
                            <td class="text-center">{{ $detnotaentregaItem->nro_ren }} </td>
                            <td class="text-center">{{ $detnotaentregaItem->fk_cod_prod }} </td>
                            @if($des_con)
                                <td class="text-center">{{ $des_con->des_con}} </td>
                            @else
                                <td class="text-center">&nbsp</td>
                            @endif
                            <td class="text-center">{{ $detnotaentregaItem->cantidad }} </td>
                            <td class="text-center">{{ $detnotaentregaItem->saldo }} </td>
                            <td class="text-center">{{ $detnotaentregaItem->totrecep }} </td>
                            <td class="text-center">{{ $detnotaentregaItem->por_iva }} </td>
                            <td class="text-center">{{ $detnotaentregaItem->mon_iva }} </td>
                            <td class="text-center">{{ $detnotaentregaItem->tip_cod }} </td>
                            <td class="text-center">{{ $detnotaentregaItem->cod_pryacc }} </td>
                            <td class="text-center">{{ $detnotaentregaItem->cod_obj }} </td>
                            <td class="text-center">{{ $detnotaentregaItem->gerencia }} </td>
                            <td class="text-center">{{ $detnotaentregaItem->unidad }} </td>
                            <td class="text-center">{{ $detnotaentregaItem->cod_par }} </td>
                            <td class="text-center">{{ $detnotaentregaItem->cod_gen }} </td>
                            <td class="text-center">{{ $detnotaentregaItem->cod_esp }} </td>
                            <td class="text-center">{{ $detnotaentregaItem->cod_sub }} </td>
                            <td class="text-center">{{ $detnotaentregaItem->gasto }} </td>
                            <td class="text-center">{{ $detnotaentregaItem->cta_cont }} </td>
                            <td class="text-center">{{ $detnotaentregaItem->cta_x_pagar }} </td>

                        </tr>
                    @endforeach

                @endif
            </tbody>
        </table>
    </div>

    <!-- Divisor Estructura de Gastos-->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Estructura de Gastos</h5>
        </div>

        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12  offset-2">
        <table  class="table table-bordered table-striped table-hover text-nowrap table-responsive">   
            <thead>
                <tr align="center" style="background: rgba(128, 255, 0, 0.3); border: 1px solid rgba(100, 200, 0, 0.3);">
                    <th>TP</th>
                    <th>P/A</th>
                    <th>Objetivo</th>
                    <th>Gerencia.</th>
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
                @foreach ($encnotaentrega->detgastosnotaentrega as $detgastosnotaentregaItem)
                    <tr>
                        <td class="text-center"> {{ $detgastosnotaentregaItem->tip_cod }} </td>
                        <td class="text-center"> {{ $detgastosnotaentregaItem->cod_pryacc }} </td>
                        <td class="text-center"> {{ $detgastosnotaentregaItem->cod_obj }} </td>
                        <td class="text-center"> {{ $detgastosnotaentregaItem->gerencia }} </td>
                        <td class="text-center"> {{ $detgastosnotaentregaItem->unidad }} </td>
                        <td class="text-center"> {{ $detgastosnotaentregaItem->cod_par }} </td>
                        <td class="text-center"> {{ $detgastosnotaentregaItem->cod_gen }} </td>
                        <td class="text-center"> {{ $detgastosnotaentregaItem->cod_esp }} </td>
                        <td class="text-center"> {{ $detgastosnotaentregaItem->cod_sub }} </td>
                        <td class="text-center"> {{ $detgastosnotaentregaItem->mto_cau }} </td>
                        <td class="text-center"> {{ ($detgastosnotaentregaItem->causar== 1)? 'SI': 'NO' }} </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
