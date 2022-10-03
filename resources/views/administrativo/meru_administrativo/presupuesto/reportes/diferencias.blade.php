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
					<li class="breadcrumb-item active text-bold">Diferencias Presupuestarias</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<x-form method="post" action="{{ route('presupuesto.reporte.diferencias.store') }}" target="_blank">
					<x-card>
                        <x-slot name="header">
                            <h3 class="card-title text-bold">Diferencias Presupuestarias</h3>
                        </x-slot>

                        <x-slot name="body">

                            <x-divisor class="col-6 offset-3" titulo="Periodo"/>

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
                                    </x-select>
                                </div>
                            </div>

                            <x-divisor class="col-6 offset-3" titulo="Datos de Búsqueda"/>

                            <div class="row col-12">
								<div class="form-group col-4 offset-4">
									<x-label for="criterio">Criterio</x-label>
									<x-select name="criterio" class="form-control-sm {{ $errors->has('criterio') ? 'is-invalid' : '' }}" required>
										<option value="">Seleccione...</option>
                                        @foreach ($criterios as $key => $val)
											<option value="{{ $key }}" @selected(old('criterio') == $key)>
												{{ $val }}
											</option>
										@endforeach
									</x-select>
								</div>
							</div>

                            <x-divisor class="col-6 offset-3" titulo="Formato"/>

							<div class="row col-12">
								<div class="form-group col-2 offset-5">
									<x-label for="tipo_reporte">Tipo Reporte</x-label>
									<x-select name="tipo_reporte" class="select2bs4 form-control-sm" required>
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

@section('js')
	<script type="text/javascript"> 
		$(function () {
            $('.select2bs4').select2({
				minimumResultsForSearch: 20,
            });
		});
	</script>
@endsection

@section('sidebar')
	@include('layouts.sidebar')
@endsection