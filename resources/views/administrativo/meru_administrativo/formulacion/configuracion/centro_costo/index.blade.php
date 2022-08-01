@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<x-button class="btn-success" href="{{ route('formulacion.configuracion.centro_costo.create') }}" title="Nuevo"><i class="fas fa-plus-circle"></i> Nuevo</x-button>
				<x-button href="{{ route('formulacion.configuracion.centro_costo.print_centros_costos') }}" target="_blank" class="btn-primary" title="Generar PDF">
					<i class="fas fa-download"></i> Generar PDF
				</x-button>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('home') }}">P&aacute;gina principal</a></li>
					<li class="breadcrumb-item active text-bold">Listar Centros de Costo</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<livewire:administrativo.meru-administrativo.formulacion.configuracion.centro-costo-index />
			</div>
		</div>
	</div>
</section>

@endsection

@section('sidebar')
    @include('layouts.sidebar')
@endsection

@section('js')
    <script type="text/javascript">
        window.livewire.on('alert', param => {
			toastr[param['type']](param['message']);
		});
    </script>
@endsection
