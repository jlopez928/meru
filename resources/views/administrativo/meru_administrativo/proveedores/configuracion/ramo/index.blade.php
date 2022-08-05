@extends('layouts.aplicacion')

@section('content')


<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-12">
            <div class="col-sm-6">
				<x-button class="btn-success" href="{{ route('proveedores.configuracion.ramo.create') }}" title="Nuevo"><i class="fas fa-plus-circle"></i> Nuevo</x-button>
                <x-button href="{{ route('proveedores.configuracion.ramo.print_ramos')}}" target="_blank" class="btn-primary" title="Generar PDF"><i class="fas fa-download"> Generar PDF</i></i></x-button>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('home') }}">Página principal</a></li>
					<li class="breadcrumb-item active text-bold">Listar Ramos</li>
				</ol>
			</div>
		</div>
	</div>
</section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <livewire:administrativo.meru-administrativo.proveedores.configuracion.ramo-index />
                </div>
            </div>
        </div>
    </section>

@endsection

@section('sidebar')
    @include('layouts.sidebar')
@endsection
