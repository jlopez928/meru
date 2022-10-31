@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-2">
				<x-button class="btn-success" href="{{ route('modificaciones.movimientos.disminucion.create') }}" title="Nuevo"><i class="fas fa-plus-circle"></i> Nuevo</x-button>
			</div>
			<div class="col-sm-10">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('home') }}">P&aacute;gina principal</a></li>
					<li class="breadcrumb-item active text-bold">Modificaciones</li>
                    <li class="breadcrumb-item active text-bold">Movimientos</li>
                    <li class="breadcrumb-item active text-bold">Disminución</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<livewire:administrativo.meru-administrativo.modificaciones.movimientos.disminucion-index />
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
		$(document).on('click', '.print-sol', function (e) {
			e.preventDefault();
			$('#print-form-' + this.id).submit();
		});
	</script>
@endsection