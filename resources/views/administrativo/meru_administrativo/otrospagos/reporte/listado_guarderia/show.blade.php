@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-12">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('contratos.proceso.actacontratobraserv.index') }}">Programa Principal</a></li>
					<li class="breadcrumb-item active text-bold">Listado de Guarderias </li>
				</ol>
			</div>
		</div>
	</div>
</section>


<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<x-form method="post" action="{{route('otrospagos.reporte.print_listado_guarderia') }}" target="_blank">
					<x-card>
						<x-slot name="header">
							<h3 class="card-title text-bold">Listado de Guarderias</h3>
						</x-slot>

						<x-slot name="body">
							<div class="col-12 offset-1">



								<div class="form-group col-4">
									<x-label for="correlativo">Correlativo</x-label>
									<x-input  class="form-control-sm" name="correlativo" value="" />
								</div>


								<div class="row col-12 offset-1">
									<div class="col-12">
										<h5 class="card-title text-secondary text-bold">Fecha Creación</h5>
									</div>

									<div class="dropdown-divider col-8" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>

									<div class="row col-12">
										<div class="form-group col-2">
											<x-label for="concepto">Fecha Inicio</x-label>
											<x-input  class="form-control-sm text-center" name="inicio" type="date" value="" />
										</div>

										<div class="form-group col-2">
											<x-label for="fin">Fecha Final</x-label>
											<x-input  class="form-control-sm text-center" name="fin" type="date" value="" />
										</div>
									</div>
									<div class="form-group col-4">
										<x-label for="tipo">Formato Reporte</x-label>
										<x-select name="tipo" class="form-control-sm">
											<option value="">Seleccione...</option>
											<option value="PDF"> {{'PDF'}}</option>
											<option value="EXCEL"> {{'Hoja de Cálculo'}}</option>
										</x-select>
									</div>
								</div>

							</div>
						</x-slot>
						<x-slot name="footer">
							<div class="row offset-6" alighn="center ">
							    <x-input id="Aceptar" name="Aceptar" type="submit" class="btn btn-sm btn-primary text-bold float-right"  value="Aceptar" />
							</div>
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

@push('scripts')
    <script type="text/javascript">

        $(function () {
            $('.select2bs4').select2({
                theme: 'bootstrap4',
                minimumInputLength: 2,
                language: {
                    noResults: function() {
                        return 'No se encontraron resultados';
                    },
                    inputTooShort: function() {
                        return 'Ingrese al menos dos letras';
                    }
                }
            }).on('change', function(event){
                Livewire.emit('changeSelect', $(this).val(), event.target.id)
            });
        })

    </script>
@endpush
