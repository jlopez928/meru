<x-divisor class="col-10 offset-1" titulo="Datos Básicos"/>

<div class="row col-12">
	<div class="form-group col-2 offset-2">
		<x-label for="ano_pro">Año</x-label>
		<x-input type="text" name="ano_pro" class="form-control form-control-sm text-center" value="{{ $traspaso->ano_pro }}" disabled/>
	</div>

	<div class="form-group col-2">
		<x-label for="xnro_mod">Código</x-label>
		<x-input type="text" name="xnro_mod" class="form-control form-control-sm text-center"  value="{{ $traspaso->xnro_mod }}" disabled />
	</div>

    <div class="form-group col-2">
		<x-label for="nro_mod">Número</x-label>
		<x-input type="text" name="nro_mod" class="form-control form-control-sm text-center"  value="{{ $traspaso->nro_mod }}" disabled />
	</div>

    <div class="form-group col-2">
		<x-label for="num_doc">Solicitud</x-label>
		<x-input type="text" name="num_doc" class="form-control form-control-sm text-center"  value="{{ $traspaso->num_doc }}" disabled />
	</div>
</div>

<x-divisor class="col-10 offset-1" titulo="Fechas"/>

<div class="row col-12">
	<div class="form-group col-3 offset-3">
		<x-label for="fec_tra">Fecha Transacción</x-label>
		<x-input type="text" name="fec_tra" class="form-control form-control-sm text-center" value="{{ \Carbon\Carbon::parse($traspaso->fec_tra)->format('d/m/Y'); }}" disabled />
	</div>

	<div class="form-group col-3">
		<x-label for="fec_sta">Fecha Estado</x-label>
		<x-input type="text" name="fec_sta" class="form-control form-control-sm text-center" value="{{ \Carbon\Carbon::parse($traspaso->fec_sta)->format('d/m/Y'); }}" disabled />
	</div>
</div>

<x-divisor class="col-10 offset-1" titulo="Otros Datos"/>

<div class="row col-12">
	<div class="form-group col-8 offset-2">
		<x-label for="concepto">Concepto</x-label>
		<x-input type="text" name="concepto" class="form-control-sm"  maxlength="500" value="{{ $traspaso->concepto }}" disabled/>
	</div>
</div>

<div class="row col-12">
	<div class="form-group col-8 offset-2">
		<x-label for="justificacion">Justificación</x-label>
		<textarea id="justificacion" name="justificacion" class="form-control" maxlength="300" cols="50" rows="5" title="Indique Justificación" disabled>{{ $traspaso->justificacion }}</textarea>
	</div>
</div>

<div class="row col-12">
	<div class="form-group col-3 offset-2">
		<x-label for="estado">Estado:</x-label>
		<span name="estado">{{ Str::replace('_', ' ', $traspaso->sta_reg->name) }}</span>
	</div>
</div>

<div class="card card-outline">
	<div class="card-header">
		<h3 class="card-title text-bold">Partidas Cedentes</h3>
	</div>

	<div class="card-body">
		<div class="row col-12" style="margin-top: 10px;">
			<table class="table table-bordered table-sm text-center" style="font-size:12px;">
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
						<th style="width:250px;vertical-align:middle;">Monto Disp.</th>
						<th style="width:100px;vertical-align:middle;">Monto</th>
					</tr>
				</thead>
				<tbody style="font-size:12px;">
					@forelse ($partidasCedentes as $item)
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
							<td class="text-left" style="vertical-align:middle;">
								{{ $item['descrip'] }}
							</td>
							<td class="text-right" style="vertical-align:middle;padding-right:10px;">
								{{ number_format($item['mto_dis'], 2, ',', '.') }}
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
		</div>

		<div class="row col-12" style="paddin-right:0px !important;">
			<div class="form-group row col-12">
				<x-label for="total_ced" class="col-form-label col-2 offset-8 text-right">Total:</x-label>
				<div class="col-2">
					<x-input name="total_ced" class="text-right" value="{{ number_format($traspaso->totalCedentes(), 2, ',', '.') }}" disabled/>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="card card-outline">
	<div class="card-header">
		<h3 class="card-title text-bold">Partidas Receptoras</h3>
	</div>

	<div class="card-body">
		<div class="row col-12" style="margin-top: 10px;">
			<table class="table table-bordered table-sm text-center" style="font-size:12px;">
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
						<th style="width:250px;vertical-align:middle;">Monto Disp.</th>
						<th style="width:100px;vertical-align:middle;">Monto</th>
					</tr>
				</thead>
				<tbody style="font-size:12px;">
					@forelse ($partidasReceptoras as $item)
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
							<td class="text-left" style="vertical-align:middle;">
								{{ $item['descrip'] }}
							</td>
							<td class="text-right" style="vertical-align:middle;padding-right:10px;">
								{{ number_format($item['mto_dis'], 2, ',', '.') }}
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
		</div>

		<div class="row col-12" style="paddin-right:0px !important;">
			<div class="form-group row col-12">
				<x-label for="total_rec" class="col-form-label col-2 offset-8 text-right">Total:</x-label>
				<div class="col-2">
					<x-input name="total_rec" class="text-right" value="{{ number_format($traspaso->totalReceptoras(), 2, ',', '.') }}" disabled/>
				</div>
			</div>
		</div>
	</div>
</div>