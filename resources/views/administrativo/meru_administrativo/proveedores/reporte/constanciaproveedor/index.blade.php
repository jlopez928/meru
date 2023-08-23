@extends('layouts.aplicacion')

@section('content')


<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-12">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('home') }}">Página principal</a></li>
					<li class="breadcrumb-item active text-bold">Listar Proveeedores</li>
				</ol>
			</div>
		</div>
	</div>
</section>


<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
               <livewire:administrativo.meru-administrativo.proveedores.reporte.constancia-proveedor-index />
			</div>
		</div>
	</div>
</section>

@endsection

 @section('sidebar')
    @include('layouts.sidebar')
@endsection

