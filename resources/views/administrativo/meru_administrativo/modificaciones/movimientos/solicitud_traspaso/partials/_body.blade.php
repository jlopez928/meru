<!-- Divisor -->
<div class="row col-8 offset-2 text-center">
	<div class="col-12">
		<h5 class="card-title text-secondary text-bold">Datos Básicos</h5>
	</div>

	<div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
</div>

<div class="row col-12">
	<div class="form-group col-3 offset-3">
		<x-label for="ano_pro">Año</x-label>
		<x-input type="text" name="ano_pro" class="form-control form-control-sm text-center" value="{{ $solicitudTraspaso->ano_pro }}" disabled/>
	</div>

	<div class="form-group col-3">
		<x-label for="nro_sol">Solicitud</x-label>
		<x-input type="text" name="nro_sol" class="form-control form-control-sm text-center"  value="{{ $solicitudTraspaso->nro_sol }}" disabled />
	</div>
</div>

<div class="row col-12">
	<div class="form-group col-3 offset-3">
		<x-label for="fec_sol">Fecha Solicitud</x-label>
		<x-input type="text" name="fec_sol" class="form-control form-control-sm text-center" value="{{ \Carbon\Carbon::parse($solicitudTraspaso->fec_sol)->format('d/m/Y'); }}" disabled />
	</div>

	<div class="form-group col-3">
		<x-label for="num_sop">Documento</x-label>
		<x-input type="text" class="form-control form-control-sm text-center" maxlength="12" value="{{ $solicitudTraspaso->num_sop }}" disabled/>
	</div>
</div>

<div class="row col-12">
	<div class="form-group col-6 offset-3">
		<x-label for="cod_ger">Gerencia</x-label>

			<x-select name="cod_ger" class="form-control-sm select2bs4" disabled>
				@foreach ($gerencias as $gerenciaItem)
					<option value="{{ $gerenciaItem->cod_ger }}" @selected($solicitudTraspaso->cod_ger == $gerenciaItem->cod_ger)>{{ $gerenciaItem->des_ger }}</option>
				@endforeach
			</x-select>
	</div>
</div>

<!-- Divisor -->
<div class="row col-12">
	<div class="col-12">
		<h5 class="card-title text-secondary text-bold">Otros Datos</h5>
	</div>

	<div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
</div>

<div class="row col-12">
	<div class="form-group col-3 offset-1">
		<x-label for="nro_ext">Extensión</x-label>
		<x-input type="text" name="nro_ext" class="form-control form-control-sm text-center"  maxlength="20" value="{{ $solicitudTraspaso->nro_ext }}" disabled/>
	</div>

	<div class="form-group col-7">
		<x-label for="concepto">Concepto</x-label>
		<x-input type="text" name="concepto" class="form-control-sm"  maxlength="500" value="{{ $solicitudTraspaso->concepto }}" disabled/>
	</div>
</div>

<div class="row col-12">
	<div class="form-group col-10 offset-1">
		<x-label for="justificacion">Justificación</x-label>
		<textarea id="justificacion" name="justificacion" class="form-control" maxlength="300" cols="50" rows="5" title="Indique Justificación" disabled>{{ $solicitudTraspaso->justificacion }}</textarea>
	</div>
</div>

@if(Route::is('modificaciones.movimientos.solicitud_traspaso.anular.edit') || $solicitudTraspaso->sta_reg->value == 3)
	<div class="row col-12">
		<div class="form-group col-10 offset-1">
			<x-label for="cau_anu">Causa Anulación</x-label>
			<input id="cau_anu" name="cau_anu" class="form-control form-control-sm {{ $errors->has('cau_anu') ? 'is-invalid' : '' }}"  maxlength="500" value="{{ old('cau_anu', $solicitudTraspaso->cau_anu) }}" {{ $solicitudTraspaso->sta_reg->value == 3 ? 'disabled' : 'required' }}/>

			@error('cau_anu')
				<span class="invalid-feedback" role="alert">
					{{ $message }}
				</span>
			@enderror
		</div>
	</div>
@endif

<div class="row col-12">
	<div class="form-group col-3 offset-1">
		<x-label for="total">Total</x-label>
		<x-input type="text" id="total" name="total" class="form-control form-control-sm text-right" value="{{ number_format($solicitudTraspaso->total, 2, ',', '.') }}" disabled />
	</div>
</div>

<div class="row col-12">
	<div class="form-group col-3 offset-1">
		<x-label for="estado">Estado:</x-label>
		<span name="estado">{{ $solicitudTraspaso->sta_reg->name }}</span>
	</div>
</div>

<!-- Divisor -->
<div class="row col-12">
	<div class="col-12">
		<h5 class="card-title text-secondary text-bold">Partidas Receptoras</h5>
	</div>

	<div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
</div>

<div class="row col-12">
	
	<table class="table table-bordered table-sm text-center" >
		<thead>
			<tr class="table-primary">
				<th style="width:50px;vertical-align:middle;">Tp</th>
				<th style="width:50px;vertical-align:middle;">P/A</th>
				<th style="width:50px;vertical-align:middle;">Obj</th>
				<th style="width:50px;vertical-align:middle;">Gcia</th>
				<th style="width:50px;vertical-align:middle;">U.Ejec.</th>
				<th style="width:50px;vertical-align:middle;">Pa</th>
				<th style="width:50px;vertical-align:middle;">Gn</th>
				<th style="width:50px;vertical-align:middle;">Esp.</th>
				<th style="width:50px;vertical-align:middle;">Sub.Esp</th>
				<th style="width:250px;vertical-align:middle;">Descripción</th>
				<th style="width:100px;vertical-align:middle;">Monto</th>
			</tr>
		</thead>
		<tbody style="font-size:12px;">
			@forelse ($estructuras as $item)
				<tr>
					<td class="text-center"  style="vertical-align:middle;">
						{{ $item['tip_cod'] }}
					</td>
					<td class="text-center"  style="vertical-align:middle;">
						{{ $item['cod_pryacc'] }}
					</td>
					<td class="text-center" style="vertical-align:middle;">
						{{ $item['cod_obj'] }}
					</td>
					<td class="text-center" style="vertical-align:middle;">
						{{ $item['gerencia'] }}
					</td>
					<td class="text-center" style="vertical-align:middle;">
						{{ $item['unidad'] }}
					</td>
					<td class="text-center" style="vertical-align:middle;">
						{{ $item['cod_par'] }}
					</td>
					<td class="text-center" style="vertical-align:middle;">
						{{ $item['cod_gen'] }}
					</td>
					<td class="text-center" style="vertical-align:middle;">
						{{ $item['cod_esp'] }}
					</td>
					<td class="text-center" style="vertical-align:middle;">
						{{ $item['cod_sub'] }}
					</td>
					<td class="text-center" style="vertical-align:middle;">
						{{ $item['descrip'] }}
					</td>
					<td class="text-right" style="vertical-align:middle;padding-right:10px;">
						{{ number_format($item['mto_tra'], 2, ',', '.') }}
					</td>
				</tr>
			@empty
				<tr>
					<td class="text-center" colspan="11">
						No existen registros
					</td>
				</tr>
			@endforelse
		</tbody>
	</table>
	<x-input type="hidden" name="estructuras" id="estructuras" />
</div>