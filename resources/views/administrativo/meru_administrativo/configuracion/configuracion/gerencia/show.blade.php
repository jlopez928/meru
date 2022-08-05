@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-12">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('configuracion.configuracion.gerencia.index') }}">Gerencia</a></li>
					<li class="breadcrumb-item active text-bold">Ver Gerencia</li>
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
						<h3 class="card-title text-bold">Gerencia</h3>
					</x-slot>

					<x-slot name="body">

						<div class="row col-12">
							<div class="form-group col-8 offset-1">
								<x-label for="gerencia">Código - Nombre</x-label>
								<x-input class="form-control-sm" name="gerencia" value="{{ $gerencia->cod_ger . ' - ' . $gerencia->des_ger }}" disabled/>
							</div>

							<div class="form-group col-2">
								<x-label for="nomenclatura">Nomenclatura</x-label>
								<x-input class="text-center form-control-sm" name="nomenclatura" value="{{ $gerencia->nomenclatura }}" disabled/>
							</div>
						</div>

						<div class="row col-12">
							<div class="form-group col-3 offset-1">
								<x-label for="jefe">Jefe / Responsable</x-label>
								<x-input class="text-center form-control-sm" name="jefe" value="{{ $gerencia->nom_jefe }}" disabled/>
							</div>

							<div class="form-group col-4">
								<x-label for="cargo_jefe">Cargo</x-label>
								<x-input class="text-center form-control-sm" name="cargo_jefe" value="{{ $gerencia->car_jefe }}" disabled/>
							</div>

							<div class="form-group col-3">
								<x-label for="correo">Correo Electrónico</x-label>
								<x-input class="text-center form-control-sm" name="correo" value="{{ $gerencia->correo_jefe }}" disabled/>
							</div>
						</div>

						<div class="row col-12">
							<div class="form-group col-2 offset-1">
								<x-label for="centro_costo">Centro de Costo</x-label>
								<x-input class="text-center form-control-sm" name="centro_costo" value="{{ $gerencia->centro_costo }}" disabled/>
							</div>

							<div class="form-group col-4">
								<x-label for="viaticos_nac">Estructura de Gastos Viaticos Nac.</x-label>
								<x-input class="text-center form-control-sm" name="viaticos_nac" value="{{ $gerencia->part_gastos }}" disabled/>
							</div>

							<div class="form-group col-4">
								<x-label for="viaticos_internac">Estructura de Gastos Viaticos Internac.</x-label>
								<x-input class="text-center form-control-sm" name="viaticos_internac" value="{{ $gerencia->part_gastos_vinternac }}" disabled/>
							</div>
						</div>

						<div class="row col-12">
							<div class="form-group col-2 offset-1">
								<x-label for="centro_costo_ant">Centro de Costo Ant.</x-label>
								<x-input class="text-center form-control-sm" name="centro_costo_ant" value="{{ $gerencia->centro_costo_anterior }}" disabled/>
							</div>

							<div class="form-group col-4 offset-1 text-center">
								<x-label for="aplica_pre">¿Aplica Pre-compromiso?</x-label><br>
								<b>
									<span name="aplica_pre" class="{{ $gerencia->aplica_pre == 'SI' ? 'text-success' : 'text-danger' }}">
										{{ $gerencia->aplica_pre }}
									</span>
								</b>
							</div>

							<div class="form-group col-4">
								<x-label for="estado">Estado</x-label><br>
								<b>
									<span name="estado" class="{{ $gerencia->status == 'ACTIVA' ? 'text-success' : 'text-danger' }}">
										{{ $gerencia->status }}
									</span>
								</b>
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
