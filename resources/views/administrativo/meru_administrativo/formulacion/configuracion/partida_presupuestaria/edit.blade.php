@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-12">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('formulacion.configuracion.partida_presupuestaria.index') }}">Partida Presupuestaria</a></li>
					<li class="breadcrumb-item active text-bold">Editar</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<x-form method="put" action="{{ route('formulacion.configuracion.partida_presupuestaria.update', $partidaPresupuestaria) }}">
					@include('administrativo/meru_administrativo/formulacion/configuracion/partida_presupuestaria/partials/_form', ['accion' => 'editar'])
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
		    });
		});
	</script>
@endsection