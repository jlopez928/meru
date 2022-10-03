@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-12">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('modificaciones.movimientos.traspaso_presupuestario.index') }}">Traspaso Presupuestario</a></li>
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
				<x-form method="put" action="{{ route('modificaciones.movimientos.traspaso_presupuestario.update', $traspaso) }}">
					<livewire:administrativo.meru-administrativo.modificaciones.movimientos.traspaso-form :traspaso="$traspaso"/>
				</x-form>
			</div>
		</div>
	</div>
</section>

@endsection

@section('sidebar')
	@include('layouts.sidebar')
@endsection