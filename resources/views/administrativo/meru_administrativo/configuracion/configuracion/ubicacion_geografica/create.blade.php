@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-12">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('configuracion.configuracion.ubicacion_geografica.index') }}">Ubicaciones Geográficas</a></li>
					<li class="breadcrumb-item active text-bold">Registrar Ubicaciones Geograficas</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<x-form method="post" action="{{ route('configuracion.configuracion.ubicacion_geografica.store') }}">
					@include('administrativo/meru_administrativo/configuracion/configuracion/ubicacion_geografica/partials/_form')
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
