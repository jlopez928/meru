@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-12">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('configuracion.configuracion.descuento.index') }}">Descuentos y Retenciones</a></li>
					<li class="breadcrumb-item active text-bold">Ver Descuentos y Retenciones</li>
				</ol>
			</div>
		</div>
	</div>
</section>
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
                    <x-card>
                        <x-slot name="header">
                            <h3 class="card-title text-bold">Descuentos y Retenciones</h3>
                        </x-slot>
                        <x-slot name="body">
                            <div class="row col-12">
                               <x-field class="form-group col-2 offset-1" >
                                    <x-label for="id">{{ __('ID') }}</x-label>
                                    <x-input class="text-center form-control-sm " name="id" value="{{  old('id', $descuento->id) }}" readonly/>
                                </x-field>
                                <x-field class="form-group col-2" >
                                    <x-label for="cod_des">Código</x-label>
                                    <x-input readonly  name="cod_des" class=" form-control-sm {{ $errors->has('cod_des') ? '' : '' }}" type="text" placeholder="Ingrese Código" value="{{ old('cod_des', $descuento->cod_des) }}"  />
                                    <div class="invalid-feedback">
                                        @error('cod_des') {{ $message }} @enderror
                                    </div>
                                </x-field>

                                <x-field class="form-group col-5 ">
                                    <x-label for="des_des">Descripción</x-label>
                                    <x-input readonly  name="des_des" class="form-control-sm {{ $errors->has('des_des') ? '' : '' }}" type="text" placeholder="Ingrese descripción" value="{{ old('des_des', $descuento->des_des) }}"  />
                                    <div class="invalid-feedback">
                                        @error('des_des') {{ $message }} @enderror
                                    </div>
                                </x-field>
                                {{--  se incluyen los combos que actualizan el código a traves de un componente  --}}
                                <x-field class="form-group col-2 offset-1" >
                                    <x-label for="cla_desc">&nbsp</x-label>
                                    <x-input name="cla_desc" class="form-control-sm {{ $errors->has('cla_desc') ? '' : '' }}" placeholder="Ingrese monto UT" value="{{ old('cla_desc', $descuento->cla_desc) }}" readonly  />
                                    <div class="invalid-feedback">
                                        @error('cla_desc') {{ $message }} @enderror
                                    </div>
                                </x-field>
                                <x-field class="form-group col-7 " >
                                    <x-label for="desccla_desc">Clase</x-label>
                                    <x-input name="cla_desc" class="form-control-sm {{ $errors->has('cla_desc') ? '' : '' }}" placeholder="Ingrese monto UT" value="{{ old('cla_desc', $descuento->cla_desc) }}" readonly  />
                                    <div class="invalid-feedback">
                                        @error('cla_desc') {{ $message }} @enderror
                                    </div>
                                </x-field>
                                <x-field class="form-group col-2 offset-1">
                                    <x-label for="residente">&nbsp</x-label>
                                    <x-input name="residente" class="form-control-sm{{ $errors->has('residente') ? '' : 'is-valid' }}" placeholder="Ingrese vigencia" value="{{ old('residente', $descuento->residente) }}"  readonly />
                                    <div class="invalid-feedback">
                                        @error('residente') {{ $message }} @enderror
                                    </div>
                                </x-field>
                                <x-field class="form-group col-7 ">
                                    <x-label for="residente">Ubicación</x-label>
                                    <x-input name="residente" class="form-control-sm {{ $errors->has('residente') ? 'is-invalid' : '' }}" placeholder="Ingrese vigencia" value="{{ old('residente', $descuento->residente) }}"  readonly />
                                    <div class="invalid-feedback">
                                        @error('residente') {{ $message }} @enderror
                                    </div>
                                </x-field>
                                <x-field class="form-group col-2 offset-1">
                                    <x-label for="tip_mto">&nbsp</x-label>
                                    <x-input name="tip_mto" class="form-control-sm {{ $errors->has('tip_mto') ? 'is-invalid' : '' }}"  placeholder="Ingrese tipo monto" value="{{ old('tip_mto', $descuento->tip_mto) }}" readonly />
                                    <div class="invalid-feedback">
                                        @error('tip_mto') {{ $message }} @enderror
                                    </div>
                                </x-field>
                                <x-field class="form-group col-4">
                                    <x-label for="tip_mto">Tipo Monto</x-label>
                                    <x-input name="tip_mto" class="form-control-sm {{ $errors->has('tip_mto') ? 'is-invalid' : '' }}"  placeholder="Ingrese tipo monto" value="{{ old('tip_mto', $descuento->tip_mto) }}" readonly />
                                    <div class="invalid-feedback">
                                        @error('tip_mto') {{ $message }} @enderror
                                    </div>
                                </x-field>
                                <x-field class="form-group col-3 ">
                                    <x-label for="por_islr">Porc. I.S.L.R.</x-label>
                                    <x-input readonly  name="por_islr" class="form-control-sm{{ $errors->has('por_islr') ? 'is-invalid' : '' }}" placeholder="Ingrese Porc. I.S.L.R." value="{{ old('por_islr', $descuento->por_islr) }}"   />
                                    <div class="invalid-feedback">
                                        @error('por_islr') {{ $message }} @enderror
                                    </div>
                                </x-field>
                                <x-field class="form-group col-3 offset-1">
                                    <x-label for="fecha">Fecha </x-label>
                                    <x-input name="fecha" readonly class="form-control-sm {{ $errors->has('fecha') ? 'invalid' : '' }}" type="date" placeholder="Ingrese fecha vigencia" value="{{  $descuento->fecha }}"  />
                                    <div class="invalid-feedback">
                                        @error('fecha') {{ $message }} @enderror
                                    </div>
                                </x-field>
                                <x-field class="form-group col-2 ">
                                    <x-label for="status">Estado</x-label>
                                    <x-select  readonly disabled  name="status" class="form-control-sm{{ $errors->has('status') ? '' : '' }}">
                                        <option value="{{ old('status', $descuento->status) == '0' ? '0' : '1' }}" selected>{{ old('status', $descuento->status) == '0' ? 'INACTIVO' : 'ACTIVO' }}</option>
                                        <option value="{{ old('status', $descuento->status) == '0' ? '1' : '0'}}"> {{ old('status', $descuento->status) == '0' ? 'ACTIVO' : 'INACTIVO' }}</option>
                                 </x-select>
                                    <div class="invalid-feedback">
                                        @error('status') {{ $message }} @enderror
                                    </div>
                                </x-field>

                                <x-field class="form-group col-3 "  style="visibility: hidden">
                                    <x-label for="id_des">Usuario</x-label>
                                    <x-input name="usuario" class="form-control-sm{{ $errors->has('id_des') ? '' : '' }}" placeholder="Ingrese costo UT UCAU" value="{{ auth()->user()->id }}"  />
                                    <div class="invalid-feedback">
                                        @error('usuario') {{ $message }} @enderror
                                    </div>
                                </x-field>
                                <x-field class="col-2"  style="visibility: hidden">
                                    <x-label for="id_des">id_des</x-label>
                                    <x-input name="id_des" class="form-control-sm{{ $errors->has('id_des') ? '' : '' }}" value="{{ old('id_des', '')  }}"  />
                                    <div class="invalid-feedback">
                                        @error('id_des') {{ $message }} @enderror
                                    </div>
                                </x-field>
                            </div>
                        </x-slot>
                    </x-card>
			</div>
		</div>
	</div>
</section>



@endsection

@section('sidebar')
	@include('layouts.sidebar')
@endsection
