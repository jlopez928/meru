@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-12">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('configuracion.configuracion.tasacambio.index') }}">Tasa Cambio</a></li>
					<li class="breadcrumb-item active text-bold">Mostrar Tasa de Cambio</li>
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
                                <x-field class="form-group col-3 offset-1">
                                    <x-label for="id">{{ __('ID') }}</x-label>
                                    <x-input class="text-center form-control-sm }}"  name="id" value="{{ old('id', $tasacambio->id) }}" readonly/>
                                </x-field>
                                <x-field class="form-group col-3 ">
                                    <x-label for="fecha">Fecha Tasa</x-label>
                                    <x-input name="fec_tasa" class="form-control-sm {{ $errors->has('fec_tasa') ? 'is-invalid' : '' }}" type="date" placeholder="Ingrese fecha vigencia" value="{{ old('fec_tasa', $tasacambio->fec_tasa) }}" readonly />
                                    <div class="invalid-feedback">
                                        @error('fec_tasa') {{ $message }} @enderror
                                    </div>
                                </x-field>
                                <x-field class="form-group col-3">
                                    <x-label for="bs_tasa">Monto Tasa</x-label>
                                    <x-input name="bs_tasa" class="form-control-sm {{ $errors->has('bs_tasa') ? 'is-invalid' : '' }}" placeholder="Ingrese monto UT" value="{{ old('bs_tasa', $tasacambio->bs_tasa) }}" readonly  />
                                    <div class="invalid-feedback">
                                        @error('bs_tasa') {{ $message }} @enderror
                                    </div>
                                </x-field>
                                <x-field class="form-group col-3 offset-1">
                                    <x-label for="fecha">Fecha </x-label>
                                    <x-input name="fecha" class="form-control-sm {{ $errors->has('fecha') ? 'is-invalid' : '' }}" type="date" placeholder="Ingrese fecha vigencia" value="{{ old('fecha', $tasacambio->fecha) }}" readonly />
                                    <div class="invalid-feedback">
                                        @error('fecha') {{ $message }} @enderror
                                    </div>
                                </x-field>
                                <x-field class="form-group col-3">
                                    <x-label for="usuario">Usuario</x-label>
                                    <x-input name="usuario" class="form-control-sm {{ $errors->has('usuario') ? 'is-invalid' : '' }}" placeholder="Ingrese costo UT UCAU" value="{{ $tasacambio->usuario }}" readonly />
                                    <div class="invalid-feedback">
                                        @error('usuario') {{ $message }} @enderror
                                    </div>
                                </x-field>
                                <x-field class="form-group col-3 ">
                                    <x-label for="sta_reg">Estado</x-label>
                                    <x-select   disabled readonly  name="sta_reg" class="form-control-sm {{ $errors->has('sta_reg') ? 'is-invalid' : '' }}">
                                        <option value="{{ old('sta_reg', $tasacambio->sta_reg) == '0' ? '0' : '1' }}" selected>{{ old('sta_reg', $tasacambio->sta_reg) == '0' ? 'INACTIVO' : 'ACTIVO' }}</option>
                                        <option value="{{ old('sta_reg', $tasacambio->sta_reg) == '0' ? '1' : '0'}}"> {{ old('sta_reg', $tasacambio->sta_reg) == '0' ? 'ACTIVO' : 'INACTIVO' }}</option>
                                      </x-select>
                                    <div class="invalid-feedback">
                                        @error('sta_reg') {{ $message }} @enderror
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
