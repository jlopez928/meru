@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-12">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('presupuesto.causado.actacontobraserv.index') }}">Actas a Contratos de Obras/Servicios</a></li>
					<li class="breadcrumb-item active text-bold">  @if ($causar==1) Causar @elseif ($aprobar==1) Aprobar  @elseif ($reversar==1)  Reversar @else Consultar @endif</li>
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
                                @include('administrativo/meru_administrativo/presupuesto/causado/actacontobraserv/partials/_acta')
                            </div>
                            <div class="tab-pane fade" id="detalle" role="tabpanel" aria-labelledby="detalle-tab">
                                @include('administrativo/meru_administrativo/presupuesto/causado/actacontobraserv/partials/_detalle')
                            </div>
                            <div class="tab-pane fade" id="comprobante" role="tabpanel" aria-labelledby="comprobante-tab">
                                @include('administrativo/meru_administrativo/presupuesto/causado/actacontobraserv/partials/_comprobante')
                            </div>
                        </div>
                        <div class="form-group">
					</x-slot>

					<x-slot name="footer">
                        @if ($causar==1)
                            <div class="col-sm-1 float-right">
                                <x-label >&nbsp</x-label>
                                <x-button class="btn btn-sm btn-primary text-bold float-right" href="{{ route('presupuesto.causado.actacontobraserv.causar_ejecutar',$encnotaentrega->id) }}" title="Causar"> Causar</x-button>
                            </div>
                        @endif
                        @if ($aprobar==1)
                            <div class="col-sm-1 float-right">
                                <x-label >&nbsp</x-label>
                                <x-button class="btn btn-sm btn-primary text-bold float-right" href="{{ route('presupuesto.causado.actacontobraserv.aprobar_ejecutar',$encnotaentrega->id) }}" title="Aprobar"> Aprobar</x-button>
                            </div>
                        @endif
                        @if ($reversar==1)
                        <div class="col-sm-1 float-right">
                            <x-label >&nbsp</x-label>
                            <x-button class="btn btn-sm btn-primary text-bold float-right" href="{{ route('presupuesto.causado.actacontobraserv.reversar_ejecutar',$encnotaentrega->id) }}" title="Aprobar"> Reversar</x-button>
                        </div>
                    @endif

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
