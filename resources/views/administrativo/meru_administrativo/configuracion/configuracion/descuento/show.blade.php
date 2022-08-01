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
                            <h3 class="card-title text-bold">Tasa Cambio</h3>
                        </x-slot>
                        <x-slot name="body">
                            <div class="row col-12">
                                <x-field class="col-1">
                                    <x-label for="id">{{ __('ID') }}</x-label>
                                    <x-input name="id" value="{{ old('id', $descuento->id) }}" readonly/>
                                </x-field>
                                <x-field class="col-2">
                                    <x-label for="cod_des">Código</x-label>
                                    <x-input name="cod_des" class="{{ $errors->has('cod_des') ? 'is-invalid' : 'is-valid' }}" type="text" placeholder="Ingrese código de descuento" value="{{ old('cod_des', $descuento->cod_des) }}" readonly />
                                    <div class="invalid-feedback">
                                        @error('cod_des') {{ $message }} @enderror
                                    </div>
                                </x-field>
                                <x-field class="col-2">
                                    <x-label for="des_des">Descripción</x-label>
                                    <x-input name="des_des" class="{{ $errors->has('des_des') ? 'is-invalid' : 'is-valid' }}" type="text" placeholder="Ingrese descripción" value="{{ old('des_des', $descuento->des_des) }}" readonly />
                                    <div class="invalid-feedback">
                                        @error('des_des') {{ $message }} @enderror
                                    </div>
                                </x-field>
                                <x-field class="col-2">
                                    <x-label for="tip_mto">Tipo Monto</x-label>
                                    <x-input name="tip_mto" class="{{ $errors->has('tip_mto') ? 'is-invalid' : 'is-valid' }}" type="date" placeholder="Ingrese tipo monto" value="{{ old('tip_mto', $descuento->tip_mto) }}" readonly />
                                    <div class="invalid-feedback">
                                        @error('tip_mto') {{ $message }} @enderror
                                    </div>
                                </x-field>
                                <x-field class="col-2">
                                    <x-label for="cla_desc">Clase Desc.</x-label>
                                    <x-input name="cla_desc" class="{{ $errors->has('cla_desc') ? 'is-invalid' : 'is-valid' }}" placeholder="Ingrese monto UT" value="{{ old('cla_desc', $descuento->cla_desc) }}" readonly  />
                                    <div class="invalid-feedback">
                                        @error('cla_desc') {{ $message }} @enderror
                                    </div>
                                </x-field>
                                <x-field class="col-2">
                                    <x-label for="por_islr">Porc I.S.L.R.</x-label>
                                    <x-input name="por_islr" class="{{ $errors->has('por_islr') ? 'is-invalid' : 'is-valid' }}" placeholder="Ingrese vigencia" value="{{ old('por_islr', $descuento->por_islr) }}"  readonly />
                                    <div class="invalid-feedback">
                                        @error('por_islr') {{ $message }} @enderror
                                    </div>
                                </x-field>
                                <x-field class="col-2">
                                    <x-label for="residente">Ubicación</x-label>
                                    <x-input name="residente" class="{{ $errors->has('residente') ? 'is-invalid' : 'is-valid' }}" placeholder="Ingrese vigencia" value="{{ old('residente', $descuento->residente) }}"  readonly />
                                    <div class="invalid-feedback">
                                        @error('residente') {{ $message }} @enderror
                                    </div>
                                </x-field>
                                <x-field class="col-2">
                                    <x-label for="fecha">Fecha</x-label>
                                    <x-input name="fecha" class="{{ $errors->has('fecha') ? 'is-invalid' : 'is-valid' }}" placeholder="Ingrese costo UT UCAU" value="{{ old('fecha', $descuento->fecha) }}" readonly />
                                    <div class="invalid-feedback">
                                        @error('fecha') {{ $message }} @enderror
                                    </div>
                                </x-field>
                                <x-field class="col-2">
                                    <x-label for="status">Estado</x-label>
                                    <x-select  disabled readonly name="status" class="{{ $errors->has('status') ? 'is-invalid' : 'is-valid' }}">
                                        <option value="{{ old('status', $descuento->status) == '0' ? '0' : '1' }}" selected>{{ old('status', $descuento->status) == '0' ? 'INACTIVO' : 'ACTIVO' }}</option>
                                        <option value="{{ old('status', $descuento->status) == '0' ? '1' : '0'}}"> {{ old('status', $descuento->status) == '0' ? 'ACTIVO' : 'INACTIVO' }}</option>
                                 </x-select>
                                    <div class="invalid-feedback">
                                        @error('status') {{ $message }} @enderror
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
