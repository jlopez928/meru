@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-12">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('compras.configuracion.grupo_producto.index') }}">Grupos de Productos</a></li>
					<li class="breadcrumb-item active text-bold">Ver Grupos de Productos</li>
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
					<x-slot:header>
						<h3 class="card-title text-bold">Grupos de Productos</h3>
					</x-slot>

					<x-slot:body>

                        <div class="row col-12">

                            <x-field class="form-group col-2 offset-1">
                                <x-label for="grupo">{{ __('Grupo') }}</x-label>
                                <x-input class="text-center form-control-sm" value="{{ $grupoproducto->grupo }}" readonly />
                            </x-field>

                            <x-field class="form-group col-6">
                                <x-label for="des_grupo">{{ __('Description') }}</x-label>
                                <x-input class="form-control-sm" value="{{ $grupoproducto->des_grupo }}" readonly />
                            </x-field>

                            <x-field class="form-group col-2">
                                <x-label for="sta_reg">{{ __('Status') }}</x-label>
                                <x-select class="form-control-sm" disabled>
                                    @foreach (\App\Enums\Administrativo\Meru_Administrativo\Estado::cases() as $estado)
                                        <option value="{{ $estado->value }}" @selected(old('sta_reg', $grupoproducto->sta_reg?->value) === $estado->value) >
                                            {{ $estado->name }}
                                        </option>
                                    @endforeach
                                </x-select>
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
