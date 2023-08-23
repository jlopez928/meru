<x-card wire:init="inicializar" x-data="">
	<x-slot name="header">
		<h3 class="card-title text-bold">Solicitud de Traspaso</h3>
	</x-slot>

	<x-slot name="body">

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
				<input type="text" wire:model.defer="ano_pro" id="ano_pro" name="ano_pro" class="form-control form-control-sm text-center {{ $errors->has('ano_pro') ? 'is-invalid' : '' }}" readonly />

				@error('ano_pro')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>

			<div class="form-group col-3">
				<x-label for="nro_sol">Solicitud</x-label>
				<input type="text" wire:model.defer="nro_sol" id="nro_sol" name="nro_sol" class="form-control form-control-sm text-center {{ $errors->has('nro_sol') ? 'is-invalid' : '' }}"  disabled/>

				@error('nro_sol')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>
        </div>

        <div class="row col-12">
			<div class="form-group col-3 offset-3">
                <x-label for="fec_sol">Fecha Solicitud</x-label>
                <input type="text" wire:model.defer="fec_sol" id="fec_sol" name="fec_sol" class="form-control form-control-sm text-center {{ $errors->has('fec_sol') ? 'is-invalid' : '' }}" readonly required/>

				@error('fec_sol')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>

			<div class="form-group col-3">
				<x-label for="num_sop">Documento</x-label>
				<input type="text" wire:model.defer="num_sop" id="num_sop" name="num_sop" class="form-control form-control-sm text-center {{ $errors->has('num_sop') ? 'is-invalid' : '' }}" maxlength="12" required/>

				@error('num_sop')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>
        </div>

        <div class="row col-12">
			<div class="form-group col-6 offset-3">
				<x-label for="cod_ger">Gerencia</x-label>

				<div wire:ignore>
					<x-select wire:model.defer="cod_ger" name="cod_ger" class="form-control-sm select2bs4 {{ $errors->has('cod_ger') ? 'is-invalid' : '' }}" style="pointer-events:none !important;" requied>
						<option value="">Seleccione...</option>
						@foreach ($this->Gerencia as $gerenciaItem)
							<option value="{{ $gerenciaItem->cod_ger }}" @selected(old('cod_ger', $solicitudTraspaso->cod_ger) == $gerenciaItem->cod_ger)>{{ $gerenciaItem->des_ger }}</option>
						@endforeach
					</x-select>

					@error('cod_ger')
						<span class="invalid-feedback" role="alert">
							{{ $message }}
						</span>
					@enderror

				</div>
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
                <input type="text" wire:model.defer="nro_ext" x-mask="99999" id="nro_ext" name="nro_ext" class="form-control form-control-sm text-center {{ $errors->has('nro_ext') ? 'is-invalid' : '' }}"  maxlength="20"/>

				@error('nro_ext')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>

			<div class="form-group col-7">
				<x-label for="concepto">Concepto</x-label>
				<input type="text" wire:model.defer="concepto" id="concepto" name="concepto" class="form-control form-control-sm {{ $errors->has('concepto') ? 'is-invalid' : '' }}"  maxlength="500" />

				@error('concepto')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>
        </div>

		<div class="row col-12">
			<div class="form-group col-10 offset-1">
				<x-label for="concepto">Justificación</x-label>
				<textarea wire:model.defer="justificacion" id="justificacion" name="justificacion" class="form-control {{ $errors->has('justificacion') ? 'is-invalid' : '' }}" maxlength="300" cols="50" rows="5" title="Indique Justificación" ></textarea>

				@error('justificacion')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>
        </div>

		<div class="row col-12">
			<div class="form-group col-3 offset-1">
				<x-label for="total">Total</x-label>
				<input type="text" wire:model.defer="total" x-mask:dynamic="$money($input, ',')" id="total" name="total" class="form-control form-control-sm text-right {{ $errors->has('total') ? 'is-invalid' : '' }}"  required readonly/>

				@error('total')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>
        </div>

		@if ($solicitudTraspaso->id)
			<div class="row col-12">
				<div class="form-group col-3 offset-1">
					<x-label for="estado">Estado:</x-label>
					<span name="estado">{{ $solicitudTraspaso->sta_reg->name }}</span>
				</div>
			</div>
		@endif

		<!-- Divisor -->
		<div class="row col-12">
            <div class="col-12">
                <h5 class="card-title text-secondary text-bold">Partidas Receptoras</h5>
            </div>

            <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
        </div>

		<div class="row col-12">
			<div class="form-group col-12">

				<div class="form-group row">
					<x-label for="partida" class="col-form-label col-2 text-right">Partida de Gasto</x-label>
					<div class="col-4">
						<div wire:ignore>
							<x-select wire:model.defer="partida" name="partida" class="form-control-sm select2bs4 {{ $errors->has('partida') ? 'is-invalid' : '' }}">
								<option value="">Seleccione...</option>
								@foreach ($this->PartidaPresupuestaria as $partidaItem)
									<option value="{{ $partidaItem->id }}" >{{ $partidaItem->cod_cta . ' - ' . $partidaItem->des_con }}</option>
								@endforeach
							</x-select>

							@error('partida')
								<span class="invalid-feedback" role="alert">
									{{ $message }}
								</span>
							@enderror
						</div>
					</div>
					<x-label for="monto" class="col-form-label col-1 text-right">Monto</x-label>
					<div class="col-2">
						<x-input x-mask:dynamic="$money($input, ',')" wire:model.defer="monto" name="monto" class="text-right money-mask" />
					</div>
					<div class="col-2">
						<button type="button" class="btn btn-sm btn-success text-bold" wire:click="agregarEstructura">Agregar</button>
					</div>
				</div>
			</div>
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
						<th style="width:50px;vertical-align:middle;"></th>
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
							<td class="text-center" style="vertical-align:middle;">
								<a href="#" onclick="event.preventDefault()" wire:click="eliminarEstructura({{ "'" . $item['cod_com'] . "'" }})">
									<i class="far fa-trash-alt text-danger" title="Eliminar"></i>
								</a>
							</td>
						</tr>
					@empty
						<tr>
							<td class="text-center" colspan="12">
								No existen registros
							</td>
						</tr>
					@endforelse
				</tbody>
			</table>
			<input type="text" name="estructuras" id="estructuras" style="display:none" readonly/>
		</div>
	</x-slot>

	<x-slot name="footer">
		<button type="submit" class="btn btn-sm btn-primary text-bold float-right">Guardar</button>
	</x-slot>

