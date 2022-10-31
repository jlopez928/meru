<x-card wire:init="inicializar" x-data="">
	<x-slot name="header">
		<h3 class="card-title text-bold">Crédito Adicional</h3>
	</x-slot>

	<x-slot name="body">

		<x-divisor class="col-10 offset-1" titulo="Datos Básicos"/>

        <div class="row col-12">
            <div class="form-group col-2 offset-2">
                <x-label for="ano_pro">Año</x-label>
                <x-input type="text" name="ano_pro"  wire:model.defer="anoPro" class="form-control form-control-sm text-center" readonly />
            </div>
        
            <div class="form-group col-2">
                <x-label for="xnro_mod">Código</x-label>
                <x-input type="text" name="xnro_mod"  wire:model.defer="xnroMod" class="form-control form-control-sm text-center" readonly />
            </div>
        
            <div class="form-group col-2">
                <x-label for="nro_mod">Número</x-label>
                <x-input type="text" name="nro_mod" wire:model.defer="nroMod" class="form-control form-control-sm text-center" readonly />
            </div>
        
            <div class="form-group col-2">
                <x-label for="num_doc">Solicitud</x-label>
                <x-input type="text" name="num_doc" wire:model.defer="numDoc" x-mask="99999" class="form-control form-control-sm text-center {{ $errors->has('num_doc') ? 'is-invalid' : '' }}" />

                @error('num_doc')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
            </div>
        </div>

		<x-divisor class="col-10 offset-1" titulo="Fechas"/>

        <div class="row col-12">
            <div class="form-group col-3 offset-3">
                <x-label for="fec_tra">Fecha Transacción</x-label>
                <x-input type="text" name="fec_tra" wire:model.defer="fecTra" class="form-control form-control-sm text-center" readonly />
            </div>

            <div class="form-group col-3">
                <x-label for="fec_sta">Fecha Estado</x-label>
                <x-input type="text" name="fec_sta" wire:model.defer="fecSta" class="form-control form-control-sm text-center" readonly />
            </div>
        </div>

        <x-divisor class="col-10 offset-1" titulo="Otros Datos"/>

        <div class="row col-12">
            <div class="form-group col-8 offset-2">
                <x-label for="concepto">Concepto</x-label>
                <x-input type="text" name="concepto" wire:model.defer="concepto" class="form-control-sm {{ $errors->has('concepto') ? 'is-invalid' : '' }}"  maxlength="500" />

				@error('concepto')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
            </div>
        </div>

        <div class="row col-12">
            <div class="form-group col-8 offset-2">
                <x-label for="justificacion">Justificación</x-label>
                <textarea id="justificacion" name="justificacion" wire:model.defer="justificacion" class="form-control {{ $errors->has('justificacion') ? 'is-invalid' : '' }}" maxlength="300" cols="50" rows="5" title="Indique Justificación" >{{ $creditoAdicional->justificacion }}</textarea>

				@error('justificacion')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
            </div>
        </div>

		@if ($creditoAdicional->id)
            <div class="row col-12">
                <div class="form-group col-3 offset-2">
                    <x-label for="estado">Estado:</x-label>
                    <span name="estado">{{ Str::replace('_', ' ', $creditoAdicional->sta_reg->name) }}</span>
                </div>
            </div>
		@endif

		<div class="card card-outline">
			<div class="card-header">
				<h3 class="card-title text-bold">Partidas Receptoras</h3>
			</div>
		
			<div class="card-body">
				<div class="row col-12">
					<div class="col-1">
						<x-label for="tip_cod_rec">Tp</x-label>
						<x-input name="tip_cod_rec" class="text-center" wire:model.defer="receptora.tip_cod" x-mask="99" maxlength="2"/>
					</div>
					<div class="col-1">
						<x-label for="cod_pryacc_rec">P/A</x-label>
						<x-input name="cod_pryacc_rec" class="text-center" wire:model.defer="receptora.cod_pryacc" x-mask="99" maxlength="2"/>
					</div>
					<div class="col-1">
						<x-label for="cod_obj_rec">Obj</x-label>
						<x-input name="cod_obj_rec" class="text-center" wire:model.defer="receptora.cod_obj" x-mask="99" maxlength="2"/>
					</div>
					<div class="col-1">
						<x-label for="gerencia_rec">Gcia</x-label>
						<x-input name="gerencia_rec" class="text-center" wire:model.defer="receptora.gerencia" x-mask="99" maxlength="2"/>
					</div>
					<div class="col-1">
						<x-label for="unidad_rec">U.Ejec.</x-label>
						<x-input name="unidad_rec" class="text-center" wire:model.defer="receptora.unidad" x-mask="99" maxlength="2"/>
					</div>
					<div class="col-1">
						<x-label for="cod_par_rec">Pa</x-label>
						<x-input name="cod_par_rec" class="text-center" wire:model.defer="receptora.cod_par" x-mask="99" maxlength="2"/>
					</div>
					<div class="col-1">
						<x-label for="cod_gen_rec">Gn</x-label>
						<x-input name="cod_gen_rec" class="text-center" wire:model.defer="receptora.cod_gen" x-mask="99" maxlength="2"/>
					</div>
					<div class="col-1">
						<x-label for="cod_esp_rec">Esp.</x-label>
						<x-input name="cod_esp_rec" class="text-center" wire:model.defer="receptora.cod_esp" x-mask="99" maxlength="2"/>
					</div>
					<div class="col-1">
						<x-label for="cod_sub_rec">Sub.Esp</x-label>
						<x-input name="cod_sub_rec" class="text-center" wire:model.defer="receptora.cod_sub" x-mask="99" maxlength="2"/>
					</div>
					<div class="col-2">
						<x-label for="monto_rec">Monto</x-label>
						<x-input name="monto_rec" class="text-right money-mask" x-mask:dynamic="$money($input, ',')" wire:model.defer="montoRec"/>
					</div>
					<div class="col-1">
						<x-label for="btnAgregarRec" style="color:#FFF;">_</x-label>
						<button type="button" name="btnAgregarRec" class="btn btn-sm btn-success text-bold" wire:click="agregarEstructura">Agregar</button>
					</div>
				</div>

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
								<th style="width:100px;vertical-align:middle;">Monto Disp.</th>
								<th style="width:100px;vertical-align:middle;">Monto</th>
								<th style="width:50px;vertical-align:middle;">
									@if(!empty($estructurasReceptoras))
										<a href="#" onclick="event.preventDefault()" wire:click="eliminarTodasEstructuras" style="font-size:12px;">
											<i class="far fa-trash-alt text-danger" title="Eliminar Todas"></i>
										</a>
									@endif
								</th>
							</tr>
						</thead>
						<tbody>
							@forelse ($estructurasReceptoras as $item)
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
									<td class="text-center" style="vertical-align:middle;">
										<a href="#" onclick="event.preventDefault()" wire:click="eliminarEstructura({{ "'" . $item['cod_com'] . "'" }})">
											<i class="far fa-trash-alt text-danger" title="Eliminar"></i>
										</a>
									</td>
								</tr>
							@empty
								<tr>
									<td class="text-center" colspan="13">
										No existen registros
									</td>
								</tr>
							@endforelse
						</tbody>
					</table>
					<input type="text" name="estructurasReceptoras" id="estructurasReceptoras" style="display:none" readonly/>
				</div>

				<div class="row col-12" style="paddin-right:0px !important;">
					<div class="form-group row col-12">
						<x-label for="total_rec" class="col-form-label col-2 offset-8 text-right">Total:</x-label>
						<div class="col-2">
							<x-input name="total_rec" class="text-right {{ $errors->has('total_rec') ? 'is-invalid' : '' }}" wire:model.defer="totRec" readonly/>

							@error('total_rec')
								<span class="invalid-feedback" role="alert">
									{{ $message }}
								</span>
							@enderror

							@error('estructurasReceptoras')
								<span class="invalid-feedback" role="alert">
									{{ $message }}
								</span>
							@enderror
						</div>
					</div>
				</div>
			</div>
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

		window.livewire.on('estructura:act', param => {
			var detalle = JSON.stringify(param['estructuras']);
			$('#estructurasReceptoras').val(detalle);
		});

		window.livewire.on('swal:alert', param => {
			Swal.fire({
				title : param['titulo'],
				html  : param['mensaje'],
				icon  : param['tipo'],
				confirmButtonText : 'Aceptar'
			})
		});
	</script>
@endpush