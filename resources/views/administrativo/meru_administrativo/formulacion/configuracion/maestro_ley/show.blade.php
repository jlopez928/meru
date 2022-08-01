@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-12">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('formulacion.configuracion.maestro_ley.index') }}">Maestro de Ley</a></li>
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
						<h3 class="card-title text-bold">Maestro de Ley</h3>
					</x-slot>

					<x-slot name="body">

						<!-- Divisor -->
						<div class="row col-12">
							<div class="col-12">
								<h5 class="card-title text-secondary text-bold">Datos Estructura</h5>
							</div>

							<div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
						</div>

						<div class="row col-12">
							<div class="form-group col-2 offset-1">
								<x-label for="ano_pro">Año</x-label>
								<x-input class="text-center form-control-sm" name="ano_pro" value="{{ $maestroLey->ano_pro }}" disabled/>
							</div>

							<div class="form-group col-4">
								<x-label for="centro_costo">Centro de Costo</x-label>
								<x-input class="text-center form-control-sm" name="centro_costo" value="{{ $maestroLey->centroCosto->cod_cencosto }}" disabled/>
							</div>

							<div class="form-group col-4">
								<x-label for="partida_gastos">Partida de Gastos</x-label>
								<x-input class="text-center form-control-sm" name="partida_gastos" value="{{ $maestroLey->partidaPresupuestaria->cod_cta }}" disabled/>
							</div>
						</div>

						<div class="row col-12">
							<div class="form-group col-4 offset-2">
								<x-label for="anterior">Estructura presupuestaria</x-label>
								<x-input class="text-center form-control-sm" name="anterior" value="{{ $maestroLey->cod_com }}" disabled/>
							</div>

							<div class="form-group col-4 text-center">
								<x-label for="exceder_pago">Permite pagar sin tener suficiente Causado<br>(En Dualidad)</x-label><br>
								<b>
									<span name="exceder_pago" class="{{ $maestroLey->exc_pag == 'SI' ? 'text-success' : 'text-danger' }}">
										{{ $maestroLey->exc_pag }}
									</span>
								</b>
							</div>
						</div>

						<!-- Divisor -->
						<div class="row col-12">
							<div class="col-12">
								<h5 class="card-title text-secondary text-bold">Montos acumulados</h5>
							</div>

							<div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
						</div>

						<div class="row col-12">

							<div class="col-5">

								<div class="form-group row">
									<x-label for="formulado" class="col-form-label col-4 text-right">Monto Formulado</x-label>
									<div class="col-8">
										<x-input class="text-right form-control-sm" name="formulado" value="{{ $maestroLey->formatNumber('ley_for') }}" disabled/>
									</div>
								</div>

								<div class="form-group row">
									<x-label for="modificado" class="col-form-label text-right col-4">Monto Modificado</x-label>
									<div class="col-8">
										<x-input class="text-right form-control-sm" name="modificado" value="{{ $maestroLey->formatNumber('mto_mod') }}" disabled/>
									</div>
								</div>

								<div class="form-group row">
									<x-label for="pre_compromiso" class="col-form-label text-right col-4">Monto Pre-Compromiso</x-label>
									<div class="col-8">
										<x-input class="text-right form-control-sm" name="pre_compromiso" value="{{ $maestroLey->formatNumber('mto_pre') }}" disabled/>
									</div>
								</div>

								<div class="form-group row">
									<x-label for="causado" class="col-form-label text-right col-4">Monto de Causado</x-label>
									<div class="col-8">
										<x-input class="text-right form-control-sm" name="causado" value="{{ $maestroLey->formatNumber('mto_cau') }}" disabled/>
									</div>
								</div>

								<div class="form-group row">
									<x-label for="pagado" class="col-form-label text-right col-4">Monto de Pagado</x-label>
									<div class="col-8">
										<x-input class="text-right form-control-sm" name="pagado" value="{{ $maestroLey->formatNumber('mto_pag') }}" disabled/>
									</div>
								</div>

								<div class="form-group row">
									<x-label for="compromiso_ant" class="col-form-label text-right col-4">Monto Compromiso Años Anteriores</x-label>
									<div class="col-8">
										<x-input class="text-right form-control-sm" name="compromiso_ant" value="{{ $maestroLey->formatNumber('mto_com_anterior') }}" disabled/>
									</div>
								</div>
							</div>

							<div class="col-5 offset-1">

								<div class="form-group row">
									<x-label for="ley" class="col-form-label text-right col-4">Monto de Ley</x-label>
									<div class="col-8">
										<x-input class="text-right form-control-sm" name="ley" value="{{ $maestroLey->formatNumber('mto_ley') }}" disabled/>
									</div>
								</div>

								<div class="form-group row">
									<x-label for="apartado" class="col-form-label text-right col-4">Monto Apartado</x-label>
									<div class="col-8">
										<x-input class="text-right form-control-sm" name="apartado" value="{{ $maestroLey->formatNumber('mto_apa') }}" disabled/>
									</div>
								</div>

								<div class="form-group row">
									<x-label for="compromiso" class="col-form-label text-right col-4">Monto de Compromiso</x-label>
									<div class="col-8">
										<x-input class="text-right form-control-sm" name="compromiso" value="{{ $maestroLey->formatNumber('mto_com') }}" disabled/>
									</div>
								</div>

								<div class="form-group row">
									<x-label for="disponible" class="col-form-label text-right col-4">Monto Disponible</x-label>
									<div class="col-8">
										<x-input class="text-right form-control-sm" name="disponible" value="{{ $maestroLey->formatNumber('mto_dis') }}" disabled/>
									</div>
								</div>

								<div class="form-group row">
									<x-label for="bloqueado" class="col-form-label text-right col-4">Saldo de Bloqueado</x-label>
									<div class="col-8">
										<x-input class="text-right form-control-sm" name="bloqueado" value="{{ $maestroLey->formatNumber('mto_cnc') }}" disabled/>
									</div>
								</div>

								<div class="form-group row">
									<x-label for="causado_ant" class="col-form-label text-right col-4">Monto Causado Años Anteriores</x-label>
									<div class="col-8">
										<x-input class="text-right form-control-sm" name="causado_ant" value="{{ $maestroLey->formatNumber('mto_cau_anterior') }}" disabled/>
									</div>
								</div>
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