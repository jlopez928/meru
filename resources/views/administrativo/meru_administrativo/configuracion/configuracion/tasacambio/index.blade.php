@extends('layouts.aplicacion')

@section('content')


<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-12">
            <div class="col-sm-6">
				<x-button class="btn-success" href="{{ route('configuracion.configuracion.tasacambio.create') }}" title="Nuevo"><i class="fas fa-plus-circle"></i> Nuevo</x-button>
                <x-button href="{{ route('configuracion.configuracion.tasacambio.print_tasacambio')}}" target="_blank" class="btn-primary" title="Generar PDF"><i class="fas fa-download"> Generar PDF</i></i></x-button>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('home') }}">Página principal</a></li>
					<li class="breadcrumb-item active text-bold">Listar Tasa Cambio</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
			    {{--  <livewire:administrativo.siher.configuracion.marca-component />--}}
               <livewire:administrativo.meru-administrativo.configuracion.configuracion.tasa-cambio-index />
			</div>
		</div>
	</div>
</section>
@endsection

 @section('sidebar')
    @include('layouts.sidebar')
@endsection

