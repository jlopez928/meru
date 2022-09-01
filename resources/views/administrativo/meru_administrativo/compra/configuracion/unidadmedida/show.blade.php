@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-12">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('compra.configuracion.unidadmedida.index') }}">Unidad de Medidas</a></li>
					<li class="breadcrumb-item active text-bold">Ver Unidad de Medida</li>
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
                            <h3 class="card-title text-bold">Unidad de Medida </h3>
                        </x-slot>
                        <x-slot name="body">
                            <div class="row col-12">
                               <x-field class="text-center form-group col-1 offset-1" >
                                    <x-label for="id">{{ __('ID') }}</x-label>
                                    <x-input class="text-center form-control-sm " name="id" value="{{  old('id', $unidadmedida->id) }}" readonly/>
                                </x-field>
                                <x-field class="text-center form-group col-1" >
                                    <x-label for="cod_uni">Código</x-label>
                                    <x-input readonly  name="cod_uni" class=" form-control-sm {{ $errors->has('cod_uni') ? '' : '' }}" type="text" placeholder="Ingrese Código" value="{{ old('cod_uni', $unidadmedida->cod_uni) }}"  />
                                    <div class="invalid-feedback">
                                        @error('cod_uni') {{ $message }} @enderror
                                    </div>
                                </x-field>

                                <x-field class="text-center form-group col-5 ">
                                    <x-label for="des_uni">Descripción</x-label>
                                    <x-input readonly  name="des_uni" class="form-control-sm {{ $errors->has('des_uni') ? '' : '' }}" type="text" placeholder="Ingrese descripción" value="{{ old('des_uni', $unidadmedida->des_uni) }}"  />
                                    <div class="invalid-feedback">
                                        @error('des_uni') {{ $message }} @enderror
                                    </div>
                                </x-field>
                                <x-field class="form-group col-2 ">
                                    <x-label for="sta_reg">Estado</x-label>
                                    <x-select  readonly  style="pointer-events: none"  name="sta_reg" class="form-control-sm{{ $errors->has('sta_reg') ? 'is-invalid' : '' }}">
                                        <option value="{{ old('sta_reg', $unidadmedida->sta_reg) == '0' ? '0' : '1' }}" selected>{{ old('sta_reg', $unidadmedida->sta_reg) == '0' ? 'INACTIVO' : 'ACTIVO' }}</option>
                                        <option value="{{ old('sta_reg', $unidadmedida->sta_reg) == '0' ? '1' : '0'}}"> {{ old('sta_reg', $unidadmedida->sta_reg) == '0' ? 'ACTIVO' : 'INACTIVO' }}</option>
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
