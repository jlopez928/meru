<x-card>
    <x-slot name="header">
        <h3 class="card-title text-bold">Causa de Anulación</h3>
    </x-slot>
    <x-slot name="body">
        <div class="row col-12">
            <x-field class="text-center form-group col-1 offset-1" >
                 <x-label for="id">{{ __('ID') }}</x-label>
                 <x-input class="text-center form-control-sm " name="id" value="{{  old('id', $causaanulacion->id) }}" readonly/>
             </x-field>
             <x-field class="text-center form-group col-1" >
                 <x-label for="cod_cau">Código</x-label>
                 <x-input readonly  name="cod_cau" class="text-center form-control-sm {{ $errors->has('cod_cau') ? '' : '' }}" type="text" value="{{ old('cod_cau', $causaanulacion->cod_cau) }}"  />
                 <div class="invalid-feedback">
                     @error('cod_cau') {{ $message }} @enderror
                 </div>
             </x-field>
             <x-field class="form-group col-6" >
                <x-label for="des_cau">Descripción</x-label>
                <x-input name="des_cau" class=" form-control-sm {{ $errors->has('des_cau') ? 'is-invalid' : '' }}" type="text" placeholder="Ingrese descripción" value="{{ old('des_cau', $causaanulacion->des_cau) }}"  />
                <div class="invalid-feedback">
                    @error('des_cau') {{ $message }} @enderror
                </div>
            </x-field>
             <x-field class="form-group col-2 ">
                <x-label for="sta_reg">Estado</x-label>
                <x-select   name="sta_reg" class="form-control-sm{{ $errors->has('sta_reg') ? 'is-invalid' : '' }}">
                    <option value="{{ old('sta_reg', $causaanulacion->sta_reg) == '0' ? '0' : '1' }}" selected>{{ old('sta_reg', $causaanulacion->sta_reg) == '0' ? 'INACTIVO' : 'ACTIVO' }}</option>
                    <option value="{{ old('sta_reg', $causaanulacion->sta_reg) == '0' ? '1' : '0'}}"> {{ old('sta_reg', $causaanulacion->sta_reg) == '0' ? 'ACTIVO' : 'INACTIVO' }}</option>
             </x-select>
                <div class="invalid-feedback">
                    @error('sta_reg') {{ $message }} @enderror
                </div>
            </x-field>

         </div>
    </x-slot>

    <x-slot name="footer">
		<button type="submit" class="btn btn-sm btn-primary text-bold float-right">Guardar</button>
	</x-slot>
</x-card>
