@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-12">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('configuracion.configuracion.unidadtributaria.index') }}">Unidad Tributaria</a></li>
					<li class="breadcrumb-item active text-bold">Ver Unidad Tributaria</li>
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
                            <h3 class="card-title text-bold">Unidad Tributaria</h3>
                        </x-slot>
                        <x-slot name="body">
                            <div class="row col-12">
                                <x-field class="form-group col-3 offset-1">
                                    <x-label for="id">{{ __('ID') }}</x-label>
                                    <x-input class="text-center form-control-sm"  name="id" value="{{ old('id', $unidadtributarium->id) }}" readonly/>
                                </x-field>
                                <x-field class="form-group col-3 ">
                                    <x-label for="bs_ut">Monto UT</x-label>
                                    <x-input name="bs_ut" class="form-control-sm {{ $errors->has('bs_ut') ? 'is-invalid' : '' }}" placeholder="Ingrese monto UT" value="{{ old('bs_ut', $unidadtributarium->bs_ut) }}" readonly  />
                                    <div class="invalid-feedback">
                                        @error('bs_ut') {{ $message }} @enderror
                                    </div>
                                </x-field>
                                <x-field class="form-group col-3 ">
                                    <x-label for="bs_ucau">Monto UCAU</x-label>
                                    <x-input name="bs_ucau" class="form-control-sm {{ $errors->has('bs_ucau') ? 'is-invalid' : '' }}" placeholder="Ingrese costo UT UCAU" value="{{ old('bs_ucau', $unidadtributarium->bs_ucau) }}" readonly />
                                    <div class="invalid-feedback">
                                        @error('bs_ucau') {{ $message }} @enderror
                                    </div>
                                </x-field>
                                <x-field class="form-group col-3 offset-1">
                                    <x-label for="usuario">Usuario</x-label>
                                    <x-input name="usuario" class="form-control-sm {{ $errors->has('usuario') ? 'is-invalid' : '' }}" placeholder="Ingrese costo UT UCAU" value="{{ old('usuario', $unidadtributarium->usuario) }}" readonly />
                                    <div class="invalid-feedback">
                                        @error('usuario') {{ $message }} @enderror
                                    </div>
                                </x-field>
                                <x-field class="form-group col-3 ">
                                    <x-label for="fec_ut">Fecha Vigencia</x-label>
                                    <x-input name="fec_ut" class="form-control-sm {{ $errors->has('fec_ut') ? 'is-invalid' : '' }}" type="date" placeholder="Ingrese fecha vigencia" value="{{ old('fec_ut', $unidadtributarium->fec_ut) }}" readonly />
                                    <div class="invalid-feedback">
                                        @error('fec_ut') {{ $message }} @enderror
                                    </div>
                                </x-field>
                                <x-field class="form-group col-3 ">
                                    <x-label for="vigente">Estado</x-label>
                                    <x-select   disabled readonly  name="vigente" class="form-control-sm {{ $errors->has('vigente') ? 'is-invalid' : '' }}">
                                        <option value="{{ old('vigente', $unidadtributarium->vigente) == '0' ? '0' : '1' }}" selected>{{ old('vigente', $unidadtributarium->vigente) == '0' ? 'INACTIVO' : 'ACTIVO' }}</option>
                                        <option value="{{ old('vigente', $unidadtributarium->vigente) == '0' ? '1' : '0'}}"> {{ old('vigente', $unidadtributarium->vigente) == '0' ? 'ACTIVO' : 'INACTIVO' }}</option>
                                      </x-select>
                                    <div class="invalid-feedback">
                                        @error('vigente') {{ $message }} @enderror
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
