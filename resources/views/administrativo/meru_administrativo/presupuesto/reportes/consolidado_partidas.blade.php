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
					<li class="breadcrumb-item active text-bold">Consolidado por Partidas</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<x-form method="post" action="{{ route('presupuesto.reporte.consolidado_partidas.store') }}" target="_blank">
					<x-card x-data="handler()">
                        <x-slot name="header">
                            <h3 class="card-title text-bold">Consolidado por Partidas</h3>
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

                            <x-divisor class="col-6 offset-3" titulo="Partida de Gasto"/>

                            <div class="row col-12">
                                <div class="form-group col-1 offset-2">
                                    <x-label for="cod_par">Partida</x-label>
                                    <x-input name="cod_par" x-mask="99" class="text-center {{ $errors->has('cod_par') ? 'is-invalid' : '' }}" value="{{ old('cod_par') }}" maxlength="2" @keyup="generarCodPartida()"/>

                                    @error('cod_par')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-1">
                                    <x-label for="cod_gen">Genérica</x-label>
                                    <x-input name="cod_gen" x-mask="99" class="text-center {{ $errors->has('cod_gen') ? 'is-invalid' : '' }}" value="{{ old('cod_gen') }}" maxlength="2" @keyup="generarCodPartida()"/>

                                    @error('cod_gen')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-1">
                                    <x-label for="cod_esp">Específica</x-label>
                                    <x-input name="cod_esp" x-mask="99" class="text-center {{ $errors->has('cod_esp') ? 'is-invalid' : '' }}" value="{{ old('cod_esp') }}" maxlength="2" @keyup="generarCodPartida()"/>

                                    @error('cod_esp')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-1">
                                    <x-label for="cod_sub">Sub-Esp</x-label>
                                    <x-input name="cod_sub" x-mask="99" class="text-center {{ $errors->has('cod_sub') ? 'is-invalid' : '' }}" value="{{ old('cod_sub') }}" maxlength="2" @keyup="generarCodPartida()"/>

                                    @error('cod_sub')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-4">
                                    <x-label for="cod_partida">Partida de Gastos</x-label>
                                    <select name="cod_partida" id="cod_partida" class="form-control form-control-sm" readonly>
                                        <option value="">...</option>
                                        @foreach ($partidas as $partidaItem)cod_partida
                                            <option value="{{ $partidaItem->cod_cta }}" @selected(old('cod_partida') == $partidaItem->cod_cta)>{{ $partidaItem->cod_cta . ' - ' . $partidaItem->des_con }}</option>
                                        @endforeach
                                    </select>

                                    @error('cod_partida')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
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

        function handler() {
            return {
                init() {
                    this.generarCodPartida();
                },
                generarCodPartida() {
                    $('#cod_partida').val(
                        '4.' +
                        $('#cod_par').val().padStart('2', '0') + '.' +
                        $('#cod_gen').val().padStart('2', '0') + '.' +
                        $('#cod_esp').val().padStart('2', '0') + '.' +
                        $('#cod_sub').val().padStart('2', '0')
                    );
                }
            }
        }
	</script>
@endsection

@section('sidebar')
	@include('layouts.sidebar')
@endsection