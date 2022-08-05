@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-12">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('configuracion.configuracion.ubicacion_geografica.index') }}">Ubicaciones Geográficas</a></li>
					<li class="breadcrumb-item active text-bold">Ver Ubicaciones Geograficas </li>
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
						<h3 class="card-title text-bold">Ubicaciones Geograficas</h3>
					</x-slot>

					<x-slot name="body">

						<div class="row col-12">
						    <div class="form-group col-3 offset-1">
						        <x-label for="estado">Estado</x-label>
						        <select name="estado" id="estado" class="form-control form-control-sm" disabled>
						            <option value=""></option>
						            @foreach ($estados as $edoItem)
						                <option value="{{ $edoItem->cod_edo }}" @selected($edoItem->cod_edo == $ubicacionGeografica->cod_edo)>{{ $edoItem->des_ubi }}</option>
						            @endforeach
						        </select>
						    </div>

						    <div class="form-group col-3">
						        <x-label for="municipio">Municipio</x-label>
						        <select name="municipio" id="municipio" class="form-control form-control-sm" disabled>
					                <option value="">...</option>

						            @foreach ($municipios as $municipioItem)
						                <option value="{{ $municipioItem->cod_mun }}" @selected($municipioItem->cod_mun == $ubicacionGeografica->cod_mun)>{{ $municipioItem->des_ubi }}</option>
						            @endforeach
						        </select>
						    </div>

						    <div class="form-group col-3">
						        <x-label for="parroquia">Parroquia</x-label>
						        <select name="parroquia" id="parroquia" class="form-control form-control-sm" disabled>
					                <option value="">...</option>

						            @foreach ($parroquias as $parroquiaItem)
						                <option value="{{ $parroquiaItem->cod_par }}" @selected($parroquiaItem->cod_par == $ubicacionGeografica->cod_par)>{{ $parroquiaItem->des_ubi }}</option>
						            @endforeach
						        </select>
						    </div>
						</div>

						<div class="row col-12">
							<div class="form-group col-3 offset-1">
								<x-label for="descripcion">Descripción</x-label>
								<x-input class="form-control-sm" name="descripcion" value="{{ $ubicacionGeografica->des_ubi }}"  maxlength="40" disabled/>
							</div>

							<div class="form-group col-3 ">
								<x-label for="capital">Capital</x-label>
								<x-input class="form-control-sm" name="capital" value="{{ $ubicacionGeografica->capital }}" maxlength="40" disabled/>
							</div>

							<div class="form-group col-1">
								<x-label for="codigo">Código</x-label>
								<x-input class="text-center form-control-sm" name="codigo" value="{{ $ubicacionGeografica->cod_ubi }}" maxlength="5" disabled/>
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
