@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-12">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('contratos.proceso.actacontratobraserv.index') }}">Actas a Contratos de Obras/Servicios</a></li>
					<li class="breadcrumb-item active text-bold">@if($valor=='modificar') Modificar Entrega @else Reimprimir Acta  @endif</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
                {{--  <x-form method="{{ $accion =='create' ?  'post' : 'put'   }}" action="{{ $accion == 'create' ? route('otrospagos.proceso.certificacionservicio.store') :
                                             route('otrospagos.proceso.certificacionservicio.update', $certificacionservicio->id) }}">  --}}
                <x-form method="{{ $valor=='modificar' ? 'put' : 'get' }}" action="{{ $valor == 'modificar' ?
                            route('contratos.proceso.actacontratobraserv.update', $encnotaentrega->id) :
                            route('contratos.proceso.actacontratobraserv.reimprimirentrega', $encnotaentrega->id) }}" target="_blank">
                    <x-card>
                        <x-slot name="header">
                            <h3 class="card-title text-bold">Actas a Contratos de Obras/Servicios</h3>
                        </x-slot>

                        {{-- Tab de la pantalla --}}
                        <x-slot name="body">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="acta-tab" data-toggle="tab" data-target="#acta" type="button" role="tab" aria-controls="identificacion" aria-selected="true">Acta de Servicio</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                <button class="nav-link" id="detalle-tab" data-toggle="tab" data-target="#detalle" type="button" role="tab" aria-controls="otros" aria-selected="false">Detalle de Acta de Servicio</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                <button class="nav-link" id="comprobante-tab" data-toggle="tab" data-target="#comprobante" type="button" role="tab" aria-controls="situacion-financiera" aria-selected="false">Comprobantes de Nota de Entrega</button>
                                </li>
                            </ul>

                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="acta" role="tabpanel" aria-labelledby="acta-tab">
                                     @include('administrativo/meru_administrativo/contratos/proceso/actacontratoobraserv/partials_show/_actamodificar')
                                </div>
                                <div class="tab-pane fade" id="detalle" role="tabpanel" aria-labelledby="detalle-tab">
                                    @include('administrativo/meru_administrativo/contratos/proceso/actacontratoobraserv/partials_show/_detalle')
                                </div>
                                <div class="tab-pane fade" id="comprobante" role="tabpanel" aria-labelledby="comprobante-tab">
                                    @include('administrativo/meru_administrativo/contratos/proceso/actacontratoobraserv/partials_show/_comprobante')
                                </div>
                            </div>
                            <div class="form-group">
                        </x-slot>
                        <x-slot name="footer">
                            @if($valor=='modificar')
                                <x-input id="modificar" name="terminar" type="submit" class="btn btn-sm btn-primary text-bold float-right"  value="Modificar Entrega" />
                           @else
                                <x-input id="modificar" name="reimprimir" target="_blank" type="submit" class="btn btn-sm btn-primary text-bold float-right"  value="Reimprimir Entrega" />
                            @endif
                        </x-slot>
                    </x-card>
                </x-form>
			</div>
		</div>
	</div>
</section>

@endsection

@section('sidebar')
	@include('layouts.sidebar')
@endsection
