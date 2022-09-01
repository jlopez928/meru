@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-12">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('otrospagos.configuracion.conceptoservicio.index') }}">Página Principal</a></li>
					<li class="breadcrumb-item active text-bold">Mostrar Conceptos de Servicios</li>
				</ol>
			</div>
		</div>
	</div>
</section>
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
                <x-card >
                    <x-slot name="header">
                        <h3 class="card-title text-bold">Conceptos de Servicios</h3>
                    </x-slot>

                    <x-slot name="body">
                        <div class="row col-12">
                            <x-field class="text-center col-1 offset-1">
                                <x-label for="cod_con">Código</x-label>
                                <x-input readonly  id="cod_con" name="cod_con"  value="{{ old('total', $conceptoservicio->cod_con) }}"  class="text-center form-control-sm {{ $errors->has('cod_con') ? 'is-invalid' : '' }}" />
                                    <div class="invalid-feedback">
                                        @error('cod_con') {{ $message }} @enderror
                                    </div>
                            </x-field>
                            <x-field class="text-center col-7">
                                <x-label for="des_con">Descripción</x-label>
                                <x-input  readonly id="des_con" name="des_con"  value="{{ old('total', $conceptoservicio->des_con) }}"  class="text-left form-control-sm {{ $errors->has('des_con') ? 'is-invalid' : '' }}" />
                                    <div class="invalid-feedback">
                                        @error('des_con') {{ $message }} @enderror
                                    </div>
                            </x-field>
                            <x-field class="text-center col-2 ">
                                <x-label for="sta_reg">Estado</x-label>
                                <x-select  readonly  style="pointer-events: none"  name="sta_reg" class="text-center  form-control-sm{{ $errors->has('status') ? 'is-invalid' : '' }}">
                                    <option value="{{ old('sta_reg', $conceptoservicio->sta_reg) == '0' ? '0' : '1' }}" selected>{{ old('status', $conceptoservicio->sta_reg) == '0' ? 'INACTIVO' : 'ACTIVO' }}</option>
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
                            <table class="table table-bordered table-sm text-center" >
                                <thead>
                                    <tr class="table-primary">
                                        <th style="width:50px;vertical-align:middle;">Pa</th>
                                        <th style="width:50px;vertical-align:middle;">Gn</th>
                                        <th style="width:50px;vertical-align:middle;">Esp.</th>
                                        <th style="width:50px;vertical-align:middle;">Sub.Esp</th>
                                        <th style="width:350px;vertical-align:middle;">Descripción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($conceptoservicio->opconceptosdet as $item)
                                        <tr>
                                            <td class="text-center">
                                                {{ $item['cod_par']}}
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
                                                {{ $item->partidaPresupuestaria->des_con }}
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
