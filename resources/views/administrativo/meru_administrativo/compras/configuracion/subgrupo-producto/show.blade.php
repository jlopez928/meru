@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-12">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('compras.configuracion.subgrupo_producto.index') }}">SubGrupo de Productos</a></li>
					<li class="breadcrumb-item active text-bold">Ver SubGrupo de Productos</li>
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
						<h3 class="card-title text-bold">SubGrupo de Productos</h3>
					</x-slot>

					<x-slot:body>
                        <div class="row col-12">
                            <x-field class="col-2 offset-1">
                                <x-label for="grupo">Grupo</x-label>
                                <x-input class="text-center form-control-sm" value="{{ $subgrupo->grupo }}" readonly />
                            </x-field>

                            <x-field class="col-8">
                                <x-label for="des_grupo">Descripción</x-label>
                                <x-input class="form-control-sm" value="{{ $subgrupo->grupoproducto->des_grupo }}" readonly />
                            </x-field>
                        </div>
                        
						<div class="row col-12">
                            <x-field class="col-2 offset-1">
                                <x-label for="subgrupo">SubGrupo</x-label>
                                <x-input class="text-center form-control-sm" value="{{ $subgrupo->subgrupo }}" readonly />
                            </x-field>

                            <x-field class="col-8">
                                <x-label for="des_subgrupo">Descripción</x-label>
                                <x-input class="form-control-sm" value="{{ $subgrupo->des_subgrupo }}" readonly />
                            </x-field>
                        </div>

						<div class="row col-12">
                            <x-field class="col-2 offset-1">
                                <x-label for="sta_reg">{{ __('Status') }}</x-label>
                                <x-select class="form-control-sm" disabled>
                                    @foreach (\App\Enums\Administrativo\Meru_Administrativo\Estado::cases() as $estado)
                                        <option value="{{ $estado->value }}" @selected(old('sta_reg', $subgrupo->sta_reg?->value) === $estado->value) >
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
