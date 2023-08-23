<!-- Divisor Devolucion-->
<div class="row col-12">
    <x-label for="tipo">&nbsp</x-label>
    <div class="col-12">
        <h5 class="card-title text-secondary text-bold">Datos de la Devolución</h5>
    </div>
    <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
</div>

<div class="row col-12 offset-2">
    <div class="form-group col-6">
        <x-label for="lresp_dev">Devolver A</x-label>
        {{--  <x-input name="resp_dev" class="form-control-sm text-sm-center " value="{{ $recepfactura->resp_dev }}"  disabled/>  --}}

        <x-select  id="resp_dev" name="resp_dev"  class="form-select  form-control-sm {{ $errors->has('resp_dev') ? 'is-invalid' : '' }}">
            <option value="">-- Seleccione --</option>
            @foreach ($gerencias  as $gerencia)
                <option value="{{$gerencia->cod_ger }}"> {{  $gerencia->des_ger}}</option>
            @endforeach
        </x-select>
        <div class="invalid-feedback">
            @error('resp_dev') {{ $message }} @enderror
        </div>
    </div>
    <div class="form-group col-2">
        <x-label for="fec_dev">Fecha de Devolución</x-label>
        <x-input name="fec_dev" class="form-control-sm text-sm-center" type="date"  value="{{ $recepfactura->fec_dev}}"  />
    </div>

</div>
<div class="row col-12 offset-2">
    <div class="form-group col-6 ">
        <x-label for="observaciones">Observaciones</x-label>
        <textarea  id="observaciones" name="observaciones"   class="form-control" rows="3" >{{ $recepfactura->observaciones }}</textarea>
    </div>
</div>

<!-- Divisor Devolucion-->
<div class="row col-12">
    <x-label for="tipo">&nbsp</x-label>
    <div class="col-12">
        <h5 class="card-title text-secondary text-bold">Causas de Devolución</h5>
    </div>
    <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
</div>
<div class="row col-12  offset-2" style="height: 295px; overflow: auto; width: 800px;">
    <table  id="causadev" name="causadev" class="table table-bordered table-striped table-hover text-nowrap table-responsive">
        <thead>
            <tr align="center" style="background: rgba(128, 255, 0, 0.3); border: 1px solid rgba(100, 200, 0, 0.3);">
                <th>Marcar</th>
                <th>Código</th>
                <th>Causa de la Devolución</th>

            </tr>
        </thead>

        <tbody>

        @if($accion=='devolver')
            @foreach ($faccausadevolucion as $faccausadevolucionaItem)
                <tr>
                    <td class="text-center">
                        <x-input wire:model.defer="marcar" type="checkbox" id="marcar" name="marcar[]"  class="form-check text-sm-right"  value="{{ $faccausadevolucionaItem->cod_dev }}" />
                    </td>
                    <td class="text-center">
                        <x-input  type="text" id="cod_dev[]" name="cod_dev[]"  class="form-control text-sm-right border-0"  value="{{ $faccausadevolucionaItem->cod_dev }}" />
                        </td>
                    <td class="text-center"> {{ $faccausadevolucionaItem->descrip_dev }} </td>
                </tr>
            @endforeach
        @endif

        </tbody>
    </table>
</div>

