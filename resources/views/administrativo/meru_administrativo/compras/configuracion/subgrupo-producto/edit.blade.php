@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-12">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('compras.configuracion.subgrupo_producto.index') }}">SubGrupo de Productos</a></li>
					<li class="breadcrumb-item active text-bold">Editar SubGrupo de Productos</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<x-form method="put" action="{{ route('compras.configuracion.subgrupo_producto.update', $subgrupoproducto) }}">
					@include('administrativo/meru_administrativo/compras/configuracion/subgrupo-producto/partials/_form', [ 'accion' => 'edit'])
				</x-form>
			</div>
		</div>
	</div>
</section>

@endsection

@section('sidebar')
	@include('layouts.sidebar')
@endsection
