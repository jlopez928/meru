@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-12">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('formulacion.configuracion.maestro_ley.index') }}">Maestro de Ley</a></li>
					<li class="breadcrumb-item active text-bold">Crear</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<x-form method="post" action="{{ route('formulacion.configuracion.maestro_ley.store') }}">
					@include('administrativo/meru_administrativo/formulacion/configuracion/maestro_ley/partials/_form')
				</x-form>
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
		$(function () {
		    $('.select2bs4').select2({
				//theme: 'bootstrap4',
		    }).on('change', function() {
				let ceco       = $('#centro_costo').val();
				let partida    = $('#partida_presupuestaria').val();
				let estructura = (ceco != '' && partida != '') ? ceco + partida.substr(1) : '';
		    	$('#estructura').val(estructura);
		    });
		});
	</script>
@endsection