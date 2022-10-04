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
					<li class="breadcrumb-item active text-bold">Solicitudes de Traspasos Presupuestarios</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<x-form method="post" action="{{ route('presupuesto.reporte.solicitudes_traspaso.store') }}" target="_blank">
					<x-card>
						<x-slot name="header">
							<h3 class="card-title text-bold">Solicitudes de Traspasos Presupuestarios</h3>
						</x-slot>

						<x-slot name="body">

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

							<x-divisor class="col-6 offset-3" titulo="Otros Filtros"/>

							<div class="row col-12">
								<div class="form-group col-6 offset-3">
									<x-label for="gerencia">Gerencia</x-label>
									<x-select name="gerencia" class="select2bs4 form-control-sm {{ $errors->has('gerencia') ? 'is-invalid' : '' }}">
										<option value="">Seleccione...</option>
                                        @foreach ($gerencias as $key => $ger)
											<option value="{{ $key }}" @selected(old('gerencia') == $key)>
												{{ $ger }}
											</option>
										@endforeach
									</x-select>
								</div>
							</div>

                            <div class="row col-12">
								<div class="form-group col-2 offset-5">
									<x-label for="sta_reg">Estado</x-label>
									<x-select name="sta_reg" class="select2bs4 form-control-sm {{ $errors->has('sta_reg') ? 'is-invalid' : '' }}" >
										<option value="">Seleccione...</option>
                                        @foreach ($estados as $sta)
											<option value="{{ $sta->value }}" @selected(old('sta_reg') == $sta->value)>
												{{ $sta->name }}
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