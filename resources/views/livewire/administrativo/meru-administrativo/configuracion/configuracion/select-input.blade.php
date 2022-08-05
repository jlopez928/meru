<div style="display:contents">
      <x-field class="form-group col-2 offset-1">
          <x-label for="cla_desc">&nbsp</x-label>
          <x-input wire:model="cla_desc" name="cla_desc" class=" form-control-sm {{ $errors->has('cla_desc') ? 'is-invalid' : '' }}" type="text"  value="{{ old('cla_desc', $descuento->cla_desc) }}" readonly />
          <div class="invalid-feedback">
              @error('cla_desc') {{ $message }} @enderror
          </div>
      </x-field>
      <x-field class="form-group col-7 ">
          <x-label for="adm_retencion_id">Clase</x-label>
          <x-select  wire:model="selectedRetencion" name="adm_retencion_id" class=" form-control-sm {{ $errors->has('adm_retencion_id') ? 'is-invalid' : '' }}">
              <option value="{{'0' }}" > {{ 'Seleccione' }}</option>
                  @foreach ($retencion as $retItem)
                       <option value="{{ $retItem->id }}" @selected(old('adm_retencion_id', $descuento->adm_retencion_id) == $retItem->id)>       {{ $retItem->des_ret }}                 </option>
                  @endforeach
          </x-select>
          <div class="invalid-feedback">
              @error('adm_retencion_id') {{ $message }} @enderror
          </div>
      </x-field>
      {{--  Combo para la ubicación o residencia  --}}
      <x-field class="form-group col-2 offset-1">
          <x-label for="residente">&nbsp</x-label>
          <x-input wire:model="residente" name="residente" class=" form-control-sm {{ $errors->has('residente') ? 'is-invalid' : '' }}" type="text"   readonly />
          <div class="invalid-feedback">
              @error('residente') {{ $message }} @enderror
          </div>
      </x-field>
      <x-field class="form-group col-7 ">
          <x-label for="adm_residencia_id">Ubicación</x-label>
          <x-select  wire:model="selectedResidencia" name="adm_residencia_id" class=" form-control-sm {{ $errors->has('adm_residencia_id') ? 'is-invalid' : '' }}">
              <option value="{{'0' }}" > {{ 'Seleccione' }}</option>
                  @foreach ($residencia as $resItem)
                      <option value="{{ $resItem->id }}"  @selected(old('adm_residencia_id', $descuento->adm_residencia_id) == $resItem->id)> {{ $resItem->descripcion }}</option>
                  @endforeach
          </x-select>
          <div class="invalid-feedback">
              @error('adm_residencia_id') {{ $message }} @enderror
          </div>
      </x-field>
      {{--  combo para los tipos de monto  --}}
      <x-field class="form-group col-2 offset-1">
          <x-label for="tip_mto">&nbsp</x-label>
          <x-input wire:model="tip_mto" name="tip_mto" class=" form-control-sm {{ $errors->has('tip_mto') ? 'is-invalid' : '' }}" type="text" value="{{ old('tip_mto', $descuento->tip_mto) }}" readonly />
          <div class="invalid-feedback">
              @error('tip_mto') {{ $message }} @enderror
          </div>
      </x-field>
      <x-field class="form-group col-4 ">
          <x-label for="tipo_montos_id">Tipo Monto</x-label>
          <x-select wire:model="selectedTipoMonto" name="tipo_montos_id" class=" form-control-sm {{ $errors->has('tipo_montos_id') ? 'is-invalid' : '' }}">
              <option value="{{'0' }}" > {{ 'Seleccione' }}</option>
              @foreach ($tipomontos as $tipomonto)
                  {{ $descuento->tipo_montos_id }}/ {{ $tipomonto->id }}/{{ $tipomonto->codigo }}
                  <option value="{{ $tipomonto->id }}" @selected(old('tipo_montos_id', $descuento->tipo_montos_id) == $tipomonto->id)>       {{ $tipomonto->descripcion }}                 </option>
              @endforeach
          </x-select>
          <div class="invalid-feedback">
              @error('tipo_montos_id') {{ $message }} @enderror
          </div>
      </x-field>
  </div>
