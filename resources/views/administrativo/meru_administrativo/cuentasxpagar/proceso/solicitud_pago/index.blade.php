
@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-12">
            <div class="col-sm-6">
				  <x-button class="btn-success" href="{{ route('cuentasxpagar.proceso.solicititudpago.create') }}" title="Provisión"><i class="fas fa-plus-circle"></i> Nuevo</x-button>
                  <x-button href="{{ route('cuentasxpagar.proceso.print_solicitud_pago')}}" target="_blank" class="btn-primary" title="Generar PDF"><i class="fas fa-download"> Generar PDF</i></i></x-button>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('home') }}">Página principal</a></li>
					<li class="breadcrumb-item active text-bold">Listar Solicitud de Pago</li>
				</ol>
			</div>
		</div>
	</div>
</section>
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
                <livewire:administrativo.meru-administrativo.cuentasx-pagar.proceso.solicitud-pago-index />
			</div>
		</div>
	</div>
</section>

@endsection

@section('sidebar')
    @include('layouts.sidebar')
@endsection

