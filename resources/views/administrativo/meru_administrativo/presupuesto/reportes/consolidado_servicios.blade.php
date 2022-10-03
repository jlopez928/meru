@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-12">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('home') }}">Página Principal</a></li>
					<li class="breadcrumb-item active text-bold">Control Presupuestario</li>
					<li class="breadcrumb-item active text-bold">Reportes</li>
					<li class="breadcrumb-item active text-bold">Consolidado de Servicios</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<x-form method="post" action="{{ route('presupuesto.reporte.consolidado_servicios.store') }}" target="_blank">
					<x-card>
						<x-slot name="header">
							<h3 class="card-title text-bold">Consolidado de Servicios</h3>
						</x-slot>

						<x-slot name="body">

							<div class="row col-12">
								<div class="form-group col-3 offset-3">
                                    <x-label for="fec_ini">Fecha inicial</x-label>
                                    <x-input  name="fec_ini" type="date" class="text-center form-control-sm {{ $errors->has('fec_ini') ? 'is-invalid' : '' }}" required/>
								</div>

                                <div class="form-group col-3">
                                    <x-label for="fec_fin">Fecha final</x-label>
                                    <x-input  name="fec_fin" type="date" class="text-center form-control-sm {{ $errors->has('fec_fin') ? 'is-invalid' : '' }}" required/>
								</div>
							</div>

						</x-slot>

						<x-slot name="footer">
							<button type="submit" class="btn btn-sm btn-primary text-bold float-right">Generar</button>
						</x-slot>

					</x-card>
				</x-form>
			</div>
		</div>
	</div>
</section>

@endsection

@section('sidebar')
	@include('layouts.sidebar')
@endsection