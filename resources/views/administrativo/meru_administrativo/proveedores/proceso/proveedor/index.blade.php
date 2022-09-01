@extends('layouts.aplicacion')

@section('content')

    <section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-1">
                    <x-button class="btn-success" href="{{ route('proveedores.proceso.proveedor.create') }}" title="Nuevo"><i class="fas fa-plus-circle"></i> Nuevo</x-button>
				</div>
				<div class="col-sm-5">
                    {{--  <a target="_blank" href="{{ route('proveedores.proceso.proveedor.print_proveedores') }}" class="btn btn-sm  btn-primary text-bold" title="Generar PDF">Generar PDF</a>  --}}
				</div>
				<div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item text-bold"><a href="{{ route('home') }}">Página principal</a></li>
                        <li class="breadcrumb-item active text-bold">Proveedores</li>
                    </ol>
				</div>
			</div>
		</div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <livewire:administrativo.meru-administrativo.proveedores.proceso.proveedor-index />
                </div>
            </div>
        </div>
    </section>

@endsection

@section('sidebar')
    @include('layouts.sidebar')
@endsection
