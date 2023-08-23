@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-12">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('home') }}">Página Principal</a></li>
					<li class="breadcrumb-item active text-bold">Formulación</li>
					<li class="breadcrumb-item active text-bold">Reportes</li>
					<li class="breadcrumb-item active text-bold">Ejecución Presupuestaria</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<x-form method="post" action="{{ route('formulacion.reportes.maestro_ley.ejecucion.store') }}" target="_blank">
					<x-card x-data="handler()">
						<x-slot name="header">
							<h3 class="card-title text-bold">Reporte Ejecución Presupuestaria</h3>
						</x-slot>

						<x-slot name="body">

							<!-- Divisor -->
							<div class="row col-6 offset-3">
								<div class="col-12">
									<h5 class="card-title text-secondary text-bold">Periodo</h5>
								</div>

								<div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
							</div>

							<div class="row col-12">
								<div class="form-group col-2 offset-4">
									<x-label for="ano_pro">Año</x-label>
									<x-select name="ano_pro" class="form-control-sm {{ $errors->has('ano_pro') ? 'is-invalid' : '' }} " required>
										<option value="">Seleccione...</option>
										@foreach ($periodosRep as $periodoItem)
											<option value="{{ $periodoItem }}" @selected(old('ano_pro') == $periodoItem)>
												{{ $periodoItem }}
											</option>
										@endforeach
									</x-select>
								</div>

								<div class="form-group col-2">
									<x-label for="mes">Mes</x-label>
									<x-select name="mes" class="form-control-sm {{ $errors->has('mes') ? 'is-invalid' : '' }}" required>
										<option value="">Seleccione...</option>
										@foreach ($meses as $key => $mes)
											<option value="{{ $key }}" @selected(old('mes') == $key)>
												{{ $mes }}
											</option>
										@endforeach
										<option value="13">EJECUCIÓN ACTUAL</option>
									</x-select>
								</div>
							</div>

							<!-- Divisor -->
							<div class="row col-6 offset-3">
								<div class="col-12">
									<h5 class="card-title text-secondary text-bold">Formato</h5>
								</div>

								<div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
							</div>

							<div class="row col-12">
								<div class="form-group col-2 offset-4">
									<x-label for="filtro">Filtro</x-label>
									<x-select name="filtro" class="form-control-sm" required>
										{{-- <option value="">SELECCIONE...</option> --}}
										<option value="C">CENTRO DE COSTO</option>
										{{-- <option value="P">PARTIDA PRESUPUESTARIA</option> --}}
									</x-select>
								</div>

								<div class="form-group col-2">
									<x-label for="tipo_reporte">Tipo Reporte</x-label>
									<x-select name="tipo_reporte" class="form-control-sm" required>
										<option value="P">PDF</option>
										{{-- <option value="E">EXCEL</option> --}}
									</x-select>
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