</x-card>

@push('scripts')
	<script type="text/javascript">
		$(document).ready(function () {
			$('.money-mask').keypress(function (e) {
				if (e.which != 8 && e.which != 0 && e.which != 44 && (e.which < 48 || e.which > 57)) {
					return false;
				}
			});
		});

		$(function () {
				$('.select2bs4').select2().on('change', function(event) {
                Livewire.emit('changeSelect', $(this).val(), event.target.id)
            });
		});

		/*
		$('#total').inputmask("(.999){+|1},00", { 
			alias: 'decimal', 
			digits: 2,
			digitsOptional: false, 
			allowMinus: false,
			numericInput: true, 
			radixPoint: ',',
			placeholder: '0,00', 
			defaultValue: '0,00'
		});
		*/

		/*
		Inputmask({
			alias          : 'decimal',
			digits         : 2,
			digitsOptional : false,
			allowMinus     : false,
			//numericInput   : true,
			groupSeparator : '.',
			radixPoint     : ',',
			placeholder    : '0,00',
			defaultValue   : '0,00',
			//insertMode:false,
			removeMaskOnSubmit: true,
		}).mask('#total');//.mask('#monto');
		*/

		window.livewire.on('estructura:act', param => {
			var detalle = JSON.stringify(param['estructuras']);
			$('#estructuras').val(detalle);
		});

		window.livewire.on('swal:alert', param => {
			Swal.fire({
				title : param['titulo'],
				text  : param['mensaje'],
				icon  : param['tipo'],
			})
		});

		window.livewire.on('partida', param => {
			$('#partida').val(param['partida']);
			$('#partida').triggerHandler('change');
		});
	</script>
@endpush