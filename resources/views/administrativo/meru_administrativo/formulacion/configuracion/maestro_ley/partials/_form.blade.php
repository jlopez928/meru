<x-card x-data="handler()">
	<x-slot name="header">
		<h3 class="card-title text-bold">Partida Presupuestaria</h3>
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
				<x-input class="text-center form-control-sm" name="ano_pro" value="{{ old('ano_pro', $maestroLey->ano_pro) }}" required readonly/>
			</div>

			<div class="form-group col-4">
				<x-label for="centro_costo">Centro de Costo</x-label>
				<select name="centro_costo" id="centro_costo" class="form-control form-control-sm select2bs4 {{ $errors->has('centro_costo') ? 'is-invalid' : '' }}" required {{ $maestroLey->id ? 'disabled' : '' }}>
					<option value="">Seleccione...</option>
					@foreach ($centrosCosto as $cecoItem)
						<option value="{{ $cecoItem->cod_cencosto }}" @selected(old('centro_costo', $maestroLey->centroCosto->cod_cencosto) == $cecoItem->cod_cencosto)>{{ $cecoItem->cod_cencosto . ' - ' . $cecoItem->des_con }}</option>
					@endforeach
				</select>

				@error('centro_costo')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>

			<div class="form-group col-4">
				<x-label for="partida_presupuestaria">Partida de Gastos</x-label>
				<select name="partida_presupuestaria" id="partida_presupuestaria" class="form-control form-control-sm select2bs4 {{ $errors->has('partida_presupuestaria') ? 'is-invalid' : '' }}" required {{ $maestroLey->id ? 'disabled' : '' }}>
					<option value="">Seleccione...</option>
					@foreach ($partidas as $partidaItem)
						<option value="{{ $partidaItem->cod_cta }}" @selected(old('partida_presupuestaria', $maestroLey->partidaPresupuestaria->cod_cta) == $partidaItem->cod_cta)>{{ $partidaItem->cod_cta . ' - ' . $partidaItem->des_con }}</option>
					@endforeach
				</select>

				@error('partida_presupuestaria')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>
		</div>

		<div class="row col-12">
			<div class="form-group col-4 offset-2">
				<x-label for="estructura">Estructura presupuestaria</x-label>
				<x-input name="estructura" class="text-center form-control-sm {{ $errors->has('estructura') ? 'is-invalid' : '' }}" value="{{ $maestroLey->cod_com }}" required readonly/>

				@error('estructura')
					<span class="invalid-feedback" role="alert">
						{{ $message }}
					</span>
				@enderror
			</div>

			<div class="form-group col-4 text-center">
				<x-label for="exceder_pago">¿Permitir pagar sin tener suficiente Causado?<br>(En Dualidad)</x-label><br>
				<div class="custom-control custom-switch">
					<input type="checkbox" class="custom-control-input" id="exceder_pago" name="exceder_pago" value="SI" @checked(old('exceder_pago', $maestroLey->exc_pag) == 'SI')>
					<label class="custom-control-label text-sm" for="exceder_pago">Permitir</label>
				</div>
				
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
		<button type="submit" class="btn btn-sm btn-primary text-bold float-right">Guardar</button>
	</x-slot>

</x-card>

<script type="text/javascript">
	function handler() {
		return {
			init() {
				this.generarEstructura();
			},
			generarEstructura() {
				let ceco       = $('#centro_costo').val();
				let partida    = $('#partida_presupuestaria').val();
				let estructura = (ceco != '' && partida != '') ? ceco + partida.substr(1) : '';
		    	$('#estructura').val(estructura);
			}
		}
	}
</script>