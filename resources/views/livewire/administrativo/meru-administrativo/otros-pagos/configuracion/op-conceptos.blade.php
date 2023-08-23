
<x-card wire:init="cargar_emit">
	<x-slot name="header">
		<h3 class="card-title text-bold">Conceptos de Servicios</h3>
	</x-slot>

	<x-slot name="body">
		<div class="row col-12">
            <x-field class="text-center col-1 offset-1">
                <x-label for="cod_con">Código</x-label>
                <x-input readonly  id="cod_con" name="cod_con" wire:model.defer="cod_con"  class="text-center form-control-sm {{ $errors->has('cod_con') ? 'is-invalid' : '' }}" />
                    <div class="invalid-feedback">
                        @error('cod_con') {{ $message }} @enderror
                    </div>
            </x-field>
			<x-field class="text-center col-7">
                <x-label for="des_con">Descripción</x-label>
                <x-input   id="des_con" name="des_con" wire:model.defer="des_con"  class="text-left form-control-sm {{ $errors->has('des_con') ? 'is-invalid' : '' }}" />
                    <div class="invalid-feedback">
                        @error('des_con') {{ $message }} @enderror
                    </div>
            </x-field>
            <x-field class="text-center col-2 ">
                <x-label for="sta_reg">Estado</x-label>
                <x-select   name="sta_reg" class="text-center form-control-sm{{ $errors->has('sta_reg') ? 'is-invalid' : '' }}">
                    <option value="{{ old('sta_reg', $conceptoservicio->sta_reg) == '0' ? '0' : '1' }}" selected>{{ old('sta_reg', $conceptoservicio->sta_reg) == '0' ? 'INACTIVO' : 'ACTIVO' }}</option>
                    <option value="{{ old('sta_reg', $conceptoservicio->sta_reg) == '0' ? '1' : '0'}}"> {{ old('sta_reg', $conceptoservicio->sta_reg) == '0' ? 'ACTIVO' : 'INACTIVO' }}</option>
             </x-select>
                <div class="invalid-feedback">
                    @error('sta_reg') {{ $message }} @enderror
                </div>
            </x-field>
        </div>
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
					<div class="col-8">
						<div wire:ignore>
							<x-select wire:model.defer="partidapresupuestaria" id="partida" name="partida" class="form-control-sm select2bs4 {{ $errors->has('partida') ? 'is-invalid' : '' }} required">
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
					<div class="col-2">
						<button type="button" class="btn btn-sm btn-success text-bold" wire:click="agregarPartida">Agregar</button>
					</div>
				</div>
			</div>
        </div>
		<div class="row col-12">
			<table class="table table-bordered table-sm text-center" >
				<thead>
					<tr class="table-primary">
						<th style="width:50px;vertical-align:middle;">Pa</th>
						<th style="width:50px;vertical-align:middle;">Gn</th>
						<th style="width:50px;vertical-align:middle;">Esp.</th>
						<th style="width:50px;vertical-align:middle;">Sub.Esp</th>
						<th style="width:350px;vertical-align:middle;">Descripción</th>
                        <th style="width:30px;vertical-align:middle;"></th>
					</tr>
				</thead>
				<tbody>
					@forelse ($estructuras as $item)
						<tr>
							<td class="text-center">
								{{ $item['cod_par'] }}
							</td>
							<td class="text-center">
								{{ $item['cod_gen'] }}
							</td>
							<td class="text-center">
								{{ $item['cod_esp'] }}
							</td>
							<td class="text-center">
								{{ $item['cod_sub'] }}
							</td>
							<td class="text-center">
								{{$item['des_con']}}
							</td>
                            <td class="text-center" style="vertical-align:middle;">
                                <a href="#" onclick="event.preventDefault()" wire:click="eliminarEstructura({{ "'" . $item['estructura'] . "'" }})">
                                    <i class="fa fa-trash text-danger" title="Eliminar"></i>
                                </a>
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
			<input type="hidden"  name="estructuras" id="estructuras" />
		</div>
	</x-slot>
	<x-slot name="footer">
		<button type="submit" class="btn btn-sm btn-primary text-bold float-right">Guardar</button>
	</x-slot>
</x-card>
@push('scripts')
	<script type="text/javascript">
		window.livewire.on('agregar', param => {
			var detalle = JSON.stringify(param['estructuras']);
			$("#estructuras").val(detalle);
        });
        window.livewire.on('swal:alert', param => {
            Swal.fire({title:param['titulo'],
            text:param['mensaje'],
            icon:param['tipo']
            })
        });

	</script>

@endpush
