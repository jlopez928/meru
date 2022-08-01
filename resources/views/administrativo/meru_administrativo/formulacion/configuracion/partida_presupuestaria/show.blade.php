@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-12">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('formulacion.configuracion.partida_presupuestaria.index') }}">Partida Presupuestaria</a></li>
					<li class="breadcrumb-item active text-bold">Consultar</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<x-card>
					<x-slot name="header">
						<h3 class="card-title text-bold">Partida Presupuestaria</h3>
					</x-slot>

					<x-slot name="body">

						<!-- Divisor -->
						<div class="row col-12">
 							<div class="col-12">
								<h5 class="card-title text-secondary text-bold">Datos Partida</h5>
							</div>

 							<div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
 						</div>

						<div class="row col-12">
							<div class="form-group col-1 offset-2">
								<x-label for="tipo">Tipo</x-label>
								<x-input name="tipo" class="form-control-sm text-center " value="{{ $partidaPresupuestaria->tip_cod }}" maxlength="2" disabled/>
							</div>

							<div class="form-group col-1">
								<x-label for="partida">Partida</x-label>
								<x-input name="partida" class="form-control-sm text-center" value="{{ $partidaPresupuestaria->cod_par }}" maxlength="2" disabled/>
							</div>

							<div class="form-group col-1">
								<x-label for="generica">Genérica</x-label>
								<x-input name="generica" class="form-control-sm text-center" value="{{ $partidaPresupuestaria->cod_gen }}" maxlength="2" disabled/>
							</div>

							<div class="form-group col-1">
								<x-label for="especifica">Específica</x-label>
								<x-input name="especifica" class="form-control-sm text-center" value="{{ $partidaPresupuestaria->cod_esp }}" maxlength="2" disabled/>
							</div>

							<div class="form-group col-1">
								<x-label for="subespecifica">Sub-específica</x-label>
								<x-input name="subespecifica" class="form-control-sm text-center" value="{{ $partidaPresupuestaria->cod_sub }}" maxlength="2" disabled/>
							</div>

							<div class="form-group col-3">
								<x-label for="cod_partida">Código Partida</x-label>
								<x-input class="text-center form-control-sm" name="cod_partida" value="{{ $partidaPresupuestaria->cod_cta }}" disabled/>
							</div>
						</div>

						<div class="row col-12">
							<div class="form-group col-8 offset-2">
								<x-label for="descripcion">Descripción</x-label>
								<x-input name="descripcion" class="form-control-sm" value="{{ $partidaPresupuestaria->des_con }}" maxlength="500" disabled/>
							</div>
						</div>

						<!-- Divisor -->
						<div class="row col-12">
 							<div class="col-12">
								<h5 class="card-title text-secondary text-bold">Partida asociada - Efectos por pagar</h5>
							</div>

 							<div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
 						</div>

						<div class="row col-12">
							<div class="form-group col-6 offset-3">
								<x-label for="cod_partida">Código Partida</x-label>
								<x-input class="form-control-sm" name="cod_partida" value="{{ $partidaPresupuestaria->part_asociada . ' - ' . $partidaPresupuestaria->partidaAsociada->des_con }}" disabled/>
							</div>
						</div>

						<!-- Divisor -->
						<div class="row col-12">
 							<div class="col-12">
								<h5 class="card-title text-secondary text-bold">Cuentas Contables asociadas</h5>
							</div>

 							<div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
 						</div>

						<div class="row col-12">
							<div class="form-group col-4 text-center">
								<x-label for="cta_activo">Cuenta de Activo</x-label>
								<x-input name="cta_activo" class="form-control-sm text-center" value="{{ $partidaPresupuestaria->cta_activo }}" maxlength="18" disabled/>
							</div>

							<div class="form-group col-4 text-center">
								<x-label for="cta_gasto">Cuenta de Gasto</x-label>
								<x-input name="cta_gasto" class="form-control-sm text-center" value="{{ $partidaPresupuestaria->cta_gasto }}" maxlength="18" disabled/>
							</div>

							<div class="form-group col-4 text-center">
								<x-label for="cta_por_pagar">Cuenta por Pagar</x-label>
								<x-input name="cta_por_pagar" class="form-control-sm text-center" value="{{ $partidaPresupuestaria->cta_x_pagar }}" maxlength="18" disabled/>
							</div>
						</div>

						<div class="row col-12">
							<div class="form-group col-4 offset-2 text-center">
								<x-label for="cta_por_pagar_activo">Cuenta por Pagar Activo</x-label>
								<x-input name="cta_por_pagar_activo" class="form-control-sm text-center" value="{{ $partidaPresupuestaria->cta_x_pagar_activo }}" maxlength="18" disabled/>
							</div>

							<div class="form-group col-4 text-center">
								<x-label for="cta_provision">Cuenta de Provisión</x-label>
								<x-input name="cta_provision" class="form-control-sm text-center" value="{{ $partidaPresupuestaria->cta_provision }}" maxlength="18" disabled/>
							</div>
						</div>

					</x-slot>

					<x-slot name="footer">
					</x-slot>

				</x-card>
			</div>
		</div>
	</div>
</section>

@endsection

@section('sidebar')
	@include('layouts.sidebar')
@endsection