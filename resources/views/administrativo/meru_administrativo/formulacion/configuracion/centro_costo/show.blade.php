@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-12">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('formulacion.configuracion.centro_costo.index') }}">Centro de Costo</a></li>
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
						<h3 class="card-title text-bold">Centro de Costo</h3>
					</x-slot>

					<x-slot name="body">

						<div class="row col-12">
							<div class="form-group col-2 offset-1">
								<x-label for="ano_pro">Año</x-label>
								<x-input class="text-center form-control-sm" name="ano_pro" value="{{ $centroCosto->ano_pro }}" disabled/>
							</div>

							<div class="form-group col-4">
								<x-label for="cod_centro">Código Centro</x-label>
								<x-input class="text-center form-control-sm" name="cod_centro" value="{{ $centroCosto->cod_cencosto }}" disabled/>
							</div>
						</div>

						<div class="row col-12">
							<div class="form-group col-2 offset-1">
								<x-label for="tipo">Tipo</x-label>
								<x-input name="tipo" class="form-control-sm text-center " value="{{ $centroCosto->tip_cod }}" maxlength="2" disabled/>
							</div>

							<div class="form-group col-2">
								<x-label for="proyecto">Proyecto / Acción</x-label>
								<x-input name="proyecto" class="form-control-sm text-center" value="{{ $centroCosto->cod_pryacc }}" maxlength="2" disabled/>
							</div>

							<div class="form-group col-2">
								<x-label for="objetivo">Objetivo específico</x-label>
								<x-input name="objetivo" class="form-control-sm text-center" value="{{ $centroCosto->cod_obj }}" maxlength="2" disabled/>
							</div>

							<div class="form-group col-2">
								<x-label for="gerencia">Gerencia</x-label>
								<x-input name="gerencia" class="form-control-sm text-center" value="{{ $centroCosto->gerencia }}" maxlength="2" disabled/>
							</div>

							<div class="form-group col-2">
								<x-label for="unidad">Unidad Ejecutora</x-label>
								<x-input name="unidad" class="form-control-sm text-center" value="{{ $centroCosto->unidad }}" maxlength="2" disabled/>
							</div>
						</div>

						<div class="row col-12">
							<div class="form-group col-10 offset-1">
								<x-label for="descripcion">Descripción</x-label>
								<x-input name="descripcion" class="form-control-sm" value="{{ $centroCosto->des_con }}" maxlength="500" disabled/>
							</div>
						</div>

						<div class="row col-12">

							<div class="form-group col-3 offset-2 text-center">
								<x-label for="estado">Estado</x-label><br>
								<b>
									<span name="estado" class="{{ $centroCosto->sta_reg == 'ACTIVO' ? 'text-success' : 'text-danger' }}">
										{{ $centroCosto->sta_reg }}
									</span>
								</b>
							</div>

							<div class="form-group col-3 text-center">
								<x-label for="credito_adicional">¿Crédito Adicional?</x-label><br>
								<b>
									<span name="credito_adicional" class="{{ $centroCosto->cre_adi == 'SI' ? 'text-success' : 'text-danger' }}">
										{{ $centroCosto->cre_adi }}
									</span>
								</b>
							</div>

							<div class="form-group col-2 text-center">
								<x-label for="siglas">Siglas</x-label>
								<x-input name="siglas" class="form-control-sm text-center" value="{{ $centroCosto->cre_adi == 'SI' ? $centroCosto->gerencias()->first()->nomenclatura : '' }}" maxlength="3" disabled/>
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