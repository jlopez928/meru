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

        <x-select  id="resp_dev" style="pointer-events:none" name="resp_dev"  class="form-select  form-control-sm {{ $errors->has('resp_dev') ? 'is-invalid' : '' }}" readonly>
            <option value="">-- Seleccione --</option>
            @foreach ($gerencias  as $gerencia)
                <option value="{{$gerencia->cod_ger }}" @if($gerencia->cod_ger == $recepfactura->resp_dev) selected @endif> {{  $gerencia->des_ger}}</option>
            @endforeach
        </x-select>
        <div class="invalid-feedback">
            @error('resp_dev') {{ $message }} @enderror
        </div>
    </div>
    <div class="form-group col-2">
        <x-label for="lfec_dev">Fecha de Devolución</x-label>
        <x-input name="fec_dev" class="form-control-sm text-sm-center" type="text"  value="{{($recepfactura->fec_dev)? $recepfactura->fec_dev->format('d-m-Y') : ''}}"  readonly/>
    </div>

</div>
<div class="row col-12 offset-2">
    <div class="form-group col-6 ">
        <x-label for="observaciones">Observaciones</x-label>
        <textarea  id="observaciones" name="observaciones"   class="form-control" rows="3"readonly>{{ $recepfactura->observaciones }}</textarea>
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
<div class="row col-12  offset-2">
    <table  id="causadev" name="causadev" wire:model.defer="causadev" class="table table-bordered table-striped table-hover text-nowrap table-responsive">
        <thead>
            <tr align="center" style="background: rgba(128, 255, 0, 0.3); border: 1px solid rgba(100, 200, 0, 0.3);">
                <th>Marcar</th>
                <th>Código</th>
                <th>Causa de la Devolución</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($this->causadev as $index => $causa )
            <tr>
                <td class="text-center">
                    {{ $causa['marcar'] }}
                </td>
                <td class="text-center">
                    {{ $causa['cod_dev'] }}
                </td>
                <td class="text-center">
                    {{  $causa['descrip_dev']['descrip_dev']  }}
                </td>

            </tr>
            @endforeach
        </tbody>
    </table>
</div>

