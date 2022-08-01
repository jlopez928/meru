@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-12">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('configuracion.configuracion.tasacambio.index') }}">Tasa Cambio</a></li>
					<li class="breadcrumb-item active text-bold">Mostrar</li>
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
                                    <x-input name="id" value="{{ old('id', $tasacambio->id) }}" readonly/>
                                </x-field>
                                <x-field class="col-2">
                                    <x-label for="fec_tasa">Fecha Tasa</x-label>
                                    <x-input name="fec_tasa" class="{{ $errors->has('fec_tasa') ? 'is-invalid' : 'is-valid' }}" type="date" placeholder="Ingrese fecha vigencia" value="{{ old('fec_tasa', $tasacambio->fec_tasa) }}" readonly />
                                    <div class="invalid-feedback">
                                        @error('fec_tasa') {{ $message }} @enderror
                                    </div>
                                </x-field>
                                <x-field class="col-2">
                                    <x-label for="bs_tasa">Monto Tasa</x-label>
                                    <x-input name="bs_tasa" class="{{ $errors->has('bs_tasa') ? 'is-invalid' : 'is-valid' }}" placeholder="Ingrese monto UT" value="{{ old('bs_tasa', $tasacambio->bs_tasa) }}" readonly  />
                                    <div class="invalid-feedback">
                                        @error('bs_tasa') {{ $message }} @enderror
                                    </div>
                                </x-field>
                                <x-field class="col-2">
                                    <x-label for="sta_reg">Vigencia</x-label>
                                    <x-input name="sta_reg" class="{{ $errors->has('sta_reg') ? 'is-invalid' : 'is-valid' }}" placeholder="Ingrese vigencia" value="{{ old('sta_reg', $tasacambio->sta_reg) }}"  readonly />
                                    <div class="invalid-feedback">
                                        @error('sta_reg') {{ $message }} @enderror
                                    </div>
                                </x-field>
                                <x-field class="col-2">
                                    <x-label for="fecha">Fecha</x-label>
                                    <x-input name="fecha" class="{{ $errors->has('fecha') ? 'is-invalid' : 'is-valid' }}" placeholder="Ingrese costo UT UCAU" value="{{ old('fecha', $tasacambio->fecha) }}" readonly />
                                    <div class="invalid-feedback">
                                        @error('fecha') {{ $message }} @enderror
                                    </div>
                                </x-field>
                                <x-field class="col-2">
                                    <x-label for="usuario">Usuario</x-label>
                                    <x-input name="usuario" class="{{ $errors->has('usuario') ? 'is-invalid' : 'is-valid' }}" placeholder="Ingrese costo UT UCAU" value="{{ auth()->user()->email }}" readonly />
                                    <div class="invalid-feedback">
                                        @error('usuario') {{ $message }} @enderror
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
