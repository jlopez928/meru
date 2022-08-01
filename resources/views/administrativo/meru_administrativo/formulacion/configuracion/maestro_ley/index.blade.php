@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<x-button class="btn-success" href="{{ route('formulacion.configuracion.maestro_ley.create') }}" title="Nuevo"><i class="fas fa-plus-circle"></i> Nuevo</x-button>
				<x-button class="btn-primary" href="{{ route('formulacion.configuracion.maestro_ley.importar.create') }}" title="Nuevo"><i class="fas fa-file-import"></i> Importar Excel</x-button>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('home') }}">P&aacute;gina principal</a></li>
					<li class="breadcrumb-item active text-bold">Listar Maestro de Ley</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<livewire:administrativo.meru-administrativo.formulacion.configuracion.maestro-ley-index />
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
