@extends('layouts.aplicacion')

@section('content')

    <section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-4">
                    <x-button class="btn-success" href="{{ route('compras.proceso.solicitud_unidad.create', ['opcion' => 1]) }}" title="Crear Solicitud de Compra"><i class="fas fa-plus-circle"></i> Nuevo</x-button>
                    <x-button class="btn-success" href="{{ route('compras.proceso.solicitud_unidad.create', ['opcion' => 0]) }}" title="Crear Solicitud de Compra Sin Precompromiso"><i class="fas fa-plus-circle"></i> Nuevo Sin Pre</x-button>
				</div>
				<div class="col-sm-2">
                    {{--  <a target="_blank" href="{{ route('proveedores.proceso.proveedor.print_proveedores') }}" class="btn btn-sm  btn-primary text-bold" title="Generar PDF">Generar PDF</a>  --}}
				</div>
				<div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item text-bold"><a href="{{ route('home') }}">Página principal</a></li>
                        <li class="breadcrumb-item active text-bold">Solicitudes Unidad</li>
                    </ol>
				</div>
			</div>
		</div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <livewire:administrativo.meru-administrativo.compras.proceso.solicitud-unidad-index />
                </div>
            </div>
        </div>
    </section>

@endsection

@section('sidebar')
    @include('layouts.sidebar')
@endsection
