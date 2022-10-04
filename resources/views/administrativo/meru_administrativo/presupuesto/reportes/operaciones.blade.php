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
					<li class="breadcrumb-item active text-bold">Operaciones de Presupuesto</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<x-form method="post" action="{{ route('presupuesto.reporte.operaciones.store') }}" target="_blank">
					<x-card>
						<x-slot name="header">
							<h3 class="card-title text-bold">Operaciones de Presupuesto</h3>
						</x-slot>

						<x-slot name="body">

							<x-divisor class="col-6 offset-3" titulo="Operación"/>

							<div class="row col-12">
								<div class="form-group col-2 offset-5">
									<x-label for="operacion">Operación</x-label>
									<x-select name="operacion" class="form-control-sm {{ $errors->has('operacion') ? 'is-invalid' : '' }} " required>
										@foreach ($operaciones as $key => $ope)
											<option value="{{ $key }}" @selected(old('operacion') == $key)>
												{{ $ope }}
											</option>
										@endforeach
									</x-select>
								</div>
							</div>

							<x-divisor class="col-6 offset-3" titulo="Rango de Fechas"/>

							<div class="row col-12">
								<div class="form-group col-3 offset-3">
                                    <x-label for="fec_ini">Fecha inicial</x-label>
                                    <x-input  name="fec_ini" type="date" class="text-center form-control-sm {{ $errors->has('fec_ini') ? 'is-invalid' : '' }}" />
								</div>

                                <div class="form-group col-3">
                                    <x-label for="fec_fin">Fecha final</x-label>
                                    <x-input  name="fec_fin" type="date" class="text-center form-control-sm {{ $errors->has('fec_fin') ? 'is-invalid' : '' }}" />
								</div>
							</div>

                            <x-divisor class="col-6 offset-3" titulo="Formato"/>

							<div class="row col-12">
								<div class="form-group col-2 offset-5">
									<x-label for="tipo_reporte">Tipo Reporte</x-label>
									<x-select name="tipo_reporte" class="form-control-sm" required>
										<option value="P">PDF</option>
										<option value="E">EXCEL</option>
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